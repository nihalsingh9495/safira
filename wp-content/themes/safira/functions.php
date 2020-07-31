<?php
/**
 * Safira functions and definitions
 */
/**
* Require files
*/
	//TGM-Plugin-Activation
require_once( get_template_directory().'/class-tgm-plugin-activation.php' );
	//Init the Redux Framework
if ( class_exists( 'ReduxFramework' ) && !isset( $redux_demo ) && file_exists( get_template_directory().'/theme-config.php' ) ) {
	require_once( get_template_directory().'/theme-config.php' );
}
	// Theme files
if ( file_exists( get_template_directory().'/include/wooajax.php' ) ) {
	require_once( get_template_directory().'/include/wooajax.php' );
}
if ( file_exists( get_template_directory().'/include/map_shortcodes.php' ) ) {
	require_once( get_template_directory().'/include/map_shortcodes.php' );
}
if ( file_exists( get_template_directory().'/include/shortcodes.php' ) ) {
	require_once( get_template_directory().'/include/shortcodes.php' );
}
define('PLUGIN_REQUIRED_PATH','http://roadthemes.com/plugins');
Class Safira_Class {
	/**
	* Global values
	*/
	static function safira_post_odd_event(){
		global $wp_session;
		if(!isset($wp_session["safira_postcount"])){
			$wp_session["safira_postcount"] = 0;
		}
		$wp_session["safira_postcount"] = 1 - $wp_session["safira_postcount"];
		return $wp_session["safira_postcount"];
	}
	static function safira_post_thumbnail_size($size){
		global $wp_session;
		if($size!=''){
			$wp_session["safira_postthumb"] = $size;
		}
		return $wp_session["safira_postthumb"];
	}
	static function safira_shop_class($class){
		global $wp_session;
		if($class!=''){
			$wp_session["safira_shopclass"] = $class;
		}
		return $wp_session["safira_shopclass"];
	}
	static function safira_show_view_mode(){
		$safira_opt = get_option( 'safira_opt' );
		$safira_viewmode = 'grid-view'; //default value
		if(isset($safira_opt['default_view'])) {
			$safira_viewmode = $safira_opt['default_view'];
		}
		if(isset($_GET['view']) && $_GET['view']!=''){
			$safira_viewmode = $_GET['view'];
		}
		return $safira_viewmode;
	}
	static function safira_shortcode_products_count(){
		global $wp_session;
		$safira_productsfound = 0;
		if(isset($wp_session["safira_productsfound"])){
			$safira_productsfound = $wp_session["safira_productsfound"];
		}
		return $safira_productsfound;
	}
	/**
	* Constructor
	*/
	function __construct() {
		// Register action/filter callbacks
		//WooCommerce - action/filter
		add_theme_support( 'woocommerce' );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
		add_filter( 'get_product_search_form', array($this, 'safira_woo_search_form'));
		add_filter( 'woocommerce_shortcode_products_query', array($this, 'safira_woocommerce_shortcode_count'));
		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
		    return array(
		        'width'  => 90,
		        'height' => 90,
		        'crop'   => 1,
		    );
		} );
		//move message to top
		remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
		add_action( 'woocommerce_show_message', 'wc_print_notices', 10 );
		//remove add to cart button after item
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		//Single product organize
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		//remove cart total under cross sell
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
		add_action( 'safira_cart_totals', 'woocommerce_cart_totals', 5 );
		//Theme actions
		add_action( 'after_setup_theme', array($this, 'safira_setup'));
		add_action( 'tgmpa_register', array($this, 'safira_register_required_plugins'));
		add_action( 'wp_enqueue_scripts', array($this, 'safira_scripts_styles') );
		add_action( 'wp_head', array($this, 'safira_custom_code_header'));
		add_action( 'widgets_init', array($this, 'safira_widgets_init'));
		add_action( 'save_post', array($this, 'safira_save_meta_box_data'));
		add_action('comment_form_before_fields', array($this, 'safira_before_comment_fields'));
		add_action('comment_form_after_fields', array($this, 'safira_after_comment_fields'));
		add_action( 'customize_register', array($this, 'safira_customize_register'));
		add_action( 'customize_preview_init', array($this, 'safira_customize_preview_js'));
		add_action('admin_enqueue_scripts', array($this, 'safira_admin_style'));
		//Theme filters
		add_filter( 'loop_shop_per_page', array($this, 'safira_woo_change_per_page'), 20 );
		add_filter( 'woocommerce_output_related_products_args', array($this, 'safira_woo_related_products_limit'));
		add_filter( 'get_search_form', array($this, 'safira_search_form'));
		add_filter('excerpt_more', array($this, 'safira_new_excerpt_more'));
		add_filter( 'excerpt_length', array($this, 'safira_change_excerpt_length'), 999 );
		add_filter('wp_nav_menu_objects', array($this, 'safira_first_and_last_menu_class'));
		add_filter( 'wp_page_menu_args', array($this, 'safira_page_menu_args'));
		add_filter('dynamic_sidebar_params', array($this, 'safira_widget_first_last_class'));
		add_filter('dynamic_sidebar_params', array($this, 'safira_mega_menu_widget_change'));
		add_filter( 'dynamic_sidebar_params', array($this, 'safira_put_widget_content'));
		add_filter( 'the_content_more_link', array($this, 'safira_modify_read_more_link'));
		//Adding theme support
		if ( ! isset( $content_width ) ) {
			$content_width = 625;
		}
	}
	/**
	* Filter callbacks
	* ----------------
	*/
	// read more link 
	function safira_modify_read_more_link() {
		$safira_opt = get_option( 'safira_opt' );
		if(isset($safira_opt['readmore_text']) && $safira_opt['readmore_text'] != ''){
			$readmore_text = esc_html($safira_opt['readmore_text']);
		} else { 
			$readmore_text = esc_html__('Read more','safira');
		};
	    return '<div><a class="readmore" href="'. get_permalink().' ">'.$readmore_text.'</a></div>';
	}
	// Change products per page
	function safira_woo_change_per_page() {
		$safira_opt = get_option( 'safira_opt' );
		return $safira_opt['product_per_page'];
	}
	//Change number of related products on product page. Set your own value for 'posts_per_page'
	function safira_woo_related_products_limit( $args ) {
		global $product;
		$safira_opt = get_option( 'safira_opt' );
		$args['posts_per_page'] = $safira_opt['related_amount'];
		return $args;
	}
	// Count number of products from shortcode
	function safira_woocommerce_shortcode_count( $args ) {
		$safira_productsfound = new WP_Query($args);
		$safira_productsfound = $safira_productsfound->post_count;
		global $wp_session;
		$wp_session["safira_productsfound"] = $safira_productsfound;
		return $args;
	}
	//Change search form
	function safira_search_form( $form ) {
		if(get_search_query()!=''){
			$search_str = get_search_query();
		} else {
			$search_str = esc_html__( 'Search... ', 'safira' );
		}
		$form = '<form role="search" method="get" class="searchform blogsearchform" action="' . esc_url(home_url( '/' ) ). '" >
		<div class="form-input">
			<input type="text" placeholder="'.esc_attr($search_str).'" name="s" class="input_text ws">
			<button class="button-search searchsubmit blogsearchsubmit" type="submit">' . esc_html__('Search', 'safira') . '</button>
			<input type="hidden" name="post_type" value="post" />
			</div>
		</form>';
		return $form;
	}
	//Change woocommerce search form
	function safira_woo_search_form( $form ) {
		global $wpdb;
		if(get_search_query()!=''){
			$search_str = get_search_query();
		} else {
			$search_str = esc_html__( 'Search product...', 'safira' );
		}
		$form = '<form role="search" method="get" class="searchform productsearchform" action="'.esc_url( home_url( '/'  ) ).'">';
			$form .= '<div class="form-input">';
				$form .= '<input type="text" placeholder="'.esc_attr($search_str).'" name="s" class="ws"/>';
				$form .= '<button class="button-search searchsubmit productsearchsubmit" type="submit">' . esc_html__('Search', 'safira') . '</button>';
				$form .= '<input type="hidden" name="post_type" value="product" />';
			$form .= '</div>';
		$form .= '</form>';
		return $form;
	}
	// Replaces the excerpt "more" text by a link
	function safira_new_excerpt_more($more) {
		return '';
	}
	//Change excerpt length
	function safira_change_excerpt_length( $length ) {
		$safira_opt = get_option( 'safira_opt' );
		if(isset($safira_opt['excerpt_length'])){
			return $safira_opt['excerpt_length'];
		}
		return 50;
	}
	//Add 'first, last' class to menu
	function safira_first_and_last_menu_class($items) {
		$items[1]->classes[] = 'first';
		$items[count($items)]->classes[] = 'last';
		return $items;
	}
	/**
	 * Filter the page menu arguments.
	 *
	 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
	 *
	 * @since Safira 1.0
	 */
	function safira_page_menu_args( $args ) {
		if ( ! isset( $args['show_home'] ) )
			$args['show_home'] = true;
		return $args;
	}
	//Add first, last class to widgets
	function safira_widget_first_last_class($params) {
		global $my_widget_num;
		$class = '';
		$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
		$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets	
		if(!$my_widget_num) {// If the counter array doesn't exist, create it
			$my_widget_num = array();
		}
		if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
			return $params; // No widgets in this sidebar... bail early.
		}
		if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
			$my_widget_num[$this_id] ++;
		} else { // If not, create it starting with 1
			$my_widget_num[$this_id] = 1;
		}
		if($my_widget_num[$this_id] == 1) { // If this is the first widget
			$class .= ' widget-first ';
		} elseif($my_widget_num[$this_id] == count($arr_registered_widgets[$this_id])) { // If this is the last widget
			$class .= ' widget-last ';
		}
		$params[0]['before_widget'] = str_replace('first_last', ' '.$class.' ', $params[0]['before_widget']);
		return $params;
	}
	//Change mega menu widget from div to li tag
	function safira_mega_menu_widget_change($params) {
		$sidebar_id = $params[0]['id'];
		$pos = strpos($sidebar_id, '_menu_widgets_area_');
		if ( !$pos == false ) {
			$params[0]['before_widget'] = '<li class="widget_mega_menu">'.$params[0]['before_widget'];
			$params[0]['after_widget'] = $params[0]['after_widget'].'</li>';
		}
		return $params;
	}
	// Push sidebar widget content into a div
	function safira_put_widget_content( $params ) {
		global $wp_registered_widgets;
		if( $params[0]['id']=='sidebar-category' ){
			$settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
			$settings = $settings_getter->get_settings();
			$settings = $settings[ $params[1]['number'] ];
			if($params[0]['widget_name']=="Text" && isset($settings['title']) && $settings['text']=="") { // if text widget and no content => don't push content
				return $params;
			}
			if( isset($settings['title']) && $settings['title']!='' ){
				$params[0][ 'after_title' ] .= '<div class="widget_content">';
				$params[0][ 'after_widget' ] = '</div>'.$params[0][ 'after_widget' ];
			} else {
				$params[0][ 'before_widget' ] .= '<div class="widget_content">';
				$params[0][ 'after_widget' ] = '</div>'.$params[0][ 'after_widget' ];
			}
		}
		return $params;
	}
	/**
	* Action hooks
	* ----------------
	*/
	/**
	 * Safira setup.
	 *
	 * Sets up theme defaults and registers the various WordPress features that
	 * Safira supports.
	 *
	 * @uses load_theme_textdomain() For translation/localization support.
	 * @uses add_editor_style() To add a Visual Editor stylesheet.
	 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
	 * 	custom background, and post formats.
	 * @uses register_nav_menu() To add support for navigation menus.
	 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
	 *
	 * @since Safira 1.0
	 */
	function safira_setup() {
		/*
		 * Makes Safira available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Safira, use a find and replace
		 * to change 'safira' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'safira', get_template_directory() . '/languages' );
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		// This theme supports a variety of post formats.
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
		// Register menus
		register_nav_menu( 'primary', esc_html__( 'Primary Menu', 'safira' ) );
		register_nav_menu( 'stickymenu', esc_html__( 'Sticky Menu', 'safira' ) );
		register_nav_menu( 'mobilemenu', esc_html__( 'Mobile Menu', 'safira' ) );
		register_nav_menu( 'categories', esc_html__( 'Categories Menu', 'safira' ) );
		/*
		 * This theme supports custom background color and image,
		 * and here we also set up the default background color.
		 */
		add_theme_support( 'custom-background', array(
			'default-color' => 'e6e6e6',
		) );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		// This theme uses a custom image size for featured images, displayed on "standard" posts.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1170, 9999 ); // Unlimited height, soft crop
		add_image_size( 'safira-category-thumb', 1170, 743, true ); // (cropped) (post carousel)
		add_image_size( 'safira-post-thumb', 700, 445, true ); // (cropped) (blog sidebar)
		add_image_size( 'safira-post-thumbwide', 1170, 743, true ); // (cropped) (blog large img)
	}
	/**
	 * Enqueue scripts and styles for front-end.
	 *
	 * @since Safira 1.0
	 */
	function safira_scripts_styles() {
		global $wp_styles, $wp_scripts;
		$safira_opt = get_option( 'safira_opt' );
		if(function_exists("vc_asset_url")){
			wp_enqueue_script( 'wpb_composer_front_js', vc_asset_url( 'js/dist/js_composer_front.min.js' ), array( 'jquery' ), WPB_VC_VERSION, true );
		}
		/*
		 * Adds JavaScript to pages with the comment form to support
		 * sites with threaded comments (when in use).
		*/
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );
		// Add Bootstrap JavaScript
		wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '4.1.1', true );
		// Add Owl files
		wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), '2.3.4', true );
		wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), '2.3.4' );
		// Add Chosen js files
		wp_enqueue_script( 'chosen-jquery', get_template_directory_uri() . '/js/chosen/chosen.jquery.min.js', array('jquery'), '1.3.0', true );
		wp_enqueue_script( 'chosen-proto', get_template_directory_uri() . '/js/chosen/chosen.proto.min.js', array('jquery'), '1.3.0', true );
		wp_enqueue_style( 'chosen', get_template_directory_uri() . '/js/chosen/chosen.min.css', array(), '1.3.0' );
		// Add parallax script files
		// Add Fancybox
		wp_enqueue_script( 'jquery-fancybox-pack', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.pack.js', array('jquery'), '2.1.5', true );
		wp_enqueue_script( 'jquery-fancybox-buttons', get_template_directory_uri().'/js/fancybox/helpers/jquery.fancybox-buttons.js', array('jquery'), '1.0.5', true );
		wp_enqueue_script( 'jquery-fancybox-media', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-media.js', array('jquery'), '1.0.6', true );
		wp_enqueue_script( 'jquery-fancybox-thumbs', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-thumbs.js', array('jquery'), '1.0.7', true );
		wp_enqueue_style( 'jquery-fancybox', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.css', array(), '2.1.5' );
		wp_enqueue_style( 'jquery-fancybox-buttons', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-buttons.css', array(), '1.0.5' );
		wp_enqueue_style( 'jquery-fancybox-thumbs', get_template_directory_uri() . '/js/fancybox/helpers/jquery.fancybox-thumbs.css', array(), '1.0.7' );
		//Superfish
		wp_enqueue_script( 'superfish', get_template_directory_uri() . '/js/superfish/superfish.min.js', array('jquery'), '1.3.15', true );
		//Add Shuffle js
		wp_enqueue_script( 'modernizr-custom', get_template_directory_uri() . '/js/modernizr.custom.min.js', array('jquery'), '2.6.2', true );
		wp_enqueue_script( 'jquery-shuffle', get_template_directory_uri() . '/js/jquery.shuffle.min.js', array('jquery'), '3.0.0', true );
		//Add mousewheel
		wp_enqueue_script( 'jquery-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), '3.1.12', true );
		// Add jQuery countdown file
		wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() . '/js/jquery.countdown.min.js', array('jquery'), '2.0.4', true );
		// Add jQuery counter files
		wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/js/waypoints.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'jquery-counterup', get_template_directory_uri() . '/js/jquery.counterup.min.js', array('jquery'), '1.0', true );
		// Add variables.js file
		wp_enqueue_script( 'safira-variables', get_template_directory_uri() . '/js/variables.js', array('jquery'), '20200603', true );
		wp_enqueue_script( 'safira-theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), '20200603', true );
		$font_url = $this->safira_get_font_url();
		if ( ! empty( $font_url ))
			wp_enqueue_style( 'safira-fonts', esc_url_raw( $font_url ), array(), null );
		// Loads our main stylesheet.
		wp_enqueue_style( 'safira-style', get_stylesheet_uri() );
		// Mega Main Menu
		wp_enqueue_style( 'megamenu-style', get_template_directory_uri() . '/css/megamenu_style.css', array(), '2.0.4');
		// Load ionicons css
		wp_enqueue_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.min.css', array(), '2.0.0');
		// Load linearicons css
		wp_enqueue_style( 'linearicons', get_template_directory_uri() . '/css/linearicons.min.css', array(), '1.0.0');
		// Load pe-icon-7-stroke css
		wp_enqueue_style( 'pe-icon-7-stroke', get_template_directory_uri() . '/css/pe-icon-7-stroke.min.css', array(), null);
		// Load fontawesome css
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.7.0');
		// Load bootstrap css
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '4.1.1');
		// Compile Less to CSS
		$previewpreset = (isset($_REQUEST['preset']) ? $_REQUEST['preset'] : null);
		// get preset from url (only for demo/preview)
		if($previewpreset){
			$_SESSION["preset"] = $previewpreset;
		}
		$presetopt = 1; /* change default preset 1 and 209 */
		if(!isset($_SESSION["preset"])){
			$_SESSION["preset"] = 1;
		}
		if($_SESSION["preset"] != 1) {
			$presetopt = $_SESSION["preset"];
		} else { /* if no preset varialbe found in url, use from theme options */
			if(isset($safira_opt['preset_option'])){
				$presetopt = $safira_opt['preset_option'];
			}
		}
		if(!isset($presetopt)) $presetopt = 1; /* in case first time install theme, no options found */
		if(isset($safira_opt['enable_less'])){
			if($safira_opt['enable_less']){
				$themevariables = array(
					'body_font'=> $safira_opt['bodyfont']['font-family'],
					'body_font_backup'=> $safira_opt['bodyfont']['font-backup'],
					'text_color'=> $safira_opt['bodyfont']['color'],
					'text_selected_bg' => $safira_opt['text_selected_bg'],
					'text_selected_color' => $safira_opt['text_selected_color'],
					'text_size'=> $safira_opt['bodyfont']['font-size'],
					'border_color'=> $safira_opt['border_color']['border-color'],
					'page_content_background' => $safira_opt['page_content_background']['background-color'],
					'row_space' => $safira_opt['row_space'],
					'row_container' => $safira_opt['row_container'],
					'heading_font'=> $safira_opt['headingfont']['font-family'],
					'heading_color'=> $safira_opt['headingfont']['color'],
					'heading_font_weight'=> $safira_opt['headingfont']['font-weight'],
					'heading_font2'=> $safira_opt['headingfont2']['font-family'],
					'heading_color2'=> $safira_opt['headingfont2']['color'],
					'heading_font_weight2'=> $safira_opt['headingfont2']['font-weight'],
					'dropdown_font'=> $safira_opt['dropdownfont']['font-family'],
					'dropdown_color'=> $safira_opt['dropdownfont']['color'],
					'dropdown_font_size'=> $safira_opt['dropdownfont']['font-size'],
					'dropdown_font_weight'=> $safira_opt['dropdownfont']['font-weight'],
					'dropdown_bg' => $safira_opt['dropdown_bg'],
					'menu_font'=> $safira_opt['menufont']['font-family'],
					'menu_color'=> $safira_opt['menufont']['color'],
					'menu_font_size'=> $safira_opt['menufont']['font-size'],
					'menu_font_weight'=> $safira_opt['menufont']['font-weight'],
					'sub_menu_font'=> $safira_opt['submenufont']['font-family'],
					'sub_menu_color'=> $safira_opt['submenufont']['color'],
					'sub_menu_font_size'=> $safira_opt['submenufont']['font-size'],
					'sub_menu_font_weight'=> $safira_opt['submenufont']['font-weight'],
					'sub_menu_bg' => $safira_opt['sub_menu_bg'],
					'categories_font'=> $safira_opt['categoriesfont']['font-family'],
					'categories_font_size'=> $safira_opt['categoriesfont']['font-size'],
					'categories_font_weight'=> $safira_opt['categoriesfont']['font-weight'],
					'categories_color'=> $safira_opt['categoriesfont']['color'],
					'categories_menu_bg' => $safira_opt['categories_menu_bg'],
					'categories_sub_menu_font'=> $safira_opt['categoriessubmenufont']['font-family'],
					'categories_sub_menu_font_size'=> $safira_opt['categoriessubmenufont']['font-size'],
					'categories_sub_menu_font_weight'=> $safira_opt['categoriessubmenufont']['font-weight'],
					'categories_sub_menu_color'=> $safira_opt['categoriessubmenufont']['color'],
					'categories_sub_menu_bg' => $safira_opt['categories_sub_menu_bg'],
					'link_color' => $safira_opt['link_color']['regular'],
					'link_hover_color' => $safira_opt['link_color']['hover'],
					'link_active_color' => $safira_opt['link_color']['active'],
					'primary_color' => $safira_opt['primary_color'],
					'menu_hover_itemlevel1_color' => $safira_opt['menu_hover_itemlevel1_color'],
					'sale_color' => $safira_opt['sale_color'],
					'saletext_color' => $safira_opt['saletext_color'],
					'rate_color' => $safira_opt['rate_color'],
					'price_font'=> $safira_opt['pricefont']['font-family'],
					'price_color'=> $safira_opt['pricefont']['color'],
					'price_font_size'=> $safira_opt['pricefont']['font-size'],
					'price_font_weight'=> $safira_opt['pricefont']['font-weight'],
					'header_color' => $safira_opt['header_color'],
					'header_link_color' => $safira_opt['header_link_color']['regular'],
					'header_link_hover_color' => $safira_opt['header_link_color']['hover'],
					'header_link_active_color' => $safira_opt['header_link_color']['active'],
					'topbar_color' => $safira_opt['topbar_color'],
					'topbar_link_color' => $safira_opt['topbar_link_color']['regular'],
					'topbar_link_hover_color' => $safira_opt['topbar_link_color']['hover'],
					'topbar_link_active_color' => $safira_opt['topbar_link_color']['active'],
					'footer_color' => $safira_opt['footer_color']['rgba'],
					'footer_title_color' => $safira_opt['footer_title_color'],
					'footer_link_color' => $safira_opt['footer_link_color']['rgba'],
				);
				if(isset($safira_opt['header_sticky_bg']['rgba']) && $safira_opt['header_sticky_bg']['rgba']!="") {
					$themevariables['header_sticky_bg'] = $safira_opt['header_sticky_bg']['rgba'];
				} else {
					$themevariables['header_sticky_bg'] = 'rgba(64, 169, 68, 0.95)';
				}
				if(isset($safira_opt['header_bg']['background-color']) && $safira_opt['header_bg']['background-color']!="") {
					$themevariables['header_bg'] = $safira_opt['header_bg']['background-color'];
				} else {
					$themevariables['header_bg'] = '#fff';
				}
				if(isset($safira_opt['footer_bg']['background-color']) && $safira_opt['footer_bg']['background-color']!="") {
					$themevariables['footer_bg'] = $safira_opt['footer_bg']['background-color'];
				} else {
					$themevariables['footer_bg'] = '#222222';
				}
				switch ($presetopt) {
					case 2:
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['primary_color'] = '#80b82d';
						$themevariables['menu_color'] = '#222222';
						$themevariables['menu_hover_itemlevel1_color'] = '#80b82d';
						$themevariables['sale_color'] = '#80b82d';
						$themevariables['price_color'] = '#80b82d';
						$themevariables['link_hover_color'] = '#80b82d';
						$themevariables['link_active_color'] = '#80b82d';
						$themevariables['header_color'] = '#ffffff';
						$themevariables['header_link_hover_color'] = '#80b82d';
						$themevariables['header_link_active_color'] = '#80b82d';
						break;
					case 3:
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['primary_color'] = '#fc8a35';
						$themevariables['menu_color'] = '#222222';
						$themevariables['menu_hover_itemlevel1_color'] = '#fc8a35';
						$themevariables['sale_color'] = '#fc8a35';
						$themevariables['price_color'] = '#fc8a35';
						$themevariables['link_hover_color'] = '#fc8a35';
						$themevariables['link_active_color'] = '#fc8a35';
						$themevariables['header_link_hover_color'] = '#fc8a35';
						$themevariables['header_link_active_color'] = '#fc8a35';
						break;
					case 4:
						$themevariables['header_sticky_bg'] = 'rgba(255, 255, 255, 0.95)';
						$themevariables['primary_color'] = '#cf1f1f';
						$themevariables['menu_color'] = '#222222';
						$themevariables['menu_hover_itemlevel1_color'] = '#cf1f1f';
						$themevariables['sale_color'] = '#cf1f1f';
						$themevariables['price_color'] = '#cf1f1f';
						$themevariables['link_hover_color'] = '#cf1f1f';
						$themevariables['link_active_color'] = '#cf1f1f';
						$themevariables['header_link_hover_color'] = '#cf1f1f';
						$themevariables['header_link_active_color'] = '#cf1f1f';
						break;
				}
				if(function_exists('compileLessFile')){
					compileLessFile('theme.less', 'theme'.$presetopt.'.css', $themevariables);
				}
			}
		}
		// Load main theme css style files
		wp_enqueue_style( 'safira-theme', get_template_directory_uri() . '/css/theme'.$presetopt.'.css', array('bootstrap'), '1.0.0');
		wp_enqueue_style( 'safira-opt-css', get_template_directory_uri() . '/css/opt_css.css', array('safira-theme'), '1.0.0');
		if(function_exists('WP_Filesystem')){
			if ( ! WP_Filesystem() ) {
				$url = wp_nonce_url();
				request_filesystem_credentials($url, '', true, false, null);
			}
			global $wp_filesystem;
			//add custom css, sharing code to header
			if($wp_filesystem->exists(get_template_directory(). '/css/opt_css.css')){
				$customcss = $wp_filesystem->get_contents(get_template_directory(). '/css/opt_css.css');
				if(isset($safira_opt['custom_css']) && $customcss!=$safira_opt['custom_css']){ //if new update, write file content
					$wp_filesystem->put_contents(
						get_template_directory(). '/css/opt_css.css',
						$safira_opt['custom_css'],
						FS_CHMOD_FILE // predefined mode settings for WP files
					);
				}
			} else {
				$wp_filesystem->put_contents(
					get_template_directory(). '/css/opt_css.css',
					$safira_opt['custom_css'],
					FS_CHMOD_FILE // predefined mode settings for WP files
				);
			}
		}
		//add javascript variables
		ob_start(); ?>
		"use strict";
		var safira_brandnumber = <?php if(isset($safira_opt['brandnumber'])) { echo esc_js($safira_opt['brandnumber']); } else { echo '6'; } ?>,
			safira_brandscrollnumber = <?php if(isset($safira_opt['brandscrollnumber'])) { echo esc_js($safira_opt['brandscrollnumber']); } else { echo '2';} ?>,
			safira_brandpause = <?php if(isset($safira_opt['brandpause'])) { echo esc_js($safira_opt['brandpause']); } else { echo '3000'; } ?>,
			safira_brandanimate = <?php if(isset($safira_opt['brandanimate'])) { echo esc_js($safira_opt['brandanimate']); } else { echo '700';} ?>;
		var safira_brandscroll = false;
			<?php if(isset($safira_opt['brandscroll'])){ ?>
				safira_brandscroll = <?php echo esc_js($safira_opt['brandscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var safira_categoriesnumber = <?php if(isset($safira_opt['categoriesnumber'])) { echo esc_js($safira_opt['categoriesnumber']); } else { echo '6'; } ?>,
			safira_categoriesscrollnumber = <?php if(isset($safira_opt['categoriesscrollnumber'])) { echo esc_js($safira_opt['categoriesscrollnumber']); } else { echo '2';} ?>,
			safira_categoriespause = <?php if(isset($safira_opt['categoriespause'])) { echo esc_js($safira_opt['categoriespause']); } else { echo '3000'; } ?>,
			safira_categoriesanimate = <?php if(isset($safira_opt['categoriesanimate'])) { echo esc_js($safira_opt['categoriesanimate']); } else { echo '700';} ?>;
		var safira_categoriesscroll = 'false';
			<?php if(isset($safira_opt['categoriesscroll'])){ ?>
				safira_categoriesscroll = <?php echo esc_js($safira_opt['categoriesscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var safira_blogpause = <?php if(isset($safira_opt['blogpause'])) { echo esc_js($safira_opt['blogpause']); } else { echo '3000'; } ?>,
			safira_bloganimate = <?php if(isset($safira_opt['bloganimate'])) { echo esc_js($safira_opt['bloganimate']); } else { echo '700'; } ?>;
		var safira_blogscroll = false;
			<?php if(isset($safira_opt['blogscroll'])){ ?>
				safira_blogscroll = <?php echo esc_js($safira_opt['blogscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var safira_testipause = <?php if(isset($safira_opt['testipause'])) { echo esc_js($safira_opt['testipause']); } else { echo '3000'; } ?>,
			safira_testianimate = <?php if(isset($safira_opt['testianimate'])) { echo esc_js($safira_opt['testianimate']); } else { echo '700'; } ?>;
		var safira_testiscroll = false;
			<?php if(isset($safira_opt['testiscroll'])){ ?>
				safira_testiscroll = <?php echo esc_js($safira_opt['testiscroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var safira_catenumber = <?php if(isset($safira_opt['catenumber'])) { echo esc_js($safira_opt['catenumber']); } else { echo '6'; } ?>,
			safira_catescrollnumber = <?php if(isset($safira_opt['catescrollnumber'])) { echo esc_js($safira_opt['catescrollnumber']); } else { echo '2';} ?>,
			safira_catepause = <?php if(isset($safira_opt['catepause'])) { echo esc_js($safira_opt['catepause']); } else { echo '3000'; } ?>,
			safira_cateanimate = <?php if(isset($safira_opt['cateanimate'])) { echo esc_js($safira_opt['cateanimate']); } else { echo '700';} ?>;
		var safira_catescroll = false;
			<?php if(isset($safira_opt['catescroll'])){ ?>
				safira_catescroll = <?php echo esc_js($safira_opt['catescroll'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		var safira_menu_number = <?php if(isset($safira_opt['categories_menu_items'])) { echo esc_js((int)$safira_opt['categories_menu_items']); } else { echo '10';} ?>;
		var safira_sticky_header = false;
			<?php if(isset($safira_opt['sticky_header'])){ ?>
				safira_sticky_header = <?php echo esc_js($safira_opt['sticky_header'])==1 ? 'true': 'false'; ?>;
			<?php } ?>
		jQuery(document).ready(function(){
			jQuery(".ws").on('focus', function(){
				if(jQuery(this).val()=="<?php esc_html__( 'Search product...', 'safira');?>"){
					jQuery(this).val("");
				}
			});
			jQuery(".ws").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("<?php esc_html__( 'Search product...', 'safira');?>");
				}
			});
			jQuery(".wsearchsubmit").on('click', function(){
				if(jQuery("#ws").val()=="<?php esc_html__( 'Search product...', 'safira');?>" || jQuery("#ws").val()==""){
					jQuery("#ws").focus();
					return false;
				}
			});
			jQuery(".search_input").on('focus', function(){
				if(jQuery(this).val()=="<?php esc_html__( 'Search...', 'safira');?>"){
					jQuery(this).val("");
				}
			});
			jQuery(".search_input").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("<?php esc_html__( 'Search...', 'safira');?>");
				}
			});
			jQuery(".blogsearchsubmit").on('click', function(){
				if(jQuery("#search_input").val()=="<?php esc_html__( 'Search...', 'safira');?>" || jQuery("#search_input").val()==""){
					jQuery("#search_input").focus();
					return false;
				}
			});
		});
		<?php
		$jsvars = ob_get_contents();
		ob_end_clean();
		if(function_exists('WP_Filesystem')){
			if($wp_filesystem->exists(get_template_directory(). '/js/variables.js')){
				$jsvariables = $wp_filesystem->get_contents(get_template_directory(). '/js/variables.js');
				if($jsvars!=$jsvariables){ //if new update, write file content
					$wp_filesystem->put_contents(
						get_template_directory(). '/js/variables.js',
						$jsvars,
						FS_CHMOD_FILE // predefined mode settings for WP files
					);
				}
			} else {
				$wp_filesystem->put_contents(
					get_template_directory(). '/js/variables.js',
					$jsvars,
					FS_CHMOD_FILE // predefined mode settings for WP files
				);
			}
		}
		//add css for footer, header templates
		$jscomposer_templates_args = array(
			'orderby'          => 'title',
			'order'            => 'ASC',
			'post_type'        => 'templatera',
			'post_status'      => 'publish',
			'posts_per_page'   => 30,
		);
		$jscomposer_templates = get_posts( $jscomposer_templates_args);
		if(count($jscomposer_templates) > 0) {
			foreach($jscomposer_templates as $jscomposer_template){
				if($jscomposer_template->post_title == $safira_opt['header_layout'] || $jscomposer_template->post_title == $safira_opt['footer_layout'] || $jscomposer_template->post_title == $safira_opt['header_mobile_layout'] || $jscomposer_template->post_title == $safira_opt['header_sticky_layout']){
					$jscomposer_template_css = get_post_meta ( $jscomposer_template->ID, '_wpb_shortcodes_custom_css', false);
					if(isset($jscomposer_template_css[0]))
					wp_add_inline_style( 'safira-opt-css', $jscomposer_template_css[0]);
				}
			}
		}
		//page width
		$safira_opt = get_option( 'safira_opt');
		if(isset($safira_opt['box_layout_width'])){
			wp_add_inline_style( 'safira-opt-css', '.wrapper.box-layout {max-width: '.$safira_opt['box_layout_width'].'px;}');
		}
	}
	//add sharing code to header
	function safira_custom_code_header() {
		global $safira_opt;
		if ( isset($safira_opt['share_head_code']) && $safira_opt['share_head_code']!='') {
			echo wp_kses($safira_opt['share_head_code'], array(
				'script' => array(
					'type' => array(),
					'src' => array(),
					'async' => array()
				),
			));
		}
	}
	/**
	 * Register sidebars.
	 *
	 * Registers our main widget area and the front page widget areas.
	 *
	 * @since Safira 1.0
	 */
	function safira_widgets_init() {
		$safira_opt = get_option( 'safira_opt');
		register_sidebar( array(
			'name' => esc_html__( 'Blog Sidebar', 'safira' ),
			'id' => 'sidebar-1',
			'description' => esc_html__( 'Sidebar on blog page', 'safira' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		));
		register_sidebar( array(
			'name' => esc_html__( 'Shop Sidebar', 'safira' ),
			'id' => 'sidebar-shop',
			'description' => esc_html__( 'Sidebar on shop page (only sidebar shop layout)', 'safira' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		));
		register_sidebar( array(
			'name' => esc_html__( 'Single product Sidebar', 'safira' ),
			'id' => 'sidebar-single_product',
			'description' => esc_html__( 'Sidebar on product details page', 'safira' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		));
		register_sidebar( array(
			'name' => esc_html__( 'Pages Sidebar', 'safira' ),
			'id' => 'sidebar-page',
			'description' => esc_html__( 'Sidebar on content pages', 'safira' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>',
		));
		if(isset($safira_opt['custom-sidebars']) && $safira_opt['custom-sidebars']!=""){
			foreach($safira_opt['custom-sidebars'] as $sidebar){
				$sidebar_id = str_replace(' ', '-', strtolower($sidebar));
				if($sidebar_id!='') {
					register_sidebar( array(
						'name' => $sidebar,
						'id' => $sidebar_id,
						'description' => $sidebar,
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget' => '</aside>',
						'before_title' => '<h3 class="widget-title"><span>',
						'after_title' => '</span></h3>',
					));
				}
			}
		}
	}
	static function safira_meta_box_callback( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'safira_meta_box', 'safira_meta_box_nonce');
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value = get_post_meta( $post->ID, '_safira_post_intro', true);
		echo '<label for="safira_post_intro">';
		esc_html_e( 'This content will be used to replace the featured image, use shortcode here', 'safira');
		echo '</label><br />';
		wp_editor( $value, 'safira_post_intro', $settings = array());
	}
	static function safira_custom_sidebar_callback( $post ) {
		global $wp_registered_sidebars;
		$safira_opt = get_option( 'safira_opt');
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'safira_meta_box', 'safira_meta_box_nonce');
		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		//show sidebar dropdown select
		$csidebar = get_post_meta( $post->ID, '_safira_custom_sidebar', true);
		echo '<label for="safira_custom_sidebar">';
		esc_html_e( 'Select a custom sidebar for this post/page', 'safira');
		echo '</label><br />';
		echo '<select id="safira_custom_sidebar" name="safira_custom_sidebar">';
			echo '<option value="">'.esc_html__('- None -', 'safira').'</option>';
			foreach($wp_registered_sidebars as $sidebar){
				$sidebar_id = $sidebar['id'];
				if($csidebar == $sidebar_id){
					echo '<option value="'.$sidebar_id.'" selected="selected">'.$sidebar['name'].'</option>';
				} else {
					echo '<option value="'.$sidebar_id.'">'.$sidebar['name'].'</option>';
				}
			}
		echo '</select><br />';
		//show custom sidebar position
		$csidebarpos = get_post_meta( $post->ID, '_safira_custom_sidebar_pos', true);
		echo '<label for="safira_custom_sidebar_pos">';
		esc_html_e( 'Sidebar position', 'safira');
		echo '</label><br />';
		echo '<select id="safira_custom_sidebar_pos" name="safira_custom_sidebar_pos">'; ?>
			<option value="left" <?php if($csidebarpos == 'left') {echo 'selected="selected"';}?>><?php echo esc_html__('Left', 'safira'); ?></option>
			<option value="right" <?php if($csidebarpos == 'right') {echo 'selected="selected"';}?>><?php echo esc_html__('Right', 'safira'); ?></option>
		<?php echo '</select>';
	}
	function safira_save_meta_box_data( $post_id ) {
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Check if our nonce is set.
		if ( ! isset( $_POST['safira_meta_box_nonce'] ) ) {
			return;
		}
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['safira_meta_box_nonce'], 'safira_meta_box' ) ) {
			return;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		/* OK, it's safe for us to save the data now. */
		// Make sure that it is set.
		if ( ! ( isset( $_POST['safira_post_intro'] ) || isset( $_POST['safira_custom_sidebar'] ) ) )  {
			return;
		}
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['safira_post_intro']);
		// Update the meta field in the database.
		update_post_meta( $post_id, '_safira_post_intro', $my_data);
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['safira_custom_sidebar']);
		// Update the meta field in the database.
		update_post_meta( $post_id, '_safira_custom_sidebar', $my_data);
		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['safira_custom_sidebar_pos']);
		// Update the meta field in the database.
		update_post_meta( $post_id, '_safira_custom_sidebar_pos', $my_data);
	}
	//Change comment form
	function safira_before_comment_fields() {
		echo '<div class="comment-input">';
	}
	function safira_after_comment_fields() {
		echo '</div>';
	}
	/**
	 * Register postMessage support.
	 *
	 * Add postMessage support for site title and description for the Customizer.
	 *
	 * @since Safira 1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	function safira_customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}
	/**
	 * Enqueue Javascript postMessage handlers for the Customizer.
	 *
	 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
	 *
	 * @since Safira 1.0
	 */
	function safira_customize_preview_js() {
		wp_enqueue_script( 'safira-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130301', true);
	}
	function safira_admin_style() {
	  wp_enqueue_style('safira-admin-styles', get_template_directory_uri().'/css/admin.css');
	}
	/**
	* Utility methods
	* ---------------
	*/
	//Add breadcrumbs
	static function safira_breadcrumb() {
		global $post;
		$safira_opt = get_option( 'safira_opt');
		$brseparator = '<span class="separator">/</span>';
		if (!is_home()) {
			echo '<div class="breadcrumbs">';
			echo '<a href="';
			echo esc_url( home_url( '/' ));
			echo '">';
			echo esc_html__('Home', 'safira');
			echo '</a>'.$brseparator;
			if (is_category() || is_single()) {
				$categories = get_the_category();
				if ( count( $categories ) > 0 ) {
					echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
				}
				if (is_single()) {
					if ( count( $categories ) > 0 ) { echo ''.$brseparator; }
					the_title();
				}
			} elseif (is_page()) {
				if($post->post_parent){
					$anc = get_post_ancestors( $post->ID);
					$title = get_the_title();
					foreach ( $anc as $ancestor ) {
						$output = '<a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a>'.$brseparator;
					}
					echo wp_kses($output, array(
							'a'=>array(
								'href' => array(),
								'title' => array()
							),
							'span'=>array(
								'class'=>array()
							)
						)
					);
					echo '<span title="'.esc_attr($title).'"> '.esc_html($title).'</span>';
				} else {
					echo '<span> '.get_the_title().'</span>';
				}
			}
			elseif (is_tag()) {single_tag_title();}
			elseif (is_day()) {printf( esc_html__( 'Archive for: %s', 'safira' ), '<span>' . get_the_date() . '</span>');}
			elseif (is_month()) {printf( esc_html__( 'Archive for: %s', 'safira' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'safira' ) ) . '</span>');}
			elseif (is_year()) {printf( esc_html__( 'Archive for: %s', 'safira' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'safira' ) ) . '</span>');}
			elseif (is_author()) {echo "<span>".esc_html__('Archive for','safira'); echo'</span>';}
			elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<span>".esc_html__('Blog Archives','safira'); echo'</span>';}
			elseif (is_search()) {echo "<span>".esc_html__('Search Results','safira'); echo'</span>';}
			echo '</div>';
		} else {
			echo '<div class="breadcrumbs">';
			echo '<a href="';
			echo esc_url( home_url( '/' ));
			echo '">';
			echo esc_html__('Home', 'safira');
			echo '</a>'.$brseparator;
			if(isset($safira_opt['blog_header_text']) && $safira_opt['blog_header_text']!=""){
				echo esc_html($safira_opt['blog_header_text']);
			} else {
				echo esc_html__('Blog', 'safira');
			}
			echo '</div>';
		}
	}
	static function safira_limitStringByWord ($string, $maxlength, $suffix = '') {
		if(function_exists( 'mb_strlen' )) {
			// use multibyte functions by Iysov
			if(mb_strlen( $string )<=$maxlength) return $string;
			$string = mb_substr( $string, 0, $maxlength);
			$index = mb_strrpos( $string, ' ');
			if($index === FALSE) {
				return $string;
			} else {
				return mb_substr( $string, 0, $index ).$suffix;
			}
		} else { // original code here
			if(strlen( $string )<=$maxlength) return $string;
			$string = substr( $string, 0, $maxlength);
			$index = strrpos( $string, ' ');
			if($index === FALSE) {
				return $string;
			} else {
				return substr( $string, 0, $index ).$suffix;
			}
		}
	}
	static function safira_excerpt_by_id($post, $length = 25, $tags = '<a><span><em><strong>') {
		if ( is_numeric( $post ) ) {
			$post = get_post( $post);
		} elseif( ! is_object( $post ) ) {
			return false;
		}
		if ( has_excerpt( $post->ID ) ) {
			$the_excerpt = $post->post_excerpt;
			return apply_filters( 'the_content', $the_excerpt);
		} else {
			$the_excerpt = $post->post_content;
		}
		$the_excerpt = strip_shortcodes( strip_tags( $the_excerpt, $tags ));
		$the_excerpt = preg_split( '/\b/', $the_excerpt, $length * 2 + 1);
		$excerpt_waste = array_pop( $the_excerpt);
		$the_excerpt = implode( $the_excerpt);
		return apply_filters( 'the_content', $the_excerpt);
	}
	/**
	 * Return the Google font stylesheet URL if available.
	 *
	 * The use of Khula by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * @since Safira 1.0
	 *
	 * @return string Font stylesheet or empty string if disabled.
	 */
	function safira_get_font_url() {
		$fonts_url = '';
		/* Translators: If there are characters in your language that are not
		* supported by Work Sans, Rozha One translate this to 'off'. Do not translate
		* into your own language.
		*/
		$worksans = _x( 'on', ' Work Sans: on or off', 'safira');
		$rozhaone = _x( 'on', 'Rozha One font: on or off', 'safira');
		if ( 'off' !== $worksans ) {
			$font_families[] = 'Work Sans:300,400,500,600,700';
		}
		if ( 'off' !== $rozhaone ) {
			$font_families[] = 'Rozha One:400';
		}
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css'); // old https
		return esc_url_raw( $fonts_url);
	}
	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since Safira 1.0
	 */
	static function safira_content_nav( $html_id ) {
		global $wp_query;
		$html_id = esc_attr( $html_id);
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo esc_attr($html_id); ?>" class="navigation" role="navigation">
				<h3 class="assistive-text"><?php esc_html_e( 'Post navigation', 'safira'); ?></h3>
				<div class="nav-previous"><?php next_posts_link( wp_kses(__( '<span class="meta-nav">&larr;</span> Older posts', 'safira' ),array('span'=>array('class'=>array())) )); ?></div>
				<div class="nav-next"><?php previous_posts_link( wp_kses(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'safira' ), array('span'=>array('class'=>array())) )); ?></div>
			</nav>
		<?php endif;
	}
	/* Pagination */
	static function safira_pagination() {
		global $wp_query, $paged;
		if(empty($paged)) $paged = 1;
		$pages = $wp_query->max_num_pages;
			if(!$pages || $pages == '') {
			   	$pages = 1;
			}
		if(1 != $pages) {
			echo '<div class="pagination-container"><div class="pagination">';
			$big = 999999999; // need an unlikely integer
			echo paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages,
				'prev_text'    => esc_html__('Previous', 'safira'),
				'next_text'    =>esc_html__('Next', 'safira')
			));
			echo '</div></div>';
		}
	}
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own safira_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Safira 1.0
	 */
	static function safira_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php esc_html_e( 'Pingback:', 'safira'); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'safira' ), '<span class="edit-link">', '</span>'); ?></p>
		<?php
				break;
			default :
			// Proceed with normal comments.
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-avatar">
					<?php echo get_avatar( $comment, 50); ?>
				</div>
				<div class="comment-info">
					<header class="comment-meta comment-author vcard">
						<?php
							printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
								get_comment_author_link(),
								// If current post author is also comment author, make it known visually.
								( $comment->user_id === $post->post_author ) ? '<span>' . esc_html__( 'Post author', 'safira' ) . '</span>' : ''
							);
							printf( '<time datetime="%1$s">%2$s</time>',
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( esc_html__( '%1$s at %2$s', 'safira' ), get_comment_date('M d.Y'), get_comment_time() )
							);
						?>
						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'safira' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) )); ?>
						</div><!-- .reply -->
					</header><!-- .comment-meta -->
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'safira'); ?></p>
					<?php endif; ?>
					<section class="comment-content comment">
						<?php comment_text(); ?>
						<?php edit_comment_link( esc_html__( 'Edit', 'safira' ), '<p class="edit-link">', '</p>'); ?>
					</section><!-- .comment-content -->
				</div>
			</article><!-- #comment-## -->
		<?php
			break;
		endswitch; // end comment_type check
	}
	/**
	 * Set up post entry meta.
	 *
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own safira_entry_meta() to override in a child theme.
	 *
	 * @since Safira 1.0
	 */
	static function safira_entry_meta() {
		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', ', ');
		$num_comments = (int)get_comments_number();
		$write_comments = '';
		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = esc_html__('0 comments', 'safira');
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . esc_html__(' comments', 'safira');
			} else {
				$comments = esc_html__('1 comment', 'safira');
			}
			$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
		}
		$utility_text = null;
		if ( ( post_password_required() || !comments_open() ) && ($tag_list != false && isset($tag_list) ) ) {
			$utility_text = esc_html__( 'Tags: %2$s', 'safira');
		} elseif ( $tag_list != false && isset($tag_list) && $num_comments != 0 ) {
			$utility_text = esc_html__( '%1$s / Tags: %2$s', 'safira');
		} elseif ( ($num_comments == 0 || !isset($num_comments) ) && $tag_list==true ) {
			$utility_text = esc_html__( 'Tags: %2$s', 'safira');
		} else {
			$utility_text = esc_html__( '%1$s', 'safira');
		}
		if ( ($tag_list != false && isset($tag_list)) || $num_comments != 0 ) { ?>
			<div class="entry-meta">
				<?php printf( $utility_text, $write_comments, $tag_list); ?>
			</div>
		<?php }
	}
	static function safira_entry_meta_small() {
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list(', ');
		$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( wp_kses(__( 'View all posts by %s', 'safira' ), array('a'=>array())), get_the_author() ) ),
			get_the_author()
		);
		$utility_text = esc_html__( 'Posted by %1$s / %2$s', 'safira');
		printf( $utility_text, $author, $categories_list);
	}
	static function safira_entry_comments() {
		$date = sprintf( '<time class="entry-date" datetime="%3$s">%4$s</time>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
		$num_comments = (int)get_comments_number();
		$write_comments = '';
		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = wp_kses(__('<span>0</span> comments', 'safira'), array('span'=>array()));
			} elseif ( $num_comments > 1 ) {
				$comments = '<span>'.$num_comments .'</span>'. esc_html__(' comments', 'safira');
			} else {
				$comments = wp_kses(__('<span>1</span> comment', 'safira'), array('span'=>array()));
			}
			$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
		}
		$utility_text = esc_html__( '%1$s', 'safira');
		printf( $utility_text, $write_comments);
	}
	/**
	* TGM-Plugin-Activation
	*/
	function safira_register_required_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__('RoadThemes Helper', 'safira'),
				'slug'               => 'roadthemes-helper',
				'source'             => get_template_directory() . '/plugins/roadthemes-helper.zip',
				'required'           => true,
				'version'            => '1.0.0',
				'force_activation'   => false,
				'force_deactivation' => false,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Mega Main Menu', 'safira'),
				'slug'               => 'mega_main_menu',
				'source'             => PLUGIN_REQUIRED_PATH . '/mega_main_menu.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Import Sample Data', 'safira'),
				'slug'               => 'road-importdata',
				'source'             => get_template_directory() . '/plugins/road-importdata.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Revolution Slider', 'safira'),
				'slug'               => 'revslider',
				'source'             => PLUGIN_REQUIRED_PATH . '/revslider.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Visual Composer', 'safira'),
				'slug'               => 'js_composer',
				'source'             => PLUGIN_REQUIRED_PATH . '/js_composer.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Templatera', 'safira'),
				'slug'               => 'templatera',
				'source'             => PLUGIN_REQUIRED_PATH . '/templatera.zip',
				'required'           => true,
				'external_url'       => '',
			),
			array(
				'name'               => esc_html__('Testimonials', 'safira'),
				'slug'               => 'testimonials-by-woothemes',
				'source'             => get_template_directory() . '/plugins/testimonials-by-woothemes.zip',
				'required'           => true,
				'external_url'       => '',
			),
			// Plugins from the WordPress Plugin Rsafiratory.
			array(
				'name'               => esc_html__('Redux Framework', 'safira'),
				'slug'               => 'redux-framework',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'      => esc_html__('Contact Form 7', 'safira'),
				'slug'      => 'contact-form-7',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('MailChimp for WordPress', 'safira'),
				'slug'      => 'mailchimp-for-wp',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('Shortcodes Ultimate', 'safira'),
				'slug'      => 'shortcodes-ultimate',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('Simple Local Avatars', 'safira'),
				'slug'      => 'simple-local-avatars',
				'required'  => false,
			),
			
			array(
				'name'      => esc_html__('TinyMCE Advanced', 'safira'),
				'slug'      => 'tinymce-advanced',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('Widget Importer & Exporter', 'safira'),
				'slug'      => 'widget-importer-exporter',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('WooCommerce', 'safira'),
				'slug'      => 'woocommerce',
				'required'  => true,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Compare', 'safira'),
				'slug'      => 'yith-woocommerce-compare',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Wishlist', 'safira'),
				'slug'      => 'yith-woocommerce-wishlist',
				'required'  => false,
			),
			array(
				'name'      => esc_html__('YITH WooCommerce Zoom Magnifier', 'safira'),
				'slug'      => 'yith-woocommerce-zoom-magnifier',
				'required'  => false,
			),
		);
		/**
		 * Array of configuration settings. Amend each line as needed.
		 * If you want the default strings to be available under your own theme domain,
		 * leave the strings uncommented.
		 * Some of the strings are added into a sprintf, so see the comments at the
		 * end of each line for what each argument will be.
		 */
		$config = array(
			'default_path' => '',                      // Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'safira' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'safira' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'safira' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'safira' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'safira' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'safira' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'safira' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'safira' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'safira' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'safira' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'safira' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'safira' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'safira' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'safira' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'safira' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'safira' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'safira' ), // %s = dashboard link.
				'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			)
		);
		tgmpa( $plugins, $config);
	}
}
// Instantiate theme
$Safira_Class = new Safira_Class();
//Fix duplicate id of mega menu
function safira_mega_menu_id_change($params) {
	ob_start('safira_mega_menu_id_change_call_back');
}
function safira_mega_menu_id_change_call_back($html){
	$html = preg_replace('/id="mega_main_menu"/', 'id="mega_main_menu_first"', $html, 1);
	$html = preg_replace('/id="mega_main_menu_ul"/', 'id="mega_main_menu_ul_first"', $html, 1);
	return $html;
}
add_action('wp_loaded', 'safira_mega_menu_id_change');
function safira_enqueue_script() {
	wp_add_inline_script( 'safira-theme', 'var ajaxurl = "'.admin_url('admin-ajax.php').'";','before');
}
add_action( 'wp_enqueue_scripts', 'safira_enqueue_script');
// Wishlist count
if( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ){
	function yith_wcwl_ajax_update_count(){
		wp_send_json( array(
			'count' => yith_wcwl_count_all_products()
		));
	}
	add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
	add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
}
function safira_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'safira_pingback_header' );