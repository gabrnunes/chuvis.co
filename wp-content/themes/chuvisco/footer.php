
<footer class="footer">
    <div class="container">
        <?php if(!is_page()) get_search_form(); ?>

        <nav>
            <ul>
                <li><a href="<?php echo home_url( '/regras' ); ?>">regras</a></li>
                <li><a href="<?php echo home_url( '/duvidas' ); ?>">dúvidas</a></li>
                <li><a href="<?php echo home_url( '/contato' ); ?>">contato</a></li>
            </ul>
        </nav>
    </div>
</footer>


<script src="<?php bloginfo('template_directory'); ?>/dist/js/main.min.js?v=1.0"></script>

</body>
</html>

