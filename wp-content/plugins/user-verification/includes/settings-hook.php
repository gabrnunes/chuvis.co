<?php
if (!defined('ABSPATH')) exit;  // if direct access

add_action('user_verification_settings_content_email_verification', 'user_verification_settings_content_email_verification');

function user_verification_settings_content_email_verification()
{
    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $email_verification_enable = isset($user_verification_settings['email_verification']['enable']) ? $user_verification_settings['email_verification']['enable'] : 'yes';
    $verification_page_id = isset($user_verification_settings['email_verification']['verification_page_id']) ? $user_verification_settings['email_verification']['verification_page_id'] : '';
    $redirect_after_verification = isset($user_verification_settings['email_verification']['redirect_after_verification']) ? $user_verification_settings['email_verification']['redirect_after_verification'] : '';
    $login_after_verification = isset($user_verification_settings['email_verification']['login_after_verification']) ? $user_verification_settings['email_verification']['login_after_verification'] : '';
    $exclude_user_roles = isset($user_verification_settings['email_verification']['exclude_user_roles']) ? $user_verification_settings['email_verification']['exclude_user_roles'] : array();


?>
    <div class="section">
        <div class="section-title"><?php echo __('Email verification', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for email verification.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'        => 'enable',
            'parent'        => 'user_verification_settings[email_verification]',
            'title'        => __('Enable email verification', 'user-verification'),
            'details'    => __('Select to enable or disable email verification.', 'user-verification'),
            'type'        => 'select',
            'value'        => $email_verification_enable,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'verification_page_id',
            'parent'        => 'user_verification_settings[email_verification]',
            'title'        => __('Choose verification page', 'user-verification'),
            'details'    => __('Select page where verification will process. default home page if select none.', 'user-verification'),
            'type'        => 'select',
            'value'        => $verification_page_id,
            'default'        => '',
            'args'        => user_verification_get_pages_list(),

        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'redirect_after_verification',
            'parent'        => 'user_verification_settings[email_verification]',
            'title'        => __('Redirect after verification', 'user-verification'),
            'details'    => __('Redirect to any page after successfully verified account.', 'user-verification'),
            'type'        => 'select',
            'value'        => $redirect_after_verification,
            'default'        => '',
            'args'        => user_verification_get_pages_list(),

        );

        $settings_tabs_field->generate_field($args);




        $args = array(
            'id'        => 'login_after_verification',
            'parent'        => 'user_verification_settings[email_verification]',
            'title'        => __('Automatically login after verification', 'user-verification'),
            'details'    => __('Set yes to login automatically after verification completed, otherwise set no.', 'user-verification'),
            'type'        => 'select',
            'value'        => $login_after_verification,
            'default'        => 'yes',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),

        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'exclude_user_roles',
            'parent'        => 'user_verification_settings[email_verification]',
            'title'        => __('Exclude user role', 'user-verification'),
            'details'    => __('You can exclude verification for these user roles to login on your site.', 'user-verification'),
            'type'        => 'select',
            'multiple'        => true,
            'value'        => $exclude_user_roles,
            'default'        => array(),
            'args'        => user_verification_user_roles(),

        );

        $settings_tabs_field->generate_field($args);




        ?>

    </div>


    <div class="section">
        <div class="section-title"><?php echo __('Error messages', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize error messages.', 'user-verification'); ?></p>

        <?php

        $messages = isset($user_verification_settings['messages']) ? $user_verification_settings['messages'] : array();

        $invalid_key = isset($messages['invalid_key']) ? $messages['invalid_key'] : __('Sorry, activation key is not valid.', 'user-verification');
        $activation_sent = isset($messages['activation_sent']) ? $messages['activation_sent'] : __('Verification mail has been sent.', 'user-verification');
        $verify_email = isset($messages['verify_email']) ? $messages['verify_email'] : __('Verify your email first!', 'user-verification');
        $registration_success = isset($messages['registration_success']) ? $messages['registration_success'] : __('Registration complete. Please verify the mail first, then visit the <a href="%s">login page</a>.', 'user-verification');
        $verification_success = isset($messages['verification_success']) ? $messages['verification_success'] : __('Thanks for Verifying.', 'user-verification');
        $verification_fail = isset($messages['verification_fail']) ? $messages['verification_fail'] : __('Sorry! Verification failed.', 'user-verification');
        $please_wait = isset($messages['please_wait']) ? $messages['please_wait'] : __('Please wait.', 'user-verification');
        $mail_instruction = isset($messages['mail_instruction']) ? $messages['mail_instruction'] : __('Please check your mail inbox and follow the instruction. don\'t forget to check spam or trash folder.', 'user-verification');

        $redirect_after_verify = isset($messages['redirect_after_verify']) ? $messages['redirect_after_verify'] : __('You will redirect after verification', 'user-verification');
        $not_redirect = isset($messages['not_redirect']) ? $messages['not_redirect'] : __('Click if not redirect automatically', 'user-verification');


        $title_checking_verification = isset($messages['title_checking_verification']) ? $messages['title_checking_verification'] : __('Checking Verification', 'user-verification');
        $title_sending_verification = isset($messages['title_sending_verification']) ? $messages['title_sending_verification'] : __('Sending verification mail', 'user-verification');

        $captcha_error = isset($messages['captcha_error']) ? $messages['captcha_error'] : __('Captcha not resolved.', 'user-verification');





        $args = array(
            'id'        => 'invalid_key',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Invalid activation key', 'user-verification'),
            'details'    => __('Show custom message when user activation key is invalid or wrong', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $invalid_key,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'activation_sent',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Activation key has sent', 'user-verification'),
            'details'    => __('Show custom message when activation key is sent to user email', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $activation_sent,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'verify_email',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Verify email address', 'user-verification'),
            'details'    => __('Show custom message when user try to login without verifying email with proper activation key', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $verify_email,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'registration_success',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Registration success message', 'user-verification'),
            'details'    => __('User will get this message as soon as registered on your website', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $registration_success,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'verification_success',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Verification successful', 'user-verification'),
            'details'    => __('Show custom message when user successfully verified', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $verification_success,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'verification_fail',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Verification fail', 'user-verification'),
            'details'    => __('Show custom message when verification failed', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $verification_fail,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'please_wait',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Please wait text', 'user-verification'),
            'details'    => __('Show custom for "please wait"', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $please_wait,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'mail_instruction',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Mail instruction text', 'user-verification'),
            'details'    => __('Add custom text for mail instructions.', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $mail_instruction,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'redirect_after_verify',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Redirect after verify text', 'user-verification'),
            'details'    => __('Add custom text redirect after verification.', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $redirect_after_verify,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'not_redirect',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Not redirect text', 'user-verification'),
            'details'    => __('Add custom text not redirect automatically.', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $not_redirect,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'title_checking_verification',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Popup title checking verification', 'user-verification'),
            'details'    => __('Show custom for "checking verification"', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $title_checking_verification,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'title_sending_verification',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Popup title sending verification', 'user-verification'),
            'details'    => __('Show custom for "sending verification"', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $title_sending_verification,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);




        $args = array(
            'id'        => 'captcha_error',
            'parent'        => 'user_verification_settings[messages]',
            'title'        => __('Captcha error message', 'user-verification'),
            'details'    => __('Show custom message when captcha error occurred.', 'user-verification'),
            'type'        => 'textarea',
            'value'        => $captcha_error,
            'default'        => '',

        );

        $settings_tabs_field->generate_field($args);

        ?>

    </div>



    <?php
}



add_action('user_verification_settings_content_email_templates', 'user_verification_settings_content_email_templates');

if (!function_exists('user_verification_settings_content_email_templates')) {
    function user_verification_settings_content_email_templates()
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_user_verification_emails = new class_user_verification_emails();
        $templates_data_default = $class_user_verification_emails->email_templates_data();
        $email_templates_parameters = $class_user_verification_emails->email_templates_parameters();


        $user_verification_settings = get_option('user_verification_settings');


        $logo_id = isset($user_verification_settings['logo_id']) ? $user_verification_settings['logo_id'] : '';
        $templates_data_saved = isset($user_verification_settings['email_templates_data']) ? $user_verification_settings['email_templates_data'] : $templates_data_default;



    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Email settings', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Customize email settings.', 'user-verification'); ?></p>

            <?php

            $args = array(
                'id'        => 'logo_id',
                'parent'        => 'user_verification_settings',
                'title'        => __('Email logo', 'user-verification'),
                'details'    => __('Email logo URL to display on mail.', 'user-verification'),
                'type'        => 'media',
                'value'        => $logo_id,
                'default'        => '',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);




            ob_start();


            ?>
            <div class="templates_editor expandable">
                <?php




                if (!empty($templates_data_default))
                    foreach ($templates_data_default as $key => $templates) {

                        $templates_data_display = isset($templates_data_saved[$key]) ? $templates_data_saved[$key] : $templates;


                        $email_bcc = isset($templates_data_display['email_bcc']) ? $templates_data_display['email_bcc'] : '';
                        $email_from = isset($templates_data_display['email_from']) ? $templates_data_display['email_from'] : '';
                        $email_from_name = isset($templates_data_display['email_from_name']) ? $templates_data_display['email_from_name'] : '';
                        $reply_to = isset($templates_data_display['reply_to']) ? $templates_data_display['reply_to'] : '';
                        $reply_to_name = isset($templates_data_display['reply_to_name']) ? $templates_data_display['reply_to_name'] : '';
                        $email_subject = isset($templates_data_display['subject']) ? $templates_data_display['subject'] : '';

                        $email_body = isset($templates_data_display['html']) ? $templates_data_display['html'] : '';


                        $enable = isset($templates_data_display['enable']) ? $templates_data_display['enable'] : 'yes';
                        $description = isset($templates_data_display['description']) ? $templates_data_display['description'] : '';

                        $parameters = isset($email_templates_parameters[$key]) ? $email_templates_parameters[$key] : array();


                        //echo '<pre>'.var_export($enable).'</pre>';

                ?>
                    <div class="item template <?php echo $key; ?>">
                        <div class="header">
                            <span title="<?php echo __('Click to expand', 'user-verification'); ?>" class="expand ">
                                <i class="fa fa-expand"></i>
                                <i class="fa fa-compress"></i>
                            </span>

                            <?php
                            if ($enable == 'yes') :
                            ?>
                                <span title="<?php echo __('Enable', 'user-verification'); ?>" class="is-enable ">
                                    <i class="fa fa-check-square"></i>
                                </span>
                            <?php
                            else :
                            ?>
                                <span title="<?php echo __('Disabled', 'user-verification'); ?>" class="is-enable ">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            <?php
                            endif;
                            ?>
                            <span class="expand"><?php echo $templates['name']; ?></span>

                        </div>
                        <input type="hidden" name="user_verification_settings[email_templates_data][<?php echo $key; ?>][name]" value="<?php echo $templates['name']; ?>" />
                        <div class="options">
                            <div class="description"><?php echo $description; ?></div><br /><br />



                            <?php


                            $args = array(
                                'id'        => 'enable',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Enable?', 'user-verification'),
                                'details'    => __('Enable or disable this email notification.', 'user-verification'),
                                'type'        => 'select',
                                'value'        => $enable,
                                'default'        => 'yes',
                                'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),

                            );

                            $settings_tabs_field->generate_field($args);






                            $args = array(
                                'id'        => 'email_bcc',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Email Bcc', 'user-verification'),
                                'details'    => __('Send a copy to these email(Bcc)', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $email_bcc,
                                'default'        => '',
                                'placeholder'        => get_bloginfo('admin_email'),


                            );

                            $settings_tabs_field->generate_field($args);


                            $args = array(
                                'id'        => 'email_from_name',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Email from name', 'user-verification'),
                                'details'    => __('Write email displaying from name', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $email_from_name,
                                'default'        => '',
                                'placeholder'        => get_bloginfo('title'),


                            );

                            $settings_tabs_field->generate_field($args);



                            $args = array(
                                'id'        => 'email_from',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Email from', 'user-verification'),
                                'details'    => __('Email from email address', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $email_from,
                                'default'        => '',
                                'placeholder'        => get_bloginfo('admin_email'),


                            );

                            $settings_tabs_field->generate_field($args);

                            $args = array(
                                'id'        => 'reply_to_name',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Reply to name', 'user-verification'),
                                'details'    => __('Email reply to name', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $reply_to_name,
                                'default'        => '',
                                'placeholder'        => get_bloginfo('title'),


                            );

                            $settings_tabs_field->generate_field($args);


                            $args = array(
                                'id'        => 'reply_to',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Reply to', 'user-verification'),
                                'details'    => __('Reply to email address', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $reply_to,
                                'default'        => '',
                                'placeholder'        => get_bloginfo('admin_email'),


                            );

                            $settings_tabs_field->generate_field($args);



                            $args = array(
                                'id'        => 'subject',
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Email subject', 'user-verification'),
                                'details'    => __('Write email subjects', 'user-verification'),
                                'type'        => 'text',
                                'value'        => $email_subject,
                                'default'        => '',
                                'placeholder'        => '',


                            );

                            $settings_tabs_field->generate_field($args);

                            $args = array(
                                'id'        => 'html',
                                'css_id'        => $key,
                                'parent'        => 'user_verification_settings[email_templates_data][' . $key . ']',
                                'title'        => __('Email body', 'user-verification'),
                                'details'    => __('Write email body', 'user-verification'),
                                'type'        => 'wp_editor',

                                'value'        => $email_body,
                                'default'        => '',
                                'placeholder'        => '',


                            );

                            $settings_tabs_field->generate_field($args);

                            ob_start();
                            ?>
                            <ul>


                                <?php

                                if (!empty($parameters)) :
                                    foreach ($parameters as $parameterId => $parameter) :
                                ?>
                                        <li><code><?php echo $parameterId; ?></code> => <?php echo $parameter; ?></li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                            <?php


                            $custom_html = ob_get_clean();

                            $args = array(
                                'id'        => 'html',
                                //                                    'parent'		=> 'user_verification_settings[email_templates_data]['.$key.']',
                                'title'        => __('Parameter', 'user-verification'),
                                'details'    => __('Available parameter for this email template', 'user-verification'),
                                'type'        => 'custom_html',
                                'html'        => $custom_html,
                                'default'        => '',


                            );

                            $settings_tabs_field->generate_field($args);

                            ?>


                        </div>

                    </div>
                <?php

                    }


                ?>


            </div>
            <?php


            $html = ob_get_clean();




            $args = array(
                'id'        => 'email_templates',
                //'parent'		=> '',
                'title'        => __('Email templates', 'user-verification'),
                'details'    => __('Customize email templates.', 'user-verification'),
                'type'        => 'custom_html',
                //'multiple'		=> true,
                'html'        => $html,
            );

            $settings_tabs_field->generate_field($args);




            ?>


        </div>
    <?php


    }
}


add_action('user_verification_settings_content_email_otp', 'user_verification_settings_content_email_otp');

function user_verification_settings_content_email_otp()
{


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $enable_default_login = isset($user_verification_settings['email_otp']['enable_default_login']) ? $user_verification_settings['email_otp']['enable_default_login'] : 'no';
    $enable_wc_login = isset($user_verification_settings['email_otp']['enable_wc_login']) ? $user_verification_settings['email_otp']['enable_wc_login'] : 'no';


    $enable_default_register = isset($user_verification_settings['email_otp']['enable_default_register']) ? $user_verification_settings['email_otp']['enable_default_register'] : 'no';


    //echo '<pre>'.var_export($_SERVER['HTTP_USER_AGENT'], true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Email OTP', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for email OTP.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'        => 'enable_default_login',
            'parent'        => 'user_verification_settings[email_otp]',
            'title'        => __('Enable on default login', 'user-verification'),
            'details'    => __('Enable OTP on default login page. every time a user try to login will require a OTP send via mail.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_default_login,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'enable_wc_login',
            'parent'        => 'user_verification_settings[email_otp]',
            'title'        => __('Enable on WooCommerce login', 'user-verification'),
            'details'    => __('Enable OTP on WooCommerce login page. every time a user try to login via WooCommerce login form will require a OTP send via mail.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_wc_login,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'enable_default_register',
            'parent'        => 'user_verification_settings[email_otp]',
            'title'        => __('Enable on default register', 'user-verification'),
            'details'    => __('Enable OTP on default registration page. every time a user try to register will require a OTP send via mail.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_default_register,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        //$settings_tabs_field->generate_field($args);


        ?>

    </div>

<?php
}




add_action('user_verification_settings_content_spam_protection', 'user_verification_settings_content_spam_protection');

function user_verification_settings_content_spam_protection()
{


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $enable_domain_block = isset($user_verification_settings['spam_protection']['enable_domain_block']) ? $user_verification_settings['spam_protection']['enable_domain_block'] : 'no';
    $blocked_domain = isset($user_verification_settings['spam_protection']['blocked_domain']) ? $user_verification_settings['spam_protection']['blocked_domain'] : array();
    $allowed_domain = isset($user_verification_settings['spam_protection']['allowed_domain']) ? $user_verification_settings['spam_protection']['allowed_domain'] : array();

    $enable_username_block = isset($user_verification_settings['spam_protection']['enable_username_block']) ? $user_verification_settings['spam_protection']['enable_username_block'] : 'no';
    $blocked_username = isset($user_verification_settings['spam_protection']['blocked_username']) ? $user_verification_settings['spam_protection']['blocked_username'] : array();

    $enable_browser_block = isset($user_verification_settings['spam_protection']['enable_browser_block']) ?
        $user_verification_settings['spam_protection']['enable_browser_block'] : 'no';
    $allowed_browsers = isset($user_verification_settings['spam_protection']['allowed_browsers']) ? $user_verification_settings['spam_protection']['allowed_browsers'] : array();




    //echo '<pre>'.var_export($_SERVER['HTTP_USER_AGENT'], true).'</pre>';

?>
    <div class="section">
        <div class="section-title"><?php echo __('Spam Protection', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for spam protection.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'        => 'enable_domain_block',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Enable domain block', 'user-verification'),
            'details'    => __('You can enable email domain name blocking for spammy/temporary email account services.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_domain_block,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'blocked_domain',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Blocked domains', 'user-verification'),
            'details'    => __('One domain per line. without http:// or https:// or www.', 'user-verification'),
            'type'        => 'text_multi',
            'value'        => $blocked_domain,
            'default'        => array(),
            'placeholder' => __('domain.com', 'user-verification'),

        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'allowed_domain',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Allowed domains', 'user-verification'),
            'details'    => __('One domain per line. without http:// or https:// or www', 'user-verification'),
            'type'        => 'text_multi',
            'value'        => $allowed_domain,
            'default'        => array(),
            'placeholder' => __('domain.com', 'user-verification'),

        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'enable_username_block',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Enable username block', 'user-verification'),
            'details'    => __('User will not able to register blocked username, like admin, info, etc.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_username_block,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'blocked_username',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Blocked username', 'user-verification'),
            'details'    => __('You can following string match <ul><li><b>^username</b> : String start with <b><i>username</i></b></li><li><b>username$</b> : String end by <b><i>username</i></b></li><li><b>username</b> : String contain <b><i>username</i></b></b></li></ul>', 'user-verification'),
            'type'        => 'text_multi',
            'value'        => $blocked_username,
            'default'        => array(),
            'placeholder' => __('username', 'user-verification'),

        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'enable_browser_block',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Enable device/browser block', 'user-verification'),
            'details'    => __('User will not able to register blocked device or browser, like chrome, safari, etc.', 'user-verification'),
            'type'        => 'select',
            'value'        => $enable_browser_block,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        //$settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'allowed_browsers',
            'parent'        => 'user_verification_settings[spam_protection]',
            'title'        => __('Allowed devices/browsers', 'user-verification'),
            'details'    => __('Select the browser list to allow user login.', 'user-verification'),
            'type'        => 'checkbox',
            'style'        => array('inline' => false),

            'value'        => $allowed_browsers,
            'args'        => array(
                'chrome' => __('Chrome', 'user-verification'), 'safari' => __('Safari', 'user-verification')
            ),

            'default'        => array(),

        );

        //$settings_tabs_field->generate_field($args);





        ?>

    </div>


    <div class="section">
        <div class="section-title"><?php echo __('isspammy.com Integration', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Enable integration with <a href="http://isspammy.com">http://isspammy.com</a>', 'user-verification'); ?></p>

        <?php

        $report_comment_spam = isset($user_verification_settings['isspammy']['report_comment_spam']) ? $user_verification_settings['isspammy']['report_comment_spam'] : 'yes';
        $report_comment_trash = isset($user_verification_settings['isspammy']['report_comment_trash']) ? $user_verification_settings['isspammy']['report_comment_trash'] : 'yes';
        $block_comment = isset($user_verification_settings['isspammy']['block_comment']) ? $user_verification_settings['isspammy']['block_comment'] : 'yes';
        $comment_form_notice = isset($user_verification_settings['isspammy']['comment_form_notice']) ? $user_verification_settings['isspammy']['comment_form_notice'] : 'yes';
        $comment_form_notice_text = isset($user_verification_settings['isspammy']['comment_form_notice_text']) ?
            $user_verification_settings['isspammy']['comment_form_notice_text'] : '';

        $block_register = isset($user_verification_settings['isspammy']['block_register']) ? $user_verification_settings['isspammy']['block_register'] : 'yes';
        $block_login = isset($user_verification_settings['isspammy']['block_login']) ? $user_verification_settings['isspammy']['block_login'] : 'yes';

        $args = array(
            'id'        => 'report_comment_spam',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Report spam comments email', 'user-verification'),
            'details'    => __('Report spam comment email address to isspammy.com, when you marked a comment as spam it will send comment email address to isspammy.com via API', 'user-verification'),
            'type'        => 'select',
            'value'        => $report_comment_spam,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'report_comment_trash',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Report trash comments email', 'user-verification'),
            'details'    => __('Report trash comment email address to isspammy.com, when you trashed a comment it will send comment email address to isspammy.com via API', 'user-verification'),
            'type'        => 'select',
            'value'        => $report_comment_trash,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'block_comment',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Block spammer comments', 'user-verification'),
            'details'    => __('When enabled it will blocked or deleted all comment from spammer reported on isspammy.com', 'user-verification'),
            'type'        => 'select',
            'value'        => $block_comment,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'comment_form_notice',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Display notice under comment form', 'user-verification'),
            'details'    => __('Display a privacy notice under comment forms.', 'user-verification'),
            'type'        => 'select',
            'value'        => $comment_form_notice,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'comment_form_notice_text',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Custom notice text', 'user-verification'),
            'details'    => __('Display custom notice text under comment forms. please use %s to replace isspammy.com 
          privacy url https://isspammy.com/privacy-policy/', 'user-verification'),
            'type'        => 'text',
            'value'        => $comment_form_notice_text,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'        => 'block_register',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Block user registeration', 'user-verification'),
            'details'    => __('When enabled it will blocked or deleted all registration from spammer reported on isspammy.com', 'user-verification'),
            'type'        => 'select',
            'value'        => $block_register,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'block_login',
            'parent'        => 'user_verification_settings[isspammy]',
            'title'        => __('Block user login', 'user-verification'),
            'details'    => __('When enabled it will blocked all login from spammer reported on isspammy.com', 'user-verification'),
            'type'        => 'select',
            'value'        => $block_login,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);




        ?>

    </div>


<?php
}


add_action('user_verification_settings_content_recaptcha', 'user_verification_settings_content_recaptcha');

function user_verification_settings_content_recaptcha()
{


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $sitekey = isset($user_verification_settings['recaptcha']['sitekey']) ? $user_verification_settings['recaptcha']['sitekey'] : '';
    $secretkey = isset($user_verification_settings['recaptcha']['secretkey']) ? $user_verification_settings['recaptcha']['secretkey'] : '';
    $recaptcha_version = isset($user_verification_settings['recaptcha']['version']) ? $user_verification_settings['recaptcha']['version'] : 'v2_checkbox';

    $default_login_page = isset($user_verification_settings['recaptcha']['default_login_page']) ? $user_verification_settings['recaptcha']['default_login_page'] : 'no';
    $default_registration_page = isset($user_verification_settings['recaptcha']['default_registration_page']) ? $user_verification_settings['recaptcha']['default_registration_page'] : 'no';
    $default_lostpassword_page = isset($user_verification_settings['recaptcha']['default_lostpassword_page']) ? $user_verification_settings['recaptcha']['default_lostpassword_page'] : 'no';
    $comment_form = isset($user_verification_settings['recaptcha']['comment_form']) ? $user_verification_settings['recaptcha']['comment_form'] : 'no';



    //echo '<pre>'.var_export($user_verification_settings, true).'</pre>';

?>
    <div class="section">
        <div class="section-title"><?php echo __('reCAPTCHA', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for reCAPTCHA.', 'user-verification'); ?></p>

        <?php

        $args = array(
            'id'        => 'version',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('Recaptcha version', 'user-verification'),
            'details'    => __('Select recaptcha version.', 'user-verification'),
            'type'        => 'select',
            'value'        => $recaptcha_version,
            'default'        => '',
            'args'        => array('v3' => __('V3', 'user-verification'), 'v2_checkbox' => __('V2 - Checkbox', 'user-verification')), //'v2_invisible'=>__('V2 - Invisible','user-verification')
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'sitekey',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('reCAPTCHA sitekey', 'user-verification'),
            'details'    => __('Google reCAPTCHA sitekey, please register here <a href="https://www.google.com/recaptcha/admin/">https://www.google.com/recaptcha/admin/</a>', 'user-verification'),
            'type'        => 'text',
            'value'        => $sitekey,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'        => 'secretkey',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('reCAPTCHA secret key', 'user-verification'),
            'details'    => __('Google reCAPTCHA secret key, please register here <a href="https://www.google.com/recaptcha/admin/">https://www.google.com/recaptcha/admin/</a>', 'user-verification'),
            'type'        => 'text',
            'value'        => $secretkey,
            'default'        => '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'default_login_page',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('Recaptcha on default login page', 'user-verification'),
            'details'    => __('Enable recaptcha on default login page.', 'user-verification'),
            'type'        => 'select',
            'value'        => $default_login_page,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'default_registration_page',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('Recaptcha on default registration page', 'user-verification'),
            'details'    => __('Enable recaptcha on default registration page.', 'user-verification'),
            'type'        => 'select',
            'value'        => $default_registration_page,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'default_lostpassword_page',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('Recaptcha on default reset password page', 'user-verification'),
            'details'    => __('Enable recaptcha on default reset password page.', 'user-verification'),
            'type'        => 'select',
            'value'        => $default_lostpassword_page,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'        => 'comment_form',
            'parent'        => 'user_verification_settings[recaptcha]',
            'title'        => __('Recaptcha on comment forms', 'user-verification'),
            'details'    => __('Enable recaptcha on comment forms.', 'user-verification'),
            'type'        => 'select',
            'value'        => $comment_form,
            'default'        => '',
            'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
        );

        $settings_tabs_field->generate_field($args);



        ?>

    </div>

    <?php
}














add_action('user_verification_settings_content_temp_login', 'user_verification_settings_content_temp_login');

if (!function_exists('user_verification_settings_content_temp_login')) {
    function user_verification_settings_content_temp_login($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $user_verification_settings = get_option('user_verification_settings');

        $enable = isset($user_verification_settings['temp_login']['enable']) ? $user_verification_settings['temp_login']['enable'] : 'no';
        $duration = isset($user_verification_settings['temp_login']['duration']) ? $user_verification_settings['temp_login']['duration'] : 3600;
        $require_verification = isset($user_verification_settings['temp_login']['require_verification']) ?
            $user_verification_settings['temp_login']['require_verification'] : 'no';

        //var_dump($delete_unverified_user);






    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Temporary login', 'user-verification'); ?></div>
            <p class="description section-description"><?php //echo __('Use following to get help and support from our expert team.', 'user-verification'); 
                                                        ?></p>

            <?php

            $args = array(
                'id'        => 'enable',
                'parent'        => 'user_verification_settings[temp_login]',
                'title'        => __('Enable', 'user-verification'),
                'details'    => __('Enable temporary login via url.', 'user-verification'),
                'type'        => 'select',
                'value'        => $enable,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'require_verification',
                'parent'        => 'user_verification_settings[temp_login]',
                'title'        => __('Require verification', 'user-verification'),
                'details'    => __('Require email verification for temporary login.', 'user-verification'),
                'type'        => 'select',
                'value'        => $require_verification,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'duration',
                'parent'        => 'user_verification_settings[temp_login]',
                'title'        => __('Duration', 'user-verification'),
                'details'    => __('maximum duration for temp login. ex: 3600 (in second)', 'user-verification'),
                'type'        => 'text',
                'value'        => $duration,
                'default'        => '',
            );

            $settings_tabs_field->generate_field($args);


            ?>


        </div>
    <?php


    }
}





add_action('user_verification_settings_content_tools', 'user_verification_settings_content_tools');

if (!function_exists('user_verification_settings_content_tools')) {
    function user_verification_settings_content_tools($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $user_verification_settings = get_option('user_verification_settings');

        $delete_unverified_user = isset($user_verification_settings['unverified']['delete_user']) ? $user_verification_settings['unverified']['delete_user'] : 'no';


        $friendly_date = date('d-m-Y h:i:sa', wp_next_scheduled('user_verification_delete_unverified_user'));


        if ($delete_unverified_user == 'yes') {
            if (!wp_next_scheduled('user_verification_delete_unverified_user')) {
                wp_schedule_event(time(), 'daily', 'user_verification_delete_unverified_user');
            }
        } else {
            wp_clear_scheduled_hook('user_verification_delete_unverified_user');
        }


    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Tools', 'user-verification'); ?></div>
            <p class="description section-description"><?php //echo __('Use following to get help and support from our expert team.', 'user-verification'); 
                                                        ?></p>

            <?php



            $des = ($delete_unverified_user == 'yes') ? sprintf(__('Enable to delete unverified users. Next schedule <strong>%s</strong>', 'user-verification'), $friendly_date) : __('Enable to delete unverified users.', 'user-verification');


            $args = array(
                'id'        => 'delete_user',
                'parent'        => 'user_verification_settings[unverified]',
                'title'        => __('Delete unverified users', 'user-verification'),
                'details'    => $des,
                'type'        => 'select',
                'value'        => $delete_unverified_user,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'user-verification'), 'no' => __('No', 'user-verification')),
            );

            $settings_tabs_field->generate_field($args);

            ?>


        </div>
    <?php


    }
}







add_action('user_verification_settings_content_help_support', 'user_verification_settings_content_help_support');

if (!function_exists('user_verification_settings_content_help_support')) {
    function user_verification_settings_content_help_support($tab)
    {

        $settings_tabs_field = new settings_tabs_field();


    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Get support', 'user-verification'); ?></div>
            <p class="description section-description"><?php echo __('Use following to get help and support from our expert team.', 'user-verification'); ?></p>

            <?php
            ob_start();
            ?>

            <p><?php echo __('Ask question for free on our forum and get quick reply from our expert team members.', 'user-verification'); ?></p>
            <a class="button" target="_blank" href="https://www.pickplugins.com/create-support-ticket/"><?php echo __('Create support ticket', 'user-verification'); ?></a>

            <p><?php echo __('Read our documentation before asking your question.', 'user-verification'); ?></p>
            <a class="button" target="_blank" href="https://pickplugins.com/documentation/user-verification/"><?php echo __('Documentation', 'user-verification'); ?></a>

            <p><?php echo __('Watch video tutorials.', 'user-verification'); ?></p>
            <a class="button" target="_blank" href="https://www.youtube.com/playlist?list=PL0QP7T2SN94bJmrpEqtjsj9nnR6jiKTDt"><i class="fab fa-youtube"></i> <?php echo __('All tutorials', 'user-verification'); ?></a>





            <?php

            $html = ob_get_clean();

            $args = array(
                'id'        => 'get_support',
                //'parent'		=> '',
                'title'        => __('Ask question', 'user-verification'),
                'details'    => '',
                'type'        => 'custom_html',
                'html'        => $html,

            );

            $settings_tabs_field->generate_field($args);


            ob_start();
            ?>

            <p class="">We wish your 2 minutes to write your feedback about the <b>Post Grid</b> plugin. give us <span style="color: #ffae19"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span></p>

            <a target="_blank" href="https://wordpress.org/support/plugin/user-verification/reviews/#new-post" class="button"><i class="fab fa-wordpress"></i> Write a review</a>


            <?php

            $html = ob_get_clean();

            $args = array(
                'id'        => 'reviews',
                //'parent'		=> '',
                'title'        => __('Submit reviews', 'user-verification'),
                'details'    => '',
                'type'        => 'custom_html',
                'html'        => $html,

            );

            $settings_tabs_field->generate_field($args);



            ?>


        </div>
<?php


    }
}






add_action('user_verification_settings_save', 'user_verification_settings_save');

function user_verification_settings_save()
{

    $user_verification_settings = isset($_POST['user_verification_settings']) ?  user_verification_recursive_sanitize_arr($_POST['user_verification_settings']) : array();
    update_option('user_verification_settings', $user_verification_settings);
}
