<?php
/**
 * The template for displaying Search Results pages
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
$safira_blog_main_extra_class = NULl;
if($safira_blogsidebar=='left') {
	$safira_blog_main_extra_class = 'order-lg-last';
}
$main_column_class = NULL;
switch($safira_bloglayout) {
	case 'sidebar':
		$safira_blogclass = 'blog-sidebar';
		$safira_blogcolclass = 9;
		$main_column_class = 'main-column';
		Safira_Class::safira_post_thumbnail_size('safira-post-thumb');
		break;
	case 'largeimage':
		$safira_blogclass = 'blog-large';
		$safira_blogcolclass = 9;
		$main_column_class = 'main-column';
		Safira_Class::safira_post_thumbnail_size('safira-post-thumbwide');
		break;
	case 'grid':
		$safira_blogclass = 'grid';
		$safira_blogcolclass = 9;
		$main_column_class = 'main-column';
		Safira_Class::safira_post_thumbnail_size('safira-post-thumbwide');
		break;
	default:
		$safira_blogclass = 'blog-nosidebar';
		$safira_blogcolclass = 12;
		$safira_blogsidebar = 'none';
		Safira_Class::safira_post_thumbnail_size('safira-post-thumb');
}
?>
<div class="main-container">
	<div class="title-breadcumbs">
		<div class="container">
			<header class="entry-header">
				<h2 class="entry-title"><?php printf( wp_kses(__( 'Search Results for: %s', 'safira' ), array('span'=>array())), '<span>' . get_search_query() . '</span>' ); ?></h2>
			</header>
			<div class="breadcrumb-container">
				<?php Safira_Class::safira_breadcrumb(); ?> 
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12 <?php echo 'col-lg-'.$safira_blogcolclass; ?> <?php echo ''.$main_column_class; ?> <?php echo esc_attr($safira_blog_main_extra_class);?>">
				<div class="page-content blog-page blogs <?php echo esc_attr($safira_blogclass); if($safira_blogsidebar=='left') {echo ' left-sidebar'; } if($safira_blogsidebar=='right') {echo ' right-sidebar'; } ?>">
					<?php if ( have_posts() ) : ?>
						<div class="post-container">
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'content', get_post_format() ); ?>
							<?php endwhile; ?>
						</div>
						<?php Safira_Class::safira_pagination(); ?>
					<?php else : ?>
						<article id="post-0" class="post no-results not-found">
							<header class="entry-header">
								<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'safira' ); ?></h1>
							</header>
							<div class="entry-content">
								<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'safira' ); ?></p>
								<?php get_search_form(); ?>
							</div><!-- .entry-content -->
						</article><!-- #post-0 -->
					<?php endif; ?>
				</div>
			</div>
			<?php get_sidebar(); ?>
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