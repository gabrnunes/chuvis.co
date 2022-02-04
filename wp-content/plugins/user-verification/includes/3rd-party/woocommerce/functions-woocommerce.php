<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


add_action('woocommerce_checkout_process', 'uv_woocommerce_on_checkout_protect_username');

function uv_woocommerce_on_checkout_protect_username(){



    $billing_email = isset( $_POST['billing_email'] ) ? sanitize_email($_POST['billing_email']) : "";
    $username = isset( $_POST['account_username'] ) ? sanitize_text_field($_POST['account_username']) : "";
    if( empty( $billing_email ) ) return;

    if( 'yes' === get_option( 'woocommerce_registration_generate_username' ) ){
        $username_arr = explode( "@", $billing_email );
        $username = isset( $username_arr[0] ) ? $username_arr[0] : "";
    }


    $is_blocked = user_verification_is_username_blocked($username);
    if($is_blocked){
        wc_add_notice( __( "<strong>{$username}</strong> username is not allowed!", 'user-verification' ), 'error' );
    }


}






add_action('woocommerce_checkout_process', 'uv_woocommerce_on_checkout_protect_blocked_domain');

function uv_woocommerce_on_checkout_protect_blocked_domain(){



    $billing_email = isset( $_POST['billing_email'] ) ? sanitize_email($_POST['billing_email']) : "";
    $username = isset( $_POST['account_username'] ) ? sanitize_text_field($_POST['account_username']) : "";
    if( empty( $billing_email ) ) return;

    $is_blocked = user_verification_is_emaildomain_blocked($billing_email);
    if($is_blocked){
        wc_add_notice( __( "This email domain is not allowed!", 'user-verification' ), 'error' );

    }


}







add_filter( 'woocommerce_process_registration_errors','uv_woocommerce_registration_protect_username', 10, 4 );

function uv_woocommerce_registration_protect_username( $validation_error, $username, $password, $email ){


    if( 'yes' === get_option( 'woocommerce_registration_generate_username' ) ){
        $username_arr = explode( "@", $email );
        $username = isset( $username_arr[0] ) ? $username_arr[0] : "";
    }

    $is_blocked = user_verification_is_username_blocked($username);
    if($is_blocked){

        $validation_error->add( 'blocked_username', sprintf(__( "<strong>%s</strong> username is not allowed!", 'user-verification' ),$username));

    }

    return $validation_error;
}




add_filter( 'woocommerce_process_registration_errors','uv_woocommerce_registration_protect_blocked_domain', 10, 4 );

function uv_woocommerce_registration_protect_blocked_domain( $validation_error, $username, $password, $email ){


    $is_blocked = user_verification_is_emaildomain_blocked($email);
    if($is_blocked){
        $validation_error->add( 'blocked_username',__( "This email domain is not allowed!", 'user-verification' ) );
    }


    return $validation_error;
}









//add_action( 'woocommerce_checkout_order_processed', 'user_verification_woocommerce_checkout_order_processed', 10, 3 );

function user_verification_woocommerce_checkout_order_processed( $order_id, $posted_data, $order ){

    $user_verification_settings = get_option('user_verification_settings');
    $disable_auto_login = isset($user_verification_settings['woocommerce']['disable_auto_login']) ? $user_verification_settings['woocommerce']['disable_auto_login'] : 'yes';


	if ( is_user_logged_in() && $disable_auto_login=='yes' ) {
		//wp_logout();
	}

}


add_action( 'woocommerce_thankyou', 'user_verification_woocommerce_thankyou');

function user_verification_woocommerce_thankyou( $order_id ){

    $user_verification_settings = get_option('user_verification_settings');
    $disable_auto_login = isset($user_verification_settings['woocommerce']['disable_auto_login']) ? $user_verification_settings['woocommerce']['disable_auto_login'] : 'yes';
    $redirect_after_payment = isset($user_verification_settings['woocommerce']['redirect_after_payment']) ? $user_verification_settings['woocommerce']['redirect_after_payment'] : wc_get_page_id('myaccount');


    $order = new WC_Order( $order_id );

//    if($disable_auto_login == 'yes'){
//        return;
//    }

    if($redirect_after_payment == 'none'){
        return;
    }


    $url = get_permalink($redirect_after_payment).'?uv_check=true';

    if ( $order->status != 'failed' ) {
        wp_redirect($url);
        exit;
    }
}






add_filter( 'woocommerce_registration_redirect', 'user_verification_woocommerce_registration_redirect', 10, 1 );

function user_verification_woocommerce_registration_redirect(){

    $user_verification_settings = get_option('user_verification_settings');
    $disable_auto_login = isset($user_verification_settings['woocommerce']['disable_auto_login']) ? $user_verification_settings['woocommerce']['disable_auto_login'] : 'yes';



	if ( is_user_logged_in() && $disable_auto_login=='yes' ) {

	    global $current_user;
		//$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$approved_status = get_user_meta($user_id, 'user_activation_status', true);
		//if the user hasn't been approved destroy the cookie to kill the session and log them out
		if ( $approved_status == 1 ){

			return get_permalink(wc_get_page_id('myaccount'));
		}
		else{
			wp_logout();
			return get_permalink(wc_get_page_id('myaccount')) . "?approved=false";
		}
	}else{
        return get_permalink(wc_get_page_id('myaccount'));
    }




}

function user_verification_wc_registration_message(){

    $user_verification_settings = get_option('user_verification_settings');
    $message_after_registration = isset($user_verification_settings['woocommerce']['message_after_registration']) ? $user_verification_settings['woocommerce']['message_after_registration'] : __('Registration success, please check mail for details.', 'user-verification');



	$not_approved_message = '<p class="registration">'.__('Send in your registration application today!<br /> NOTE: Your account will be held for moderation and you will be unable to login until it is approved.','user-verification').'</p>';
	if( isset($_REQUEST['approved']) ){

		$approved = sanitize_text_field($_REQUEST['approved']);
		if ($approved == 'false')  echo '<p class="registration successful">'.$message_after_registration.'</p>';
		//else echo $not_approved_message;
	}
	//else echo $not_approved_message;

}
add_action('woocommerce_before_customer_login_form', 'user_verification_wc_registration_message', 2);