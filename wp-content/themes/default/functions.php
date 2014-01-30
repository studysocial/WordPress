<?php

require('classes/nav.php');

//remove WordPress's bullshit formatting techniques
remove_filter('the_content', 'wpautop');
add_filter('show_admin_bar', '__return_false');
add_action('wp_enqueue_scripts', 'init');

function init() {
	wp_enqueue_script('jquery');
}

//Images and CSS
function b($path = '') {
	echo BUCKET_DIR . $path;
}

function i($image = '') {
	echo IMAGE_DIR . $image;
}

function get_path($remove_blog = true) {
	global $current_blog;

	$url = parse_url($_SERVER['REQUEST_URI']);
	$path = trim($url['path'], '/');

	if ($remove_blog && isset($current_blog, $current_blog->path))
		$path = substr(str_replace('./', '/', $url['path']), strlen($current_blog->path));

	return $path;
}

//To search by an array of IDs, send the array to $post_title
function make_query($post_title, $meta_keys = false, $limit = false) {
	global $wpdb;
	if (is_array($post_title)) {
		$post_ids = "'" . implode("','", $post_title) . "'";
	} else {
		$post_ids = "SELECT ID
			FROM {$wpdb->prefix}posts
			WHERE post_title LIKE '{$post_title}%'
				AND post_status != 'inherit'
				AND post_status != 'trash'
			ORDER BY post_date DESC
			$limit";
	}
	$limit = $limit ? "LIMIT $limit" : "";
	$meta_key = is_array($meta_keys) ? "meta_key IN ('" . implode($meta_keys, "','") . "') AND" : '';
	$q="SELECT post_id, meta_key, meta_value
		FROM {$wpdb->prefix}postmeta
		WHERE {$meta_key} post_id IN ($post_ids)
		ORDER BY post_id DESC, meta_key ASC";
	return $q;
}

function get_object($post_id, $meta_keys = false, $use_id = true) {
	global $wpdb;
	$object = array();
	$where = "WHERE post_id ";
	$where .= $use_id ? "= '{$post_id}'" : "IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_title = '{$post_id}')";
	$where .= $meta_keys ? " AND meta_key IN ('" . implode($meta_keys, "','") . "')" : '';
	$get_meta = mysql_query(
		$q="SELECT post_id, meta_key, meta_value
			FROM {$wpdb->prefix}postmeta $where
			ORDER BY meta_key ASC");
	while ($meta = mysql_fetch_object($get_meta)) {
		$object['id'] = $meta->post_id;
		$object[$meta->meta_key] = $meta->meta_value;
	}

	return $object;
}

function get_objects($post_title, $meta_keys = false, $sort_by = false, $limit = false) {
	$objects = $object = array();
	if ($meta_keys)
		sort($meta_keys);

	$get_meta = mysql_query(make_query($post_title, $meta_keys, $limit));
	$i = $last_id = 0;
	while ($meta = mysql_fetch_object($get_meta)) {
		if ($meta->post_id != $last_id && $last_id != 0) {
			$key = $sort_by ? $object[$sort_by] : $i;
			$objects[$key] = $object;
			$i++;
		}

		$object['id'] = $last_id = $meta->post_id;
		$object[$meta->meta_key] = $meta->meta_value;
	}
	
	if (empty($object))
		return array();

	$key = $sort_by ? $object[$sort_by] : $i;
	$objects[$key] = $object;

	if ($sort_by)
		ksort($objects);

	return $objects;
}

function get_post_by_title($title) {
	global $wpdb;
	$post = mysql_fetch_object(mysql_query("SELECT post_content FROM {$wpdb->prefix}posts WHERE post_title = '$title' AND post_status = 'publish' LIMIT 1"));
	if (!$post) {
		$blog_id = get_current_blog_id();
		$base_prefix = $wpdb->base_prefix;
		while (!$post && $blog_id > 1) {
			$blog_id--;
			if ($blog_id == 1) $prefix = $base_prefix;
			else $prefix = "{$base_prefix}{$blog_id}_";
			$post = mysql_fetch_object(mysql_query("SELECT post_content FROM {$prefix}posts WHERE post_title = '$title' AND post_status = 'publish' LIMIT 1"));
			if (!$post && $blog_id == 0) return false;
		}
	}
	return $post;
}

function display_page($title) {
	$post = get_post_by_title($title);
	if (!$post) {
		echo 'Please add a page with the title "' . $title . '".';
	} else {
		echo $post->post_content;
	}
}

$get_settings = mysql_query(
	$q="SELECT `meta_key`, `meta_value`
		FROM wp_postmeta
		WHERE post_id IN (
			SELECT ID
			FROM wp_posts
			WHERE post_title = 'Settings'
				AND post_status != 'inherit'
				AND post_status != 'trash' ORDER BY post_id DESC)
		ORDER BY meta_key ASC");
while ($setting = mysql_fetch_object($get_settings)) {
	$settings[$setting->meta_key] = $setting->meta_value;
}

?>