<?php
/**
 * Model loader class
 */
namespace App\includes\utils;

class WPPF_Model{

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

    }

	public function model( $model ) {
		if ( ! class_exists( $model ) ) {
			require_once PLUGIN_DIR . '/' . str_replace( 'App\\','',$model) . '.php';
//			require_once PLUGIN_DIR . '/includes/models/' . $model . '.php';
			return new $model();
		}

		return null;
	}
}

function wppf_model() {
	return WPPF_Model::instance();
}
