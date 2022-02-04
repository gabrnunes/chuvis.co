<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_action( 'um_registration_after_auto_login', 'uv_um_registration_after_auto_login', 100, 1 );
function uv_um_registration_after_auto_login( $user_id ) {
    $user_verification_settings = get_option('user_verification_settings');
    $disable_auto_login = isset($user_verification_settings['ultimate_member']['disable_auto_login']) ? $user_verification_settings['ultimate_member']['disable_auto_login'] : 'yes';


    if($disable_auto_login == 'yes'){

        wp_logout();

    }

}


add_action( 'um_profile_before_header', 'um_profile_before_header', 10, 1 );
function um_profile_before_header( $args ) {
    $user_verification_settings = get_option('user_verification_settings');
    $message_before_header = isset($user_verification_settings['ultimate_member']['message_before_header']) ? $user_verification_settings['ultimate_member']['message_before_header'] : '';

    $profile_id = um_profile_id();
    $is_verified = user_verification_is_verified($profile_id);

    if(!$is_verified){
        echo $message_before_header;
        //wp_logout();
    }


}

add_action( 'um_add_error_on_form_submit_validation', 'my_add_error_on_form_submit_validation', 10, 3 );
function my_add_error_on_form_submit_validation( $field, $key, $args ) {
    // your code here
    $user_verification_settings = get_option('user_verification_settings');

    $enable_username_block = isset($user_verification_settings['spam_protection']['enable_username_block']) ? $user_verification_settings['spam_protection']['enable_username_block'] : 'yes';


    if($enable_username_block == 'yes' && $key == 'user_login'){

        $is_blocked = user_verification_is_username_blocked($args[$key]);
        if($is_blocked){
            UM()->form()->add_error('user_login', __('Username is blocked','user-verification') );
        }
    }


    if($enable_username_block == 'yes' && $key == 'user_email'){

        $is_blocked = user_verification_is_emaildomain_blocked($args[$key]);
        if($is_blocked){
            UM()->form()->add_error('user_email', __('This email domain is not allowed!','user-verification') );
        }
    }



}








