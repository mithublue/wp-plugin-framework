<?php
/**
 * Settings class for WordPress Plugin Framework
 */
class WPPF_PluginSettings {

	/**
	 * The option group used for settings
	 * @var string
	 */
	private $option_group;

	/**
	 * The name of the settings page
	 * @var string
	 */
	private $page_slug;

	/**
	 * The array of settings fields
	 * @var array
	 */
	private $settings;

	/**
	 * Initialize the settings class
	 *
	 * @param string $option_group
	 * @param string $page_slug
	 * @param array $settings
	 */
	public function __construct($option_group, $page_slug, $settings) {
		$this->option_group = $option_group;
		$this->page_slug = $page_slug;
		$this->settings = $settings;

		add_action('admin_menu', array($this, 'add_settings_page'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	/**
	 * Add the settings page to the admin menu
	 */
	public function add_settings_page() {
		add_menu_page(
			__('Plugin Settings', 'my-plugin-domain'),
			__('Plugin Settings', 'my-plugin-domain'),
			'manage_options',
			$this->page_slug,
			array($this, 'render_settings_page'),
			'dashicons-admin-generic',
			30
		);
	}

	/**
	 * Render the settings page
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">
			<h2><?php _e('Plugin Settings', 'my-plugin-domain'); ?></h2>
			<form method="post" action="options.php">
				<?php
				settings_fields($this->option_group);
				do_settings_sections($this->page_slug);
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings with the WordPress Settings API
	 */
	public function register_settings() {
		foreach ($this->settings as $section => $fields) {
			add_settings_section(
				$section,
				$fields['title'],
				array($this, 'render_section'),
				$this->page_slug
			);

			foreach ($fields['fields'] as $field) {
				add_settings_field(
					$field['id'],
					$field['label'],
					array($this, 'render_field'),
					$this->page_slug,
					$section,
					$field
				);
			}
		}

		register_setting($this->option_group, $this->option_group, array($this, 'sanitize_settings'));
	}

	/**
	 * Sanitize and validate settings
	 *
	 * @param array $input
	 * @return array
	 */
	public function sanitize_settings($input) {
		foreach ($this->settings as $section => $fields) {
			foreach ($fields['fields'] as $field) {
				if (isset($input[$field['id']])) {
					$input[$field['id']] = sanitize_text_field($input[$field['id']]);
				}
			}
		}
		return $input;
	}

	/**
	 * Render settings section
	 *
	 * @param array $section
	 */
	public function render_section($section) {
		// You can output additional section information here
	}

	/**
	 * Render settings field
	 *
	 * @param array $field
	 */
	public function render_field($field) {
		$value = get_option($this->option_group);

		switch ($field['type']) {
			case 'text':
				printf(
					'<input type="text" id="%s" name="%s[%s]" value="%s" />',
					$field['id'],
					$this->option_group,
					$field['id'],
					isset($value[$field['id']]) ? esc_attr($value[$field['id']]) : ''
				);
				break;

			// Add more field types as needed

			default:
				break;
		}

		if (isset($field['description'])) {
			echo '<p class="description">' . esc_html($field['description']) . '</p>';
		}
	}
}
