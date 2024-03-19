<?php
/**
 * Plugin Name: WP Plugin Framework
 * Description: A simple WordPress plugin framework with common features.
 * Version: 1.0.0
 * Author: Mithu A Quayium
 * Text Domain: wppf
 * Domain Path: /languages
 */

namespace App;
use App\includes\core\MetaBoxManager;
use App\includes\core\WPPF_ModuleManager;
use App\includes\models\Book;
use function App\includes\utils\wppf_model;

require_once 'vendor/autoload.php';
require_once 'facades/WP_Plugin_Framework.php';

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

		// Add more hooks and actions as needed

	}

	/**
	 * Load translation files
	 */
	public function load_textdomain() {
		load_plugin_textdomain('wppf', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Initialize modules
		$this->init_modules();

		// Add more initialization tasks as needed
//		wppf_model()->model( Book::class )->all();

	}

	/**
	 * Include necessary files
	 */
	private function include_files() {
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-plugin-framework.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-module-manager.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-template-manager.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-custom-post-type.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-taxonomy.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/traits/fields.php';
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-metabox-manager.php';


		require_once plugin_dir_path(__FILE__) . 'includes/settings/class-settings.php';
		require_once plugin_dir_path(__FILE__) . 'includes/security/class-security.php';
		require_once plugin_dir_path(__FILE__) . 'includes/utils/class-model.php';
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

	public function metabox_manager() {
		return MetaBoxManager::instance();
	}


}

// Instantiate the plugin
$wp_plugin_framework = new WP_PluginFramework();
\WP_Plugin_Framework::metabox_manager()->add_metabox( 'test', [
	'title' => 'Test metabox',
	'callback' => function() {},
	'screen' => 'page',
	'context' => 'normal',
	'priority' => 'default',
	'fields' => [
		'field_name_1' => [
			'name' => 'field_name_1',
			'type' => 'text',
			'title' => 'First name',
			'desc' => 'This is first name',
			'value' => ''
		]
	]
] );

