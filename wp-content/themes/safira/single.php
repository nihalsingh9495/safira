<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Safira_Theme
 * @since Safira 1.0
 */
$safira_opt = get_option( 'safira_opt' );
get_header();
$safira_bloglayout = 'sidebar';
if(isset($safira_opt['blog_layout']) && $safira_opt['blog_layout']!=''){
	$safira_bloglayout = $safira_opt['blog_layout'];
}
if(isset($_GET['layout']) && $_GET['layout']!=''){
	$safira_bloglayout = $_GET['layout'];
}
$safira_blogsidebar = 'right';
if(isset($safira_opt['sidebarblog_pos']) && $safira_opt['sidebarblog_pos']!=''){
	$safira_blogsidebar = $safira_opt['sidebarblog_pos'];
}
if(isset($_GET['sidebar']) && $_GET['sidebar']!=''){
	$safira_blogsidebar = $_GET['sidebar'];
}
if ( !is_active_sidebar( 'sidebar-1' ) )  {
	$safira_bloglayout = 'nosidebar';
}
$main_column_class = NULL;
switch($safira_bloglayout) {
	case 'sidebar':
		$safira_blogclass = 'blog-sidebar';
		$safira_blogcolclass = 9;
		$main_column_class = 'main-column';
		break;
	default:
		$safira_blogclass = 'blog-nosidebar'; //for both fullwidth and no sidebar
		$safira_blogcolclass = 12;
		$safira_blogsidebar = 'none';
}
?>
<div class="main-container">
	<div class="title-breadcumbs">
		<div class="container">
			<header class="entry-header">
				<h2 class="entry-title"><?php if(isset($safira_opt['blog_header_text']) && ($safira_opt['blog_header_text'] !='')) { echo esc_html($safira_opt['blog_header_text']); } else { esc_html_e('Blog', 'safira');}  ?></h2>
			</header>
			<div class="breadcrumb-container">
				<?php Safira_Class::safira_breadcrumb(); ?> 
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<?php
			$customsidebar = get_post_meta( $post->ID, '_safira_custom_sidebar', true );
			$customsidebar_pos = get_post_meta( $post->ID, '_safira_custom_sidebar_pos', true );
			if($customsidebar != ''){
				if($customsidebar_pos == 'left' && is_active_sidebar('sidebar-1') ) {
					echo '<div id="secondary" class="col-12 col-lg-3">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				} 
			} else {
				if($safira_blogsidebar=='left' && is_active_sidebar( 'sidebar-single_product' )) {
					get_sidebar();
				}
			} ?>
			<div class="col-12 <?php echo 'col-lg-'.$safira_blogcolclass; ?> <?php echo ''.$main_column_class; ?>">
				<div class="page-content blog-page single <?php echo esc_attr($safira_blogclass); if($safira_blogsidebar=='left') {echo ' left-sidebar'; } if($safira_blogsidebar=='right') {echo ' right-sidebar'; } ?>">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
						<?php comments_template( '', true ); ?>
					<?php endwhile; // end of the loop. ?>
				</div>
			</div>
			<?php
			if($customsidebar != ''){
				if($customsidebar_pos == 'right' && is_active_sidebar( $customsidebar ) ) {
					echo '<div id="secondary" class="col-12 col-lg-3">';
						dynamic_sidebar( $customsidebar );
					echo '</div>';
				} 
			} else {
				if($safira_blogsidebar=='right' && is_active_sidebar('sidebar-1')) {
					get_sidebar();
				}
			} ?>
		</div>
	</div> 
	<!-- brand logo -->
	<?php 
		if(isset($safira_opt['inner_brand']) && function_exists('safira_brands_shortcode') && shortcode_exists( 'ourbrands' ) ){
			if($safira_opt['inner_brand'] && isset($safira_opt['brand_logos'][0]) && $safira_opt['brand_logos'][0]['thumb']!=null) { ?>
				<div class="inner-brands">
					<div class="container">
						<?php echo do_shortcode('[ourbrands]'); ?>
					</div>
				</div>
			<?php }
		}
	?>
	<!-- end brand logo --> 
</div>
<?php get_footer(); ?>