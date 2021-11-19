<?php 



class FP_Shop{

	private static $_instance = null;

    private $html;
    private $all_ids;
    private $key_fields;
    private $address_fields;


    public function __construct() {

        add_action( 'wp_enqueue_scripts',           array( $this, 'fp_347682_enqueue_assets'),1 );
        // add_action( 'init',           array( $this, 'filter_default_address_fields'),1 );

        // add_shortcode( 'fp_checkout',               array( $this, 'fp_937952_echo_product_ids') );

        // add_action( 'wp_print_scripts',     array( $this, 'remove_password_strength_meter') );

    }
	/**
	 * Main FP_Vote Instance
	 *
	 * Ensures only one instance of FP_Vote is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see   FP_Vote()
	 * @return FP_Vote instance
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	}
	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->_token ), $this->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', $this->_token ), $this->_version );
	}


    public function fp_347682_enqueue_assets() 
    {
        if ( ! is_admin() )
        {
           // wp_enqueue_style('css', plugin_dir_url( __DIR__ )  . 'assets/css/fp-css.css');
        }

            wp_register_style('fp-checkout-frontend', plugin_dir_url( __DIR__ )  . 'assets/css/fp-checkout-frontend.css');
            // wp_register_style('fp-checkout-woo-checkout', plugin_dir_url( __DIR__ )  . 'assets/css/fp-checkout-woo-checkout.css');
            array_unshift(wp_styles()->queue, 'fp-checkout-frontend');
            // wp_enqueue_style('fp-checkout-popup', plugin_dir_url( __DIR__ )  . 'assets/css/fp-checkout-popup.css');
        
            //add_action( 'elementor/frontend/after_enqueue_styles', 'fp_013812_dequeue_assets_elementor_pro' );
        
    }
    
    


    
    // public function ele_disable_page_title( $return ) {
    //     return false;
    // }

    public function filter_default_address_fields( $address_fields ) {
        // Only on checkout page
        if( ! is_checkout() ) return $address_fields;
    
        // All field keys in this array
        $key_fields = array('country','first_name','last_name','company','address_1','address_2','city','state','postcode','phone');
    
        // Loop through each address fields (billing and shipping)
        foreach( $key_fields as $key_field )
            $address_fields[$key_field]['required'] = false;
    
        return $address_fields;
    }





    // //disable zxcvbn.min.js in wordpress
    // public function remove_password_strength_meter() {
    //     // Deregister script about password strenght meter
    //     wp_dequeue_script('zxcvbn-async');
    //     wp_deregister_script('zxcvbn-async');
    // }
    


}






