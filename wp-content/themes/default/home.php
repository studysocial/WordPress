<?php
/**
 * @package WordPress
 * @subpackage Study Edge
 * @since Study Edge 1.0
 */

global $nav;

get_header();
require(file_exists($f = 'pages/' . $nav->getActive() . '.php') ? $f : 'pages/home.php');
get_footer();

?>