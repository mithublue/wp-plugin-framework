<?php
namespace App\includes\core;
use App\includes\admin\Admin_Menu_Manager;

/**
 * WordPress settings API demo class
 *
 */
if ( !class_exists('WPPF_Settings' ) ):
	class WPPF_Settings {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @static
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @static
		 *
		 * @return ${ClassName} An instance of the class.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		private $settings_api;
		protected $settings_menu = [];


		function __construct() {
			$this->settings_api = new \WeDevs_Settings_API();

			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'admin_menu') );
		}

		function add_settings_menu( $arg ) {
			$default = [
				'title' => 'Settings API',
				'menu_title' => 'Settings API',
				'capability' => 'manage_options',
				'slug' => 'settings_api_test',
				'callback' => function() {},
				'parent_slug' => null, //if given, it will be submenu
				'type' => 'menu', //options: theme_option, settings
			];
			$arg = array_merge( $default, $arg );
			$this->settings_menu[$arg['slug']] = $arg;
			Admin_Menu_Manager::instance()->add_menu( $arg );
		}

		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		function admin_menu() {
			add_options_page( 'Settings API', 'Settings API', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'wedevs_basics',
					'title' => __( 'Basic Settings', 'wedevs' )
				),
				array(
					'id'    => 'wedevs_advanced',
					'title' => __( 'Advanced Settings', 'wedevs' )
				)
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {
			$settings_fields = array(
				'wedevs_basics' => array(
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
				'wedevs_advanced' => array(
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
			);

			return $settings_fields;
		}

		function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ($pages as $page) {
					$pages_options[$page->ID] = $page->post_title;
				}
			}

			return $pages_options;
		}

	}
endif;
