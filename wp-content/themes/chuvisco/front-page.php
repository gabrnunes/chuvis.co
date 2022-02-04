<?php 

global $wpdb;    
$result = $wpdb->get_results("
SELECT
       p.ID, ((TIME_TO_SEC(TIMEDIFF(NOW(), p.post_date)) / 60) / m.meta_value) AS minutes
FROM
     $wpdb->posts p
     LEFT JOIN
     $wpdb->postmeta m ON p.ID = m.post_id
     WHERE
           p.post_type = 'post'
           AND ( m.meta_key = 'post_like_count')
ORDER BY
         minutes ASC
LIMIT 30
");

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