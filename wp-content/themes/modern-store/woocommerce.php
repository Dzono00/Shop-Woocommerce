<?php get_header(); ?>

<div id="loop-container" class="loop-container">
  <?php woocommerce_breadcrumb(); ?>
  <?php woocommerce_content(); ?>
</div>

<?php 
if ( is_shop() || is_product_category() || is_product_tag() ) {
  get_sidebar( 'store' ); 
}
?>


<?php get_footer();