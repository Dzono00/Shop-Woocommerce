<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Icegram Campaign Admin class
*/
if ( ! class_exists( 'Icegram_Compat_forminator' ) ) {
	class Icegram_Compat_forminator extends Icegram_Compat_Base {

		function __construct() {
			global $icegram; 
			parent::__construct();

			add_filter('icegram_get_form_list', array( &$this, 'get_form_list'), 11, 1);
		}

		function get_form_list( $forms ){
			$form_list['forminator_forms'] = array();
			if ( function_exists( 'forminator_cform_modules' ) ) {

				$custom_forms = forminator_cform_modules( - 1, 'publish' );
				if ( ! empty( $custom_forms ) ) {

					foreach ( $custom_forms as $form ) {
						$form_id = $form['id'];
						if ( class_exists( 'Forminator_Custom_Form_Model' ) ) {
							$custom_form        = Forminator_Custom_Form_Model::model()->load( $form_id );
							$form_type          = ! empty( $custom_form->settings['form-type'] ) ? $custom_form->settings['form-type'] : '';
							
							$title = forminator_get_form_name( $form_id, 'custom_form' );
							
							if ( mb_strlen( $title ) > 25 ) {
								$title = mb_substr( $title, 0, 25 ) . '...';
							}
							
							$form_list['forminator_forms'][ 'forminatorforms_' . $form_id ] = $form_title;
						}
					}
					
					return array_merge( $forms, $form_list );
				}
			} elseif ( class_exists( 'Forminator_API' ) && method_exists( Forminator_API::class, 'get_forms' ) ) {
				$form_status      = Forminator_Form_Model::STATUS_PUBLISH;
				$forminator_forms = Forminator_API::get_forms( null, '', '', $form_status );

				if ( ! empty( $forminator_forms ) ) {
					foreach ( $forminator_forms as $form ) {
						$form_id    = $form->id;
						$form_title = $form->name;

						if ( ! empty( $form_id ) && ! empty( $form_title ) ) {

							if ( mb_strlen( $form_title ) > 25 ) {
								$form_title = mb_substr( $form_title, 0, 25 );
							}

							$form_list['forminator_forms'][ 'forminatorforms_' . $form_id ] = $form_title;
						}
					}
					return array_merge( $forms, $form_list );
				}
			}	
			
			return $forms;
		}
	}
}	