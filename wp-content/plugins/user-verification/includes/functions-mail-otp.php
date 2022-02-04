<?php
if ( ! defined('ABSPATH')) exit;  // if direct access



add_action('login_form', 'user_verification_login_form_otp');
function user_verification_login_form_otp(){

    $user_verification_settings = get_option('user_verification_settings');
    $enable_default_login = isset($user_verification_settings['email_otp']['enable_default_login']) ? $user_verification_settings['email_otp']['enable_default_login'] : 'no';

    if($enable_default_login != 'yes') return;

    //if($default_login_page == 'yes'):
    ?>
    <?php
    wp_enqueue_script('scripts-login');
    wp_enqueue_style('font-awesome-5');

    ?>

        <div id="send-otp"  class="">

            <span>Send OTP</span>
            <span class="icon-loading"><i class="fas fa-spin fa-spinner"></i></span>
        </div>

    <script>





    </script>
    <style>
        .user-pass-wrap, .forgetmenot, .submit{
            display:none;
        }
        #send-otp{
            text-align: center;
            padding: 10px;
            background: #2271b1;
            margin: 0 0 15px 0;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
        }

        #send-otp .icon-loading{
            display:none;
        }
        #send-otp.loading .icon-loading{
            display: inline-block;
        }

    </style>


    <?php
    //endif;
}


add_action('woocommerce_login_form', 'user_verification_wc_login_form_otp');
function user_verification_wc_login_form_otp(){

    $user_verification_settings = get_option('user_verification_settings');

    $user_verification_settings = get_option('user_verification_settings');
    $enable_wc_login = isset($user_verification_settings['email_otp']['enable_wc_login']) ? $user_verification_settings['email_otp']['enable_wc_login'] : 'no';

    if($enable_wc_login != 'yes') return;

    wp_enqueue_script('scripts-otp');
    wp_enqueue_style('font-awesome-5');

    ?>

    <div class="user-verification-otp">

        <div class="button-send-otp" id="send-otp">
            <span class="button-text"><?php echo __('Send OTP', 'user-verification'); ?></span>
            <span class="button-icon icon-loading"><i class="fas fa-spin fa-spinner"></i></span>
        </div>

        <div class="otp-messages">


        </div>

    </div>

    <style>
        .user-pass-wrap, .forgetmenot, .submit{
            display:none;
        }
        #send-otp{
            text-align: center;
            padding: 5px;
            background: #2271b1;
            margin: 10px 0 15px 0;
            color: #fff;
            border-radius: 3px;
            cursor: pointer;
        }

        #send-otp .icon-loading{
            display:none;
        }
        #send-otp.loading .icon-loading{
            display: inline-block;
        }
        .otp-messages{}
        .otp-messages .otp-message{
            background: #dddddd9e;
            margin: 5px 0;
            padding: 5px;
        }
        .otp-messages .otp-message.success{
            border-left: 4px solid #4caf50;
        }

        .otp-messages .otp-message.info{
            border-left: 4px solid #1b96ff;
        }
        .otp-messages .otp-message.error{
            border-left: 4px solid #f96c5d;
        }
        .otp-messages .otp-message.warning{
            border-left: 4px solid #fdb209;
        }


        /*WooCommerce*/

        .woocommerce-form-login p{
            display:none;
        }
        .woocommerce-form-login p:nth-child(1){
            display:block;
        }

        .password-input, label[for=password], .woocommerce-form-login__submit, .woocommerce-form-login__rememberme, .lost_password{
            display:none;
        }

    </style>

    <?php


}




function user_verification_send_otp(){

    $response = array();
    $error = new WP_Error();

    $user_login = isset($_POST['user_login']) ? sanitize_text_field($_POST['user_login']) : '';

    // Check if username is empty or null
    if(empty($user_login)):
        $error->add( 'empty_user_login', __( 'ERROR: User login should not empty.', 'user-verification' ) );
    endif;



    // Check if user name or user email is valid or not
    $user = (is_email($user_login)) ? get_user_by( 'email', $user_login ) :  get_user_by( 'login', $user_login );
    $user_id = isset($user->ID) ? $user->ID : '';

    //error_log($user_id);

    if(empty($user_id)){
        $error->add( 'user_not_found', __( 'ERROR: User not found.', 'user-verification' ) );
    }


    $user_verification_settings = get_option('user_verification_settings');
    //$default_login_page = isset($user_verification_settings['recaptcha']['default_login_page']) ? $user_verification_settings['recaptcha']['default_login_page'] : '';
    $captcha_error = isset($user_verification_settings['messages']['captcha_error']) ? $user_verification_settings['messages']['captcha_error'] : '';
    $secretkey = isset($user_verification_settings['recaptcha']['secretkey']) ? $user_verification_settings['recaptcha']['secretkey'] : '';





    if(isset($_POST['g-recaptcha-response'])):
        $captcha = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';

        $response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $secretkey ."&response=". $captcha);
        $response = json_decode($response["body"], true);

        if( $response["success"] != true){
            $error->add( 'recaptcha', $captcha_error );
        }
    endif;


    $uv_otp_count = get_transient('uv_otp_count_'.$user_id);


    if($uv_otp_count >= 4) {
        $error->add('tried_limit_reached', 'Sorry you have tried too many times.');
    }




        if ( !$error->has_errors() ) {


        $user_email = isset($user->user_email) ? $user->user_email : '';
        $phone_number = get_user_meta($user_id,'phone_number', true);



        $password = wp_generate_password(6, false, false);
        //$password = wp_rand(100000, 999999);

        //wp_set_password( $password, $user_id );
        update_user_meta($user_id, 'uv_otp', $password);


        if(!empty($uv_otp_count)){
            $uv_otp_count += 1 ;
        }else{
            $uv_otp_count = 1;
        }

        set_transient( 'uv_otp_count_'.$user_id, $uv_otp_count, 60 );


            $user_data = array();
            $user_data['user_email'] = $user_email;
            $user_data['phone_number'] = $phone_number;
            $user_data['user_id'] = $user_id;
            $user_data['otp'] = $password;


            $otp_via_mail = user_verification_send_otp_via_mail($user_data);
            //$otp_via_sms = user_verification_send_otp_via_sms($user_data);


            if($otp_via_mail){
                $response['success_message'] = '<div class="message otp-message error">OTP has been sent successfully.</div>';
            }else{
                $response['success_message'] = '<div class="message otp-message error">OTP generated, but unable to send mail.</div>';

            }


            $response['otp_via_mail'] = $otp_via_mail;
            //$response['otp_via_sms'] = $otp_via_sms;
            $response['password'] = $password;
            $response['uv_otp_count'] = $uv_otp_count;




    }
    else{

        $error_messages = $error->get_error_messages();

        ob_start();
            if(!empty($error_messages))
                foreach ($error_messages as $message){
                    ?>
                    <div class="message otp-message error"><?php echo $message; ?></div>
                    <?php
                }

        $response['error'] = ob_get_clean();


    }

    echo json_encode($response);
    die();
}


add_action('wp_ajax_user_verification_send_otp', 'user_verification_send_otp');
add_action('wp_ajax_nopriv_user_verification_send_otp', 'user_verification_send_otp');


add_filter('check_password','user_verification_check_password_otp_default_login', 99, 4);
function user_verification_check_password_otp_default_login($check, $password, $hash, $user_id) {

    $user_verification_settings = get_option('user_verification_settings');
    $enable_default_login = isset($user_verification_settings['email_otp']['enable_default_login']) ? $user_verification_settings['email_otp']['enable_default_login'] : 'no';

    if($enable_default_login != 'yes') return $check;

//    error_log($check);
//    error_log($password);
//    error_log($hash);
//    error_log($user_id);

    //$errors = [];

    return true;

}


add_filter('check_password','user_verification_check_password_otp_wc_login', 99, 4);
function user_verification_check_password_otp_wc_login($check, $password, $hash, $user_id) {

    $user_verification_settings = get_option('user_verification_settings');
    $enable_wc_login = isset($user_verification_settings['email_otp']['enable_wc_login']) ? $user_verification_settings['email_otp']['enable_wc_login'] : 'no';

    if($enable_wc_login != 'yes') return $check;

//    error_log($check);
//    error_log($password);
//    error_log($hash);
//    error_log($user_id);

    //$errors = [];

    return true;

}









add_filter('wp_authenticate_user','user_verification_auth_otp_default_login',10,2);
function user_verification_auth_otp_default_login($user, $password){


    $user_verification_settings = get_option('user_verification_settings');
    $enable_default_login = isset($user_verification_settings['email_otp']['enable_default_login']) ? $user_verification_settings['email_otp']['enable_default_login'] : 'no';

    if($enable_default_login != 'yes') return $user;

    $error = new WP_Error();


    $user_id = isset($user->ID) ? $user->ID : '';
    //$uv_otp = isset($_POST['pwd']) ? sanitize_text_field($_POST['pwd']) : '';

    //error_log($password);
    //error_log($uv_otp);

    if(empty($password)){
        $error->add('otp_empty', __('OTP should not empty. 1','user-verification'));

    }

    $saved_otp = get_user_meta($user_id,'uv_otp', true);

    if(empty($saved_otp)){
        $error->add('otp_not_found', __('OTP not found. 1','user-verification'));

    }

//    error_log($saved_otp);
//    error_log($uv_otp);
    //error_log(serialize($user));

    if($saved_otp != $password){
        $error->add('otp_not_match', __('OTP is not correct.','user-verification'));

    }


    if ( !$error->has_errors()) {
        return  $user;
    }else{
        return  $error;
    }





}


//add_filter('wp_authenticate_user','user_verification_auth_otp_wc_login',10,2);
function user_verification_auth_otp_wc_login($user, $password) {


    $user_verification_settings = get_option('user_verification_settings');
    $enable_wc_login = isset($user_verification_settings['email_otp']['enable_wc_login']) ? $user_verification_settings['email_otp']['enable_wc_login'] : 'no';

    if($enable_wc_login != 'yes') return $user;

    $error = new WP_Error();


    $user_id = isset($user->ID) ? $user->ID : '';
    $uv_otp = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';

    //error_log($password);
    //error_log($uv_otp);


    if(empty($password)){
        $error->add('otp_empty', __('OTP should not empty.','user-verification'));

    }

    $saved_otp = get_user_meta($user_id,'uv_otp', true);

    if(empty($saved_otp)){
        $error->add('otp_not_found', __('OTP not found.','user-verification'));

    }

//    error_log($saved_otp);
//    error_log($uv_otp);
    //error_log(serialize($user));

    if($saved_otp != $password){
        $error->add('otp_not_match', __('OTP is not correct.','user-verification'));

    }


    if ( !$error->has_errors() ) {
        return  $user;
    }else{
        return  $error;
    }





}



function user_verification_clear_otp_on_logout($user_id) {

    $user_verification_settings = get_option('user_verification_settings');
    $enable_default_login = isset($user_verification_settings['email_otp']['enable_default_login']) ? $user_verification_settings['email_otp']['enable_default_login'] : 'no';

    if($enable_default_login != 'yes') return;

    delete_user_meta($user_id, 'uv_otp');


    //delete_transient( 'wpdocs_transient_name' );
}
add_action( 'wp_logout', 'user_verification_clear_otp_on_logout' );


function user_verification_send_otp_via_mail($user_data){

    //return true;

    $user_email = $user_data['user_email'];
    $phone_number = $user_data['phone_number'];
    $user_id = $user_data['user_id'];
    $otp = $user_data['otp'];



    $user_verification_settings = get_option('user_verification_settings');


    $class_user_verification_emails = new class_user_verification_emails();
    $email_templates_data = $class_user_verification_emails->email_templates_data();

    $logo_id = isset($user_verification_settings['logo_id']) ? $user_verification_settings['logo_id'] : '';

    $email_templates_data = isset($user_verification_settings['email_templates_data']['send_mail_otp']) ? $user_verification_settings['email_templates_data']['send_mail_otp'] : $email_templates_data['send_mail_otp'];


    //error_log(serialize($email_templates_data));

    $email_bcc = isset($email_templates_data['email_bcc']) ? $email_templates_data['email_bcc'] : '';
    $email_from = isset($email_templates_data['email_from']) ? $email_templates_data['email_from'] : '';
    $email_from_name = isset($email_templates_data['email_from_name']) ? $email_templates_data['email_from_name'] : '';
    $reply_to = isset($email_templates_data['reply_to']) ? $email_templates_data['reply_to'] : '';
    $reply_to_name = isset($email_templates_data['reply_to_name']) ? $email_templates_data['reply_to_name'] : '';
    $email_subject = isset($email_templates_data['subject']) ? $email_templates_data['subject'] : '';
    $email_body = isset($email_templates_data['html']) ? $email_templates_data['html'] : '';

    $email_body = do_shortcode($email_body);
    $email_body = wpautop($email_body);


    $user_data 	= get_userdata( $user_id );




    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $site_logo_url = wp_get_attachment_url($logo_id);

    $vars = array(
        '{site_name}'=> esc_html($site_name),
        '{site_description}' => esc_html($site_description),
        '{site_url}' => esc_url_raw($site_url),
        '{site_logo_url}' => esc_url_raw($site_logo_url),

        '{first_name}' => esc_html($user_data->first_name),
        '{last_name}' => esc_html($user_data->last_name),
        '{user_display_name}' => esc_html($user_data->display_name),
        '{user_email}' => esc_html($user_data->user_email),
        '{user_name}' => esc_html($user_data->user_nicename),
        '{user_avatar}' => get_avatar( $user_data->user_email, 60 ),

        '{otp_code}' => $otp,

    );



    $vars = apply_filters('user_verification_mail_vars', $vars);



    $email_data['email_to'] =  $user_data->user_email;
    $email_data['email_bcc'] =  $email_bcc;
    $email_data['email_from'] = $email_from ;
    $email_data['email_from_name'] = $email_from_name;
    $email_data['reply_to'] = $reply_to;
    $email_data['reply_to_name'] = $reply_to_name;

    $email_data['subject'] = strtr($email_subject, $vars);
    $email_data['html'] = strtr($email_body, $vars);
    $email_data['attachments'] = array();




    return $class_user_verification_emails->send_email($email_data);


    //return true;

}


function user_verification_send_otp_via_sms($user_data){

    return true;
}


function your_function( $user_login, $user ) {
    $user_id = isset($user->ID) ? $user->ID : '';

    wp_set_password(  '',  $user_id );

}
//add_action('wp_login', 'your_function', 10, 2);





