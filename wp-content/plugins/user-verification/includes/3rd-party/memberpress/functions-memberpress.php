<?php


if ( ! defined('ABSPATH')) exit;  // if direct access 




function mepr_validate_signup_uv( $errors ) {
    $user_email = isset( $_POST['user_email'] ) ? sanitize_email($_POST['user_email']) : false;
    $user_login = isset( $_POST['user_login'] ) ? sanitize_text_field($_POST['user_login']) : false;

    $is_blocked = user_verification_is_emaildomain_blocked($user_email);


    $email_parts = explode('@', $user_email);
    $email_domain = isset($email_parts[1]) ? $email_parts[1] : '';

//    error_log('$is_blocked:'. $is_blocked);
//    error_log('$is_blocked:'. $email_domain);


    if($is_blocked){
        $errors[] = sprintf(__( 'This <strong>%s</strong> domain is blocked!', 'user-verification' ), esc_url_raw($email_domain));
    }



    $is_allowed = user_verification_is_emaildomain_allowed($user_email);



    if(!$is_allowed){
        $errors[] = sprintf(__( 'This <strong>%s</strong> domain is not allowed!', 'user-verification' ), esc_url_raw($email_domain));

    }

    $username_blocked = user_verification_is_username_blocked($user_login);

    //var_dump($user_login);

    if($username_blocked){
        $errors[] = sprintf(__( 'This <strong>%s</strong> username is not allowed!', 'user-verification' ), esc_html($user_login));

    }









    return $errors;
}

add_filter( 'mepr-validate-signup', 'mepr_validate_signup_uv' );




