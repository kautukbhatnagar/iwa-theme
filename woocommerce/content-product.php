<?php
/**
 * IWA product card — overrides WooCommerce content-product.php
 */
defined( 'ABSPATH' ) || exit;

global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;

$product_id  = $product->get_id();
$product_url = get_permalink( $product_id );

// ── Collect all images (featured + gallery) ─────────────────
$fallback_pool = [
    'https://images.unsplash.com/photo-1586495777744-4e6232bf4667?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1531251445707-1f000e1e87d0?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400&h=400&fit=crop',
    'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?w=400&h=400&fit=crop',
];

$images = [];
$main   = get_the_post_thumbnail_url( $product_id, 'medium_large' );
if ( $main ) $images[] = $main;

foreach ( $product->get_gallery_image_ids() as $gid ) {
    $url = wp_get_attachment_image_url( $gid, 'medium_large' );
    if ( $url ) $images[] = $url;
}

if ( empty( $images ) ) {
    $images[] = $fallback_pool[ $product_id % count( $fallback_pool ) ];
}

$has_slider   = count( $images ) > 1;
$slider_id    = 'iwa-slider-' . $product_id;

// ── Price calculation ────────────────────────────────────────
$on_sale       = $product->is_on_sale();
$regular_price = (float) $product->get_regular_price();
$sale_price    = (float) $product->get_sale_price();
$discount_pct  = ( $on_sale && $regular_price > 0 )
    ? round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 )
    : 0;

$currency = get_woocommerce_currency_symbol();
?>
<li <?php wc_product_class( 'iwa-product-card group rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-lg transition flex flex-col relative', $product ); ?>>

    <!-- ── Image area ─────────────────────────────────────── -->
    <div class="relative overflow-hidden aspect-square flex-shrink-0 bg-gray-50">

        <?php if ( $has_slider ) : ?>
        <!-- Slider -->
        <div id="<?php echo esc_attr( $slider_id ); ?>" class="iwa-card-slider w-full h-full relative">
            <?php foreach ( $images as $idx => $img_url ) : ?>
            <div class="iwa-slide absolute inset-0 transition-opacity duration-500 <?php echo $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>">
                <a href="<?php echo esc_url( $product_url ); ?>" class="block w-full h-full">
                    <img src="<?php echo esc_url( $img_url ); ?>"
                         alt="<?php echo esc_attr( $product->get_name() ); ?>"
                         class="w-full h-full object-cover" loading="lazy">
                </a>
            </div>
            <?php endforeach; ?>

            <!-- Dot indicators -->
            <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1 z-20">
                <?php foreach ( $images as $idx => $_ ) : ?>
                <button type="button"
                        class="iwa-dot w-1.5 h-1.5 rounded-full transition-all <?php echo $idx === 0 ? 'bg-white scale-125' : 'bg-white/50'; ?>"
                        data-index="<?php echo $idx; ?>"
                        aria-label="Slide <?php echo $idx + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        (function(){
            var el    = document.getElementById('<?php echo esc_js( $slider_id ); ?>');
            if (!el) return;
            var slides = el.querySelectorAll('.iwa-slide');
            var dots   = el.querySelectorAll('.iwa-dot');
            var cur    = 0;
            function goTo(n){
                slides[cur].classList.replace('opacity-100','opacity-0');
                slides[cur].classList.replace('z-10','z-0');
                dots[cur].classList.remove('bg-white','scale-125');
                dots[cur].classList.add('bg-white/50');
                cur = n % slides.length;
                slides[cur].classList.replace('opacity-0','opacity-100');
                slides[cur].classList.replace('z-0','z-10');
                dots[cur].classList.remove('bg-white/50');
                dots[cur].classList.add('bg-white','scale-125');
            }
            dots.forEach(function(d){ d.addEventListener('click',function(){ goTo(+d.dataset.index); }); });
            var timer = setInterval(function(){ goTo(cur+1); }, 3000);
            el.addEventListener('mouseenter', function(){ clearInterval(timer); });
            el.addEventListener('mouseleave', function(){ timer = setInterval(function(){ goTo(cur+1); }, 3000); });
        })();
        </script>

        <?php else : ?>
        <!-- Single image -->
        <a href="<?php echo esc_url( $product_url ); ?>" class="block w-full h-full">
            <img src="<?php echo esc_url( $images[0] ); ?>"
                 alt="<?php echo esc_attr( $product->get_name() ); ?>"
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
        </a>
        <?php endif; ?>

        <!-- ── Sale ribbon badge ─────────────────────────── -->
        <?php if ( $on_sale && $discount_pct > 0 ) : ?>
        <div class="absolute top-0 right-0 z-20 overflow-hidden w-16 h-16 pointer-events-none">
            <div class="absolute top-3 -right-5 rotate-45 bg-iwa-green text-white text-[9px] font-bold tracking-wide text-center w-20 py-0.5 shadow-sm">
                <?php echo $discount_pct; ?>% OFF
            </div>
        </div>
        <?php elseif ( $product->is_featured() ) : ?>
        <div class="absolute top-0 right-0 z-20 overflow-hidden w-16 h-16 pointer-events-none">
            <div class="absolute top-3 -right-5 rotate-45 bg-iwa-blue text-white text-[9px] font-bold tracking-wide text-center w-20 py-0.5 shadow-sm">
                NEW
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Wishlist button ───────────────────────────── -->
        <button type="button"
                class="iwa-wishlist-btn absolute bottom-2 right-2 z-20 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center text-gray-400 hover:text-red-500 transition"
                data-product-id="<?php echo absint( $product_id ); ?>"
                aria-label="Add to wishlist">
            <svg class="w-4 h-4 iwa-heart-outline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
            </svg>
            <svg class="w-4 h-4 iwa-heart-filled hidden text-red-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001z"/>
            </svg>
        </button>
    </div>

    <!-- ── Product info ──────────────────────────────────── -->
    <div class="p-3 flex flex-col flex-1">
        <h3 class="font-semibold text-iwa-ink text-sm leading-snug group-hover:text-iwa-saffron transition line-clamp-2 flex-1 mb-2">
            <a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
        </h3>

        <!-- Price row -->
        <div class="flex items-baseline gap-2 flex-wrap mb-3">
            <?php if ( $on_sale ) : ?>
            <span class="text-sm font-bold text-iwa-ink"><?php echo esc_html( $currency . number_format( $sale_price, 2 ) ); ?></span>
            <span class="text-xs text-gray-400 line-through"><?php echo esc_html( $currency . number_format( $regular_price, 2 ) ); ?></span>
            <?php if ( $discount_pct > 0 ) : ?>
            <span class="text-[10px] font-bold text-iwa-green">(<?php echo $discount_pct; ?>% OFF)</span>
            <?php endif; ?>
            <?php else : ?>
            <span class="text-sm font-bold text-iwa-ink"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
            <?php endif; ?>
        </div>

        <!-- Add to Cart -->
        <div class="iwa-atc-wrap">
            <?php woocommerce_template_loop_add_to_cart( [ 'product' => $product ] ); ?>
        </div>
    </div>
</li>
