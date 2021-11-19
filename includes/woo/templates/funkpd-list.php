<?php
/**
 * Template to display product selection fields in a list
 *
 * @package WooCommerce-One-Page-Checkout/Templates
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



?>fp_164812_
<div id="checkout-products">
	<?php foreach( $products as $product ) : ?>
	<div class="product-item <?php if ( rapcart_get_products_prop( $product, 'in_cart' ) ) echo 'selected'; ?>" >



        <div class="content">

            <div class="title" title="<?php echo $product->get_title(); ?>">
                <h3><?php echo $product->get_title(); ?></h3>
            </div>

            <div class="description">
                <?php 

                $short_desc = strip_shortcodes($product->get_short_description());
                $short_desc = strip_tags(apply_filters( 'woocommerce_short_description', $short_desc ),"<p><a><br><b><u><i><strong><span><div>"); 
     
                echo $short_desc;
                
                ?>
            </div>

            <div class="details hide">

        		<?php //wc_get_template( 'single-product/product-metas.php', array( 'product' => $product ), '', FP_One_Page_Checkout::$template_path );?>

            </div>



            <div class="rapcart-product-quantity product-quantity">
            
                <?php wc_get_template( 'checkout/add-to-cart/quantity-input.php', array( 'product' => $product ), '', FP_One_Page_Checkout::$template_path );?>
                <?php wc_get_template( 'checkout/add-to-cart/button.php', array( 'product' => $product ), '', FP_One_Page_Checkout::$template_path );?>

            </div><!-- .rapcart-product-quantity -->

        
            <div class="product-quick_view">

                <a data-product-id="<?php echo $product->get_id(); ?>" class="quick_view button" title="quick view">
                <?php echo file_get_contents( FP_SHOP_DIR . "assets/svg/quick_view-eye.svg"); ?></a>

            </div>


        <?php //echo $product->get_short_description(); ?>
        <?php //print_r($product); ?>

    
		<?php if ( $product->is_type( 'variation' ) ) : ?>

			<?php $attribute_string = sprintf( '&nbsp;(%s)', wc_get_formatted_variation( $product->get_variation_attributes(), true ) ); ?>
			<span class="attributes"><?php echo esc_html( apply_filters( 'woocommerce_attribute', $attribute_string, $product->get_variation_attributes(), $product ) ); ?></span>
		
        <?php else : ?>

			<?php $attributes = $product->get_attributes(); ?>
			<?php foreach ( $attributes as $attribute ) : ?>

				<?php $attribute_string = sprintf( '&nbsp;(%s)', $product->get_attribute( $attribute['name'] ) ); ?>
			    <span class="attributes"><?php echo esc_html( apply_filters( 'woocommerce_attribute', $attribute_string, $attribute, $product ) ); ?></span>
			
            <?php endforeach; ?>

		<?php endif; ?>
        </div>
        <div class="stock-badge">
            <?php wc_get_template( 'checkout/add-to-cart/availability.php', array( 'product' => $product ), '', FP_One_Page_Checkout::$template_path ); ?>
        </div>
    </div>
	<?php endforeach; // end of the loop. ?>
</div>
