<?php



if (!defined('ABSPATH')) exit;  // if direct access 


class class_user_verification_users_columns
{

    public function __construct()
    {

        add_filter('manage_users_custom_column', array($this, '_users_columns_display'), 10, 3);
        add_filter('manage_users_columns', array($this, '_users_columns'));
    }

    public function _users_columns($columns)
    {


        $user_verification_settings = get_option('user_verification_settings');
        $email_verification_enable = isset($user_verification_settings['email_verification']['enable']) ? $user_verification_settings['email_verification']['enable'] : 'yes';


        // $new_columns 	= array();
        // $count 			= 0;

        // foreach( $columns as $column_key => $column_title ){
        //     $count++;

        // 	if( $count == 3 ) $new_columns[ 'verification_status' ] = __('Verification Status', 'user-verification');
        // 	else $new_columns[ $column_key ] = $column_title;
        // }

        if ($email_verification_enable == 'yes') {
            $columns['verification_status'] = __('Email Verification', 'user-verification');
        }

        return $columns;
    }

    public function _users_columns_display($val, $column_name, $user_id)
    {


        if ($column_name == 'verification_status') {

            ob_start();

            $user_activation_status = get_user_meta($user_id, 'user_activation_status', true);
            $user_activation_status = empty($user_activation_status) ? 0 : $user_activation_status;
            $uv_status                 = $user_activation_status == 1 ? __('Verified', 'user-verification') : __('Unverified', 'user-verification');
            $activation_key = get_user_meta($user_id, 'user_activation_key', true);

?>
            <div class='uv_status status-<?php echo esc_attr($user_activation_status); ?>'>
                <?php echo esc_html($uv_status); ?>
            </div>
            <div class='row-actions'>
                <?php

                $actionurl = admin_url() . 'users.php';

                if ($user_activation_status == 0) {

                    $mark_as_verified_url = add_query_arg(
                        array(
                            'user_id' => $user_id,
                            'mark_as_verified' => 'yes',
                        ),
                        $actionurl
                    );

                    $mark_as_verified_url = wp_nonce_url($mark_as_verified_url,  'mark_as_verified');


                    $resend_verification_url = add_query_arg(
                        array(
                            'user_id' => $user_id,
                            'resend_verification' => 'yes',
                        ),
                        $actionurl
                    );

                    $resend_verification_url = wp_nonce_url($resend_verification_url,  'resend_verification');


                ?>

                    <span class="mark_as_verified">
                        <a href="<?php echo esc_url_raw($mark_as_verified_url); ?>"><?php echo __('Mark as Verified', 'user-verification'); ?></a>
                    </span> |
                    <span class="resend_verification">
                        <a href="<?php echo esc_url_raw($resend_verification_url); ?>"><?php echo __('Resend verification', 'user-verification'); ?></a>
                    </span>
                <?php

                }

                if ($user_activation_status == 1) {

                    $mark_as_unverified_url = add_query_arg(
                        array(
                            'user_id' => $user_id,
                            'mark_as_unverified' => 'yes',
                        ),
                        $actionurl
                    );

                    $mark_as_unverified_url = wp_nonce_url($mark_as_unverified_url,  'mark_as_unverified');

                ?>
                    <span class="mark_as_unverified">
                        <a href="<?php echo esc_url_raw($mark_as_unverified_url); ?>"><?php echo __('Mark as unverified', 'user-verification'); ?></a>
                    </span>
                <?php
                }
                ?>
                <span class='activation_key' title="<?php echo __('Activation key', 'user-verification'); ?>"><?php
                                                                                                                echo esc_html($activation_key); ?></span>
            </div>

<?php

            return ob_get_clean();
        } else {
            return $val;
        }
    }
}

new class_user_verification_users_columns();
