
<footer class="footer">
    <div class="container">
        <?php if(is_front_page() || is_page("novos") || is_page("arquivo")) get_search_form(); ?>

        <nav>
            <ul>
                <li><a href="<?php echo home_url( '/guia-de-uso' ); ?>">guia de uso</a></li>
                <li><a href="<?php echo home_url( '/duvidas' ); ?>">dúvidas</a></li>
                <li><a href="<?php echo home_url( '/contato' ); ?>">contato</a></li>
                <li>hospedado pela <a href="https://webhaus.com.br/" target="_blank" rel="noopener noreferrer">Webhaus</a></li>
            </ul>
        </nav>
    </div>
</footer>


<script src="<?php bloginfo('template_directory'); ?>/dist/js/main.min.js?v=1.0"></script>
<script data-goatcounter="https://chuvisco.goatcounter.com/count"
        async src="//gc.zgo.at/count.js"></script>

</body>
</html>

