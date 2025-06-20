<?php 

$slides = array( 'slide_1', 'slide_2', 'slide_3', 'slide_4', 'slide_5');
$content = '';
$count = 0;

foreach ( $slides as $slide ) {

  if ( $count > 0 && !defined( 'MODERN_STORE_PRO_FILE' ) ) {
    break;
  }
  if ( ct_modern_store_header_promo_output_rules( $slide ) == false ) continue;
  $count++;

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

  $slide_title = get_theme_mod( 'header_promo_title_text_' . $slide );
  $subtitle = get_theme_mod( 'header_promo_subtitle_text_' . $slide );
  $button_url = get_theme_mod( 'header_promo_button_url_' . $slide );
  $button_text = get_theme_mod( 'header_promo_button_text_' . $slide );
  
  if ( $count == 1 ) {
    $classes = 'slide-1 ' . $slide . ' current';
  } else {
    $classes = 'slide-' . $count . ' ' . $slide;
  }
  $content .= '<div class="slide '. esc_attr( $classes ) .'">';
  $content .= '<div class="content">';
  $content .= '<div class="title">'. esc_html( $slide_title ) .'</div>';
  $content .= '<div class="subtitle">'. esc_html( $subtitle ) .'</div>';
  if ( $button_text != '' ) {
    $content .= '<div class="button">';
    $content .= '<a href="'. esc_url( $button_url ) .'">'. esc_html( $button_text ) .'</a>';
    $content .= '</div>';
  }
  $content .= '</div>';
  $content .= '<div class="background"></div>';
  $content .= '<div class="overlay"></div>';
  $content .= '</div>';
}

// Prepare slide navigation content
$navigation = '';
if ( $count > 1 ) {
  $navigation .= '<div class="navigation">';
  for ($x = 1; $x <= $count; $x++) {
    if ( $x == 1 ) {
      $classes = 'slide-1 current';
    } else {
      $classes = 'slide-' . $x;
    }
    $navigation .= '<div class="'. esc_attr( $classes ) .'"><button>'. esc_html__( 'View slide', 'modern-store') .'</button></div>';
  }
  $navigation .= '</div>';
}


if ( !empty( $content ) ) { ?>
  <div id="header-promo" class="header-promo">
    <div class="slides">
      <?php echo wp_kses_post($content); ?>  
    </div>
    <?php echo wp_kses_post( $navigation ); ?>  
  </div>
<?php }