<?php

get_header();

$post_ID = get_the_ID();

?>

<div class="container">
    <?php get_template_part('post', null, array('id' => $post_ID)); ?>
    <?php comments_template(); ?> 
</div>



<?php get_footer(); ?>