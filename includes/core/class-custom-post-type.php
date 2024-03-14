<?php
/**
 * Custom Post Type class for WordPress Plugin Framework
 */
class WPPF_CustomPostType {

	/**
	 * The post type slug
	 * @var string
	 */
	private $post_type;

	/**
	 * The labels for the post type
	 * @var array
	 */
	private $labels;

	/**
	 * The arguments for the post type
	 * @var array
	 */
	private $args;

	/**
	 * Initialize the custom post type class
	 *
	 * @param string $post_type
	 * @param array $labels
	 * @param array $args
	 */
	public function __construct($post_type, $labels, $args = array()) {
		$this->post_type = $post_type;
		$this->labels = $labels;
		$this->args = $args;

		add_action('init', array($this, 'register_post_type'));
	}

	/**
	 * Register the custom post type
	 */
	public function register_post_type() {
		$default_labels = array(
			'name'               => _x('Custom Post Type', 'post type general name', 'my-plugin-domain'),
			'singular_name'      => _x('Custom Post', 'post type singular name', 'my-plugin-domain'),
			'menu_name'          => _x('Custom Posts', 'admin menu', 'my-plugin-domain'),
			'name_admin_bar'     => _x('Custom Post', 'add new on admin bar', 'my-plugin-domain'),
			'add_new'            => _x('Add New', 'custom post', 'my-plugin-domain'),
			'add_new_item'       => __('Add New Custom Post', 'my-plugin-domain'),
			'new_item'           => __('New Custom Post', 'my-plugin-domain'),
			'edit_item'          => __('Edit Custom Post', 'my-plugin-domain'),
			'view_item'          => __('View Custom Post', 'my-plugin-domain'),
			'all_items'          => __('All Custom Posts', 'my-plugin-domain'),
			'search_items'       => __('Search Custom Posts', 'my-plugin-domain'),
			'parent_item_colon'  => __('Parent Custom Posts:', 'my-plugin-domain'),
			'not_found'          => __('No custom posts found.', 'my-plugin-domain'),
			'not_found_in_trash' => __('No custom posts found in Trash.', 'my-plugin-domain'),
		);

		$default_args = array(
			'labels'             => array_merge($default_labels, $this->labels),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => $this->post_type),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		);

		$args = wp_parse_args($this->args, $default_args);

		register_post_type($this->post_type, $args);
	}
}
