<?php

    $postID = $args['id'] ? $args['id'] : get_the_id();

    $count_key = 'post_like_count';
    $count = get_post_meta($postID, $count_key, true);

?>
<a class="vote" href="">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M12 0c-4.87 7.197-8 11.699-8 16.075 0 4.378 3.579 7.925 8 7.925s8-3.547 8-7.925c0-4.376-3.13-8.878-8-16.075z"/></svg>
    <span><?php echo $count; ?></span>
</a>