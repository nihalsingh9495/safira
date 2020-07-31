<?php
/**
 * The template for displaying Author Archive pages
 *
 * Used to display archive-type pages for posts by an author.
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
				<header class="entry-header">
					<h2 class="entry-title"><?php the_archive_title(); ?></h2>
				</header>
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
						<?php
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							 *
							 * We reset this later so we can run the loop
							 * properly with a call to rewind_posts().
							 */
							the_post();
						?>
						<?php
						// If a user has filled out their description, show a bio on their entries.
						if ( get_the_author_meta( 'description' ) ) : ?>
							<div class="archive-header">
								<h3 class="archive-title"><?php printf( esc_html__( 'Author Archives: %s', 'safira' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '">' . get_the_author() . '</a></span>' ); ?></h3>
								<div class="author-info archives">
									<div class="author-avatar">
										<?php
										/**
										 * Filter the author bio avatar size.
										 *
										 * @since Safira 1.0
										 *
										 * @param int $size The height and width of the avatar in pixels.
										 */
										$author_bio_avatar_size = apply_filters( 'safira_author_bio_avatar_size', 68 );
										echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
										?>
									</div><!-- .author-avatar -->
									<div class="author-description">
										<h2><?php printf( esc_html__( 'About %s', 'safira' ), get_the_author() ); ?></h2>
										<p><?php the_author_meta( 'description' ); ?></p>
									</div><!-- .author-description	-->
								</div><!-- .author-info -->
							</div><!-- .archive-header -->
						<?php endif; ?>
						<?php
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();
						?>
						<div class="post-container">
							<?php /* Start the Loop */ ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'content', get_post_format() ); ?>
							<?php endwhile; ?>
						</div>
						<?php Safira_Class::safira_pagination(); ?>
					<?php else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
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