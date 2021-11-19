<?php 
/**
 * Plugin Name: RapidCart
 * Description: rapid cart.
 * Author:      FunkPd
 * Author URI:  https://FunkPD.com/
 * Text Domain: rc-funkpd
 * Domain Path: languages
 * Plugin URI:  https://FunkPD.com/
 * Version: 1.0.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package rc-funkpd
 * @since 1.0
 * @author FunkPd
 * @copyright Copyright (c) 2021 FunkPd
 * @link https://FunkPd.com/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

define( 'FP_SHOP_DIR', plugin_dir_path( __FILE__ ) );

include 'includes/woo-functions.php';
include 'includes/fp-rapcart.php';
// include 'includes/fp-metabox.php';
// include 'includes/fp-dynamic-shortcode-wrapper.php';
// include 'includes/rapcart-override-template.php';
include 'includes/fp-woo-modify-billing-fields.php';
// include 'includes/remove-wp-bloat.php';



require_once FP_SHOP_DIR . 'includes/class-fp-shop.php';
// require_once FP_SHOP_DIR . 'includes/fp-class__woocommerce_quick_view.php';

// function FP_Shop() {
// 	$instance = FP_Shop::instance( __FILE__, '1.0.0' );

// 	return $instance;
// }

// FP_Shop();


add_action( 'wp_enqueue_scripts', 'fp_412794_enqueue_frontend_styles',1);
function fp_412794_enqueue_frontend_styles() {

    // wp_enqueue_script( 'fp-quantity_buttons', plugins_url( 'assets/js/fp-quantity_buttons.js' , __FILE__ ), array('jquery'), '', true  );
    // wp_enqueue_script( 'fp-toggle-sidebar',   plugins_url( 'assets/js/fp-toggle-sidebar.js' , __FILE__ ), array('jquery'), '', true  );

    wp_enqueue_script( 'fp-quantity_buttons', plugins_url( 'assets/js/fp-quantity_buttons.js' , __FILE__ ), array(), false, true ); 

    wp_enqueue_style( 'fp-quick_view', plugins_url( 'assets/css/fp-quick_view.css' , __FILE__ ),1,1 ); 
    wp_enqueue_style( 'fp-checkout', plugins_url( 'assets/css/fp-checkout.css' , __FILE__ ),1,1 ); 

}







add_filter( 'woocommerce_locate_template', 'woo_adon_plugin_template', 1, 3 );
function woo_adon_plugin_template( $template, $template_name, $template_path ) {
    global $woocommerce;
    $_template = $template;

    if ( ! $template_path ) 
        $template_path = $woocommerce->template_url;

    $plugin_path  = untrailingslashit( FP_SHOP_DIR )  . '/includes/woo/templates/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
            $template_path . $template_name,
            $template_name
        )
    );

    if( ! $template && file_exists( $plugin_path . $template_name ) )
        $template = $plugin_path . $template_name;

    if ( ! $template )
        $template = $_template;
    //printing here will cause an Internal Server Error
    // print_r("<div class='pre'>i<div>" . $template . "</div></div>");
    return $template;
}
