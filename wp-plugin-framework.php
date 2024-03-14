<?php
/**
 * Plugin Name: WP Plugin Framework
 * Description: A simple WordPress plugin framework with common features.
 * Version: 1.0.0
 * Author: Mithu A Quayium
 * Text Domain: wp-plugin-framework
 * Domain Path: /languages
 */

require_once 'vendor/autoload.php';

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

define( 'PLUGIN_DIR', dirname( __FILE__ ) );
define( 'ASSET_URL', plugins_url( 'assets', __FILE__ ) );

class WP_PluginFramework {

	/**
	 * Initialize the plugin
	 */
	public function __construct() {
		// Load translation files
		add_action('plugins_loaded', array($this, 'load_textdomain'));

		// Initialize core functionality
		add_action('init', array($this, 'init'));

		// Include necessary files
		$this->include_files();

		if ( is_admin() ) {
			$this->include_admin_files();
		}

		//register post type
		$this->register_post_types();
		$this->register_taxonomy();

		// Add more hooks and actions as needed

	}

	/**
	 * Load translation files
	 */
	public function load_textdomain() {
		load_plugin_textdomain('wp-plugin-framework', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Initialize modules
		$this->init_modules();

		// Add more initialization tasks as needed

	}

	/**
	 * Include necessary files
	 */
	private function include_files() {
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-plugin-framework.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-module-manager.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-hook-manager.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-template-manager.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-custom-post-type.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-taxonomy.php';
		require_once plugin_dir_path(__FILE__) . 'includes/settings/class-settings.php';
		require_once plugin_dir_path(__FILE__) . 'includes/security/class-security.php';
		require_once plugin_dir_path(__FILE__) . 'includes/utils/class-i18n.php';
		require_once plugin_dir_path(__FILE__) . 'includes/utils/class-database.php';
		require_once plugin_dir_path(__FILE__) . 'includes/utils/class-debugger.php';
		// Add more file inclusions as needed
	}

	private function include_admin_files() {
		require_once plugin_dir_path(__FILE__) . 'includes/admin/class-admin.php';
	}

	/**
	 * Initialize modules
	 */
	private function init_modules() {
		// Initialize individual modules using the Module Manager
		$module_manager = new WPPF_ModuleManager();
		$module_manager->register_module( 'sample-module', 'Sample_Module' );
		$module_manager->init_modules();
	}

	public function register_post_types() {
		// Instantiate the custom post type class
		$custom_post_type = new WPPF_CustomPostType(
			'my_custom_post_type',
			array(
				'singular' => __('Custom Post', 'my-plugin-domain'),
				'plural'   => __('Custom Posts', 'my-plugin-domain'),
			),
			array(
				// Additional arguments for register_post_type
			)
		);

	}

	public function register_taxonomy() {
		// Instantiate the taxonomy class
		$taxonomy = new WPPF_Taxonomy(
			'my_custom_taxonomy',
			array('my_custom_post_type'), // Array of post types to which the taxonomy should be attached
			array(
				'singular' => __('Custom Taxonomy', 'my-plugin-domain'),
				'plural'   => __('Custom Taxonomies', 'my-plugin-domain'),
			),
			array(
				// Additional arguments for register_taxonomy
			)
		);
	}
}

// Instantiate the plugin
$wp_plugin_framework = new WP_PluginFramework();

