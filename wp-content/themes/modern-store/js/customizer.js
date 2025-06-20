jQuery(document).ready(function($){


  /* === Toggle visibility of the Featured Image size control === */
  const FISizeTypeControl = $('#customize-control-fi_size_type');
  const FISizeControl = $('#customize-control-fi_size');
  toggleControlVisibility(
    FISizeTypeControl.find('input:checked').val(), 
    FISizeControl
  );
  FISizeTypeControl.on('click', function() {
    toggleControlVisibility(
      FISizeTypeControl.find('input:checked').val(), 
      FISizeControl
    );
  });
  
  function toggleControlVisibility(value, target) {
    if ( value == 'yes' ) {
      target.addClass('show');
    } else {
      target.removeClass('show');
    }
  }

  /* === Add reset button to Featured Image size === */
  const resetFIButton = '<input type="button" id="reset-fi-size" class="button button-small wp-picker-default" value="Default" aria-label="Reset Featured Image Size">';
  $('#customize-control-fi_size').append(resetFIButton);
  $('#reset-fi-size').on('click', function() {
    $('#_customize-input-fi_size').val(50);
    $('#_customize-input-fi_size').trigger( 'change' );
  });

  const slides = ['slide_1', 'slide_2', 'slide_3', 'slide_4', 'slide_5'];

  slides.forEach( function( slide ) {

    /* === Add reset button to Header Promo text width === */
    const resetTextWidthButton = '<input type="button" id="reset-text-width-' + slide + '" class="button button-small wp-picker-default" value="Default" aria-label="Reset Text Width">';
    $('#customize-control-header_promo_text_width_' + slide).append(resetTextWidthButton);
    $('#reset-text-width-' + slide).on('click', function() {
      $('#_customize-input-header_promo_text_width_' + slide).val(50);
      $('#_customize-input-header_promo_text_width_' + slide).trigger( 'change' );
    });

    /* === Add reset button to Header Promo height === */
    const resetHeaderPromoHeightButton = '<input type="button" id="reset-header-promo-height-' + slide + '" class="button button-small wp-picker-default" value="Default" aria-label="Reset Header Promo height">';
    $('#customize-control-header_promo_height_' + slide).append(resetHeaderPromoHeightButton);
    $('#reset-header-promo-height-' + slide).on('click', function() {
      $('#_customize-input-header_promo_height_' + slide).val(60);
      $('#_customize-input-header_promo_height_' + slide).trigger( 'change' );
    });

    /* === Add reset button to Header Promo BG opacity === */
    const resetHeaderPromoBGOpacityButton = '<input type="button" id="reset-header-promo-bg-opacity-' + slide + '" class="button button-small wp-picker-default" value="Default" aria-label="Reset Header Promo background opacity">';
    $('#customize-control-header_promo_overlay_opacity_' + slide).append(resetHeaderPromoBGOpacityButton);
    $('#reset-header-promo-bg-opacity-' + slide).on('click', function() {
      $('#_customize-input-header_promo_overlay_opacity_' + slide).val(0);
      $('#_customize-input-header_promo_overlay_opacity_' + slide).trigger( 'change' );
    });
  });
  

  /* === Header promo display options === */
  jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function() {
    checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
      function() {
        return this.value;
      }
    ).get().join( ',' );
    jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
  });


  // Marking inactive homepage sections
  const homepageSections = [
    'shortcode',
    'featured_products',
    'on_sale_products',
    'categories',
    'top_rated_products',
    'latest_products'
  ];  

  homepageSections.forEach(function(sectionName) {
      
      const inputs = $('#customize-control-homepage_' + sectionName).find('input');
      
      inputs.each(function() {
          const section = $('#accordion-section-modern_store_homepage_' + sectionName).find('h3');
          if ( $(this).val() == 'no' && $(this).prop('checked') ) {
              section.addClass('inactive');
          }
          $(this).on('change', function() {
              if ( $(this).val() == 'no' && $(this).prop('checked') ) {
                  section.addClass('inactive');
              } else {
                  section.removeClass('inactive');
              }
          })
      })
  });
  
  function expandScrollTrigger(section, handle) {
    section.expanded.bind( function( isExpanded ) {
      if ( isExpanded ) {
          wp.customize.previewer.send( handle );
      }
    } );
  }

  (function ( api ) {
    
    api.section( 'modern_store_homepage_on_sale_products', function( section ) {
      expandScrollTrigger(section, 'on-sale-focus');  
    } );
    api.section( 'modern_store_homepage_latest_products', function( section ) {
      expandScrollTrigger(section, 'latest-products-focus');  
    } );
    api.section( 'modern_store_homepage_top_rated_products', function( section ) {
      expandScrollTrigger(section, 'top-rated-focus');  
    } );
    api.section( 'modern_store_homepage_featured_products', function( section ) {
      expandScrollTrigger(section, 'featured-products-focus');  
    } );
    api.section( 'modern_store_homepage_categories', function( section ) {
      expandScrollTrigger(section, 'product-categories-focus');  
    } );
    api.section( 'modern_store_homepage_shortcode', function( section ) {
      expandScrollTrigger(section, 'shortcode-focus');  
    } );

    // PRO
    api.section( 'modern_store_pro_homepage_best_selling_products', function( section ) {
      expandScrollTrigger(section, 'best-selling-focus');  
    } );
    api.section( 'modern_store_pro_homepage_custom_products', function( section ) {
      expandScrollTrigger(section, 'custom-products-focus');  
    } );
    api.section( 'modern_store_pro_homepage_custom_categories', function( section ) {
      expandScrollTrigger(section, 'custom-categories-focus');  
    } );
    api.section( 'modern_store_pro_homepage_latest_posts', function( section ) {
      expandScrollTrigger(section, 'latest-posts-focus');  
    } );
  } ( wp.customize ) );
});

