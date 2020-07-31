<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header( 'shop' ); ?>
<div class="main-container">
	<div class="title-breadcumbs">
		<div class="container">
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
				<header class="entry-header shop-title">
					<h2 class="entry-title"><?php if(isset($safira_opt['single_product_header_text']) && ($safira_opt['single_product_header_text'] !='')) { echo esc_html($safira_opt['single_product_header_text']); } else { esc_html_e('Product Details', 'safira');}  ?></h2>
				</header>
			<?php endif; ?>
			<div class="breadcrumb-container">
				<div class="container">
					<?php
						/**
						 * woocommerce_before_main_content hook
						 *
						 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
						 * @hooked woocommerce_breadcrumb - 20
						 */
						do_action( 'woocommerce_before_main_content' );
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="product-page">
		<div class="product-view">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php wc_get_template_part( 'content', 'single-product' ); ?>
			<?php endwhile; // end of the loop. ?>
			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );
			?>
			<?php
				/**
				 * woocommerce_sidebar hook
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
			?>
		</div> 
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
<?php get_footer( 'shop' ); ?>