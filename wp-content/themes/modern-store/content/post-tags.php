<?php
if ( get_theme_mod( 'post_tags') == 'no' ) return;

$post_tags   = get_the_tags( $post->ID );
$output = '';
if ( $post_tags ) {
	echo '<div class="post-tags">';
	echo '<ul>';
	foreach ( $post_tags as $post_tag ) {
		// translators: placeholder is the name of the post tag
		echo '<li><a href="' . esc_url( get_tag_link( $post_tag->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'modern-store' ), $post_tag->name ) ) . '">' . esc_html( $post_tag->name ) . '</a></li>';
	}
	echo '</ul>';
	echo '</div>';
}