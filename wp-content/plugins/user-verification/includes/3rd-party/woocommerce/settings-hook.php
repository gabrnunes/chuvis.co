<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_filter('user_verification_settings_tabs', 'user_verification_settings_tabs_woocommerce');

function user_verification_settings_tabs_woocommerce($tabs){

    $current_tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'email_verification';


    $tabs[] = array(
        'id' => 'woocommerce',
        'title' => sprintf(__('%s WooCommerce','user-verification'),'<i class="fas fa-cart-arrow-down"></i>'),
        'priority' => 15,
        'active' => ($current_tab == 'woocommerce') ? true : false,
    );

    return $tabs;

}


add_action('user_verification_settings_content_woocommerce', 'user_verification_settings_content_woocommerce');

function user_verification_settings_content_woocommerce(){
    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');

    //echo '<pre>'.var_export($user_verification_settings['woocommerce'], true).'</pre>';


    $disable_auto_login = isset($user_verification_settings['woocommerce']['disable_auto_login']) ? $user_verification_settings['woocommerce']['disable_auto_login'] : 'yes';
    $message_after_registration = isset($user_verification_settings['woocommerce']['message_after_registration']) ? $user_verification_settings['woocommerce']['message_after_registration'] : 'yes';
    $redirect_after_payment = isset($user_verification_settings['woocommerce']['redirect_after_payment']) ? $user_verification_settings['woocommerce']['redirect_after_payment'] : '';



    //echo '<pre>'.var_export($user_verification_settings, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('WooCommerce', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for WooCommerce.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'		=> 'disable_auto_login',
            'parent'		=> 'user_verification_settings[woocommerce]',
            'title'		=> __('Disable auto login','user-verification'),
            'details'	=> __('You can disable auto login after registration via WooCommerce register form. this also disable login on checkout page.','user-verification'),
            'type'		=> 'select',
            'value'		=> $disable_auto_login,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'		=> 'message_after_registration',
            'parent'		=> 'user_verification_settings[woocommerce]',
            'title'		=> __('Display Message after successfully registration','user-verification'),
            'details'	=> __('You can display custom message on after successfully registration via WooCommerce register form.','user-verification'),
            'type'		=> 'text',
            'value'		=> $message_after_registration,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);





        $args = array(
            'id'		=> 'redirect_after_payment',
            'parent'		=> 'user_verification_settings[woocommerce]',
            'title'		=> __('Redirect after payment','user-verification'),
            'details'	=> __('You can set custom page to redirect after successfully payment, and this page should check verification status and take action to stay logged-in or logged-out the user automatically. please use following shortcode <code>[user_verification_message message="Please check email to verify account first"]</code> to check verification status, it will automatically logged-out the unverified user and display the custom message.','user-verification'),
            'type'		=> 'select',
            'value'		=> $redirect_after_payment,
            'default'		=> '',
            'args'		=> user_verification_get_pages_list(),

        );

        $settings_tabs_field->generate_field($args);




        ?>

    </div>

    <?php
}




add_action('user_verification_settings_content_recaptcha', 'user_verification_settings_content_recaptcha_woo');

function user_verification_settings_content_recaptcha_woo(){


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $wc_login_form = isset($user_verification_settings['recaptcha']['wc_login_form']) ? $user_verification_settings['recaptcha']['wc_login_form'] : 'no';
    $wc_register_form = isset($user_verification_settings['recaptcha']['wc_register_form']) ? $user_verification_settings['recaptcha']['wc_register_form'] : 'no';
    $wc_lostpassword_form = isset($user_verification_settings['recaptcha']['wc_lostpassword_form']) ? $user_verification_settings['recaptcha']['wc_lostpassword_form'] : 'no';



    //echo '<pre>'.var_export($user_verification_settings, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('WooCommerce reCAPTCHA', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for WooCommerce reCAPTCHA.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'		=> 'wc_login_form',
            'parent'		=> 'user_verification_settings[recaptcha]',
            'title'		=> __('WooCommerce login from','user-verification'),
            'details'	=> __('Enable reCAPTCHA on WooCommerce login from','user-verification'),
            'type'		=> 'select',
            'value'		=> $wc_login_form,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'wc_register_form',
            'parent'		=> 'user_verification_settings[recaptcha]',
            'title'		=> __('WooCommerce register from','user-verification'),
            'details'	=> __('Enable reCAPTCHA on WooCommerce register from','user-verification'),
            'type'		=> 'select',
            'value'		=> $wc_register_form,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);

        $args = array(
            'id'		=> 'wc_lostpassword_form',
            'parent'		=> 'user_verification_settings[recaptcha]',
            'title'		=> __('WooCommerce lost password from','user-verification'),
            'details'	=> __('Enable reCAPTCHA on WooCommerce lost password from','user-verification'),
            'type'		=> 'select',
            'value'		=> $wc_lostpassword_form,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);




        ?>

    </div>

    <?php
}


