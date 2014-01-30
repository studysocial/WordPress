<?php
$objects = get_objects('Home Page', array('video_url', 'paragraph'));
$home = end($objects);
?>
<div class="wrap full mobile-hide">
	<div class="full-video"><div><b></b></div></div>
	<div class="video" data-video="<?php echo $home['video_url'] ?>">
		<div class="wrap">
			<div class="overlay">
				<h1>Group Interactive Networks (GIN)</h1>
				<?php echo $home['paragraph'] ?>
				<br /><br />
				<a class="btn" href="login/">Login to your GIN System</a> &nbsp;
				<a class="btn transparent" id="demo-button">Watch Demo</a>
			</div>
			<div class="hover">
				<b class="white"></b>
				<b class="blue"></b>
				<span>Intro Video</span>
			</div>
		</div>
	</div>
</div>
<a class="mobile-show mobile-video" href="<?php echo $home['video_url'] ?>">
	<img style="display:block" alt="" src="<?php i('banners/home.jpg') ?>" />
	<span>
		<b></b>
		<img class="play" alt="" src="<?php i('play.png') ?>" />
	</span>
	<h3>Study Smarter, Not Harder.</h3>
</a>
<div class="wrap padded bordered">
	<div class="col-55 centered">
		<img alt="" src="<?php i('connect.png') ?>" />
	</div>
	<div class="col-40 ml-5">
		<h2>Manage, Communicate &amp; Connect</h2>
		<h3>All in one place.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla suscipit tincidunt tempus. Etiam tincidunt libero fermentum fermentum aliquet. Mauris pulvinar ante sed sapien pellentesque, eu ultrices massa cursus. Vivamus sollicitudin sodales lectus eget suscipit. Vestibulum nec orci sit amet felis tristique malesuada vel et nisi. Phasellus metus purus, bibendum quis mi in, facilisis rutrum elit. Curabitur lacinia enim non dui pharetra tincidunt. Cras ultrices egestas erat in aliquet. Maecenas ac mollis enim, a iaculis quam. Nullam aliquam magna diam, eu eleifend diam iaculis vel. Morbi dignissim rhoncus ligula vel lobortis. Quisque vulputate, dui eu mollis auctor, massa mi tincidunt mauris, at dapibus nisi justo eget diam. Vestibulum euismod iaculis libero, id tincidunt lorem condimentum id.</p>
	</div>
	<div class="clear"></div>
</div>
<div class="wrap padded bordered">
	<div class="col-40">
		<h2>Network, Engage &amp; Expand</h2>
		<h3>Anytime, Anywhere.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla suscipit tincidunt tempus. Etiam tincidunt libero fermentum fermentum aliquet. Mauris pulvinar ante sed sapien pellentesque, eu ultrices massa cursus. Vivamus sollicitudin sodales lectus eget suscipit. Vestibulum nec orci sit amet felis tristique malesuada vel et nisi. Phasellus metus purus, bibendum quis mi in, facilisis rutrum elit. Curabitur lacinia enim non dui pharetra tincidunt. Cras ultrices egestas erat in aliquet. Maecenas ac mollis enim, a iaculis quam. Nullam aliquam magna diam, eu eleifend diam iaculis vel. Morbi dignissim rhoncus ligula vel lobortis. Quisque vulputate, dui eu mollis auctor, massa mi tincidunt mauris, at dapibus nisi justo eget diam. Vestibulum euismod iaculis libero, id tincidunt lorem condimentum id.</p>
	</div>
	<div class="col-55 ml-5 centered">
		<img alt="" src="<?php i('phone.png') ?>" />
	</div>
	<div class="clear"></div>
</div>
<div class="wrap padded bordered">
	<div class="col-55 centered">
		<img alt="" src="<?php i('computer.png') ?>" />
	</div>
	<div class="col-40 ml-5">
		<h2>Create, Customize &amp; Update</h2>
		<h3>Make it your own.</h3>
		<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla suscipit tincidunt tempus. Etiam tincidunt libero fermentum fermentum aliquet. Mauris pulvinar ante sed sapien pellentesque, eu ultrices massa cursus. Vivamus sollicitudin sodales lectus eget suscipit. Vestibulum nec orci sit amet felis tristique malesuada vel et nisi. Phasellus metus purus, bibendum quis mi in, facilisis rutrum elit. Curabitur lacinia enim non dui pharetra tincidunt. Cras ultrices egestas erat in aliquet. Maecenas ac mollis enim, a iaculis quam. Nullam aliquam magna diam, eu eleifend diam iaculis vel. Morbi dignissim rhoncus ligula vel lobortis. Quisque vulputate, dui eu mollis auctor, massa mi tincidunt mauris, at dapibus nisi justo eget diam. Vestibulum euismod iaculis libero, id tincidunt lorem condimentum id.</p>
	</div>
	<div class="clear"></div>
</div>
<div class="wrap full pink padded">
	<h2 class="centered">Our National Partners</h2><br />
	<div class="slider wrap">
		<b class="icon right-arrow-big"></b>
		<b class="icon left-arrow-big"></b>
		<div>
			<div class="interior partners">
				<a href="" target="_blank">
					<img src="<?php i('greeks/ADPi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
				<a href="" target="_blank">
					<img src="<?php i('greeks/AlphaPhi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
				<a href="" target="_blank">
					<img src="<?php i('greeks/ADPi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
				<a href="" target="_blank">
					<img src="<?php i('greeks/ADPi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
				<a href="" target="_blank">
					<img src="<?php i('greeks/ADPi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
				<a href="" target="_blank">
					<img src="<?php i('greeks/ADPi.png') ?>" alt="" /><br />
					Alpha Delta Pi
				</a>
			</div>
		</div>
	</div>
</div>
<div class="wrap full teal padded">
	<div class="wrap testimonials">
		<blockquote>
			<img src="<?php i('samples/testimonial-face.png') ?>" alt="" />
			<div>
				&quot;This is where we will put quotes from when in the news and below it will be the person it's face or just organization name.&quot;<br /><br />
				<span class="name">- Jordan Johnson,</span> <i>Alpha Sigma Chi President</i>
			</div>
		</blockquote>
		<blockquote>
			<img src="<?php i('samples/testimonial-face.png') ?>" alt="" />
			<div>
				&quot;This is where we will put quotes from when in the news and below it will be the person it's face or just organization name.&quot;<br /><br />
				<span class="name">- Jordan Johnson,</span> <i>Alpha Sigma Chi President</i>
			</div>
		</blockquote>
		<blockquote>
			<img src="<?php i('samples/testimonial-face.png') ?>" alt="" />
			<div>
				&quot;This is where we will put quotes from when in the news and below it will be the person it's face or just organization name.&quot;<br /><br />
				<span class="name">- Jordan Johnson,</span> <i>Alpha Sigma Chi President</i>
			</div>
		</blockquote>
		<blockquote>
			<img src="<?php i('samples/testimonial-face.png') ?>" alt="" />
			<div>
				&quot;This is where we will put quotes from when in the news and below it will be the person it's face or just organization name.&quot;<br /><br />
				<span class="name">- Jordan Johnson,</span> <i>Alpha Sigma Chi President</i>
			</div>
		</blockquote>
		<div class="clear"></div>
	</div>
</div>
<div class="wrap full icons">
	<div class="top">
		<h1>Find out why our clients love us.</h1>
		<h2>Sign up for a free trial today.</h2>
	</div>
	<div class="wrap">
		<div class="col-3">
			<div class="icon-col">
				<img src="<?php i('icons/phone.png') ?>" alt="" />
				<br />
				<h4>Questions? Call us.</h4>
				We're here to help you!<br />
				Give us a call or chat with us live.<br />
				Our Customer Service Representatives<br />
				are available Monday-Friday<br />
				10:00am-6:00pm EST.<br /><br />
				<font size="+1">1-888-GINSYSTEM</font><br />
				<font size="-1">(888-283-1928)</font>
			</div>
		</div>
		<div class="col-3">
			<div class="icon-col">
				<img src="<?php i('icons/info.png') ?>" alt="" />
				<br />
				<h4>Request Information</h4>
				Want more information? One of our<br />
				Customer Service Representatives<br />
				will show you how the GIN System can<br />
				help your organization.<br /><br />
				<a href="info/"><font size="+1">Request Info &raquo;</font></a>
			</div>
		</div>
		<div class="col-3">
			<div class="icon-col">
				<img src="<?php i('icons/tv.png') ?>" alt="" />
				<br />
				<h4>Start a free trial.</h4>
				Give Group Interactive Networks<br />
				a try by signing up for a 30 day<br />
				free trial. No contracts or credit cards necessary.<br /><br />
				<a href="trial/"><font size="+1">Start a trial &raquo;</font></a>
			</div>
		</div>
		<div class="clear"></div>
		<div class="half space"></div>
	</div>
</div>
<?php require(get_template_directory() . '/get-our-app.php') ?>
<div class="video-overlay">
	<b></b>
</div>