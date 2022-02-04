<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 




function validate_email_edu(){

    global $bp;

    $email = $bp->signup->email;

    if ($email){


        $is_blocked = user_verification_is_emaildomain_blocked($email);


        $email_parts = explode('@', $email);
        $email_domain = isset($email_parts[1]) ? $email_parts[1] : '';

//    error_log('$is_blocked:'. $is_blocked);
//    error_log('$is_blocked:'. $email_domain);


        if($is_blocked){
            //$errors[] = sprintf(__( 'This %s domain is blocked!', 'user-verification' ), '<strong>'.$email_domain.'</strong>');
            $bp->signup->errors['signup_email'] = sprintf(__( 'This %s domain is blocked!', 'user-verification' ), esc_url_raw($email_domain));
        }



        $is_allowed = user_verification_is_emaildomain_allowed($email);



        if(!$is_allowed){
            //$errors[] = sprintf(__( 'This %s domain is not allowed!', 'user-verification' ), '<strong>'.$email_domain.'</strong>');
            $bp->signup->errors['signup_email'] = sprintf(__( 'This %s domain is not allowed!', 'user-verification' ), esc_url_raw($email_domain));
        }




    }


    $username = $bp->signup->username;

    if ($username){

        $username_blocked = user_verification_is_username_blocked($username);

        if($username_blocked){
            $bp->signup->errors['signup_username'] = sprintf(__( 'This %s username is not allowed!', 'user-verification' ), esc_html($username));

        }

    }






}

add_action('bp_signup_validate','validate_email_edu');




function bp_core_signup_user_uv( $user_id, $user_login, $user_password, $user_email, $usermeta) {

    user_verification_user_registered( $user_id );

}
add_action( "bp_core_signup_user", "bp_core_signup_user_uv", 10, 5 );




// add the column data for each row
function bp_members_signup_columns_uv( $arr ) {

    if(!is_multisite()){
        $arr['uv_bp'] = __('Verification Status', 'user-verification');

    }

    return $arr;

}
add_filter( "bp_members_signup_columns", "bp_members_signup_columns_uv", 10 );

function bp_members_signup_custom_column_uv_bp( $val, $column_name, $signup_object ) {

    //error_log(serialize($signup_object));

    $id = $signup_object->id;

    global $wpdb;

    if(is_multisite()){
        $table = $wpdb->base_prefix . "signups";
        $meta_data	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE signup_id = %s", $id ) );

    }else{
        $table = $wpdb->prefix . "bp_xprofile_data";
        $meta_data	= $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %s", $id ) );

    }



    //var_dump($table);
    //var_dump($id);

    $user_id = isset($meta_data->user_id) ? $meta_data->user_id : '';

    //var_dump($user_id);

    $this_user		= get_user_by( 'id', $user_id );

    if( $column_name == 'uv_bp' ) {

        ob_start();
        $user_activation_status = get_user_meta( $user_id, 'user_activation_status', true );
        $user_activation_status = empty( $user_activation_status ) ? 0 : $user_activation_status;
        $uv_status 				= $user_activation_status == 1 ? __('Verified', 'user-verification') : __('Unverified', 'user-verification');
        $activation_key = get_user_meta( $user_id, 'user_activation_key', true );


        ?>
        <div class='uv_status'><?php echo esc_html($uv_status); ?></div>
        <div class='row-actions'>
        <?php


        if( $user_activation_status == 0 ) {

            ?>
            <span class="uv_action uv_approve" user_id="<?php echo esc_attr($user_id); ?>" do="approve"><?php __('Mark as verified', 'user-verification'); ?>></span>
            <?php
        }

        if( $user_activation_status == 1 ) {

            ?>
            <span class="uv_action uv_remove_approval" user_id="<?php echo esc_attr($user_id); ?>" do="remove_approval"><?php __('Mark as unverified', 'user-verification'); ?>></span>
            <?php
        }

        ?>
        <span class='activation_key' > <?php echo  esc_html($activation_key); ?></span>
    </div>
    <?php

        return ob_get_clean();
    }


}
add_filter( "bp_members_signup_custom_column", "bp_members_signup_custom_column_uv_bp", 10, 3 );

