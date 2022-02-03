<?php

$id = $args['id'] ? $args['id'] : get_the_id();
$className = $args['className'] ? $args['className'] : "col-sm-6 col-lg";

$imagem = get_bloginfo('template_directory') . "/dist/images/padrao.jpg";

if(has_post_thumbnail()) {
    $imagem = aq_resize(get_the_post_thumbnail_url($id, 'large'), 635, 635, true);
}

$posttags = get_the_tags();

?>

<div class="preview-post-blog <?php echo $className; ?>">
    <a class="blog-post-img" href="<?php the_permalink(); ?>">
        <img src="<?php echo $imagem; ?>" class="img-fluid" alt="<?php the_title(); ?>">
    </a>
    <div class="tags mt-3">
        <?php foreach($posttags as $t) : ?>
            <span class="text-lowercase"><?php echo $t->name; ?></span>
        <?php endforeach; ?>
    </div>
    <h3 class="mt-2">
        <a href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
    </h3>
    <div class="autor">
        <i class="fas fa-user"></i>
        <?php echo get_the_author_meta('display_name', $post->post_author); ?> | <?php echo get_the_date('d.M.Y'); ?>
    </div>
</div>