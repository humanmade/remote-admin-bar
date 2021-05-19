<?php

namespace HM\AdminBar;

use WP_Admin_Bar;
use WP_Screen;

/**
 * Register the ajax endpoint for the remote admin bar.
 */
function bootstrap() {

	// Make sure cookie constants are defined when needed.
	wp_cookie_constants();

	add_action( 'wp_ajax_admin_bar_render', __NAMESPACE__ . '\\admin_bar_render' );
	add_action( 'wp_login', __NAMESPACE__ . '\\set_js_cookie', 10, 4 );
	add_action( 'wp_logout', __NAMESPACE__ . '\\clear_js_cookie' );
}

/**
 * Return the HTML, scripts, and styles for the remote admin bar.
 */
function admin_bar_render() {
	global $wp, $wp_admin_bar, $current_screen;

	// Initialize query based on query string variables.
	$wp->main( $_SERVER['QUERY_STRING'] ?? '' );

	// Ensure that is_admin() returns false to register the correct menu nodes.
	$current_screen = WP_Screen::get( 'front' );

	// Set up the admin bar for the current view.
	require_once ABSPATH . WPINC . '/class-wp-admin-bar.php';

	$wp_admin_bar = new WP_Admin_Bar();
	$wp_admin_bar->initialize();
	$wp_admin_bar->add_menus();

	$admin_bar_data = [
		'markup' => get_markup(),
		'scripts' => get_scripts(),
		'styles' => get_styles(),
	];

	rest_send_cors_headers( true );
	wp_send_json( $admin_bar_data );
	die();
}

/**
 * Set a cookie to identify that the current user is logged in.
 *
 * We can't rely on WordPress's LOGGED_IN_COOKIE, which is HTTP-only.
 *
 * @param string  $user_login Username of current user.
 * @param WP_User $user       Current user object.
 */
function set_js_cookie( $user_id, $user ) {

	/**
	 * Set the expiration time of the logged-in cookie to the same time as a user auth cookie.
	 */
	$expiration = time() + apply_filters( 'auth_cookie_expiration', 14 * DAY_IN_SECONDS, $user->user_id, true );

	setcookie(
		'wp_remote_admin_bar',
		1,
		[
			'domain'   => COOKIE_DOMAIN,
			'expires'  => $expires,
			'httponly' => false,
			'path'     => COOKIEPATH,
		] );
}

/**
 * Clear the cookie used to identify logged in users.
 */
function clear_js_cookie() {
	setcookie(
		'wp_remote_admin_bar',
		0,
		[
			'domain'  => COOKIE_DOMAIN,
			'expires' => time() - YEAR_IN_SECONDS,
			'path'    => COOKIEPATH,
		]
	);
}

/**
 * Return the markup for the admin bar.
 */
function get_markup() {
	global $wp_admin_bar;

	ob_start();

	/**
	 * Core hook used by plugins to add and remove items from the admin bar.
	 *
	 * Plugins occasionally (and incorrectly) render output on this hook, so it
	 * needs to be called inside the output buffer.
	 */
	do_action_ref_array( 'admin_bar_menu', [ &$wp_admin_bar ] );

	/**
	 * Hook for ease of targeting admin menu nodes only when serving admin bar remotely.
	 *
	 * Use this to remove entries which don't make sense on headless sites, for example.
	 *
	 * @param WP_Admin_Bar GLobal admin bar object (passed by reference).
	 */
	do_action_ref_array( 'remote_admin_bar_menu', [ &$wp_admin_bar ] );

	/**
	 * Core hook for adding output before the admin bar is rendered.
	 */
	do_action( 'wp_before_admin_bar_render' );

	$wp_admin_bar->render();

	/**
	 * Core hook for adding output after the admin bar is rendered.
	 */
	do_action( 'wp_after_admin_bar_render' );

	return ob_get_clean();
}

/**
 * Return the admin-bar js scripts.
 */
function get_scripts() {
	global $wp_scripts;

	ob_start();
	$wp_scripts->print_scripts();
	return ob_get_clean();
}

/**
 * Return stylesheets needed for rendering the admin bar.
 */
function get_styles() {
	global $wp_styles;

	ob_start();
	_admin_bar_bump_cb();
	$wp_styles->do_items();
	return ob_get_clean();
}
