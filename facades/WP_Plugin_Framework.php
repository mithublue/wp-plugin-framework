<?php
class WP_Plugin_Framework{

	protected static $instance;

	protected static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new \App\WP_PluginFramework();
		}
		return self::$instance;
	}

	public static function __callStatic($method, $args)
	{
		$instance = static::getInstance();

		if (!method_exists($instance, $method)) {
			throw new \BadMethodCallException("Method $method does not exist");
		}

		return call_user_func_array([$instance, $method], $args);
	}
}
