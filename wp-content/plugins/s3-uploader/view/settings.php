<div class="aws-content wp_s3-settings">

<?php
$buckets = $this->get_buckets();

if (is_wp_error($buckets)) :
	?>
	<div class="error">
		<p>
			<?php _e('Error retrieving a list of your S3 buckets from AWS:', 'wp_s3'); ?>
			<?php echo $buckets->get_error_message(); ?>
		</p>
	</div>
	<?php
endif;

if (isset($_GET['updated'])) {
	?>
	<div class="updated">
		<p>
			<?php _e('Settings saved.', 'wp_s3'); ?>
		</p>
	</div>
	<?php
}
?>

<form method="post">
<input type="hidden" name="action" value="save" />
<?php wp_nonce_field('wp_s3-save-settings') ?>

<table class="form-table">
<tr valign="top">
	<td>
		<h3><?php _e('S3 Settings', 'wp_s3'); ?></h3>

		<select name="bucket" class="bucket">
		<option value="">-- <?php _e('Select an S3 Bucket', 'wp_s3'); ?> --</option>
		<?php if (is_array($buckets)) foreach ($buckets as $bucket): ?>
		    <option value="<?php echo esc_attr($bucket['Name']); ?>" <?php echo $bucket['Name'] == $this->get_setting('bucket') ? 'selected="selected"' : ''; ?>><?php echo esc_html($bucket['Name']); ?></option>
		<?php endforeach;?>
		<option value="new"><?php _e('Create a new bucket...', 'wp_s3'); ?></option>
		</select><br />

		<input type="checkbox" name="virtual-host" value="1" id="virtual-host" <?php echo $this->get_setting('virtual-host') ? 'checked="checked" ' : '';?> />
		<label for="virtual-host"> <?php _e('Bucket is setup for virtual hosting', 'wp_s3'); ?></label> (<a href="http://docs.amazonwebservices.com/AmazonS3/2006-03-01/VirtualHosting.html">more info</a>)
		<br />

		<input type="checkbox" name="expires" value="1" id="expires" <?php echo $this->get_setting('expires') ? 'checked="checked" ' : ''; ?> />
		<label for="expires"> <?php printf(__('Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em>', 'wp_s3'), 'http://developer.yahoo.com/performance/rules.html#expires'); ?></label>
	</td>
</tr>

<tr valign="top">
	<td>
		<label><?php _e('Object Path:', 'wp_s3'); ?></label>&nbsp;&nbsp;
		<input type="text" name="object-prefix" value="<?php echo esc_attr($this->get_setting('object-prefix')); ?>" size="30" />
		<label><?php echo trailingslashit($this->get_dynamic_prefix()); ?></label>
	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e('CloudFront Settings', 'wp_s3'); ?></h3>

		<label><?php _e('Domain Name', 'wp_s3'); ?></label><br />
		<input type="text" name="cloudfront" value="<?php echo esc_attr($this->get_setting('cloudfront')); ?>" size="50" />
		<p class="description"><?php _e('Leave blank if you aren&#8217;t using CloudFront.', 'wp_s3'); ?></p>

		<input type="checkbox" name="object-versioning" value="1" id="object-versioning" <?php echo $this->get_setting('object-versioning') ? 'checked="checked" ' : ''; ?> />
		<label for="object-versioning"> <?php printf(__('Implement <a href="%s">object versioning</a> by appending a timestamp to the S3 file path', 'wp_s3'), 'http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/ReplacingObjects.html'); ?></label>
	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e('Plugin Settings', 'wp_s3'); ?></h3>

		<input type="checkbox" name="copy-to-s3" value="1" id="copy-to-s3" <?php echo $this->get_setting('copy-to-s3') ? 'checked="checked" ' : ''; ?> />
		<label for="copy-to-s3"> <?php _e('Copy files to S3 as they are uploaded to the Media Library', 'wp_s3'); ?></label>
		<br />

		<input type="checkbox" name="serve-from-s3" value="1" id="serve-from-s3" <?php echo $this->get_setting('serve-from-s3') ? 'checked="checked" ' : ''; ?> />
		<label for="serve-from-s3"> <?php _e('Point file URLs to S3/CloudFront for files that have been copied to S3', 'wp_s3'); ?></label>
		<br />

		<input type="checkbox" name="remove-local-file" value="1" id="remove-local-file" <?php echo $this->get_setting('remove-local-file') ? 'checked="checked" ' : ''; ?> />
		<label for="remove-local-file"> <?php _e('Remove uploaded file from local filesystem once it has been copied to S3', 'wp_s3'); ?></label>
		<br />

		<input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php echo $this->get_setting('force-ssl') ? 'checked="checked" ' : ''; ?> />
		<label for="force-ssl"> <?php _e('Always serve files over https (SSL)', 'wp_s3'); ?></label>
		<br />

		<input type="checkbox" name="hidpi-images" value="1" id="hidpi-images" <?php echo $this->get_setting('hidpi-images') ? 'checked="checked" ' : ''; ?> />
		<label for="hidpi-images"> <?php _e('Copy any HiDPI (@2x) images to S3 (works with WP Retina 2x plugin)', 'wp_s3'); ?></label>

	</td>
</tr>
<tr valign="top">
	<td>
		<button type="submit" class="button button-primary"><?php _e('Save Changes', 'amazon-web-services'); ?></button>
	</td>
</tr>
</table>

</form>

</div>