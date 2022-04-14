<?php

/**
 * Plugin Name: Órbita
 * Plugin URI: https://gnun.es
 * Description: Órbita é o plugin para criar um sistema Hacker News-like para o Manual do Usuário.
 * Version: 1.0.0
 * Author: Gabriel Nunes
 * Author URI: https://gnun.es
 **/

function orbita_enqueue_styles() {
    wp_enqueue_style( 'orbita', plugins_url( '/public/main.css', __FILE__ ) );
}

add_action('wp_enqueue_scripts', 'orbita_enqueue_styles');

function orbita_enqueue_scripts() {
    wp_enqueue_script( 'orbita', plugins_url( '/public/main.min.js', __FILE__ ) );
}

add_action('wp_enqueue_scripts', 'orbita_enqueue_scripts');

function orbita_setup_post_type() {
    register_post_type(
        'orbita_post',
        array(
        'labels' => array(
            'name' => __( 'Órbita' ),
            'singular_name' => __( 'Órbita' )
        ),
        'public' => true,
        'show_ui' => true,
        'hierarchical' => true,
        'has_archive' => false,
        'supports' => array('title', 'custom-fields', 'author', 'comments', 'editor'),
        'capability_type' => 'post',
        'exclude_from_search' => true,
        'rewrite' => array('slug' => 'orbita-post'),
        )
    );
    
    register_taxonomy(
		'orbita_category',
		array('orbita_post'),
		array(
			'labels' => array(
                'name' => __( 'Categorias' ),
                'singular_name' => __( 'Categoria' )
            ),
			'rewrite' => array( 'slug' => 'categoria' ),
			'hierarchical' => true
		)
	);
} 
add_action( 'init', 'orbita_setup_post_type' );

/****************** Templates **********************/

function load_orbita_template( $template ) {
    global $post;

    if ( 'orbita_post' === $post->post_type && locate_template( array( 'single-orbita_post.php' ) ) !== $template ) {
        return plugin_dir_path( __FILE__ ) . 'single-orbita.php';
    }

    return $template;
}

add_filter( 'single_template', 'load_orbita_template' );

/****************** Shortcodes *********************/

function orbita_sort_by_points($a, $b) {
    return $a['points'] < $b['points'];
}

function orbita_get_vote_html($post_id) {
    $users_vote_key = 'post_users_vote';
    $users_vote_array = get_post_meta($post_id, $users_vote_key, true);
    $already_voted = false;
    $additional_class = '';

    if($users_vote_array && array_search(get_current_user_id(), $users_vote_array) !== false) $already_voted = true;
    if(is_user_logged_in() && !$already_voted) $additional_class = 'orbita-vote-can-vote';
    if($already_voted) $additional_class = 'orbita-vote-already-voted';

    $html = '<button title="Votar" class="orbita-vote ' . $additional_class . '" data-url="' . admin_url('admin-ajax.php') . '" data-post-id="' . $post_id . '">';
    $html .= '  Votar';
    $html .= '</button>';

    return $html;
}

function orbita_get_header_html() {
    $html = '<div class="orbita-header">';
    $html .= '  <a href="/orbita/postar/" class="orbita-post-button">Postar</a>';
    $html .= '  <a href="/orbita">Capa</a>';
    $html .= '  <a href="/orbita/guia-de-uso">Guia de uso</a>';
    $html .= '  <a href="/orbita/arquivo">Arquivo</a>';
    $html .= '</div>';

    return $html;
}

function orbita_get_post_html($post_id) {
    global $post; 
    $post = get_post( $post_id, OBJECT );
    setup_postdata( $post );

    $external_url = get_post_meta($post_id, 'external_url', true);
    if (!$external_url) $external_url = get_permalink();
    $only_domain = parse_url($external_url, PHP_URL_HOST);
    $count_key = 'post_like_count';
    $count = get_post_meta($post_id, $count_key, true);

    if (!$count) $count = "nenhum";

    date_default_timezone_set('America/Sao_Paulo');
    $human_date = human_time_diff( strtotime("now"), strtotime(get_the_date('m/d/Y H:i')) );

    $votes_text = $count > 1 ? 'votos' : 'voto';

    $html = '<article class="orbita-post">';
    $html .= orbita_get_vote_html($post_id);
    $html .= '  <div class="orbita-post-infos">';
    $html .= '    <div class="orbita-post-title">';
    $html .= '          <a href="' . $external_url . '" rel="ugc" title="' . get_the_title() . '">' . get_the_title() . '</a>';
    $html .= '          <div class="orbita-post-info">';
    $html .= '              <span class="orbita-post-domain">' . $only_domain . '</span>';
    $html .= '          </div>';
    $html .= '          <div class="orbita-post-date">';
    $html .= '              <span data-votes-post-id="' . $post_id . '">' . $count . ' </span> ' . $votes_text . ' | por ' . get_the_author_meta('display_name', $post->post_author) . ' ' . $human_date . ' atrás | <a href=" ' . get_permalink() . '">' . get_comments_number_text( 'sem comentários', '1 comentário', '% comentários' ) . '</a>';
    $html .= '          </div>';
    $html .= '      </div>';
    $html .=    '</div>';
    $html .= '</article>';

    return $html;
}

function orbita_ranking_shortcode($atts = [], $content = null, $tag = '') {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );

    $orbita_rank_atts = shortcode_atts(
        array(
            'days' => '5',
            'vote-points' => 1,
            'comment-points' => 2,
        ), $atts, $tag
    );

    $posts_array = array();

    $args = array(
        'post_type'         => 'orbita_post',
        'posts_per_page'    => -1,
        'date_query'        => array(
            'after'    => $orbita_rank_atts['days'] . " days ago"
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
            $invisible_votes = get_post_meta(get_the_id(), 'invisible_votes', true);
            $total_points = ($points_comments * $orbita_rank_atts['comment-points']) + ($points_votes * $orbita_rank_atts['vote-points']) + ($invisible_votes * $orbita_rank_atts['vote-points']);
    
            if ($total_points > 0)  {
                $total_points = $total_points - ($time_elapsed / 10);
    
                $posts_array[] = array(
                    'id' => get_the_id(),
                    'points' => $total_points
                );
            }
        endwhile; 
    
        usort($posts_array, 'orbita_sort_by_points');
        $posts_array = array_slice($posts_array, 0, 30);
    endif;

    $html = '<div class="orbita-ranking">';

    $html .= orbita_get_header_html();

    foreach($posts_array as $post) {
        $html .= orbita_get_post_html($post['id']);
    }

    $html .= '</div>';
    
    return $html;
}

function orbita_posts_shortcode($atts = [], $content = null, $tag = '') {
    $atts = array_change_key_case( (array) $atts, CASE_LOWER );
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $html = orbita_get_header_html();

    $orbita_posts_atts = shortcode_atts(
        array(
            'latest' => false
        ), $atts, $tag
    );

    $args = array(
        'post_type'         => 'orbita_post',
        'posts_per_page'    => 30,
        'paged'             => $paged,
    );

    if ($orbita_posts_atts['latest'] == true) {
        $args['date_query'] = array(
            'after'    => "2 days ago"
        );
    }

    $query = new WP_Query( $args );

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $html .= orbita_get_post_html(get_the_id());
        endwhile; 

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $html .= get_previous_posts_link( '&laquo; mais recentes' );
        if ($paged > 1) $html .= "&nbsp;&nbsp;";
        $html .= get_next_posts_link( 'mais antigos &raquo;', $query->max_num_pages );
    endif;
    
    return $html;
}

function orbita_form_shortcode() {
    if (!is_user_logged_in()) {
        $html = 'Para iniciar debates na Órbita, <a href="' . wp_login_url(home_url( '/orbita/postar' )) . '">faça login</a> ou <a href="' . wp_registration_url() . '">cadastre-se gratuitamente</a>.';
        return $html;
    }

    if ( $_POST && isset($_POST['orbita_post_title']) ) {

        $alreadyPosted = get_page_by_title($_POST['orbita_post_title'], OBJECT, 'orbita_post');
    
        if($alreadyPosted->ID && $alreadyPosted->post_author == get_current_user_id()) {
            $html = 'Parece que este post <a href="'.home_url('/?p='.$alreadyPosted->ID).'">já existe</a>.';
            return $html;
        }

        $default_category = get_term_by('slug', 'link', 'orbita_category');
    
        $post = array(
            'post_title'    => $_POST['orbita_post_title'],
            'post_content'  => $_POST['orbita_post_content'],
            'tax_input'     => array(
                'orbita_category' => array( $default_category->term_id )
            ),
            'meta_input'    => array(
                'external_url' => $_POST['orbita_post_url'],
            ),
            'post_status'   => 'publish',
            'post_type' 	=> 'orbita_post'
        );
        $post_id = wp_insert_post($post);

        orbita_increase_post_like($post_id);

        $html = orbita_get_header_html();

        $html .= 'Tudo certo! Agora você pode <a href="'.home_url('/?p='.$post_id).'">acessar seu post</a>.';
        
        return $html;
    }

    $html = orbita_get_header_html();

    $html .= '<div class="orbita-form">';
    $html .= '  <form id="new_post" name="new_post" method="post"  enctype="multipart/form-data">';
    $html .= '      <div class="orbita-form-control">';
    $html .= '          <label for="orbita_post_title">Título</label>';
    $html .= '          <input required type="text" id="orbita_post_title" name="orbita_post_title" value="'. $_GET['t'] .'" placeholder="Prefira títulos em português">';
    $html .= '      </div>';
    $html .= '      <div class="orbita-form-control"><br>';
    $html .= '          <p>Deixe o link vazio para iniciar uma discussão (que pode ser uma dúvida, por exemplo). Se você enviar um comentário ele irá aparecer no topo.</p>';
    $html .= '      </div>';
    $html .= '      <div class="orbita-form-control">';
    $html .= '          <label for="orbita_post_url">Link</label>';
    $html .= '          <input type="url" id="orbita_post_url" name="orbita_post_url" placeholder="https://" value="'. $_GET['u'] .'">';
    $html .= '      </div>';
    $html .= '      <div class="orbita-form-control">';
    $html .= '          <label for="orbita_post_content">Comentário</label>';
    $html .= '          <textarea rows="5" id="orbita_post_content" name="orbita_post_content"></textarea>';
    $html .= '      </div>';
    $html .= '      <div class="orbita-form-control">';
    $html .= '          <p>Antes de postar, leia nossas <a href="https://manualdousuario.net/doc-comentarios/" target="_blank" rel="noreferrer noopener">dicas e orientações para comentários</a>.</p>';
    $html .= '      </div>';
    $html .= '      <input type="submit" value="Publicar">';
    $html .= '  </form>';
    $html .= '</div>';

    $html .= '<div class="orbita-bookmarklet ctx-atencao">';
    $html .= '  Se preferir, pode usar nosso bookmarklet! Arraste o botão abaixo para a sua barra de favoritos e clique nele quando quiser compartilhar um link.<br>';
    $html .= '  <a onclick="return false" href="javascript:window.location=%22https://manualdousuario.net/orbita/postar?u=%22+encodeURIComponent(document.location)+%22&t=%22+encodeURIComponent(document.title)">postar no orbita</a>';
    $html .= '</div>';

    return $html;
}

function orbita_header_shortcode() {
    $html = orbita_get_header_html();
    return $html;
}

function orbita_vote_shortcode() {
    $html = orbita_get_vote_html(get_the_ID());
    return $html;
}

function orbita_shortcodes_init() {
    add_shortcode( 'orbita-form', 'orbita_form_shortcode' );
    add_shortcode( 'orbita-ranking', 'orbita_ranking_shortcode' );
    add_shortcode( 'orbita-posts', 'orbita_posts_shortcode' );
    add_shortcode( 'orbita-header', 'orbita_header_shortcode' );
    add_shortcode( 'orbita-vote', 'orbita_vote_shortcode' );
}
 
add_action( 'init', 'orbita_shortcodes_init' );

/****************** Ativação e desativação do plugin *********************/

function orbita_activate() { 
    orbita_setup_post_type(); 
    orbita_shortcodes_init();
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'orbita_activate' );

function orbita_deactivate() {
    unregister_post_type( 'orbita_post' );
    remove_shortcode('orbita');
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'orbita_deactivate' );

/****************** Função AJAX  *********************/

function orbita_increase_post_like($post_id) {
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

function orbita_update_post_likes()
{
    if(!is_user_logged_in())
        return;

    if(!$_POST)
        return;

    $count = orbita_increase_post_like($_POST['post_id']);

    if ($count) {
        echo json_encode(array('success' => true, 'count' => $count));
    } else {
        echo json_encode(array('success' => false));
    }

    wp_die();
}

add_action('wp_ajax_nopriv_orbita_update_post_likes', 'orbita_update_post_likes');
add_action('wp_ajax_orbita_update_post_likes', 'orbita_update_post_likes');

?>