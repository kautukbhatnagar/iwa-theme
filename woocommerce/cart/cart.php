<?php
/**
 * IWA Cart page template
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<?php if ( WC()->cart->is_empty() ) : ?>

<div class="py-24 text-center">
    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
        <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z"/>
        </svg>
    </div>
    <h2 class="text-2xl font-bold text-iwa-ink mb-2">Your cart is empty</h2>
    <p class="text-iwa-ink-soft mb-8">Looks like you haven't added anything yet.</p>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
       class="rounded-full bg-iwa-saffron text-white px-8 py-3 font-semibold hover:bg-iwa-saffron-light transition inline-block">
        Browse the Shop
    </a>
</div>

<?php else : ?>

<div class="iwa-cart-wrap grid lg:grid-cols-3 gap-8 lg:gap-10 items-start">

    <!-- ── Cart Items ─────────────────────────────────────── -->
    <div class="lg:col-span-2">
        <h1 class="font-heading text-2xl font-bold text-iwa-ink mb-1">
            Your Cart
        </h1>
        <p class="text-sm text-iwa-ink-soft mb-6">
            <?php echo sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'woocommerce' ), WC()->cart->get_cart_contents_count() ); ?>
        </p>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="space-y-3" id="iwa-cart-items">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) continue;

                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    $thumb_src         = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
                    if ( ! $thumb_src ) $thumb_src = wc_placeholder_img_src( 'thumbnail' );
                    $item_class = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key );
                ?>
                <div class="<?php echo esc_attr( $item_class ); ?> flex gap-4 p-4 rounded-xl border border-gray-100 bg-white hover:border-iwa-saffron/30 hover:shadow-sm transition">

                    <!-- Thumbnail -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 rounded-xl overflow-hidden bg-gray-50">
                        <?php if ( $product_permalink ) : ?><a href="<?php echo esc_url( $product_permalink ); ?>" class="block w-full h-full"><?php endif; ?>
                        <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $_product->get_name() ); ?>" class="w-full h-full object-cover">
                        <?php if ( $product_permalink ) : ?></a><?php endif; ?>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0 flex flex-col">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="font-semibold text-iwa-ink text-sm leading-snug">
                                    <?php if ( $product_permalink ) : ?>
                                    <a href="<?php echo esc_url( $product_permalink ); ?>" class="hover:text-iwa-saffron transition">
                                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                                    </a>
                                    <?php else :
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                    endif; ?>
                                </h4>
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                                <p class="text-xs text-iwa-ink-soft mt-1">
                                    <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                                </p>
                            </div>
                            <!-- Remove -->
                            <?php echo apply_filters( 'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s" class="remove text-gray-300 hover:text-red-400 transition flex-shrink-0 mt-0.5" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_item_key="%s"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></a>',
                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                    esc_html__( 'Remove this item', 'woocommerce' ),
                                    esc_attr( $product_id ),
                                    esc_attr( $_product->get_sku() ),
                                    esc_attr( $cart_item_key )
                                ),
                            $cart_item_key ); ?>
                        </div>

                        <div class="flex items-center justify-between mt-auto pt-3">
                            <!-- Qty stepper -->
                            <?php if ( $_product->is_sold_individually() ) : ?>
                            <span class="text-xs text-iwa-ink-soft bg-gray-100 rounded-full px-3 py-1">Qty: 1</span>
                            <input type="hidden" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="1">
                            <?php else : ?>
                            <div class="iwa-qty-wrap flex items-center border border-gray-200 rounded-full overflow-hidden w-28">
                                <button type="button" class="iwa-qty-minus w-9 h-9 flex items-center justify-center text-iwa-ink-soft hover:text-iwa-ink hover:bg-gray-50 transition text-base font-medium select-none">−</button>
                                <input type="number"
                                       class="iwa-qty-input w-10 h-9 text-center text-sm font-semibold text-iwa-ink border-none outline-none bg-transparent"
                                       name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                       value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
                                       min="0"
                                       max="<?php echo esc_attr( ( $_product->get_max_purchase_quantity() < 0 ) ? '' : $_product->get_max_purchase_quantity() ); ?>"
                                       step="1"
                                       data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                                <button type="button" class="iwa-qty-plus w-9 h-9 flex items-center justify-center text-iwa-ink-soft hover:text-iwa-ink hover:bg-gray-50 transition text-base font-medium select-none">+</button>
                            </div>
                            <?php endif; ?>
                            <!-- Subtotal -->
                            <span class="font-bold text-iwa-ink text-sm">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php do_action( 'woocommerce_cart_contents' ); ?>

            <!-- Coupon + Update -->
            <div class="mt-5 flex flex-wrap gap-3">
                <?php if ( wc_coupons_enabled() ) : ?>
                <div class="flex gap-2 flex-1 min-w-[220px]">
                    <input type="text" name="coupon_code" id="coupon_code" value=""
                           class="flex-1 rounded-full border-2 border-gray-200 px-4 py-2 text-sm focus:border-iwa-saffron outline-none transition"
                           placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>">
                    <button type="submit" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
                            class="rounded-full bg-iwa-ink text-white px-5 py-2 text-sm font-semibold hover:bg-iwa-ink/80 transition whitespace-nowrap">
                        Apply
                    </button>
                </div>
                <?php endif; ?>
                <button type="submit" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"
                        class="rounded-full border-2 border-gray-200 text-iwa-ink-soft px-5 py-2 text-sm font-medium hover:border-iwa-ink hover:text-iwa-ink transition"
                        <?php do_action( 'woocommerce_cart_update_button_tag' ); ?>>
                    Update Cart
                </button>
            </div>

            <?php do_action( 'woocommerce_cart_coupon' ); ?>
            <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
        </form>

        <?php do_action( 'woocommerce_after_cart_table' ); ?>

        <p class="mt-6">
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
               class="inline-flex items-center gap-2 text-sm font-medium text-iwa-ink-soft hover:text-iwa-saffron transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Continue Shopping
            </a>
        </p>
    </div>

    <!-- ── Order Summary ──────────────────────────────────── -->
    <div class="lg:col-span-1">
        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-6 sticky top-24">
            <h3 class="font-heading font-bold text-lg text-iwa-ink mb-5 pb-4 border-b border-gray-200">Order Summary</h3>
            <?php woocommerce_cart_totals(); ?>
        </div>
    </div>
</div>

<script>
(function(){
    // Qty stepper
    document.querySelectorAll('.iwa-qty-wrap').forEach(function(w){
        var inp = w.querySelector('.iwa-qty-input');
        var max = parseInt(inp.getAttribute('max'), 10);
        w.querySelector('.iwa-qty-minus').addEventListener('click', function(){
            var v = parseInt(inp.value,10)-1; inp.value = Math.max(v, 0);
            inp.dispatchEvent(new Event('change',{bubbles:true}));
        });
        w.querySelector('.iwa-qty-plus').addEventListener('click', function(){
            var v = parseInt(inp.value,10)+1; inp.value = (max>0) ? Math.min(v,max) : v;
            inp.dispatchEvent(new Event('change',{bubbles:true}));
        });
    });
    // Auto-submit on qty change
    document.querySelectorAll('.iwa-qty-input').forEach(function(inp){
        inp.addEventListener('change', function(){
            var btn = document.querySelector('[name="update_cart"]');
            if(btn){ btn.removeAttribute('disabled'); btn.click(); }
        });
    });
})();
</script>

<?php do_action( 'woocommerce_after_cart' ); ?>
<?php endif; ?>
