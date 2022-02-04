
<?php  get_header();  ?>

<div class="container">

    <?php if(is_search()) : ?>
        <h1 class="page-title">Resultados da busca por "<?php echo get_search_query(); ?>"</h1>
    <?php endif; ?>

    <?php 
        if (have_posts()) :
            while (have_posts()) : the_post();
                get_template_part('post', null, array('id' => get_the_id()));
            endwhile; 
    ?>
    
    <?php wp_pagenavi(); ?>

    <?php else: ?>
        
        <p>Nenhuma publicação encontrada, tente novamente.</p>

    <?php endif; ?>

</div>

<?php get_footer(); ?>