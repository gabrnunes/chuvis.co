<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

add_filter('user_verification_settings_tabs', 'user_verification_settings_tabs_ultimate_member');

function user_verification_settings_tabs_ultimate_member($tabs){

    $current_tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'email_verification';



    $tabs[] = array(
        'id' => 'ultimate_member',
        'title' => sprintf(__('%s Ultimate Member','user-verification'),'<i class="fas fa-users"></i>'),
        'priority' => 20,
        'active' => ($current_tab == 'ultimate_member') ? true : false,
    );

    return $tabs;

}


add_action('user_verification_settings_content_ultimate_member', 'user_verification_settings_content_ultimate_member');

function user_verification_settings_content_ultimate_member(){


    $settings_tabs_field = new settings_tabs_field();

    $user_verification_settings = get_option('user_verification_settings');

    //delete_option('user_verification_settings');


    $disable_auto_login = isset($user_verification_settings['ultimate_member']['disable_auto_login']) ? $user_verification_settings['ultimate_member']['disable_auto_login'] : 'no';
    $message_before_header = isset($user_verification_settings['ultimate_member']['message_before_header']) ? $user_verification_settings['ultimate_member']['message_before_header'] : '';



    //echo '<pre>'.var_export($user_verification_settings, true).'</pre>';

    ?>
    <div class="section">
        <div class="section-title"><?php echo __('WooCommerce', 'user-verification'); ?></div>
        <p class="description section-description"><?php echo __('Customize options for WooCommerce.', 'user-verification'); ?></p>

        <?php


        $args = array(
            'id'		=> 'disable_auto_login',
            'parent'		=> 'user_verification_settings[ultimate_member]',
            'title'		=> __('Disable auto login','user-verification'),
            'details'	=> __('You can disable auto login after registration via ultimate member register form.','user-verification'),
            'type'		=> 'select',
            'value'		=> $disable_auto_login,
            'default'		=> '',
            'args'		=> array('yes'=>__('Yes','user-verification'), 'no'=>__('No','user-verification')  ),
        );

        $settings_tabs_field->generate_field($args);


        $args = array(
            'id'		=> 'message_before_header',
            'parent'		=> 'user_verification_settings[ultimate_member]',
            'title'		=> __('Display Message after successfully registration','user-verification'),
            'details'	=> __('You can display custom message at profile header after redirect profile page via Ultimate Member.','user-verification'),
            'type'		=> 'text',
            'value'		=> $message_before_header,
            'default'		=> '',
        );

        $settings_tabs_field->generate_field($args);



        ?>

    </div>

    <?php
}


