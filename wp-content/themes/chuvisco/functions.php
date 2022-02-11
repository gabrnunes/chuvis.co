<?php
/*
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);
ini_set('error_log','script_errors.log');
ini_set('log_errors','On');*/

show_admin_bar(false);

function increase_post_like($postID) {
    if (!is_user_logged_in()) return false;

    $users_vote_key = 'post_users_vote';
    $users_vote_array = get_post_meta($postID, $users_vote_key, true);

    if($users_vote_array && array_search(get_current_user_id(), $users_vote_array) !== false) return false;

    if (!$users_vote_array) {
        $users_vote_array = array(get_current_user_id());
        delete_post_meta($postID, $users_vote_key);
        add_post_meta($postID, $users_vote_key, $users_vote_array);
    } else {
        $users_vote_array[] = get_current_user_id();
        update_post_meta($postID, $users_vote_key, $users_vote_array);
    }

    $count_key = 'post_like_count';
    $count = get_post_meta($postID, $count_key, true);

    if($count==''){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '1');
    } else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }

    return $count;
}

function update_post_likes()
{
    if(!is_user_logged_in())
        return;

    if(!$_POST)
        return;

    $count = increase_post_like($_POST['post_id']);

    if ($count) {
        echo json_encode(array('success' => true, 'count' => $count));
    } else {
        echo json_encode(array('success' => false));
    }

    wp_die();
}

add_action('wp_ajax_nopriv_update_post_likes', 'update_post_likes');
add_action('wp_ajax_update_post_likes', 'update_post_likes');

function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

$required_capability = 'edit_others_posts';
$redirect_to = '';
function no_admin_init() {      
    // We need the config vars inside the function
    global $required_capability, $redirect_to;      
    // Is this the admin interface?
    if (
        // Look for the presence of /wp-admin/ in the url
        stripos($_SERVER['REQUEST_URI'],'/wp-admin/') !== false
        &&
        // Allow calls to async-upload.php
        stripos($_SERVER['REQUEST_URI'],'async-upload.php') == false
        &&
        // Allow calls to admin-ajax.php
        stripos($_SERVER['REQUEST_URI'],'admin-ajax.php') == false
    ) {         
        // Does the current user fail the required capability level?
        if (!current_user_can($required_capability)) {              
            if ($redirect_to == '') { $redirect_to = get_option('home'); }              
            // Send a temporary redirect
            wp_redirect($redirect_to,302);              
        }           
    }       
}
// Add the action with maximum priority
add_action('init','no_admin_init',0);

function my_login_stylesheet() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/dist/css/login.css' );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );