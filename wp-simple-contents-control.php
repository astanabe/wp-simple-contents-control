<?php
/**
 * Plugin Name:       Simple Contents Control
 * Plugin URI:        https://github.com/astanabe/wp-simple-contents-control
 * Description:       A simple contents visibility control plugin for WordPress
 * Author:            Akifumi S. Tanabe
 * Author URI:        https://github.com/astanabe
 * License:           GNU General Public License v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-simple-contents-control
 * Domain Path:       /languages
 * Version:           0.1.0
 * Requires at least: 6.4
 *
 * @package           WP_Simple_Contents_Control
 */

// Security check
if (!defined('ABSPATH')) {
	exit;
}

// Add shortcode for logged-out users
function wp_simple_contents_control_if_logout($atts, $content = null) {
	if (!is_user_logged_in()) {
		return do_shortcode(shortcode_unautop($content));
	}
	return '';
}
add_shortcode('if-logout', 'wp_simple_contents_control_if_logout');

// Add shortcode for logged-in users
function wp_simple_contents_control_if_login($atts, $content = null) {
	if (is_user_logged_in()) {
		return do_shortcode(shortcode_unautop($content));
	}
	return '';
}
add_shortcode('if-login', 'wp_simple_contents_control_if_login');

// Add shortcode for specific user_login
function wp_simple_contents_control_if_user($atts, $content = null) {
	if (!is_user_logged_in()) {
		return '';
	}
	$atts = shortcode_atts(['is' => ''], $atts, 'if-user');
	$users = array_map('trim', explode(',', strtolower($atts['is'])));
	$user = wp_get_current_user();
	if (array_intersect($users, $user->user_login)) {
		return do_shortcode(shortcode_unautop($content));
	}
	return '';
}
add_shortcode('if-user', 'wp_simple_contents_control_if_user');

// Add shortcode for specific roles
function wp_simple_contents_control_if_role($atts, $content = null) {
	if (!is_user_logged_in()) {
		return '';
	}
	$atts = shortcode_atts(['is' => ''], $atts, 'if-role');
	$roles = array_map('trim', explode(',', strtolower($atts['is'])));
	$roles = array_map(function($role) {
		return preg_replace('/ +/', '-', $role);
	}, $roles);
	$user = wp_get_current_user();
	$user_roles = (array) $user->roles;
	if (array_intersect($roles, $user_roles)) {
		return do_shortcode(shortcode_unautop($content));
	}
	return '';
}
add_shortcode('if-role', 'wp_simple_contents_control_if_role');

// Add shorcode for specific groups
function wp_simple_contents_control_if_group($atts, $content = null) {
	if (!is_user_logged_in()) {
		return '';
	}
	if (!function_exists('groups_get_user_groups')) {
		return '';
	}
	$atts = shortcode_atts(['is' => ''], $atts, 'if-group');
	$groups = array_map('trim', explode(',', strtolower($atts['is'])));
	$groups = array_map(function($group) {
		return preg_replace('/ +/', '-', $group);
	}, $groups);
	$user_id = get_current_user_id();
	$user_groups = groups_get_user_groups($user_id);
	$user_group_ids = $user_groups['groups'];
	foreach ($groups as $group_name) {
		$group = groups_get_group(['group_id' => 0, 'search_terms' => $group_name, 'per_page' => 1]);
		if (!empty($group) && isset($group['groups'][0]->id)) {
			$group_id = $group['groups'][0]->id;
			if (in_array($group_id, $user_group_ids)) {
				return do_shortcode(shortcode_unautop($content));
			}
		}
	}
	return '';
}
add_shortcode('if-group', 'wp_simple_contents_control_if_group');

// Add shortcode for inserting search form
function wp_simple_contents_control_search_form() {
	ob_start();
	get_search_form();
	return ob_get_clean();
}
add_shortcode('search-form', 'wp_simple_contents_control_search_form');
