<?php
namespace App\includes\core;

use App\includes\core\traits\Fields;

class MetaBoxManager {

	use Fields;

	protected $metaboxes = [];

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

	// Initialize the MetaBoxManager
	public function __construct() {
		add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
		add_action('save_post', array($this, 'saveMetaBoxes'));
	}

	/**
	 * @param $metabox_id
	 * @param $arg array [ title, callback, screen, context, priority, fields = array ]
	 *
	 * @return void
	 */
	public function add_metabox( $metabox_id, $arg ) {
		$this->metaboxes[$metabox_id] = $arg;
	}

	// Register meta boxes
	public function registerMetaBoxes() {
		foreach ( $this->metaboxes as $metabox_id => $metabox_arg ) {
			add_meta_box(
				$metabox_id,
				$metabox_arg['title'],
				array($this, 'renderMetaBox'),
				$metabox_arg['screen'], // post type
				$metabox_arg['context'],
				$metabox_arg['priority'],
				[ 'metabox_id' => $metabox_id ]
			);
		}
	}

	// Render the meta box content
	public function renderMetaBox($post, $args) {
		//if there is any callback, call it
		if ( isset( $this->metaboxes[$args['id']]['callback'] ) ) {
			$this->metaboxes[$args['id']]['callback']();
		}

		if ( isset( $this->metaboxes[$args['id']]['fields'] ) ) {
			/*$fields = [
				'field_name_1' => [
					'name' => 'field_name_1',
					'type' => 'text',
					'title' => 'First name',
					'description' => 'This is first name'
				]
			];*/
			foreach ( $this->metaboxes[$args['id']]['fields'] as $field_name => $field_array ) {
				if ( isset( $field_array['type'] ) ) {
					if ( method_exists( $this, $field_array['type'] ) ) {
						$this->{$field_array['type']}($field_array);
					}
				}
			}
		}

		// Retrieve the current value of the meta field
		$meta_value = get_post_meta($post->ID, 'custom_meta_field', true);
		?>
		<label for="custom_meta_field">Custom Field:</label>
		<input type="text" id="custom_meta_field" name="custom_meta_field" value="<?php echo esc_attr($meta_value); ?>">
		<?php
	}

	// Save meta box data
	public function saveMetaBoxes($post_id) {
		// Check if the nonce is set.
		if (!isset($_POST['meta_box_nonce'])) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($_POST['meta_box_nonce'], 'my_custom_meta_box_nonce')) {
			return $post_id;
		}

		// Check if this is an autosave.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// Check the post type.
		if ('post' !== $_POST['post_type']) {
			return $post_id;
		}

		// Sanitize user input.
		$meta_value = sanitize_text_field($_POST['custom_meta_field']);

		// Update the meta field in the database.
		update_post_meta($post_id, 'custom_meta_field', $meta_value);
	}
}

// Instantiate the MetaBoxManager
//new MetaBoxManager();
