<?php
if ( is_active_sidebar( 'store' ) ) : ?>
    <aside class="sidebar sidebar-store" id="sidebar-store" role="complementary">
        <?php dynamic_sidebar( 'store' ); ?>
    </aside>
<?php endif;