<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


add_filter('pmpro_confirmation_url', 'uv_pmpro_confirmation_url', 10, 3);


function uv_pmpro_confirmation_url($rurl, $user_id, $pmpro_level){

    $url = $rurl.'&uv_action=logout';

    return $url;

}




add_filter('pmpro_confirmation_message', 'uv_pmpro_confirmation_message', 10, 2);


function uv_pmpro_confirmation_message($confirmation_message, $pmpro_invoice){

    $uv_action = isset($_GET['uv_action']) ? sanitize_text_field($_GET['uv_action']) : '';
    if($uv_action == 'logout'):

        $user_verification_settings = get_option('user_verification_settings');
        $verification_page_id = isset($user_verification_settings['email_verification']['verification_page_id']) ? $user_verification_settings['email_verification']['verification_page_id'] : '';
        $message_checkout_page = isset($user_verification_settings['paid_memberships_pro']['message_checkout_page']) ? $user_verification_settings['paid_memberships_pro']['message_checkout_page'] : '';



        global $current_user;
        //$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $verification_page_url = get_permalink($verification_page_id);
        $verification_page_url = !empty($verification_page_url) ? $verification_page_url : get_bloginfo('url');

        $resend_link = $verification_page_url.'?uv_action=resend&id='. $user_id;


        $confirmation_message .= '<div class="user-verification-message" style="color: #f00">'.$message_checkout_page.'</div>';



    endif;

    return $confirmation_message;
}



add_action('wp_footer','uv_pm_pro_logout_not_verified');

function uv_pm_pro_logout_not_verified(){

    $active_plugins = get_option('active_plugins');
    if(in_array( 'paid-memberships-pro/paid-memberships-pro.php', (array) $active_plugins )){

        global $current_user;
        //$current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $status = user_verification_is_verified($user_id);

        $uv_action = isset($_GET['uv_action']) ? sanitize_text_field($_GET['uv_action']) : '';

        if ( !$status && $uv_action == 'logout'){
            wp_logout();

            $user_verification_settings = get_option('user_verification_settings');
            $redirect_timout = isset($user_verification_settings['paid_memberships_pro']['redirect_timout']) ? $user_verification_settings['paid_memberships_pro']['redirect_timout'] : '';
            $redirect_after_checkout = isset($user_verification_settings['paid_memberships_pro']['redirect_after_checkout']) ? $user_verification_settings['paid_memberships_pro']['redirect_after_checkout'] : '';



            $page_url = get_permalink($redirect_after_checkout);

            if(empty($page_url)):
                $page_url = wp_logout_url();
            endif;


            $resend_link = $page_url.'?user_id='. $user_id;

            ?>
            <script>
                jQuery(document).ready(function($){window.setTimeout(function() {window.location.href = "<?php echo $resend_link; ?>";}, <?php echo $redirect_timout; ?>);})
            </script>
            <?php
        }

    }

}










add_filter("pmpro_registration_checks", "my_pmpro_registration_protect_username");

function my_pmpro_registration_protect_username(){
    global $pmpro_msg, $pmpro_msgt, $current_user;

    $username = isset($_REQUEST['username']) ? sanitize_user($_REQUEST['username']) : '';
    $is_blocked = user_verification_is_username_blocked($username);

    if($is_blocked) {
        $pmpro_msg = __( "<strong>{$username}</strong> username is not allowed!", 'user-verification' );
        $pmpro_msgt = "pmpro_error";
        return false;
    }
    else{
        //all good
        return true;
    }
}





add_filter("pmpro_registration_checks", "my_pmpro_registration_protect_blocked_domain");

function my_pmpro_registration_protect_blocked_domain(){
    global $pmpro_msg, $pmpro_msgt, $current_user;

    $user_id = $current_user->ID;

    $bemail = isset($_REQUEST['bemail']) ? sanitize_email($_REQUEST['bemail']) : '';

    $is_blocked = user_verification_is_emaildomain_blocked($bemail);

    if($is_blocked) {
        $pmpro_msg = __( "This email domain is not allowed!", 'user-verification' );
        $pmpro_msgt = "pmpro_error";
        return false;
    }
    else{


        //all good
        return true;



    }
}




add_filter("pmpro_registration_checks", "my_pmpro_registration_success_send_activation_mail");

function my_pmpro_registration_success_send_activation_mail(){
    global $pmpro_msg, $pmpro_msgt, $current_user;


    $user_id = $current_user->ID;


    if($pmpro_msgt) {

        $user_verification_settings = get_option('user_verification_settings');




        $class_user_verification_emails = new class_user_verification_emails();
        $email_templates_data = $class_user_verification_emails->email_templates_data();

        $logo_id = isset($user_verification_settings['logo_id']) ? $user_verification_settings['logo_id'] : '';

        $verification_page_id = isset($user_verification_settings['email_verification']['verification_page_id']) ? $user_verification_settings['email_verification']['verification_page_id'] : '';
        $exclude_user_roles = isset($user_verification_settings['email_verification']['exclude_user_roles']) ? $user_verification_settings['email_verification']['exclude_user_roles'] : array();
        $email_templates_data = isset($user_verification_settings['email_templates_data']['user_registered']) ? $user_verification_settings['email_templates_data']['user_registered'] : $email_templates_data['user_registered'];

        $enable = isset($email_templates_data['enable']) ? $email_templates_data['enable'] : 'yes';


        $email_bcc = isset($email_templates_data['email_bcc']) ? $email_templates_data['email_bcc'] : '';
        $email_from = isset($email_templates_data['email_from']) ? $email_templates_data['email_from'] : '';
        $email_from_name = isset($email_templates_data['email_from_name']) ? $email_templates_data['email_from_name'] : '';
        $reply_to = isset($email_templates_data['reply_to']) ? $email_templates_data['reply_to'] : '';
        $reply_to_name = isset($email_templates_data['reply_to_name']) ? $email_templates_data['reply_to_name'] : '';
        $email_subject = isset($email_templates_data['subject']) ? $email_templates_data['subject'] : '';
        $email_body = isset($email_templates_data['html']) ? $email_templates_data['html'] : '';

        $email_body = do_shortcode($email_body);
        $email_body = wpautop($email_body);

        $verification_page_url = get_permalink($verification_page_id);
        $verification_page_url = !empty($verification_page_url) ? $verification_page_url : get_bloginfo('url');

        $permalink_structure = get_option('permalink_structure');

        $user_activation_key =  md5(uniqid('', true) );

        update_user_meta( $user_id, 'user_activation_key', $user_activation_key );
        update_user_meta( $user_id, 'user_activation_status', 0 );

        $user_data 	= get_userdata( $user_id );




        $user_roles = !empty($user_data->roles) ? $user_data->roles : array();


        if(!empty($exclude_user_roles))
            foreach ($exclude_user_roles as $role):

                if(in_array($role, $user_roles)){
                    //update_option('uv_custom_option', $role);
                    update_user_meta( $user_id, 'user_activation_status', 1 );
                    return;
                }

            endforeach;



        $verification_url = add_query_arg(
            array(
                'activation_key' => $user_activation_key,
                'user_verification_action' => 'email_verification',
            ),
            $verification_page_url
        );

        $verification_url = wp_nonce_url( $verification_url,  'email_verification' );



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

            '{ac_activaton_url}' => esc_url_raw($verification_url),

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


        if($enable == 'yes'){
            $mail_status = $class_user_verification_emails->send_email($email_data);

        }


        //all good
        return false;


    }
    else{




        return true;




    }
}















//update the user after checkout
function my_update_first_and_last_name_after_checkout($user_id){

    update_user_meta($user_id, "user_activation_status", 0);
}
add_action('pmpro_after_checkout', 'my_update_first_and_last_name_after_checkout');






