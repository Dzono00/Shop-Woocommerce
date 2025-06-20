jQuery(document).ready(function($){

    var body = $('body');
    var siteHeader = $('#site-header');
    var titleContainer = $('#title-container');
    var toggleNavigation = $('#toggle-navigation');
    var closeMobileMenu = $('#close-mobile-menu');
    var menuPrimaryContainer = $('#menu-primary-container');
    var menuSecondaryContainer = $('#menu-secondary-container');
    var mobileMenuContainer = $('#mobile-menu-container');
    var mobileMenuContainerInner = $('#mobile-menu-container-inner');
    var toggleDropdown = $('.toggle-dropdown');
    var socialIcons = $('#social-icons-container');
    var menuLink = $('.menu-item').children('a');
    var searchFormContainer = $('#search-form-container');

    objectFitAdjustment();

    toggleNavigation.on('click', openMobileMenu);
    closeMobileMenu.on('click', openMobileMenu);
    body.on('click', '#search-icon', openSearchBar);

    $('.post-content').fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    $(window).resize(function(){
        objectFitAdjustment();
        moveElementsToMobileMenu(true);
    });

    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {
        objectFitAdjustment();
    } );

    function moveElementsToMobileMenu(resize) {
        
        if ( window.innerWidth < 800 ) {
            // avoid moving elements because it causes the keyboard to auto-hide on Android when user clicks on the search bar
            // because the keyboard itself triggers a resize event
            if ( mobileMenuContainer.hasClass('open') ) {
                return;
            }
            mobileMenuContainerInner.append(menuSecondaryContainer);
            menuSecondaryContainer.addClass('moved');

            mobileMenuContainerInner.append(socialIcons);
            socialIcons.addClass('moved');

            searchFormContainer.insertAfter( $('#close-mobile-menu') );
            searchFormContainer.addClass('moved');

            $('#toggle-container').prepend($('#user-account-icon-container'));
            $('#toggle-container').append($('#shopping-cart-container'));
            $('#user-account-icon-container,#shopping-cart-container').css('opacity', 1);
        } else if ( resize ) {
            $('.header-top').append(menuSecondaryContainer);
            menuSecondaryContainer.removeClass('moved');
            
            $('.header-top').append(socialIcons);
            socialIcons.removeClass('moved');

            $('.header-middle').append(searchFormContainer);
            searchFormContainer.removeClass('moved');
            $('.header-middle').append($('#user-account-icon-container'));
            $('.header-middle').append($('#shopping-cart-container'));
        }
    }
    moveElementsToMobileMenu(false);

    function openMobileMenu() {

        if( mobileMenuContainer.hasClass('open') ) {
            mobileMenuContainer.removeClass('open');
            $(this).removeClass('open');
            
            body.css( 'overflow', 'auto' );
            updateBodyScroll(false);

            
            // change screen reader text
            // $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {
            mobileMenuContainer.addClass('open');
            $(this).addClass('open');

            body.css( 'overflow', 'hidden' );
            
            if ( mobileMenuContainerInner.innerHeight() < window.innerHeight ) {
                bodyScrollLock.disableBodyScroll(mobileMenuContainer);
            }
            
            updateBodyScroll(true);

            // change screen reader text
            // $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    }

    function updateBodyScroll(open) {
        if ( open ) {
            if ( mobileMenuContainerInner.height() < window.innerHeight ) {
                bodyScrollLock.disableBodyScroll(mobileMenuContainer);
            }
        } else {
            bodyScrollLock.enableBodyScroll(mobileMenuContainer);
        }
    }

    // display the dropdown menus
    toggleDropdown.on('click', openDropdownMenu);

    function openDropdownMenu() {

        // get the buttons parent (li)
        var menuItem = $(this).parent();

        // if already opened
        if( menuItem.hasClass('open') ) {

            // remove open class
            menuItem.removeClass('open');

            $(this).removeClass('open');

            // change screen reader text
            //$(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

            updateBodyScroll();
        } else {

            // add class to open the menu
            menuItem.addClass('open');

            $(this).addClass('open');

            // change screen reader text
            //$(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');

            updateBodyScroll();
        }
    }

    function openSearchBar(){

        if( $(this).hasClass('open') ) {

            $(this).removeClass('open');
            socialMediaIcons.removeClass('fade');

            // make search input inaccessible to keyboards
            siteHeader.find('.search-field').attr('tabindex', -1);

            // handle mobile width search bar sizing
            if( window.innerWidth < 900 ) {
                siteHeader.find('.search-form').attr('style', '');
            }
        } else {

            $(this).addClass('open');
            socialMediaIcons.addClass('fade');

            // make search input keyboard accessible
            siteHeader.find('.search-field').attr('tabindex', 0);

            // handle mobile width search bar sizing
            if( window.innerWidth < 800 ) {

                // distance to other side (35px is width of icon space)
                var leftDistance = window.innerWidth * 0.83332 - 35;

                siteHeader.find('.search-form').css('left', -leftDistance + 'px')
            }
        }
    }

    /* allow keyboard access/visibility for dropdown menu items */
    menuLink.focus(function(){
        $(this).parents('ul').addClass('focused');
    });
    menuLink.focusout(function(){
        $(this).parents('ul').removeClass('focused');
    });

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                if ( !$(this).parent().parent('.post').hasClass('ratio-natural') ) {

                    var image = $(this).children('img').add($(this).children('a').children('img'));

                    // don't process images twice (relevant when using infinite scroll)
                    if ( image.hasClass('no-object-fit') ) {
                        return;
                    }

                    image.addClass('no-object-fit');

                    // if the image is not wide enough to fill the space
                    if (image.outerWidth() < $(this).outerWidth()) {

                        image.css({
                            'width': '100%',
                            'min-width': '100%',
                            'max-width': '100%',
                            'height': 'auto',
                            'min-height': '100%',
                            'max-height': 'none'
                        });
                    }
                    // if the image is not tall enough to fill the space
                    if (image.outerHeight() < $(this).outerHeight()) {

                        image.css({
                            'height': '100%',
                            'min-height': '100%',
                            'max-height': '100%',
                            'width': 'auto',
                            'min-width': '100%',
                            'max-width': 'none'
                        });
                    }
                }
            });
        }
    }

    // ===== Scroll to Top ==== //

    if ( $('#scroll-to-top').length !== 0 ) {
        $(window).on( 'scroll', function() {
            if ($(this).scrollTop() >= 200) {        // If page is scrolled more than 50px
                $('#scroll-to-top').addClass('visible');    // Fade in the arrow
            } else {
                $('#scroll-to-top').removeClass('visible');   // Else fade out the arrow
            }
        });
        $('#scroll-to-top').click(function(e) {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 600);
            $(this).blur();
        });
    }

    $('#store-search').change(function() {
        const category = $(this).val() == 'All' ? '' : $(this).val();
        $('#product_cat_search').val(category);
    });

    /***** Tablet menu support *****/
    const parentMenuItems = menuPrimaryContainer.find('.menu-item-has-children, .page_item_has_children');
    var openMenu = false;
    if (window.innerWidth > 799) {  
        $(window).on('touchstart', tabletSubMenus);
    }
    function tabletSubMenus() {
        $(window).off('touchstart', tabletSubMenus);
        parentMenuItems.on('click', openDropdown);
        $(document).on('touchstart', (function(e) {
            if ( openMenu ) {
                if ($(e.target).parents('.menu-primary').length == 0) {
                    parentMenuItems.removeClass('menu-open');
                    openMenu = false
                }
            }
        }));
    }
    function openDropdown(e){
        if (!$(this).hasClass('menu-open')){
            e.preventDefault();
            $(this).addClass('menu-open');
            openMenu = true;
        }
    }

    const sliderTime = objectL10n.sliderTime === '' ? 5 : objectL10n.sliderTime;
    var autoRotation = autoRotateSlider;
    if ( objectL10n.autoRotateSlider == 'yes' ) {
        if ( $('#header-promo .slide').length > 1 ) {
            var autoRotationID = setInterval( autoRotation, sliderTime + '000' );
        }
    }

    function autoRotateSlider() {
        var current = $('#header-promo .slides').find('.current');
        current.removeClass('current');
        var currentNav = $('#header-promo .navigation').children('.current');
        currentNav.removeClass('current');
        if (current.next().length) {
            current.next().addClass('current');
            currentNav.next().addClass('current');
        } else {
            current.siblings(":first").addClass('current');
            currentNav.siblings(":first").addClass('current');
        }
    }

    // Rotate slider
    function manualRotateSlider() {
        $('#header-promo').find('.navigation').children().removeClass('current');
        const slideClass = $(this).parent().attr('class');
        $(this).parent().addClass('current');
        const slides = $('#header-promo').find('.slide');
        slides.removeClass('current');
        slides.each( function() {
            if ( $(this).hasClass(slideClass) ) {
                $(this).addClass('current');
            }
        });
        if ( objectL10n.autoRotateSlider == 'yes' ) {
            clearInterval(autoRotationID);
            if ( $('#header-promo .slide').length > 1 ) {
                autoRotationID = setInterval( autoRotation, sliderTime + '000' );
            }
        }
    }

    // Rotate slider
    $('#header-promo').find('button').on('click', manualRotateSlider);
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);