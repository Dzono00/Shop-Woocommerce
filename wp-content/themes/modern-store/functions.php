<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/last-updated-meta-box.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'modern-store',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Modern Store for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'modern-store' )
	)
);

//----------------------------------------------------------------------------------
//	Set content width variable
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_set_content_width' ) ) ) {
	function ct_modern_store_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 700;
		}
	}
}
add_action( 'after_setup_theme', 'ct_modern_store_set_content_width', 0 );

//----------------------------------------------------------------------------------
//	Add theme support for various features, register menus, load text domain
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_theme_setup' ) ) ) {
	function ct_modern_store_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_modern_store_infinite_scroll_render'
		) );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true
		) );

		$defaults = array(
			'width'                  => 1400,
			'height'                 => 500,
			'flex-height'            => true,
			'flex-width'             => true,
			'header-text'            => false
		);
		add_theme_support( 'custom-header', $defaults );

		// Add WooCommerce support
		add_theme_support( 'woocommerce' );
		// Add support for WooCommerce image gallery features
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		add_image_size( 'ct-modern-store-homepage-thumb', 600, 600, true ); // (cropped)

		// Gutenberg - add support for editor styles
		add_theme_support('editor-styles');

		// Gutenberg - wide images
		add_theme_support( 'align-wide' );

		// Gutenberg - modify the font sizes
		add_theme_support( 'editor-font-sizes', array(
			array(
					'name' => __( 'small', 'modern-store' ),
					'shortName' => __( 'S', 'modern-store' ),
					'size' => 12,
					'slug' => 'small'
			),
			array(
					'name' => __( 'regular', 'modern-store' ),
					'shortName' => __( 'M', 'modern-store' ),
					'size' => 16,
					'slug' => 'regular'
			),
			array(
					'name' => __( 'large', 'modern-store' ),
					'shortName' => __( 'L', 'modern-store' ),
					'size' => 24,
					'slug' => 'large'
			),
			array(
					'name' => __( 'larger', 'modern-store' ),
					'shortName' => __( 'XL', 'modern-store' ),
					'size' => 32,
					'slug' => 'larger'
			)
		) );

		register_nav_menus( array(
			'primary' 	=> esc_html__( 'Primary', 'modern-store' ),
			'secondary' => esc_html__( 'Secondary', 'modern-store' )
		) );

		load_theme_textdomain( 'modern-store', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_modern_store_theme_setup' );

//----------------------------------------------------------------------------------
//	Register widget areas
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_register_widget_areas' ) ) ) {
	function ct_modern_store_register_widget_areas() {

		register_sidebar( array(
			'name'          => esc_html__( 'Store Sidebar', 'modern-store' ),
			'id'            => 'store',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar on the store and product archive pages.', 'modern-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_modern_store_register_widget_areas' );


//----------------------------------------------------------------------------------
//	Customize comment markup
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_customize_comments' ) ) ) {
	function ct_modern_store_customize_comments( $comment, $args, $depth ) { ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 36, '', get_comment_author() );
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'modern-store' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<span class="comment-date"><?php comment_date(); ?></span>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html__( 'Reply', 'modern-store' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) ); ?>
				<?php edit_comment_link( esc_html__( 'Edit', 'modern-store' ) ); ?>
			</div>
		</article>
		<?php
	}
}

//----------------------------------------------------------------------------------
//	Remove notes after comment form
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_remove_comments_notes_after' ) ) {
	function ct_modern_store_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_modern_store_remove_comments_notes_after' );

//----------------------------------------------------------------------------------
//	Filter the 'read more' link
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_filter_read_more_link' ) ) {
	function ct_modern_store_filter_read_more_link( $custom = false ) {

		if ( is_feed() ) {
			return;
		}
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'read_more_text' );
		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'modern-store' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_modern_store_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_modern_store_filter_read_more_link', 10 ); // automatic excerpts

//----------------------------------------------------------------------------------
//	Add more link to excerpts
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_filter_manual_excerpts' ) ) {
	function ct_modern_store_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_modern_store_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_modern_store_filter_manual_excerpts' );

//----------------------------------------------------------------------------------
//	Output content VS excerpt based on user settings
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_excerpt' ) ) {
	function ct_modern_store_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

//----------------------------------------------------------------------------------
//	Update automatic excerpt word count based on user settings
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_custom_excerpt_length' ) ) {
	function ct_modern_store_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'ct_modern_store_custom_excerpt_length', 99 );

//----------------------------------------------------------------------------------
//	Turn off scrolling to after excerpt after clicking 'read more' link
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_remove_more_link_scroll' ) ) {
	function ct_modern_store_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_modern_store_remove_more_link_scroll' );

//----------------------------------------------------------------------------------
//	Output Featured Image
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_featured_image' ) ) {
	function ct_modern_store_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			$featured_image = '<div class="featured-image-container">';
			if ( is_singular() && !is_page_template('templates/homepage.php' ) ) {
				$featured_image .= '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image .= '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
			$featured_image .= '</div>';
		}

		$featured_image = apply_filters( 'ct_modern_store_featured_image', $featured_image );

		if ( $featured_image ) {
			echo wp_kses( $featured_image, array(
				'div' => array(
					'class' => array()
				),
				'a'   => array(
					'href' => array()
				),
				'img' => array(
					'src' 	 => array(),
					'srcset' => array(),
					'alt' 	 => array(),
					'id' 		 => array(),
					'class'  => array(),
					'height' => array(),
					'width'  => array(),
					'sizes'  => array()
				),
				// for Featured Videos in Modern Store Pro
				'iframe' => array(
					'src' => array(),
					'id' => array(),
					'title' => array(),
					'frameborder' => array(),
					'allow' => array(),
					'allowfullscreen' => array(),
					'webkitallowfullscreen' => array(),
					'mozallowfullscreen' => array()
				)
			) );
		}
	}
}

//----------------------------------------------------------------------------------
//	Return array of all social sites and IDs
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_social_array' ) ) {
	function ct_modern_store_social_array() {

		$social_sites = array(
			'twitter'       => 'ct_modern_store_twitter_profile',
			'facebook'      => 'ct_modern_store_facebook_profile',
			'instagram'     => 'ct_modern_store_instagram_profile',
			'linkedin'      => 'ct_modern_store_linkedin_profile',
			'pinterest'     => 'ct_modern_store_pinterest_profile',
			'youtube'       => 'ct_modern_store_youtube_profile',
			'email'         => 'ct_modern_store_email_profile',
			'phone'         => 'ct_modern_store_phone_profile',
			'email-form'    => 'ct_modern_store_email_form_profile',
			'amazon'        => 'ct_modern_store_amazon_profile',
			'artstation'    => 'ct_modern_store_artstation_profile',
			'bandcamp'      => 'ct_modern_store_bandcamp_profile',
			'behance'       => 'ct_modern_store_behance_profile',
			'bitbucket'     => 'ct_modern_store_bitbucket_profile',
			'codepen'       => 'ct_modern_store_codepen_profile',
			'delicious'     => 'ct_modern_store_delicious_profile',
			'deviantart'    => 'ct_modern_store_deviantart_profile',
			'digg'          => 'ct_modern_store_digg_profile',
			'discord'       => 'ct_modern_store_discord_profile',
			'dribbble'      => 'ct_modern_store_dribbble_profile',
			'etsy'          => 'ct_modern_store_etsy_profile',
			'flickr'        => 'ct_modern_store_flickr_profile',
			'foursquare'    => 'ct_modern_store_foursquare_profile',
			'github'        => 'ct_modern_store_github_profile',
			'goodreads' 		=> 'ct_modern_store_goodreads_profile',
			'google-wallet' => 'ct_modern_store_google_wallet_profile',
			'hacker-news'   => 'ct_modern_store_hacker-news_profile',
			'medium'        => 'ct_modern_store_medium_profile',
			'meetup'        => 'ct_modern_store_meetup_profile',
			'mixcloud'      => 'ct_modern_store_mixcloud_profile',
			'ok-ru'         => 'ct_modern_store_ok_ru_profile',
			'orcid'         => 'ct_modern_store_orcid_profile',
			'patreon'       => 'ct_modern_store_patreon_profile',
			'paypal'        => 'ct_modern_store_paypal_profile',
			'pocket'        => 'ct_modern_store_pocket_profile',
			'podcast'       => 'ct_modern_store_podcast_profile',
			'quora'         => 'ct_modern_store_quora_profile',
			'qq'            => 'ct_modern_store_qq_profile',
			'ravelry'       => 'ct_modern_store_ravelry_profile',
			'reddit'        => 'ct_modern_store_reddit_profile',
			'researchgate'  => 'ct_modern_store_researchgate_profile',
			'rss'           => 'ct_modern_store_rss_profile',
			'skype'         => 'ct_modern_store_skype_profile',
			'slack'         => 'ct_modern_store_slack_profile',
			'slideshare'    => 'ct_modern_store_slideshare_profile',
			'snapchat'      => 'ct_modern_store_snapchat_profile',
			'soundcloud'    => 'ct_modern_store_soundcloud_profile',
			'spotify'       => 'ct_modern_store_spotify_profile',
			'stack-overflow' => 'ct_modern_store_stack_overflow_profile',
			'steam'         => 'ct_modern_store_steam_profile',
			'strava'        => 'ct_modern_strava_steam_profile',
			'stumbleupon'   => 'ct_modern_store_stumbleupon_profile',
			'telegram'      => 'ct_modern_store_telegram_profile',
			'tencent-weibo' => 'ct_modern_store_tencent_weibo_profile',
			'tumblr'        => 'ct_modern_store_tumblr_profile',
			'twitch'        => 'ct_modern_store_twitch_profile',
			'untappd'       => 'ct_modern_store_untappd_profile',
			'vimeo'         => 'ct_modern_store_vimeo_profile',
			'vine'          => 'ct_modern_store_vine_profile',
			'vk'            => 'ct_modern_store_vk_profile',
			'wechat'        => 'ct_modern_store_wechat_profile',
			'weibo'         => 'ct_modern_store_weibo_profile',
			'whatsapp'      => 'ct_modern_store_whatsapp_profile',
			'xing'          => 'ct_modern_store_xing_profile',
			'yahoo'         => 'ct_modern_store_yahoo_profile',
			'yelp'          => 'ct_modern_store_yelp_profile',
			'500px'         => 'ct_modern_store_500px_profile'
		);

		return apply_filters( 'ct_modern_store_social_array_filter', $social_sites );
	}
}

//----------------------------------------------------------------------------------
//	Output social iconss
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_social_icons_output' ) ) {
	function ct_modern_store_social_icons_output() {

		$social_sites = ct_modern_store_social_array();

		// store the site name and url
		foreach ( $social_sites as $social_site => $profile ) {

			if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
				$active_sites[ $social_site ] = $social_site;
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				if ( $active_site == 'rss' ) {
					$class = 'fas fa-rss';
				} elseif ( $active_site == 'email-form' ) {
					$class = 'far fa-envelope';
				} elseif ( $active_site == 'podcast' ) {
					$class = 'fas fa-podcast';
				} elseif ( $active_site == 'ok-ru' ) {
					$class = 'fab fa-odnoklassniki';
				} elseif ( $active_site == 'wechat' ) {
					$class = 'fab fa-weixin';
				} elseif ( $active_site == 'phone' ) {
					$class = 'fas fa-phone';
				} elseif ( $active_site == 'pocket' ) {
					$class = 'fab fa-get-pocket';
				} else {
					$class = 'fab fa-' . $active_site;
				}
				
				$url = get_theme_mod( $key );
				
				echo '<li>';
				if ( $active_site == 'email' ) { ?>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( $url ) ); ?>">
						<i class="fa fa-envelope" title="<?php echo antispambot( $url ) ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'skype' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
				<?php } elseif ( $active_site == 'phone' ) { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'tel' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo str_replace('tel:', '', esc_url( $url, array( 'tel' ) )); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				<?php } else { ?>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $key ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"
						   title="<?php echo esc_attr( $active_site ); ?>"></i>
					</a>
					<?php
				}
				echo '</li>';
			}
			echo "</ul>";
		}
	}
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
if ( ! function_exists( ( 'ct_modern_store_wp_page_menu' ) ) ) {
	function ct_modern_store_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => - 1
			)
		);
	}
}

//----------------------------------------------------------------------------------
//	Output dropdown buttons for mobile menu
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_nav_dropdown_buttons' ) ) ) {
	function ct_modern_store_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' || $args->theme_location == 'secondary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false" name="toggle-dropdown"><span class="screen-reader-text">' . esc_html_x( "open menu", "verb: open the menu", "modern-store" ) . '</span><i class="fas fa-angle-down"></i></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_modern_store_nav_dropdown_buttons', 10, 4 );

//----------------------------------------------------------------------------------
//	Add featured status marker to sticky posts
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_sticky_post_marker' ) ) ) {
	function ct_modern_store_sticky_post_marker() {

		if ( is_sticky() && ! is_archive() ) {
			echo '<div class="sticky-status"><span>' . esc_html__( "Featured", "modern-store" ) . '</span></div>';
		}
	}
}
add_action( 'sticky_post_status', 'ct_modern_store_sticky_post_marker' );

//----------------------------------------------------------------------------------
//	Reset the Customizer options after user clicks reset button in theme options page
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_reset_customizer_options' ) ) ) {
	function ct_modern_store_reset_customizer_options() {

		if ( !isset( $_POST['modern_store_reset_customizer'] ) || !isset( $_POST['modern_store_reset_customizer_nonce'] ) ) {
			return;
		}

		if ( 'modern_store_reset_customizer_settings' !== $_POST['modern_store_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['modern_store_reset_customizer_nonce'] ), 'modern_store_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'homepage_on_sale_products',
			'homepage_on_sale_products_title',
			'homepage_on_sale_products_count',
			'homepage_on_sale_products_order',
			'homepage_latest_products',
			'homepage_latest_products_title',
			'homepage_latest_products_count',
			'homepage_latest_products_category',
			'homepage_top_rated_products',
			'homepage_top_rated_products_title',
			'homepage_top_rated_products_count',
			'homepage_featured_products',
			'homepage_featured_products_title',
			'homepage_featured_products_count',
			'homepage_featured_products_order',
			'homepage_categories',
			'homepage_categories_title',
			'homepage_categories_count',
			'homepage_categories_subcategory_display',
			'homepage_categories_thumbnail',
			'homepage_categories_product_count',
			'homepage_categories_order',
			'header_promo_display_slide_1',
			'header_promo_title_text_slide_1',
			'header_promo_subtitle_text_slide_1',
			'header_promo_button_text_slide_1',
			'header_promo_button_url_slide_1',
			'header_promo_text_alignment_slide_1',
			'header_promo_text_width_slide_1',
			'header_promo_image_slide_1',
			'header_promo_height_slide_1',
			'header_promo_overlay_color_slide_1',
			'header_promo_overlay_opacity_slide_1',
			'color_primary',
			'store_search_bar_display',
			'store_search_bar_category_select',
			'store_search_bar_submit_button',
			'store_search_bar_category_select_label',
			'user_icon_display',
			'user_icon_mobile_display',
			'shopping_cart_display',
			'shopping_cart_display_count',
			'shopping_cart_mobile_display',
			'fi_size_type',
			'fi_size',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'last_updated',
			'scroll_to_top'
		);

		$social_sites = ct_modern_store_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_modern_store_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		// And reset the homepage order
		delete_option( 'ct_modern_store_homepage_order' );

		$redirect = admin_url( 'themes.php?page=modern-store-options' );
		$redirect = add_query_arg( 'modern_store_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_modern_store_reset_customizer_options' );

//----------------------------------------------------------------------------------
//	Admin notice that Customizer settings were deleted
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_delete_settings_notice' ) ) ) {
	function ct_modern_store_delete_settings_notice() {

		if ( isset( $_GET['modern_store_status'] ) ) {
			?>
			<div class="updated">
				<p><?php esc_html_e( 'Customizer settings deleted', 'modern-store' ); ?>.</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'ct_modern_store_delete_settings_notice' );

//----------------------------------------------------------------------------------
//	Add classes to body for styling
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_body_class' ) ) ) {
	function ct_modern_store_body_class( $classes ) {

		global $post;
		$full_post = get_theme_mod( 'full_post' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}
		if ( ct_modern_store_is_wc_active() ) {

			if ( is_shop() ) {
				$classes[] = 'woocommerce-shop';
			}
			if ( is_product_category() || is_product_tag() ) {
				$classes[] = 'woocommerce-archive';
			}
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_modern_store_body_class' );

//----------------------------------------------------------------------------------
//	Add a shared class for post divs on archive and single pages
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_post_class' ) ) ) {
	function ct_modern_store_post_class( $classes ) {
		$classes[] = 'entry';
		return $classes;
	}
}
add_filter( 'post_class', 'ct_modern_store_post_class' );

//----------------------------------------------------------------------------------
//	Output SVGs in place of images or icon fonts
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_svg_output' ) ) ) {
	function ct_modern_store_svg_output( $type ) {

		$svg = '';
		if ( $type == 'toggle-navigation' ) {
			$svg = '<svg width="36px" height="24px" viewBox="0 0 36 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<g transform="translate(-198.000000, -90.000000)" fill="#FFFFFF">
												<g transform="translate(0.000000, 18.000000)">
														<g transform="translate(0.000000, 60.000000)">
																<g transform="translate(141.000000, 12.000000)">
																		<g transform="translate(57.000000, 0.000000)">
																				<rect x="0" y="22" width="36" height="2"></rect>
																				<rect x="0" y="11" width="36" height="2"></rect>
																				<rect x="0" y="0" width="36" height="2"></rect>
																		</g>
																</g>
														</g>
												</g>
										</g>
								</g>
						</svg>';
		} elseif ( $type == 'close-menu' ) {
			$svg = '<svg width="28px" height="28px" viewBox="0 0 28 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<g transform="translate(-174.000000, -18.000000)" fill="#FFFFFF">
												<g transform="translate(174.000000, 18.000000)">
														<rect transform="translate(14.000000, 14.000000) rotate(-45.000000) translate(-14.000000, -14.000000) " x="-4" y="13" width="36" height="2"></rect>
														<rect transform="translate(14.000000, 14.000000) rotate(45.000000) translate(-14.000000, -14.000000) " x="-4" y="13" width="36" height="2"></rect>
												</g>
										</g>
								</g>
						</svg>';
		}

		return $svg;
	}
}

//----------------------------------------------------------------------------------
//	Output meta elements in the <head>
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_add_meta_elements' ) ) ) {
	function ct_modern_store_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo wp_kses( $meta_elements, array(
			'meta' => array(
				'charset' 	 		=> array(),
				'name' 					=> array(),
				'content' 	 		=> array(),
				'initial-scale' => array()
			)
		) );
	}
}
add_action( 'wp_head', 'ct_modern_store_add_meta_elements', 1 );

//----------------------------------------------------------------------------------
//	Select proper template part for Jetpack infinite scroll compatibility
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_modern_store_infinite_scroll_render' ) ) ) {
	function ct_modern_store_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

//----------------------------------------------------------------------------------
//	Get correct template part from main loop
//	Routing templates this way to follow DRY coding patterns 
//	(Loop is only present in index.php)
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_get_content_template' ) ) {
	function ct_modern_store_get_content_template() {

		if ( is_home() || is_archive() || is_search() ) {
			get_template_part( 'content-archive', get_post_type() );
		} else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

//----------------------------------------------------------------------------------
//	Allow Skype URIs to be used
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_allow_skype_protocol' ) ) {
	function ct_modern_store_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_modern_store_allow_skype_protocol' );

//----------------------------------------------------------------------------------
//	Remove label that can't be edited with the_archive_title() e.g. "Category: Business" => "Business"
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_modify_archive_titles' ) ) {
	function ct_modern_store_modify_archive_titles( $title ) {

		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_month() ) {
			$title = single_month_title( ' ' );
		}
		// is_year() and is_day() neglected b/c there is no analogous function for retrieving the page title

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'ct_modern_store_modify_archive_titles' );

//--------------------------------------------------------------------------------------------------
// Sanitize CSS then convert "&gt;" back into ">" character so direct descendant CSS selectors work
//--------------------------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_sanitize_css' ) ) {
	function ct_modern_store_sanitize_css( $css ) {
		$css = wp_kses( $css, '' );
		$css = str_replace( '&gt;', '>', $css );

		return $css;
	}
}

//----------------------------------------------------------------------------------
// Return CSS based on the user's Customizer selected colors.
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_override_colors' ) ) {
	function ct_modern_store_override_colors() {

		$color_css     = '';
		$primary_color = get_theme_mod( 'color_primary' );

		if ( $primary_color != '' && $primary_color != '#ffc270' ) {
			$color_css = "a:hover,a:active,a:focus,.design-credit a:hover,.design-credit a:active,.design-credit a:focus,.menu-primary ul li.current-menu-item > a:link,.menu-primary ul li.current-menu-item > a:visited,
			.menu-primary ul li.current_page_item > a:link,.menu-primary ul li.current_page_item > a:visited,
			.widget_rating_filter .star-rating,.woocommerce.single-product .star-rating,.woocommerce.single-product #reviews #comments .review .star-rating,.woocommerce.single-product .comment-form .stars a,
			.woocommerce ul.products li.product .star-rating,.woocommerce-message:before,.woocommerce-info:before,.user-icon a:hover, .user-icon a:active, .user-icon a:focus, .cart-icon a:hover, .cart-icon a:active, .cart-icon a:focus,
			.woocommerce-cart .shopping-cart-container a,.woocommerce-account .user-account-icon-container a,#cancel-comment-reply-link:link, .comment-reply-link:link, .comment-edit-link:link, #cancel-comment-reply-link:visited,
			.comment-reply-link:visited, .comment-edit-link:visited,.widget_products .star-rating,.woocommerce ul.cart_list .star-rating,.woocommerce ul.product_list_widget .star-rating {
			  color: $primary_color;
			}";
			$color_css .= "input[type='submit']:hover,input[type='submit']:active,input[type='submit']:focus,.comment-form .form-submit input:hover,.post-tags a:hover,
			.post-tags a:active,.post-tags a:focus,.site-header .search-form-container .submit-button,.woocommerce .widget_price_filter .ui-slider-horizontal .ui-slider-range,.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
			.woocommerce .widget_price_filter .button:hover,.woocommerce .widget_price_filter .button:active,.woocommerce .widget_price_filter .button:focus,.woocommerce-cart .woocommerce-cart-form__contents .actions > .button:hover,
			.woocommerce-cart .woocommerce-cart-form__contents .actions > .button:active,.woocommerce-cart .woocommerce-cart-form__contents .actions > .button:focus,.woocommerce-cart .cart-collaterals .cart_totals .checkout-button:hover,
			.woocommerce-cart .cart-collaterals .cart_totals .checkout-button:active,.woocommerce-cart .cart-collaterals .cart_totals .checkout-button:focus,.woocommerce-checkout .checkout #place_order:hover,.woocommerce-checkout .checkout #place_order:active,
			.woocommerce-checkout .checkout #place_order:focus,.woocommerce.single-product .entry .onsale,.woocommerce.single-product form .single_add_to_cart_button:hover,.woocommerce.single-product form .single_add_to_cart_button:active,
			.woocommerce.single-product form .single_add_to_cart_button:focus,.woocommerce.single-product #review_form #respond .form-submit input:hover,.woocommerce ul.products li.product .button:hover,.woocommerce ul.products li.product .button:active,
			.woocommerce ul.products li.product .button:focus,.woocommerce ul.products li.product .onsale,.woocommerce nav.woocommerce-pagination ul li a:hover,.woocommerce nav.woocommerce-pagination ul li a:active,.woocommerce nav.woocommerce-pagination ul li a:focus,
			.woocommerce a.button:hover,.woocommerce a.button:active,.woocommerce a.button:focus,.woocommerce-message a.button:hover,.woocommerce-message a.button:active,.woocommerce-message a.button:focus,.woocommerce button.button:hover,
			.woocommerce button.button:active,.woocommerce button.button:focus,.woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__cell-order-actions .button:hover,.woocommerce-store-notice,p.demo_store,
			.cart-count, .social-media-icons a:hover,.social-media-icons a:active,.social-media-icons a:focus {
				background: $primary_color;
			}";
			$color_css .= "blockquote,.wp-block-quote,.wp-block-quote.is-style-large,input[type='text']:focus,input[type='email']:focus,input[type='password']:focus,input[type='number']:focus,input[type='search']:focus,
			input[type='tel']:focus,input[type='url']:focus,textarea:focus,.comment-form input:focus,.comment-form textarea:focus,.pagination a:hover,.pagination a:active,.pagination a:focus,.blog .featured-image-container:hover,.archive .featured-image-container:hover,.search-results .featured-image-container:hover,.page-template-homepage .featured-image-container:hover,.toggle-dropdown.open,.more-link:hover,.more-link:active,.more-link:focus,
			.post-tags a:hover,.post-tags a:active,.post-tags a:focus,.further-reading div:hover,.site-header .search-form-container .search-field:focus,.search .main-container .search-form .search-field:focus,
			.woocommerce-cart .woocommerce-cart-form__contents .product-quantity input:focus,.woocommerce-cart .woocommerce-cart-form__contents .coupon .input-text:focus,.woocommerce.single-product form .quantity input:focus,
			.woocommerce ul.products li.product .button,.woocommerce-message,.woocommerce-info, .social-media-icons a:hover,.social-media-icons a:active,.social-media-icons a:focus {
				border-color: $primary_color;
			}";
		}
		return $color_css;
	}
}

//----------------------------------------------------------------------------------
// Output the user's custom colors
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_color_css' ) ) {
	function ct_modern_store_output_color_css() {
		if ( !is_rtl() ) {
			$color_css = ct_modern_store_override_colors();
			if ( !empty( $color_css ) ) {
				wp_add_inline_style( 'ct-modern-store-style', ct_modern_store_sanitize_css( $color_css ) );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_output_color_css', 20 );

//----------------------------------------------------------------------------------
// Output differently for RTL b/c RTL stylesheets have no handle (not enqueued!!) and the <link>
// element is output so late
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_color_css_rtl' ) ) {
	function ct_modern_store_output_color_css_rtl() {
		if ( is_rtl() ) {
			$color_css = ct_modern_store_override_colors();
			if ( !empty($color_css) ) {
				echo '<style id="ct-modern-store-style-inline-css" type="text/css">'. ct_modern_store_sanitize_css( $color_css ) .'</style>';
			}
		}
	}
}
add_action( 'wp_head', 'ct_modern_store_output_color_css_rtl', 99 );

//----------------------------------------------------------------------------------
// Output the "Last Updated" date on posts
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_last_updated_date' ) ) {
	function ct_modern_store_output_last_updated_date() {
		
		global $post;

		if ( get_the_modified_date() != get_the_date() ) {
			$updated_post = get_post_meta( $post->ID, 'ct_modern_store_last_updated', true );
			$updated_customizer = get_theme_mod( 'last_updated' );
			if ( 
				( $updated_customizer == 'yes' && ($updated_post != 'no') )
				|| $updated_post == 'yes' 
				) {
					echo '<p class="last-updated">'. esc_html__("Last updated on", "modern-store") . ' ' . esc_html( get_the_modified_date() ) . ' </p>';
				}
		}
	}
}
//----------------------------------------------------------------------------------
// Output the markup for the optional scroll-to-top arrow 
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_scroll_to_top_arrow' ) ) {
	function ct_modern_store_scroll_to_top_arrow() {
		$setting = get_theme_mod('scroll_to_top');
		
		if ( $setting == 'yes' ) {
			echo '<button id="scroll-to-top" class="scroll-to-top"><span class="screen-reader-text">'. esc_html__('Scroll to the top', 'modern-store') .'</span><i class="fas fa-arrow-up"></i></button>';
		}
	}
}
add_action( 'ct_modern_store_body_bottom', 'ct_modern_store_scroll_to_top_arrow');

//----------------------------------------------------------------------------------
// Output CSS to style height of Featured Images based on Customizer setting
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_fi_styles' ) ) {
	function ct_modern_store_output_fi_styles() {

		$css = '';
		$fi_size_type = get_theme_mod( 'fi_size_type' );

		if ( $fi_size_type == 'no' ) {
			$css .= ".featured-image { 
				padding-bottom: 0; 
				height: auto;
			}";
			$css .= ".featured-image > a, .featured-image > a > img, .featured-image > img { 
				position: static;
			}";
		} else {
			$fi_size = get_theme_mod( 'fi_size' );
			if ( !empty($fi_size) && $fi_size != 50 ) {
				$css .= ".featured-image { padding-bottom: $fi_size%; }";
			}
		}

		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_output_fi_styles', 99 );

//----------------------------------------------------------------------------------
// Output CSS to update search form styles based on which elements are hidden
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_update_search_form_styles' ) ) {
	function ct_modern_store_update_search_form_styles() {

		$css = '';
		$cat_select 	 = get_theme_mod( 'store_search_bar_category_select' );
		$submit_button = get_theme_mod( 'store_search_bar_submit_button' );

		if ( $cat_select == 'no' ) {
			$css .= ".site-header .search-form-container .search-field {
				border-radius: 18px 0 0 18px;
			}";
		}
		if ( $submit_button == 'no' ) {
			$css .= ".site-header .search-form-container .search-field {
				border-radius: 0 18px 18px 0;	
			}";
		}
		if ( $cat_select == 'no' && $submit_button == 'no' ) {
			$css .= ".site-header .search-form-container .search-field {
				border-radius: 18px;	
			}";
		}

		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_update_search_form_styles', 20 );

//----------------------------------------------------------------------------------
// Update styles for user account icon
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_update_user_icon_styles' ) ) {
	function ct_modern_store_update_user_icon_styles() {

		$css = '';

		if ( get_theme_mod( 'user_icon_mobile_display' ) == 'no' ) {
			$css .= '@media all and (max-width: 799px) {
				.user-account-icon-container {
					display: none;
				}
			}';
		}

		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_update_user_icon_styles', 20 );

//----------------------------------------------------------------------------------
// Update styles for shopping cart icon
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_update_shopping_cart_styles' ) ) {
	function ct_modern_store_update_shopping_cart_styles() {

		$css = '';

		if ( get_theme_mod( 'shopping_cart_mobile_display' ) == 'no' ) {
			$css .= '@media all and (max-width: 799px) {
				.shopping-cart-container {
					display: none;
				}
			}';
		}

		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_filter( 'wp_enqueue_scripts', 'ct_modern_store_update_shopping_cart_styles', 20 );

//----------------------------------------------------------------------------------
// Update cart count in shopping cart live
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_update_cart_count' ) ) {
	function ct_modern_store_update_cart_count( $fragments ) {
		global $woocommerce;

		ob_start();
		?>
		<a class="shopping-cart-icon" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'Visit your shopping cart', 'modern-store' ); ?>">
			<i class="fa fa-shopping-cart"></i>
			<?php if ( get_theme_mod( 'shopping_cart_display_count' ) != 'no' ) : ?>
				<span class="cart-count">
					<?php echo absint(WC()->cart->get_cart_contents_count()); ?>
				</span>
			<?php endif; ?>
		</a>
		<?php
		$fragments['a.shopping-cart-icon'] = ob_get_clean();
		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ct_modern_store_update_cart_count' );

//----------------------------------------------------------------------------------
// Determine if the header promo should be output
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_header_promo_output_rules' ) ) {
	function ct_modern_store_header_promo_output_rules( $slide ) {
		
		$display = get_theme_mod( 'header_promo_display_' . $slide ) ? get_theme_mod( 'header_promo_display_' . $slide ) : array();
		if ( $slide == 'slide_1' && empty( $display ) ) {
			$display = array('homepage');
		}
		$output = false;

		if ( is_front_page() && in_array( 'homepage', $display) ) {
			$output = true;
		}
		if ( is_home() && in_array( 'blog', $display) ) {
			$output = true;
		}
		if ( is_singular('post') && in_array( 'posts', $display) ) {
			$output = true;
		}
		if ( is_singular('page') && !is_front_page() && in_array( 'pages', $display) ) {
			$output = true;
		}
		if ( is_archive() && in_array( 'archives', $display) ) {
			$output = true;
		}
		if ( is_search() && in_array( 'search', $display) ) {
			$output = true;
		}
		if ( ct_modern_store_is_wc_active() ) {
			if ( is_shop() && in_array( 'store', $display) ) {
				$output = true;
			}
			if ( is_product_category() && in_array( 'store-archives', $display) ) {
				$output = true;
			}
			if ( is_product() && in_array( 'products', $display) ) {
				$output = true;
			}
		}

		return $output;
	}
}

//----------------------------------------------------------------------------------
// Check if WooCommerce is active
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_is_wc_active' ) ) {
	function ct_modern_store_is_wc_active() {
		return class_exists( 'woocommerce' );
	}
}

//----------------------------------------------------------------------------------
// Header promo styles
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_header_promo_styles' ) ) {
	function ct_modern_store_output_header_promo_styles() {

		$slides = array( 'slide_1', 'slide_2', 'slide_3', 'slide_4', 'slide_5');
		$css = '';

		foreach ( $slides as $slide ) {
			
			// Don't output if not displayed based on display rules
			if ( ct_modern_store_header_promo_output_rules( $slide ) == false ) continue;
				
			// Handle slide with different naming convention due to TRT requiring Custom Header
			if ( $slide == 'slide_1' ) {
				$background_image = get_theme_mod( 'header_image' );
				if ( is_random_header_image() ) {
					$background_image = get_random_header_image();
				}
			} else {
				$background_image = get_theme_mod( 'header_promo_image_' . $slide );
			}

			// Don't add styles if no image is set
			if ( $background_image == '' ) continue;

			$text_alignment 	= get_theme_mod( 'header_promo_text_alignment_' . $slide );
			$text_width 			= get_theme_mod( 'header_promo_text_width_' . $slide );
			$height 					= get_theme_mod( 'header_promo_height_' . $slide );
			$overlay_color 		= get_theme_mod( 'header_promo_overlay_color_' . $slide );
			$overlay_opacity 	= get_theme_mod( 'header_promo_overlay_opacity_' . $slide );

			$css .= "#header-promo .$slide .background { background-image: url('$background_image');}";
			if ( $text_alignment == 'center' ) {
				$css .= ".header-promo .$slide {
					justify-content: center;
					text-align: center;
				}";
				$css .= ".header-promo .$slide .content {
					padding-left: 0;	
				}";
			} elseif ( $text_alignment == 'right' ) {
				$css .= ".header-promo .$slide {
					justify-content: flex-end;
					text-align: right;
				}";
				$css .= ".header-promo .$slide .content {
					padding: 0 6.25% 0 0;
				}";
			}
			if ( !empty( $text_width) && $text_width != 50 ) {
				$css .= ".header-promo .$slide .content { width: $text_width%; }";
			}
			if ( !empty( $height ) && $height != 60 ) {
				$css .= ".header-promo .$slide { height: ". $height ."vh; }";
			}
			if ( !empty( $overlay_color) && $overlay_color != '#000000' ) {
				$css .= ".header-promo .$slide .overlay { background: $overlay_color; }";
			}
			if ( !empty( $overlay_opacity) && $overlay_opacity != 0 ) {
				$css .= ".header-promo .$slide .overlay { opacity: $overlay_opacity; }";
			}
		}
		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_output_header_promo_styles', 99 );

//----------------------------------------------------------------------------------
// Output products based on user settings (used in templates/homepage.php)
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_output_wc_products' ) ) {
	function ct_modern_store_output_wc_products( $setting ) {

		if ( !ct_modern_store_is_wc_active() ) return;
		
		if ( $setting == 'modern_store_homepage_on_sale_products' ) {
			if ( get_theme_mod( 'homepage_on_sale_products' ) == 'no' ) {
				return;
			}
			$id 	 = 'on-sale-products';
			$class = 'on-sale-products woocommerce';
			$title = get_theme_mod( 'homepage_on_sale_products_title' ) ? get_theme_mod( 'homepage_on_sale_products_title' ) : __( 'On Sale Products', 'modern-store' );
			$count = get_theme_mod( 'homepage_on_sale_products_count' ) ? get_theme_mod( 'homepage_on_sale_products_count' ) : 4;
			$order = get_theme_mod( 'homepage_on_sale_products_order' ) ? get_theme_mod( 'homepage_on_sale_products_order' ) : 'newest';
			$loop_args = array(
				'post_type'      => 'product',
				'posts_per_page' => $count,
				'meta_query'     => WC()->query->get_meta_query(),
				'post__in'       => array_merge( array( 0 ), wc_get_product_ids_on_sale() ),
			);
			if ( $order == 'oldest' ) {
				$loop_args['order'] = 'ASC';
			}
			if ( $order == 'cheapest' || $order == 'most-expensive' ) {
				$loop_args['orderby']  = 'meta_value_num';
				$loop_args['meta_key'] = '_price';
			}
			if ( $order == 'cheapest' ) {
				$loop_args['order'] = 'ASC';
			}
		} elseif ( $setting == 'modern_store_homepage_latest_products' ) {
			if ( get_theme_mod( 'homepage_latest_products' ) == 'no' ) {
				return;
			}
			$id 	 		= 'latest-products';
			$class 		= 'latest-products woocommerce';
			$title 		= get_theme_mod( 'homepage_latest_products_title' ) ? get_theme_mod( 'homepage_latest_products_title' ) : __( 'Latest Products', 'modern-store' );
			$count 		= get_theme_mod( 'homepage_latest_products_count' ) ? get_theme_mod( 'homepage_latest_products_count' ) : 7;
			$category = get_theme_mod( 'homepage_latest_products_category' ) ? get_theme_mod( 'homepage_latest_products_category' ) : 'all';
			$loop_args = array(
				'post_type'      => 'product',
				'posts_per_page' => $count,
				'tax_query'      => array(
					array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $category,
							'operator' => 'IN'
					),
					array(
							'taxonomy' => 'product_visibility',
							'field'    => 'slug',
							'terms'    => 'exclude-from-catalog',
							'operator' => 'NOT IN'
					)
				)
			);
		} elseif ( $setting == 'modern_store_homepage_top_rated_products' ) {
			if ( get_theme_mod( 'homepage_top_rated_products' ) == 'no' ) {
				return;
			}
			$id 	 = 'top-rated-products';
			$class = 'top-rated-products woocommerce';
			$title = get_theme_mod( 'homepage_top_rated_products_title' ) ? get_theme_mod( 'homepage_top_rated_products_title' ) : __( 'Top-rated Products', 'modern-store' );
			$count = get_theme_mod( 'homepage_top_rated_products_count' ) ? get_theme_mod( 'homepage_top_rated_products_count' ) : 4;
			$loop_args = array(
				'post_type'      => 'product',
				'posts_per_page' => $count,
				'meta_key'       => '_wc_average_rating',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
			);
		} elseif ( $setting == 'modern_store_homepage_featured_products' ) {
			// only free section disabled by default
			if ( get_theme_mod( 'homepage_featured_products' ) != 'yes' ) {
				return;
			}
			$id 	 = 'featured-products';
			$class = 'featured-products woocommerce';
			$title = get_theme_mod( 'homepage_featured_products_title' ) ? get_theme_mod( 'homepage_featured_products_title' ) : __( 'Featured Products', 'modern-store' );
			$count = get_theme_mod( 'homepage_featured_products_count' ) ? get_theme_mod( 'homepage_featured_products_count' ) : 4;
			$order = get_theme_mod( 'homepage_featured_products_order' ) ? get_theme_mod( 'homepage_featured_products_order' ) : 'newest';
			$loop_args = array(
				'post_type'      => 'product',
				'posts_per_page' => $count,
				'tax_query' => array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					)
				)
			);
			if ( $order == 'oldest' ) {
				$loop_args['order'] = 'ASC';
			}
			if ( $order == 'cheapest' || $order == 'most-expensive' ) {
				$loop_args['orderby']  = 'meta_value_num';
				$loop_args['meta_key'] = '_price';
			}
			if ( $order == 'cheapest' ) {
				$loop_args['order'] = 'ASC';
			}
		} elseif ( $setting == 'modern_store_pro_homepage_best_selling_products' ) {
			if ( get_theme_mod( 'homepage_best_selling_products' ) != 'yes' || !defined( 'MODERN_STORE_PRO_FILE' ) ) {
				return;
			}
			$id 	 = 'best-selling-products';
			$class = 'best-selling-products woocommerce';
			$title = get_theme_mod( 'homepage_best_selling_products_title' ) ? get_theme_mod( 'homepage_best_selling_products_title' ) : __( 'Our Best-selling Products', 'modern-store' );
			$count = get_theme_mod( 'homepage_best_selling_products_count' ) ? get_theme_mod( 'homepage_best_selling_products_count' ) : 4;
			$loop_args = array(
				'post_type'      => 'product',
				'posts_per_page' => $count,
				'meta_key'       => 'total_sales',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
			);
		} elseif ( $setting == 'modern_store_pro_homepage_custom_products' ) {
			if ( get_theme_mod( 'homepage_custom_products' ) != 'yes' || !defined( 'MODERN_STORE_PRO_FILE' ) ) {
				return;
			}
			$id 	 = 'custom-products';
			$class = 'custom-products woocommerce';
			$title = get_theme_mod( 'homepage_custom_products_title' ) ? get_theme_mod( 'homepage_custom_products_title' ) : __( 'My Products', 'modern-store' );
			$ids = get_theme_mod('homepage_custom_products_select');
			$ids = explode( '|', $ids);
			$count = count( $ids );
			// setting posts_per_page limit to prevent DB powered sites with X,000 products from crashing the site
			$loop_args = array(
				'post_type' 		 => 'product',
				'post__in'			 => $ids,
				'posts_per_page' => 200, 
				'orderby' 			 => 'post__in'
			);
		} elseif ( $setting == 'modern_store_pro_homepage_latest_posts' ) {
			if ( get_theme_mod( 'homepage_latest_posts' ) != 'yes' || !defined( 'MODERN_STORE_PRO_FILE' ) ) {
				return;
			} 
			$id 	 		 = 'latest-posts';
			$class 		 = 'latest-posts woocommerce';
			$title 		 = get_theme_mod( 'homepage_latest_posts_title' ) ? get_theme_mod( 'homepage_latest_posts_title' ) : __( 'Latest Posts', 'modern-store' );
			$count 		 = get_theme_mod( 'homepage_latest_posts_count' ) ? get_theme_mod( 'homepage_latest_posts_count' ) : 3;
			$category  = get_theme_mod( 'homepage_latest_posts_category' ) ? get_theme_mod( 'homepage_latest_posts_category' ) : 'all';
			$loop_args = array(
				'post_type'      => 'post',
				'posts_per_page' => $count,
				'cat'      			 => $category
			);
		}

		// Don't output the normal loop if it's the shortcode section
		if ( $setting == 'modern_store_homepage_shortcode' ) {
			$shortcode = get_theme_mod('homepage_shortcode_code');
			if ( get_theme_mod( 'homepage_shortcode' ) != 'yes' || empty( $shortcode ) ) {
				return;
			}
			echo '<div id="shortcode" class="shortcode woocommerce">';
				echo '<div class="shortcode-inner">';
					echo do_shortcode("$shortcode");
				echo '</div>';
			echo '</div>';
		} else {
			// Loop and output products
			echo '<div id="'. esc_attr( $id ) .'" class="'. esc_attr( $class ) .'">';
			echo '<h2 class="section-title">'. esc_html( $title ) .'</h2>';
			echo '<ul class="products count-'. esc_attr( $count ) .'">';
			$loop = new WP_Query( $loop_args );
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) : $loop->the_post();
				if ( $setting == 'modern_store_pro_homepage_latest_posts' ) {
					get_template_part( 'content', 'archive' );
				} else {
					wc_get_template_part( 'content', 'product' );
				}
				endwhile;
			} else {
				echo esc_html__( 'No products found', 'modern-store' );
			}
			wp_reset_postdata();
			echo '</ul>';
			echo '</div>';
		}
	}
}

//----------------------------------------------------------------------------------
// Output product categories ssection based on user settings (used in templates/homepage.php)
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_homepage_categories' ) ) {
	function ct_modern_store_homepage_categories( $setting ) {

		if ( !ct_modern_store_is_wc_active() ) return;

		if ( $setting == 'modern_store_homepage_categories' ) {
			
			if ( get_theme_mod( 'homepage_categories' ) == 'no' ) return;

			$id						 = 'product-categories';
			$class				 = 'woocommerce product-categories';
			$title 				 = get_theme_mod( 'homepage_categories_title' ) ? get_theme_mod( 'homepage_categories_title' ) : __( 'Shop by Category', 'modern-store' );
			$count 				 = get_theme_mod( 'homepage_categories_count' ) ? get_theme_mod( 'homepage_categories_count' ) : 3;
			$subcategories = get_theme_mod( 'homepage_categories_subcategory_display' ) ? get_theme_mod( 'homepage_categories_subcategory_display' ) : 'no';
			// setting posts_per_page limit to prevent DB powered sites with X,000 categories from crashing the site
			$args					 = array(
				'taxonomy' 		 	 => 'product_cat',
				'posts_per_page' => 200
			);
			$product_categories = get_terms( $args );
			$order 							= get_theme_mod( 'homepage_categories_order' ) ? get_theme_mod( 'homepage_categories_order' ) : 'alphabetical';
			$thumbnails 				= get_theme_mod( 'homepage_categories_thumbnail' );
			$product_count 			= get_theme_mod( 'homepage_categories_product_count' );

			if ( $order == 'reverse-alphabetical' ) {
				$product_categories = array_reverse( $product_categories );
			}
			if ( $order == 'newest' ) {				
				uasort($product_categories, 'ct_modern_store_category_sort_newest');
			}
			if ( $order == 'oldest' ) {
				uasort($product_categories, 'ct_modern_store_category_sort_oldest');
			}
			if ( $order == 'most-products' ) {
				uasort($product_categories, 'ct_modern_store_category_sort_most_products');
			}
			if ( $order == 'least-products' ) {
				uasort($product_categories, 'ct_modern_store_category_sort_least_products');
			}
		} elseif ( $setting == 'modern_store_pro_homepage_custom_categories' ) {

			if ( get_theme_mod( 'homepage_custom_categories' ) != 'yes' ) return;

			$id						 = 'custom-categories';
			$class				 = 'woocommerce custom-categories';
			$title 				 = get_theme_mod( 'homepage_custom_categories_title' ) ? get_theme_mod( 'homepage_custom_categories_title' ) : __( 'Custom Categories', 'modern-store' );
			$subcategories = 'yes'; // Always show user-selected categories even if sub-category
			$category_ids  = get_theme_mod('homepage_custom_categories_select');
			$category_ids  = explode( '|', $category_ids);
			$count 				 = count( $category_ids );
			// setting posts_per_page limit to prevent DB powered sites with X,000 categories from crashing the site
			$args 				 = array(
				'include'			 	 => $category_ids,
				'posts_per_page' => 200,
				'orderby' 			 => 'include'
			);
			$product_categories = get_terms( 'product_cat', $args );
			$thumbnails 				= get_theme_mod( 'homepage_custom_categories_thumbnail' );
			$product_count 			= get_theme_mod( 'homepage_custom_categories_product_count' );
		}

		if ( empty( $product_categories ) ) return;

		$loop_count 				= 0;
		$category_html 			= '';

		foreach( $product_categories as $category ) {
			if ( $loop_count >= $count ) {
				break;
			}
			// Skip if subcategories off and category is a subcategory
			if ( $subcategories != 'yes' && !empty( $category->parent ) ) {
				continue;
			}
			$link = get_term_link( $category->term_id, 'product_cat' );
			$category_html .= '<li class="category product">';
			if ( $thumbnails != 'no' ) {
				$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
				$image 				= wp_get_attachment_url( $thumbnail_id );
				if ( $image ) {
					$image_size 		= wc_get_image_size( 'woocommerce_thumbnail' );
					$image_width 		= $image_size['width'];
					$image_height 	= $image_size['height'];
					$category_html .= '<a class="image-link" href="'. esc_url( $link ) .'">';
					$category_html .= '<img src="'. esc_url( $image ) .'" width="'. absint( $image_width ) .'" height="'. absint( $image_height ) .'" alt="'. esc_attr( $category->name ) .'" />';
					$category_html .= '</a>';
				}
			}
			$category_html .= '<a class="title" href="'. esc_url( $link ) .'">'. esc_html( $category->name );
			if ( $product_count != 'no' ) {
				$category_html .= ' <span>('. esc_html( $category->count ) .')</span>';
			}
			$category_html .= '</a>';
			$category_html .= '</li>';

			$loop_count++;
		}
		echo '<div id="'. esc_attr( $id ) .'" class="'. esc_attr( $class ) .'">';
		echo '<h2 class="section-title">'. esc_html( $title ) .'</h2>';
		echo '<ul class="categories products count-'. esc_attr( $count ) .'">';
		echo $category_html;
		echo '</ul>';
		echo '</div>';
	}
}

//----------------------------------------------------------------------------------
// Remove "Add to Cart" button
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_homepage_remove_add_to_cart' ) ) {
	function ct_modern_store_homepage_remove_add_to_cart() {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
}
add_action( 'woocommerce_after_shop_loop_item', 'ct_modern_store_homepage_remove_add_to_cart', 1 );

//----------------------------------------------------------------------------------
// Edit classes on products on homepage to make styling easier
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_edit_homepage_product_classes' ) ) {
	function ct_modern_store_edit_homepage_product_classes( $classes ) {

		if ( is_page_template( 'templates/homepage.php' ) ) {
			// Remove the 'first' and 'last' classes that make custom row styling tough
			$classes = array_diff( $classes, array( 
				array_search( 'first', $classes ) => 'first', 
				array_search( 'last', $classes ) => 'last'
			) );
		}
		
		return $classes;
	}
}
add_filter( 'post_class', 'ct_modern_store_edit_homepage_product_classes', 30 );

//----------------------------------------------------------------------------------
// Get and return the order of the homepage sections
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_set_homepage_order' ) ) {
	function ct_modern_store_set_homepage_order() {
		$homepage_order = get_option( 'ct_modern_store_homepage_order' );
		if ( empty( $homepage_order ) || empty( $homepage_order['modern_store_homepage_shortcode' ] ) ) {
			$homepage_order = array(
				'modern_store_homepage_shortcode'  								=> 1,
				'modern_store_homepage_featured_products'  				=> 2,
				'modern_store_homepage_on_sale_products'	 				=> 3,
				'modern_store_homepage_categories' 				 				=> 4,
				'modern_store_homepage_top_rated_products' 				=> 5,
				'modern_store_homepage_latest_products'		 				=> 6,
				'modern_store_pro_homepage_best_selling_products' => 7,
				'modern_store_pro_homepage_custom_products' 			=> 8,
				'modern_store_pro_homepage_custom_categories' 		=> 9,
				'modern_store_pro_homepage_latest_posts' 					=> 10
			);
			update_option( 'ct_modern_store_homepage_order', $homepage_order );
		}
		return $homepage_order;	
	}
}

//----------------------------------------------------------------------------------
// Turn of partial refresh for logo, so title shows up when logo is removed
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_update_logo_refresh' ) ) {
	function ct_modern_store_update_logo_refresh( $wp_customize ) {
		$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
	}
}
add_action( 'customize_register', 'ct_modern_store_update_logo_refresh', 20 );


//----------------------------------------------------------------------------------
// Update spacing around logo if there is one
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_logo_styles' ) ) {
	function ct_modern_store_logo_styles(){
		$logo_set = get_theme_mod( 'custom_logo' );
		$css = '';
		if ( !empty( $logo_set ) ) {
			$css .= '@media all and (min-width: 50em) {
				.title-container {
					margin: 0.75em 3em 0.75em 0;
				}
				.rtl .title-container {
					margin: 0.75em 0 0.75em 3em;
				}
			}';
		}
		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_logo_styles', 20 );

//----------------------------------------------------------------------------------
// Filter product images on the homepage to use product page image size
// They can display up to 588px wide and the WC default single image size is 600px
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_homepage_image_size' ) ) {
	function ct_modern_store_homepage_image_size($size) {
		if ( is_page_template( 'templates/homepage.php' ) ) {
			return 'ct-modern-store-homepage-thumb';
		} else {
			return $size;
		}
	}
}
add_filter( 'single_product_archive_thumbnail_size', 'ct_modern_store_homepage_image_size' );

//----------------------------------------------------------------------------------
//	Output pagination
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_pagination' ) ) {
	function ct_modern_store_pagination() {

		// Don't output on WC pages
		if ( ct_modern_store_is_wc_active() ) {
			if ( is_woocommerce() ) {
				return;
			}
		}
		// Output pagination if Jetpack not installed, otherwise check if infinite scroll is active before outputting
		if ( !class_exists( 'Jetpack' ) ) {
			the_posts_pagination( array(
					'mid_size' => 1
			) );
		} elseif ( !Jetpack::is_module_active( 'infinite-scroll' ) ) {
			the_posts_pagination( array(
					'mid_size' => 1
			) );
		}
	}
}

//----------------------------------------------------------------------------------
//	Using an anonymous functions insead of these 4 named functions with uasort works best but causes
//  a fatal error for PHP 5.2 which 1.8% of WP users still use. uasort() cannot pass the third
//  parameter required to update the sorting mechanism. create_function() works but is deprecated 
//  as of PHP 7.2. Checking the PHP version and using either an anon function or create_function()
//  is more verbose than simply using 4 named functions, hence the disappointing code below :sad-emoji:
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_category_sort_newest' ) ) {
	function ct_modern_store_category_sort_newest($a, $b) {
		return $b->term_id - $a->term_id;
	}
}
if ( ! function_exists( 'ct_modern_store_category_sort_oldest' ) ) {
	function ct_modern_store_category_sort_oldest($a, $b) {
		return $a->term_id - $b->term_id;
	}
}
if ( ! function_exists( 'ct_modern_store_category_sort_most_products' ) ) {
	function ct_modern_store_category_sort_most_products($a, $b) {
		return $b->count - $a->count;
	}
}
if ( ! function_exists( 'ct_modern_store_category_sort_least_products' ) ) {
	function ct_modern_store_category_sort_least_products($a, $b) {
		return $a->count - $b->count;
	}
}

//----------------------------------------------------------------------------------
//	Shim for backwards compatibility for required wp_body_open() function added in WP 5.2
//----------------------------------------------------------------------------------
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

//----------------------------------------------------------------------------------
// Output user styles for maximum width of the search bar category select
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_modern_store_category_select_max_width' ) ) {
	function ct_modern_store_category_select_max_width(){
		$max_width = get_theme_mod( 'store_search_bar_category_select_max_width' );
		$css = '';
		if ( !empty( $max_width ) && $max_width != 300 ) {
			$css .= '#store-search {max-width: '. $max_width .'px;}';
		}
		if ( !empty( $css ) ) {
			$css = ct_modern_store_sanitize_css($css);
			wp_add_inline_style( 'ct-modern-store-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_modern_store_category_select_max_width', 20 );

//----------------------------------------------------------------------------------
// Add support for Elementor headers & footers
//----------------------------------------------------------------------------------
function ct_modern_store_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );
}
add_action( 'elementor/theme/register_locations', 'ct_modern_store_register_elementor_locations' );