<?php
/**
 * Taxonomy class for WordPress Plugin Framework
 */
class WPPF_Taxonomy {

	/**
	 * The taxonomy slug
	 * @var string
	 */
	private $taxonomy;

	/**
	 * The post types to which the taxonomy should be attached
	 * @var array
	 */
	private $post_types;

	/**
	 * The labels for the taxonomy
	 * @var array
	 */
	private $labels;

	/**
	 * The arguments for the taxonomy
	 * @var array
	 */
	private $args;

	/**
	 * Initialize the taxonomy class
	 *
	 * @param string $taxonomy
	 * @param array $post_types
	 * @param array $labels
	 * @param array $args
	 */
	public function __construct($taxonomy, $post_types, $labels, $args = array()) {
		$this->taxonomy = $taxonomy;
		$this->post_types = $post_types;
		$this->labels = $labels;
		$this->args = $args;

		add_action('init', array($this, 'register_taxonomy'));
	}

	/**
	 * Register the custom taxonomy
	 */
	public function register_taxonomy() {
		$default_labels = array(
			'name'                       => _x('Custom Taxonomy', 'taxonomy general name', 'my-plugin-domain'),
			'singular_name'              => _x('Custom Taxonomy', 'taxonomy singular name', 'my-plugin-domain'),
			'search_items'               => __('Search Custom Taxonomies', 'my-plugin-domain'),
			'popular_items'              => __('Popular Custom Taxonomies', 'my-plugin-domain'),
			'all_items'                  => __('All Custom Taxonomies', 'my-plugin-domain'),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __('Edit Custom Taxonomy', 'my-plugin-domain'),
			'update_item'                => __('Update Custom Taxonomy', 'my-plugin-domain'),
			'add_new_item'               => __('Add New Custom Taxonomy', 'my-plugin-domain'),
			'new_item_name'              => __('New Custom Taxonomy Name', 'my-plugin-domain'),
			'separate_items_with_commas' => __('Separate custom taxonomies with commas', 'my-plugin-domain'),
			'add_or_remove_items'        => __('Add or remove custom taxonomies', 'my-plugin-domain'),
			'choose_from_most_used'      => __('Choose from the most used custom taxonomies', 'my-plugin-domain'),
			'not_found'                  => __('No custom taxonomies found.', 'my-plugin-domain'),
			'menu_name'                  => __('Custom Taxonomy', 'my-plugin-domain'),
		);

		$default_args = array(
			'labels'            => array_merge($default_labels, $this->labels),
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'rewrite'           => array('slug' => $this->taxonomy),
		);

		$args = wp_parse_args($this->args, $default_args);

		register_taxonomy($this->taxonomy, $this->post_types, $args);
	}
}
