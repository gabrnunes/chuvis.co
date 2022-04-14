<?php
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            $external_url = get_post_meta(get_the_id(), 'external_url', true);
            $count = get_post_meta(get_the_id(), 'post_like_count', true);

            if (!$count) $count = "nenhum";
            $votes_text = $count > 1 ? 'votos' : 'voto';

        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <header class="entry-header">
                <?php 
                
                if($external_url) :
                    the_title( '<h1 class="entry-title">🔗 <a href="' . esc_url( $external_url ) . '" rel="ugc">', '</a></h1>' );
                else:
                    the_title( '<h1 class="entry-title">', '</h1>' );
                endif;
                ?>
                <div class="entry-meta">
                    <?php echo do_shortcode('[orbita-vote]'); ?><span data-votes-post-id="<?php the_ID(); ?>"><?php echo $count; ?></span> <?php echo $votes_text; ?> | <?php mdu2020_posted_by(); ?> em <?php mdu2020_posted_on(); ?>
                </div>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            
            <footer class="entry-footer">
                <span style="float: right">
                    <a href="mailto:?subject=<?php the_title(); ?>&amp;body=Veja este post: <?php echo get_permalink(); ?>" title="Compartilhe por e-mail" rel="noopener">
                    <xml version="1.0" encoding="iso-8859-1"?><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 490.2 490.2" width="30" style="enable-background:new 0 0 490.2 490.2;" xml:space="preserve">
                        <g>
                            <path d="M420.95,61.8C376.25,20.6,320.65,0,254.25,0c-69.8,0-129.3,23.4-178.4,70.3s-73.7,105.2-73.7,175
                            c0,66.9,23.4,124.4,70.1,172.6c46.9,48.2,109.9,72.3,189.2,72.3c47.8,0,94.7-9.8,140.7-29.5c15-6.4,22.3-23.6,16.2-38.7l0,0
                            c-6.3-15.6-24.1-22.8-39.6-16.2c-40,17.2-79.2,25.8-117.4,25.8c-60.8,0-107.9-18.5-141.3-55.6c-33.3-37-50-80.5-50-130.4
                            c0-54.2,17.9-99.4,53.6-135.7c35.6-36.2,79.5-54.4,131.5-54.4c47.9,0,88.4,14.9,121.4,44.7s49.5,67.3,49.5,112.5
                            c0,30.9-7.6,56.7-22.7,77.2c-15.1,20.6-30.8,30.8-47.1,30.8c-8.8,0-13.2-4.7-13.2-14.2c0-7.7,0.6-16.7,1.7-27.1l18.6-152.1h-64
                            l-4.1,14.9c-16.3-13.3-34.2-20-53.6-20c-30.8,0-57.2,12.3-79.1,36.8c-22,24.5-32.9,56.1-32.9,94.7c0,37.7,9.7,68.2,29.2,91.3
                            c19.5,23.2,42.9,34.7,70.3,34.7c24.5,0,45.4-10.3,62.8-30.8c13.1,19.7,32.4,29.5,57.9,29.5c37.5,0,69.9-16.3,97.2-49
                            c27.3-32.6,41-72,41-118.1C488.05,152.9,465.75,103,420.95,61.8z M273.55,291.9c-11.3,15.2-24.8,22.9-40.5,22.9
                            c-10.7,0-19.3-5.6-25.8-16.8c-6.6-11.2-9.9-25.1-9.9-41.8c0-20.6,4.6-37.2,13.8-49.8s20.6-19,34.2-19c11.8,0,22.3,4.7,31.5,14.2
                            s13.8,22.1,13.8,37.9C290.55,259.2,284.85,276.6,273.55,291.9z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>
                    <a href="tg://msg_url?url=<?php echo get_permalink(); ?>&nbsp;&rarr;&nbsp;Siga @manualdousuario" target="_blank" rel="noopener" title="Compartilhe no Telegram"><svg id="Bold" enable-background="new 0 0 24 24" height="30" viewBox="0 0 24 24" width="30" xmlns="http://www.w3.org/2000/svg"><path d="m12 24c6.629 0 12-5.371 12-12s-5.371-12-12-12-12 5.371-12 12 5.371 12 12 12zm-6.509-12.26 11.57-4.461c.537-.194 1.006.131.832.943l.001-.001-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953z"/></svg></a>
                    <a href="https://wa.me/?text=<?php the_title('',':&nbsp;'); ?><?php echo get_permalink(); ?>" target="_blank" rel="noopener" title="Compartilhe no WhatsApp"><svg id="Bold" enable-background="new 0 0 24 24" height="30" viewBox="0 0 24 24" width="30" xmlns="http://www.w3.org/2000/svg"><path d="m17.507 14.307-.009.075c-2.199-1.096-2.429-1.242-2.713-.816-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.293-.506.32-.578.878-1.634.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.576-.05-.997-.042-1.368.344-1.614 1.774-1.207 3.604.174 5.55 2.714 3.552 4.16 4.206 6.804 5.114.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345z"/><path d="m20.52 3.449c-7.689-7.433-20.414-2.042-20.419 8.444 0 2.096.549 4.14 1.595 5.945l-1.696 6.162 6.335-1.652c7.905 4.27 17.661-1.4 17.665-10.449 0-3.176-1.24-6.165-3.495-8.411zm1.482 8.417c-.006 7.633-8.385 12.4-15.012 8.504l-.36-.214-3.75.975 1.005-3.645-.239-.375c-4.124-6.565.614-15.145 8.426-15.145 2.654 0 5.145 1.035 7.021 2.91 1.875 1.859 2.909 4.35 2.909 6.99z"/></svg></a>
                </span>

                <p>🪐 <a href="/orbita">Órbita</a></p>
            </footer>
        <?php 
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
