<?php
/**
 * @package WordPress
 * @subpackage Study_Edge
 * @since Study Edge 1.0
 */

global $nav;
?>
	<footer class="wrap full">
		<div class="wrap">
			<div class="left mobile-hide">
				<?php $nav->output(false, false) ?>
			</div>
			<div class="right">
				<h3>Stay in Touch</h3>
				<div class="social">
					<a href="http://facebook.com/studyedge" target="_blank"><img src="<?php i() ?>social/facebook.png" alt="" /></a>
					<a href="http://twitter.com/studyedge" target="_blank"><img src="<?php i() ?>social/twitter.png" alt="" /></a>
					<a href="http://instagram.com/studyedge" target="_blank"><img src="<?php i() ?>social/instagram.png" alt="" /></a>
					<a href="http://youtube.com/studyedge" target="_blank"><img src="<?php i() ?>social/youtube.png" alt="" /></a>
				</div>
				1-888-97-STUDY<br />
				<a href="mailto:help@studyedge.com">help@studyedge.com</a><br />
				1717 NW 1st Ave.<br />
				Gainesville, FL 32603<br />
				Sunday-Friday, 11am-8pm
			</div>
			<div class="clear"></div>
			<div class="copy">
				Copyright <?php echo date('Y') ?> &copy; <?php bloginfo('name') ?> &nbsp;
				<!--<?php
				define('END_TIME', microtime(true));
				echo (END_TIME - START_TIME)*1000 . " ms";
				?>-->
			</div>
		</div>
	</footer>
	<div id="popup-overlay" popup="close"></div>
	<div id="popup"><div></div></div>
	<?php wp_footer() ?>
</body>
</html>