<?php
if ( ! defined('ABSPATH')) exit; // if direct access 

class class_user_verification_notices{

    public function __construct(){

        add_action('admin_notices', array( $this, 'mark_as_verified' ));
        add_action('admin_notices', array( $this, 'mark_as_unverified' ));
        add_action('admin_notices', array( $this, 'resend_verification' ));

    }


    public function mark_as_verified(){

        $output = array();

        wp_parse_str($_SERVER['QUERY_STRING'], $output);
        $output = user_verification_recursive_sanitize_arr($output);

        $mark_as_verified = isset($output['mark_as_verified']) ? sanitize_text_field($output['mark_as_verified']) : '';

        if(empty($mark_as_verified)) return;
        if($mark_as_verified != 'yes') return;

        if(!current_user_can( 'manage_options' )) return;

        $user_id = isset($output['user_id']) ? sanitize_text_field($output['user_id']) : '';
        $_wpnonce = isset($output['_wpnonce']) ? sanitize_text_field($output['_wpnonce']) : '';


        if ( wp_verify_nonce( $_wpnonce, 'mark_as_verified' )  ){

            $user_data = get_user_by('id', $user_id);
            update_user_meta( $user_id, 'user_activation_status', 1 );

            $display_name = $user_data->display_name;

            ob_start();

            ?>
            <div class="updated notice is-dismissible">
                <p>
                    <?php
                    echo sprintf(__('<strong>%s</strong> marked as verified', 'user-verification'), esc_html($display_name))
                    ?>
                </p>

            </div>
            <?php

            echo ob_get_clean();

        }

    }



    public function mark_as_unverified(){

        $output = array();


        wp_parse_str($_SERVER['QUERY_STRING'], $output);
        $output = user_verification_recursive_sanitize_arr($output);

        $mark_as_unverified = isset($output['mark_as_unverified']) ? sanitize_text_field($output['mark_as_unverified']) : '';

        if(empty($mark_as_unverified)) return;
        if($mark_as_unverified != 'yes') return;

        if(!current_user_can( 'manage_options' )) return;

        $user_id = isset($output['user_id']) ? sanitize_text_field($output['user_id']) : '';
        $_wpnonce = isset($output['_wpnonce']) ? sanitize_text_field($output['_wpnonce']) : '';


        if ( wp_verify_nonce( $_wpnonce, 'mark_as_unverified' )  ){

            $user_data = get_user_by('id', $user_id);
            update_user_meta( $user_id, 'user_activation_status', 0 );

            $display_name = $user_data->display_name;

            ob_start();

            ?>
            <div class="updated notice is-dismissible">
                <p>
                    <?php
                    echo sprintf(__('<strong>%s</strong> marked as Unverified', 'user-verification'), esc_html($display_name))
                    ?>
                </p>

            </div>
            <?php

            echo ob_get_clean();

        }

    }



    public function resend_verification(){

        $output = array();

        wp_parse_str($_SERVER['QUERY_STRING'], $output);
        $output = user_verification_recursive_sanitize_arr($output);

        $resend_verification = isset($output['resend_verification']) ? sanitize_text_field($output['resend_verification']) : '';


        if(empty($resend_verification)) return;
        if($resend_verification != 'yes') return;

        if(!current_user_can( 'manage_options' )) return;

        $user_id = isset($output['user_id']) ? sanitize_text_field($output['user_id']) : '';
        $_wpnonce = isset($output['_wpnonce']) ? sanitize_text_field($output['_wpnonce']) : '';





        if ( wp_verify_nonce( $_wpnonce, 'resend_verification' )  ){



            $user_verification_settings = get_option('user_verification_settings');
            $email_verification_enable = isset($user_verification_settings['email_verification']['enable']) ? $user_verification_settings['email_verification']['enable'] : 'yes';

            if($email_verification_enable != 'yes') return;

            $class_user_verification_emails = new class_user_verification_emails();
            $email_templates_data = $class_user_verification_emails->email_templates_data();

            $logo_id = isset($user_verification_settings['logo_id']) ? $user_verification_settings['logo_id'] : '';

            $verification_page_id = isset($user_verification_settings['email_verification']['verification_page_id']) ? $user_verification_settings['email_verification']['verification_page_id'] : '';
            $exclude_user_roles = isset($user_verification_settings['email_verification']['exclude_user_roles']) ? $user_verification_settings['email_verification']['exclude_user_roles'] : array();
            $email_templates_data = isset($user_verification_settings['email_templates_data']['email_resend_key']) ? $user_verification_settings['email_templates_data']['email_resend_key'] : $email_templates_data['email_resend_key'];

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


            if(!empty($exclude_user_roles)){
                foreach ($exclude_user_roles as $role):

                    if(in_array($role, $user_roles)){
                        //update_option('uv_custom_option', $role);
                        update_user_meta( $user_id, 'user_activation_status', 1 );
                        return;
                    }

                endforeach;
            }




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


            $display_name = $user_data->display_name;

            ob_start();

            ?>
            <div class="updated notice is-dismissible">
                <p>
                    <?php
                    echo sprintf(__('Verification mail resend to <strong>%s</strong>', 'user-verification'), esc_html($display_name))
                    ?>
                </p>

            </div>
            <?php

            echo ob_get_clean();

        }

    }



}

new class_user_verification_notices();