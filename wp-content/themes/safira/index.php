<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
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
				<h2 class="entry-title"><?php if(isset($safira_opt['blog_header_text']) && ($safira_opt['blog_header_text'] !='')) { echo esc_html($safira_opt['blog_header_text']); } else { esc_html_e('Blog', 'safira');}  ?></h2>
			</header>
			<div class="breadcrumb-container">
				<?php Safira_Class::safira_breadcrumb(); ?> 
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12 <?php echo 'col-lg-'.$safira_blogcolclass; ?> <?php echo ''.$main_column_class; ?> <?php echo esc_attr($safira_blog_main_extra_class);?>">
				<div class="page-content blog-page blogs <?php echo esc_attr($safira_blogclass); ?>">
					<div class="blog-wrapper">
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
							<?php if ( current_user_can( 'edit_posts' ) ) :
								// Show a different message to a logged-in user who can add posts.
							?>
								<header class="entry-header">
									<h1 class="entry-title"><?php esc_html_e( 'No posts to display', 'safira' ); ?></h1>
								</header>
								<div class="entry-content">
									<p><?php printf( wp_kses(__( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'safira' ), array('a'=>array('href'=>array()))), admin_url( 'post-new.php' ) ); ?></p>
								</div><!-- .entry-content -->
							<?php else :
								// Show the default message to everyone else.
							?>
								<header class="entry-header">
									<h1 class="entry-title"><?php esc_html_e( 'Nothing Found', 'safira' ); ?></h1>
								</header>
								<div class="entry-content">
									<p><?php esc_html_e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'safira' ); ?></p>
									<?php get_search_form(); ?>
								</div><!-- .entry-content -->
							<?php endif; ?>
							</article><!-- #post-0 -->
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if($safira_bloglayout!='nosidebar' && is_active_sidebar('sidebar-1')): ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
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