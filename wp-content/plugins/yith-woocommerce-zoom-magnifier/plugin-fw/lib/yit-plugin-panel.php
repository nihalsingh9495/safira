<?php
/**
 * This file belongs to the YIT Plugin Framework.
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !class_exists( 'YIT_Plugin_Panel' ) ) {
    /**
     * YIT Plugin Panel
     * Setting Page to Manage Plugins
     *
     * @class      YIT_Plugin_Panel
     * @package    YITH
     * @since      1.0
     * @author     Your Inspiration Themes
     */
    class YIT_Plugin_Panel {

        /**
         * @var string version of class
         */
        public $version = '1.0.0';

        /**
         * @var array a setting list of parameters
         */
        public $settings = array();

        /**
         * @var array
         */
        protected $_tabs_path_files;

        /**
         * @var array
         */
        protected $_main_array_options;

        /**
         * @var array
         */
        protected $_tabs_hierarchy;

        /**
         * @var array
         */
        protected static $_panel_tabs_in_wp_pages = array();

        /**
         * @var array
         */
        public $links;

        /**
         * @var bool
         */
        protected static $_actions_initialized = false;

        /**
         * Constructor
         *
         * @param array $args
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         * @since  1.0
         */
        public function __construct( $args = array() ) {

            if ( !empty( $args ) ) {

                $default_args = array(
                    'parent_slug' => 'edit.php?',
                    'page_title'  => __( 'Plugin Settings', 'yith-plugin-fw' ),
                    'menu_title'  => __( 'Settings', 'yith-plugin-fw' ),
                    'capability'  => 'manage_options',
                    'icon_url'    => '',
                    'position'    => null
                );

                $args = apply_filters( 'yit_plugin_fw_panel_option_args', wp_parse_args( $args, $default_args ) );
                if ( isset( $args[ 'parent_page' ] ) && 'yit_plugin_panel' === $args[ 'parent_page' ] )
                    $args[ 'parent_page' ] = 'yith_plugin_panel';

                $this->settings         = $args;
                $this->_tabs_path_files = $this->get_tabs_path_files();

                if ( isset( $this->settings[ 'create_menu_page' ] ) && $this->settings[ 'create_menu_page' ] ) {
                    $this->add_menu_page();
                }

                if ( !empty( $this->settings[ 'links' ] ) ) {
                    $this->links = $this->settings[ 'links' ];
                }

                add_action( 'admin_init', array( $this, 'register_settings' ) );
                add_action( 'admin_menu', array( $this, 'add_setting_page' ), 20 );
                add_action( 'admin_menu', array( $this, 'add_premium_version_upgrade_to_menu' ), 100 );
                add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 100 );
                add_action( 'admin_init', array( $this, 'add_fields' ) );

                add_action( 'admin_enqueue_scripts', array( $this, 'init_wp_with_tabs' ), 11 );

                // init actions once to prevent multiple actions
                static::_init_actions();
            }

            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

            //yith-plugin-ui
            add_action( 'yith_plugin_fw_before_yith_panel', array( $this, 'add_plugin_banner' ), 10, 1 );
            add_action( 'wp_ajax_yith_plugin_fw_save_toggle_element', array( $this, 'save_toggle_element_options' ) );

        }

        /**
         * Init actions to show YITH Panel tabs in WP Pages
         *
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @since    3.4.0
         */
        public function init_wp_with_tabs() {
            if ( !current_user_can( $this->settings[ 'capability' ] ) ) {
                return;
            }

            global $pagenow, $post_type, $taxonomy;
            $tabs = false;

            if ( in_array( $pagenow, array( 'post.php', 'post-new.php', 'edit.php' ), true )
                 && !in_array( $post_type, array( 'product', 'page', 'post' ) ) ) {
                $tabs = $this->get_post_type_tabs( $post_type );
            } else if ( in_array( $pagenow, array( 'edit-tags.php', 'term.php' ), true ) ) {
                $tabs = $this->get_taxonomy_tabs( $taxonomy );
            }

            if ( $tabs ) {
                // tabs_in_edit
                $current_tab_args = array(
                    'page'            => $this->settings[ 'page' ],
                    'current_tab'     => isset( $tabs[ 'tab' ] ) ? $tabs[ 'tab' ] : '',
                    'current_sub_tab' => isset( $tabs[ 'sub_tab' ] ) ? $tabs[ 'sub_tab' ] : ''
                );

                wp_enqueue_style( 'yit-plugin-style' );
                wp_enqueue_style( 'yith-plugin-fw-fields' );
                wp_enqueue_script( 'yith-plugin-fw-wp-pages' );

                if ( !self::$_panel_tabs_in_wp_pages ) {
                    self::$_panel_tabs_in_wp_pages = $current_tab_args;
                    add_action( 'all_admin_notices', array( $this, 'print_panel_tabs_in_wp_pages' ) );
                    add_action( 'admin_footer', array( $this, 'print_panel_tabs_in_wp_pages_end' ) );
                    add_filter( 'parent_file', array( $this, 'set_parent_file_to_handle_menu_for_wp_pages' ) );
                    add_filter( 'submenu_file', array( $this, 'set_submenu_file_to_handle_menu_for_wp_pages' ), 10, 2 );
                }
            }
        }

        /**
         * Init actions once to prevent multiple actions
         *
         * @since  3.0.0
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         */
        protected static function _init_actions() {
            if ( !static::$_actions_initialized ) {
                add_filter( 'admin_body_class', array( __CLASS__, 'add_body_class' ) );

                // sort plugins by name in YITH Plugins menu
                add_action( 'admin_menu', array( __CLASS__, 'sort_plugins' ), 90 );
                add_filter( 'add_menu_classes', array( __CLASS__, 'add_menu_class_in_yith_plugin' ) );


                static::$_actions_initialized = true;
            }
        }

        protected function _maybe_init_vars() {
            if ( !isset( $this->_main_array_options ) && !isset( $this->_tabs_hierarchy ) ) {
                $options_path              = $this->settings[ 'options-path' ];
                $this->_main_array_options = array();
                $this->_tabs_hierarchy     = array();

                foreach ( $this->settings[ 'admin-tabs' ] as $item => $v ) {
                    $path = trailingslashit( $options_path ) . $item . '-options.php';
                    $path = apply_filters( 'yith_plugin_panel_item_options_path', $path, $options_path, $item, $this );
                    if ( file_exists( $path ) ) {
                        $_tab                      = include $path;
                        $this->_main_array_options = array_merge( $this->_main_array_options, $_tab );
                        $sub_tabs                  = $this->get_sub_tabs( $_tab );
                        $current_tab_key           = array_keys( $_tab )[ 0 ];

                        $this->_tabs_hierarchy[ $current_tab_key ] = array_merge( array( 'parent' => '', 'has_sub_tabs' => !!$sub_tabs ), $this->get_tab_info_by_options( $_tab[ $current_tab_key ] ) );

                        foreach ( $sub_tabs as $sub_item => $sub_options ) {
                            if ( strpos( $sub_item, $item . '-' ) === 0 ) {
                                $sub_item = substr( $sub_item, strlen( $item ) + 1 );
                            }
                            $sub_tab_path = $options_path . '/' . $item . '/' . $sub_item . '-options.php';
                            $sub_tab_path = apply_filters( 'yith_plugin_panel_sub_tab_item_options_path', $sub_tab_path, $sub_tabs, $sub_item, $this );

                            if ( file_exists( $sub_tab_path ) ) {
                                $_sub_tab                  = include $sub_tab_path;
                                $this->_main_array_options = array_merge( $this->_main_array_options, $_sub_tab );

                                $current_sub_tab_key                           = array_keys( $_sub_tab )[ 0 ];
                                $this->_tabs_hierarchy[ $current_sub_tab_key ] = array_merge( array( 'parent' => $current_tab_key ), $this->get_tab_info_by_options( $_sub_tab[ $current_sub_tab_key ] ) );
                            }
                        }
                    }
                }
            }
        }

        /**
         * Add yith-plugin-fw-panel in body classes in Panel pages
         *
         * @param $admin_body_classes
         * @return string
         * @author Leanza Francesco <leanzafrancesco@gmail.com>
         * @since  3.0.0
         */
        public static function add_body_class( $admin_body_classes ) {
            global $pagenow;
            if ( ( 'admin.php' == $pagenow && strpos( get_current_screen()->id, 'yith-plugins_page' ) !== false ) )
                $admin_body_classes = substr_count( $admin_body_classes, ' yith-plugin-fw-panel ' ) == 0 ? $admin_body_classes . ' yith-plugin-fw-panel ' : $admin_body_classes;

            return $admin_body_classes;
        }

        /**
         * Add Menu page link
         *
         * @return void
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function add_menu_page() {
            global $admin_page_hooks;

            if ( !isset( $admin_page_hooks[ 'yith_plugin_panel' ] ) ) {
                $position   = apply_filters( 'yit_plugins_menu_item_position', '62.32' );
                $capability = apply_filters( 'yit_plugin_panel_menu_page_capability', 'manage_options' );
                $show       = apply_filters( 'yit_plugin_panel_menu_page_show', true );

                //  YITH text must not be translated
                if ( !!$show ) {
                    add_menu_page( 'yith_plugin_panel', 'YITH', $capability, 'yith_plugin_panel', null, yith_plugin_fw_get_default_logo(), $position );
                    $admin_page_hooks[ 'yith_plugin_panel' ] = 'yith-plugins'; // prevent issues for backward compatibility
                }
            }
        }

        /**
         * Remove duplicate submenu
         * Submenu page hack: Remove the duplicate YIT Plugin link on subpages
         *
         * @return void
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function remove_duplicate_submenu_page() {
            /* === Duplicate Items Hack === */
            remove_submenu_page( 'yith_plugin_panel', 'yith_plugin_panel' );
        }

        /**
         * Enqueue script and styles in admin side
         * Add style and scripts to administrator
         *
         * @return void
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function admin_enqueue_scripts() {
            global $pagenow;

            // enqueue styles only in the current panel page
            if ( 'admin.php' === $pagenow && strpos( get_current_screen()->id, $this->settings[ 'page' ] ) !== false || apply_filters( 'yit_plugin_panel_asset_loading', false ) ) {
                wp_enqueue_media();

                wp_enqueue_style( 'yith-plugin-fw-fields' );
                wp_enqueue_style( 'yit-jquery-ui-style' );
                wp_enqueue_style( 'raleway-font' );

                wp_enqueue_script( 'jquery-ui' );
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-dialog' );
                wp_enqueue_script( 'yith_how_to' );
                wp_enqueue_script( 'yith-plugin-fw-fields' );
            }

            if ( ( 'admin.php' == $pagenow && yith_plugin_fw_is_panel() ) || apply_filters( 'yit_plugin_panel_asset_loading', false ) ) {
                wp_enqueue_media();
                wp_enqueue_style( 'yit-plugin-style' );
                wp_enqueue_script( 'yit-plugin-panel' );
            }

            if ( 'admin.php' == $pagenow && strpos( get_current_screen()->id, 'yith_upgrade_premium_version' ) !== false ) {
                wp_enqueue_style( 'yit-upgrade-to-pro' );
                wp_enqueue_script( 'colorbox' );
            }

        }

        /**
         * Register Settings
         * Generate wp-admin settings pages by registering your settings and using a few callbacks to control the output
         *
         * @return void
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function register_settings() {
            register_setting( 'yit_' . $this->settings[ 'parent' ] . '_options', 'yit_' . $this->settings[ 'parent' ] . '_options', array( $this, 'options_validate' ) );
        }

        /**
         * Options Validate
         * a callback function called by Register Settings function
         *
         * @param $input
         * @return array validate input fields
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function options_validate( $input ) {

            $option_key = !empty( $input[ 'option_key' ] ) ? $input[ 'option_key' ] : 'general';

            $yit_options = $this->get_main_array_options();

            // default
            $valid_input = $this->get_options();

            $submit = ( !empty( $input[ 'submit-general' ] ) ? true : false );
            $reset  = ( !empty( $input[ 'reset-general' ] ) ? true : false );

            foreach ( $yit_options[ $option_key ] as $section => $data ) {
                foreach ( $data as $option ) {
                    if ( isset( $option[ 'sanitize_call' ] ) && isset( $option[ 'id' ] ) ) { //yiw_debug($option, false);
                        if ( is_array( $option[ 'sanitize_call' ] ) ) :
                            foreach ( $option[ 'sanitize_call' ] as $callback ) {
                                if ( is_array( $input[ $option[ 'id' ] ] ) ) {
                                    $valid_input[ $option[ 'id' ] ] = array_map( $callback, $input[ $option[ 'id' ] ] );
                                } else {
                                    $valid_input[ $option[ 'id' ] ] = call_user_func( $callback, $input[ $option[ 'id' ] ] );
                                }
                            }
                        else :
                            if ( is_array( $input[ $option[ 'id' ] ] ) ) {
                                $valid_input[ $option[ 'id' ] ] = array_map( $option[ 'sanitize_call' ], $input[ $option[ 'id' ] ] );
                            } else {
                                $valid_input[ $option[ 'id' ] ] = call_user_func( $option[ 'sanitize_call' ], $input[ $option[ 'id' ] ] );
                            }
                        endif;
                    } else {
                        if ( isset( $option[ 'id' ] ) ) {
                            $value = isset( $input[ $option[ 'id' ] ] ) ? $input[ $option[ 'id' ] ] : false;
                            if ( isset( $option[ 'type' ] ) && in_array( $option[ 'type' ], array( 'checkbox', 'onoff' ) ) ) {
                                $value = yith_plugin_fw_is_true( $value ) ? 'yes' : 'no';
                            }

                            if ( !empty( $option[ 'yith-sanitize-callback' ] ) && is_callable( $option[ 'yith-sanitize-callback' ] ) ) {
                                $value = call_user_func( $option[ 'yith-sanitize-callback' ], $value );
                            }

                            $valid_input[ $option[ 'id' ] ] = $value;
                        }
                    }

                }
            }

            return $valid_input;
        }

        /**
         * Add Setting SubPage
         * add Setting SubPage to wordpress administrator
         *
         * @return array validate input fields
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function add_setting_page() {
            $this->settings[ 'icon_url' ] = isset( $this->settings[ 'icon_url' ] ) ? $this->settings[ 'icon_url' ] : '';
            $this->settings[ 'position' ] = isset( $this->settings[ 'position' ] ) ? $this->settings[ 'position' ] : null;
            $parent                       = $this->settings[ 'parent_slug' ] . $this->settings[ 'parent_page' ];

            if ( !empty( $parent ) ) {
                add_submenu_page( $parent, $this->settings[ 'page_title' ], $this->settings[ 'menu_title' ], $this->settings[ 'capability' ], $this->settings[ 'page' ], array( $this, 'yit_panel' ) );
            } else {
                add_menu_page( $this->settings[ 'page_title' ], $this->settings[ 'menu_title' ], $this->settings[ 'capability' ], $this->settings[ 'page' ], array( $this, 'yit_panel' ), $this->settings[ 'icon_url' ], $this->settings[ 'position' ] );
            }
            /* === Duplicate Items Hack === */
            $this->remove_duplicate_submenu_page();
            do_action( 'yit_after_add_settings_page' );


        }

        /**
         * Add Premium Version upgrade menu item
         *
         * @return   void
         * @since    2.9.13
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         */
        public function add_premium_version_upgrade_to_menu() {
            /* === Add the How To menu item only if the customer haven't a premium version enabled === */
            if ( function_exists( 'YIT_Plugin_Licence' ) && !!YIT_Plugin_Licence()->get_products() ) {
                return;
            }

            global $submenu;
            if ( apply_filters( 'yit_show_upgrade_to_premium_version', isset( $submenu[ 'yith_plugin_panel' ] ) ) ) {
                $submenu[ 'yith_plugin_panel' ][ 'how_to' ] = array(
                    sprintf( '%s%s%s', '<span id="yith-how-to-premium">', __( 'How to install premium version', 'yith-plugin-fw' ), '</span>' ),
                    'install_plugins',
                    '//support.yithemes.com/hc/en-us/articles/217840988',
                    __( 'How to install premium version', 'yith-plugin-fw' ),
                );
            }
        }

        /**
         * Print the tabs navigation
         *
         * @param array $args
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @since    3.4.0
         */
        public function print_tabs_nav( $args = array() ) {
            $defaults = array(
                'current_tab'   => $this->get_current_tab(),
                'premium_class' => isset( $this->settings[ 'class' ] ) ? 'yith-premium' : 'premium',
                'page'          => $this->settings[ 'page' ],
                'parent_page'   => $this->settings[ 'parent_page' ],
                'wrapper_class' => 'nav-tab-wrapper'
            );
            $args     = wp_parse_args( $args, $defaults );
            /**
             * @var string $current_tab
             * @var string $premium_class
             * @var string $page
             * @var string $parent_page
             * @var string $wrapper_class
             */
            extract( $args );

            $tabs = '';

            foreach ( $this->settings[ 'admin-tabs' ] as $tab => $tab_value ) {
                $active_class  = ( $current_tab == $tab ) ? ' nav-tab-active' : '';
                $active_class  .= 'premium' == $tab ? ' ' . $premium_class : '';
				$active_class  = apply_filters( 'yith_plugin_fw_panel_active_tab_class', $active_class, $current_tab, $tab );

                $first_sub_tab = $this->get_first_sub_tab_key( $tab );
                $sub_tab       = !!$first_sub_tab ? $first_sub_tab : '';

                $url = $this->get_nav_url( $page, $tab, $sub_tab, $parent_page );

                $tabs .= '<a class="nav-tab' . $active_class . '" href="' . $url . '">' . $tab_value . '</a>';
            }
            ?>
            <h2 class="<?php echo $wrapper_class ?>">
                <?php echo $tabs ?>
            </h2>
            <?php
            $this->print_sub_tabs_nav( $args );
        }

        /**
         * @param string $page
         * @param string $tab
         * @param string $sub_tab
         * @param string $parent_page
         * @return string
         */
        public function get_nav_url( $page, $tab, $sub_tab = '', $parent_page = '' ) {
            $tab_hierarchy = $this->get_tabs_hierarchy();
            $key           = !!$sub_tab ? $sub_tab : $tab;

            if ( isset( $tab_hierarchy[ $key ], $tab_hierarchy[ $key ][ 'type' ], $tab_hierarchy[ $key ][ 'post_type' ] ) && 'post_type' === $tab_hierarchy[ $key ][ 'type' ] ) {
                $url = admin_url( "edit.php?post_type={$tab_hierarchy[$key]['post_type']}" );
            } elseif ( isset( $tab_hierarchy[ $key ], $tab_hierarchy[ $key ][ 'type' ], $tab_hierarchy[ $key ][ 'taxonomy' ] ) && 'taxonomy' === $tab_hierarchy[ $key ][ 'type' ] ) {
                $url = admin_url( "edit-tags.php?taxonomy={$tab_hierarchy[$key]['taxonomy']}" );
            } else {
                $url = !!$parent_page ? "?{$parent_page}&" : '?';
                $url .= "page={$page}&tab={$tab}";
                $url .= !!$sub_tab ? "&sub_tab={$sub_tab}" : '';
                $url = admin_url( "admin.php{$url}" );
            }

            return apply_filters( 'yith_plugin_fw_panel_url', $url, $page, $tab, $sub_tab, $parent_page );
        }

        /**
         * Print the Sub-tabs navigation if the current tab has sub-tabs
         *
         * @param array $args
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @since    3.4.0
         */
        public function print_sub_tabs_nav( $args = array() ) {
            $defaults = array(
                'current_tab'     => $this->get_current_tab(),
                'page'            => $this->settings[ 'page' ],
                'current_sub_tab' => $this->get_current_sub_tab(),
            );
            $args     = wp_parse_args( $args, $defaults );

            /**
             * @var string $current_tab
             * @var string $page
             * @var string $current_sub_tab
             */
            extract( $args );

            $sub_tabs = $this->get_sub_tabs( $current_tab );

            if ( $sub_tabs && $current_sub_tab ) {
                include YIT_CORE_PLUGIN_TEMPLATE_PATH . '/panel/sub-tabs-nav.php';
            }
        }

        /**
         * Show a tabbed panel to setting page
         * a callback function called by add_setting_page => add_submenu_page
         *
         * @return void
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function yit_panel() {
            $this->maybe_redirect_to_proper_wp_page();
            $yit_options = $this->get_main_array_options();
            $wrap_class  = isset( $this->settings[ 'class' ] ) ? $this->settings[ 'class' ] : '';

            $option_key        = $this->get_current_option_key();
            $custom_tab_action = $this->is_custom_tab( $yit_options, $option_key );
            ?>
            <div class="wrap <?php echo $wrap_class ?>">
                <div id="icon-themes" class="icon32"><br/></div>
                <?php
                do_action( 'yith_plugin_fw_before_yith_panel', $this->settings[ 'page' ] );

                $this->print_tabs_nav();

                if ( $custom_tab_action ) {
                    $this->print_custom_tab( $custom_tab_action );
                    return;
                }

                $panel_content_class = apply_filters( 'yit_admin_panel_content_class', 'yit-admin-panel-content-wrap' );
                ?>
                <div id="wrap" class="yith-plugin-fw plugin-option yit-admin-panel-container">
                    <?php $this->message(); ?>
                    <div class="<?php echo $panel_content_class; ?>">
                        <h2><?php echo $this->get_tab_title() ?></h2>
                        <?php if ( $this->is_show_form() ) : ?>
                            <form id="yith-plugin-fw-panel" method="post" action="options.php">
                                <?php do_settings_sections( 'yit' ); ?>
                                <p>&nbsp;</p>
                                <?php settings_fields( 'yit_' . $this->settings[ 'parent' ] . '_options' ); ?>
                                <input type="hidden" name="<?php echo $this->get_name_field( 'option_key' ) ?>" value="<?php echo esc_attr( $option_key ) ?>"/>
                                <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'yith-plugin-fw' ) ?>" style="float:left;margin-right:10px;"/>
                            </form>
                            <form method="post">
                                <?php $warning = __( 'If you continue with this action, you will reset all options in this page.', 'yith-plugin-fw' ) ?>
                                <input type="hidden" name="yit-action" value="reset"/>
                                <input type="submit" name="yit-reset" class="button-secondary" value="<?php _e( 'Reset to default', 'yith-plugin-fw' ) ?>"
                                       onclick="return confirm('<?php echo $warning . '\n' . __( 'Are you sure?', 'yith-plugin-fw' ) ?>');"/>
                            </form>
                            <p>&nbsp;</p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * Check if is a custom tab
         *
         * @param array  $options
         * @param string $option_key
         * @return bool
         */
        public function is_custom_tab( $options, $option_key ) {
            foreach ( $options[ $option_key ] as $section => $option ) {
                if ( isset( $option[ 'type' ] ) && isset( $option[ 'action' ] ) && 'custom_tab' == $option[ 'type' ] && !empty( $option[ 'action' ] ) ) {
                    return $option[ 'action' ];
                } else {
                    return false;
                }
            }

            return false;
        }

        public function get_tab_type_by_options( $tab_options ) {
            $first         = !!$tab_options && is_array( $tab_options ) ? current( $tab_options ) : array();
            $type          = isset( $first[ 'type' ] ) ? $first[ 'type' ] : 'options';
            $special_types = array( 'post_type', 'taxonomy', 'custom_tab', 'multi_tab' );
            return in_array( $type, $special_types ) ? $type : 'options';
        }

        public function get_tab_info_by_options( $tab_options ) {
            $type  = $this->get_tab_type_by_options( $tab_options );
            $info  = array( 'type' => $type );
            $first = !!$tab_options && is_array( $tab_options ) ? current( $tab_options ) : array();
            if ( 'post_type' === $type ) {
                $info[ 'post_type' ] = isset( $first[ 'post_type' ] ) ? $first[ 'post_type' ] : '';
            } else if ( 'taxonomy' === $type ) {
                $info[ 'taxonomy' ] = isset( $first[ 'taxonomy' ] ) ? $first[ 'taxonomy' ] : '';
            }

            return $info;
        }

        /**
         * Fire the action to print the custom tab
         *
         * @param string $action Action to fire
         * @return void
         * @since    1.0
         * @author   Andrea Grillo <andrea.grillo@yithemes.com>
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function print_custom_tab( $action ) {
            do_action( $action );
        }

        /**
         * Add sections and fields to setting panel
         * read all options and show sections and fields
         *
         * @return void
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function add_fields() {
            $yit_options = $this->get_main_array_options();
            $option_key  = $this->get_current_option_key();

            if ( !$option_key ) {
                return;
            }
            foreach ( $yit_options[ $option_key ] as $section => $data ) {
                add_settings_section( "yit_settings_{$option_key}_{$section}", $this->get_section_title( $section ), $this->get_section_description( $section ), 'yit' );
                foreach ( $data as $option ) {
                    if ( isset( $option[ 'id' ] ) && isset( $option[ 'type' ] ) && isset( $option[ 'name' ] ) ) {
                        add_settings_field( "yit_setting_" . $option[ 'id' ], $option[ 'name' ], array( $this, 'render_field' ), 'yit', "yit_settings_{$option_key}_{$section}", array( 'option' => $option, 'label_for' => $this->get_id_field( $option[ 'id' ] ) ) );
                    }
                }
            }
        }


        /**
         * Add the tabs to admin bar menu
         * set all tabs of settings page on wp admin bar
         *
         * @return void|array return void when capability is false
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function add_admin_bar_menu() {

            global $wp_admin_bar;

            if ( !current_user_can( 'manage_options' ) ) {
                return;
            }

            if ( !empty( $this->settings[ 'admin_tabs' ] ) ) {
                foreach ( $this->settings[ 'admin-tabs' ] as $item => $title ) {

                    $wp_admin_bar->add_menu( array(
                                                 'parent' => $this->settings[ 'parent' ],
                                                 'title'  => $title,
                                                 'id'     => $this->settings[ 'parent' ] . '-' . $item,
                                                 'href'   => admin_url( 'themes.php' ) . '?page=' . $this->settings[ 'parent_page' ] . '&tab=' . $item
                                             ) );
                }
            }
        }


        /**
         * Get current tab
         * get the id of tab showed, return general is the current tab is not defined
         *
         * @return string
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_current_tab() {
            $admin_tabs = array_keys( $this->settings[ 'admin-tabs' ] );

            if ( !isset( $_GET[ 'page' ] ) || $_GET[ 'page' ] != $this->settings[ 'page' ] ) {
                return false;
            }
            if ( isset( $_REQUEST[ 'yit_tab_options' ] ) ) {
                return $_REQUEST[ 'yit_tab_options' ];
            } elseif ( isset( $_GET[ 'tab' ] ) ) {
                return $_GET[ 'tab' ];
            } elseif ( isset( $admin_tabs[ 0 ] ) ) {
                return $admin_tabs[ 0 ];
            } else {
                return 'general';
            }
        }

        /**
         * Get the current sub-tab
         *
         * @return string the key of the sub-tab if exists, empty string otherwise
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @since    3.4.0
         */
        public function get_current_sub_tab() {
            $sub_tabs = $this->get_sub_tabs();
            $sub_tab  = isset( $_REQUEST[ 'sub_tab' ] ) ? $_REQUEST[ 'sub_tab' ] : '';

            if ( $sub_tabs ) {
                if ( $sub_tab && !isset( $sub_tabs[ $sub_tab ] ) || !$sub_tab ) {
                    $sub_tab = current( array_keys( $sub_tabs ) );
                }
            } else {
                $sub_tab = '';
            }

            return $sub_tab;
        }

        /**
         * Return the option key related to the current page
         * for sub-tabbed tabs, it will return the current sub-tab
         * fot normal tabs, it will return the current tab
         *
         * @return string the current sub-tab, if exists; the current tab otherwise
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @since    3.4.0
         */
        public function get_current_option_key() {
            $current_tab     = $this->get_current_tab();
            $current_sub_tab = $this->get_current_sub_tab();

            if ( !$current_tab ) {
                return false;
            }

            return $current_sub_tab ? $current_sub_tab : $current_tab;
        }


        /**
         * Message
         * define an array of message and show the content od message if
         * is find in the query string
         *
         * @return void
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function message() {

            $message = array(
                'element_exists'   => $this->get_message( '<strong>' . __( 'The element you have entered already exists. Please, enter another name.', 'yith-plugin-fw' ) . '</strong>', 'error', false ),
                'saved'            => $this->get_message( '<strong>' . __( 'Settings saved', 'yith-plugin-fw' ) . '.</strong>', 'updated', false ),
                'reset'            => $this->get_message( '<strong>' . __( 'Settings reset', 'yith-plugin-fw' ) . '.</strong>', 'updated', false ),
                'delete'           => $this->get_message( '<strong>' . __( 'Element deleted correctly.', 'yith-plugin-fw' ) . '</strong>', 'updated', false ),
                'updated'          => $this->get_message( '<strong>' . __( 'Element updated correctly.', 'yith-plugin-fw' ) . '</strong>', 'updated', false ),
                'settings-updated' => $this->get_message( '<strong>' . __( 'Element updated correctly.', 'yith-plugin-fw' ) . '</strong>', 'updated', false ),
                'imported'         => $this->get_message( '<strong>' . __( 'Database imported correctly.', 'yith-plugin-fw' ) . '</strong>', 'updated', false ),
                'no-imported'      => $this->get_message( '<strong>' . __( 'An error has occurred during import. Please try again.', 'yith-plugin-fw' ) . '</strong>', 'error', false ),
                'file-not-valid'   => $this->get_message( '<strong>' . __( 'The added file is not valid.', 'yith-plugin-fw' ) . '</strong>', 'error', false ),
                'cant-import'      => $this->get_message( '<strong>' . __( 'Sorry, import is disabled.', 'yith-plugin-fw' ) . '</strong>', 'error', false ),
                'ord'              => $this->get_message( '<strong>' . __( 'Sorting successful.', 'yith-plugin-fw' ) . '</strong>', 'updated', false )
            );

            foreach ( $message as $key => $value ) {
                if ( isset( $_GET[ $key ] ) ) {
                    echo $message[ $key ];
                }
            }

        }

        /**
         * Get Message
         * return html code of message
         *
         * @param        $message
         * @param string $type can be 'error' or 'updated'
         * @param bool   $echo
         * @return string
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function get_message( $message, $type = 'error', $echo = true ) {
            $message = '<div id="message" class="' . $type . ' fade"><p>' . $message . '</p></div>';
            if ( $echo ) {
                echo $message;
            }

            return $message;
        }


        /**
         * Get Tab Path Files
         * return an array with file names of tabs
         *
         * @return array
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_tabs_path_files() {

            $option_files_path = $this->settings[ 'options-path' ] . '/';

            $tabs = array();

            foreach ( ( array ) glob( $option_files_path . '*.php' ) as $filename ) {
                preg_match( '/(.*)-options\.(.*)/', basename( $filename ), $filename_parts );

                if ( !isset( $filename_parts[ 1 ] ) ) {
                    continue;
                }

                $tab = $filename_parts[ 1 ];

                $tabs[ $tab ] = $filename;
            }

            return $tabs;
        }

        /**
         * Get main array options
         * return an array with all options defined on options-files
         *
         * @return array
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_main_array_options() {
            $this->_maybe_init_vars();

            return $this->_main_array_options;
        }

        function get_tabs_hierarchy() {
            $this->_maybe_init_vars();

            return $this->_tabs_hierarchy;
        }

        /**
         * Return the sub-tabs array of a specific tab
         *
         * @param array|bool $_tab the tab; if not set it'll be the current tab
         * @since    3.4.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         * @return array sub-tabs array
         */
        public function get_sub_tabs( $_tab = false ) {
            if ( false === $_tab ) {
                $_tab = $this->get_current_tab();
            }

            if ( is_string( $_tab ) ) {
                $main_array_options  = $this->get_main_array_options();
                $current_tab_options = isset( $main_array_options[ $_tab ] ) ? $main_array_options[ $_tab ] : array();
                if ( $current_tab_options ) {
                    $_tab = array( $_tab => $current_tab_options );
                }
            }

            $_tab_options = !!$_tab && is_array( $_tab ) ? current( $_tab ) : false;
            $_first       = !!$_tab_options && is_array( $_tab_options ) ? current( $_tab_options ) : false;
            if ( $_first && is_array( $_first ) && isset( $_first[ 'type' ] ) && 'multi_tab' === $_first[ 'type' ] && !empty( $_first[ 'sub-tabs' ] ) ) {
                return $_first[ 'sub-tabs' ];
            }
            return array();
        }

        public function get_first_sub_tab_key( $_tab = false ) {
            $key = false;
            if ( is_string( $_tab ) ) {
                $main_array_options  = $this->get_main_array_options();
                $current_tab_options = isset( $main_array_options[ $_tab ] ) ? $main_array_options[ $_tab ] : array();
                if ( $current_tab_options ) {
                    $_tab = array( $_tab => $current_tab_options );
                }
            }

            if ( ( $sub_tabs = $this->get_sub_tabs( $_tab ) ) ) {
                $key = current( array_keys( $sub_tabs ) );
            }

            return $key;
        }


        /**
         * Set an array with all default options
         * put default options in an array
         *
         * @return array
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function get_default_options() {
            $yit_options     = $this->get_main_array_options();
            $default_options = array();

            foreach ( $yit_options as $tab => $sections ) {
                foreach ( $sections as $section ) {
                    foreach ( $section as $id => $value ) {
                        if ( isset( $value[ 'std' ] ) && isset( $value[ 'id' ] ) ) {
                            $default_options[ $value[ 'id' ] ] = $value[ 'std' ];
                        }
                    }
                }
            }

            unset( $yit_options );

            return $default_options;
        }


        /**
         * Get the title of the tab
         * return the title of tab
         *
         * @return string
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_tab_title() {
            $yit_options = $this->get_main_array_options();
            $option_key  = $this->get_current_option_key();

            foreach ( $yit_options[ $option_key ] as $sections => $data ) {
                foreach ( $data as $option ) {
                    if ( isset( $option[ 'type' ] ) && $option[ 'type' ] == 'title' ) {
                        return $option[ 'name' ];
                    }
                }
            }
            return '';
        }

        /**
         * Get the title of the section
         * return the title of section
         *
         * @param $section
         * @return string
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_section_title( $section ) {
            $yit_options = $this->get_main_array_options();
            $option_key  = $this->get_current_option_key();

            foreach ( $yit_options[ $option_key ][ $section ] as $option ) {
                if ( isset( $option[ 'type' ] ) && $option[ 'type' ] == 'section' ) {
                    return $option[ 'name' ];
                }
            }
            return '';
        }

        /**
         * Get the description of the section
         * return the description of section if is set
         *
         * @param $section
         * @return string
         * @since    1.0
         * @author   Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_section_description( $section ) {
            $yit_options = $this->get_main_array_options();
            $option_key  = $this->get_current_option_key();

            foreach ( $yit_options[ $option_key ][ $section ] as $option ) {
                if ( isset( $option[ 'type' ] ) && $option[ 'type' ] == 'section' && isset( $option[ 'desc' ] ) ) {
                    return '<p>' . $option[ 'desc' ] . '</p>';
                }
            }
            return '';
        }


        /**
         * Show form when necessary
         * return true if 'showform' is not defined
         *
         * @return bool
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function is_show_form() {
            $yit_options = $this->get_main_array_options();
            $option_key  = $this->get_current_option_key();

            foreach ( $yit_options[ $option_key ] as $sections => $data ) {
                foreach ( $data as $option ) {
                    if ( !isset( $option[ 'type' ] ) || $option[ 'type' ] != 'title' ) {
                        continue;
                    }
                    if ( isset( $option[ 'showform' ] ) ) {
                        return $option[ 'showform' ];
                    } else {
                        return true;
                    }
                }
            }
        }

        /**
         * Get name field
         * return a string with the name of the input field
         *
         * @param string $name
         * @return string
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_name_field( $name = '' ) {
            return 'yit_' . $this->settings[ 'parent' ] . '_options[' . $name . ']';
        }

        /**
         * Get id field
         * return a string with the id of the input field
         *
         * @param string $id
         * @return string
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function get_id_field( $id ) {
            return 'yit_' . $this->settings[ 'parent' ] . '_options_' . $id;
        }


        /**
         * Render the field showed in the setting page
         * include the file of the option type, if file do not exists
         * return a text area
         *
         * @param array $param
         * @return void
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        function render_field( $param ) {

            if ( !empty( $param ) && isset( $param [ 'option' ] ) ) {
                $option     = $param [ 'option' ];
                $db_options = $this->get_options();

                $custom_attributes = array();

                if ( !empty( $option[ 'custom_attributes' ] ) && is_array( $option[ 'custom_attributes' ] ) ) {
                    foreach ( $option[ 'custom_attributes' ] as $attribute => $attribute_value ) {
                        $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
                    }
                }

                $custom_attributes = implode( ' ', $custom_attributes );
                $std               = isset( $option[ 'std' ] ) ? $option[ 'std' ] : '';
                $db_value          = ( isset( $db_options[ $option[ 'id' ] ] ) ) ? $db_options[ $option[ 'id' ] ] : $std;

                if ( isset( $option[ 'deps' ] ) )
                    $deps = $option[ 'deps' ];

                if ( 'on-off' === $option[ 'type' ] )
                    $option[ 'type' ] = 'onoff';

                if ( $field_template_path = yith_plugin_fw_get_field_template_path( $option ) ) {
                    $field_container_path = apply_filters( 'yith_plugin_fw_panel_field_container_template_path', YIT_CORE_PLUGIN_TEMPLATE_PATH . '/panel/panel-field-container.php', $option );
                    file_exists( $field_container_path ) && include( $field_container_path );
                } else {
                    do_action( "yit_panel_{$option['type']}", $option, $db_value, $custom_attributes );
                }
            }
        }

        /**
         * Get options from db
         * return the options from db, if the options aren't defined in the db,
         * get the default options ad add the options in the db
         *
         * @return array
         * @since  1.0
         * @author Emanuela Castorina <emanuela.castorina@yithemes.it>
         */
        public function get_options() {
            $options = get_option( 'yit_' . $this->settings[ 'parent' ] . '_options' );
            if ( $options === false || ( isset( $_REQUEST[ 'yit-action' ] ) && $_REQUEST[ 'yit-action' ] == 'reset' ) ) {
                $options = $this->get_default_options();
            }

            return $options;
        }

        /**
         * Show a box panel with specific content in two columns as a new woocommerce type
         *
         * @param array $args
         * @return   void
         * @since    1.0
         * @author   Emanuela Castorina      <emanuela.castorina@yithemes.com>
         */
        public static function add_infobox( $args = array() ) {
            if ( !empty( $args ) ) {
                extract( $args );
                require_once( YIT_CORE_PLUGIN_TEMPLATE_PATH . '/panel/boxinfo.php' );
            }
        }

        /**
         * Show a box panel with specific content in two columns as a new woocommerce type
         *
         * @param array $args
         * @return   void
         * @deprecated 3.0.12 Do nothing! Method left to prevent Fatal Error if called directly
         */
        public static function add_videobox( $args = array() ) {

        }

        /**
         * Fire the action to print the custom tab
         *
         * @return void
         * @deprecated 3.0.12 Do nothing! Method left to prevent Fatal Error if called directly
         */
        public function print_video_box() {

        }

        /**
         * sort plugins by name in YITH Plugins menu
         *
         * @since    3.0.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public static function sort_plugins() {
            global $submenu;
            if ( !empty( $submenu[ 'yith_plugin_panel' ] ) ) {
                $sorted_plugins = $submenu[ 'yith_plugin_panel' ];

                usort( $sorted_plugins, function ( $a, $b ) {
                    return strcmp( current( $a ), current( $b ) );
                } );

                $submenu[ 'yith_plugin_panel' ] = $sorted_plugins;
            }
        }

        /**
         * add menu class in YITH Plugins menu
         *
         * @since    3.0.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public static function add_menu_class_in_yith_plugin( $menu ) {
            global $submenu;

            if ( !empty( $submenu[ 'yith_plugin_panel' ] ) ) {
                $item_count = count( $submenu[ 'yith_plugin_panel' ] );
                $columns    = absint( $item_count / 20 ) + 1;
                $columns    = max( 1, min( $columns, 3 ) );
                $columns    = apply_filters( 'yith_plugin_fw_yith_plugins_menu_columns', $columns, $item_count );

                if ( $columns > 1 ) {
                    $class = "yith-plugin-fw-menu-$columns-columns";
                    foreach ( $menu as $order => $top ) {
                        if ( 'yith_plugin_panel' === $top[ 2 ] ) {
                            $c                   = $menu[ $order ][ 4 ];
                            $menu[ $order ][ 4 ] = add_cssclass( $class, $c );
                            break;
                        }
                    }
                }
            }

            return $menu;
        }

        /**
         * Check if inside the admin tab there's the premium tab to
         * check if the plugin is a free or not
         *
         * @author Emanuela Castorina
         */
        function is_free() {
            return ( !empty( $this->settings[ 'admin-tabs' ] ) && isset( $this->settings[ 'admin-tabs' ][ 'premium' ] ) );
        }

        /**
         * Add plugin banner
         */
        public function add_plugin_banner( $page ) {

            if ( $page != $this->settings[ 'page' ] || !isset( $this->settings[ 'class' ] ) ) {
                return;
            }

            if ( $this->is_free() && isset( $this->settings[ 'plugin_slug' ] ) ):
                $rate_link = apply_filters( 'yith_plugin_fw_rate_url', 'https://wordpress.org/support/plugin/' . $this->settings[ 'plugin_slug' ] . '/reviews/?rate=5#new-post' );
                ?>
                <h1 class="notice-container"></h1>
                <div class="yith-plugin-fw-banner">
                    <h1><?php echo esc_html( $this->settings[ 'page_title' ] ) ?></h1>
                </div>
                <div class="yith-plugin-fw-rate">
	                <?php printf('<strong>%s</strong> %s <a href="%s" target="_blank"><u>%s</u> <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></a>  %s',
                    __('We need your support','yith-plugin-fw'),
                     __('to keep updating and improving the plugin. Please,','yith-plugin-fw'),
                     $rate_link,
                     __('help us by leaving a five-star rating','yith-plugin-fw' ),
                     __(':) Thanks!','yith-plugin-fw' ) )?>
                    </div>
            <?php else: ?>
                <h1 class="notice-container"></h1>
                <div class="yith-plugin-fw-banner">
                    <h1><?php echo esc_html( $this->settings[ 'page_title' ] ) ?></h1>
                </div>
            <?php endif ?>
            <?php
        }

        /**
         * Add additional element after print the field.
         *
         * @since  3.2
         * @author Emanuela Castorina
         */
        public function add_yith_ui( $field ) {
            global $pagenow;

            $screen = function_exists('get_current_screen') ? get_current_screen() : false;

            if ( empty( $this->settings[ 'class' ] ) || !isset( $field[ 'type' ] ) ) {
                return;
            }
            if ( 'admin.php' === $pagenow && $screen && strpos( $screen->id, $this->settings[ 'page' ] ) !== false ) {
                switch ( $field[ 'type' ] ) {
                    case 'datepicker':
                        echo '<span class="yith-icon icon-calendar"></span>';
                        break;
                    default:
                        break;
                }
            }
        }


        public function get_post_type_tabs( $post_type ) {
            $tabs = array();

            foreach ( $this->get_tabs_hierarchy() as $key => $info ) {
                if ( isset( $info[ 'type' ], $info[ 'post_type' ] ) && 'post_type' === $info[ 'type' ] && $post_type === $info[ 'post_type' ] ) {
                    if ( !empty( $info[ 'parent' ] ) ) {
                        $tabs = array( 'tab' => $info[ 'parent' ], 'sub_tab' => $key );
                    } else {
                        $tabs = array( 'tab' => $key );
                    }
                    break;
                }
            }

			$panel_page = isset( $this->settings['page'] ) ? $this->settings['page'] : 'general';

			return apply_filters( "yith_plugin_fw_panel_{$panel_page}_get_post_type_tabs", $tabs, $post_type );
        }

        public function get_taxonomy_tabs( $taxonomy ) {
            $tabs = array();

            foreach ( $this->get_tabs_hierarchy() as $key => $info ) {
                if ( isset( $info[ 'type' ], $info[ 'taxonomy' ] ) && 'taxonomy' === $info[ 'type' ] && $taxonomy === $info[ 'taxonomy' ] ) {
                    if ( !empty( $info[ 'parent' ] ) ) {
                        $tabs = array( 'tab' => $info[ 'parent' ], 'sub_tab' => $key );
                    } else {
                        $tabs = array( 'tab' => $key );
                    }
                    break;
                }
            }

			$panel_page = isset( $this->settings['page'] ) ? $this->settings['page'] : 'general';

			return apply_filters( "yith_plugin_fw_panel_{$panel_page}_get_taxonomy_tabs", $tabs, $taxonomy );
        }


        /**
         * If the panel page is a WP Page, this will redirect you to the correct page
         * useful when a Post Type (Taxonomy) is the first tab of your panel, so when you open your panel it'll open the Post Type (Taxonomy) list
         *
         * @since    3.4.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function maybe_redirect_to_proper_wp_page() {
            if ( !isset( $_REQUEST[ 'yith-plugin-fw-panel-skip-redirect' ] ) ) {
                $url = $this->get_nav_url( $this->settings[ 'page' ], $this->get_current_tab(), $this->get_current_sub_tab() );
                if ( strpos( $url, 'edit.php' ) !== false || strpos( $url, 'edit-tags.php' ) !== false ) {
                    wp_safe_redirect( add_query_arg( array( 'yith-plugin-fw-panel-skip-redirect' => 1 ), $url ) );
                    exit;
                }
            }
        }

        /**
         * Print the Panel tabs and sub-tabs navigation in WP pages
         * Important: this opens a wrapper <div> that will be closed through YIT_Plugin_Panel::print_panel_tabs_in_post_edit_page_end()
         *
         * @since    3.4.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function print_panel_tabs_in_wp_pages() {
            if ( self::$_panel_tabs_in_wp_pages ) {
                wp_enqueue_style( 'yit-plugin-style' );
                $wrap_class = isset( $this->settings[ 'class' ] ) ? $this->settings[ 'class' ] : '';

                ?>
                <div class="yith-plugin-fw-wp-page-wrapper">
                <?php
                echo "<div class='{$wrap_class}'>";
                $this->add_plugin_banner( $this->settings[ 'page' ] );
                $this->print_tabs_nav( self::$_panel_tabs_in_wp_pages );
                echo "</div>";
            }
        }


        /**
         * Close the wrapper opened in YIT_Plugin_Panel::print_panel_tabs_in_wp_pages()
         *
         * @since    3.4.0
         * @author   Leanza Francesco <leanzafrancesco@gmail.com>
         */
        public function print_panel_tabs_in_wp_pages_end() {
            if ( self::$_panel_tabs_in_wp_pages ) {
                echo "</div><!-- /yith-plugin-fw-wp-page-wrapper -->";
            }
        }

        public function set_parent_file_to_handle_menu_for_wp_pages( $parent_file ) {
            if ( self::$_panel_tabs_in_wp_pages ) {
                return 'yith_plugin_panel';
            }

            return $parent_file;
        }

        public function set_submenu_file_to_handle_menu_for_wp_pages( $submenu_file, $parent_file ) {
            if ( self::$_panel_tabs_in_wp_pages ) {
                return $this->settings[ 'page' ];
            }
            return $submenu_file;
        }

        /**
         *
         */
        public function save_toggle_element_options() {
            return true;
        }
    }


}
