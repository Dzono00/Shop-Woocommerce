<span class="comments-link">
	<i class="fa fa-comment" title="<?php esc_attr_e( 'comment icon', 'modern-store' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( esc_html__( 'Comments closed', 'modern-store' ), esc_html__( '1 Comment', 'modern-store' ), esc_html__( '% Comments', 'modern-store' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( esc_html__( 'Leave a Comment', 'modern-store' ), esc_html__( '1 Comment', 'modern-store' ), esc_html__( '% Comments', 'modern-store' ) );
		echo '</a>';
	endif;
	?>
</span>