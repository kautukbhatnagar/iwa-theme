<?php
/**
 * IWA My Account — Orders list
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<?php if ( $has_orders ) : ?>

<div class="space-y-4">

    <div class="flex items-center justify-between mb-2">
        <h2 class="font-heading font-bold text-base text-iwa-ink"><?php esc_html_e( 'My Orders', 'woocommerce' ); ?></h2>
        <span class="text-xs text-iwa-ink-soft"><?php esc_html_e( 'Most recent first', 'woocommerce' ); ?></span>
    </div>

    <div class="rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-100">
        <?php
        foreach ( $customer_orders->orders as $customer_order ) :
            $order          = wc_get_order( $customer_order );
            $item_count     = $order->get_item_count() - $order->get_item_count_refunded();
            $order_actions  = wc_get_account_orders_actions( $order );
        ?>
        <div class="flex items-center gap-4 px-4 py-4 bg-white hover:bg-gray-50 transition group">
            <!-- Order info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="text-sm font-bold text-iwa-ink">#<?php echo esc_html( $order->get_order_number() ); ?></p>
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide
                        <?php echo ( $order->get_status() === 'completed' ) ? 'bg-green-100 text-iwa-green' : 'bg-orange-100 text-iwa-saffron'; ?>">
                        <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                    </span>
                </div>
                <p class="text-xs text-iwa-ink-soft mt-0.5">
                    <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                    &bull;
                    <?php printf( esc_html( _n( '%d item', '%d items', $item_count, 'woocommerce' ) ), $item_count ); ?>
                </p>
            </div>

            <!-- Total -->
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-iwa-ink"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
            </div>

            <!-- Actions -->
            <div class="flex-shrink-0 flex gap-2">
                <?php foreach ( $order_actions as $key => $order_action ) : ?>
                <a href="<?php echo esc_url( $order_action['url'] ); ?>"
                   class="rounded-full text-xs font-semibold px-3 py-1.5 transition
                       <?php echo ( 'view' === $key )
                           ? 'border border-gray-200 text-iwa-ink-soft hover:border-iwa-saffron hover:text-iwa-saffron'
                           : 'bg-iwa-saffron text-white hover:bg-iwa-saffron-light'; ?>">
                    <?php echo esc_html( $order_action['name'] ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
    <nav class="flex justify-center gap-1 mt-6">
        <?php if ( 1 !== $current_page ) : ?>
        <a class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm text-iwa-ink-soft hover:border-iwa-saffron hover:text-iwa-saffron transition"
           href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">
            &larr;
        </a>
        <?php endif; ?>
        <?php for ( $i = 1; $i <= $customer_orders->max_num_pages; $i++ ) : ?>
        <a class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
               <?php echo ( $i === $current_page ) ? 'bg-iwa-saffron text-white' : 'border border-gray-200 text-iwa-ink-soft hover:border-iwa-saffron hover:text-iwa-saffron'; ?>"
           href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $i ) ); ?>">
            <?php echo esc_html( $i ); ?>
        </a>
        <?php endfor; ?>
        <?php if ( $current_page < $customer_orders->max_num_pages ) : ?>
        <a class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm text-iwa-ink-soft hover:border-iwa-saffron hover:text-iwa-saffron transition"
           href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">
            &rarr;
        </a>
        <?php endif; ?>
    </nav>
    <?php endif; ?>

</div>

<?php else : ?>

<div class="rounded-xl border border-gray-100 bg-gray-50 py-16 text-center">
    <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center mx-auto mb-4 shadow-sm">
        <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </div>
    <h3 class="font-heading font-bold text-base text-iwa-ink mb-2"><?php esc_html_e( 'No orders yet', 'woocommerce' ); ?></h3>
    <p class="text-sm text-iwa-ink-soft mb-6"><?php esc_html_e( 'Your past orders will appear here once you make a purchase.', 'woocommerce' ); ?></p>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
       class="inline-block rounded-full bg-iwa-saffron text-white font-semibold text-sm px-6 py-2.5 hover:bg-iwa-saffron-light transition">
        <?php esc_html_e( 'Start Shopping', 'woocommerce' ); ?>
    </a>
</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
