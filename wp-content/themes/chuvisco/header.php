<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Primary Meta Tags -->
  <title><?php wp_title('&#8212; ', true, 'right'); ?>Chuvisco</title>
  <meta name="title" content="<?php wp_title('&#8212; ', true, 'right'); ?>Chuvisco">
  <meta name="description" content="Participe da nossa comunidade e tenha acesso a links de artigos, ferramentas e tudo que entusiastas da cultura criativa e maker precisam.">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://chuvis.co/">
  <meta property="og:title" content="<?php wp_title('&#8212; ', true, 'right'); ?>Chuvisco">
  <meta property="og:description" content="Participe da nossa comunidade e tenha acesso a links de artigos, ferramentas e tudo que entusiastas da cultura criativa e maker precisam.">
  <meta property="og:image" content="<?php bloginfo('template_directory'); ?>/dist/images/card.png">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://chuvis.co/">
  <meta property="twitter:title" content="<?php wp_title('&#8212; ', true, 'right'); ?>Chuvisco">
  <meta property="twitter:description" content="Participe da nossa comunidade e tenha acesso a links de artigos, ferramentas e tudo que entusiastas da cultura criativa e maker precisam.">
  <meta property="twitter:image" content="<?php bloginfo('template_directory'); ?>/dist/images/card.png">

  <link rel="shortcut icon" type="image/png" href="<?php bloginfo('template_directory'); ?>/dist/images/favicon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/dist/css/main.css?1.0">
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
</head>

<body <?php body_class(); ?>>

  <header class="header">
    <div class="container">
      <a href="<?php echo home_url( '/' ); ?>" class="logo" title="Chuvisco">
        <span>
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path d="M12 0c-4.87 7.197-8 11.699-8 16.075 0 4.378 3.579 7.925 8 7.925s8-3.547 8-7.925c0-4.376-3.13-8.878-8-16.075z"/></svg>
        </span>
        Chuvisco
      </a>

      <nav>
        <ul>
          <li><a href="<?php echo home_url( '/novos' ); ?>">novos</a></li>
          <li><a href="<?php echo home_url( '/arquivo' ); ?>">arquivo</a></li>
        </ul>

        <ul>
          <?php 
          if(is_user_logged_in()) : 
            
            $current_user = wp_get_current_user();
          ?>
            <li><?php echo $current_user->display_name; ?> <a href="<?php echo wp_logout_url(home_url()); ?>">(sair)</a></li>
          <?php endif; ?>
          <li><a href="<?php echo (is_user_logged_in() ? home_url( '/enviar' ) : home_url( '/login' )); ?>">enviar link</a></li>
          <?php if(!is_user_logged_in()) : ?>
            <li><a href="<?php echo home_url( '/login' ); ?>">login</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>