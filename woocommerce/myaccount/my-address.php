<?php
/**
 * IWA My Account — Address Book
 */
defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
    $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', [
        'billing'  => __( 'Billing address', 'woocommerce' ),
        'shipping' => __( 'Shipping address', 'woocommerce' ),
    ], $customer_id );
} else {
    $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', [
        'billing' => __( 'Billing address', 'woocommerce' ),
    ], $customer_id );
}

$oldcol = 1;
$col    = 1;
?>

<div class="space-y-4">
    <div class="mb-2">
        <h2 class="font-heading font-bold text-base text-iwa-ink"><?php esc_html_e( 'My Addresses', 'woocommerce' ); ?></h2>
        <p class="text-xs text-iwa-ink-soft mt-0.5"><?php esc_html_e( 'The following addresses will be used by default during checkout.', 'woocommerce' ); ?></p>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">

    <?php foreach ( $get_addresses as $name => $title ) :
        $address = wc_get_account_formatted_address( $name, $customer_id );
        $col++;
    ?>
        <div class="rounded-xl border border-gray-100 bg-white overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-heading font-semibold text-sm text-iwa-ink"><?php echo esc_html( $title ); ?></h3>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>"
                   class="text-xs font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">
                    <?php esc_html_e( 'Edit', 'woocommerce' ); ?>
                </a>
            </div>
            <div class="px-5 py-4">
                <?php if ( $address ) : ?>
                <address class="not-italic text-sm text-iwa-ink-soft leading-relaxed">
                    <?php echo wp_kses_post( $address ); ?>
                </address>
                <?php else : ?>
                <p class="text-sm text-iwa-ink-soft italic"><?php esc_html_e( 'No address saved yet.', 'woocommerce' ); ?></p>
                <a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>"
                   class="inline-flex items-center gap-1 text-xs font-semibold text-iwa-saffron hover:text-iwa-saffron-light mt-2 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <?php esc_html_e( 'Add address', 'woocommerce' ); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    </div>
</div>
