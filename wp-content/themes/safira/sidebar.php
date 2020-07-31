<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Safira_Theme
 * @since Safira 1.0
 */
$safira_opt = get_option( 'safira_opt' );
$safira_blogsidebar = 'right';
if(isset($safira_opt['sidebarblog_pos']) && $safira_opt['sidebarblog_pos']!=''){
	$safira_blogsidebar = $safira_opt['sidebarblog_pos'];
}
if(isset($_GET['sidebar']) && $_GET['sidebar']!=''){
	$safira_blogsidebar = $_GET['sidebar'];
}
$safira_blog_sidebar_extra_class = NULl;
if($safira_blogsidebar=='left') {
	$safira_blog_sidebar_extra_class = 'order-lg-first';
}
?>
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="secondary" class="col-12 col-lg-3 <?php echo esc_attr($safira_blog_sidebar_extra_class);?>">
		<div class="secondary-inner">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div>
	</div><!-- #secondary -->
<?php endif; ?>