<?php 

if( ! function_exists( 'road_get_slider_setting' ) ) {
	function road_get_slider_setting() {
		return array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'safira' ),
				'param_name'  => 'style',
				'value'       => array(
					__( 'Grid view', 'safira' )                    => 'product-grid',
					__( 'List view', 'safira' )                    => 'product-list',
					__( 'List view 2', 'safira' )                    => 'product-list style2',
					__( 'Grid view (countdown) ', 'safira' )     => 'product-grid-countdown',
					__( 'Grid view (countdown style 2) ', 'safira' )     => 'product-grid-countdown style2',
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Enable slider', 'safira' ),
				'description' => __( 'If slider is enabled, the "column" ins General group is the number of rows ', 'safira' ),
				'param_name'  => 'enable_slider',
				'value'       => true,
				'save_always' => true, 
				'group'       => __( 'Slider Options', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: over 1500px)', 'safira' ),
				'param_name' => 'items_1500up',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '4', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 1200px - 1499px)', 'safira' ),
				'param_name' => 'items_1200_1499',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '4', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 992px - 1199px)', 'safira' ),
				'param_name' => 'items_992_1199',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '4', 'safira' ),
			), 
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 768px - 991px)', 'safira' ),
				'param_name' => 'items_768_991',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '3', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 640px - 767px)', 'safira' ),
				'param_name' => 'items_640_767',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '2', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: 375px - 639px)', 'safira' ),
				'param_name' => 'items_375_639',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '2', 'safira' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => __( 'Number of columns (screen: under 374px)', 'safira' ),
				'param_name' => 'items_0_374',
				'group'      => __( 'Slider Options', 'safira' ),
				'value'      => esc_html__( '1', 'safira' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Navigation', 'safira' ),
				'param_name'  => 'navigation',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'safira' ),
				'value'       => array(
					__( 'No', 'safira' )  => false,
					__( 'Yes', 'safira' ) => true,
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Pagination', 'safira' ),
				'param_name'  => 'pagination',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'safira' ),
				'value'       => array(
					__( 'No', 'safira' )  => false,
					__( 'Yes', 'safira' ) => true,
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Item Margin (unit: pixel)', 'safira' ),
				'param_name'  => 'item_margin',
				'value'       => 30,
				'save_always' => true,
				'group'       => __( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __( 'Slider speed number (unit: second)', 'safira' ),
				'param_name'  => 'speed',
				'value'       => '500',
				'save_always' => true,
				'group'       => __( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider loop', 'safira' ),
				'param_name'  => 'loop',
				'value'       => true,
				'group'       => __( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => __( 'Slider Auto', 'safira' ),
				'param_name'  => 'auto',
				'value'       => true,
				'group'       => __( 'Slider Options', 'safira' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => __( 'Navigation Style', 'safira' ),
				'param_name'  => 'navigation_style',
				'value'       => array(
					__( 'Style 1', 'safira' ) => 'nav-style',
					__( 'Style 2 - with heading title style 2', 'safira' ) => 'nav-style nav-style2',
					__( 'Style 3 - with heading title style 4', 'safira' ) => 'nav-style nav-style3',
				),
				'group'       => __( 'Slider Options', 'safira' ),
			),
		);
	}
}