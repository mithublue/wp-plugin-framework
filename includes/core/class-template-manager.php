<?php
namespace App\includes\core;

/**
 * Template Manager class for WordPress Plugin Framework
 */
class WPPF_TemplateManager {

	/**
	 * Render a template
	 *
	 * @param string $template_name The name of the template file (without .php extension)
	 * @param array $data Optional data to pass to the template
	 */
	public static function render_template($template_name, $data = array()) {
		$template_path = PLUGIN_DIR . '/includes/templates/' . $template_name . '.php';

		if (file_exists($template_path)) {
			extract($data);
			include $template_path;
		} else {
			// Template not found, handle the error accordingly
			echo '<p>Template not found: ' . $template_name . '</p>';
		}
	}
}
