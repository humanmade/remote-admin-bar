<?php
/*
Plugin Name: Remote Admin Bar
Description: Serves the WordPress admin bar remotely for use in headless or decoupled sites.
Author: Human Made
Author URI: http://humanmade.com
Requires PHP: 7.3
Version: 0.0.3
License: GPL 2+
*/

require_once __DIR__ . '/inc/namespace.php';

HM\AdminBar\bootstrap();
