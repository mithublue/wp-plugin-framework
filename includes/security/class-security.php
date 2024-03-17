<?php
namespace App\includes\security;

/**
 * Security class for WordPress Plugin Framework
 */
class WPPF_PluginSecurity {

	/**
	 * Validate email address
	 *
	 * @param string $email
	 * @return bool
	 */
	public static function validate_email($email) {
		return is_email($email);
	}

	/**
	 * Sanitize text input
	 *
	 * @param string $text
	 * @return string
	 */
	public static function sanitize_text($text) {
		return sanitize_text_field($text);
	}

	/**
	 * Sanitize URL
	 *
	 * @param string $url
	 * @return string
	 */
	public static function sanitize_url($url) {
		return esc_url_raw($url);
	}

	/**
	 * Validate and sanitize an integer
	 *
	 * @param mixed $integer
	 * @return int|false
	 */
	public static function validate_integer($integer) {
		return filter_var($integer, FILTER_VALIDATE_INT);
	}

	/**
	 * Validate and sanitize a boolean value
	 *
	 * @param mixed $bool
	 * @return bool
	 */
	public static function validate_boolean($bool) {
		return filter_var($bool, FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 * Check for potential security issues in user input
	 *
	 * @param string $input
	 * @return string
	 */
	public static function sanitize_input($input) {
		return wp_kses($input, array());
	}

	/**
	 * Verify nonce
	 *
	 * @param string $nonce_action
	 * @param string $nonce_name
	 * @return bool
	 */
	public static function verify_nonce($nonce_action, $nonce_name) {
		return isset($_POST[$nonce_name]) && wp_verify_nonce($_POST[$nonce_name], $nonce_action);
	}

	/**
	 * Basic security check for user roles
	 *
	 * @param string $required_role
	 * @return bool
	 */
	public static function current_user_has_role($required_role) {
		$user = wp_get_current_user();
		return in_array($required_role, (array) $user->roles);
	}

	/**
	 * Prevent direct access to this file
	 */
	private function __construct() {
		die("Direct access denied.");
	}
}
