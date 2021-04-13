<?php
/**
 * Exit if accessed directly
 *
 * @package wallet-payment-gateway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wallet_bal = get_user_meta( $user_id, 'mwb_wallet', true );

?>

<div class='content active'>
    <form method="post" action="" id="mwb_wallet_transfer_form">
        <p class="mwb-wallet-field-container form-row form-row-wide">
            <label for="mwb_wallet_transfer_user_id"><?php esc_html_e( 'Transfer to :', 'wallet-system-for-woocommerce' ); ?></label>
            <input type="text" step="0.01" min="0" max="<?php esc_attr_e( $wallet_bal, 'wallet-system-for-woocommerce' ); ?>" class="mwb-wallet-userselect" name="mwb_wallet_transfer_user_id" required="">
        </p>
        <p class="mwb-wallet-field-container form-row form-row-wide">
            <label for="mwb_wallet_transfer_amount"><?php esc_html_e( 'Amount', 'wallet-system-for-woocommerce' ); ?></label>
            <input type="number" step="0.01" min="0" max="<?php esc_attr_e( $wallet_bal, 'wallet-system-for-woocommerce' ); ?>" id="mwb_wallet_transfer_amount" name="mwb_wallet_transfer_amount" required="">
        </p>
        <p class="error" style="color:red"></p>
        <p class="mwb-wallet-field-container form-row form-row-wide">
            <label for="mwb_wallet_transfer_note"><?php esc_html_e( 'What\'s this for', 'wallet-system-for-woocommerce' ); ?></label>
            <textarea name="mwb_wallet_transfer_note"></textarea>
        </p
        <p class="mwb-wallet-field-container form-row">
            <input type="hidden" name="current_user_id" value="<?php esc_attr_e( $user_id, 'wallet-system-for-woocommerce' ); ?>">
            <input type="submit" class="mwb-btn__filled button" id="mwb_proceed_transfer" name="mwb_proceed_transfer" value="Proceed">
        </p>
    </form>
</div>


