<?php

add_filter( 'woocommerce_locate_template', 'fp_123293_rapcart_override_template', 10, 5 );
function fp_123293_rapcart_override_template($template, $template_name, $template_path) 
{
	if ($template_name == 'checkout/product-list.php') 
	{
		$template = plugin_dir_path( __FILE__ ) . 'woo/templates/funkpd-list.php';
	}
	if ($template_name == 'checkout/form-checkout.php') 
	{
		$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/form-checkout.php';
	}
	if ($template_name == 'checkout/add-to-cart/quantity-input.php') 
	{
		$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/add-to-cart/quantity-input.php';
	}
	if ($template_name == 'checkout/add-to-cart/button.php') 
	{
		$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/add-to-cart/button.php';
	}
	if ($template_name == 'checkout/add-to-cart/availability.php') 
	{
		$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/add-to-cart/availability.php';
	}
	// if ($template_name == 'checkout/product-simple.php') 
	// {
	// 	$template = plugin_dir_path( __FILE__ ) . 'woo/templates/product-simple.php';
	// }
    // if ($template_name == 'checkout/product-single.php') 
	// {
	// 	$template = plugin_dir_path( __FILE__ ) . 'woo/templates/product-single.php';
	// }
	// if ($template_name == 'checkout/add-to-cart/radio.php') 
	// {
	// 	$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkbox.php';
	// }
	// if ($template_name == 'checkout/review-order.php') 
	// {
	// 	$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/review-order-rapcart.php';
	// }
	// if ($template_name == 'checkout/form-billing.php') 
	// {
	// 	$template = plugin_dir_path( __FILE__ ) . 'woo/templates/checkout/form-billing.php';
	// }
    return $template;
}