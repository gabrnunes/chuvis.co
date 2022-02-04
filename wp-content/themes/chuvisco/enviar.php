<?php 

if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

if ( $_POST && isset($_POST['title']) ) {

    $post = array(
        'post_title'    => $_POST['title'],
        'tags_input'    => $_POST['english'] ? "em-ingles" : "",
        'meta_input'    => array(
            'external_url' => $_POST['url'],
        ),
        'post_status'   => 'publish',
        'post_type' 	=> 'post'
    );
    $post_id = wp_insert_post($post);

    if ( $post_id ) {
        wp_redirect(home_url('/p/'.$post_id));
        exit;
    }
}

/* Template Name: Enviar */ 

?>

<?php get_header(); ?>

<div class="container">
    <h1 class="page-title">Envie um link</h1>

    <form id="new_post" name="new_post" method="post"  enctype="multipart/form-data">

    <div class="form-control">
        <label for="title">título</label><br>
        <input required="required" type="text" id="title" value="<?php echo $_GET['t']; ?>" tabindex="1" size="20" name="title" /><br>
        <span class="help-block">o título deve estar em português.</span>
    </div> 
    <div class="form-control">
        <label for="url">url</label><br>
        <input required="required" type="url" id="url" placeholder="https://" value="<?php echo $_GET['u']; ?>" tabindex="1" size="20" name="url" /><br>
        <span class="help-block">qual link você quer compartilhar?</span>
    </div>
    <div class="form-control">
        <label><input type="checkbox" name="english"> O texto do link está em inglês</label>
    </div>
    <input type="submit" value="Publicar" tabindex="6" id="submit" name="submit" />
        
    </form>

    <div class="bookmarklet">
        Se preferir, pode usar nosso bookmarklet! Arraste o botão abaixo para a sua barra de favoritos e clique nele quando quiser compartilhar um link.<br>
        <a onclick="return false" href="javascript:window.location=%22https://chuvis.co/enviar?u=%22+encodeURIComponent(document.location)+%22&t=%22+encodeURIComponent(document.title)">postar no chuvisco</a>
    </div>
</div>

<?php get_footer(); ?>