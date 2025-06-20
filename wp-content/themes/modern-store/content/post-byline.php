<?php
$author_display = get_theme_mod( 'post_byline_author' );
$date_display   = get_theme_mod( 'post_byline_date' );

if ( $author_display == 'no' && $date_display == 'no' ) {
	return;
}

$author = get_the_author();
// add compatibility when used in header before loop
if ( empty( $author ) ) {
	global $post;
	$author = get_the_author_meta( 'display_name', $post->post_author );
}
$date   = date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) );

echo '<div class="post-byline">';
if ( $author_display == 'no' ) {
	// translators: placeholder is the date the post was published
	printf( esc_html_x( 'Published %s', 'This blog post was published on some date', 'modern-store' ), esc_html( $date ) );
} elseif ( $date_display == 'no' ) {
	// translators: placeholder is the author who published the post
	printf( esc_html_x( 'Published by %s', 'This blog post was published by some author', 'modern-store' ), esc_html( $author ) );
} else {
	// translators: placeholders are the date the post was published and the author who published it
	printf( _x( '<span>Published by</span> %1$s <span>on</span> %2$s', 'This blog post was published on some date by some author', 'modern-store' ), esc_html( $author ), esc_html( $date ) );
}
echo '</div>';
