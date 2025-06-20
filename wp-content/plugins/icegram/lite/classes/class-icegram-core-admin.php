<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'ig_get_request_data' ) ) {
	function ig_get_request_data( $var = '', $default = '', $clean = true ) {
		return ig_get_data( $_REQUEST, $var, $default, $clean );
	}
}

if ( ! function_exists( 'ig_get_data' ) ) {
	function ig_get_data( $array = array(), $var = '', $default = '', $clean = false ) {

		if ( ! empty( $var ) ) {
			$value = isset( $array[ $var ] ) ? wp_unslash( $array[ $var ] ) : $default;
		} else {
			$value = wp_unslash( $array );
		}

		if ( $clean ) {
			$value = ig_clean( $value );
		}

		return $value;
	}
}

if ( ! function_exists( 'ig_clean' ) ) {
	/**
	 * Clean String or array using sanitize_text_field
	 *
	 * @param $variable Data to sanitize
	 *
	 * @return array|string
	 *
	 * @since 3.1.12
	 */
	function ig_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'ig_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}

if ( ! function_exists('ig_array_contains') ) {
	function ig_array_contains( $array, $findme ) {
		return array_filter($array, function ($val) use ( $findme ) {
			return strpos( $val, $findme ) !== false;
		});
	}
}