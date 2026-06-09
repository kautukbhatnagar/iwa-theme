<?php
/**
 * IWA My Account — View Order
 */
defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="space-y-6">

    <!-- Back link -->
    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
       class="inline-flex items-center gap-1.5 text-sm text-iwa-saffron font-semibold hover:text-iwa-saffron-light transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        <?php esc_html_e( 'Back to Orders', 'woocommerce' ); ?>
    </a>

    <!-- Order header -->
    <div class="rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
            <div>
                <h2 class="font-heading font-bold text-base text-iwa-ink">
                    <?php printf( esc_html__( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?>
                </h2>
                <p class="text-xs text-iwa-ink-soft mt-0.5">
                    <?php printf( esc_html__( 'Placed on %s', 'woocommerce' ), wc_format_datetime( $order->get_date_created() ) ); ?>
                </p>
            </div>
            <span class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide
                <?php echo ( $order->get_status() === 'completed' ) ? 'bg-green-100 text-iwa-green' : 'bg-orange-100 text-iwa-saffron'; ?>">
                <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
            </span>
        </div>

        <!-- Order items table -->
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                    <th class="text-right px-6 py-3 text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft hidden sm:table-cell"><?php esc_html_e( 'Qty', 'woocommerce' ); ?></th>
                    <th class="text-right px-6 py-3 text-[10px] uppercase tracking-wider font-semibold text-iwa-ink-soft"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $order->get_items() as $item_id => $item ) :
                    $product     = $item->get_product();
                    $thumb_url   = $product ? get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' ) : '';
                ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php if ( $thumb_url ) : ?>
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt=""
                                 class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-100">
                            <?php else : ?>
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex-shrink-0"></div>
                            <?php endif; ?>
                            <div>
                                <p class="font-semibold text-iwa-ink text-sm"><?php echo esc_html( $item->get_name() ); ?></p>
                                <?php
                                $item_meta = new WC_Order_Item_Product( $item_id );
                                $meta_data = $item->get_all_formatted_meta_data( '' );
                                foreach ( $meta_data as $meta ) :
                                ?>
                                <p class="text-xs text-iwa-ink-soft mt-0.5">
                                    <?php echo wp_kses_post( $meta->display_key . ': ' . $meta->display_value ); ?>
                                </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right text-iwa-ink-soft hidden sm:table-cell">
                        &times; <?php echo esc_html( $item->get_quantity() ); ?>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-iwa-ink">
                        <?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                <tr class="<?php echo ( 'order_total' === $key ) ? 'border-t-2 border-gray-200' : 'border-t border-gray-100'; ?>">
                    <th colspan="2" class="px-6 py-3 text-left font-semibold text-iwa-ink <?php echo ( 'order_total' === $key ) ? '' : 'text-sm'; ?>">
                        <?php echo esc_html( $total['label'] ); ?>
                    </th>
                    <td class="px-6 py-3 text-right font-bold text-iwa-ink <?php echo ( 'order_total' === $key ) ? '' : 'text-sm'; ?>">
                        <?php echo wp_kses_post( $total['value'] ); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tfoot>
        </table>
    </div>

    <!-- Customer details: billing + shipping -->
    <div class="grid sm:grid-cols-2 gap-4">
        <?php if ( $order->get_formatted_billing_address() ) : ?>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <h3 class="font-heading font-bold text-sm text-iwa-ink mb-3 pb-2 border-b border-gray-100"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?></h3>
            <address class="not-italic text-sm text-iwa-ink-soft leading-relaxed">
                <?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?>
            </address>
            <?php if ( $order->get_billing_phone() ) : ?>
            <p class="text-xs text-iwa-ink-soft mt-2">
                <?php printf( esc_html__( 'Phone: %s', 'woocommerce' ), esc_html( $order->get_billing_phone() ) ); ?>
            </p>
            <?php endif; ?>
            <?php if ( $order->get_billing_email() ) : ?>
            <p class="text-xs text-iwa-ink-soft mt-1">
                <?php printf( esc_html__( 'Email: %s', 'woocommerce' ), esc_html( $order->get_billing_email() ) ); ?>
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
        <?php elseif ( $order->get_formatted_billing_address() ) : ?>
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 flex items-center justify-center">
            <p class="text-sm text-iwa-ink-soft"><?php esc_html_e( 'Same as billing address', 'woocommerce' ); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Order notes -->
    <?php if ( $notes ) : ?>
    <div class="rounded-xl border border-gray-100 bg-white p-5">
        <h3 class="font-heading font-bold text-sm text-iwa-ink mb-3 pb-2 border-b border-gray-100"><?php esc_html_e( 'Order Updates', 'woocommerce' ); ?></h3>
        <ol class="space-y-3">
            <?php foreach ( $notes as $note ) : ?>
            <li class="flex gap-3">
                <div class="w-2 h-2 rounded-full bg-iwa-saffron mt-1.5 flex-shrink-0"></div>
                <div>
                    <p class="text-sm text-iwa-ink"><?php echo wp_kses_post( $note->comment_content ); ?></p>
                    <p class="text-xs text-iwa-ink-soft mt-0.5"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $note->comment_date ) ) ); ?></p>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
    <?php endif; ?>

    <!-- Actions (pay again, cancel, etc.) -->
    <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

    <?php
    $actions = wc_get_account_orders_actions( $order );
    if ( ! empty( $actions ) ) : ?>
    <div class="flex flex-wrap gap-3">
        <?php foreach ( $actions as $key => $action ) : ?>
        <a href="<?php echo esc_url( $action['url'] ); ?>"
           class="rounded-full border-2 text-sm font-semibold px-5 py-2 transition
               <?php echo ( 'pay' === $key ) ? 'border-iwa-saffron bg-iwa-saffron text-white hover:bg-iwa-saffron-light hover:border-iwa-saffron-light' : 'border-gray-200 text-iwa-ink-soft hover:border-iwa-ink hover:text-iwa-ink'; ?>">
            <?php echo esc_html( $action['name'] ); ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
