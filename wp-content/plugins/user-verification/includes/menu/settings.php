<?php	
if ( ! defined('ABSPATH')) exit;  // if direct access


$current_tab = isset($_REQUEST['tab']) ? sanitize_text_field($_REQUEST['tab']) : 'email_verification';

$user_verification_settings_tab = array();

$user_verification_settings_tab[] = array(
    'id' => 'email_verification',
    'title' => sprintf(__('%s Email Verification','user-verification'),'<i class="far fa-envelope"></i>'),
    'priority' => 1,
    'active' => ($current_tab == 'email_verification') ? true : false,
);

$user_verification_settings_tab[] = array(
    'id' => 'email_otp',
    'title' => sprintf(__('%s  Email OTP','user-verification'),'<i class="fas fa-key"></i>'),
    'priority' => 2,
    'active' => ($current_tab == 'email_otp') ? true : false,
);

//$user_verification_settings_tab[] = array(
//    'id' => 'sms_otp',
//    'title' => sprintf(__('%s  SMS OTP','user-verification'),'<i class="fas fa-sms"></i>'),
//    'priority' => 2,
//    'active' => ($current_tab == 'sms_otp') ? true : false,
//);

$user_verification_settings_tab[] = array(
    'id' => 'spam_protection',
    'title' => sprintf(__('%s Spam Protection','user-verification'),'<i class="fas fa-user-secret"></i>'),
    'priority' => 5,
    'active' => ($current_tab == 'spam_protection') ? true : false,
);

$user_verification_settings_tab[] = array(
    'id' => 'recaptcha',
    'title' => sprintf(__('%s reCAPTCHA','user-verification'),'<i class="fas fa-robot"></i>'),
    'priority' => 10,
    'active' => ($current_tab == 'recaptcha') ? true : false,
);


$user_verification_settings_tab[] = array(
    'id' => 'email_templates',
    'title' => sprintf(__('%s Email Templates','user-verification'),'<i class="fas fa-envelope-open-text"></i>'),
    'priority' => 10,
    'active' => ($current_tab == 'email_templates') ? true : false,
);






//$user_verification_settings_tab[] = array(
//  'id' => 'temp_login',
//  'title' => sprintf(__('%s Temp Login','user-verification'),'<i class="fab fa-keycdn"></i>'),
//  'priority' => 80,
//  'active' => ($current_tab == 'temp_login') ? true : false,
//);



$user_verification_settings_tab[] = array(
    'id' => 'tools',
    'title' => sprintf(__('%s Tools','user-verification'),'<i class="fas fa-magic"></i>'),
    'priority' => 80,
    'active' => ($current_tab == 'tools') ? true : false,
);



$user_verification_settings_tab[] = array(
    'id' => 'help_support',
    'title' => sprintf(__('%s Help & support','user-verification'),'<i class="far fa-question-circle"></i>'),
    'priority' => 90,
    'active' => ($current_tab == 'help_support') ? true : false,
);



//$user_verification_settings_tab[] = array(
//    'id' => 'buy_pro',
//    'title' => sprintf(__('%s Buy Pro','user-verification'),'<i class="fas fa-hands-helping"></i>'),
//    'priority' => 95,
//    'active' => ($current_tab == 'buy_pro') ? true : false,
//);







$user_verification_settings_tab = apply_filters('user_verification_settings_tabs', $user_verification_settings_tab);

$tabs_sorted = array();

if(!empty($user_verification_settings_tab))
foreach ($user_verification_settings_tab as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $user_verification_settings_tab);



$user_verification_settings = get_option('user_verification_settings');

//delete_option('user_verification_settings');



?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><h2><?php echo sprintf(__('%s Settings', 'user-verification'), user_verification_plugin_name)?></h2>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
	        <input type="hidden" name="user_verification_hidden" value="Y">
            <input type="hidden" name="tab" value="<?php echo esc_attr($current_tab); ?>">
            <?php
            if(!empty($_POST['user_verification_hidden'])){
                $nonce = sanitize_text_field($_POST['_wpnonce']);
                if(wp_verify_nonce( $nonce, 'user_verification_nonce' ) && $_POST['user_verification_hidden'] == 'Y') {
                    do_action('user_verification_settings_save');
                    ?>
                    <div class="updated notice  is-dismissible"><p><strong><?php _e('Changes Saved.', 'user-verification' ); ?></strong></p></div>
                    <?php
                }
            }
            ?>
            <div class="settings-tabs-loading" style="">Loading...</div>
            <div class="settings-tabs vertical has-right-panel" style="display: none">
                <div class="settings-tabs-right-panel">
                    <?php
                    if(!empty($user_verification_settings_tab))
                    foreach ($user_verification_settings_tab as $tab) {
                        $id = $tab['id'];
                        $active = $tab['active'];
                        ?>
                        <div class="right-panel-content <?php if($active) echo 'active';?> right-panel-content-<?php echo $id; ?>">
                            <?php
                            do_action('user_verification_settings_tabs_right_panel_'.$id);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <ul class="tab-navs">
                    <?php
                    if(!empty($user_verification_settings_tab))
                    foreach ($user_verification_settings_tab as $tab){
                        $id = $tab['id'];
                        $title = $tab['title'];
                        $active = $tab['active'];
                        $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                        $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                        $is_pro = isset($tab['is_pro']) ? $tab['is_pro'] : false;
                        $pro_text = isset($tab['pro_text']) ? $tab['pro_text'] : '';
                        ?>
                        <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>">
                            <?php echo $title; ?>
                            <?php
                            if($is_pro):
                                ?><span class="pro-feature"><?php echo $pro_text; ?></span> <?php
                            endif;
                            ?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
                if(!empty($user_verification_settings_tab))
                foreach ($user_verification_settings_tab as $tab){
                    $id = $tab['id'];
                    $title = $tab['title'];
                    $active = $tab['active'];
                    ?>
                    <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                        <?php
                        do_action('user_verification_settings_content_'.$id, $tab);
                        ?>
                    </div>
                    <?php
                }
                ?>
                <div class="clear clearfix"></div>
                <p class="submit">
                    <?php wp_nonce_field( 'user_verification_nonce' ); ?>
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes','user-verification' ); ?>" />
                </p>
            </div>
		</form>
</div>