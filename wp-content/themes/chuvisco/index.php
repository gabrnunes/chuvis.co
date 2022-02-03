
<?php  get_header();  ?>

<?php get_template_part('header-blog'); ?>

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

                        get_template_part('preview-post-blog', null, array('id' => get_the_id(), 'className' => 'col-sm-6 mb-5 white'));

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
        <div class="col-4 d-none d-lg-block">
            <?php get_sidebar(); ?>
        </div>

    </div>

</div>
<?php 

$args = array(
    'posts_per_page' => -1,
    'category_name' => 'texto-de-alunos'
);
$query = new WP_Query( $args );

if($query->have_posts()) :
?>
<div class="card opened">
    <div class="card-header">
        <div class="container">
            últimos textos de <strong>alunes</strong>
        </div>
    </div>

    <div class="container">
        <div class="my-5">
            <div id="textos-alunes-slider">
                <?php 
                    while ($query->have_posts()) : $query->the_post();
                        get_template_part('preview-post-blog', null, array('id' => get_the_id(), 'className' => 'col'));
                    endwhile;
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php 

$args = array(
    'posts_per_page' => -1,
    'category_name' => 'texto-de-profes'
);
$query = new WP_Query( $args );

if($query->have_posts()) :
?>

<div class="card opened">
    <div class="card-header">
        <div class="container">
            últimos textos de <strong>profes</strong>
        </div>
    </div>

    <div class="container">
        <div class="my-5">
            <div id="textos-profes-slider">
                <?php 
                    while ($query->have_posts()) : $query->the_post();
                        get_template_part('preview-post-blog', null, array('id' => get_the_id(), 'className' => 'col'));
                    endwhile;
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>