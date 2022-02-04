<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );


if ( is_plugin_active( 'ultimate-member/ultimate-member.php' ) ) {
    require_once( user_verification_plugin_dir . 'includes/3rd-party/ultimate-member/settings-hook.php');
    require_once( user_verification_plugin_dir . 'includes/3rd-party/ultimate-member/functions-ultimate-member.php');

}

if ( is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) ) {
    require_once( user_verification_plugin_dir . 'includes/3rd-party/paid-memberships-pro/settings-hook.php');
    require_once( user_verification_plugin_dir . 'includes/3rd-party/paid-memberships-pro/functions-paid-memberships-pro.php');
}

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
    require_once( user_verification_plugin_dir . 'includes/3rd-party/woocommerce/settings-hook.php');
    require_once( user_verification_plugin_dir . 'includes/3rd-party/woocommerce/functions-woocommerce.php');

}


if ( is_plugin_active( 'buddypress/buddypress.php' ) ) {
    require_once( user_verification_plugin_dir . 'includes/3rd-party/buddypress/functions-buddypress.php');

}



if ( is_plugin_active( 'memberpress/memberpress.php' ) ) {
    require_once( user_verification_plugin_dir . 'includes/3rd-party/memberpress/functions-memberpress.php');
}



