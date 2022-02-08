<?php 

function sortByPoints($a, $b) {
    return $a['points'] < $b['points'];
}

$posts_array = array();

$args = array(
    'post_type'         => 'post',
    'posts_per_page'    => -1,
    'date_query'        => array(
        'after'    => "5 days ago"
    )
);
$query = new WP_Query( $args );

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        $today = new DateTime('NOW');
        $post_date = new DateTime(get_the_date("Y-m-d H:i:s"));

        $difference = $today->diff($post_date);
        $difference_days = $difference->d * 24;
        $difference_hours = $difference->h;
        $difference_minutes = $difference->i / 60;


        $time_elapsed = $difference_days + $difference_hours + $difference_minutes;

        $points_comments = get_comments_number() * 2;
        $points_votes = get_post_meta(get_the_id(), 'post_like_count', true);

        if ($points_votes != '')  {
            $total_points = ($points_comments + $points_votes) - ($time_elapsed / 10);

            $posts_array[] = array(
                'id' => get_the_id(),
                'points' => $total_points
            );
        }
    endwhile; 

    usort($posts_array, 'sortByPoints');
    $posts_array = array_slice($posts_array, 0, 30);
endif;
?>

<?php  get_header();  ?>

<div class="container">
    <?php
       foreach($posts_array as $post) {
           get_template_part('post', null, array('id' => $post['id']));
       }
    ?>
</div>

<?php get_footer(); ?>