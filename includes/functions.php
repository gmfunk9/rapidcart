<?php
/**
 * WooCommerce One Page Checkout functions
 *
 * Functions mainly to take advantage of APIs added to newer versions of WooCommerce while maintaining backward compatibility.
 *
 * @author  Automattic
 * @version 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Set the property for a product in a version independent way.
 *
 * @since 1.4.0
 */
function rapcart_set_products_prop( $product, $prop, $value ) {
	if ( is_callable( array( $product, 'update_meta_data' ) ) ) { // WC 3.0+
		$product->update_meta_data( $prop, $value );
	} else {
		$product->{$prop} = $value;
	}
}

/**
 * Returns the ID of a parent product of a variation product or false otherwise.
 *
 * @since 1.5.5
 *
 * @param WC_Product|int $product WC_Product object or an ID
 *
 * @return int|false
 */
function rapcart_get_variation_parent_id( $product ) {
	$product = $product instanceof WC_Product ? $product : wc_get_product( $product );

	if ( ! $product->is_type( 'variation' ) ) {
		$parent = false;
	} else if ( is_callable( array( $product, 'get_parent_id' ) ) ) {
		$parent = $product->get_parent_id();
	} else if ( ! empty( $product->parent ) && $product->parent instanceof WC_Product_Variable ) {
		$parent = $product->parent->get_id();
	} else {
		$parent = wp_get_post_parent_id( $product->get_id() );
	}

	return $parent;
}

/**
 * Get the property for a product in a version independent way.
 *
 * @since 1.4.0
 */
function rapcart_get_products_prop( $product, $prop, $meta_key_prefix = '' ) {
	if ( is_callable( array( $product, 'get_meta' ) ) ) { // WC 3.0+
		$value = $product->get_meta( $meta_key_prefix . $prop );
	} else {
		$value = $product->{$prop};
	}

	return $value;
}

/**
 * Get the attributes for a product in a version independent way.
 *
 * @since 1.5.4
 */
function rapcart_get_products_attr_name( $product ) {

	if ( is_callable( array( $product, 'get_attributes' ) ) ) {
		$name = $product->get_attributes();
	}
	else {
		$name = $product->get_title();
	}

	return $name;
}
/**
 * Get the name for a product in a version independent way.
 *
 * @since 1.5.4
 */
function rapcart_get_products_name( $product ) {

	if ( is_callable( array( $product, 'get_name' ) ) ) { // WC 3.0+
		$name = $product->get_name();
	} else {
		$name = $product->get_title();
	}

	return $name;
}
/**
 * Get the type of a certain product
 *
 * @since 1.4.0
 */
function rapcart_get_product_type( $product ) {

	if ( $product->is_type( 'variable' ) ) {
		$product_type = 'variable';
	} elseif ( $product->get_type() ) {
		$product_type = $product->get_type();
	} else {
		$product_type = 'simple';
	}

	return $product_type;
}

/**
 * Get the url to remove a cart item from the cart.
 *
 * @since 1.5.4
 */
function rapcart_get_cart_remove_url( $cart_item_key ) {

	if ( is_callable( 'wc_get_cart_remove_url' ) ) {
		$url = wc_get_cart_remove_url( $cart_item_key );
	} else {
		$url = WC()->cart->get_remove_url( $cart_item_key );
	}

	return $url;
}

/**
 * Gets the cart item formatted data in a WC version compatible way.
 *
 * @since 1.5.4
 */
function rapcart_get_formatted_cart_item_data( $cart_item, $flat = false ) {

	if ( is_callable( 'wc_get_formatted_cart_item_data' ) ) {
		$item_data = wc_get_formatted_cart_item_data( $cart_item, $flat );
	} else {
		$item_data = WC()->cart->get_item_data( $cart_item );
	}

	return $item_data;
}

/**
 * Get all child products with ancestry to a given product.
 * Unlike WC's get_visible_children() or get_children(), this function traverses down grouped products to find all leaf children.
 *
 * @param  int|WC_Product $product The product or product ID.
 * @return array The products child product IDs.
 */
function rapcart_get_all_child_products( $product ) {
	$product  = is_a( $product, 'WC_Product' ) ? $product : wc_get_product( $product );
	$children = array();

	if ( ! is_a( $product, 'WC_Product' ) || ! $product->has_child() ) {
		return $children;
	}

	$visible_children = rapcart_get_visible_children( $product );

	// Variable products are only 1 layer deep, we only need to return their children.
	if ( $product->is_type( 'variable' ) ) {
		$children = $visible_children;
	} elseif ( $product->is_type( 'grouped' ) ) {
		// Find all grouped child product's children
		foreach ( $visible_children as $child_product_id ) {
			$grand_children = rapcart_get_all_child_products( $child_product_id );

			// When there aren't any grand children, this child is the leaf, so add it.
			if ( empty( $grand_children ) ) {
				$children[] = $child_product_id;
			} else {
				$children = array_merge( $children, $grand_children );
			}
		}
	}

	return $children;
}

/**
 * Determines if the current request is for the frontend.
 *
 * The logic in this function is based off WooCommerce::is_request( 'frontend' )
 *
 * @since 1.7.0
 *
 * @return bool
 */
function rapcart_is_frontend_request() {
	return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! rapcart_is_rest_api_request();
}

/**
 * Returns true if the request is a non-legacy REST API request.
 *
 * This function is a compatibility wrapper for WC()->is_rest_api_request() which was introduced in WC 3.6.
 *
 * @since 1.7.0
 *
 * @return bool
 */


function rapcart_is_rest_api_request() {
	if ( is_woocommerce_active() ) 
	{
		if ( is_callable( array( WC(), 'is_rest_api_request' ) ) ) {
			return WC()->is_rest_api_request();
		}
	}

	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return false;
	}

	$rest_prefix         = trailingslashit( rest_get_url_prefix() );
	$is_rest_api_request = ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) ); // phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	return apply_filters( 'woocommerce_is_rest_api_request', $is_rest_api_request );
}

/**
 * This function gets the visible children with pre WC 3.0 compatibility.
 * WC 3.0 provides a get_visible_children() method instead of using the $is_visible parameter on get_children()
 *
 * @param WC_Product $product The product
 *
 * @return array
 * @since 1.7.0
 */
function rapcart_get_visible_children( $product ) {
	if ( ! is_a( $product, 'WC_Product' ) ) {
		return array();
	}

	return is_callable( array( $product, 'get_visible_children', ) ) ? $product->get_visible_children() : $product->get_children( true );
}

/**
 * Determines if customer registration is enabled on checkout.
 *
 * A WC pre 3.0 compatibility wrapper for @see WC_Checkout::is_registration_enabled().
 *
 * @since 1.7.10
 * @return bool Whether registration is enabled on the checkout page.
 */
function rapcart_is_checkout_registration_enabled() {

	if ( is_callable( array( WC()->checkout(), 'is_registration_enabled' ) ) ) {
		return WC()->checkout()->is_registration_enabled();
	} else {
		// WC pre 3.0 compat.
		return 'yes' === get_option( 'woocommerce_enable_signup_and_login_from_checkout' );
	}
}


add_filter( 'rwmb_meta_boxes', 'fp_703104_register_meta_boxes' );

function fp_703104_register_meta_boxes( $meta_boxes ) {
    $prefix = 'fp-';

    $meta_boxes[] = [
        'title'      => esc_html__( 'Description Details', 'fpc-funkpd' ),
        'id'         => 'fpc-woo_description-details',
        'post_types' => 'product',
        'context'    => 'normal',
        'autosave'   => true,
        'fields'     => [
            [
                'type' => 'url',
                'name' => esc_html__( 'Url', 'fpc-funkpd' ),
                'id'   => $prefix . 'url_iumzuedb9ip',
            ],
        ],
    ];

    return $meta_boxes;
}




//add_filter( 'woocommerce_email_recipient_new_order', 'add_recipient', 10, 2 );
function add_recipient( $recipient, $order )
{
    if ( ! is_a( $order, 'WC_Order' ) ) return $recipient;

    // Additional email recipient
    $additional_email = "wordpress@funkpd.com";

    // The term name "pa_chef-one" is very strange … It should be "one" or "chef-one" (may be)
    $term_slug = "one";

    $has_term = false;

    // Iterating through each order item
    foreach ($order->get_items() as $item_id => $item_obj) {
        $variation_id = $item_obj->get_variation_id();
        $variation_obj = wc_get_product($variation_id);
        $variation_attributes = $variation_obj->get_attributes();
        foreach( $variation_attributes as $taxonomy_key => $term_value ){

            if( $taxonomy_key == "pa_chef" && $term_value == $term_slug ){
                $recipient .= ','. $additional_email;
                $has_term = true;
                break; // stop the 2nd loop
            }
        }
        if( $has_term ) break; // stop the 1st loop
    }
    return $recipient;
}





