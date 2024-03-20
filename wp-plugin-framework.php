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
use App\includes\admin\Admin_Menu_Manager;
use App\includes\core\MetaBoxManager;
use App\includes\core\WPPF_ModuleManager;
use App\includes\core\WPPF_Settings;
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
		require_once plugin_dir_path(__FILE__) . 'includes/core/class-settings-manager.php';


//		require_once plugin_dir_path(__FILE__) . 'includes/settings/class-settings.php';
		require_once plugin_dir_path(__FILE__) . 'includes/security/class-security.php';
		require_once plugin_dir_path(__FILE__) . 'includes/utils/class-model.php';
		// Add more file inclusions as needed
	}

	private function include_admin_files() {
		require_once plugin_dir_path(__FILE__) . 'includes/admin/class-admin.php';
		require_once plugin_dir_path(__FILE__) . 'includes/admin/class-admin-menu-manager.php';
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

	public function settings_manager() {
		return WPPF_Settings::instance();
	}

	public function admin_menu_manager() {
		return Admin_Menu_Manager::instance();
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

//\WP_Plugin_Framework::settings_manager();
\WP_Plugin_Framework::settings_manager()->add_settings_menu(
	[
		'title' => 'Settings API',
		'menu_title' => 'Settings API',
		'capability' => 'manage_options',
		'slug' => 'settings_api_test',
		'parent_slug' => null, //if given, it will be submenu
		'type' => 'menu', //options: theme_option, settings
		'sections' => [
			[
				'id'    => 'wppf_basics',
				'title' => __( 'Basic Settings', 'wppf' )
			],
			[
				'id'    => 'wppf_advanced',
				'title' => __( 'Advanced Settings', 'wppf' )
			],
		],
		'fields' => array(
			'wppf_basics' => array(
				array(
					'name'              => 'text_val',
					'label'             => __( 'Text Input', 'wedevs' ),
					'desc'              => __( 'Text input description', 'wedevs' ),
					'placeholder'       => __( 'Text Input placeholder', 'wedevs' ),
					'type'              => 'text',
					'default'           => 'Title',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'number_input',
					'label'             => __( 'Number Input', 'wedevs' ),
					'desc'              => __( 'Number field with validation callback `floatval`', 'wedevs' ),
					'placeholder'       => __( '1.99', 'wedevs' ),
					'min'               => 0,
					'max'               => 100,
					'step'              => '0.01',
					'type'              => 'number',
					'default'           => 'Title',
					'sanitize_callback' => 'floatval'
				),
				array(
					'name'        => 'textarea',
					'label'       => __( 'Textarea Input', 'wedevs' ),
					'desc'        => __( 'Textarea description', 'wedevs' ),
					'placeholder' => __( 'Textarea placeholder', 'wedevs' ),
					'type'        => 'textarea'
				),
				array(
					'name'        => 'html',
					'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wedevs' ),
					'type'        => 'html'
				),
				array(
					'name'  => 'checkbox',
					'label' => __( 'Checkbox', 'wedevs' ),
					'desc'  => __( 'Checkbox Label', 'wedevs' ),
					'type'  => 'checkbox'
				),
				array(
					'name'    => 'radio',
					'label'   => __( 'Radio Button', 'wedevs' ),
					'desc'    => __( 'A radio button', 'wedevs' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => 'Yes',
						'no'  => 'No'
					)
				),
				array(
					'name'    => 'selectbox',
					'label'   => __( 'A Dropdown', 'wedevs' ),
					'desc'    => __( 'Dropdown description', 'wedevs' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => 'Yes',
						'no'  => 'No'
					)
				),
				array(
					'name'    => 'password',
					'label'   => __( 'Password', 'wedevs' ),
					'desc'    => __( 'Password description', 'wedevs' ),
					'type'    => 'password',
					'default' => ''
				),
				array(
					'name'    => 'file',
					'label'   => __( 'File', 'wedevs' ),
					'desc'    => __( 'File description', 'wedevs' ),
					'type'    => 'file',
					'default' => '',
					'options' => array(
						'button_label' => 'Choose Image'
					)
				)
			),
			'wppf_advanced' => array(
				array(
					'name'    => 'color',
					'label'   => __( 'Color', 'wedevs' ),
					'desc'    => __( 'Color description', 'wedevs' ),
					'type'    => 'color',
					'default' => ''
				),
				array(
					'name'    => 'password',
					'label'   => __( 'Password', 'wedevs' ),
					'desc'    => __( 'Password description', 'wedevs' ),
					'type'    => 'password',
					'default' => ''
				),
				array(
					'name'    => 'wysiwyg',
					'label'   => __( 'Advanced Editor', 'wedevs' ),
					'desc'    => __( 'WP_Editor description', 'wedevs' ),
					'type'    => 'wysiwyg',
					'default' => ''
				),
				array(
					'name'    => 'multicheck',
					'label'   => __( 'Multile checkbox', 'wedevs' ),
					'desc'    => __( 'Multi checkbox description', 'wedevs' ),
					'type'    => 'multicheck',
					'default' => array('one' => 'one', 'four' => 'four'),
					'options' => array(
						'one'   => 'One',
						'two'   => 'Two',
						'three' => 'Three',
						'four'  => 'Four'
					)
				),
			)
		)
	]
);


