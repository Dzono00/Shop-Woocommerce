<?php get_header(); ?>

<div id="loop-container" class="loop-container">
    <?php
    get_template_part( 'content/archive-header' );
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ct_modern_store_get_content_template();
        endwhile;
    endif;
    ?>
</div>

<?php get_footer();