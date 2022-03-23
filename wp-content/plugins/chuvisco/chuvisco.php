<?php

/**
 * Plugin Name: Chuvisco
 * Plugin URI: https://chuvis.co
 * Description: Chuvisco é o plugin para criar um sistema Hacker News-like para o Manual do Usuário.
 * Version: 1.0.0
 * Author: Gabriel Nunes
 * Author URI: https://gnun.es
 **/

function chuvisco_enqueue_styles() {
    wp_enqueue_style( 'chuvisco', plugins_url( '/public/main.css', __FILE__ ) );
}

add_action('wp_enqueue_scripts', 'chuvisco_enqueue_styles');

function chuvisco_enqueue_scripts() {
    wp_enqueue_script( 'chuvisco', plugins_url( '/public/main.min.js', __FILE__ ) );
}

add_action('wp_enqueue_scripts', 'chuvisco_enqueue_scripts');

function chuvisco_setup_post_type() {
    register_post_type(
        'chuvisco_post',
        array(
        'labels' => array(
            'name' => __( 'Chuvisco' ),
            'singular_name' => __( 'Chuvisco' )
        ),
        'public' => true,
        'show_ui' => true,
        'hierarchical' => true,
        'has_archive' => true,
        'supports' => array('title', 'custom-fields', 'author', 'comments', 'editor'),
        'capability_type' => 'post',
        )
    ); 
} 
add_action( 'init', 'chuvisco_setup_post_type' );

/****************** Shortcodes *********************/

function chuvisco_sort_by_points($a, $b) {
    return $a['points'] < $b['points'];
}

function chuvisco_get_vote_html($post_id) {
    $count_key = 'post_like_count';
    $count = get_post_meta($post_id, $count_key, true);

    if (!$count) $count = 0;

    $users_vote_key = 'post_users_vote';
    $users_vote_array = get_post_meta($post_id, $users_vote_key, true);
    $already_voted = false;
    $additional_class = '';

    if($users_vote_array && array_search(get_current_user_id(), $users_vote_array) !== false) $already_voted = true;
    if(is_user_logged_in() && !$already_voted) $additional_class = 'chuvisco-vote-can-vote';
    if($already_voted) $additional_class = 'chuvisco-vote-already-voted';

    $html = '<button class="chuvisco-vote ' . $additional_class . '" data-url="' . admin_url('admin-ajax.php') . '" data-post-id="' . $post_id . '">';
    $html .= '  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M12 0c-4.87 7.197-8 11.699-8 16.075 0 4.378 3.579 7.925 8 7.925s8-3.547 8-7.925c0-4.376-3.13-8.878-8-16.075z"/></svg>';
    $html .= '  <span>' . $count . '</span>';
    $html .= '</button>';

    return $html;
}

function chuvisco_get_post_html($post_id) {
    global $post; 
    $post = get_post( $post_id, OBJECT );
    setup_postdata( $post );

    $in_english = get_post_meta($post_id, 'in_english', true);
    $external_url = get_post_meta($post_id, 'external_url', true);
    if (!$external_url) $external_url = get_permalink();
    $only_domain = parse_url($external_url, PHP_URL_HOST);

    date_default_timezone_set('America/Sao_Paulo');
    $human_date = human_time_diff( strtotime("now"), strtotime(get_the_date('m/d/Y H:i')) );
    
    
    $html = '<article class="chuvisco-post">';
    $html .= chuvisco_get_vote_html($post_id);
    $html .= '  <div class="chuvisco-post-infos">';
    $html .= '    <div class="chuvisco-post-title">';
    $html .= '          <a href="' . $external_url . '" title="' . get_the_title() . '">';
    $html .= '              ' . get_the_title();
    $html .= '          </a>';
    $html .= '          <div class="chuvisco-post-info">';

    if ($in_english) :
        $html .= '          <span class="chuvisco-post-tag">em inglês</span>';
    endif;

    $html .= '              <span class="chuvisco-post-domain">' . $only_domain . '</span>';
    $html .= '          </div>';
    $html .= '          <div class="chuvisco-post-date">';
    $html .= '              enviado por ' . get_the_author_meta('display_name', $post->post_author) . ' há <a href="' . get_permalink() . '">' . $human_date . ' atrás</a> | <a href=" ' . get_permalink() . '">' . get_comments_number_text( 'nenhum comentário', '1 comentário', '% comentários' ) . '</a>';
    $html .= '          </div>';
    $html .= '      </div>';
    $html .=    '</div>';
    $html .= '</article>';

    return $html;
}

function chuvisco_ranking_shortcode($atts = [], $content = null, $tag = '') {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $chuvisco_rank_atts = shortcode_atts(
        array(
            'days' => '5',
            'vote-points' => 1,
            'comment-points' => 2,
        ), $atts, $tag
    );

    $posts_array = array();

    $args = array(
        'post_type'         => 'chuvisco_post',
        'posts_per_page'    => -1,
        'date_query'        => array(
            'after'    => $chuvisco_rank_atts['days'] . " days ago"
        )
    );

    $query = new WP_Query( $args );

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $today = new DateTime('NOW');
            $post_date = new DateTime(get_the_date("Y-m-d H:i:s"));
    
            $difference = $today->diff($post_date);
            $difference_days = $difference->d * 24;
            $difference_hours = $difference->h;
            $difference_minutes = $difference->i / 60;
    
            $time_elapsed = $difference_days + $difference_hours + $difference_minutes;
    
            $points_comments = get_comments_number();
            $points_votes = get_post_meta(get_the_id(), 'post_like_count', true);
            $total_points = ($points_comments * $chuvisco_rank_atts['comment-points']) + ($points_votes * $chuvisco_rank_atts['vote-points']);
    
            if ($total_points > 0)  {
                $total_points = $total_points - ($time_elapsed / 10);
    
                $posts_array[] = array(
                    'id' => get_the_id(),
                    'points' => $total_points
                );
            }
        endwhile; 
    
        usort($posts_array, 'chuvisco_sort_by_points');
        $posts_array = array_slice($posts_array, 0, 30);
    endif;

    $html = '<div class="chuvisco-ranking">';

    foreach($posts_array as $post) {
        $html .= chuvisco_get_post_html($post['id']);
    }

    $html .= '</div>';
    
    return $html;
}

function chuvisco_form_shortcode() {
    // do something to $content
    // always return
    return "yeaah";
}

function chuvisco_shortcodes_init() {
    add_shortcode( 'chuvisco-form', 'chuvisco_form_shortcode' );
    add_shortcode( 'chuvisco-ranking', 'chuvisco_ranking_shortcode' );
}
 
add_action( 'init', 'chuvisco_shortcodes_init' );

/****************** Ativação e desativação do plugin *********************/

function chuvisco_activate() { 
    chuvisco_setup_post_type(); 
    chuvisco_shortcodes_init();
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'chuvisco_activate' );

function chuvisco_deactivate() {
    unregister_post_type( 'chuvisco_post' );
    remove_shortcode('chuvisco');
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'chuvisco_deactivate' );

/****************** Função AJAX  *********************/

function chuvisco_increase_post_like($post_id) {
    if (!is_user_logged_in()) return false;

    $users_vote_key = 'post_users_vote';
    $users_vote_array = get_post_meta($post_id, $users_vote_key, true);

    if($users_vote_array && array_search(get_current_user_id(), $users_vote_array) !== false) return false;

    if (!$users_vote_array) {
        $users_vote_array = array(get_current_user_id());
        delete_post_meta($post_id, $users_vote_key);
        add_post_meta($post_id, $users_vote_key, $users_vote_array);
    } else {
        $users_vote_array[] = get_current_user_id();
        update_post_meta($post_id, $users_vote_key, $users_vote_array);
    }

    $count_key = 'post_like_count';
    $count = get_post_meta($post_id, $count_key, true);

    if($count==''){
        $count = 1;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '1');
    } else{
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }

    return $count;
}

function chuvisco_update_post_likes()
{
    if(!is_user_logged_in())
        return;

    if(!$_POST)
        return;

    $count = chuvisco_increase_post_like($_POST['post_id']);

    if ($count) {
        echo json_encode(array('success' => true, 'count' => $count));
    } else {
        echo json_encode(array('success' => false));
    }

    wp_die();
}

add_action('wp_ajax_nopriv_chuvisco_update_post_likes', 'chuvisco_update_post_likes');
add_action('wp_ajax_chuvisco_update_post_likes', 'chuvisco_update_post_likes');

?>