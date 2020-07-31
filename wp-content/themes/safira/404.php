<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Safira_Theme
 * @since Safira 1.0
 */
$safira_opt = get_option( 'safira_opt' );
get_header();
?>
<div class="main-container error404">
	<div class="container">
		<div class="search-form-wrapper">
			<h2><?php esc_html_e( "OOPS! PAGE NOT BE FOUND", 'safira' ); ?></h2>
			<p class="home-link"><?php esc_html_e( "Sorry but the page you are looking for does not exist, has been removed, changed or is temporarity unavailable.", 'safira' ); ?></p>
			<?php get_search_form(); ?>
			<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr__( 'Back to home', 'safira' ); ?>"><?php esc_html_e( 'Back to home page', 'safira' ); ?></a>
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