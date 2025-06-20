<?php

function ct_modern_store_register_theme_page() {
	// translators: %s is the name of the theme and will be filled in programmatically
	add_theme_page( 
		sprintf( esc_html__( '%s Dashboard', 'modern-store' ), wp_get_theme() ), 
		sprintf( esc_html__( '%s Dashboard', 'modern-store' ), wp_get_theme() ), 
		'edit_theme_options', 
		'modern-store-options', 
		'ct_modern_store_options_content' );
}
add_action( 'admin_menu', 'ct_modern_store_register_theme_page' );

function ct_modern_store_options_content() {

	$pro_url = 'https://www.competethemes.com/modern-store-pro/?utm_source=wp-dashboard&utm_medium=Dashboard&utm_campaign=Modern%20Store%20Pro%20-%20Dashboard';
	?>
	<div id="modern-store-dashboard-wrap" class="wrap modern-store-dashboard-wrap">
		<h2>
			<?php // translators: %s is the name of the theme and will be filled in programmatically
			printf( esc_html__( '%s Dashboard', 'modern-store' ), wp_get_theme() ); ?>
		</h2>
		<?php do_action( 'ct_modern_store_theme_options_before' ); ?>
		<div class="main">
			<?php if ( defined( 'MODERN_STORE_PRO_FILE' ) ) : ?>
			<div class="thanks-upgrading" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3><?php esc_html_e( 'Thanks for upgrading!', 'modern-store' ); ?></h3>
				<p><?php esc_html_e( 'You can find the new features in the Customizer', 'modern-store' ); ?></p>
			</div>
			<?php endif; ?>
			<?php if ( !defined( 'MODERN_STORE_PRO_FILE' ) ) : ?>
			<div class="getting-started">
				<h3><?php esc_html_e( 'Get Started with Modern Store', 'modern-store' ); ?></h3>
				<p><?php esc_html_e( 'Follow this step-by-step guide to customize your website with Modern Store:', 'modern-store' ); ?></p>
				<a href="https://www.competethemes.com/help/getting-started-modern-store/" target="_blank"><?php esc_html_e( 'Read the Getting Started Guide', 'modern-store' ); ?></a>
			</div>
			<div class="pro">
				<h3><?php esc_html_e( 'Customize More with Modern Store Pro', 'modern-store'); ?></h3>
				<p><?php 
				printf( 
					wp_kses( 
						__('Add 8 amazing new features to your site with the <a href="%s" target="_blank">Modern Store Pro</a> plugin.', 'modern-store' ), 
						array( 'a' => array( 'href' => array(), 'target' => array() ) ) 
					), 
					esc_url( $pro_url ) ); 
				?></p>
				<ul class="feature-list">
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/reorder-homepage.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'Reorder Your Homepage', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Use the new sorting buttons in the Homepage Builder to arrange your homepage exactly how you want.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'Each section updates instantly on your site while you make your changes.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/homepage-sections.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( '4 New Homepage Sections', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Get more control over your homepage with four new customizable sections.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'New sections include Custom Products, Custom Categories, Best-selling Products, and Latest Posts.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/header-promo-slider.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'Header Promo Slider', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Add up to 5 slides to the header promo each with custom text, backgrounds, and colors.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'Choose where each slide displays so you can promote targeted offers.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/layouts.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'New Layouts', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Choose between a left sidebar, right sidebar, or no sidebar layout for any page on your site. Customize the store, homepage, and product pages individually.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/fonts.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'New Fonts', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Choose from over 700+ fonts and change the font across your entire site at once. Tweak the design with 15 different custom font settings in Modern Store Pro.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-videos.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'Featured Videos', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Featured Videos are an easy way to share videos in place of Featured Images. Instantly embed a Youtube video by copying and pasting its URL into an input.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'Modern Store Pro auto-embeds videos from Youtube, Vimeo, DailyMotion, Flickr, Animoto, TED, Blip, Cloudup, FunnyOrDie, Hulu, Vine, WordPress.tv, and VideoPress.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/widget-areas.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'New Widget Areas', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'The flexibility of multiple widget areas can help increase ad revenue and generate more email subscribers. Adding widgets to the new widget areas is easy as dragging-and-dropping.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'Modern Store Pro adds 6 new widget areas.', 'modern-store' ); ?></p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/footer-text.png'; ?>" />
						</div>
						<div class="text">
							<h4><?php esc_html_e( 'Custom Footer Text', 'modern-store' ); ?></h4>
							<p><?php esc_html_e( 'Custom footer text lets you further brand your site. Just start typing to add your own text to the footer.', 'modern-store' ); ?></p>
							<p><?php esc_html_e( 'The footer text supports plain text and full HTML for adding links.', 'modern-store' ); ?></p>
						</div>
					</li>
				</ul>
				<p><?php 
				printf( 
					wp_kses( 
						__('<a href="%s" target="_blank">Click here</a> to view Modern Store Pro now, and see what it can do for your site.', 'modern-store' ), 
						array( 'a' => array( 'href' => array(), 'target' => array() ) ) 
					), 
					esc_url( $pro_url ) ); 
				?></p>
			</div>
			<div class="pro-ad" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3><?php esc_html_e( 'Add Incredible Flexibility to Your Site', 'modern-store' ); ?></h3>
				<p><?php esc_html_e( 'Start customizing with Modern Store Pro today', 'modern-store' ); ?></p>
				<a href="<?php echo esc_url( $pro_url ); ?>" target="_blank"><?php esc_html_e( 'View Modern Store Pro', 'modern-store' ); ?></a>
			</div>
			<?php endif; ?>
		</div>
		<div class="sidebar">
			<div class="dashboard-widget">
				<h4><?php esc_html_e( 'More Amazing Resources', 'modern-store' ); ?></h4>
				<ul>
					<li><a href="https://www.competethemes.com/documentation/modern-store-support-center/" target="_blank"><?php esc_html_e( 'Modern Store Support Center', 'modern-store' ); ?></a></li>
					<li><a href="https://wordpress.org/support/theme/modern-store" target="_blank"><?php esc_html_e( 'Support Forum', 'modern-store' ); ?></a></li>
					<li><a href="https://www.competethemes.com/help/modern-store-changelog/" target="_blank"><?php esc_html_e( 'Changelog', 'modern-store' ); ?></a></li>
					<li><a href="https://www.competethemes.com/help/modern-store-css-snippets/" target="_blank"><?php esc_html_e( 'CSS Snippets', 'modern-store' ); ?></a></li>
					<li><a href="<?php echo esc_url( $pro_url ); ?>" target="_blank"><?php esc_html_e( 'Modern Store Pro', 'modern-store' ); ?></a></li>
				</ul>
			</div>
			<div class="dashboard-widget">
				<h4><?php esc_html_e( 'Reset Customizer Settings', 'modern-store' ); ?></h4>
				<p><?php esc_html_e( "Warning: Clicking this button will erase the Modern Store theme's current settings in the Customizer.", "modern-store" ); ?></p>
				<form method="post">
					<input type="hidden" name="modern_store_reset_customizer" value="modern_store_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'modern_store_reset_customizer_nonce', 'modern_store_reset_customizer_nonce' ); ?>
						<?php submit_button( __('Reset Customizer Settings', 'modern-store' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'ct_modern_store_theme_options_after' ); ?>
	</div>
<?php }