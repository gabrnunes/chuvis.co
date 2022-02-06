<?php get_header(); ?>

<div class="container">
    <h1 class="page-title"><?php the_title(); ?></h1>

    <?php the_content(); ?>

    <p class="last-updated">Última atualização: <?php echo get_the_modified_time('d/m/Y'); ?></p>
</div>

<?php get_footer(); ?>