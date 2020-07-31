<?php
//Shortcodes for Visual Composer
add_action( 'vc_before_init', 'safira_vc_shortcodes' );
function safira_vc_shortcodes() { 
	//Site logo
	vc_map( array(
		'name' => esc_html__( 'Logo', 'safira'),
		'description' => esc_html__( 'Insert logo image', 'safira' ),
		'base' => 'roadlogo',
		'class' => '',
		'category' => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params' => array(
			array(
				'type'       => 'attach_image',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Upload logo image', 'safira' ),
				'description'=> esc_html__( 'Note: For retina screen, logo image size is at least twice as width and height (width is set below) to display clearly', 'safira' ),
				'param_name' => 'logo_image',
				'value'      => '',
			),
			array(
				'type' => 'dropdown',
				'holder' => 'div',
				'class' => '',
				'heading' => esc_html__( 'Insert logo link or not', 'safira' ),
				'param_name' => 'logo_link',
				'value' => array(
					esc_html__( 'Yes', 'safira' )	=> 1,
					esc_html__( 'No', 'safira' )	=> 0,
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Logo width (unit: px)', 'safira' ),
				'description'=> esc_html__( 'Insert number. Leave blank if you want to use original image size', 'safira' ),
				'param_name' => 'logo_width',
				'value'      => esc_html__( '150', 'safira' ),
			),
		)
	) );
	//Main Menu
	vc_map( array(
		'name'        => esc_html__( 'Main Menu', 'safira'),
		'description' => esc_html__( 'Set Primary Menu in Apperance - Menus - Manage Locations', 'safira' ),
		'base'        => 'roadmainmenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Set Primary Menu in Apperance - Menus - Manage Locations', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Sticky Menu
	vc_map( array(
		'name'        => esc_html__( 'Sticky Menu', 'safira'),
		'description' => esc_html__( 'Set Sticky Menu in Apperance - Menus - Manage Locations', 'safira' ),
		'base'        => 'roadstickymenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Set Sticky Menu in Apperance - Menus - Manage Locations', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Mobile Menu
	vc_map( array(
		'name'        => esc_html__( 'Mobile Menu', 'safira'),
		'description' => esc_html__( 'Set Mobile Menu in Apperance - Menus - Manage Locations', 'safira' ),
		'base'        => 'roadmobilemenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Set Mobile Menu in Apperance - Menus - Manage Locations', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Wishlist
	vc_map( array(
		'name'        => esc_html__( 'Wishlist', 'safira'),
		'description' => esc_html__( 'Wishlist', 'safira' ),
		'base'        => 'roadwishlist',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Categories Menu
	vc_map( array(
		'name'        => esc_html__( 'Categories Menu', 'safira'),
		'description' => esc_html__( 'Set Categories Menu in Apperance - Menus - Manage Locations', 'safira' ),
		'base'        => 'roadcategoriesmenu',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Set Categories Menu in Apperance - Menus - Manage Locations', 'safira' ),
				'param_name' => 'no_settings',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Inner Category Menu', 'safira' ),
				'description' => esc_html__( 'Always show category menu on inner pages', 'safira' ),
				'param_name' => 'categories_menu_sub',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Home Category Menu', 'safira' ),
				'description' => esc_html__( 'Always show category menu on home page', 'safira' ),
				'param_name' => 'categories_menu_home',
			),
		),
	) );
	//Social Icons
	vc_map( array(
		'name'        => esc_html__( 'Social Icons', 'safira'),
		'description' => esc_html__( 'Configure icons and links in Theme Options', 'safira' ),
		'base'        => 'roadsocialicons',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Mini Cart
	vc_map( array(
		'name'        => esc_html__( 'Mini Cart', 'safira'),
		'description' => esc_html__( 'Mini Cart', 'safira' ),
		'base'        => 'roadminicart',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Products Search without dropdown
	vc_map( array(
		'name'        => esc_html__( 'Product Search (No dropdown)', 'safira'),
		'description' => esc_html__( 'Product Search (No dropdown)', 'safira' ),
		'base'        => 'roadproductssearch',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Products Search with dropdown
	vc_map( array(
		'name'        => esc_html__( 'Product Search (Dropdown)', 'safira'),
		'description' => esc_html__( 'Product Search (Dropdown)', 'safira' ),
		'base'        => 'roadproductssearchdropdown',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => '',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'This widget does not have settings', 'safira' ),
				'param_name' => 'no_settings',
			),
		),
	) );
	//Image slider
	vc_map( array(
		'name'        => esc_html__( 'Image slider', 'safira' ),
		'description' => esc_html__( 'Upload images and links in Theme Options', 'safira' ),
		'base'        => 'image_slider',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'safira' ),
				'param_name' => 'rows',
				'value'      => array(
					esc_html__( '1', 'safira' )	=> '1',
					esc_html__( '2', 'safira' )	=> '2',
					esc_html__( '3', 'safira' )	=> '3',
					esc_html__( '4', 'safira' )	=> '4',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: over 1201px)', 'safira' ),
				'param_name' => 'items_1200up',
				'value'      => esc_html__( '4', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 992px - 1199px)', 'safira' ),
				'param_name' => 'items_992_1199',
				'value'      => esc_html__( '4', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 768px - 991px)', 'safira' ),
				'param_name' => 'items_768_991',
				'value'      => esc_html__( '3', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 640px - 767px)', 'safira' ),
				'param_name' => 'items_640_767',
				'value'      => esc_html__( '2', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 480px - 639px)', 'safira' ),
				'param_name' => 'items_480_639',
				'value'      => esc_html__( '2', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: under 479px)', 'safira' ),
				'param_name' => 'items_0_479',
				'value'      => esc_html__( '1', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Navigation', 'safira' ),
				'param_name' => 'navigation',
				'value'      => array(
					esc_html__( 'Yes', 'safira' ) => true,
					esc_html__( 'No', 'safira' )  => false,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Pagination', 'safira' ),
				'param_name' => 'pagination',
				'value'      => array(
					esc_html__( 'No', 'safira' )  => false,
					esc_html__( 'Yes', 'safira' ) => true,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Item Margin (unit: pixel)', 'safira' ),
				'param_name' => 'item_margin',
				'value'      => esc_html__( '30', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Slider speed number (unit: second)', 'safira' ),
				'param_name' => 'speed',
				'value'      => esc_html__( '500', 'safira' ),
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider loop', 'safira' ),
				'param_name' => 'loop',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider Auto', 'safira' ),
				'param_name' => 'auto',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'safira' ),
				'param_name' => 'style',
				'value'      => array(
					esc_html__( 'Style 1', 'safira' )  => 'style1',
				),
			)
		),
	) );
	//Brand logos
	vc_map( array(
		'name'        => esc_html__( 'Brand Logos', 'safira' ),
		'description' => esc_html__( 'Upload images and links in Theme Options', 'safira' ),
		'base'        => 'ourbrands',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'safira' ),
				'param_name' => 'rows',
				'value'      => array(
					esc_html__( '1', 'safira' )	=> '1',
					esc_html__( '2', 'safira' )	=> '2',
					esc_html__( '3', 'safira' )	=> '3',
					esc_html__( '4', 'safira' )	=> '4',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: over 1201px)', 'safira' ),
				'param_name' => 'items_1201up',
				'value'      => esc_html__( '5', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 992px - 1199px)', 'safira' ),
				'param_name' => 'items_992_1199',
				'value'      => esc_html__( '5', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 768px - 991px)', 'safira' ),
				'param_name' => 'items_768_991',
				'value'      => esc_html__( '4', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 640px - 767px)', 'safira' ),
				'param_name' => 'items_640_767',
				'value'      => esc_html__( '3', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 480px - 639px)', 'safira' ),
				'param_name' => 'items_480_639',
				'value'      => esc_html__( '2', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: under 479px)', 'safira' ),
				'param_name' => 'items_0_479',
				'value'      => esc_html__( '1', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Navigation', 'safira' ),
				'param_name' => 'navigation',
				'value'      => array(
					esc_html__( 'Yes', 'safira' ) => true,
					esc_html__( 'No', 'safira' )  => false,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Pagination', 'safira' ),
				'param_name' => 'pagination',
				'value'      => array(
					esc_html__( 'No', 'safira' )  => false,
					esc_html__( 'Yes', 'safira' ) => true,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Item Margin (unit: pixel)', 'safira' ),
				'param_name' => 'item_margin',
				'value'      => esc_html__( '0', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    =>  esc_html__( 'Slider speed number (unit: second)', 'safira' ),
				'param_name' => 'speed',
				'value'      => esc_html__( '500', 'safira' ),
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider loop', 'safira' ),
				'param_name' => 'loop',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider Auto', 'safira' ),
				'param_name' => 'auto',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Style', 'safira' ),
				'param_name' => 'style',
				'value'      => array(
					esc_html__( 'Style 1', 'safira' )       => 'style1',
				),
			)
		),
	) );
	//Latest posts
	vc_map( array(
		'name'        => esc_html__( 'Latest posts', 'safira' ),
		'description' => esc_html__( 'List posts', 'safira' ),
		'base'        => 'latestposts',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"        => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of posts', 'safira' ),
				'param_name' => 'posts_per_page',
				'value'      => esc_html__( '10', 'safira' ),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Category', 'safira' ),
				'param_name'  => 'category',
				'value'       => esc_html__( '0', 'safira' ),
				'description' => esc_html__( 'Slug of the category (example: slug-1, slug-2). Default is 0 : show all posts', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Image scale', 'safira' ),
				'param_name' => 'image',
				'value'      => array(
					esc_html__( 'Wide', 'safira' )	=> 'wide',
					esc_html__( 'Square', 'safira' ) => 'square',
				),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Excerpt length', 'safira' ),
				'param_name' => 'length',
				'value'      => esc_html__( '20', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns', 'safira' ),
				'param_name' => 'colsnumber',
				'value'      => array(
					esc_html__( '1', 'safira' )	=> '1',
					esc_html__( '2', 'safira' )	=> '2',
					esc_html__( '3', 'safira' )	=> '3',
					esc_html__( '4', 'safira' )	=> '4',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Enable slider', 'safira' ),
				'param_name'  => 'enable_slider',
				'value'       => true,
				'save_always' => true, 
				'group'       => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of rows', 'safira' ),
				'param_name' => 'rowsnumber',
				'group'      => esc_html__( 'Slider Options', 'safira' ),
				'value'      => array(
						esc_html__( '1', 'safira' )	=> '1',
						esc_html__( '2', 'safira' )	=> '2',
						esc_html__( '3', 'safira' )	=> '3',
						esc_html__( '4', 'safira' )	=> '4',
						esc_html__( '5', 'safira' )	=> '5',
					),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 992px - 1199px)', 'safira' ),
				'param_name' => 'items_992_1199',
				'value'      => esc_html__( '3', 'safira' ),
				'group'      => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 768px - 991px)', 'safira' ),
				'param_name' => 'items_768_991',
				'value'      => esc_html__( '3', 'safira' ),
				'group'      => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 640px - 767px)', 'safira' ),
				'param_name' => 'items_640_767',
				'value'      => esc_html__( '2', 'safira' ),
				'group'      => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: 480px - 639px)', 'safira' ),
				'param_name' => 'items_480_639',
				'value'      => esc_html__( '2', 'safira' ),
				'group'      => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of columns (screen: under 479px)', 'safira' ),
				'param_name' => 'items_0_479',
				'value'      => esc_html__( '1', 'safira' ),
				'group'      => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Navigation', 'safira' ),
				'param_name'  => 'navigation',
				'save_always' => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
				'value'       => array(
					esc_html__( 'Yes', 'safira' ) => true,
					esc_html__( 'No', 'safira' )  => false,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Pagination', 'safira' ),
				'param_name'  => 'pagination',
				'save_always' => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
				'value'       => array(
					esc_html__( 'No', 'safira' )  => false,
					esc_html__( 'Yes', 'safira' ) => true,
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Item Margin (unit: pixel)', 'safira' ),
				'param_name'  => 'item_margin',
				'value'       => esc_html__( '30', 'safira' ),
				'save_always' => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Slider speed number (unit: second)', 'safira' ),
				'param_name'  => 'speed',
				'value'       => esc_html__( '500', 'safira' ),
				'save_always' => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Slider loop', 'safira' ),
				'param_name'  => 'loop',
				'value'       => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Slider Auto', 'safira' ),
				'param_name'  => 'auto',
				'value'       => true,
				'group'       => esc_html__( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Navigation Style', 'safira' ),
				'param_name'  => 'navigation_style',
				'value'       => array(
					__( 'Style 1', 'safira' ) => 'nav-style',
					__( 'Style 2', 'safira' ) => 'nav-style nav-style2',
				),
				'group'       => __( 'Slider Options', 'safira' ),
			),
		),
	) );
	//Testimonials
	vc_map( array(
		'name'        => esc_html__( 'Testimonials', 'safira' ),
		'description' => esc_html__( 'Testimonial slider', 'safira' ),
		'base'        => 'testimonials',
		'class'       => '',
		'category'    => esc_html__( 'Theme', 'safira'),
		"icon"     	  => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'      => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number of testimonial', 'safira' ),
				'param_name' => 'limit',
				'value'      => esc_html__( '10', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Display Author', 'safira' ),
				'param_name' => 'display_author',
				'value'      => array(
					esc_html__( 'Yes', 'safira' )	=> '1',
					esc_html__( 'No', 'safira' )	=> '0',
				),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Display Avatar', 'safira' ),
				'param_name' => 'display_avatar',
				'value'      => array(
					esc_html__( 'Yes', 'safira' )=> '1',
					esc_html__( 'No', 'safira' ) => '0',
				),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Avatar image size', 'safira' ),
				'param_name'  => 'size',
				'value'       => esc_html__( '150', 'safira' ),
				'description' => esc_html__( 'Avatar image size in pixels. Default is 150', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Display URL', 'safira' ),
				'param_name' => 'display_url',
				'value'      => array(
					esc_html__( 'Yes', 'safira' )	=> '1',
					esc_html__( 'No', 'safira' )	=> '0',
				),
			),
			array(
				'type'        => 'textfield',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Category', 'safira' ),
				'param_name'  => 'category',
				'value'       => esc_html__( '0', 'safira' ),
				'description' => esc_html__( 'Slug of the category (only one category). Default is 0 : show all testimonials', 'safira' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Navigation', 'safira' ),
				'param_name' => 'navigation',
				'value'      => array(
					esc_html__( 'Yes', 'safira' ) => true,
					esc_html__( 'No', 'safira' )  => false,
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Pagination', 'safira' ),
				'param_name' => 'pagination',
				'value'      => array(
					esc_html__( 'Yes', 'safira' ) => true,
					esc_html__( 'No', 'safira' )  => false,
				),
			),
			array(
				'type'       => 'textfield',
				'heading'    =>  esc_html__( 'Slider speed number (unit: second)', 'safira' ),
				'param_name' => 'speed',
				'value'      => esc_html__( '500', 'safira' ),
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider loop', 'safira' ),
				'param_name' => 'loop',
			),
			array(
				'type'       => 'checkbox',
				'value'      => true,
				'heading'    => esc_html__( 'Slider Auto', 'safira' ),
				'param_name' => 'auto',
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'safira' ),
				'param_name'  => 'style',
				'value'       => array(
					esc_html__( 'Style 1', 'safira' )                => 'style1',
					esc_html__( 'Style 2 (about page)', 'safira' )   => 'style-about-page',
				),
			)
		),
	) );
	//Counter
	vc_map( array(
		'name'     => esc_html__( 'Counter', 'safira' ),
		'description' => esc_html__( 'Counter', 'safira' ),
		'base'     => 'safira_counter',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'safira'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'        => 'attach_image',
				'holder'      => 'div',
				'class'       => '',
				'heading'     => esc_html__( 'Image icon', 'safira' ),
				'param_name'  => 'image',
				'value'       => '',
				'description' => esc_html__( 'Upload icon image', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Number', 'safira' ),
				'param_name' => 'number',
				'value'      => '',
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text', 'safira' ),
				'param_name' => 'text',
				'value'      => '',
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'safira' ),
				'param_name'  => 'style',
				'value'       => array(
					esc_html__( 'Style 1', 'safira' )   => 'style1',
				),
			),
		),
	) );
	//Heading title
	vc_map( array(
		'name'     => esc_html__( 'Heading Title', 'safira' ),
		'description' => esc_html__( 'Heading Title', 'safira' ),
		'base'     => 'roadthemes_title',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'safira'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'       => 'textarea',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Heading title element', 'safira' ),
				'param_name' => 'heading_title',
				'value'      => esc_html__( 'Title', 'safira' ),
			),
			array(
				'type'       => 'textarea',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Heading sub-title element', 'safira' ),
				'param_name' => 'sub_heading_title',
				'value'      => '',
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'safira' ),
				'param_name'  => 'style',
				'value'       => array(
					esc_html__( 'Style 1 (Default)', 'safira' )    => 'style1',
					esc_html__( 'Style 2', 'safira' )              => 'style2',
					esc_html__( 'Style 3', 'safira' )     => 'style3',
					esc_html__( 'Style 4', 'safira' )     => 'style4',
					esc_html__( 'Style 5 (Footer title)', 'safira' )     => 'style5',
				),
			),
		),
	) );
	//Countdown
	vc_map( array(
		'name'     => esc_html__( 'Countdown', 'safira' ),
		'description' => esc_html__( 'Countdown', 'safira' ),
		'base'     => 'roadthemes_countdown',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'safira'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (day)', 'safira' ),
				'param_name' => 'countdown_day',
				'value'      => esc_html__( '1', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (month)', 'safira' ),
				'param_name' => 'countdown_month',
				'value'      => esc_html__( '1', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'End date (year)', 'safira' ),
				'param_name' => 'countdown_year',
				'value'      => esc_html__( '2020', 'safira' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'safira' ),
				'param_name'  => 'style',
				'value'       => array(
					esc_html__( 'Style 1', 'safira' )      => 'style1',
				),
			),
		),
	) );
	//Login logout
	vc_map( array(
		'name'     => esc_html__( 'Login/logout links', 'safira' ),
		'description' => esc_html__( 'Login/logout links', 'safira' ),
		'base'     => 'road_login_logout',
		'class'    => '',
		'category' => esc_html__( 'Theme', 'safira'),
		"icon"     => get_template_directory_uri() . "/images/road-icon.jpg",
		'params'   => array(
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text Wellcome', 'safira' ),
				'param_name' => 'txt_wellcome',
				'value'      => esc_html__( 'Hello', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text Login', 'safira' ),
				'param_name' => 'txt_login',
				'value'      => esc_html__( 'Login', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text Logout', 'safira' ),
				'param_name' => 'txt_logout',
				'value'      => esc_html__( 'Logout', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'holder'     => 'div',
				'class'      => '',
				'heading'    => esc_html__( 'Text Signup', 'safira' ),
				'param_name' => 'txt_signup',
				'value'      => esc_html__( 'Signup', 'safira' ),
			),
		),
	) );
}
?>