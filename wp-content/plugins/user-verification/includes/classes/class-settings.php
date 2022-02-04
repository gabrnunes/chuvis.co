<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class class_user_verification_settings{

	public function __construct(){
		
		add_action('admin_menu', array( $this, '_menu_init' ));
		
		}


    public function _menu_init() {

        add_submenu_page( 'users.php', __( 'User Verification', 'user-verification' ), __( 'User Verification', 'user-verification' ), 'manage_options', 'user_verification', array( $this, 'settings' ) );


    }

	public function settings(){
		include(user_verification_plugin_dir.'includes/menu/settings.php');
	}


	
}
	
new class_user_verification_settings();


