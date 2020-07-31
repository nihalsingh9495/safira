<?php
/**
 * The sidebar for shop page
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Safira_Theme
 * @since Safira 1.0
 */
$safira_opt = get_option( 'safira_opt' );
$shopsidebar = 'left';
if(isset($safira_opt['sidebarshop_pos']) && $safira_opt['sidebarshop_pos']!=''){
	$shopsidebar = $safira_opt['sidebarshop_pos'];
}
if(isset($_GET['sidebar']) && $_GET['sidebar']!=''){
	$shopsidebar = $_GET['sidebar'];
}
$safira_shop_sidebar_extra_class = NULl;
if($shopsidebar=='left') {
	$safira_shop_sidebar_extra_class = 'order-lg-first';
}
?>
<?php if ( is_active_sidebar( 'sidebar-shop' ) ) : ?>
	<div id="secondary" class="col-12 col-lg-3 sidebar-shop <?php echo esc_attr($safira_shop_sidebar_extra_class);?>">
		<div class="secondary-inner">
			<?php dynamic_sidebar( 'sidebar-shop' ); ?>
		</div>
	</div>
<?php endif; ?>