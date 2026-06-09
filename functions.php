<?php
/**
 * IWA Theme — functions.php
 */

// ── Enqueue styles ─────────────────────────────────────────
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'iwa-theme',
        get_template_directory_uri() . '/assets/css/iwa-theme.css',
        [],
        filemtime( get_template_directory() . '/assets/css/iwa-theme.css' )
    );
} );

// ── Theme Setup ────────────────────────────────────────────
add_action( 'after_setup_theme', 'iwa_theme_setup' );
function iwa_theme_setup() {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_filter( 'excerpt_length', function () { return 28; } );
    add_filter( 'excerpt_more',   function () { return '&hellip;'; } );

    // Nav menus
    register_nav_menus( [
        'primary' => 'Primary Navigation',
    ] );

    // ── WooCommerce support ──────────────────────────────────
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

// ── Nav Walker with dropdown support ───────────────────────
class IWA_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '<ul class="iwa-dropdown">';
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $output .= '</ul>';
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item    = $data_object;
        $classes = empty( $item->classes ) ? [] : (array) $item->classes;
        $has_sub = in_array( 'menu-item-has-children', $classes );
        $active  = in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-ancestor', $classes );

        $li_class = 'iwa-nav-item' . ( $has_sub ? ' iwa-has-dropdown' : '' );
        $output  .= '<li class="' . esc_attr( $li_class ) . '">';

        $atts           = [];
        $atts['href']   = ! empty( $item->url ) ? $item->url : '#';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['class']  = 'iwa-nav-link-item' . ( $active ? ' active' : '' );

        $attr_str = '';
        foreach ( $atts as $attr => $val ) {
            if ( ! empty( $val ) ) $attr_str .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
        }

        $title   = apply_filters( 'the_title', $item->title, $item->ID );
        $chevron = $has_sub ? '<svg class="iwa-chevron" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>' : '';

        $output .= '<a' . $attr_str . '>' . esc_html( $title ) . $chevron . '</a>';
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}

// ── WooCommerce: remove default breadcrumb + sidebar wrappers ─
add_action( 'init', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );
    remove_action( 'woocommerce_sidebar',             'woocommerce_get_sidebar', 10 );
} );

// (All WooCommerce styles are now in assets/css/iwa-theme.css)

// ── WooCommerce: wishlist AJAX endpoint ──────────────────────
add_action( 'wp_ajax_iwa_get_wishlist',        'iwa_get_wishlist_ajax' );
add_action( 'wp_ajax_nopriv_iwa_get_wishlist', 'iwa_get_wishlist_ajax' );

function iwa_get_wishlist_ajax() {
    if ( ! check_ajax_referer( 'iwa_wishlist', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
    }
    $raw = sanitize_text_field( wp_unslash( $_POST['ids'] ?? '' ) );
    $ids = array_values( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );

    if ( empty( $ids ) ) {
        wp_send_json_success( [ 'html' => '', 'count' => 0 ] );
    }

    $query = new WP_Query( [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'post__in'       => $ids,
        'posts_per_page' => -1,
        'orderby'        => 'post__in',
    ] );

    ob_start();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
    }
    wp_reset_postdata();
    $html = ob_get_clean();

    wp_send_json_success( [ 'html' => $html, 'count' => $query->post_count ] );
}

// ── Account nav icon helper ───────────────────────────────────
function iwa_account_nav_icon( $endpoint ) {
    $icons = [
        'dashboard'       => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>',
        'orders'          => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.015H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.015H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.015H3.75v-.015zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>',
        'downloads'       => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>',
        'edit-address'    => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>',
        'edit-account'    => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>',
        'customer-logout' => '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>',
    ];
    return $icons[ $endpoint ] ?? '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>';
}

// ── WooCommerce: wishlist JS (localStorage) ───────────────────
add_action( 'wp_footer', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    ?>
    <script>
    (function(){
        var WL_KEY = 'iwa_wishlist';
        function getWL(){ try{ return JSON.parse(localStorage.getItem(WL_KEY)||'[]'); }catch(e){ return []; } }
        function saveWL(a){ localStorage.setItem(WL_KEY, JSON.stringify(a)); }
        function isIn(id){ return getWL().indexOf(String(id)) > -1; }
        function toggle(id){
            var wl = getWL(), sid = String(id), idx = wl.indexOf(sid);
            if(idx > -1){ wl.splice(idx,1); } else { wl.push(sid); }
            saveWL(wl); return idx === -1;
        }
        function syncBtn(btn){
            var id = btn.dataset.productId, on = isIn(id);
            var outline = btn.querySelector('.iwa-heart-outline');
            var filled  = btn.querySelector('.iwa-heart-filled');
            if(outline) outline.classList.toggle('hidden', on);
            if(filled)  filled.classList.toggle('hidden', !on);
            btn.classList.toggle('text-red-500', on);
        }
        function init(){
            document.querySelectorAll('.iwa-wishlist-btn').forEach(function(btn){
                syncBtn(btn);
                if(btn._iwaWLBound) return;
                btn._iwaWLBound = true;
                btn.addEventListener('click', function(e){
                    e.preventDefault(); e.stopPropagation();
                    toggle(btn.dataset.productId);
                    syncBtn(btn);
                    updateHeaderCount();
                });
            });
        }
        function updateHeaderCount(){
            var c = getWL().length;
            var badge = document.getElementById('iwa-wl-header-count');
            if(!badge) return;
            if(c > 0){ badge.textContent = c; badge.classList.remove('hidden'); }
            else { badge.classList.add('hidden'); }
        }
        window.iwaInitWishlist = init;
        if(document.readyState === 'loading'){ document.addEventListener('DOMContentLoaded', function(){ init(); updateHeaderCount(); }); }
        else { init(); updateHeaderCount(); }
        document.addEventListener('wc_fragments_refreshed', init);
    })();
    </script>
    <?php
} );

// ── WooCommerce: cart count via AJAX fragments ────────────────
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    ob_start();
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <span class="iwa-cart-count" <?php echo $count ? '' : 'style="display:none"'; ?>>
        <?php echo absint( $count ); ?>
    </span>
    <?php
    $fragments['.iwa-cart-count'] = ob_get_clean();
    return $fragments;
} );

// ── Single product: button label + suppress default after-summary hooks ──
add_filter( 'woocommerce_product_single_add_to_cart_text', function () { return 'Add to Cart'; } );

// Remove default tabs / upsells / related from woocommerce_after_single_product_summary
// (content-single-product.php renders related products inline instead)
add_action( 'init', function () {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
} );

// ── Helper: Stories archive URL ────────────────────────────
function iwa_stories_url() {
    $cat = get_category_by_slug( 'stories-indian-women-abroad' );
    return $cat
        ? get_category_link( $cat->term_id )
        : home_url( '/category/stories-indian-women-abroad/' );
}

// ── Helper: Thumbnail URL with Unsplash fallback ───────────
function iwa_thumbnail( $post_id, $size = 'large' ) {
    $url = get_the_post_thumbnail_url( $post_id, $size );
    if ( $url ) return $url;
    // Deterministic placeholder so the same post always gets the same image
    $pool = [
        'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&h=450&fit=crop',
        'https://images.unsplash.com/photo-1605649487212-47bdab064df7?w=800&h=450&fit=crop',
        'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=450&fit=crop',
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=450&fit=crop',
        'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800&h=450&fit=crop',
        'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&h=450&fit=crop',
    ];
    return $pool[ absint( $post_id ) % count( $pool ) ];
}

// ── Helper: Format date in "13 Dec" style ──────────────────
function iwa_format_date( $post_id ) {
    return get_the_date( 'j M', $post_id );
}

// ── Helper: Get all podcast episodes ───────────────────────
function iwa_get_episodes() {
    $hardcoded = [
        [ 'id' => 'npdoMGcOh7c', 'url' => 'https://www.youtube.com/watch?v=npdoMGcOh7c', 'title' => 'Episode 1',  'topic' => 'stories',  'spotify' => '', 'apple' => '' ],
        [ 'id' => 'm_5Zn51bmWQ', 'url' => 'https://www.youtube.com/watch?v=m_5Zn51bmWQ', 'title' => 'Episode 2',  'topic' => 'culture',  'spotify' => '', 'apple' => '' ],
        [ 'id' => '35pPgvaaanY', 'url' => 'https://www.youtube.com/watch?v=35pPgvaaanY', 'title' => 'Episode 3',  'topic' => 'career',   'spotify' => '', 'apple' => '' ],
        [ 'id' => 'iMSnclkjofA', 'url' => 'https://www.youtube.com/watch?v=iMSnclkjofA', 'title' => 'Episode 4',  'topic' => 'travel',   'spotify' => '', 'apple' => '' ],
        [ 'id' => 'r7RtsxloRhM', 'url' => 'https://www.youtube.com/watch?v=r7RtsxloRhM', 'title' => 'Episode 5',  'topic' => 'wellness', 'spotify' => '', 'apple' => '' ],
        [ 'id' => 'CTAU_gV8u6s', 'url' => 'https://www.youtube.com/watch?v=CTAU_gV8u6s', 'title' => 'Episode 6',  'topic' => 'finance',  'spotify' => '', 'apple' => '' ],
        [ 'id' => 'NN4BdwCRCJg', 'url' => 'https://www.youtube.com/watch?v=NN4BdwCRCJg', 'title' => 'Episode 7',  'topic' => 'stories',  'spotify' => '', 'apple' => '' ],
        [ 'id' => 'sHSYWLxuxZQ', 'url' => 'https://www.youtube.com/watch?v=sHSYWLxuxZQ', 'title' => 'Episode 8',  'topic' => 'culture',  'spotify' => '', 'apple' => '' ],
        [ 'id' => '0BlH9YC2TJM', 'url' => 'https://www.youtube.com/watch?v=0BlH9YC2TJM', 'title' => 'Episode 9',  'topic' => 'career',   'spotify' => '', 'apple' => '' ],
        [ 'id' => 'Dq_vZD7dP8o', 'url' => 'https://www.youtube.com/watch?v=Dq_vZD7dP8o', 'title' => 'Episode 10', 'topic' => 'travel',   'spotify' => '', 'apple' => '' ],
    ];

    if ( post_type_exists( 'iwa_episode' ) ) {
        $posts = get_posts( [ 'post_type' => 'iwa_episode', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC' ] );
        if ( ! empty( $posts ) ) {
            return array_map( function( $p ) {
                $yt = get_post_meta( $p->ID, 'iwa_youtube_id', true );
                return [
                    'id'      => $yt,
                    'url'     => 'https://www.youtube.com/watch?v=' . $yt,
                    'title'   => $p->post_title,
                    'topic'   => get_post_meta( $p->ID, 'iwa_topic',       true ) ?: 'stories',
                    'spotify' => get_post_meta( $p->ID, 'iwa_spotify_url', true ) ?: '',
                    'apple'   => get_post_meta( $p->ID, 'iwa_apple_url',   true ) ?: '',
                ];
            }, $posts );
        }
    }

    return $hardcoded;
}

// ── Shortcode: [iwa_blog_list] ─────────────────────────────
// Attributes:
//   layout           — "grid" (default) | "list" | "featured"
//   limit            — posts per page (default: 6)
//   category         — category slug to filter by (default: all)
//   show_excerpt     — "yes" (default) | "no"
//   show_pagination  — "yes" (default) | "no"
add_shortcode( 'iwa_blog_list', function( $atts ) {
    $atts = shortcode_atts( [
        'layout'          => 'grid',
        'limit'           => 6,
        'category'        => '',
        'show_excerpt'    => 'yes',
        'show_pagination' => 'yes',
    ], $atts, 'iwa_blog_list' );

    $layout          = sanitize_key( $atts['layout'] );
    $limit           = max( 1, intval( $atts['limit'] ) );
    $cat_slug        = sanitize_key( $atts['category'] );
    $show_excerpt    = ( strtolower( $atts['show_excerpt'] ) !== 'no' );
    $show_pagination = ( strtolower( $atts['show_pagination'] ) !== 'no' );

    // Static pages use 'page', archive/blog pages use 'paged'
    $paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

    $query_args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $paged,
    ];
    if ( $cat_slug ) {
        $cat = get_category_by_slug( $cat_slug );
        if ( $cat ) $query_args['cat'] = $cat->term_id;
    }

    $query = new WP_Query( $query_args );
    $posts = $query->posts;

    if ( empty( $posts ) ) return '<p class="text-iwa-ink-soft text-sm">No posts found.</p>';

    ob_start();

    // ── LAYOUT: grid ──────────────────────────────────────────
    if ( $layout === 'grid' ) : ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ( $posts as $p ) :
            $url    = get_permalink( $p->ID );
            $img    = iwa_thumbnail( $p->ID );
            $date   = iwa_format_date( $p->ID );
            $author = get_the_author_meta( 'display_name', $p->post_author );
            $cats   = get_the_category( $p->ID );
            $excerpt = has_excerpt( $p->ID )
                ? get_the_excerpt( $p->ID )
                : wp_trim_words( $p->post_content, 20, '&hellip;' );
        ?>
        <article class="rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-md hover:border-iwa-blue/20 transition group flex flex-col">
            <a href="<?php echo esc_url( $url ); ?>" class="block aspect-[4/3] overflow-hidden flex-shrink-0">
                <img src="<?php echo esc_url( $img ); ?>" alt=""
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
            </a>
            <div class="p-5 flex flex-col flex-1">
                <?php if ( ! empty( $cats ) ) : ?>
                <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
                   class="text-[11px] font-semibold uppercase tracking-wider text-iwa-saffron mb-2 hover:text-iwa-saffron-light transition">
                    <?php echo esc_html( $cats[0]->name ); ?>
                </a>
                <?php endif; ?>
                <h3 class="font-bold text-iwa-ink leading-snug group-hover:text-iwa-green transition line-clamp-2">
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $p->post_title ); ?></a>
                </h3>
                <?php if ( $show_excerpt ) : ?>
                <p class="mt-2 text-sm text-iwa-ink-soft leading-relaxed line-clamp-3 flex-1"><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>
                <div class="mt-4 flex items-center justify-between pt-4 border-t border-gray-100">
                    <p class="text-xs text-iwa-ink-soft"><?php echo esc_html( $date ); ?><?php if ( $author ) echo ' &middot; ' . esc_html( $author ); ?></p>
                    <a href="<?php echo esc_url( $url ); ?>"
                       class="text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">Read &rarr;</a>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <?php
    // ── LAYOUT: list ──────────────────────────────────────────
    elseif ( $layout === 'list' ) : ?>
    <div class="space-y-6">
        <?php foreach ( $posts as $p ) :
            $url    = get_permalink( $p->ID );
            $img    = iwa_thumbnail( $p->ID );
            $date   = iwa_format_date( $p->ID );
            $author = get_the_author_meta( 'display_name', $p->post_author );
            $cats   = get_the_category( $p->ID );
            $excerpt = has_excerpt( $p->ID )
                ? get_the_excerpt( $p->ID )
                : wp_trim_words( $p->post_content, 25, '&hellip;' );
        ?>
        <article class="flex gap-5 sm:gap-6 items-start rounded-xl bg-white border border-gray-100 hover:shadow-md hover:border-iwa-blue/20 transition group overflow-hidden">
            <a href="<?php echo esc_url( $url ); ?>"
               class="flex-shrink-0 w-32 sm:w-44 aspect-[4/3] overflow-hidden bg-gray-100">
                <img src="<?php echo esc_url( $img ); ?>" alt=""
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
            </a>
            <div class="py-4 pr-5 flex flex-col justify-center min-w-0">
                <?php if ( ! empty( $cats ) ) : ?>
                <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
                   class="text-[11px] font-semibold uppercase tracking-wider text-iwa-saffron mb-1 hover:text-iwa-saffron-light transition">
                    <?php echo esc_html( $cats[0]->name ); ?>
                </a>
                <?php endif; ?>
                <h3 class="font-bold text-iwa-ink leading-snug group-hover:text-iwa-green transition line-clamp-2">
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $p->post_title ); ?></a>
                </h3>
                <?php if ( $show_excerpt ) : ?>
                <p class="mt-1.5 text-sm text-iwa-ink-soft leading-relaxed line-clamp-2"><?php echo esc_html( $excerpt ); ?></p>
                <?php endif; ?>
                <p class="mt-3 text-xs text-iwa-ink-soft/70"><?php echo esc_html( $date ); ?><?php if ( $author ) echo ' &middot; ' . esc_html( $author ); ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <?php
    // ── LAYOUT: featured ─────────────────────────────────────
    // Page 1: first post is the big featured card, rest go into 2-col grid.
    // Page 2+: all posts go into the 2-col grid (no repeated hero).
    elseif ( $layout === 'featured' ) :
        $featured = ( $paged === 1 ) ? $posts[0] : null;
        $rest     = ( $paged === 1 ) ? array_slice( $posts, 1 ) : $posts;

        $fp_url     = $featured ? get_permalink( $featured->ID ) : '';
        $fp_img     = $featured ? iwa_thumbnail( $featured->ID ) : '';
        $fp_date    = $featured ? iwa_format_date( $featured->ID ) : '';
        $fp_author  = $featured ? get_the_author_meta( 'display_name', $featured->post_author ) : '';
        $fp_cats    = $featured ? get_the_category( $featured->ID ) : [];
        $fp_excerpt = $featured
            ? ( has_excerpt( $featured->ID ) ? get_the_excerpt( $featured->ID ) : wp_trim_words( $featured->post_content, 35, '&hellip;' ) )
            : '';
    ?>
    <div class="space-y-8">
        <!-- Featured post (page 1 only) -->
        <?php if ( $featured ) : ?>
        <article class="rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm group">
            <a href="<?php echo esc_url( $fp_url ); ?>" class="block aspect-video overflow-hidden">
                <img src="<?php echo esc_url( $fp_img ); ?>" alt=""
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
            </a>
            <div class="p-6 lg:p-8">
                <?php if ( ! empty( $fp_cats ) ) : ?>
                <a href="<?php echo esc_url( get_category_link( $fp_cats[0]->term_id ) ); ?>"
                   class="text-xs font-semibold uppercase tracking-wider text-iwa-saffron hover:text-iwa-saffron-light transition">
                    <?php echo esc_html( $fp_cats[0]->name ); ?>
                </a>
                <?php endif; ?>
                <h2 class="text-2xl lg:text-3xl font-bold text-iwa-ink leading-tight mt-2 group-hover:text-iwa-green transition">
                    <a href="<?php echo esc_url( $fp_url ); ?>"><?php echo esc_html( $featured->post_title ); ?></a>
                </h2>
                <?php if ( $show_excerpt ) : ?>
                <p class="mt-3 text-iwa-ink-soft leading-relaxed"><?php echo esc_html( $fp_excerpt ); ?></p>
                <?php endif; ?>
                <div class="mt-5 flex items-center justify-between">
                    <p class="text-xs text-iwa-ink-soft"><?php echo esc_html( $fp_date ); ?><?php if ( $fp_author ) echo ' &middot; ' . esc_html( $fp_author ); ?></p>
                    <a href="<?php echo esc_url( $fp_url ); ?>"
                       class="text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">Read More &rarr;</a>
                </div>
            </div>
        </article>
        <?php endif; // end $featured ?>

        <!-- Rest in 2-col grid -->
        <?php if ( ! empty( $rest ) ) : ?>
        <div class="grid sm:grid-cols-2 gap-6">
            <?php foreach ( $rest as $p ) :
                $url    = get_permalink( $p->ID );
                $img    = iwa_thumbnail( $p->ID );
                $date   = iwa_format_date( $p->ID );
                $author = get_the_author_meta( 'display_name', $p->post_author );
                $cats   = get_the_category( $p->ID );
                $excerpt = has_excerpt( $p->ID )
                    ? get_the_excerpt( $p->ID )
                    : wp_trim_words( $p->post_content, 18, '&hellip;' );
            ?>
            <article class="rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-md hover:border-iwa-blue/20 transition group flex flex-col">
                <a href="<?php echo esc_url( $url ); ?>" class="block aspect-[4/3] overflow-hidden flex-shrink-0">
                    <img src="<?php echo esc_url( $img ); ?>" alt=""
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <?php if ( ! empty( $cats ) ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
                       class="text-[11px] font-semibold uppercase tracking-wider text-iwa-saffron mb-1 hover:text-iwa-saffron-light transition">
                        <?php echo esc_html( $cats[0]->name ); ?>
                    </a>
                    <?php endif; ?>
                    <h3 class="font-bold text-iwa-ink leading-snug group-hover:text-iwa-saffron transition line-clamp-2 flex-1">
                        <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $p->post_title ); ?></a>
                    </h3>
                    <?php if ( $show_excerpt ) : ?>
                    <p class="mt-1.5 text-sm text-iwa-ink-soft line-clamp-2"><?php echo esc_html( $excerpt ); ?></p>
                    <?php endif; ?>
                    <div class="mt-3 flex items-center justify-between pt-3 border-t border-gray-100">
                        <p class="text-xs text-iwa-ink-soft"><?php echo esc_html( $date ); ?></p>
                        <a href="<?php echo esc_url( $url ); ?>"
                           class="text-sm font-semibold text-iwa-green hover:text-iwa-green-light transition">Read &rarr;</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php
    // ── Pagination ────────────────────────────────────────────
    if ( $show_pagination && $query->max_num_pages > 1 ) :
        $links = paginate_links( [
            'total'     => $query->max_num_pages,
            'current'   => $paged,
            'type'      => 'array',
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'mid_size'  => 2,
        ] );
        if ( $links ) : ?>
    <nav class="iwa-blog-pagination mt-10 flex flex-wrap justify-center items-center gap-2" aria-label="Blog pages">
        <?php foreach ( $links as $link ) :
            // Wrap plain numbers and arrows in styled spans/anchors
            $is_current = strpos( $link, 'current' ) !== false;
            $is_dots    = strpos( $link, 'dots' ) !== false;
            if ( $is_current ) : ?>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-iwa-saffron text-white text-sm font-semibold">
                <?php echo wp_kses( $link, [ 'span' => [ 'class' => [], 'aria-current' => [] ] ] ); ?>
            </span>
            <?php elseif ( $is_dots ) : ?>
            <span class="inline-flex items-center justify-center w-10 h-10 text-iwa-ink-soft text-sm">&hellip;</span>
            <?php else : ?>
            <span class="iwa-page-link inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 text-iwa-ink-soft text-sm font-medium hover:border-iwa-saffron hover:text-iwa-saffron transition [&_a]:flex [&_a]:items-center [&_a]:justify-center [&_a]:w-full [&_a]:h-full [&_a]:rounded-full">
                <?php echo wp_kses( $link, [ 'a' => [ 'href' => [], 'class' => [], 'aria-label' => [] ] ] ); ?>
            </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
        <?php endif;
    endif;

    wp_reset_postdata();
    return ob_get_clean();
} );

// ── Shortcode: [iwa_podcast_list] ──────────────────────────
// Attributes:
//   limit        — number of episodes to show (default: all)
//   topic        — pre-filter to a topic, e.g. topic="career"
//   show_filters — show topic filter buttons: "yes" (default) or "no"
add_shortcode( 'iwa_podcast_list', function( $atts ) {
    $atts = shortcode_atts( [
        'limit'        => -1,
        'topic'        => 'all',
        'show_filters' => 'yes',
    ], $atts, 'iwa_podcast_list' );

    $limit        = intval( $atts['limit'] );
    $active_topic = sanitize_key( $atts['topic'] );
    $show_filters = ( strtolower( $atts['show_filters'] ) !== 'no' );

    $episodes = iwa_get_episodes();
    if ( $limit > 0 ) {
        $episodes = array_slice( $episodes, 0, $limit );
    }

    // Collect all unique topics for filter buttons
    $topics = [];
    foreach ( $episodes as $ep ) {
        if ( ! empty( $ep['topic'] ) && ! in_array( $ep['topic'], $topics, true ) ) {
            $topics[] = $ep['topic'];
        }
    }

    // Unique ID so multiple shortcodes on one page don't clash
    static $instance = 0;
    $instance++;
    $uid = 'iwa-pl-' . $instance;

    ob_start();
    ?>
    <div id="<?php echo esc_attr( $uid ); ?>" class="iwa-podcast-list">

        <?php if ( $show_filters && count( $topics ) > 1 ) : ?>
        <div class="iwa-pl-filters flex flex-wrap gap-2 mb-8" role="tablist">
            <button type="button" role="tab"
                    data-filter="all"
                    aria-selected="<?php echo $active_topic === 'all' ? 'true' : 'false'; ?>"
                    class="iwa-pl-filter rounded-full px-4 py-2 text-sm font-medium transition
                           <?php echo $active_topic === 'all' ? 'bg-iwa-saffron text-white' : 'border border-gray-200 text-iwa-ink-soft hover:border-iwa-blue hover:text-iwa-blue'; ?>">
                All
            </button>
            <?php foreach ( $topics as $t ) : ?>
            <button type="button" role="tab"
                    data-filter="<?php echo esc_attr( $t ); ?>"
                    aria-selected="<?php echo $active_topic === $t ? 'true' : 'false'; ?>"
                    class="iwa-pl-filter rounded-full px-4 py-2 text-sm font-medium transition
                           <?php echo $active_topic === $t ? 'bg-iwa-saffron text-white' : 'border border-gray-200 text-iwa-ink-soft hover:border-iwa-blue hover:text-iwa-blue'; ?>">
                <?php echo esc_html( ucfirst( $t ) ); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="iwa-pl-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ( $episodes as $episode ) :
                if ( empty( $episode['id'] ) ) continue;
                $hidden = ( $active_topic !== 'all' && $episode['topic'] !== $active_topic ) ? ' style="display:none"' : '';
            ?>
            <article class="iwa-pl-card rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-lg hover:border-iwa-green/30 transition"
                     data-topic="<?php echo esc_attr( $episode['topic'] ); ?>"<?php echo $hidden; ?>>
                <a href="<?php echo esc_url( $episode['url'] ); ?>" target="_blank" rel="noopener" class="block relative aspect-video group">
                    <img src="https://img.youtube.com/vi/<?php echo esc_attr( $episode['id'] ); ?>/hqdefault.jpg"
                         alt="<?php echo esc_attr( $episode['title'] ); ?>"
                         class="w-full h-full object-cover" loading="lazy"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
                    <div class="absolute inset-0 bg-black/20 hover:bg-black/30 flex items-center justify-center transition">
                        <div class="w-14 h-14 rounded-full bg-iwa-saffron flex items-center justify-center text-white shadow-lg">
                            <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                    </div>
                </a>
                <div class="p-4">
                    <h3 class="font-bold text-iwa-ink text-sm leading-snug">
                        <a href="<?php echo esc_url( $episode['url'] ); ?>" target="_blank" rel="noopener">
                            <?php echo esc_html( $episode['title'] ); ?>
                        </a>
                    </h3>
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-xs text-iwa-ink-soft">Indian Women Abroad</p>
                        <span class="text-xs text-iwa-ink-soft/70 bg-gray-100 rounded-full px-2 py-0.5">
                            <?php echo esc_html( ucfirst( $episode['topic'] ) ); ?>
                        </span>
                    </div>
                    <?php if ( ! empty( $episode['spotify'] ) || ! empty( $episode['apple'] ) ) : ?>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                        <span class="text-[10px] text-iwa-ink-soft/60 mr-0.5">Also on</span>
                        <?php if ( ! empty( $episode['spotify'] ) ) : ?>
                        <a href="<?php echo esc_url( $episode['spotify'] ); ?>" target="_blank" rel="noopener"
                           title="Listen on Spotify"
                           class="flex items-center gap-1 rounded-full bg-[#1DB954]/10 text-[#1DB954] hover:bg-[#1DB954] hover:text-white px-2.5 py-1 text-[10px] font-semibold transition">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                            Spotify
                        </a>
                        <?php endif; ?>
                        <?php if ( ! empty( $episode['apple'] ) ) : ?>
                        <a href="<?php echo esc_url( $episode['apple'] ); ?>" target="_blank" rel="noopener"
                           title="Listen on Apple Podcasts"
                           class="flex items-center gap-1 rounded-full bg-[#872EC4]/10 text-[#872EC4] hover:bg-[#872EC4] hover:text-white px-2.5 py-1 text-[10px] font-semibold transition">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 4.102a7.898 7.898 0 0 1 2.786 15.273c.07-.537.14-1.38.014-1.99l-1.037-4.383c.266-.532.399-1.134.399-1.753 0-1.686-.984-2.945-2.406-2.945-1.135 0-1.686.853-1.686 1.876 0 1.142.732 2.852.732 4.31 0 1.225-.657 2.208-2.026 2.208-1.464 0-2.548-1.546-2.548-3.78 0-1.974 1.435-3.36 3.487-3.36 2.618 0 4.152 1.96 4.152 4.315 0 2.617-1.646 4.712-3.94 4.712-.77 0-1.492-.4-1.74-.875l-.47 1.75c-.172.66-.634 1.484-.945 1.988A7.9 7.9 0 0 1 12 19.898a7.898 7.898 0 0 1 0-15.796z"/></svg>
                            Apple
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
    (function () {
        var wrap    = document.getElementById(<?php echo wp_json_encode( $uid ); ?>);
        if (!wrap) return;
        var filters = wrap.querySelectorAll('.iwa-pl-filter');
        var cards   = wrap.querySelectorAll('.iwa-pl-card');
        if (!filters.length) return;
        filters.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var v = this.getAttribute('data-filter');
                cards.forEach(function (c) {
                    c.style.display = (v === 'all' || c.getAttribute('data-topic') === v) ? '' : 'none';
                });
                filters.forEach(function (b) {
                    var active = b.getAttribute('data-filter') === v;
                    b.setAttribute('aria-selected', active);
                    if (active) {
                        b.classList.add('bg-iwa-saffron', 'text-white');
                        b.classList.remove('border', 'border-gray-200', 'text-iwa-ink-soft');
                    } else {
                        b.classList.remove('bg-iwa-saffron', 'text-white');
                        b.classList.add('border', 'border-gray-200', 'text-iwa-ink-soft');
                    }
                });
            });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
} );
