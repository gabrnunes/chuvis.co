<?php get_header(); ?>

<div class="container mt-5 mb-50">
    <h2 class="m-0"><?php the_title(); ?></h2>
</div>


<div class="container my-5">
    <div class="blog-post post-interno">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>