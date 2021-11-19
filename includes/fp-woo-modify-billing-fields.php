<?php


add_filter( 'woocommerce_checkout_fields' , 'fp_795596_custom_override_checkout_fields' );
add_filter('woocommerce_checkout_fields','fp_196047_custom_wc_checkout_fields_no_label');
// add_filter( 'woocommerce_cart_item_thumbnail', '__return_false' );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
//add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_checkout_payment', 10 );
add_filter('woocommerce_enable_order_notes_field', '__return_false');


function fp_795596_custom_override_checkout_fields( $fields ) {
	unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_phone']);
    //unset($fields['order']['order_comments']);

	$fields['billing']['billing_first_name']['placeholder'] = 'First name';
	$fields['billing']['billing_last_name']['placeholder'] = 'Last name';
	$fields['billing']['billing_email']['placeholder'] = 'Email';
	
	return $fields;
}
// Action: remove label from $fields
function fp_196047_custom_wc_checkout_fields_no_label($fields) {
	// loop by category
	foreach ($fields as $category => $value) {
		// loop by fields
		foreach ($fields[$category] as $field => $property) {
			// remove label property
			unset($fields[$category][$field]['label']);
		}
	}
	return $fields;
}
function md_custom_woocommerce_checkout_fields( $fields ) 
{
    $fields['order']['order_comments']['placeholder'] = 'Additional Information';
    $fields['order']['order_comments']['label'] = ' ';

    return $fields;
}
//add_filter( 'woocommerce_checkout_fields', 'md_custom_woocommerce_checkout_fields' );
