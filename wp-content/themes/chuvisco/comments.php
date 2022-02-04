<div id="comments" class="comments-area">
 
    <?php comment_form([
        'label_submit' => 'publicar',
        'must_log_in' => '<div class="logout-comment">Faça login para comentar</div>',
        'comment_notes_after' => '',
        'logged_in_as' => '',
        'comment_notes_before' => '',
        'title_reply' => '',
        'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="5" maxlength="65525" required="required" placeholder="Seu comentário"></textarea>'
    ]); ?>

<?php if ( have_comments() ) : ?>

<ul class="comment-list">
    <?php
        wp_list_comments( array(
            'style'       => 'ul',
            'type'        => 'comment',
            'avatar_size' => 0,
        ) );
    ?>
</ul><!-- .comment-list -->

<?php if ( ! comments_open() && get_comments_number() ) : ?>
<p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
<?php endif; ?>

<?php endif; // have_comments() ?>
 
</div><!-- #comments -->