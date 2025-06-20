<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_modern_store_add_customizer_content' );

function ct_modern_store_add_customizer_content( $wp_customize ) {

	$use_partials = version_compare( PHP_VERSION, '5.3' ) >= 0 ? true : false;

	/***** Reorder default sections *****/

	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	// check if exists in case user has no pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
	}

	/***** Add PostMessage Support *****/

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	class CT_Modern_Store_Control_Checkbox_Multiple extends WP_Customize_Control {

		public $type = 'checkbox-multiple';
		
    public function render_content() {
        if ( empty( $this->choices ) ) {
					return;
				}
				if ( !empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif; ?>
        <?php if ( !empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>
        <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
        <ul>
					<?php foreach ( $this->choices as $value => $label ) : ?>
						<li>
							<label>
								<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
								<?php echo esc_html( $label ); ?>
							</label>
						</li>
					<?php endforeach; ?>
        </ul>
        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
    <?php }
	}

	$homepage_order = ct_modern_store_set_homepage_order();
	
	/********** Add Panels **********/

	if ( method_exists( 'WP_Customize_Manager', 'add_panel' ) ) {
		$wp_customize->add_panel( 'ct_modern_store_homepage_panel', array(
			'priority'    => 20,
			'title'       => __( 'Homepage Builder', 'modern-store' )
		) );
	}

	/***** Modern Store Pro Control *****/
	class ct_modern_store_pro_ad extends WP_Customize_Control {
		public function render_content() {
			$link = 'https://www.competethemes.com/modern-store-pro/';
			echo "<p class='bold'>" . sprintf( __('<a target="_blank" href="%1$s">%2$s Pro</a> is the plugin that makes advanced customization simple - and fun too!', 'modern-store'), esc_url( $link ), wp_get_theme( get_template() ) ) . "</p>";
			echo "<p>" . sprintf( __( '%1$s Pro adds the following features to %1$s:', 'modern-store' ), wp_get_theme( get_template() ) ) . "</p>";
			echo "<ul>
					<li>" . __('Reorder homepage sections', 'modern-store') . "</li>
					<li>" . __('4 new homepage sections', 'modern-store') . "</li>
					<li>" . __('Header promo slider', 'modern-store') . "</li>
					<li>" . __('+ 5 more features', 'modern-store') . "</li>
				  </ul>";
			echo "<p class='button-wrapper'><a target=\"_blank\" class='modern-store-pro-button' href='" . esc_url( $link ) . "'>" . sprintf( __('Try It Now', 'modern-store'), wp_get_theme( get_template() ) ) . " &rarr;</a></p>";
		}
	}
	/***** Modern Store Pro Section *****/

	// don't add if Modern Store Pro is active
	if ( !defined( 'MODERN_STORE_PRO_FILE' ) ) {
		// section
		$wp_customize->add_section( 'ct_modern_store_pro', array(
			'title'    => sprintf( __( '%s Pro', 'modern-store' ), wp_get_theme( get_template() ) ),
			'priority' => 1
		) );
		// Upload - setting
		$wp_customize->add_setting( 'modern_store_pro', array(
			'sanitize_callback' => 'absint'
		) );
		// Upload - control
		$wp_customize->add_control( new ct_modern_store_pro_ad(
			$wp_customize, 'modern_store_pro', array(
				'section'  => 'ct_modern_store_pro',
				'settings' => 'modern_store_pro'
			)
		) );
	}

	/***** Homepage - Shortcode *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_shortcode', array(
		'title'    => __( 'Shortcode', 'modern-store' ),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_shortcode']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_shortcode', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport' => 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_shortcode', array(
		'label'    => __( 'Display the shortcode section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_shortcode',
		'settings' => 'homepage_shortcode',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_shortcode_code', array(
		'default'           => '',
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport' => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_shortcode_code', array(
			'label'       => __( 'Enter the shortcode', 'modern-store' ),
			'section'     => 'modern_store_homepage_shortcode',
			'settings'    => 'homepage_shortcode_code',
			'type'				=> 'text'
	) );

	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_shortcode', array(
			'settings' => array(
				'homepage_shortcode',
				'homepage_shortcode_code'
			),
			'selector' => '#shortcode',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_output_wc_products('modern_store_homepage_shortcode');
			}
		));
	}

	/***** Homepage - On-sale Products *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_on_sale_products', array(
		'title'    => __( 'On Sale Products', 'modern-store' ),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_on_sale_products']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_on_sale_products', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport' => 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_on_sale_products', array(
		'label'    => __( 'Display the on sale products section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_on_sale_products',
		'settings' => 'homepage_on_sale_products',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_on_sale_products_title', array(
		'default'           => __( 'On Sale Products', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_on_sale_products_title', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'modern_store_homepage_on_sale_products',
			'settings'    => 'homepage_on_sale_products_title',
			'type'				=> 'text'
	) );
	// # of products - setting
	$wp_customize->add_setting( 'homepage_on_sale_products_count', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
		'transport' => 'postMessage'
	) );
	// # of products - control
	$wp_customize->add_control( 'homepage_on_sale_products_count', array(
		'label'    		=> __( 'Number of products', 'modern-store' ),
		'description' => __( 'Include 1-12 products on sale', 'modern-store' ),
		'section'  		=> 'modern_store_homepage_on_sale_products',
		'settings' 		=> 'homepage_on_sale_products_count',
		'type'     		=> 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12
		)
	) );
	// Order - setting
	$wp_customize->add_setting( 'homepage_on_sale_products_order', array(
		'description'    		=> __( 'Product order', 'modern-store' ),
		'default'           => 'newest',
		'sanitize_callback' => 'ct_modern_store_sanitize_product_order',
		'transport' => 'postMessage'
	) );
	// Order - control
	$wp_customize->add_control( 'homepage_on_sale_products_order', array(
		'label'    => __( 'Product order', 'modern-store' ),
		'section'  => 'modern_store_homepage_on_sale_products',
		'settings' => 'homepage_on_sale_products_order',
		'type'     => 'select',
		'choices'  => array(
			'newest' 			 	 => __( 'Newest', 'modern-store' ),
			'oldest' 				 => __( 'Oldest', 'modern-store' ),
			'cheapest' 			 => __( 'Cheapest', 'modern-store' ),
			'most-expensive' => __( 'Most expensive', 'modern-store' )
		)
	) );
	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_on_sale_products', array(
			'settings' => array(
				'homepage_on_sale_products',
				'homepage_on_sale_products_count',
				'homepage_on_sale_products_order'
			),
			'selector' => '#on-sale-products',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_output_wc_products('modern_store_homepage_on_sale_products');
			}
		));
	}

	/***** Homepage - Latest Products *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_latest_products', array(
		'title'    => __( 'Latest Products', 'modern-store' ),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_latest_products']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_latest_products', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport' 				=> 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_latest_products', array(
		'label'    => __( 'Display the latest products section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_latest_products',
		'settings' => 'homepage_latest_products',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_latest_products_title', array(
		'default'           => __( 'Latest Products', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_latest_products_title', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'modern_store_homepage_latest_products',
			'settings'    => 'homepage_latest_products_title',
			'type'				=> 'text'
	) );
	// # of products - setting
	$wp_customize->add_setting( 'homepage_latest_products_count', array(
		'default'           => 7,
		'sanitize_callback' => 'absint',
		'transport' 				=> 'postMessage'
	) );
	// # of products - control
	$wp_customize->add_control( 'homepage_latest_products_count', array(
		'label'    		=> __( 'Number of products', 'modern-store' ),
		'description' => __( 'Include 1-12 of your latest products', 'modern-store' ),
		'section'  		=> 'modern_store_homepage_latest_products',
		'settings' 		=> 'homepage_latest_products_count',
		'type'     		=> 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12
		)
	) );
	// Product category - setting
	$wp_customize->add_setting( 'homepage_latest_products_category', array(
		'default'           => 'all',
		'sanitize_callback' => 'ct_modern_store_sanitize_product_categories',
		'transport' 				=> 'postMessage'
	) );
	$categories_array = array( 'all' => __('All', 'modern-store') );
	if ( taxonomy_exists( 'product_cat') ) {
		foreach ( get_terms('product_cat') as $category ) {
			$categories_array[$category->term_id] = $category->name;
		}
	}
	// Product category - control
	$wp_customize->add_control( 'homepage_latest_products_category', array(
		'label'    => __( 'Product category', 'modern-store' ),
		'section'  => 'modern_store_homepage_latest_products',
		'settings' => 'homepage_latest_products_category',
		'type'     => 'select',
		'choices'  => $categories_array
	) );
	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_latest_products', array(
			'settings' => array(
				'homepage_latest_products',
				'homepage_latest_products_count',
				'homepage_latest_products_category'
			),
			'selector' => '#latest-products',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_output_wc_products('modern_store_homepage_latest_products');
			}
		));
	}

	/***** Homepage - Top-rated Products *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_top_rated_products', array(
		'title'    => __( 'Top-rated Products', 'modern-store' ),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_top_rated_products']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_top_rated_products', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport'					=> 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_top_rated_products', array(
		'label'    => __( 'Display the top-rated products section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_top_rated_products',
		'settings' => 'homepage_top_rated_products',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_top_rated_products_title', array(
		'default'           => __( 'Top-rated Products', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_top_rated_products_title', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'modern_store_homepage_top_rated_products',
			'settings'    => 'homepage_top_rated_products_title',
			'type'				=> 'text'
	) );
	// # of products - setting
	$wp_customize->add_setting( 'homepage_top_rated_products_count', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// # of products - control
	$wp_customize->add_control( 'homepage_top_rated_products_count', array(
		'label'    		=> __( 'Number of products', 'modern-store' ),
		'description' => __( 'Include 1-12 of your top-rated products', 'modern-store' ),
		'section'  		=> 'modern_store_homepage_top_rated_products',
		'settings' 		=> 'homepage_top_rated_products_count',
		'type'     		=> 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12
		)
	) );
	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_top_rated_products', array(
			'settings' => array(
				'homepage_top_rated_products',
				'homepage_top_rated_products_count'
			),
			'selector' => '#top-rated-products',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_output_wc_products('modern_store_homepage_top_rated_products');
			}
		));
	}

	/***** Homepage - Featured Products *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_featured_products', array(
		'title'    => __( 'Featured Products', 'modern-store' ),
		// translators: %s is a link to a tutorial
		'description' => sprintf( __( 'Learn how to choose your Featured Products: <a href="%s" target="_blank">Read Tutorial</a>', 'modern-store' ), 'https://bobwp.com/how-to-feature-products-in-woocommerce/'),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_featured_products']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_featured_products', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport' 				=> 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_featured_products', array(
		'label'    => __( 'Display the featured products section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_featured_products',
		'settings' => 'homepage_featured_products',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_featured_products_title', array(
		'default'           => __( 'Featured Products', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_featured_products_title', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'modern_store_homepage_featured_products',
			'settings'    => 'homepage_featured_products_title',
			'type'				=> 'text'
	) );
	// # of products - setting
	$wp_customize->add_setting( 'homepage_featured_products_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport' 				=> 'postMessage'
	) );
	// # of products - control
	$wp_customize->add_control( 'homepage_featured_products_count', array(
		'label'    		=> __( 'Number of products', 'modern-store' ),
		'description' => __( 'Include 1-12 of your featured products', 'modern-store' ),
		'section'  		=> 'modern_store_homepage_featured_products',
		'settings' 		=> 'homepage_featured_products_count',
		'type'     		=> 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12
		)
	) );
	// Order - setting
	$wp_customize->add_setting( 'homepage_featured_products_order', array(
		'description'    		=> __( 'Product order', 'modern-store' ),
		'default'           => 'newest',
		'sanitize_callback' => 'ct_modern_store_sanitize_product_order',
		'transport' 				=> 'postMessage'
	) );
	// Order - control
	$wp_customize->add_control( 'homepage_featured_products_order', array(
		'label'    => __( 'Product order', 'modern-store' ),
		'section'  => 'modern_store_homepage_featured_products',
		'settings' => 'homepage_featured_products_order',
		'type'     => 'select',
		'choices'  => array(
			'newest' 			 	 => __( 'Newest', 'modern-store' ),
			'oldest' 				 => __( 'Oldest', 'modern-store' ),
			'cheapest' 			 => __( 'Cheapest', 'modern-store' ),
			'most-expensive' => __( 'Most expensive', 'modern-store' )
		)
	) );
	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_featured_products', array(
			'settings' => array(
				'homepage_featured_products',
				'homepage_featured_products_count',
				'homepage_featured_products_order'
			),
			'selector' => '#featured-products',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_output_wc_products('modern_store_homepage_featured_products');
			}
		));
	}

	/***** Homepage - Product Categories *****/

	// section
	$wp_customize->add_section( 'modern_store_homepage_categories', array(
		'title'    => __( 'Product Categories', 'modern-store' ),
		'panel' 	 => 'ct_modern_store_homepage_panel',
		'priority' => $homepage_order['modern_store_homepage_categories']
	) );
	// Display - setting
	$wp_customize->add_setting( 'homepage_categories', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport'					=> 'postMessage'
	) );
	// Display - control
	$wp_customize->add_control( 'homepage_categories', array(
		'label'    => __( 'Display the product categories section?', 'modern-store' ),
		'section'  => 'modern_store_homepage_categories',
		'settings' => 'homepage_categories',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'homepage_categories_title', array(
		'default'           => __( 'Shop by Category', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'homepage_categories_title', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'modern_store_homepage_categories',
			'settings'    => 'homepage_categories_title',
			'type'				=> 'text'
	) );
	// # of categories - setting
	$wp_customize->add_setting( 'homepage_categories_count', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// # of categories - control
	$wp_customize->add_control( 'homepage_categories_count', array(
		'label'    		=> __( 'Number of categories', 'modern-store' ),
		'description' => __( 'Include 1-12 of your product categories', 'modern-store' ),
		'section'  		=> 'modern_store_homepage_categories',
		'settings' 		=> 'homepage_categories_count',
		'type'     		=> 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 12
		)
	) );
	// Subcategory display - setting
	$wp_customize->add_setting( 'homepage_categories_subcategory_display', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport'					=> 'postMessage'
	) );
	// Subcategory display - control
	$wp_customize->add_control( 'homepage_categories_subcategory_display', array(
		'label'    => __( 'Include subcategories?', 'modern-store' ),
		'section'  => 'modern_store_homepage_categories',
		'settings' => 'homepage_categories_subcategory_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Thumbnail display - setting
	$wp_customize->add_setting( 'homepage_categories_thumbnail', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport'					=> 'postMessage'
	) );
	// Thumbnail display - control
	$wp_customize->add_control( 'homepage_categories_thumbnail', array(
		'label'    => __( 'Display the category thumbnail?', 'modern-store' ),
		'section'  => 'modern_store_homepage_categories',
		'settings' => 'homepage_categories_thumbnail',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Product count display - setting
	$wp_customize->add_setting( 'homepage_categories_product_count', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings',
		'transport'					=> 'postMessage'
	) );
	// Product count display - control
	$wp_customize->add_control( 'homepage_categories_product_count', array(
		'label'    => __( 'Display the number of products?', 'modern-store' ),
		'section'  => 'modern_store_homepage_categories',
		'settings' => 'homepage_categories_product_count',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Order - setting
	$wp_customize->add_setting( 'homepage_categories_order', array(
		'description'    		=> __( 'Category order', 'modern-store' ),
		'default'           => 'alphabetical',
		'sanitize_callback' => 'ct_modern_store_sanitize_category_order',
		'transport'					=> 'postMessage'
	) );
	// Order - control
	$wp_customize->add_control( 'homepage_categories_order', array(
		'label'    => __( 'Category order', 'modern-store' ),
		'section'  => 'modern_store_homepage_categories',
		'settings' => 'homepage_categories_order',
		'type'     => 'select',
		'choices'  => array(
			'alphabetical' 			 	 => __( 'Alphabetical', 'modern-store' ),
			'reverse-alphabetical' => __( 'Reverse alphabetical', 'modern-store' ),
			'most-products' 			 => __( 'Most products', 'modern-store' ),
			'least-products' 			 => __( 'Least products', 'modern-store' ),
			'newest' 							 => __( 'Newest', 'modern-store' ),
			'oldest' 							 => __( 'Oldest', 'modern-store' )
		)
	) );
	// Add partial for faster updating of settings
	if ( $use_partials ) {
		$wp_customize->selective_refresh->add_partial('modern_store_homepage_categories', array(
			'settings' => array(
				'homepage_categories',
				'homepage_categories_count',
				'homepage_categories_subcategory_display',
				'homepage_categories_thumbnail',
				'homepage_categories_product_count',
				'homepage_categories_order'
			),
			'selector' => '#product-categories',
			'container_inclusive' => true,
			'render_callback' => function() {
				return ct_modern_store_homepage_categories('modern_store_homepage_categories');
			}
		));
	}
	/***** Header Promo *****/

	// section
	// $wp_customize->add_section( 'modern_store_header_promo_slide_1', array(
	// 	'title'    => __( 'Header Promo', 'modern-store' ),
	// 	'description' => __( 'Customize the store promo displayed below the menu.', 'modern-store' ),
	// 	'priority' => 25
	// ) );

	$wp_customize->get_section( 'header_image' )->title = __( 'Header Promo', 'modern-store' );
	$wp_customize->get_section( 'header_image' )->priority = 25;
	// setting
	$wp_customize->add_setting( 'header_promo_display_slide_1', array(
		'default'           => array('homepage'),
		'sanitize_callback' => 'ct_modern_store_sanitize_header_promo_display'
	) );
	// control
	$wp_customize->add_control(
		new CT_Modern_Store_Control_Checkbox_Multiple( 
			$wp_customize, 'header_promo_display_slide_1', array(
			'label'    => __( 'Pages to display the header promo', 'modern-store' ),
			'section'  => 'header_image',
			'settings' => 'header_promo_display_slide_1',
			'choices'  => array(
				'homepage' 			 => __( 'Homepage', 'modern-store' ),
				'store' 	 			 => __( 'Store home', 'modern-store' ),
				'store-archives' => __( 'Product categories', 'modern-store' ),
				'products' 	 		 => __( 'Product pages', 'modern-store' ),
				'blog'  	 			 => __( 'Blog', 'modern-store' ),
				'posts'  	 			 => __( 'Posts', 'modern-store' ),
				'pages'  	 			 => __( 'Pages', 'modern-store' ),
				'archives' 			 => __( 'Archives', 'modern-store' ),
				'search'   			 => __( 'Search results', 'modern-store' )
			) )
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_title_text_slide_1', array(
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_promo_title_text_slide_1', array(
			'label'       => __( 'Title text', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_title_text_slide_1',
			'type'				=> 'text'
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_subtitle_text_slide_1', array(
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_promo_subtitle_text_slide_1', array(
			'label'       => __( 'Subtitle text', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_subtitle_text_slide_1',
			'type'				=> 'text'
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_button_text_slide_1', array(
		'sanitize_callback' => 'ct_modern_store_sanitize_text'
	) );
	// control
	$wp_customize->add_control( 'header_promo_button_text_slide_1', array(
			'label'       => __( 'Button text', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_button_text_slide_1',
			'type'				=> 'text'
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_button_url_slide_1', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( 'header_promo_button_url_slide_1', array(
			'label'       => __( 'Button URL', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_button_url_slide_1',
			'type'				=> 'url'
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_text_alignment_slide_1', array(
		'default'           => 'left',
		'sanitize_callback' => 'ct_modern_store_sanitize_header_text_alignment'
	) );
	// control
	$wp_customize->add_control( 'header_promo_text_alignment_slide_1', array(
			'label'       => __( 'Text alignment', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_text_alignment_slide_1',
			'type'				=> 'radio',
			'choices'			=> array(
				'left' 	 => __( 'Left', 'modern-store' ),
				'center' => __( 'Center', 'modern-store' ),
				'right'  => __( 'Right', 'modern-store' )
			)
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_text_width_slide_1', array(
		'default'           => 50,
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_promo_text_width_slide_1', array(
			'label'       => __( 'Text width', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_text_width_slide_1',
			'type'				=> 'range',
			'input_attrs'	=> array(
				'min'  => 10,
				'max'  => 100,
				'step' => 1
			)
	) );
	// setting
	// $wp_customize->add_setting( 'header_promo_image_slide_1', array(
	// 	'default'						=> trailingslashit(get_template_directory_uri()) . 'assets/images/header-background.jpg',
	// 	'sanitize_callback' => 'esc_url_raw'
	// ) );
	// // control
	// $wp_customize->add_control( new WP_Customize_Image_Control(
	// 	$wp_customize, 'header_promo_image_slide_1', array(
	// 		'label'    		=> __( 'Background image', 'modern-store' ),
	// 		'description' => __( 'Use an image that is 2,000px wide for best results.', 'modern-store' ),
	// 		'section'  		=> 'header_image',
	// 		'settings' 		=> 'header_promo_image_slide_1'
	// 	)
	// ) );	
	// setting
	$wp_customize->add_setting( 'header_promo_height_slide_1', array(
		'default'           => 60,
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_promo_height_slide_1', array(
			'label'       => __( 'Header promo height', 'modern-store' ),
			'section'     => 'header_image',
			'settings'    => 'header_promo_height_slide_1',
			'type'				=> 'range',
			'input_attrs'	=> array(
				'min'  => 10,
				'max'  => 100,
				'step' => 1
			)
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_overlay_color_slide_1', array(
		'default' 					=> '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_promo_overlay_color_slide_1', array(
			'label'    => __( 'Background overlay color', 'modern-store' ),
			'section'  => 'header_image',
			'settings' => 'header_promo_overlay_color_slide_1'
		)
	) );
	// setting
	$wp_customize->add_setting( 'header_promo_overlay_opacity_slide_1', array(
		'default' 					=> 0,
		'sanitize_callback' => 'ct_modern_store_sanitize_header_promo_overlay_opacity',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'header_promo_overlay_opacity_slide_1', array(
		'label'    => __( 'Overlay opacity', 'modern-store' ),
		'section'  => 'header_image',
		'settings' => 'header_promo_overlay_opacity_slide_1',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.01
		)
	) );

	/***** Color *****/

	// section
	$wp_customize->add_section( 'modern_store_colors', array(
		'title'    => __( 'Brand Color', 'modern-store' ),
		'description'    => __( 'Customize the accent color used throughout your site.', 'modern-store' ),
		'priority' => 30
	) );
	// setting
	$wp_customize->add_setting( 'color_primary', array(
		'default'           => '#ffc270',
		'sanitize_callback' => 'sanitize_hex_color'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'color_primary', array(
			'label'       => __( 'Brand Color', 'modern-store' ),
			'section'     => 'modern_store_colors',
			'settings'    => 'color_primary'
		)
	) );

	/***** Store Search Bar *****/

	// section
	$wp_customize->add_section( 'modern_store_search_bar', array(
		'title'    => __( 'Store Search Bar', 'modern-store' ),
		'description' => __('Customize the store search bar located in the header.', 'modern-store'),
		'priority' => 45
	) );
	// Display - setting
	$wp_customize->add_setting( 'store_search_bar_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Display - control
	$wp_customize->add_control( 'store_search_bar_display', array(
		'label'    => __( 'Display the search bar?', 'modern-store' ),
		'section'  => 'modern_store_search_bar',
		'settings' => 'store_search_bar_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Category select - setting
	$wp_customize->add_setting( 'store_search_bar_category_select', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Category select - control
	$wp_customize->add_control( 'store_search_bar_category_select', array(
		'label'    => __( 'Display the category selector?', 'modern-store' ),
		'section'  => 'modern_store_search_bar',
		'settings' => 'store_search_bar_category_select',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Submit button - setting
	$wp_customize->add_setting( 'store_search_bar_submit_button', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Submit button - control
	$wp_customize->add_control( 'store_search_bar_submit_button', array(
		'label'    => __( 'Display the submit button?', 'modern-store' ),
		'section'  => 'modern_store_search_bar',
		'settings' => 'store_search_bar_submit_button',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Category select label - setting
	$wp_customize->add_setting( 'store_search_bar_category_select_label', array(
		'default'           => __( 'All', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Category select label - control
	$wp_customize->add_control( 'store_search_bar_category_select_label', array(
		'label'    => __( 'Default category select label', 'modern-store' ),
		'section'  => 'modern_store_search_bar',
		'settings' => 'store_search_bar_category_select_label',
		'type'     => 'text'
	) );
	// Category select max width - setting
	$wp_customize->add_setting( 'store_search_bar_category_select_max_width', array(
		'default'           => 300,
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// Category select max width - control
	$wp_customize->add_control( 'store_search_bar_category_select_max_width', array(
		'label'    => __( 'Maximum width of the category select', 'modern-store' ),
		'description' => __('(in pixels)', 'modern-store'),
		'section'  => 'modern_store_search_bar',
		'settings' => 'store_search_bar_category_select_max_width',
		'type'     => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 999
		)
	) );

	/***** User Icon *****/

	// section
	$wp_customize->add_section( 'modern_store_user_icon', array(
		'title'    => __( 'User Account Icon', 'modern-store' ),
		'description'    => __( 'Customize the icon in the header that links visitors to their account.', 'modern-store' ),
		'priority' => 40
	) );
	// Display - setting
	$wp_customize->add_setting( 'user_icon_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Display - control
	$wp_customize->add_control( 'user_icon_display', array(
		'label'    => __( 'Display the user account icon?', 'modern-store' ),
		'section'  => 'modern_store_user_icon',
		'settings' => 'user_icon_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Mobile display - setting
	$wp_customize->add_setting( 'user_icon_mobile_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Mobile display - control
	$wp_customize->add_control( 'user_icon_mobile_display', array(
		'label'    => __( 'Display on mobile devices?', 'modern-store' ),
		'section'  => 'modern_store_user_icon',
		'settings' => 'user_icon_mobile_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Use Gravatar - setting
	$wp_customize->add_setting( 'user_icon_gravatar', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Use Gravatar - control
	$wp_customize->add_control( 'user_icon_gravatar', array(
		'label'    => __( 'Replace icon with Gravatar images?', 'modern-store' ),
		'section'  => 'modern_store_user_icon',
		'settings' => 'user_icon_gravatar',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );

	/***** Shopping Cart *****/

	// section
	$wp_customize->add_section( 'modern_store_shopping_cart', array(
		'title'    => __( 'Shopping Cart Icon', 'modern-store' ),
		'description'    => __( 'Customize the shopping cart in the header.', 'modern-store' ),
		'priority' => 35
	) );
	// Display - setting
	$wp_customize->add_setting( 'shopping_cart_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Display - control
	$wp_customize->add_control( 'shopping_cart_display', array(
		'label'    => __( 'Display the shopping cart?', 'modern-store' ),
		'section'  => 'modern_store_shopping_cart',
		'settings' => 'shopping_cart_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Display count - setting
	$wp_customize->add_setting( 'shopping_cart_display_count', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Display count - control
	$wp_customize->add_control( 'shopping_cart_display_count', array(
		'label'    => __( 'Display the cart item count?', 'modern-store' ),
		'description' => __( "Empty your cart if the cart count does not disappear.", 'modern-store' ),
		'section'  => 'modern_store_shopping_cart',
		'settings' => 'shopping_cart_display_count',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Display - setting
	$wp_customize->add_setting( 'shopping_cart_mobile_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Display - control
	$wp_customize->add_control( 'shopping_cart_mobile_display', array(
		'label'    => __( 'Display on mobile devices?', 'modern-store' ),
		'section'  => 'modern_store_shopping_cart',
		'settings' => 'shopping_cart_mobile_display',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	
	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_modern_store_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_modern_store_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'modern-store' ),
		'priority'    => 55,
		'description' => __( 'Enter your social profile URLs to include new icons in the header.', 'modern-store' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_modern_store_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'modern-store' ),
				'section'  => 'ct_modern_store_social_media_icons',
				'priority' => $priority
			) );
		} else if ( $social_site == 'phone' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_modern_store_sanitize_phone'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Phone', 'modern-store' ),
				'section'     => 'ct_modern_store_social_media_icons',
				'priority'    => $priority,
				'type'        => 'text'
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'rss' ) {
				$label = __('RSS', 'modern-store');
			} elseif ( $social_site == 'researchgate' ) {
				$label = __('ResearchGate', 'modern-store');
			} elseif ( $social_site == 'soundcloud' ) {
				$label = __('SoundCloud', 'modern-store');
			} elseif ( $social_site == 'slideshare' ) {
				$label = __('SlideShare', 'modern-store');
			} elseif ( $social_site == 'codepen' ) {
				$label = __('CodePen', 'modern-store');
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = __('StumbleUpon', 'modern-store');
			} elseif ( $social_site == 'deviantart' ) {
				$label = __('DeviantArt', 'modern-store');
			} elseif ( $social_site == 'hacker-news' ) {
				$label = __('Hacker News', 'modern-store');
			} elseif ( $social_site == 'whatsapp' ) {
				$label = __('WhatsApp', 'modern-store');
			} elseif ( $social_site == 'qq' ) {
				$label = __('QQ', 'modern-store');
			} elseif ( $social_site == 'vk' ) {
				$label = __('VK', 'modern-store');
			} elseif ( $social_site == 'wechat' ) {
				$label = __('WeChat', 'modern-store');
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = __('Tencent Weibo', 'modern-store');
			} elseif ( $social_site == 'paypal' ) {
				$label = __('PayPal', 'modern-store');
			} elseif ( $social_site == 'email-form' ) {
				$label = __('Contact Form', 'modern-store');
			} elseif ( $social_site == 'google-wallet' ) {
				$label = __('Google Wallet', 'modern-store');
			} elseif ( $social_site == 'ok-ru' ) {
				$label = __('OK.ru', 'modern-store');
			} elseif ( $social_site == 'artstation' ) {
				$label = __('ArtStation', 'modern-store');
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_modern_store_sanitize_skype'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'        => 'url',
					'label'       => $label,
					// translators: %s is a link to a tutorial
					'description' => sprintf( __( 'Accepts Skype link protocol (<a href="%s" target="_blank">learn more</a>)', 'modern-store' ), 'https://www.competethemes.com/blog/skype-links-wordpress/' ),
					'section'     => 'ct_modern_store_social_media_icons',
					'priority'    => $priority
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'     => 'url',
					'label'    => $label,
					'section'  => 'ct_modern_store_social_media_icons',
					'priority' => $priority
				) );
			}
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Featured Image Size *****/

	// section
	$wp_customize->add_section( 'modern_store_fi_size', array(
		'title'    => __( 'Featured Image Size', 'modern-store' ),
		'description'    => __( "Customize the size of your post's Featured Images.", 'modern-store' ),
		'priority' => 65
	) );
	// setting
	$wp_customize->add_setting( 'fi_size_type', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_modern_store_sanitize_fi_size_type'
	) );
	// control
	$wp_customize->add_control( 'fi_size_type', array(
		'label'    => __( 'Lock Featured Image aspect ratio?', 'modern-store' ),
		'section'  => 'modern_store_fi_size',
		'settings' => 'fi_size_type',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes, use the same aspect ratio for all Featured Images', 'modern-store' ),
			'no'  => __( 'No, use the natural aspect ratio of each image', 'modern-store' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'fi_size', array(
		'default'           => '40',
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'fi_size', array(
		'label'    => __( 'Featured Image Aspect Ratio', 'modern-store' ),
		'section'  => 'modern_store_fi_size',
		'settings' => 'fi_size',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 15,
			'max'  => 80, 
			'step' => 1
		)
	) );

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'modern_store_blog', array(
		'title'    => __( 'Blog', 'modern-store' ),
		'priority' => 60
	) );
	// Full post - setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// Full post - control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'modern-store' ),
		'section'  => 'modern_store_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
	// Excerpt length - setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '30',
		'sanitize_callback' => 'absint'
	) );
	// Excerpt length - control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Automatic excerpt word count', 'modern-store' ),
		'section'  => 'modern_store_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'read_more_text', array(
		'default'           => __( 'Read More', 'modern-store' ),
		'sanitize_callback' => 'ct_modern_store_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Read More text - control
	$wp_customize->add_control( 'read_more_text', array(
		'label'    => __( 'Read More button text', 'modern-store' ),
		'section'  => 'modern_store_blog',
		'settings' => 'read_more_text',
		'type'     => 'text'
	) );
	// setting - last updated
	$wp_customize->add_setting( 'last_updated', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// control - last updated
	$wp_customize->add_control( 'last_updated', array(
		'label'    => __( 'Display the date each post was last updated?', 'modern-store' ),
		'section'  => 'modern_store_blog',
		'settings' => 'last_updated',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );

	/***** Additional Options  *****/

	// section
	$wp_customize->add_section( 'ct_modern_store_additional_options', array(
		'title'    => __( 'Additional Options', 'modern-store' ),
		'priority' => 70
	) );
	// setting - scroll-to-top arrow
	$wp_customize->add_setting( 'scroll_to_top', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_modern_store_sanitize_yes_no_settings'
	) );
	// control - scroll-to-top arrow
	$wp_customize->add_control( 'scroll_to_top', array(
		'label'    => __( 'Display Scroll-to-top arrow?', 'modern-store' ),
		'section'  => 'ct_modern_store_additional_options',
		'settings' => 'scroll_to_top',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'modern-store' ),
			'no'  => __( 'No', 'modern-store' )
		)
	) );
}

/***** Custom Sanitization Functions *****/

function ct_modern_store_sanitize_email( $input ) {
	return sanitize_email( $input );
}

// sanitize yes/no settings
function ct_modern_store_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'modern-store' ),
		'no'  => __( 'No', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_modern_store_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

function ct_modern_store_sanitize_tagline_settings( $input ) {

	$valid = array(
		'header-footer' => __( 'Yes, in the header & footer', 'modern-store' ),
		'header'        => __( 'Yes, in the header', 'modern-store' ),
		'footer'        => __( 'Yes, in the footer', 'modern-store' ),
		'no'            => __( 'No', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_sidebar_settings( $input ) {

	$valid = array(
		'after'  => __( 'Yes, after main content', 'modern-store' ),
		'before' => __( 'Yes, before main content', 'modern-store' ),
		'no'     => __( 'No', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_post_categories( $input ) {

	$categories_array = array( 'all' => 'All' );
	foreach ( get_categories() as $category ) {
		$categories_array[$category->term_id] = $category->name;
	}

	return array_key_exists( $input, $categories_array ) ? $input : '';
}

function ct_modern_store_sanitize_layout( $input ) {

	/*
	 * Also allow layouts only included in the premium plugin.
	 * Needs to be done this way b/c sanitize_callback cannot by updated
	 * via get_setting()
	 */
	$valid = array(
		'right-sidebar' => __( 'Right sidebar', 'modern-store' ),
		'left-sidebar'  => __( 'Left sidebar', 'modern-store' ),
		'narrow'        => __( 'No sidebar - Narrow', 'modern-store' ),
		'wide'          => __( 'No sidebar - Wide', 'modern-store' ),
		'two-right'     => __( 'Two column - Right sidebar', 'modern-store' ),
		'two-left'      => __( 'Two column - Left sidebar', 'modern-store' ),
		'two-narrow'    => __( 'Two column - No Sidebar - Narrow', 'modern-store' ),
		'two-wide'      => __( 'Two column - No Sidebar - Wide', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_fi_size_type( $input ) {

	$valid = array(
		'yes' => __( 'Yes, keep all Featured Images the same aspect ratio', 'modern-store' ),
		'no'  => __( 'No, use the natural size of each image', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_header_text_alignment( $input ) {

	$valid = array(
		'left' 	 => __( 'Left', 'modern-store' ),
		'center' => __( 'Center', 'modern-store' ),
		'right'  => __( 'Right', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_header_promo_display( $values ) {

	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;

	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

function ct_modern_store_sanitize_header_promo_overlay_opacity( $input ) {
	if ( is_float( floatval( $input ) ) ) {
		return $input;
	} else {
		return 0;
	}
}

//----------------------------------------------------------------------------------
// Sanitize product categories setting
//----------------------------------------------------------------------------------
function ct_modern_store_sanitize_product_categories( $input ) {

	$categories_array = array( 'all' => __('All', 'modern-store') );
	foreach ( get_terms('product_cat') as $category ) {
		$categories_array[$category->term_id] = $category->name;
	}

	return array_key_exists( $input, $categories_array ) ? $input : '';
}

function ct_modern_store_sanitize_product_order( $input ) {

	$valid = array(
		'newest' 			 	 => __( 'Newest', 'modern-store' ),
		'oldest' 				 => __( 'Oldest', 'modern-store' ),
		'cheapest' 			 => __( 'Cheapest', 'modern-store' ),
		'most-expensive' => __( 'Most expensive', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_category_order( $input ) {

	$valid = array(
		'alphabetical' 			 	 => __( 'Alphabetical', 'modern-store' ),
		'reverse-alphabetical' => __( 'Reverse alphabetical', 'modern-store' ),
		'most-products' 			 => __( 'Most products', 'modern-store' ),
		'least-products' 			 => __( 'Least products', 'modern-store' ),
		'newest' 							 => __( 'Newest', 'modern-store' ),
		'oldest' 							 => __( 'Oldest', 'modern-store' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_modern_store_sanitize_phone( $input ) {
	if ( $input != '' ) {
		return esc_url_raw( 'tel:' . $input, array( 'tel' ) );
	} else {
		return '';
	}
}