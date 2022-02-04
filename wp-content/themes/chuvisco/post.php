<?php

    $postID = $args['id'] ? $args['id'] : get_the_id();

    global $post; 
    $post = get_post( $postID, OBJECT );
    setup_postdata( $post );

    $postTags = get_the_tags();

    $externalUrl = get_post_meta($postID, 'external_url', true);
    $onlyDomain = parse_url($externalUrl, PHP_URL_HOST);

    date_default_timezone_set('America/Sao_Paulo');
    $humanDate = human_time_diff( strtotime("now"), strtotime(get_the_date('m/d/Y H:i')) );

?>

<article class="post">
    <?php get_template_part('vote', null, array('id' => get_the_id())); ?>
    <div class="infos">
        <div class="title">
            <a href="<?php echo $externalUrl; ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
            <?php foreach($postTags as $t) : ?>
                <span class="tag"><?php echo $t->name; ?></span>
            <?php endforeach; ?>
            <span class="domain"><?php echo $onlyDomain; ?></span>
        </div>
        <div class="data">
            enviado por <?php echo get_the_author_meta('display_name', $post->post_author); ?> há <a href="<?php the_permalink(); ?>"><?php echo $humanDate; ?> atrás</a> | <a href="<?php the_permalink(); ?>"><?php comments_number( 'nenhum comentário', '1 comentário', '% comentários' ); ?></a>
        </div>
    </div>
</article>

<?php wp_reset_postdata(); ?>