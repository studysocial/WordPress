<?php
use Aws\S3\S3Client;

class Amazon_S3_Uploader extends AWS_Plugin_Base {
	private $aws, $s3client;

	const SETTINGS_KEY = 'wp_s3_uploader';

	function __construct ($plugin_file_path, $aws) {
		parent::__construct($plugin_file_path);

		$this->aws = $aws;

		add_action('aws_admin_menu', array($this, 'admin_menu'));

		$this->plugin_title = __('Amazon S3 Uploader', 'wp_s3');
		$this->plugin_menu_title = __('S3 Uploader', 'wp_s3');

		add_action('wp_ajax_wp_s3-create-bucket', array($this, 'ajax_create_bucket'));

		add_filter('wp_get_attachment_url', array($this, 'wp_get_attachment_url'), 9, 2);
		//add_filter('wp_generate_attachment_metadata', array($this, 'wp_generate_attachment_metadata'), 20, 2);
		//add_filter('delete_attachment', array($this, 'delete_attachment'), 20);
		
		if (is_admin() && !empty($_FILES) && (!empty($_POST['html-upload']) || $_SERVER['REQUEST_URI'] == '/wp-admin/async-upload.php')) {
			$file = $_FILES['async-upload'];
			if (!is_array($file_info = $this->file_info($file)))
				die('That file is not allowed: ' . $file_info . '. Hit back to try again.');
			$id = $this->file_upload($file_info, intval($_POST['post_id']));
			
			if ($_POST['action'] == 'upload-attachment') {
				$data = wp_prepare_attachment_for_js($id);
				if (substr($data['url'], 0, 2) == '//')
					$data['url'] = 'http:' . $data['url'];

				if (substr($data['url'], 0, 5) == '/tmp/') {
					global $wpdb;
					$post = mysql_fetch_array(mysql_query("SELECT guid FROM $wpdb->posts WHERE ID = '$id' LIMIT 1"));
					$data['url'] = $post['guid'] . '?test2';
				}

				die(json_encode(array(
					'success' => true,
					'data' => $data,
				)));
			}
			
			if (empty($_POST['html-upload']))
				die('' . $id);
			
			header("Location: upload.php");
			die('');
		}
	}

	function file_info ($file) {
		if (!@is_uploaded_file($file['tmp_name']))
			return "something went wrong in the upload process";

		if (!($file['size'] > 0))
			return "the file is empty. Please upload something more substantial";

		$file_info = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], false);
		if (!($file_info['type'] && $file_info['ext']) && !current_user_can('unfiltered_upload'))
			return "the file type is not permitted for security reasons";

		$file['type'] = $file_info['type'];
		$file['ext'] = $file_info['ext'];
		return $file;
	}

	function file_upload ($file, $post_id = 0) {
		global $wpdb;
		$save_name = preg_replace('/[^0-9a-zA-Z-_.]/', '', basename($file['name'], '.' . $file['ext']));
		rename($file['tmp_name'], '/tmp/' . $fname = $save_name . '.' . $file['ext']);
		$attachment = array(
			'post_mime_type' => $file['type'],
			'guid' => '/tmp/' . $fname,
			'post_parent' => $post_id,
			'post_title' => $file['name'],
			'post_content' => '',
		);

		//setup URLs
		$s3client = $this->get_s3client();
		$bucket = $this->get_setting('bucket');
		$prefix = $this->getPrefix();
		$full_prefix = '//' . $bucket . '.s3.amazonaws.com/' . $prefix;

		//Insert Attachment
		if (is_wp_error($id = wp_insert_attachment($attachment, $fname, $post_id)))
			return $id;

		//Generate metadata
		if (!function_exists('wp_generate_attachment_metadata'))
			require (ABSPATH . 'wp-admin/includes/image.php'); //required for wp_generate_attachment_metadata

		$data = wp_generate_attachment_metadata($id, '/tmp/' . $fname);

		$acl = apply_filters('wps3_upload_acl', 'public-read', $file['type'], $data, $post_id, $this); // Old naming convention, will be deprecated soon
		$acl = apply_filters('wp_s3_upload_acl', $acl, $data, $post_id);

		$args = array(
			'Bucket' => $bucket,
			'Key' => $prefix . $save_name . '.' . $file['ext'],
			'SourceFile' => '/tmp/' . $fname,
			'ACL' => $acl,
		);

		if ($this->get_setting('expires')) // If far future expiration checked (10 years)
			$args['Expires'] = date('D, d M Y H:i:s O', time() + 315360000);

		$data['file'] = $full_prefix . $fname;
		update_attached_file($id, $data['file']);
		$wpdb->update($wpdb->posts,
			array( 'guid' => $data['file'] ),
			array( 'ID' => $id ),
			'%s',
			'%d'
		);

		$images = array(array()); //The middle array() is important. It sends the first image (stored in $args)
		if (isset($data['thumb']) && $data['thumb']) {
			$images[] = array(
				'Key' => $prefix . $data['thumb'],
				'SourceFile' => '/tmp/' . $data['thumb'],
			);
		} elseif (!empty($data['sizes'])) {
			foreach ($data['sizes'] as $key => $size) {
				$images[] = array(
					'Key' => $prefix . $size['file'],
					'SourceFile' => '/tmp/' . $size['file'],
				);
			}
		}

		foreach ($images as $image) {
			try {
				$s3client->putObject($image = array_merge($args, $image));
			} catch (Exception $e) {
				error_log('Error uploading ' . $args['SourceFile'] . ' to S3: ' . $e->getMessage());
			}
		}

		wp_update_attachment_metadata($id, $data);
		
		return $id;
	}

	function get_setting ($key) {
		$settings = $this->get_settings();

		// If legacy setting set, migrate settings
		if (isset($settings['wp-uploads']) && $settings['wp-uploads'] && in_array($key, array('copy-to-s3', 'serve-from-s3')))
			return '1';

		// Default object prefix
		if ('object-prefix' == $key && !isset($settings['object-prefix'])) {
			$uploads = wp_upload_dir();
			$parts = parse_url($uploads['baseurl']);
			return substr($parts['path'], 1) . '/';
		}

		return parent::get_setting($key);
	}

	function delete_attachment ($post_id) {
		if (!$this->is_plugin_setup())
			return;

		$backup_sizes = get_post_meta($post_id, '_wp_attachment_backup_sizes', true);

		$intermediate_sizes = array();
		foreach (get_intermediate_image_sizes() as $size) {
			if ($intermediate = image_get_intermediate_size($post_id, $size))
				$intermediate_sizes[] = $intermediate;
		}

		if (!($s3object = $this->get_attachment_s3_info($post_id)))
			return;

		$amazon_path = dirname($s3object['key']);
		$objects = array();

		// remove intermediate and backup images if there are any
		foreach ($intermediate_sizes as $intermediate) {
			$objects[] = array(
				'Key' => path_join($amazon_path, $intermediate['file'])
		   );
		}

		if (is_array($backup_sizes)) {
			foreach ($backup_sizes as $size) {
				$objects[] = array(
					'Key' => path_join($amazon_path, $del_file)
				);
			}
		}

		$objects[] = array(
			'Key' => $s3object['key']
		);

		try {
			$this->get_s3client()->deleteObjects(array(
				'Bucket' => $s3object['bucket'],
				'Objects' => $objects,
			));
		} catch (Exception $e) {
			error_log('Error removing files from S3: ' . $e->getMessage());
			return;
		}
	}

	function get_object_version_string($post_id) {
		$date_format = get_option('uploads_use_yearmonth_folders') ? 'dHis' : 'YmdHis';
		$time = $this->get_attachment_folder_time($post_id);

		return apply_filters('wp_s3_get_object_version_string', date($date_format, $time) . '/');
	}

	// Media files attached to a post use the post's date 
	// to determine the folder path they are placed in
	function get_attachment_folder_time($post_id) {
		$time = current_time('timestamp');

		if (!($attach = get_post($post_id)) || !($parent = $attach->post_parent) || !($post = get_post($parent)) || !(substr($post->post_date_gmt, 0, 4) > 0))
			return $time;

		return strtotime($post->post_date_gmt . ' +0000');
	}

	/* For compatibility with the old version */
	function get_attachment_s3_info( $post_id ) {
		return get_post_meta( $post_id, 'amazonS3_info', true );
	}

	function wp_get_attachment_url($url, $post_id) {
		$s3_url = strstr($url, '///');
		if ($s3_url)
			return substr($s3_url, 1);

		return ($new_url = $this->get_attachment_url($post_id)) ? $new_url : $url;
	}

	function is_plugin_setup() {
		return (bool) $this->get_setting('bucket') && !is_wp_error($this->aws->get_client());
	}

	function get_attachment_url($post_id) {
		if (!$this->get_setting('serve-from-s3') || !($s3object = $this->get_attachment_s3_info($post_id)))
			return false;

		$domain_bucket = (is_ssl() || $this->get_setting('force-ssl')) ? 'https://s3.amazonaws.com/' . $s3object['bucket'] : 'http://' . $s3object['bucket'] . '.s3.amazonaws.com';
		return apply_filters('wp_s3_get_attachment_url', $domain_bucket . '/' . $s3object['key'], $s3object, $post_id);
	}

	function verify_ajax_request() {
		if (!is_admin() || !wp_verify_nonce($_POST['_nonce'], $_POST['action']))
			wp_die(__('Cheatin&#8217; eh?', 'wp_s3'));

		if (!current_user_can('manage_options'))
			wp_die(__('You do not have sufficient permissions to access this page.', 'wp_s3'));
	}

	function ajax_create_bucket() {
		$this->verify_ajax_request();

		if (!isset($_POST['bucket_name']) || !$_POST['bucket_name'])
			wp_die(__('No bucket name provided.', 'wp_s3'));

		$result = $this->create_bucket($_POST['bucket_name']);
		exit(json_encode(is_wp_error($result) ? array('error' => $result->get_error_message()) : array('success' => '1', '_nonce' => wp_create_nonce('wp_s3-create-bucket'))));
	}

	function create_bucket($bucket_name) {
		try {
			$this->get_s3client()->createBucket(array('Bucket' => $bucket_name));
		} catch (Exception $e) {
			return new WP_Error('exception', $e->getMessage());
		}

		return true;
	}

	function admin_menu($aws) {
		$hook_suffix = $aws->add_page($this->plugin_title, $this->plugin_menu_title, 'manage_options', $this->plugin_slug, array($this, 'render_page'));
		add_action('load-' . $hook_suffix, array($this, 'plugin_load'));
	}

	function get_s3client() {
		if (is_null($this->s3client))
			$this->s3client = $this->aws->get_client()->get('s3');

		return $this->s3client;
	}

	function get_buckets() {
		try {
			$result = $this->get_s3client()->listBuckets();
		} catch (Exception $e) {
			return new WP_Error('exception', $e->getMessage());
		}

		return $result['Buckets'];
	}

	function plugin_load() {
		$src = plugins_url('assets/css/styles.css', $this->plugin_file_path);
		wp_enqueue_style('wp_s3-styles', $src, array(), $this->get_installed_version());

		$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

		$src = plugins_url('assets/js/script' . $suffix . '.js', $this->plugin_file_path);
		wp_enqueue_script('wp_s3-script', $src, array('jquery'), $this->get_installed_version(), true);
		
		wp_localize_script('wp_s3-script', 'wp_s3_i18n', array(
			'create_bucket_prompt'  => __('Bucket Name:', 'wp_s3'),
			'create_bucket_error'	=> __('Error creating bucket: ', 'wp_s3'),
			'create_bucket_nonce'	=> wp_create_nonce('wp_s3-create-bucket')
		));

		$this->handle_post_request();
	}

	function handle_post_request() {
		if (empty($_POST['action']) || $_POST['action'] != 'save')
			return;

		if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'wp_s3-save-settings'))
			die(__("Cheatin' eh?", 'amazon-web-services'));

		$this->set_settings(array());

		$post_vars = array('bucket', 'virtual-host', 'expires', 'permissions', 'cloudfront', 'object-prefix', 'copy-to-s3', 'serve-from-s3', 'remove-local-file', 'force-ssl', 'hidpi-images', 'object-versioning');
		foreach ($post_vars as $var) {
			if (!isset($_POST[$var]))
				continue;

			$this->set_setting($var, $_POST[$var]);
		}

		$this->save_settings();

		wp_redirect('admin.php?page=' . $this->plugin_slug . '&updated=1');
		exit;
	}

	function render_page() {
		$this->aws->render_view('header', array('page_title' => $this->plugin_title));
		$aws_client = $this->aws->get_client();

		is_wp_error($aws_client) ? $this->render_view('error', array('error' => $aws_client)) : $this->render_view('settings');
		$this->aws->render_view('footer');
	}

	function get_dynamic_prefix($time = null) {
		$uploads = wp_upload_dir($time);
		return str_replace($this->get_base_upload_path(), '', $uploads['path']);
	}

	// Without the multisite subdirectory
	function get_base_upload_path() {	
		if (defined('UPLOADS') && ! (is_multisite() && get_site_option('ms_files_rewriting')))
			return ABSPATH . UPLOADS;

		$upload_path = trim(get_option('upload_path'));

		if (empty($upload_path) || 'wp-content/uploads' == $upload_path)
			return WP_CONTENT_DIR . '/uploads';
		
		return strpos($upload_path, ABSPATH) !== 0 ? path_join(ABSPATH, $upload_path) : $upload_path;
	}

	function getPrefix() {
		$prefix = ltrim(trailingslashit($this->get_setting('object-prefix')), '/')
				. ltrim(trailingslashit($this->get_dynamic_prefix(date('Y/m'))), '/');

		if ($this->get_setting('object-versioning'))
			$prefix .= $this->get_object_version_string($post_id);

		return $prefix;
	}

}
