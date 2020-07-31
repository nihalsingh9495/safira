<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
if (!class_exists('safira_Theme_Config')) {
    class safira_Theme_Config {
        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;
        public function __construct() {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }
        public function initSettings() {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            // Set the default arguments
            $this->setArguments();
            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        /**
          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field   set with compiler=>true is changed.
         * */
        function compiler_action($options, $css, $changed_values) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r($changed_values); // Values that have changed since the last save
            echo "</pre>";
        }
        /**
          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.
          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section($sections) {
            $sections[] = array(
                'title' => esc_html__('Section via hook', 'safira'),
                'desc' => esc_html__('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'safira'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );
            return $sections;
        }
        /**
          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments($args) {
            return $args;
        }
        /**
          Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = esc_html__('Testing filter hook!', 'safira');
            return $defaults;
        }
        public function setSections() {
            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            ob_start();
            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';
            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'safira'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
                <?php if ($screenshot) : ?>
                    <?php if (current_user_can('edit_theme_options')) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                                <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'safira'); ?>" />
                            </a>
                    <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'safira'); ?>" />
                <?php endif; ?>
                <h4><?php echo ''.$this->theme->display('Name'); ?></h4>
                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'safira'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'safira'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' .__('Tags', 'safira') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo ''.$this->theme->display('Description'); ?></p>
                    <?php
                        if ($this->theme->parent()) {
                            printf(' <p class="howto">' .__('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'safira') . '</p>',__('http://codex.wordpress.org/Child_Themes', 'safira'), $this->theme->parent()->display('Name'));
                    } ?>
                </div>
            </div>
            <?php
            $item_info = ob_get_contents();
            ob_end_clean();
            $sampleHTML = '';
            // General
            $this->sections[] = array(
                'title'     => esc_html__('General', 'safira'),
                'desc'      => esc_html__('General theme options', 'safira'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'        => 'background_opt',
                        'type'      => 'background',
                        'title'     => esc_html__('Body background', 'safira'),
                        'subtitle'  => esc_html__('Upload image or select color. Only work with box layout', 'safira'),
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array(
                        'id'        => 'page_content_background',
                        'type'      => 'background',
                        'title'     => esc_html__('Page content background', 'safira'),
                        'subtitle'  => esc_html__('Select background for page content.', 'safira'),
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array( 
                        'id'       => 'border_color',
                        'type'     => 'border',
                        'title'    => esc_html__('Border Option', 'safira'),
                        'subtitle' => esc_html__('Only color validation can be done on this field type', 'safira'),
                        'default'  => array('border-color' => '#e7e7e7'),
                    ), 
                    array(
                        'id'        => 'back_to_top',
                        'type'      => 'switch',
                        'title'     => esc_html__('Back To Top', 'safira'),
                        'desc'      => esc_html__('Show back to top button on all pages', 'safira'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'row_space',
                        'type'      => 'text',
                        'title'     => esc_html__('Row space', 'safira'),
                        'desc'      => esc_html__('Space between row.', 'safira'),
                        "default"   => '70px',
                        'display_value' => 'text',
                    ),
					array(
                        'id'        => 'row_container',
                        'type'      => 'text',
                        'title'     => esc_html__('Width Container', 'safira'),
                        'desc'      => esc_html__('Width of container.', 'safira'),
                        "default"   => '1140px',
                        'display_value' => 'text',
                    ),
                ),
            );
            // Colors
            $this->sections[] = array(
                'title'     => esc_html__('Colors', 'safira'),
                'desc'      => esc_html__('Color options', 'safira'),
                'icon'      => 'el-icon-tint',
                'fields'    => array(
                    array(
                        'id'          => 'primary_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Primary Color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for primary color.', 'safira'),
                        'transparent' => false,
                        'default'     => '#40a944',
                        'validate'    => 'color',
                    ),
					array(
                        'id'        => 'menu_hover_itemlevel1_color',
                        'type'      => 'color',
                        'title'     => esc_html__('Hover Color for Item Menu', 'safira'),
                        'subtitle'  => esc_html__('Pick a color for hover/active color of item level 1 (Horizontal Menu).', 'safira'),
                        'transparent' => false,
                        'default'   => '#074e0a',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'          => 'sale_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Sale Label BG Color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for bg sale label.', 'safira'),
                        'transparent' => true,
                        'default'     => '#40a944',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'saletext_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Sale Label Text Color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for sale label text.', 'safira'),
                        'transparent' => false,
                        'default'     => '#fff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'rate_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Rating Star Color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for star of rating.', 'safira'),
                        'transparent' => false,
                        'default'     => '#f9d738',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'link_color',
                        'type'        => 'link_color',
                        'title'       => esc_html__('Link Color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for link.', 'safira'),
                        'default'     => array(
                            'regular'  => '#222222',
                            'hover'    => '#40a944',
                            'active'   => '#40a944',
                            'visited'  => '#40a944',
                        )
                    ),
                    array(
                        'id'          => 'text_selected_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Text selected background', 'safira'),
                        'subtitle'    => esc_html__('Select background for selected text.', 'safira'),
                        'transparent' => false,
                        'default'     => '#91b2c3',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'text_selected_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Text selected color', 'safira'),
                        'subtitle'    => esc_html__('Select color for selected text.', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                ),
            );
            //Header
            $header_layouts = array();
            $header_mobile_layouts = array();
            $header_sticky_layouts = array();
            $header_default = '';
            $header_mobile_default = '';
            $header_sticky_default = '';
            $jscomposer_templates_args = array(
                'orderby'          => 'title',
                'order'            => 'ASC',
                'post_type'        => 'templatera',
                'post_status'      => 'publish',
                'posts_per_page'   => 30,
            );
            $jscomposer_templates = get_posts( $jscomposer_templates_args );
            if(count($jscomposer_templates) > 0) {
                foreach($jscomposer_templates as $jscomposer_template){
                    $header_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                    $header_mobile_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                    $header_sticky_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                }
                $header_default = esc_html__('Header 1', 'safira');
                $header_mobile_default = esc_html__('Header Mobile', 'safira');
                $header_sticky_default = esc_html__('Header Sticky', 'safira');
            }
            $this->sections[] = array(
                'title'     => esc_html__('Header', 'safira'),
                'desc'      => esc_html__('Header options', 'safira'),
                'icon'      => 'el-icon-tasks',
                'fields'    => array(
                    array(
                        'id'                => 'header_layout',
                        'type'              => 'select',
                        'title'             => esc_html__('Header Layout', 'safira'),
                        'customizer_only'   => false,
                        'desc'              => esc_html__('Go to WPBakery Page Builder => Templates to create/edit layout', 'safira'),
                        //Must provide key  => value pairs for select options
                        'options'           => $header_layouts,
                        'default'           => $header_default,
                    ),
                    array(
                        'id'        => 'header_mobile_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Header Mobile Layout', 'safira'),
                        'customizer_only'   => false,
                        'desc'      => esc_html__('Go to WPBakery Page Builder => Templates to create/edit layout', 'safira'),
                        //Must provide key => value pairs for select options
                        'options'   => $header_mobile_layouts,
                        'default'   => $header_mobile_default,
                    ),
                    array(
                        'id'        => 'header_bg',
                        'type'      => 'background',
                        'title'     => esc_html__('Header background', 'safira'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'safira'), 
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                    array(
                        'id'          => 'header_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Header text color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for header color.', 'safira'),
                        'transparent' => false,
                        'default'     => '#222222',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'header_link_color',
                        'type'      => 'link_color',
                        'title'     => esc_html__('Header link color', 'safira'),
                        'subtitle'  => esc_html__('Pick a color for header link color.', 'safira'),
                        'default'   => array(
                            'regular'  => '#222222',
                            'hover'    => '#40a944',
                            'active'   => '#40a944',
                            'visited'  => '#40a944',
                        )
                    ),
                    array(
                        'id'          => 'dropdown_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Dropdown menu background', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for dropdown menu background.', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                ),
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Topbar', 'safira' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'          => 'topbar_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Topbar text color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for header color.', 'safira'),
                        'transparent' => false,
                        'default'     => '#555555',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'topbar_link_color',
                        'type'      => 'link_color',
                        'title'     => esc_html__('Topbar link color', 'safira'),
                        'subtitle'  => esc_html__('Pick a color for header link color.', 'safira'),
                        'default'   => array(
                            'regular'  => '#555555',
                            'hover'    => '#40a944',
                            'active'   => '#40a944',
                            'visited'  => '#40a944',
                        )
                    ),
                )
            );
			$this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Sticky Header', 'safira' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'sticky_header',
                        'type'      => 'switch',
                        'title'     => esc_html__('Use sticky header', 'safira'),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'header_sticky_bg',
                        'type'      => 'color_rgba',
                        'title'     => esc_html__('Header sticky background', 'safira'),
                        'subtitle'  => esc_html__('Set color and alpha channel', 'safira'),
                        'default'   => array(
                            'color'     => '#40a944',
                            'alpha'     => 0.95,
                        ),
                        'options'       => array(
                            'show_input'                => true,
                            'show_initial'              => true,
                            'show_alpha'                => true,
                            'show_palette'              => true,
                            'show_palette_only'         => false,
                            'show_selection_palette'    => true,
                            'max_palette_size'          => 10,
                            'allow_empty'               => true,
                            'clickout_fires_change'     => false,
                            'choose_text'               => esc_html__('Choose', 'safira'),
                            'cancel_text'               => esc_html__( 'Cancel', 'safira' ),
                            'show_buttons'              => true,
                            'use_extended_classes'      => true,
                            'palette'                   => null,
                            'input_text'                => esc_html__('Select Color', 'safira'),
                        ),                        
                    ),
                    array(
                        'id'                => 'header_sticky_layout',
                        'type'              => 'select',
                        'title'             => esc_html__('Header Sticky Layout', 'safira'),
                        'customizer_only'   => false,
                        'desc'              => esc_html__('Go to Visual Composer => Templates to create/edit layout', 'safira'),
                        //Must provide key  => value pairs for select options
                        'options'           => $header_sticky_layouts,
                        'default'           => $header_sticky_default,
                    ),
                )
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Menu', 'safira' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'mobile_menu_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Mobile menu label', 'safira'),
                        'subtitle'  => esc_html__('The label for mobile menu (example: Menu, Go to...', 'safira'),
                        'default'   => esc_html__( 'Menu', 'safira' )
                    ), 
                    array(
                        'id'          => 'sub_menu_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Submenu background', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for sub menu bg .', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                )
            ); 
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Categories Menu', 'safira' ),
                'fields'     => array(
                    array(
                        'id'          => 'categories_menu_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Category menu background', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for category menu background.', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'          => 'categories_sub_menu_bg',
                        'type'        => 'color',
                        'title'       => esc_html__('Sub category menu background', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for category sub menu background.', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
                    array(
                        'id'        => 'categories_menu_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Category menu label', 'safira'),
                        'subtitle'  => esc_html__('The label for category menu', 'safira'),
                        'default'   => esc_html__( 'All Categories', 'safira' )
                    ),
                    array(
                        'id'            => 'categories_menu_items',
                        'type'          => 'slider',
                        'title'         => esc_html__('Number of items', 'safira'),
                        'desc'          => esc_html__('Number of menu items level 1 to show, default value: 8', 'safira'),
                        "default"       => 11,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 30,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'categories_more_label',
                        'type'      => 'text',
                        'title'     => esc_html__('More items label', 'safira'),
                        'subtitle'  => esc_html__('The label for more items button', 'safira'),
                        'default'   => esc_html__( 'More Categories', 'safira' )
                    ),
                    array(
                        'id'        => 'categories_less_label',
                        'type'      => 'text',
                        'title'     => esc_html__('Less items label', 'safira'),
                        'subtitle'  => esc_html__('The label for less items button', 'safira'),
                        'default'   => esc_html__( 'Less Categories', 'safira' )
                    ),
                )
            );
            //Footer
            $footer_layouts = array();
            $footer_default = '';
            $jscomposer_templates_args = array(
                'orderby'          => 'title',
                'order'            => 'ASC',
                'post_type'        => 'templatera',
                'post_status'      => 'publish',
                'posts_per_page'   => 30,
            );
            $jscomposer_templates = get_posts( $jscomposer_templates_args );
            if(count($jscomposer_templates) > 0) {
                foreach($jscomposer_templates as $jscomposer_template){
                    $footer_layouts[$jscomposer_template->post_title] = $jscomposer_template->post_title;
                }
                $footer_default = 'Footer 1';
            }
            $this->sections[] = array(
                'title'     => esc_html__('Footer', 'safira'),
                'desc'      => esc_html__('Footer options', 'safira'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'                => 'footer_layout',
                        'type'              => 'select',
                        'title'             => esc_html__('Footer Layout', 'safira'),
                        'customizer_only'   => false,
                        'desc'              => esc_html__('Go to Visual Composer => Templates to create/edit layout', 'safira'),
                        //Must provide key  => value pairs for select options
                        'options'           => $footer_layouts,
                        'default'           => $footer_default
                    ),
                    array(
                        'id'        => 'footer_bg',
                        'type'      => 'background',
                        'title'     => esc_html__('Footer background', 'safira'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'safira'), 
                        'default'   => array('background-color' => '#222222'),
                    ),
					array(
                        'id'        => 'footer_color',
                        'type'      => 'color_rgba',
                        'title'       => esc_html__('Footer text color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for footer color.', 'safira'),
                        'default'   => array(
                            'color'     => '#ffffff',
                            'alpha'     => 0.7,
                        ),
                        'options'       => array(
                            'show_input'                => true,
                            'show_initial'              => true,
                            'show_alpha'                => true,
                            'show_palette'              => true,
                            'show_palette_only'         => false,
                            'show_selection_palette'    => true,
                            'max_palette_size'          => 10,
                            'allow_empty'               => true,
                            'clickout_fires_change'     => false,
                            'choose_text'               => esc_html__('Choose', 'safira'),
                            'cancel_text'               => esc_html__( 'Cancel', 'safira' ),
                            'show_buttons'              => true,
                            'use_extended_classes'      => true,
                            'palette'                   => null,
                            'input_text'                => esc_html__('Select Color', 'safira'),
                        ),                        
                    ),
					array(
                        'id'          => 'footer_title_color',
                        'type'        => 'color',
                        'title'       => esc_html__('Footer title color', 'safira'),
                        'subtitle'    => esc_html__('Pick a color for footer title color.', 'safira'),
                        'transparent' => false,
                        'default'     => '#ffffff',
                        'validate'    => 'color',
                    ),
					array(
                        'id'        => 'footer_link_color',
                        'type'      => 'color_rgba',
                        'title'     => esc_html__('Footer link color', 'safira'),
                        'subtitle'  => esc_html__('Pick a color for footer link color.', 'safira'),
                        'default'   => array(
                            'color'     => '#ffffff',
                            'alpha'     => 0.7,
                        ),
                        'options'       => array(
                            'show_input'                => true,
                            'show_initial'              => true,
                            'show_alpha'                => true,
                            'show_palette'              => true,
                            'show_palette_only'         => false,
                            'show_selection_palette'    => true,
                            'max_palette_size'          => 10,
                            'allow_empty'               => true,
                            'clickout_fires_change'     => false,
                            'choose_text'               => esc_html__('Choose', 'safira'),
                            'cancel_text'               => esc_html__( 'Cancel', 'safira' ),
                            'show_buttons'              => true,
                            'use_extended_classes'      => true,
                            'palette'                   => null,
                            'input_text'                => esc_html__('Select Color', 'safira'),
                        ),                        
                    ),
                ),
            );
            $this->sections[] = array(
                'title'     => esc_html__('Social Icons', 'safira'),
                'icon'      => 'el-icon-website',
                'fields'     => array(
                    array(
                        'id'       => 'social_icons',
                        'type'     => 'sortable',
                        'title'    => esc_html__('Social Icons', 'safira'),
                        'subtitle' => esc_html__('Enter social links', 'safira'),
                        'desc'     => esc_html__('Drag/drop to re-arrange', 'safira'),
                        'mode'     => 'text',
                        'label'    => true,
                        'options'  => array(
                            'facebook'     => esc_html__( 'Facebook', 'safira' ),
                            'twitter'     => esc_html__( 'Twitter', 'safira' ),
                            'instagram'     => esc_html__( 'Instagram', 'safira' ),
                            'tumblr'     => esc_html__( 'Tumblr', 'safira' ),
                            'pinterest'     => esc_html__( 'Pinterest', 'safira' ),
                            'google-plus'     => esc_html__( 'Google+', 'safira' ),
                            'linkedin'     => esc_html__( 'LinkedIn', 'safira' ),
                            'behance'     => esc_html__( 'Behance', 'safira' ),
                            'dribbble'     => esc_html__( 'Dribbble', 'safira' ),
                            'youtube'     => esc_html__( 'Youtube', 'safira' ),
                            'vimeo'     => esc_html__( 'Vimeo', 'safira' ),
                            'rss'     => esc_html__( 'Rss', 'safira' ),
                        ),
                        'default' => array(
                            'facebook'    => '//www.facebook.com', // old https
                            'twitter'     => '//twitter.com', // old https
                            'instagram'   => '//www.instagram.com', // old https
							'linkedin'    => '//www.linkedin.com', // old https
							'rss'         => '//www.rss.com', // old https
                            'tumblr'      => '',
                            'pinterest'   => '',
                            'google-plus' => '',
                            'behance'     => '',
                            'dribbble'    => '',
                            'youtube'     => '',
                            'vimeo'       => '',
                        ),
                    ),
                )
            );
            //Fonts
            $this->sections[] = array(
                'title'     => esc_html__('Fonts', 'safira'),
                'desc'      => esc_html__('Fonts options', 'safira'),
                'icon'      => 'el-icon-font',
                'fields'    => array(
                    array(
                        'id'              => 'bodyfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Body font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => true,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'text-align'      => false,
                        'line-height'   => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Main body font.', 'safira'),
                        'default'         => array(
                            'color'         => '#666666',
                            'font-weight'   => '400',
                            'font-family'   => 'Work Sans',
							'font-backup'   => 'Arial, Helvetica, sans-serif',
                            'google'        => true,
                            'font-size'     => '14px',
                        ),
                    ),
                    array(
                        'id'              => 'headingfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Heading font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => false,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Heading font.', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '600',
                            'font-family'   => 'Work Sans',
                            'google'        => true,
                        ),
                    ),
					array(
                        'id'              => 'headingfont2',
                        'type'            => 'typography',
                        'title'           => esc_html__('Heading font 2', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => false,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Second font for heading', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '400',
                            'font-family'   => 'Rozha One',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'menufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Menu font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Menu font.', 'safira'),
                        'default'         => array(
                            'color'         => '#ffffff',
                            'font-weight'   => '600',
                            'font-family'   => 'Work Sans',
                            'font-size'     => '15px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'submenufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Sub menu font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('sub menu font.', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '400',
                            'font-family'   => 'Work Sans',
                            'font-size'     => '14px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'dropdownfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Dropdown menu font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Dropdown menu font.', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '400',
                            'font-family'   => 'Work Sans',
                            'font-size'     => '14px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'categoriesfont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Category menu font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Category menu font.', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '400',
                            'font-family'   => 'Work Sans',
                            'font-size'     => '14px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'categoriessubmenufont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Category sub menu font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Category sub menu font.', 'safira'),
                        'default'         => array(
                            'color'         => '#222222',
                            'font-weight'   => '500',
                            'font-family'   => 'Work Sans',
                            'font-size'     => '15px',
                            'google'        => true,
                        ),
                    ),
                    array(
                        'id'              => 'pricefont',
                        'type'            => 'typography',
                        'title'           => esc_html__('Price font', 'safira'),
                        'google'          => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'     => false,    // Select a backup non-google font in addition to a google font
                        'subsets'         => false, // Only appears if google is true and subsets not set to false
                        'font-size'       => true,
                        'line-height'     => false,
                        'text-align'      => false,
                        'all_styles'      => false,    // Enable all Google Font style/weight variations to be added to the page
                        'units'           => 'px', // Defaults to px
                        'subtitle'        => esc_html__('Price font.', 'safira'),
                        'default'         => array(
                            'color'         => '#40a944',
                            'font-weight'   => '600',
                            'font-family'   => 'Work Sans', 
                            'font-size'     => '17px', 
                            'google'        => true,
                        ),
                    ),
                ),
            );
            //Image slider
            $this->sections[] = array(
                'title'     => esc_html__('Image slider', 'safira'),
                'desc'      => esc_html__('Upload images and links', 'safira'),
                'icon'      => 'el-icon-website',
                'fields'    => array(
                    array(
                        'id'          => 'image_slider',
                        'type'        => 'slides',
                        'title'       => esc_html__('Images', 'safira'),
                        'desc'        => esc_html__('Upload images and enter links.', 'safira'),
                        'placeholder' => array(
                            'title'           => esc_html__('Title', 'safira'),
                            'description'     => esc_html__('Description', 'safira'),
                            'url'             => esc_html__('Link', 'safira'),
                        ),
                    ),
                ),
            );
            //Brand logos
            $this->sections[] = array(
                'title'     => esc_html__('Brand Logos', 'safira'),
                'desc'      => esc_html__('Upload brand logos and links', 'safira'),
                'icon'      => 'el-icon-briefcase',
                'fields'    => array(
                    array(
                        'id'          => 'brand_logos',
                        'type'        => 'slides',
                        'title'       => esc_html__('Logos', 'safira'),
                        'desc'        => esc_html__('Upload logo image and enter logo link.', 'safira'),
                        'placeholder' => array(
                            'title'           => esc_html__('Title', 'safira'),
                            'description'     => esc_html__('Description', 'safira'),
                            'url'             => esc_html__('Link', 'safira'),
                        ),
                    ),
                ),
            );
            //Inner brand logos
            $this->sections[] = array(
                'title'     => esc_html__('Inner Brand Logos', 'safira'),
                'subsection'=> true,
                'icon'      => 'el-icon-website',
                'fields'    => array(
                    array(
                        'id'        => 'inner_brand',
                        'type'      => 'switch',
                        'title'     => esc_html__('Brand carousel in inner pages', 'safira'),
                        'subtitle'  => esc_html__('Show brand carousel in inner pages', 'safira'),
                        'default'   => false,
                    ),
                    array(
                        'id'       => 'brandscroll',
                        'type'     => 'switch',
                        'title'    => esc_html__('Auto scroll', 'safira'),
                        'default'  => true,
                    ),
                    array(
                        'id'            => 'brandscrollnumber',
                        'type'          => 'slider',
                        'title'         => esc_html__('Scroll amount', 'safira'),
                        'desc'          => esc_html__('Number of logos to scroll one time, default value: 1', 'safira'),
                        "default"       => 1,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 12,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'brandpause',
                        'type'          => 'slider',
                        'title'         => esc_html__('Pause in (seconds)', 'safira'),
                        'desc'          => esc_html__('Pause time, default value: 3000', 'safira'),
                        "default"       => 3000,
                        "min"           => 1000,
                        "step"          => 500,
                        "max"           => 10000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'brandanimate',
                        'type'          => 'slider',
                        'title'         => esc_html__('Animate in (seconds)', 'safira'),
                        'desc'          => esc_html__('Animate time, default value: 2000', 'safira'),
                        "default"       => 2000,
                        "min"           => 300,
                        "step"          => 100,
                        "max"           => 5000,
                        'display_value' => 'text'
                    ),
                ),
            );
            // Sidebar
            $this->sections[] = array(
                'title'     => esc_html__('Sidebar', 'safira'),
                'desc'      => esc_html__('Sidebar options. Shop/Product sidebar and Blog sidebar are in Product and Blog sections', 'safira'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'       => 'sidebarse_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Inner Pages Sidebar Position', 'safira'),
                        'subtitle' => esc_html__('Sidebar Position on pages (default pages). If there is no widget in this sidebar, the layout will be nosidebar', 'safira'),
                        'options'  => array(
                            'left' => esc_html__( 'Left', 'safira' ),
                            'right'=> esc_html__( 'Right', 'safira' )
						),
                        'default'  => 'left'
                    ),
                    array(
                        'id'       =>'custom-sidebars',
                        'type'     => 'multi_text',
                        'title'    => esc_html__('Custom Sidebars', 'safira'),
                        'subtitle' => esc_html__('Add more sidebars', 'safira'),
                        'desc'     => esc_html__('Enter sidebar name (Only allow digits and letters). click Add more to add more sidebar. Edit your page to select a sidebar ', 'safira')
                    ),
                ),
            );
            // Product
            $this->sections[] = array(
                'title'     => esc_html__('Product', 'safira'),
                'desc'      => esc_html__('Use this section to select options for product', 'safira'),
                'icon'      => 'el-icon-tags',
                'fields'    => array(
                    array(
                        'id'        => 'shop_banner',
                        'type'      => 'media',
                        'title'     => esc_html__('Banner image in shop pages', 'safira'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload image here. If you do not want to show shop banner, remove the image.', 'safira'),
                    ),
                    array(
                        'id'        => 'show_category_image',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show individual category thumbnail', 'safira'),
                        'subtitle'  => esc_html__('Show individual category thumbnail in product category pages. ', 'safira'),
                        'desc'      => esc_html__('If yes, product category page will display the thumbnail as banner (setting product category thumbnail in path: admin->Products->Categories). If no, product category page will display the shop banner (image uploaded above).', 'safira'),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'shop_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Shop Layout', 'safira'),
                        'subtitle'  => esc_html__('If there is no widget in this sidebar, the layout will be nosidebar', 'safira'),
                        'options'   => array(
                            'sidebar'   => esc_html__( 'Sidebar', 'safira' ),
                            'fullwidth' => esc_html__( 'Full Width', 'safira' )
                        ),
                        'default'   => 'sidebar',
                    ),
                    array(
                        'id'       => 'sidebarshop_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Shop Sidebar Position', 'safira'),
                        'subtitle' => esc_html__('Sidebar Position on shop page.', 'safira'),
                        'options'  => array(
							'left' => esc_html__( 'Left', 'safira' ),
                            'right'=> esc_html__( 'Right', 'safira' )
						),
                        'default'  => 'left'
                    ),
                    array(
                        'id'        => 'default_view',
                        'type'      => 'select',
                        'title'     => esc_html__('Shop default view', 'safira'),
                        'default'   => 'grid-view',
                        'options'   => array(
                            'grid-view' => esc_html__( 'Grid View', 'safira' ),
                            'list-view' => esc_html__( 'List View', 'safira' ),
                        ),
                    ),
                    array(
                        'id'            => 'product_per_page',
                        'type'          => 'slider',
                        'title'         => esc_html__('Products per page', 'safira'),
                        'subtitle'      => esc_html__('Amount of products per page in category page', 'safira'),
                        "default"       => 12,
                        "min"           => 4,
                        "step"          => 1,
                        "max"           => 48,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'            => 'product_per_row',
                        'type'          => 'slider',
                        'title'         => esc_html__('Product columns', 'safira'),
                        'subtitle'      => esc_html__('Amount of product columns in category page', 'safira'),
                        'desc'          => esc_html__('Only works with: 1, 2, 3, 4, 6', 'safira'),
                        "default"       => 3,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 6,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'            => 'product_per_row_fw',
                        'type'          => 'slider',
                        'title'         => esc_html__('Product columns on full width shop', 'safira'),
                        'subtitle'      => esc_html__('Amount of product columns in full width category page', 'safira'),
                        'desc'          => esc_html__('Only works with: 1, 2, 3, 4, 6', 'safira'),
                        "default"       => 4,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 6,
                        'display_value' => 'text',
                    ),
                ),
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Product page', 'safira' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'single_product_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Single Product Layout', 'safira'),
                        'subtitle'  => esc_html__('If there is no widget in this sidebar, the layout will be nosidebar', 'safira'),
                        'default'   => 'fullwidth',
                        'options'   => array(
                            'sidebar'   => esc_html__( 'Sidebar', 'safira' ),
                            'fullwidth' => esc_html__( 'Full Width', 'safira' ),
                        ),
                    ),
                    array(
                        'id'       => 'sidebarsingleproduct_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Single Product Sidebar Position', 'safira'),
                        'subtitle' => esc_html__('Sidebar Position on single product page.', 'safira'),
                        'options'  => array(
							'left' => esc_html__( 'Left', 'safira' ),
                            'right'=> esc_html__( 'Right', 'safira' )
						),
                        'default'  => 'left'
                    ),
                    array(
                        'id'        => 'product_banner',
                        'type'      => 'media',
                        'title'     => esc_html__('Banner image for single product pages', 'safira'),
                        'compiler'  => 'true',
                        'mode'      => false,
                        'desc'      => esc_html__('Upload image here. If you do not want to show single product banner, remove the image.', 'safira'),
                    ),
                    array(
                        'id'        => 'related_product_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Related product title', 'safira'),
                        'default'   => esc_html__('Related Products', 'safira'),
                    ),
                    array(
                        'id'        => 'upsell_product_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Upsell product title', 'safira'),
                        'default'   => esc_html__('Upsell Products', 'safira'),
                    ),
                    array(
                        'id'            => 'related_amount',
                        'type'          => 'slider',
                        'title'         => esc_html__('Number of related products', 'safira'),
                        "default"       => 4,
                        "min"           => 1,
                        "step"          => 1,
                        "max"           => 16,
                        'display_value' => 'text',
                    ),
                    array(
                        'id'        => 'product_share_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Product share title', 'safira'),
                        'default'   => esc_html__('Share', 'safira'),
                    ),
                )
            );
            $this->sections[] = array(
                'icon'       => 'el-icon-website',
                'title'      => esc_html__( 'Quick View', 'safira' ),
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'        => 'detail_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View details text', 'safira'),
                        'default'   => esc_html__('Quick View', 'safira'),
                    ),
                    array(
                        'id'        => 'quickview_link_text',
                        'type'      => 'text',
                        'title'     => esc_html__('View all features text', 'safira'),
                        'desc'      => esc_html__('This is the text on quick view box', 'safira'),
                        'default'   => esc_html__('See all features', 'safira'),
                    ),
                    array(
                        'id'        => 'quickview',
                        'type'      => 'switch',
                        'title'     => esc_html__('Quick View', 'safira'),
                        'desc'      => esc_html__('Show quick view button on all pages', 'safira'),
                        'default'   => true,
                    ),
                )
            );
            // Blog options
            $this->sections[] = array(
                'title'     => esc_html__('Blog', 'safira'),
                'desc'      => esc_html__('Use this section to select options for blog', 'safira'),
                'icon'      => 'el-icon-file',
                'fields'    => array( 
                    array(
                        'id'        => 'blog_header_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Blog header text', 'safira'),
                        'default'   => esc_html__('Blog', 'safira'),
                    ), 
                    array(
                        'id'        => 'blog_layout',
                        'type'      => 'select',
                        'title'     => esc_html__('Blog Layout', 'safira'),
                        'subtitle'  => esc_html__('If there is no widget in this sidebar, the layout will be nosidebar', 'safira'),
                        'options'   => array(
                            'sidebar'       => esc_html__( 'Sidebar', 'safira' ),
                            'nosidebar'     => esc_html__( 'No Sidebar', 'safira' ),
                            'largeimage'    => esc_html__( 'Large Image', 'safira' ),
                            'grid'          => esc_html__( 'Grid', 'safira' ),
                        ),
                        'default'   => 'sidebar',
                    ),
                    array(
                        'id'       => 'sidebarblog_pos',
                        'type'     => 'radio',
                        'title'    => esc_html__('Blog Sidebar Position', 'safira'),
                        'subtitle' => esc_html__('Sidebar Position on Blog pages.', 'safira'),
                        'options'  => array(
                            'left' => esc_html__( 'Left', 'safira' ),
                            'right'=> esc_html__( 'Right', 'safira' )
						),
                        'default'  => 'right'
                    ),
                    array(
                        'id'        => 'readmore_text',
                        'type'      => 'text',
                        'title'     => esc_html__('Read more text', 'safira'),
                        'default'   => esc_html__('Read More', 'safira'),
                    ),
                    array(
                        'id'        => 'blog_share_title',
                        'type'      => 'text',
                        'title'     => esc_html__('Blog share title', 'safira'),
                        'default'   => esc_html__('Share this post', 'safira'),
                    ),
                ),
            );
            // Testimonials options
            $this->sections[] = array(
                'title'     => esc_html__('Testimonials', 'safira'),
                'desc'      => esc_html__('Use this section to select options for Testimonials', 'safira'),
                'icon'      => 'el-icon-comment',
                'fields'    => array(
                    array(
                        'id'       => 'testiscroll',
                        'type'     => 'switch',
                        'title'    => esc_html__('Auto scroll', 'safira'),
                        'default'  => false,
                    ),
                    array(
                        'id'            => 'testipause',
                        'type'          => 'slider',
                        'title'         => esc_html__('Pause in (seconds)', 'safira'),
                        'desc'          => esc_html__('Pause time, default value: 3000', 'safira'),
                        "default"       => 3000,
                        "min"           => 1000,
                        "step"          => 500,
                        "max"           => 10000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'testianimate',
                        'type'          => 'slider',
                        'title'         => esc_html__('Animate in (seconds)', 'safira'),
                        'desc'          => esc_html__('Animate time, default value: 2000', 'safira'),
                        "default"       => 2000,
                        "min"           => 300,
                        "step"          => 100,
                        "max"           => 5000,
                        'display_value' => 'text'
                    ),
                ),
            );
            // Error 404 page
            $this->sections[] = array(
                'title'     => esc_html__('Error 404 Page', 'safira'),
                'desc'      => esc_html__('Error 404 page options', 'safira'),
                'icon'      => 'el-icon-cog',
                'fields'    => array(
                    array(
                        'id'        => 'background_error',
                        'type'      => 'background',
                        'title'     => esc_html__('Error 404 background', 'safira'),
                        'subtitle'  => esc_html__('Upload image or select color.', 'safira'),
                        'default'   => array('background-color' => '#ffffff'),
                    ),
                ),
            );
            // Less Compiler
            $this->sections[] = array(
                'title'     => esc_html__('Less Compiler', 'safira'),
                'desc'      => esc_html__('Turn on this option to apply all theme options. Turn of when you have finished changing theme options and your site is ready.', 'safira'),
                'icon'      => 'el-icon-wrench',
                'fields'    => array(
                    array(
                        'id'        => 'enable_less',
                        'type'      => 'switch',
                        'title'     => esc_html__('Enable Less Compiler', 'safira'),
                        'default'   => true,
                    ),
                ),
            );
            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . esc_html__('<strong>Theme URL:</strong> ', 'safira') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . esc_html__('<strong>Author:</strong> ', 'safira') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . esc_html__('<strong>Version:</strong> ', 'safira') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . esc_html__('<strong>Tags:</strong> ', 'safira') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';
            $this->sections[] = array(
                'title'     => esc_html__('Import / Export', 'safira'),
                'desc'      => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'safira'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => esc_html__( 'Import Export', 'safira' ),
                        'subtitle'      => esc_html__( 'Save and restore your Redux options', 'safira' ),
                        'full_width'    => false,
                    ),
                ),
            );
            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => esc_html__('Theme Information', 'safira'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );
        }
        public function setHelpTabs() {
            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => esc_html__('Theme Information 1', 'safira'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'safira')
            );
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => esc_html__('Theme Information 2', 'safira'),
                'content'   => esc_html__('<p>This is the tab content, HTML is allowed.</p>', 'safira')
            );
            // Set the help sidebar
            $this->args['help_sidebar'] = esc_html__('<p>This is the sidebar content, HTML is allowed.</p>', 'safira');
        }
        /**
          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'safira_opt',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => esc_html__('Theme Options', 'safira'),
                'page_title'        => esc_html__('Theme Options', 'safira'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key'    => '', // Must be defined to add google fonts to the typography module
                'async_typography'  => true,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'           => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'        => false, // REMOVE
                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );
            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => '//github.com/ReduxFramework/ReduxFramework', // old https
                'title' => esc_html__( 'Visit us on GitHub', 'safira' ),
                'icon'  => 'el-icon-github'
            );
            $this->args['share_icons'][] = array(
                'url'   => '//www.facebook.com/pages/Redux-Framework/243141545850368', // old https
                'title' => esc_html__( 'Like us on Facebook', 'safira' ),
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => esc_html__( 'Follow us on Twitter', 'safira' ),
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => esc_html__( 'Find us on LinkedIn', 'safira' ),
                'icon'  => 'el-icon-linkedin'
            );
            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
              } else {
            }
        }
    }
    global $reduxConfig;
    $reduxConfig = new safira_Theme_Config();
}
/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;
/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = esc_html__( 'just testing', 'safira' );
        /*
          do your validation
          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */
        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;