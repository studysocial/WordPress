<?php

header("Content-Type: text/css");

define('NO_WP', true);
require('../../../wp-config.php');
if(!defined('IMAGE_DIR')) define('IMAGE_DIR', 'images/');

$file = 'style.css';
if (isset($_GET['file'])) {
	$_GET['file'] = str_replace('./', '/', $_GET['file']);
	$file = file_exists($f = 'css/' . $_GET['file'] . '.css') ? $f : 'style.css';
}

$total = str_replace("'images/", "'" . IMAGE_DIR, file_get_contents($file));

if (extension_loaded('zlib'))
	ob_start('ob_gzhandler');

if (!defined('WP_DEBUG') || !WP_DEBUG) {
	$replace = array(
		'/\/\*[^!][\d\D]*?\*\/|\t+/' => '', //comments
		"/\s+/" => " ", //extraneous white space
		//'/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i' => '$1#$2$3$4$5', //Hex colors e.g. #00ccff => #0cf
	);
	$replaced = preg_replace(array_keys($replace), array_values($replace), $total);
	$replace = array(
		"@charset \"utf-8\";" => "",
		"\r\n"	=>	"",
		": "	=>	":",
		" {"	=>	"{",
		"{ "	=>	"{",
		"} "	=>	"}",
		" }"	=>	"}",
		"( "	=>	"(",
		" )"	=>	")",
		", "	=>	",",
		"; "	=>	";",
		";}"	=>	"}",
	);

	$total = trim(str_replace(array_keys($replace), array_values($replace), $replaced));
}

echo $total;
ob_end_flush();

?>