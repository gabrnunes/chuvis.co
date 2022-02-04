<?php

    $postID = $args['id'] ? $args['id'] : get_the_id();

    $count_key = 'post_like_count';
    $count = get_post_meta($postID, $count_key, true);

    if (!$count) $count = 0;

    $users_vote_key = 'post_users_vote';
    $users_vote_array = get_post_meta($postID, $users_vote_key, true);
    $already_voted = false;

    if($users_vote_array && array_search(get_current_user_id(), $users_vote_array) !== false) $already_voted = true;

?>

<button class="vote <?php if(is_user_logged_in() && !$already_voted) echo 'can-vote'; ?> <?php if($already_voted) echo 'already-voted'; ?>" data-url="<?php echo admin_url('admin-ajax.php');?>" data-post-id="<?php echo $postID; ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path d="M12 0c-4.87 7.197-8 11.699-8 16.075 0 4.378 3.579 7.925 8 7.925s8-3.547 8-7.925c0-4.376-3.13-8.878-8-16.075z"/></svg>
    <span><?php echo $count; ?></span>
</button>