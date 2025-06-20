<?php
if ( get_theme_mod( 'store_search_bar_display' ) == 'no' ) {
	return;
}

$product_categories = array();
if ( ct_modern_store_is_wc_active() ) {
	$product_categories = get_terms('product_cat');
}
if ( isset($wp_query->query['product_cat']) ) {
	$product_category = $wp_query->query['product_cat'];
} else {
	$product_category = '';
}
if ( isset($wp_query->query['post_type']) ) {
	$search_value = get_search_query();
} else {
	$search_value = '';
}
$current_category_slug = $product_category;
if (strpos($product_category, '/') !== false) {
	$current_category_slug = substr($product_category, strpos($product_category, "/") + 1);
}
$category_label = get_theme_mod( 'store_search_bar_category_select_label' );
?>
<div id='search-form-container' class='search-form-container'>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label class="screen-reader-text"><?php esc_html_e( 'Search', 'modern-store' ); ?></label>
		<?php if ( get_theme_mod( 'store_search_bar_category_select' ) != 'no' ) : ?>
			<span class="category-select">
				<i class="fas fa-caret-down"></i>	
				<select id="store-search">
					<option>
						<?php
						if ( empty( $category_label ) ) {
							esc_html_e( 'All', 'modern-store' );
						} else {
							echo esc_html( $category_label );
						} ?>
					</option>
					<?php 
					foreach ( $product_categories as $category ) { 
						if ( $wp_query->query['product_cat'] == $category->slug || $current_category_slug == $category->slug ) {
							$selected = true;
						} else {
							$selected = false;
						}
						?>
						<option value="<?php echo esc_attr( $category->slug ); ?>" <?php echo selected( $selected ); ?>><?php echo esc_html( $category->name ); ?></option>
					<?php } ?>
				</select>
			</span>
		<?php endif; ?>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search...', 'modern-store' ); ?>" value="<?php echo esc_attr( $search_value ); ?>" name="s"
					 title="<?php esc_attr_e( 'Search for:', 'modern-store' ); ?>" tabindex="-1"/>
		<?php if ( get_theme_mod( 'store_search_bar_submit_button' ) != 'no' ) : ?>
			<div class="submit-button">
				<input type="submit" class="search-submit" value='<?php esc_attr_e( 'Search', 'modern-store' ); ?>'/>
			</div>
		<?php endif; ?>
		<input type="hidden" value="product" name="post_type" id="post_type" />
		<input type="hidden" value="<?php echo esc_attr( $product_category ); ?>" name="product_cat" id="product_cat_search" />
	</form>
</div>