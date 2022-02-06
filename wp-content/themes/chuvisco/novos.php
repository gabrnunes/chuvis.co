<?php  

/* Template Name: Novos */ 

get_header();  ?>

<div class="container">

    <h1 class="page-title">Últimos links enviados</h1>

    <?php 
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $args = array(
            'post_type'         => 'post',
            'posts_per_page'    => 30,
            'paged'             => $paged,
            'date_query'        => array(
                'after'    => "2 days ago"
            )
        );
        $query = new WP_Query( $args );

        global $wp_query;
        // Put default query object in a temp variable
        $tmp_query = $wp_query;
        // Now wipe it out completely
        $wp_query = null;
        // Re-populate the global with our custom query
        $wp_query = $query;

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                get_template_part('post', null, array('id' => get_the_id()));
            endwhile; 

            print_r($the_query->max_num_pages);
    
            previous_posts_link( '&laquo; mais recentes' );
            if ($paged > 1) echo "&nbsp;&nbsp;";
            next_posts_link( 'mais antigos &raquo;', $the_query->max_num_pages );
            wp_reset_postdata();

        endif; 
    
        // Restore original query object
        $wp_query = null;
        $wp_query = $tmp_query;
    
    ?>

</div>

<?php get_footer(); ?>