<div <?php post_class(); ?>>
	<?php do_action( 'ct_modern_store_page_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<?php ct_modern_store_featured_image(); ?>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'modern-store' ),
				'after'  => '</p>',
			) ); ?>
		</div>
	</article>
	<?php do_action( 'ct_modern_store_page_after' ); ?>
	<?php comments_template(); ?>
</div>