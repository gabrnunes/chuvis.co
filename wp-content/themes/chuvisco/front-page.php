<?php 

global $wpdb;

$date = date("Y/m/d", strtotime("-5 day"));

$query = "
SELECT
       p.ID, ((TIME_TO_SEC(TIMEDIFF(NOW(), p.post_date)) / 60) / m.meta_value) AS minutes
FROM
     $wpdb->posts p
     LEFT JOIN
     $wpdb->postmeta m ON p.ID = m.post_id
     WHERE
           p.post_type = 'post'
           AND ( m.meta_key = 'post_like_count')
           AND p.post_date >= '$date' 
ORDER BY
         minutes ASC
LIMIT 30
";

$result = $wpdb->get_results($query);

?>

<?php  get_header();  ?>

<div class="container">
    <?php
        foreach($result as $post) {
            get_template_part('post', null, array('id' => $post->ID));
        }
    ?>
</div>

<?php get_footer(); ?>