( function( $ ) {

    var siteTitle = $('#site-title');

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( siteTitle.find('img').length == 0 ) {
                siteTitle.children('a').text( to );
            }
        } );
    } );

    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            var tagline = $('.tagline');
            if( tagline.length == 0 ) {
                $('#title-container').append('<p class="tagline"></p>');
            }
            tagline.text( to );
        } );
    } );

    /***** Featured Image Size *****/

    wp.customize( 'fi_size', function( value ) {
        value.bind( function( to ) {
            $('.featured-image').css( 'padding-bottom', to + '%' );
        } );
    } );

    /***** Homepage sections *****/

    wp.customize( 'homepage_featured_products_title', function( value ) {
        value.bind( function( to ) {
            $('.featured-products').find('.section-title').text( to );
        } );
    } );
    wp.customize( 'homepage_on_sale_products_title', function( value ) {
        value.bind( function( to ) {
            $('.on-sale-products').find('.section-title').text( to );
        } );
    } );
    wp.customize( 'homepage_categories_title', function( value ) {
        value.bind( function( to ) {
            $('.product-categories').find('.section-title').text( to );
        } );
    } );
    wp.customize( 'homepage_top_rated_products_title', function( value ) {
        value.bind( function( to ) {
            $('.top-rated-products').find('.section-title').text( to );
        } );
    } );
    wp.customize( 'homepage_latest_products_title', function( value ) {
        value.bind( function( to ) {
            $('.latest-products').find('.section-title').text( to );
        } );
    } );

    /***** Header Promo *****/

    const slides = ['slide_1', 'slide_2', 'slide_3', 'slide_4', 'slide_5'];

    slides.forEach( function( slide ) {

        wp.customize( 'header_promo_title_text_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.title').text( to );
            } );
        } );
        wp.customize( 'header_promo_subtitle_text_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.subtitle').text( to );
            } );
        } );
        wp.customize( 'header_promo_button_text_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.button').find('a').text( to );
            } );
        } );
        wp.customize( 'header_promo_button_url_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.button').find('a').attr( 'href', to );
            } );
        } );
        wp.customize( 'header_promo_text_width_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.content').css('width', to + '%');
            } );
        } );
        wp.customize( 'header_promo_height_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).css('height', to + 'vh');
            } );
        } );
        wp.customize( 'header_promo_overlay_color_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.overlay').css('background', to);
            } );
        } );
        wp.customize( 'header_promo_overlay_opacity_' + slide, function( value ) {
            value.bind( function( to ) {
                $('#header-promo').find('.' + slide).find('.overlay').css('opacity', to);
            } );
        } );
    });


    /***** Store Search Bar *****/
    
    wp.customize( 'store_search_bar_category_select_label', function( value ) {
        value.bind( function( to ) {
            $('#store-search').find('option:first-child').text(to);
        } );
    } );

    /***** Blog *****/
    
    wp.customize( 'read_more_text', function( value ) {
        value.bind( function( to ) {
            $('.more-link').text(to);
        } );
    } );

    /***** Search bar category  select width *****/
    
    wp.customize( 'store_search_bar_category_select_max_width', function( value ) {
        value.bind( function( to ) {
            $('#store-search').css('max-width', to + 'px');
        } );
    } );

} )( jQuery );