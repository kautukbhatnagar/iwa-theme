<?php
/**
 * IWA Order Received / Thank You page
 */
defined( 'ABSPATH' ) || exit;
?>

<?php if ( $order ) : ?>

<div class="max-w-2xl mx-auto">

    <!-- Success banner -->
    <div class="rounded-2xl bg-gradient-to-br from-iwa-green to-[#057a42] text-white p-8 mb-8 text-center">
        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
        </div>
        <h1 class="font-heading text-2xl font-bold mb-1"><?php esc_html_e( 'Order Confirmed!', 'woocommerce' ); ?></h1>
        <p class="text-white/80 text-sm">
            <?php
            if ( $order->has_status( 'failed' ) ) {
                esc_html_e( 'Unfortunately your order cannot be processed. Please attempt to pay again.', 'woocommerce' );
            } else {
                esc_html_e( 'Thank you for your purchase. We\'ll send you a confirmation email shortly.', 'woocommerce' );
            }
            ?>
        </p>
    </div>

    <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

    <!-- Order details card -->
    <div class="rounded-2xl border border-gray-100 bg-white overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="font-heading font-bold text-base text-iwa-ink">
                <?php printf( esc_html__( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?>
            </h2>
            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide
                <?php echo ( $order->get_status() === 'completed' ) ? 'bg-green-100 text-iwa-green' : 'bg-orange-100 text-iwa-saffron'; ?>">
                <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
            </span>
        </div>

        <!-- Summary info row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-100">
            <?php
            $cols = [
                [ 'label' => __( 'Date', 'woocommerce' ),    'value' => wc_format_datetime( $order->get_date_created() ) ],
                [ 'label' => __( 'Total', 'woocommerce' ),   'value' => wp_kses_post( $order->get_formatted_order_total() ) ],
                [ 'label' => __( 'Payment', 'woocommerce' ), 'value' => $order->get_payment_method_title() ?: '—' ],
                [ 'label' => __( 'Email', 'woocommerce' ),   'value' => esc_html( $order->get_billing_email() ) ],
            ];
            foreach ( $cols as $col ) : ?>
            <div class="px-4 py-4 text-center">
                <p class="text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft mb-1"><?php echo esc_html( $col['label'] ); ?></p>
                <p class="text-sm font-bold text-iwa-ink"><?php echo $col['value']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Order items -->
        <div class="border-t border-gray-100">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                        <th class="text-right px-6 py-3 text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ( $order->get_items() as $item_id => $item ) :
                        $product = $item->get_product();
                    ?>
                    <tr class="border-b border-gray-50">
                        <td class="px-6 py-3 text-iwa-ink">
                            <?php echo esc_html( $item->get_name() ); ?>
                            <span class="text-iwa-ink-soft text-xs ml-1">&times; <?php echo esc_html( $item->get_quantity() ); ?></span>
                        </td>
                        <td class="px-6 py-3 text-right font-semibold text-iwa-ink">
                            <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                    <tr class="<?php echo ( 'order_total' === $key ) ? 'border-t-2 border-gray-200' : 'border-t border-gray-100'; ?>">
                        <th class="px-6 py-3 text-left font-semibold text-iwa-ink <?php echo ( 'order_total' === $key ) ? 'text-base' : 'text-sm'; ?>">
                            <?php echo esc_html( $total['label'] ); ?>
                        </th>
                        <td class="px-6 py-3 text-right font-bold text-iwa-ink <?php echo ( 'order_total' === $key ) ? 'text-base' : 'text-sm'; ?>">
                            <?php echo wp_kses_post( $total['value'] ); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Billing / Shipping addresses -->
    <?php if ( $show_customer_details ) : ?>
    <div class="grid sm:grid-cols-2 gap-4 mb-8">
        <?php if ( $order->get_formatted_billing_address() ) : ?>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <h3 class="font-heading font-bold text-sm text-iwa-ink mb-3 pb-2 border-b border-gray-100"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h3>
            <address class="not-italic text-sm text-iwa-ink-soft leading-relaxed">
                <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
            </address>
            <?php if ( $order->get_billing_phone() ) : ?>
            <p class="text-sm text-iwa-ink-soft mt-2">
                <span class="font-semibold"><?php esc_html_e( 'Phone:', 'woocommerce' ); ?></span>
                <?php echo esc_html( $order->get_billing_phone() ); ?>
            </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ( $order->get_formatted_shipping_address() ) : ?>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <h3 class="font-heading font-bold text-sm text-iwa-ink mb-3 pb-2 border-b border-gray-100"><?php esc_html_e( 'Shipping Address', 'woocommerce' ); ?></h3>
            <address class="not-italic text-sm text-iwa-ink-soft leading-relaxed">
                <?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?>
            </address>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
    <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

    <!-- CTAs -->
    <div class="flex flex-col sm:flex-row gap-3 mt-4">
        <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"
           class="flex-1 text-center rounded-full border-2 border-iwa-saffron text-iwa-saffron font-semibold px-6 py-3 text-sm hover:bg-iwa-saffron hover:text-white transition">
            <?php esc_html_e( 'View Order Details', 'woocommerce' ); ?>
        </a>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="flex-1 text-center rounded-full bg-iwa-saffron text-white font-semibold px-6 py-3 text-sm hover:bg-iwa-saffron-light transition">
            <?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?>
        </a>
    </div>

</div>

<?php else : ?>

<!-- Fallback for non-order thank you (e.g. pay for order) -->
<div class="max-w-lg mx-auto text-center py-16">
    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-iwa-green" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
    </div>
    <h2 class="font-heading text-xl font-bold text-iwa-ink mb-2"><?php esc_html_e( 'Thank you for your order!', 'woocommerce' ); ?></h2>
    <p class="text-iwa-ink-soft text-sm mb-6"><?php echo wp_kses_post( apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Your order has been received and is now being processed.', 'woocommerce' ), null ) ); ?></p>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
       class="inline-block rounded-full bg-iwa-saffron text-white font-semibold px-8 py-3 text-sm hover:bg-iwa-saffron-light transition">
        <?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?>
    </a>
</div>

<?php endif; ?>
