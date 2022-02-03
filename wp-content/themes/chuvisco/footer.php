

<footer class="footer py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg coluna-1">
                <h2><span>Esc.</span> Escola de Escrita</h2>
                <span>um espaço intelectual e artístico de construção da inteligência e investigação de processos criativos</span>
            </div>
            <div class="col-lg coluna-2 my-5 my-lg-0">
                <?php if( have_rows('redes_sociais', 'options') ): ?>
                    <ul class="redes-sociais list-unstyled">
                        <?php while( have_rows('redes_sociais', 'options') ) : the_row(); ?>
                            <li class="item">
                                <a class="link" target="_blank" href="<?php the_sub_field('link'); ?>"><i class="fab fa-<?php the_sub_field('icone'); ?>"></i></a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-lg coluna-3 text-right">
                <i class="fas fa-map-marker-alt"></i>
                <span>em Curitiba e em todo lugar.</span>
            </div>
        </div>
    </div>
</footer>



<?php wp_footer(); ?>

<script src="<?php bloginfo('template_directory'); ?>/dist/js/main.min.js?v=4.7"></script>

</body>
</html>

