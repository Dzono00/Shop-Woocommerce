<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_wpforms' ) ) {
	class Icegram_Compat_wpforms extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();

			add_filter('icegram_get_form_list', array( &$this, 'get_form_list'), 13, 1);
		}

		function get_form_list( $forms ){
			$form_list['wp_forms'] = array();
			
			if ( class_exists( 'WPForms_Form_Handler' ) && method_exists( WPForms_Form_Handler::class, 'get' ) ) {


				$wp_forms_instance = new WPForms_Form_Handler();

				$args = array(
					'post_status' => 'publish',
				);
				$id   = '';

				$wp_forms_data = $wp_forms_instance->get( $id, $args );

				if ( ! empty( $wp_forms_data ) ) {
					foreach ( $wp_forms_data as $form ) {

						$form_id    = $form->ID;
						$form_title = $form->post_title;

						if ( ! empty( $form_id ) && ! empty( $form_title ) ) {

							if ( mb_strlen( $form_title ) > 25 ) {
								$form_title = mb_substr( $form_title, 0, 25 );
							}

							$form_list['wp_forms'][ 'wpforms_' . $form_id ] = $form_title;
						}
					}
					return array_merge( $forms, $form_list );
				}
			}
			return $forms;
		}

		function render_js( $type ) {
			global $icegram;
			if( 'wpforms' === $type || 'yes' === $icegram->cache_compatibility ){
			?>
				<script type="text/javascript">
					jQuery(function() {
					  	jQuery( window ).on( "init.icegram", function(e, ig) {
							if(typeof ig !== 'undefined' && typeof ig.messages !== 'undefined' ) {
								jQuery(ig.messages).each(function(i, msg){
									if(this.el.find('form[id^=wpforms]').length > 0 ){
										var form = this.el.find('form');
										this.el.find('input[type="submit"]').addClass('wpforms-submit');
										form.addClass('wpforms-validate wpforms-form');
										if ( form.data( 'token' ) ) {
											jQuery( '<input type="hidden" class="wpforms-token" name="wpforms[token]" />' )
												.val( form.data( 'token' ) )
												.appendTo( form );
										}
									}
								});
							}
						});
					});
				</script>

			<?php
			}
		}		
	}
}