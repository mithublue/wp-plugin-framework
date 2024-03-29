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
			add_action( 'admin_init', array($this, 'admin_init') );
		}

		function add_settings_menu( $arg ) {
			$default = [
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
					]
				],
				'fields' => array(
					'wppf_basics' => array(
						array(
							'name'              => 'text_val',
							'label'             => __( 'Text Input', 'wppf' ),
							'desc'              => __( 'Text input description', 'wppf' ),
							'placeholder'       => __( 'Text Input placeholder', 'wppf' ),
							'type'              => 'text',
							'default'           => 'Title',
							'sanitize_callback' => 'sanitize_text_field'
						),
						array(
							'name'              => 'number_input',
							'label'             => __( 'Number Input', 'wppf' ),
							'desc'              => __( 'Number field with validation callback `floatval`', 'wppf' ),
							'placeholder'       => __( '1.99', 'wppf' ),
							'min'               => 0,
							'max'               => 100,
							'step'              => '0.01',
							'type'              => 'number',
							'default'           => 'Title',
							'sanitize_callback' => 'floatval'
						),
						array(
							'name'        => 'textarea',
							'label'       => __( 'Textarea Input', 'wppf' ),
							'desc'        => __( 'Textarea description', 'wppf' ),
							'placeholder' => __( 'Textarea placeholder', 'wppf' ),
							'type'        => 'textarea'
						),
						array(
							'name'        => 'html',
							'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wppf' ),
							'type'        => 'html'
						),
						array(
							'name'  => 'checkbox',
							'label' => __( 'Checkbox', 'wppf' ),
							'desc'  => __( 'Checkbox Label', 'wppf' ),
							'type'  => 'checkbox'
						),
						array(
							'name'    => 'radio',
							'label'   => __( 'Radio Button', 'wppf' ),
							'desc'    => __( 'A radio button', 'wppf' ),
							'type'    => 'radio',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No'
							)
						),
						array(
							'name'    => 'selectbox',
							'label'   => __( 'A Dropdown', 'wppf' ),
							'desc'    => __( 'Dropdown description', 'wppf' ),
							'type'    => 'select',
							'default' => 'no',
							'options' => array(
								'yes' => 'Yes',
								'no'  => 'No'
							)
						),
						array(
							'name'    => 'password',
							'label'   => __( 'Password', 'wppf' ),
							'desc'    => __( 'Password description', 'wppf' ),
							'type'    => 'password',
							'default' => ''
						),
						array(
							'name'    => 'file',
							'label'   => __( 'File', 'wppf' ),
							'desc'    => __( 'File description', 'wppf' ),
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
							'label'   => __( 'Color', 'wppf' ),
							'desc'    => __( 'Color description', 'wppf' ),
							'type'    => 'color',
							'default' => ''
						),
						array(
							'name'    => 'password',
							'label'   => __( 'Password', 'wppf' ),
							'desc'    => __( 'Password description', 'wppf' ),
							'type'    => 'password',
							'default' => ''
						),
						array(
							'name'    => 'wysiwyg',
							'label'   => __( 'Advanced Editor', 'wppf' ),
							'desc'    => __( 'WP_Editor description', 'wppf' ),
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
			];
			$arg = array_merge( $default, $arg );
			//making sure, there is no callback is set from outside in args.
			$arg['callback'] = function() {
				$this->plugin_page( $_REQUEST['page'] );
			};
			$this->settings_api[ $arg['slug'] ] = new \WeDevs_Settings_API();
			$this->settings_menu[$arg['slug']] = $arg;

			//making admin menu
			Admin_Menu_Manager::instance()->add_menu( $arg );
		}

		function admin_init() {
			foreach ( $this->settings_api as $each_slug => $settings_obj ) {
				//set the settings
				$settings_obj->set_sections( $this->get_settings_sections( $each_slug ) );
				$settings_obj->set_fields( $this->get_settings_fields( $each_slug ) );
				//initialize settings
				$settings_obj->admin_init();
			}
		}

		function get_settings_sections( $settings_slug ) {
			//returing the sections associated with this slugs in the settings_menu
			$sections = $this->settings_menu[$settings_slug]['sections'];
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields( $settings_slug ) {
			//returning the fields for the sections
			$settings_fields = $this->settings_menu[$settings_slug]['fields'];
			return $settings_fields;
		}

		function plugin_page( $slug ) {
			echo '<div class="wrap">';

			$this->settings_api[$slug]->show_navigation();
			$this->settings_api[$slug]->show_forms();

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
