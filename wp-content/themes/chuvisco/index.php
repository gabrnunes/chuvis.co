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

            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

            previous_posts_link( '&laquo; mais recentes' );
            if ($paged > 1) echo "&nbsp;&nbsp;";
            next_posts_link( 'mais antigos &raquo;', $the_query->max_num_pages );
    ?>
    

    <?php else: ?>
        
        <p>Nenhuma publicação encontrada, tente novamente.</p>

    <?php endif; ?>

</div>

<?php get_footer(); ?>