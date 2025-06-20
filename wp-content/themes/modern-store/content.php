<div <?php post_class(); ?>>
	<?php do_action( 'ct_modern_store_post_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part( 'content/post-byline' ); ?>
		</div>
		<?php ct_modern_store_featured_image(); ?>	
		<div class="post-content">
			<?php ct_modern_store_output_last_updated_date(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'modern-store' ),
				'after'  => '</p>',
			) ); ?>
		</div>
		<div class="post-meta">
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
			<?php get_template_part( 'content/post-nav' ); ?>
		</div>
	</article>
	<?php do_action( 'ct_modern_store_post_after' ); ?>
	<?php comments_template(); ?>
</div>