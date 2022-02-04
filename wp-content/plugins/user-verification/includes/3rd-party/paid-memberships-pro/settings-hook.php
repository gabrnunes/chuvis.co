<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_filter('user_verification_settings_tabs', 'user_verification_settings_tabs_pmp');

function user_verification_settings_tabs_pmp($tabs){

    $current_tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'email_verification';




    $tabs[] = array(
        'id' => 'paid_memberships_pro',
        'title' => sprintf(__('%s Paid Memberships Pro','user-verification'),'<i class="fas fa-user-tag"></i>'),
        'priority' => 25,
        'active' => ($current_tab == 'paid_memberships_pro') ? true : false,
    );

    return $tabs;

}


add_action('user_verification_settings_content_paid_memberships_pro', 'user_verification_settings_content_paid_memberships_pro');

function user_verification_settings_content_paid_memberships_pro(){


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $disable_auto_login = isset($user_verification_settings['paid_memberships_pro']['disable_auto_login']) ? $user_verification_settings['paid_memberships_pro']['disable_auto_login'] : 'no';
    $message_checkout_page = isset($user_verification_settings['paid_memberships_pro']['message_checkout_page']) ? $user_verification_settings['paid_memberships_pro']['message_checkout_page'] : '';
    $redirect_timout = isset($user_verification_settings['paid_memberships_pro']['redirect_timout']) ? $user_verification_settings['paid_memberships_pro']['redirect_timout'] : '';
    $redirect_after_checkout = isset($user_verification_settings['paid_memberships_pro']['redirect_after_checkout']) ? $user_verification_settings['paid_memberships_pro']['redirect_after_checkout'] : '';



    //echo '<pre>'.var_export($user_verification_settings, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Paid Memberships Pro', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for Paid Memberships Pro.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'		=> 'disable_auto_login',
            'parent'		=> 'user_verification_settings[paid_memberships_pro]',
            'title'		=> __('Disable auto login','user-verification'),
            'details'	=> __('You can disable auto login after registration via Paid Memberships Pro checkout(register) form.','user-verification'),
            'type'		=> 'select',
            'value'		=> $disable_auto_login,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'message_checkout_page',
            'parent'		=> 'user_verification_settings[paid_memberships_pro]',
            'title'		=> __('Display message on checkout confirmation page','user-verification'),
            'details'	=> __('You can display custom message on checkout confirmation page.','user-verification'),
            'type'		=> 'text',
            'value'		=> $message_checkout_page,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'redirect_timout',
            'parent'		=> 'user_verification_settings[paid_memberships_pro]',
            'title'		=> __('Automatically logout after second','user-verification'),
            'details'	=> __('After successfully checkout user will wait for few second to display the message and then redirect to another page. <br> 1000 = 1 second','user-verification'),
            'type'		=> 'text',
            'value'		=> $redirect_timout,
            'default'		=> '',
            'placeholder'		=> '3000',

        );

        $settings_tabs_field->generate_field($args);



        $args = array(
            'id'		=> 'redirect_after_checkout',
            'parent'		=> 'user_verification_settings[paid_memberships_pro]',
            'title'		=> __('Redirect to this page after checkout','user-verification'),
            'details'	=> __('You can set custom page to redirect and logout after few second passed, where user can see instruction what to do next to get verified.','user-verification'),
            'type'		=> 'select',
            'value'		=> $redirect_after_checkout,
            'default'		=> '',
            'args'		=> user_verification_get_pages_list(),

        );

        $settings_tabs_field->generate_field($args);

        ?>

    </div>

    <?php
}

