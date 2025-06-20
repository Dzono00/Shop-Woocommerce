<?php do_action( 'ct_modern_store_main_bottom' ); ?>
<?php ct_modern_store_pagination(); ?>
</section> <!-- .main-container -->
<?php do_action( 'ct_modern_store_after_main' ); ?>

<?php 
// Elementor `footer` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
    <?php do_action( 'ct_modern_store_footer_top' ); ?>
    <div class="design-credit">
        <span>
            <?php
            // Translators: %s is the URL of the theme
            $footer_text = sprintf( __( '<a href="%s" rel="nofollow">Modern Store WordPress Theme</a> by Compete Themes.', 'modern-store' ), 'https://www.competethemes.com/modern-store/' );
            $footer_text = apply_filters( 'ct_modern_store_footer_text', $footer_text );
            echo wp_kses_post( $footer_text );
            ?>
        </span>
    </div>
</footer>
<?php endif; ?>
</div><!-- .max-width -->
</div><!-- .overflow-container -->

<?php do_action( 'ct_modern_store_body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>