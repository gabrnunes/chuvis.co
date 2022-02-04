<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_user_verification_manage_verification{
	
	public function __construct(){
        //add_action('init', array( $this, 'check_subscriber' ));


        add_action('wp_footer', array($this, 'check_email_verification'));
        add_action('wp_footer', array($this, 'resend_verification'));


		}

    public function resend_verification(){

        if (isset($_REQUEST['user_verification_action']) && trim($_REQUEST['user_verification_action']) == 'resend_verification') {

            wp_enqueue_style('font-awesome-5');

            $user_id = isset($_REQUEST['user_id']) ? sanitize_text_field($_REQUEST['user_id']) : '';
            $_wpnonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) : '';


            $jsData = array();
            $mail_status = false;

            if ( wp_verify_nonce( $_wpnonce, 'resend_verification' )  ){



                $user_verification_settings = get_option('user_verification_settings');



              $messages = isset($user_verification_settings['messages']) ? $user_verification_settings['messages'] : array();
              $activation_sent = isset($messages['activation_sent']) ? $messages['activation_sent'] : __('Verification mail has sent.','user-verification');
              $verification_success = isset($messages['verification_success']) ? $messages['verification_success'] : __('Verification mail has sent.','user-verification');
              $please_wait = isset($messages['please_wait']) ? $messages['please_wait'] : '';
              $mail_instruction = isset($messages['mail_instruction']) ? $messages['mail_instruction'] : __('Please check your mail inbox and follow the instruction. don\'t forget to check spam or trash folder.', 'user-verification');

              $title_sending_verification = isset($messages['title_sending_verification']) ? $messages['title_sending_verification'] : __('Sending verification mail','user-verification');


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


            }


            if($mail_status){
                $jsData['mail_sent'] = true;
                $jsData['status_icon'] = '<i class="far fa-check-circle"></i>';
                $jsData['status_text'] = $activation_sent;

            }else {
                $jsData['mail_sent'] = false;
                $jsData['status_icon'] = '<i class="fas fa-user-times"></i>';
                $jsData['status_text'] = __('Sorry! there was an error.', 'user-verification');
            }


            ?>
            <div class="check-email-verification">
                <div class="inner">
                    <span class="close"><i class="fas fa-times"></i></span>

                    <h2 class="status-title"><?php echo $title_sending_verification; ?></h2>

                    <div class="status">

                        <span class="status-icon"><i class="fas fa-spin fa-spinner"></i></span>
                        <span class="status-text"><?php echo $please_wait; ?></span>

                    </div>

                    <div class="description">
                        <p><?php echo $mail_instruction; ?></p>
                    </div>


                </div>
            </div>

            <script>
                jQuery(document).ready(function($) {

                    jsData = <?php echo json_encode($jsData); ?>


                    //console.log(jsData);

                    activation_status = jsData['activation_status'];
                    status_icon = jsData['status_icon'];
                    status_text = jsData['status_text'];
                    redirect_url = jsData['redirect_url'];
                    is_redirect = jsData['is_redirect'];
                    mail_sent = jsData['mail_sent'];

                    setTimeout(function(){

                        $('.status-icon').html(status_icon);
                        $('.status-text').html(status_text);

                        if(mail_sent){
                            $('.description').fadeIn();
                        }

                    }, 2000);



                    setTimeout(function(){
                        //$('.check-email-verification').fadeOut('slow');

                    }, 4000);


                    $(document).on('click', '.check-email-verification .close', function(){

                        $('.check-email-verification').fadeOut();


                    })

                })
            </script>

            <style type="text/css">
                .check-email-verification{
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: #50505094;
                    z-index: 99999999;
                }

                .inner{
                    width: 350px;
                    background: #fff;
                    top: 50%;
                    position: absolute;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    padding: 15px;
                    text-align: center;
                    border-radius: 4px;
                    box-shadow: -1px 11px 11px 0 rgb(152 152 152 / 50%);
                }

                .status-title{
                    font-size: 20px;
                    padding: 20px 0;
                }
                .status{
                    margin: 20px 0;
                }

                .close{
                    position: absolute;
                    right: 0;
                    top: 0;
                    background: #dc4b1e;
                    padding: 10px 15px;
                    color: #fff;
                }
                .description{
                    display: none;
                    line-height: normal;
                }

                .status .status-icon{
                    font-size: 30px;
                    vertical-align: middle;
                }

                .redirect {
                    margin: 50px 0 30px 0;
                }

            </style>
            <?php


        }


    }


	public function check_email_verification(){

        if (isset($_REQUEST['user_verification_action']) && trim($_REQUEST['user_verification_action']) == 'email_verification') {

            $jsData = array();

            wp_enqueue_style('font-awesome-5');

            $activation_key = isset($_REQUEST['activation_key']) ? sanitize_text_field($_REQUEST['activation_key']) : '';



                $user_verification_settings = get_option('user_verification_settings');

                $messages = isset($user_verification_settings['messages']) ? $user_verification_settings['messages'] : array();
                $verification_success = !empty($messages['verification_success']) ? $messages['verification_success'] : __('Thanks for Verifying.','user-verification');

                $invalid_key = !empty($messages['invalid_key']) ? $messages['invalid_key'] : __('Sorry, activation key is not valid.','user-verification');
                $verification_fail = !empty($messages['verification_fail']) ? $messages['verification_fail'] : __('Sorry! Verification failed.','user-verification');
                $please_wait = !empty($messages['please_wait']) ? $messages['please_wait'] : __('Please wait.','user-verification');
                $redirect_after_verify = !empty($messages['redirect_after_verify']) ? $messages['redirect_after_verify'] : __('You will redirect after verification','user-verification');
                $not_redirect = !empty($messages['not_redirect']) ? $messages['not_redirect'] : __('Click if not redirect automatically','user-verification');

                $title_checking_verification = !empty($messages['title_checking_verification']) ? $messages['title_checking_verification'] : __('Checking Verification','user-verification');




                $login_after_verification = isset($user_verification_settings['email_verification']['login_after_verification']) ? $user_verification_settings['email_verification']['login_after_verification'] : '';
                $redirect_after_verification = isset($user_verification_settings['email_verification']['redirect_after_verification']) ? $user_verification_settings['email_verification']['redirect_after_verification'] : '';
                $verification_page_id = isset($user_verification_settings['email_verification']['verification_page_id']) ? $user_verification_settings['email_verification']['verification_page_id'] : '';

                $redirect_page_url = get_permalink($redirect_after_verification);
                $redirect_page_url = apply_filters('user_verification_redirect_after_verification_url', $redirect_page_url);


                //var_dump($verification_key);



                global $wpdb;

                if(is_multisite()){
                    $table = $wpdb->base_prefix . "usermeta";
                }else{
                    $table = $wpdb->prefix . "usermeta";
                }


                $meta_data	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE meta_value = %s", $activation_key ) );
                $user_id = $meta_data->user_id;

              if(!empty($meta_data)){

                  $jsData['is_valid_key'] = 'yes';


                  $user_activation_status = get_user_meta( $meta_data->user_id, 'user_activation_status', true );


                  if($user_activation_status != 0){
                      $jsData['activation_status'] = 0;
                      $jsData['status_icon'] = '<i class="fas fa-user-times"></i>';
                      $jsData['status_text'] = $verification_fail;

                  }else{
                      update_user_meta( $meta_data->user_id, 'user_activation_status', 1 );
                      $jsData['activation_status'] = 1;
                      $jsData['status_icon'] = '<i class="far fa-check-circle"></i>';
                      $jsData['status_text'] = $verification_success;


                      $class_user_verification_emails = new class_user_verification_emails();
                      $email_templates_data = $class_user_verification_emails->email_templates_data();

                      $logo_id = isset($user_verification_settings['logo_id']) ? $user_verification_settings['logo_id'] : '';


                      $exclude_user_roles = isset($user_verification_settings['email_verification']['exclude_user_roles']) ? $user_verification_settings['email_verification']['exclude_user_roles'] : array();
                      $email_templates_data = isset($user_verification_settings['email_templates_data']['email_confirmed']) ? $user_verification_settings['email_templates_data']['email_confirmed'] : $email_templates_data['email_confirmed'];
                      $email_templates_data = isset($user_verification_settings['email_templates_data']['email_confirmed']) ? $user_verification_settings['email_templates_data']['email_confirmed'] : $email_templates_data['email_confirmed'];


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


                      //$user_activation_key =  md5(uniqid('', true) );

                      //update_user_meta( $user_id, 'user_activation_key', $user_activation_key );
                      update_user_meta( $user_id, 'user_activation_status', 1 );

                      $user_data 	= get_userdata( $user_id );


                      do_action('user_verification_email_verified', array('user_id'=> $user_id));


                      $user_roles = !empty($user_data->roles) ? $user_data->roles : array();


                      if(!empty($exclude_user_roles))
                          foreach ($exclude_user_roles as $role):

                              if(in_array($role, $user_roles)){
                                  //update_option('uv_custom_option', $role);
                                  update_user_meta( $user_id, 'user_activation_status', 1 );
                                  return;
                              }

                          endforeach;



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




                      if( $login_after_verification ==  "yes"  ){

                          $jsData['login_after_verify'] = 'yes';


                          $user = get_user_by( 'id', $meta_data->user_id );

                          wp_set_current_user( $meta_data->user_id, $user->user_login );



                          $redirect_page_url = add_query_arg(
                              array(
                                  'activation_key' => $activation_key,
                                  'user_verification_action' => 'autologin',
                              ),
                              $redirect_page_url
                          );

                          //$redirect_page_url = wp_nonce_url( $redirect_page_url,  'user_verification_autologin' );

                      }

                      if(($redirect_after_verification != 'none')):

                          $jsData['is_redirect'] = 'yes';
                          $jsData['redirect_url'] = esc_url_raw($redirect_page_url);

                      endif;
                  }
              }else{
                  $jsData['is_valid_key'] = 'no';
                  $jsData['is_valid_text'] = $invalid_key;
                  $jsData['is_valid_icon'] = '<i class="far fa-times-circle"></i>';
              }


            ?>
            <div class="check-email-verification">
                <div class="inner">
                    <span class="close"><i class="fas fa-times"></i></span>
                    <h2 class="status-title"><?php echo $title_checking_verification; ?></h2>

                    <div class="status">
                        <span class="status-icon"><i class="fas fa-spin fa-spinner"></i></span>
                        <span class="status-text"><?php echo $please_wait; ?></span>
                    </div>


                    <?php if(!empty($redirect_after_verification) && $redirect_after_verification != 'none'): ?>
                        <div class="redirect">
                            <p><?php echo $redirect_after_verify; ?></p>
                            <a href="<?php echo esc_url_raw($redirect_page_url); ?>">
                              <?php echo $not_redirect;  ?>
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <script>
                jQuery(document).ready(function($) {

                    jsData = <?php echo json_encode($jsData); ?>

                    //console.log(jsData);


                    activation_status = jsData['activation_status'];
                    status_icon = jsData['status_icon'];
                    status_text = jsData['status_text'];
                    redirect_url = jsData['redirect_url'];
                    is_redirect = jsData['is_redirect'];
                    is_valid_key = jsData['is_valid_key'];

                    setTimeout(function(){

                        if(is_valid_key == 'yes'){
                            $('.status-icon').html(status_icon);
                            $('.status-text').html(status_text);

                        }else{
                            is_valid_icon = jsData['is_valid_icon'];
                            is_valid_text = jsData['is_valid_text'];

                            $('.status-icon').html(is_valid_icon);
                            $('.status-text').html(is_valid_text);

                            $('.redirect').fadeOut();

                        }





                }, 2000);


                    setTimeout(function(){
                        if(is_redirect == 'yes'){
                            window.location.href = redirect_url;
                        }
                        //$('.check-email-verification').fadeOut('slow');

                    }, 4000);


                    $(document).on('click', '.check-email-verification .close', function(){

                        $('.check-email-verification').fadeOut();


                    })


                })
            </script>

            <style type="text/css">
                .check-email-verification{
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: #50505094;
                    z-index: 99999999;
                }

                .inner{
                    width: 350px;
                    background: #fff;
                    top: 50%;
                    position: absolute;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    padding: 15px;
                    text-align: center;
                    border-radius: 4px;
                    box-shadow: -1px 11px 11px 0 rgb(152 152 152 / 50%);
                    overflow: hidden;
                }
                .close{
                    position: absolute;
                    right: 0;
                    top: 0;
                    background: #dc4b1e;
                    padding: 10px 15px;
                    color: #fff;
                    cursor: pointer;
                }
                .status-title{
                    font-size: 20px;
                    padding: 20px 0;
                }
                .status{
                    margin: 20px 0;
                }

                .resend{
                    display: none;
                }

                .status .status-icon{
                    font-size: 30px;
                    vertical-align: middle;
                }

                .redirect {
                    margin: 50px 0 30px 0;
                }

            </style>
            <?php


        }


	}



}

new class_user_verification_manage_verification();













