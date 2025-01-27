<?php
/**
 * Handles all admin ajax requests.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Wallet_System_For_Woocommerce
 * @subpackage Wallet_System_For_Woocommerce/includes
 */

/**
 * Handles all admin ajax requests.
 *
 * All the functions required for handling admin ajax requests
 * required by the plugin.
 *
 * @since      1.0.0
 * @package    Wallet_System_For_Woocommerce
 * @subpackage Wallet_System_For_Woocommerce/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Wallet_System_AjaxHandler {

	/**
	 * Construct.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		add_action( 'wp_ajax_calculate_amount_after_wallet', array( &$this, 'calculate_amount_after_wallet' ) );
		add_action( 'wp_ajax_mwb_search_for_user', array( &$this, 'mwb_search_for_user' ) );
		add_action( 'wp_ajax_unset_wallet_session', array( &$this, 'unset_wallet_session' ) );

	}

	/**
	 * Set the session when partial payment is enabled
	 *
	 * @return void
	 */
	public function calculate_amount_after_wallet() {
		if ( is_user_logged_in() ) {
			check_ajax_referer( 'ajax-nonce', 'nonce' );
			$message = array();
			if ( isset( $_POST['checked'] ) && 'true' === $_POST['checked'] ) {
				WC()->session->set( 'is_wallet_partial_payment', 'true' );
			} else {
				WC()->session->set( 'is_wallet_partial_payment', 'false' );
			}
			$wallet_amount = empty( $_POST['wallet_amount'] ) ? 0 : sanitize_text_field( wp_unslash( $_POST['wallet_amount'] ) );
			$amount = empty( $_POST['amount'] ) ? 0 : sanitize_text_field( wp_unslash( $_POST['amount'] ) );
			if ( $wallet_amount >= $amount ) {
				$wallet_amount     -= $amount;
				$message['message'] = esc_html__( 'Wallet balance after using amount from it: ', 'wallet-system-for-woocommerce' ) . wc_price( $wallet_amount );
				$message['price']   = wc_price( $amount );
				WC()->session->set( 'custom_fee', $amount );

			} else {
				$message['message'] = esc_html__( 'Please enter amount less than or equal to wallet balance', 'wallet-system-for-woocommerce' );
			}
			wp_send_json( $message );
			wp_die();
		}
	}


	/**
	 * Unset the session on disabling partial payment
	 *
	 * @return void
	 */
	public function unset_wallet_session() {
		WC()->session->__unset( 'custom_fee' );
		WC()->session->__unset( 'is_wallet_partial_payment' );
		echo 'true';
		wp_die();
	}

	/**
	 * Ajax search for user by email address
	 *
	 * @return void
	 */
	public function mwb_search_for_user() {
		$return = array();
		$email  = ( isset( $_GET['email'] ) ) ? sanitize_text_field( wp_unslash( $_GET['email'] ) ) : '';
		$users  = get_users( array( 'fields' => array( 'user_email', 'ID', 'user_login' ) ) );
		if ( ! empty( $users ) && is_array( $users ) ) {
			foreach ( $users as $key => $user ) {
				if ( $email == $user->user_email ) {
					$return[] = array( $user->ID, $user->user_email, $user->user_login );
				}
			}
		}
		echo json_encode( $return );
		wp_die();
	}

}
