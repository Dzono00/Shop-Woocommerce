<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_gravityforms' ) ) {
	class Icegram_Compat_gravityforms extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();
			
			if($icegram->cache_compatibility === 'yes') {
				add_filter( 'gform_form_tag', 'change_form_action_url', 10, 2 );
				function change_form_action_url( $form_tag, $form ) {
				    $form_tag = preg_replace( "|action='(.*?)'|", "action='".Icegram::get_current_page_url()."'", $form_tag );
				    return $form_tag;
				}
			    
			}
			add_filter('icegram_get_form_list', array( &$this, 'get_form_list'), 10, 1);
		}

		function get_form_list( $forms ){

			$form_list['gravity_forms'] = array();

			if ( class_exists( 'GFFormsModel' ) && method_exists( GFFormsModel::class, 'get_forms' ) ) {
				$gravity_forms_data = GFFormsModel::get_forms();

				if ( ! empty( $gravity_forms_data ) ) {

					foreach ( $gravity_forms_data as $form ) {
						$form_id    = $form->id;
						$form_title = $form->title;

						if ( ! empty( $form_id ) && ! empty( $form_title ) ) {

							if ( mb_strlen( $form_title ) > 25 ) {
								$form_title = mb_substr( $form_title, 0, 25 );
							}

							
							$form_list['gravity_forms'][ 'gravityforms_' . $form_id ] = $form_title;
						}
					}
					return array_merge( $forms, $form_list );
				}
			}
			return $forms;
		}

		function render_js( $type ) {
			global $icegram;
			if( 'gravityform' === $type || 'yes' === $icegram->cache_compatibility ){
			?>

<style type="text/css"> 
	body.ig_laptop div#ui-datepicker-div[style],
 	body.ig_tablet div#ui-datepicker-div[style],
 	body.ig_mobile div#ui-datepicker-div[style]{
 		z-index: 9999999!important; 
 	} 
</style>

<script type="text/javascript">
jQuery(function() {
  	jQuery( window ).on( "init.icegram", function(e, ig) {
	  	// Find and init all datepicker inside gravityForms
	  	jQuery('body').on('focus', 'form[id^="gform_"] .datepicker', function(){
	  		jQuery(this).datepicker();
	  	});
  	}); // init.icegram
});
</script>

			<?php
			}
		}		
	}
}