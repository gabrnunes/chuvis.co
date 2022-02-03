<?php get_header(); ?>

  <div class="container erro404 my-5">
    <div class="row">
      <div class="col-md-12">
        <div class="titulo-imagem">
          <img src="<?php bloginfo('template_directory'); ?>/dist/images/estante.png" alt="Estante vazia">
          <h1>Ops!</h1>
        </div>
        
        <p>não encontramos esta página na nossa biblioteca</p>

        <a href="<?php echo home_url( '/' ); ?>">voltar para a porta de entrada</a>
      </div>
     
    </div>
  </div>


<?php get_footer(); ?>