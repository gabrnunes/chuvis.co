<div id="comments" class="comments-area">
 
    <?php if ( have_comments() ) : ?>

        <h3>CAIXA DE COMENTÁRIOS:</h3>
        <h4>[Pode não parecer, mas você está num lugar seguro.]</h4>
 
        <ul class="comment-list">
            <?php
                wp_list_comments( array(
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 0,
                ) );
            ?>
        </ul><!-- .comment-list -->
 
        <?php if ( ! comments_open() && get_comments_number() ) : ?>
        <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
        <?php endif; ?>
 
    <?php endif; // have_comments() ?>
 
    <?php comment_form([
        'label_submit' => 'clique para salvar o minuto de quem escreveu este texto',
        'logged_in_as' => '<strong>Você já está logado, por isso não precisa informar nome e e-mail para comentar.</strong><br><br><p class="comment-notes">Imagine que esta é a nossa camiseta de uniforme e hoje é o nosso último dia de aula, que este é nosso mural do orkut e você vai deixar um depoimento, que a leitura deste comentário pode ser o assunto do dia de alguém do outro lado da tela:</p>',
        'comment_notes_before' => '<p class="comment-notes">Imagine que esta é a nossa camiseta de uniforme e hoje é o nosso último dia de aula, que este é nosso mural do orkut e você vai deixar um depoimento, que a leitura deste comentário pode ser o assunto do dia de alguém do outro lado da tela:</p>'
    ]); ?>
 
</div><!-- #comments -->