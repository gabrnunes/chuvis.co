<?php

get_header();

wpb_set_post_views(get_the_ID());

get_template_part('header-blog');

?>

<div class="botoes-post botoes-post-mobile d-md-none">
    <?php echo do_shortcode('[wp_ulike for="post" id="'.get_the_id().'" style="wpulike-robeen"]'); ?>

    <a href="#" id="abrir-comentarios">
        <i class="fas fa-comment"></i>
        <span><?php echo get_comments_number(); ?></span>
    </a>

    <div class="addthis_inline_share_toolbox"></div>
</div>

<div class="container">


    <div class="row">

        <div class="col-lg-8">
            <section id="blog">

<?php

if (have_posts()) :

    while (have_posts()) : the_post();

        $views = get_post_meta(get_the_ID(), 'wpb_post_views_count', true);

        $category = get_the_category();

        $mycontent = $post->post_content; // wordpress users only
        $word = str_word_count(strip_tags($mycontent));
        $m = floor($word / 200);
        if($m == 0) {
            $est = 'menos de 1 minuto de leitura';
        } elseif ($m == 1) {
            $est = '1 minuto de leitura';
        } else {
            $est = $m . ' minutos de leitura';
        }
        
        date_default_timezone_set('America/Sao_Paulo');
        $data_humana = human_time_diff( strtotime("now"), strtotime(get_the_date('m/d/Y H:i')) );


        ?>


            <div class="blog-post post-interno">
                <div class="row">
                    <div class="col-md-2 col-xl-1 text-center d-none d-md-block">
                        <div class="botoes-post mb-4 mb-md-0">
                            <?php echo do_shortcode('[wp_ulike for="post" id="'.get_the_id().'" style="wpulike-robeen"]'); ?>

                            <a href="#" id="abrir-comentarios">
                                <i class="fas fa-comment"></i>
                                <span><?php echo get_comments_number(); ?></span>
                            </a>

                            <div class="addthis_inline_share_toolbox"></div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h1><?php the_title(); ?></h1>
                    
                        <div class="dados-autor">
                            <?php 
                            $author_id = get_the_author_meta('ID');
                            echo get_avatar($author_id, 64);
                            ?>

                            <div class="meta">
                                <strong><?php echo get_the_author_meta('display_name', $post->post_author); ?></strong><br>
                                Há <?php echo $data_humana ?> &bullet; <?php echo $est; ?>
                            </div>
                        </div>

                        <?php if(has_post_thumbnail()) { $imagem = aq_resize(get_the_post_thumbnail_url(get_the_id(), 'large'), 830, 420, true); ?>
                            <img class="my-5" src="<?php echo $imagem; ?>" width="100%" alt="<?php the_title(); ?>">
                        <?php } ?>

                        <?php the_content(); ?>

                        
                        <div class="tags mt-5">
                            <?php 
                            $posttags = get_the_tags();
                            
                            foreach($posttags as $t) : ?>
                                <a href="<?php echo home_url( '/tags/' ) . $t->slug; ?>" class="text-lowercase"><?php echo $t->name; ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php endwhile; endif; wp_reset_query();  ?>

            <?php
            
                    $orig_post = $post;
                    $tag_ids = array();
                    global $post;

                    $tags = wp_get_post_tags($post->ID);
                    
                    if ($tags[0]) {
                        foreach($tags as $individual_tag) {
                            array_push($tag_ids, $individual_tag->term_id);
                        }
                    }

                        $args = array(
                            'tag__in' => $tag_ids,
                            'post__not_in' => array($post->ID),
                            'posts_per_page'=> 2, // Number of related posts that will be shown.
                            'ignore_sticky_posts'=> 1
                        );

                    $my_query = new wp_query( $args );

                    if( $my_query->have_posts() ) {
            
            ?>

            <div class="relacionados">
                <h2 class="titulo">Textos relacionados</h2>

                <div class="row">
                    <?php 

                    while( $my_query->have_posts() ) {
                        $my_query->the_post();
                        

                        get_template_part('preview-post-blog', null, array('id' => get_the_id(), 'className' => 'col-md mb-5 white'));

                    
                        }
                    ?>
                </div>
            </div>

            <?php

            }
            $post = $orig_post;
            wp_reset_query(); 

            
            ?>
            </section>
            
            <div id="comentarios">
                <a href="#" id="fechar-comentarios">
                    <i class="fas fa-times"></i>
                </a>
                <?php comments_template(); ?> 
            </div>
        </div>
        <div class="col-4 d-none d-lg-block">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>
   



<?php get_footer(); ?>