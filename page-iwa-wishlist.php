<?php
/**
 * Template Name: IWA Wishlist
 * Template Post Type: page
 */

// Require login
if ( ! is_user_logged_in() ) {
    $login_url = add_query_arg( 'redirect_to', urlencode( get_permalink() ), wc_get_page_permalink( 'myaccount' ) );
    wp_redirect( $login_url );
    exit;
}

get_header();
?>

<main class="py-10 lg:py-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-sm font-semibold text-iwa-saffron uppercase tracking-wider mb-1">My Wishlist</p>
                <h1 class="font-heading text-3xl font-bold text-iwa-ink">Saved Items</h1>
                <p class="text-sm text-iwa-ink-soft mt-1" id="iwa-wl-count">&nbsp;</p>
            </div>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
               class="text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition whitespace-nowrap flex-shrink-0">
                Continue Shopping &rarr;
            </a>
        </div>

        <!-- Loading -->
        <div id="iwa-wl-loading" class="py-20 text-center text-iwa-ink-soft text-sm">
            <svg class="animate-spin w-6 h-6 mx-auto mb-3 text-iwa-saffron" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Loading your wishlist…
        </div>

        <!-- Empty -->
        <div id="iwa-wl-empty" class="hidden py-24 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
                <svg class="w-9 h-9 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-iwa-ink mb-2">Your wishlist is empty</h2>
            <p class="text-iwa-ink-soft mb-8 text-sm">Browse the shop and tap the heart icon to save items you love.</p>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
               class="rounded-full bg-iwa-saffron text-white px-8 py-3 font-semibold hover:bg-iwa-saffron-light transition inline-block">
                Browse the Shop
            </a>
        </div>

        <!-- Products grid — populated by JS -->
        <div id="iwa-wl-products" class="hidden">
            <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-6 list-none p-0 m-0" id="iwa-wl-grid"></ul>
        </div>

    </div>
</main>

<script>
(function(){
    var WL_KEY   = 'iwa_wishlist';
    var loading  = document.getElementById('iwa-wl-loading');
    var empty    = document.getElementById('iwa-wl-empty');
    var products = document.getElementById('iwa-wl-products');
    var countEl  = document.getElementById('iwa-wl-count');

    function getIds(){ try{ return JSON.parse(localStorage.getItem(WL_KEY)||'[]'); }catch(e){ return []; } }

    var ids = getIds();

    if (!ids.length) {
        loading.classList.add('hidden');
        empty.classList.remove('hidden');
        countEl.textContent = '0 items saved';
        return;
    }

    var fd = new FormData();
    fd.append('action', 'iwa_get_wishlist');
    fd.append('ids', ids.join(','));
    fd.append('nonce', '<?php echo esc_js( wp_create_nonce( 'iwa_wishlist' ) ); ?>');

    fetch('<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>', { method:'POST', body:fd })
    .then(function(r){ return r.json(); })
    .then(function(data){
        loading.classList.add('hidden');
        if (data.success && data.data.html) {
            document.getElementById('iwa-wl-grid').innerHTML = data.data.html;
            products.classList.remove('hidden');
            var n = data.data.count || 0;
            countEl.textContent = n + ' item' + (n !== 1 ? 's' : '') + ' saved';
            // Re-init wishlist btn state
            if (typeof window.iwaInitWishlist === 'function') window.iwaInitWishlist();
        } else {
            empty.classList.remove('hidden');
            countEl.textContent = '0 items saved';
        }
    })
    .catch(function(){
        loading.classList.add('hidden');
        empty.classList.remove('hidden');
    });
})();
</script>

<?php get_footer(); ?>
