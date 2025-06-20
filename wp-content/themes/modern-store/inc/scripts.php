<?php
// Front-end scripts
function ct_modern_store_load_scripts_styles() {

	$font_args = array(
		'family' => urlencode( 'Lato:400,400i,900' ),
		'subset' => urlencode( 'latin,latin-ext' ),
		'display' => 'swap'
	);
	$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
	
	wp_enqueue_style( 'ct-modern-store-google-fonts', $fonts_url );
	
	wp_enqueue_script( 'ct-modern-store-js', get_template_directory_uri() . '/js/build/production.min.js', array( 'jquery' ), '', true );
	wp_localize_script( 'ct-modern-store-js', 'objectL10n', array(
		'openMenu'       	 => esc_html__( 'open menu', 'modern-store' ),
		'closeMenu'      	 => esc_html__( 'close menu', 'modern-store' ),
		'openChildMenu'  	 => esc_html__( 'open dropdown menu', 'modern-store' ),
		'closeChildMenu' 	 => esc_html__( 'close dropdown menu', 'modern-store' ),
		'autoRotateSlider' => get_theme_mod( 'header_promo_auto_rotate' ),
		'sliderTime'       => get_theme_mod( 'header_promo_auto_rotate_time' )
	) );
	// Switching to own handle because Elementor is still loading FA 4 with "font-awesome" handle which is incompatible with new FA 5 syntax
	wp_enqueue_style( 'ct-modern-store-font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css' );
	wp_enqueue_style( 'ct-modern-store-style', get_stylesheet_uri() );

	if ( is_customize_preview() ) {
		wp_enqueue_script( 'ct-modern-store-customizer-interactions-js', get_template_directory_uri() . '/js/build/customizer-interactions.min.js', array( 'jquery' ), '', true );
	}

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_load_scripts_styles' );

// Back-end scripts
function ct_modern_store_enqueue_admin_styles( $hook ) {

	if ( $hook == 'appearance_page_modern-store-options' ) {
		wp_enqueue_style( 'ct-modern-store-admin-styles', get_template_directory_uri() . '/styles/admin.min.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'ct_modern_store_enqueue_admin_styles' );

// Customizer scripts
function ct_modern_store_enqueue_customizer_scripts() {
	wp_enqueue_style( 'ct-modern-store-customizer-styles', get_template_directory_uri() . '/styles/customizer.min.css' );
	wp_enqueue_script( 'ct-modern-store-customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js', array( 'jquery' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'ct_modern_store_enqueue_customizer_scripts' );

/*
 * Script for live updating with customizer options. Has to be loaded separately on customize_preview_init hook
 * transport => postMessage
 */
function ct_modern_store_enqueue_customizer_post_message_scripts() {
	wp_enqueue_script( 'ct-modern-store-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js', array( 'jquery' ), '', true );

}
add_action( 'customize_preview_init', 'ct_modern_store_enqueue_customizer_post_message_scripts' );