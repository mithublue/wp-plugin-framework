<?php
namespace App\includes\core;

use App\includes\core\traits\Fields;

class MetaBoxManager {

	use Fields;

	protected $metaboxes = [];
	protected $nonce_metaboxes = [];

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
		$this->list_metabox( $metabox_id, $arg );
		$this->list_nonce( $metabox_id, $arg );
	}

	public function list_metabox( $metabox_id, $arg ) {
		$this->metaboxes[$metabox_id] = $arg;
	}

	public function list_nonce( $metabox_id, $arg ) {
		$this->nonce_metaboxes['metabox_nonce_'.$metabox_id] = $arg;
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
					'desc' => 'This is first name',
					'value' => ''
				]
			];*/
			?>
			<input type="hidden" name="metabox_nonce_<?php echo $args['id']; ?>" value="<?php echo esc_attr( wp_create_nonce( $args['id'] ) ); ?>">
			<?php
			foreach ( $this->metaboxes[$args['id']]['fields'] as $field_name => $field_array ) {

				//set value to the field
				$field_array['value'] = get_post_meta( $post->ID, $field_array['name'], true );

				if ( isset( $field_array['type'] ) ) {
					if ( method_exists( $this, $field_array['type'] ) ) {
						$this->{$field_array['type']}($field_array);
					}
				}
			}
		}
	}

	/**
	 * Save metabox
	 * @param $post_id
	 *
	 * @return void
	 */
	public function saveMetaBoxes( $post_id ) {

		foreach ( $this->metaboxes as $metabox_id => $metabox_array ) {
			// Check if the nonce is set.
			if ( ! isset( $_POST['metabox_nonce_'.$metabox_id] ) ) {
				continue;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['metabox_nonce_'.$metabox_id], $metabox_id ) ) {
				continue;
			}

			// Check if this is an autosave.
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				continue;
			}

			// Check the post type.
			if ( $metabox_array['screen'] !== $_POST['post_type']) {
				continue;
			}

			$this->save_data( $post_id, $metabox_array['fields'], $_POST );
		}
	}

	/**
	 * @param $post_id
	 * @param $field_array
	 * @param $postdata
	 *
	 * @return void
	 */
	public function save_data( $post_id, $field_array, $postdata ) {
		foreach ( $field_array as $name => $field ) {
			if ( isset( $postdata[$field['name']] ) ) {
				// Sanitize user input and update.
				$meta_value = sanitize_text_field( $postdata[$field['name']] );
				update_post_meta( $post_id, $field['name'], $meta_value);
			}
		}
	}
}
