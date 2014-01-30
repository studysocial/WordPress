<div class="wrap full grey text-link mobile-hide">
	<div class="wrap">
		<div class="col-55">
			<h1>Get Our Mobile App</h1>
			<small>or go to <a href="mobile"><?php echo str_replace('http://', '', site_url('/mobile', 'http')) ?></a></small>
		</div>
		<form id="send-link-form" class="col-40 ml-5" method="post" onsubmit="return false;">
			<i>We will text you a link!</i>
			<input type="text" name="phone" value="Enter Phone Number" />
			<input id="send-link" type="submit" name="send-link" value="Text Link" />
		</form>
		<div id="send-link-form-status"></div>
		<div class="clear"></div>
	</div>
</div>