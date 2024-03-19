<?php

namespace App\includes\admin;

class Admin_Menu_Manager{

	protected $admin_menu = [];

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

	public function __construct() {
		add_action( 'admin_menu', array($this, 'generate_admin_menus') );
	}

	public function add_menu( $arg ) {
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
		$this->admin_menu[$arg['slug']] = $arg;
	}

	/**
	 * Genrate all menu items
	 * @return void
	 */
	public function generate_admin_menus() {
		foreach ( $this->admin_menu as $menu_slug => $menu ) {
			if ( empty( $menu['parent_slug'] ) ) {
				add_menu_page( $menu['title'], $menu['menu_title'], $menu['capability'], $menu['slug'], $menu['callback'] );
			} else {
				add_submenu_page( $menu['parent_slug'], $menu['title'], $menu['menu_title'], $menu['capability'], $menu['slug'], $menu['callback'] );
			}
		}
	}
}
