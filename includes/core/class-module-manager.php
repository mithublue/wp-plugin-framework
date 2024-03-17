<?php
namespace App\includes\core;
/**
 * Module Manager class for WordPress Plugin Framework
 */
class WPPF_ModuleManager {

	/**
	 * List of registered modules
	 *
	 * @var array
	 */
	private $modules = array();

	/**
	 * Register a module
	 *
	 * @param string $module_name
	 * @param object $module_instance
	 */
	public function register_module($module_name, $module_instance = null) {
		require_once PLUGIN_DIR . '/modules/' . $module_name . '/class-'.$module_name.'.php';
		$module_instance = new $module_instance();
		$this->modules[$module_name] = $module_instance;
	}

	/**
	 * Initialize all registered modules
	 */
	public function init_modules() {
		foreach ($this->modules as $module) {
			if (method_exists($module, 'init')) {
				$module->init();
			}
		}
	}

	/**
	 * Get a list of registered modules
	 *
	 * @return array
	 */
	public function get_registered_modules() {
		return $this->modules;
	}
}
