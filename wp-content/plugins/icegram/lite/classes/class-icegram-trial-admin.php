<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The trial-specific functionality of the plugin.
 */
class IG_Trial {

	public static $trial_days = 14;

	public function __construct() {
		add_action('admin_init', array( &$this, 'enable_trial' ));
		add_action('admin_init', array( &$this, 'handle_trial_plan' ));
		add_action('admin_notices', array( &$this, 'show_trial_notices' ));
		
		add_action('save_trial_campaign_message_ids', array( &$this, 'save_trial_campaign_message_ids' ));
		add_action('handle_trial_features', array( &$this, 'handle_trial_features' ));
		

	}

	public static function enable_trial(){
		global $icegram;
		if( isset( $_POST['ig_trial'] ) ){
			set_transient( 'ig_trial', true, ( 60*60*24* self::$trial_days ) );
			//Flag that keeps a track that user had opted for trial
			update_option('ig_trial_started_at', time());
			update_option('ig_opted_trial', 1);
						
			$icegram->set_icegram_plan('trial');
		}
	}

	public function handle_trial_plan() {

		global $icegram;
		$is_trial 			= $icegram->is_trial();
		$is_trial_expired 	= self::is_trial_expired();
		

		if( $icegram->is_premium() ){
			$trial_opted  = get_option('ig_opted_trial', false);
			
			if( $trial_opted ){
				
				$trial_feature_update_status = get_option( 'ig_trial_feature_recover' );

				//If the user has upgraded to premium plan during trial, end the trial
				if( ! $is_trial_expired ){

					//Deleting transient beforehand so that custom css is not removed when it expires
					self::end_trial();

				} else if( $is_trial_expired &&  'pending' == $trial_feature_update_status ) {
					//Recover custom css from trial message once user has updated to premium plan later
					do_action('handle_trial_features', 'update');
					
				}
			}		
			return true;
		}
		
		//If trial expired but not upgraded -> Update plan and handle features(custom css)
		if( $is_trial_expired && $icegram->is_trial() ){
			
			//Update plan to lite as trial has expired
			$icegram->set_icegram_plan('lite');
			
			//Remove custom css from trial message
			do_action('handle_trial_features', 'delete');
		}	
	}

	public function save_trial_campaign_message_ids( $trial_data = array()){
		
		if(empty($trial_data)){
			return;
		}
		
		if( isset($trial_data['campaign_id'])){
			update_option('ig_trial_campaign_id', $trial_data['campaign_id']);
		}
		if( isset($trial_data['messages'])){
			update_option('ig_trial_message_ids', array_column( $trial_data['messages'], 'id' ));
		}

	}

	public function handle_trial_features( $action = ''){
		//Fetch message ids of trial campaign
		if( !empty($action)){
			$message_ids = get_option('ig_trial_message_ids');
			if( !empty( $message_ids ) ){
				foreach( $message_ids as $id ){
					
					$message_data = get_post_meta( $id, 'icegram_message_data', true );
					switch ( $action ) {
						case 'delete':
							$custom_css = $message_data['custom_css'];
							$message_data['custom_css'] = '';
							
							//Save custom css in a seperate meta key
							update_post_meta($id,'icegram_trial_custom_css', $custom_css );
							update_option('ig_trial_feature_recover', 'pending');
							break;

						case 'update' :
							$message_data['custom_css'] = $message_data['custom_css'] . ' ' . get_post_meta( $id, 'icegram_trial_custom_css', true );
							
							delete_post_meta($id,'icegram_trial_custom_css' );
							update_option('ig_trial_feature_recover', 'done');
							break;
						default:
							
							break;
					}
					
					update_post_meta($id, 'icegram_message_data', $message_data );
					
				}
			}
		}
	}

	public static function is_trial_expired(){
		$is_trial_valid = get_transient( 'ig_trial' );

		if( ! $is_trial_valid ){
			return true;
		} else{
			return false;
		}

	}

	public static function get_trial_expiry_date( $date_format = 'Y-m-d H:i:s' ) {

		$trial_expiry_date = '';
		$trial_started_at  = self::get_trial_started_at();
		
		if ( ! empty( $trial_started_at ) ) {
			$trial_expires_at  = $trial_started_at + ( self::$trial_days * DAY_IN_SECONDS );
			$trial_expiry_date = gmdate( $date_format, $trial_expires_at );
		}

		return $trial_expiry_date;
	}

	public static function get_trial_started_at() {
		$trial_started_at = get_option( 'ig_trial_started_at', 0 );
		return $trial_started_at;
	}

	public static function get_remaining_trial_days() {

		$total_days_since_trial = self::get_days_since_trial_started();
		$trial_period_in_days   = self::$trial_days;
		$remaining_trial_days   = $trial_period_in_days - $total_days_since_trial;

		return $remaining_trial_days;
	}

	public static function get_days_since_trial_started() {

		$current_time       = time();
		$trial_time_in_days = 0;
		$trial_started_at   = get_option( 'ig_trial_started_at' );

		if ( ! empty( $trial_started_at ) ) {
			$trial_time_in_seconds = $current_time - $trial_started_at;
			$trial_time_in_days    = floor( $trial_time_in_seconds / DAY_IN_SECONDS );
		}

		return $trial_time_in_days;
	}

	public function end_trial() {
		delete_transient( 'ig_trial' );
	}

	/**
	 * Method to show trial related notices to user.
	 *	 
	 */
	public function show_trial_notices() {

		global $icegram;
		
		// Don't show trial notices untill onboarding is completed.
		if ( ! $icegram->is_onboarding_completed() ) {
			return;
		}
		
		$is_trial             			= $icegram->is_trial();
		$is_premium           			= $icegram->is_premium();
		$is_premium_installed 			= $icegram->is_premium_installed();
		$ig_is_page_for_notifications   = apply_filters( 'ig-engage_is_page_for_notifications', false );
		
		$show_offer_notice = false;
		
		// Add upgrade to premium nudging notice if user has opted for trial and is not a premium user and premium plugin is not installed on site and is not dashboard page.
		if ( $is_trial && ! $is_premium && ! $is_premium_installed && $ig_is_page_for_notifications ) {

			// Start nudging the user on following days before trial expiration.
			$nudging_days    = array( 1, 3 );
			$min_nudging_day = min( $nudging_days );
			$max_nudging_day = max( $nudging_days );

			// Current day's number from start of trial.
			$remaining_trial_days = self::get_remaining_trial_days();
			
			// User is in nudging period if remaining trial days are between minmum and maximum nudging days.
			$is_in_nudging_period = $remaining_trial_days >= $min_nudging_day && $remaining_trial_days <= $max_nudging_day ? true : false;
			
			// Start nudging the user if peried fall into nudging period.
			if ( $is_in_nudging_period ) {
				$current_nudging_day = 0;

				foreach ( $nudging_days as $day ) {
					if ( $remaining_trial_days <= $day ) {
						// Get current nudging day i.e. 1 or 3 or 5
						$current_nudging_day = $day;
						break;
					}
				}

				// Check if we have a nudging day.
				if ( ! empty( $current_nudging_day ) ) {
					$notice_last_dismiss_date = get_option( 'ig_trial_to_premium_notice_date' );
					
					// Always show notice if not already dismissed before.
					if ( empty( $notice_last_dismiss_date ) ) {
						$show_offer_notice = true;
					} else {
						$trial_expiry_date    = self::get_trial_expiry_date();
					
						$date_diff_in_seconds = strtotime( $trial_expiry_date ) - strtotime( $notice_last_dismiss_date );

						// Ceil function is used to round off to nearest upper limit integer, 4.1 would be 5.
						$date_diff_in_days = ceil( $date_diff_in_seconds / DAY_IN_SECONDS );
						
						// Check if current nudging day is after last dismissed date.
						if ( $current_nudging_day < $date_diff_in_days ) {
							$show_offer_notice = true;
						}
					}
				}
			}
		}
		
		if ( $show_offer_notice ) {
			include_once IG_PLUGIN_DIR . 'lite/notices/admin-trial-upsale-notices.php';
		} 
	}
}

new IG_Trial();