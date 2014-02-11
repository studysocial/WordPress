<?php
/**
 * @package WordPress
 * @subpackage Study_Edge
 * @since Study Edge 1.0
 */

global $current_blog, $nav;

$pages = array(
	'./' => 'Home',
	array(
		'about/',
		'about/',
		'parents/' => 'Parents and Professors',
	),
	'services/',
	'clients/',
	'news/',
	'contact/',
	'support/'
);

$nav = new nav($pages);
$path = get_path();

?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="<?php echo $nav->getActive() . (isset($current_blog->path) ? ' ' . str_replace('/', '', $current_blog->path) : '') ?>">
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset') ?>">
	<meta name="viewport" content="width=device-width">
	
	<title><?php bloginfo('name') ?></title>
	
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>">
	
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/style.php?file=default">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/style.php">
	
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<base href="<?php echo site_url() ?>/" />
	
	<?php wp_head() ?>
	
	<script src="<?php echo get_template_directory_uri() ?>/js/default.js"></script>
	<script src="<?php echo get_template_directory_uri() ?>/js/functions.js"></script>
	
	<!-- Typekit -->
	<script src="//use.typekit.net/gzm3kaq.js"></script>
	<script type="text/javascript">
		try{
			Typekit.load();
		} catch(e){}
	</script>

	<!--
	If using flowplayer (for video) uncomment these lines:
	<link rel="stylesheet" href="//releases.flowplayer.org/5.4.3/skin/minimalist.css">
	<script src="//releases.flowplayer.org/5.4.3/flowplayer.min.js"></script>
	-->
</head>
<body <?php body_class(); ?>>
	<header class="tk-museo-slab full wrap">
		<div class="sticky">
			<div class="wrap">
				<nav>
					<a href="./login" class="mobile-hide btn small">Login</a>
					<div class="text">Menu <span class="icon arrow-down"></span></div>
					<?php $nav->output() ?>
					<a href="./login" class="mobile-show">Login</a>
				</nav>
				<a class="logo" href="?"><img alt="" src="<?php echo i('logo.png') ?>" /></a>
			</div>
		</div>
	</header>
