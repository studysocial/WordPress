=== Amazon S3 Uploader ===
Contributors: cabloo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=C22UNPM4TTFUJ
Tags: uploads, amazon, s3, mirror, admin, media
Requires at least: 3.5
Tested up to: 3.8
Stable tag: 1.0
License: GPLv3

Copies files to Amazon S3 when they are uploaded to the media library.

== Description ==

This plugin automatically any media (including images and their thumbnails) uploaded to WordPress to [Amazon Simple Storage Service](http://aws.amazon.com/s3/) (S3).

This plugin skips the WordPress upload process completely - files are uploaded to the /tmp/ folder of your server and then moved directly from there to S3. No need to worry about permissions!

This plugin will **not** automatically copy files that are already on the server to S3.

*This plugin is a rewrite of [Amazon S3 and Cloudfront](http://wordpress.org/plugins/amazon-s3-and-cloudfront/) designed to not require write permissions on the WordPress server so that S3 can work nicely with OpsWorks.*

== Installation ==

1. Install the required [Amazon Web Services plugin](https://github.com/deliciousbrains/wp-amazon-web-services) using WordPress' built-in installer
2. Follow the instructions to setup your AWS access keys
3. Install this plugin using WordPress' built-in installer
4. Access the *Amazon S3 Uploader* option under *AWS* and configure

== Screenshots ==

1. Settings screen

== Upgrade Notice ==

= 1.0 =
This version requires PHP 5.3.3+ and the Amazon Web Services plugin

== Changelog ==

