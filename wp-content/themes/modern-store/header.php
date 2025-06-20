<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body id="<?php echo esc_attr( get_stylesheet() ); ?>" <?php body_class(); ?>>
	<?php 
	if ( function_exists( 'wp_body_open' ) ) {
				wp_body_open();
		} else {
				do_action( 'wp_body_open' );
	} ?>
	<?php do_action( 'ct_modern_store_body_top' ); ?>
	<a class="skip-content" href="#main-container"><?php esc_html_e( 'Press "Enter" to skip to content', 'modern-store' ); ?></a>
	<div id="overflow-container" class="overflow-container">
		<div id="max-width" class="max-width">
			<?php do_action( 'ct_modern_store_before_header' ); ?>
			<?php
			// Elementor `header` location
			if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
			?>
			<header class="site-header" id="site-header" role="banner">
				<div class="header-top">
					<div id="menu-secondary-container" class="menu-secondary-container">
						<?php get_template_part( 'menu', 'secondary' ); ?>
					</div>
					<div id="social-icons-container" class="social-icons-container">
						<?php ct_modern_store_social_icons_output(); ?>
					</div>
				</div>
				<div class="header-middle">
					<div id="title-container" class="title-container">
						<?php get_template_part( 'logo' ) ?>
						<?php if ( get_bloginfo( 'description' ) ) {
							echo '<p class="tagline">' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
						} ?>
					</div>
					<?php if ( ct_modern_store_is_wc_active() ) : ?>
						<?php get_template_part( 'content/search-bar' ); ?>
						<?php if ( get_theme_mod( 'user_icon_display' ) != 'no' ) : ?>
							<div id="user-account-icon-container" class="user-account-icon-container">
								<div class="user-icon">
									<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" title="<?php esc_attr_e( 'Visit your account', 'modern-store' ); ?>">
										<?php 
										if ( get_theme_mod('user_icon_gravatar') == 'yes' ) {
											echo get_avatar( get_current_user_id(), 34, '', __('Member avatar', 'modern-store'));
										} else {
											echo '<i class="fas fa-user"></i>';
										}?>
									</a>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( get_theme_mod( 'shopping_cart_display' ) != 'no' ) : ?>
							<div id="shopping-cart-container" class="shopping-cart-container">
								<div class="cart-icon">
									<a class="shopping-cart-icon" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Visit your shopping cart', 'modern-store' ); ?>">
										<i class="fa fa-shopping-cart"></i>
										<?php if ( get_theme_mod( 'shopping_cart_display_count' ) != 'no' ) : ?>
											<span class="cart-count">
												<?php echo absint(WC()->cart->get_cart_contents_count()); ?>
											</span>
										<?php endif; ?>
									</a>
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="header-bottom">
					<div id="mobile-menu-container" class="mobile-menu-container">
						<div id="mobile-menu-container-inner">
							<div id="close-mobile-menu" class="close-mobile-menu">
								<button>
									<?php echo ct_modern_store_svg_output( 'close-menu' ); ?>
								</button>
							</div>
							<div id="menu-primary-container" class="menu-primary-container">
								<?php get_template_part( 'menu', 'primary' ); ?>
							</div>
						</div>
					</div>
					<div id="toggle-container" class="toggle-container">
						<button id="toggle-navigation" class="toggle-navigation" name="toggle-navigation" aria-expanded="false">
							<span class="screen-reader-text"><?php esc_html_e( 'open menu', 'modern-store' ); ?></span>
							<?php echo ct_modern_store_svg_output( 'toggle-navigation' ); ?>
						</button>
					</div>
				</div>
			</header>
			<?php endif; ?>
			<?php get_template_part( 'content/header-promo' ); ?>
			<?php do_action( 'ct_modern_store_after_header' ); ?>
			<section id="main-container" class="main-container" role="main">
				<?php do_action( 'ct_modern_store_main_top' );
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
				}
