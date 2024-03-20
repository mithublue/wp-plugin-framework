# WP Plugin Framework
A framework for wp plugin development

## Installation

Just download this plugin and activate it. That's it ! Then, create your own plugin or theme code and use the following features the way it is shown below.

# Features

### Template management
### Module concept
### Post Types
### Taxonomy

### Eloquent ORM
You can use Laravel Eloquent ORM. The documentation is here: https://github.com/as247/wp-eloquent 

### Metabox manager

```php
  \WP_Plugin_Framework::metabox_manager()->add_metabox( 'test', [
	'title' => 'Test metabox',
	'screen' => 'page',
	'context' => 'normal',
	'priority' => 'default',
	'fields' => [
		'field_name_1' => [
			'name' => 'field_name_1',
			'type' => 'text',
			'title' => 'First name',
			'desc' => 'This is first name',
			'value' => ''
		]
	]
] );
```
### Settings/options manager

```php
\WP_Plugin_Framework::settings_manager()->add_settings_menu(
	[
		'title' => 'Settings API',
		'menu_title' => 'Settings API',
		'capability' => 'manage_options',
		'slug' => 'settings_api_test',
		'parent_slug' => null, //if given, it will be submenu
		'type' => 'menu', //options: theme_option, settings
		'sections' => [
			[
				'id'    => 'wppf_basics',
				'title' => __( 'Basic Settings', 'wppf' )
			],
			[
				'id'    => 'wppf_advanced',
				'title' => __( 'Advanced Settings', 'wppf' )
			],
		],
		'fields' => array(
			'wppf_basics' => array(
				array(
					'name'              => 'text_val',
					'label'             => __( 'Text Input', 'wppf' ),
					'desc'              => __( 'Text input description', 'wppf' ),
					'placeholder'       => __( 'Text Input placeholder', 'wppf' ),
					'type'              => 'text',
					'default'           => 'Title',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'number_input',
					'label'             => __( 'Number Input', 'wppf' ),
					'desc'              => __( 'Number field with validation callback `floatval`', 'wppf' ),
					'placeholder'       => __( '1.99', 'wppf' ),
					'min'               => 0,
					'max'               => 100,
					'step'              => '0.01',
					'type'              => 'number',
					'default'           => 'Title',
					'sanitize_callback' => 'floatval'
				),
				array(
					'name'        => 'textarea',
					'label'       => __( 'Textarea Input', 'wppf' ),
					'desc'        => __( 'Textarea description', 'wppf' ),
					'placeholder' => __( 'Textarea placeholder', 'wppf' ),
					'type'        => 'textarea'
				),
				array(
					'name'        => 'html',
					'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wppf' ),
					'type'        => 'html'
				),
				array(
					'name'  => 'checkbox',
					'label' => __( 'Checkbox', 'wppf' ),
					'desc'  => __( 'Checkbox Label', 'wppf' ),
					'type'  => 'checkbox'
				),
				array(
					'name'    => 'radio',
					'label'   => __( 'Radio Button', 'wppf' ),
					'desc'    => __( 'A radio button', 'wppf' ),
					'type'    => 'radio',
					'options' => array(
						'yes' => 'Yes',
						'no'  => 'No'
					)
				),
				array(
					'name'    => 'selectbox',
					'label'   => __( 'A Dropdown', 'wppf' ),
					'desc'    => __( 'Dropdown description', 'wppf' ),
					'type'    => 'select',
					'default' => 'no',
					'options' => array(
						'yes' => 'Yes',
						'no'  => 'No'
					)
				),
				array(
					'name'    => 'password',
					'label'   => __( 'Password', 'wppf' ),
					'desc'    => __( 'Password description', 'wppf' ),
					'type'    => 'password',
					'default' => ''
				),
				array(
					'name'    => 'file',
					'label'   => __( 'File', 'wppf' ),
					'desc'    => __( 'File description', 'wppf' ),
					'type'    => 'file',
					'default' => '',
					'options' => array(
						'button_label' => 'Choose Image'
					)
				)
			),
			'wppf_advanced' => array(
				array(
					'name'    => 'color',
					'label'   => __( 'Color', 'wppf' ),
					'desc'    => __( 'Color description', 'wppf' ),
					'type'    => 'color',
					'default' => ''
				),
				array(
					'name'    => 'password',
					'label'   => __( 'Password', 'wppf' ),
					'desc'    => __( 'Password description', 'wppf' ),
					'type'    => 'password',
					'default' => ''
				),
				array(
					'name'    => 'wysiwyg',
					'label'   => __( 'Advanced Editor', 'wppf' ),
					'desc'    => __( 'WP_Editor description', 'wppf' ),
					'type'    => 'wysiwyg',
					'default' => ''
				),
				array(
					'name'    => 'multicheck',
					'label'   => __( 'Multile checkbox', 'wppf' ),
					'desc'    => __( 'Multi checkbox description', 'wppf' ),
					'type'    => 'multicheck',
					'default' => array('one' => 'one', 'four' => 'four'),
					'options' => array(
						'one'   => 'One',
						'two'   => 'Two',
						'three' => 'Three',
						'four'  => 'Four'
					)
				),
			)
		)
	]
);
```
### Admin menu manager
```php
\WP_Plugin_Framework::admin_menu_manager()->add_menu( [
	'title' => 'WPPF Admin Menu',
	'menu_title' => 'WPPF Admin Menu Page',
	'capability' => 'manage_options',
	'slug' => 'settings_api_test',
	'callback' => function() {},
	'parent_slug' => null, //if given, it will be submenu
	'type' => 'menu', //options: theme_option, settings
] );
```

More is coming.... :)

Upcoming features:
### Routes
