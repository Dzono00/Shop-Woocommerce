jQuery(document).ready(function($){

  function scrollToSection( section ) {
    $('html, body').animate({
        scrollTop: section.offset().top - 96,
      }, 500, 'linear'
    );
  }

  wp.customize.preview.bind( 'shortcode-focus', function() {
    scrollToSection( $('#shortcode') );
  } );
  wp.customize.preview.bind( 'on-sale-focus', function() {
    scrollToSection( $('#on-sale-products') );
  } );
  wp.customize.preview.bind( 'latest-products-focus', function() {
    scrollToSection( $('#latest-products') );
  } );
  wp.customize.preview.bind( 'top-rated-focus', function() {
    scrollToSection( $('#top-rated-products') );
  } );
  wp.customize.preview.bind( 'featured-products-focus', function() {
    scrollToSection( $('#featured-products') );
  } );
  wp.customize.preview.bind( 'product-categories-focus', function() {
    scrollToSection( $('#product-categories') );
  } );

  // PRO
  wp.customize.preview.bind( 'best-selling-focus', function() {
    scrollToSection( $('#best-selling-products') );
  } );
  wp.customize.preview.bind( 'custom-products-focus', function() {
    scrollToSection( $('#custom-products') );
  } );
  wp.customize.preview.bind( 'custom-categories-focus', function() {
    scrollToSection( $('#custom-categories') );
  } );
  wp.customize.preview.bind( 'latest-posts-focus', function() {
    scrollToSection( $('#latest-posts') );
  } );

});


