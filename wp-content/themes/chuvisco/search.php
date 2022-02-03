
<?php  get_header();  ?>

<div class="container">

    <div class="row">

        <div class="col">
            <section id="blog">
                <div class="row">
                    <?php 
                    if (have_posts()) :
                    
                    $i = 0;
                    
                    while (have_posts()) : the_post();

                        $i++;

                        get_template_part('preview-post-blog', null, array('id' => get_the_id(), 'className' => 'col-sm-6 col-md-4 mb-5 white'));

                        if($i == 2) {
                            $i = 0;
                    ?>
                        <div class="w-100"></div>
                    <?php
                            
                        }
                    
                    endwhile; ?>
                </div>   

                <?php wp_pagenavi(); ?>

                <?php else: ?>
                    
                    <h2>Resultados da busca</h2>
                    <p>Nenhuma publicação encontrada, tente novamente.</p>


                <?php endif;?>

            </section>
        </div>


    </div>

</div>


<?php get_footer(); ?>