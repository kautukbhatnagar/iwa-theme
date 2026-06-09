<?php
$_s           = function_exists( 'iwa_get_settings' ) ? iwa_get_settings() : [];
$_yt          = $_s['youtube_channel']    ?? 'https://www.youtube.com/@IndianWomenAbroad';
$_sp          = $_s['spotify_url']        ?? '';
$_ap          = $_s['apple_podcasts_url'] ?? '';
$_logo        = $_s['site_logo_url']      ?? '';
$_contact     = $_s['contact_us_url']     ?? '';
$_sub_url     = ! empty( $_s['subscribe_url'] ) ? $_s['subscribe_url'] : home_url( '/#subscribe' );
$_heading_font = $_s['heading_font']      ?? 'Epilogue';
$_tagline_font = $_s['tagline_font']      ?? 'Outfit';
$_ig          = $_s['social_instagram']   ?? '';
$_tk          = $_s['social_tiktok']      ?? '';
$_fb          = $_s['social_facebook']    ?? '';
$_li          = $_s['social_linkedin']    ?? '';
$_st          = function_exists( 'iwa_stories_url' ) ? iwa_stories_url() : home_url( '/category/stories-indian-women-abroad/' );
$_store_on    = class_exists( 'WooCommerce' );
// Popup content
$_pop_headline  = ! empty( $_s['popup_headline'] )     ? $_s['popup_headline']     : 'Stories that feel like home, wherever you are.';
$_pop_subtext   = ! empty( $_s['popup_subtext'] )      ? $_s['popup_subtext']      : 'Get our best episodes, stories and community updates straight to your inbox.';
$_pop_perks     = array_filter( [
    $_s['popup_perk_1'] ?? 'New episodes every week',
    $_s['popup_perk_2'] ?? 'Curated stories from women abroad',
    $_s['popup_perk_3'] ?? 'Exclusive community updates',
] );
$_pop_flabel    = ! empty( $_s['popup_form_label'] )   ? $_s['popup_form_label']   : 'Newsletter';
$_pop_ftitle    = ! empty( $_s['popup_form_title'] )   ? $_s['popup_form_title']   : 'Join our community';
$_pop_fsubtitle = ! empty( $_s['popup_form_subtitle'] )? $_s['popup_form_subtitle']: 'No spam, unsubscribe any time.';
$_site_name   = get_bloginfo( 'name' );
// WP tagline often blank — fall back to footer_tagline setting
$_site_tag    = trim( get_bloginfo( 'description' ) );
if ( empty( $_site_tag ) ) $_site_tag = $_s['footer_tagline'] ?? '';

// Build bunny font URL for selected heading + tagline fonts
$_font_slugs  = [
    'Plus Jakarta Sans'  => 'plus-jakarta-sans:600,700',
    'Epilogue'           => 'epilogue:600,700,800',
    'Outfit'             => 'outfit:400,500,600',
    'DM Sans'            => 'dm-sans:400,500,600',
    'Inter'              => 'inter:400,500,600',
    'Nunito'             => 'nunito:400,600,700',
    'Poppins'            => 'poppins:400,500,600',
    'Work Sans'          => 'work-sans:400,500,600',
    'Playfair Display'   => 'playfair-display:400,600,700',
    'DM Serif Display'   => 'dm-serif-display:400',
    'Fraunces'           => 'fraunces:400,600,700',
    'Lora'               => 'lora:400,500,600',
    'Cormorant Garamond' => 'cormorant-garamond:400,600,700',
    'Libre Baskerville'  => 'libre-baskerville:400,700',
    'Merriweather'       => 'merriweather:400,700',
];
$_load_fonts  = array_unique( array_filter( [
    $_font_slugs[ $_heading_font ] ?? null,
    $_font_slugs[ $_tagline_font ] ?? null,
] ) );
$_font_url    = 'https://fonts.bunny.net/css?' . implode( '&', array_map( fn($v) => 'family=' . rawurlencode($v), $_load_fonts ) ) . '&display=swap';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|epilogue:600,700,800|outfit:400,500&display=swap" rel="stylesheet">
    <?php if ( ! empty( $_load_fonts ) ) : ?>
    <link href="<?php echo esc_url( $_font_url ); ?>" rel="stylesheet">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans:    ['Plus Jakarta Sans', 'sans-serif'],
                    heading: ['Epilogue', 'sans-serif'],
                    body:    ['Outfit', 'sans-serif'],
                },
                colors: {
                    iwa: {
                        white:           '#ffffff',
                        saffron:         '#ff671f',
                        'saffron-light': '#ff8a4c',
                        green:           '#046A38',
                        'green-light':   '#057a42',
                        blue:            '#06038D',
                        'blue-light':    '#0a05b3',
                        ink:             '#1a1a1a',
                        'ink-soft':      '#4a4a4a',
                    }
                }
            }
        }
    }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'font-sans antialiased text-iwa-ink bg-white' ); ?>>

<header id="iwa-header" class="bg-white sticky top-0 z-50 shadow-sm">

    <!-- ── Top bar: subscribe left | social icons right ── -->
    <div class="border-b border-gray-100">
        <div class="iwa-header-wrap flex items-center justify-between gap-4 py-2.5">
            <!-- Subscribe with us -->
            <button type="button" id="iwa-subscribe-open"
                    style="font-family:'Plus Jakarta Sans',sans-serif; font-size:0.8rem; font-weight:600; color:#ff671f; background:none; border:none; padding:0; cursor:pointer; letter-spacing:0.01em; white-space:nowrap; text-decoration:none;"
                    onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                Subscribe with us
            </button>
            <!-- Social icons -->
            <div class="flex items-center gap-3">
            <?php
            $socials = [];
            if ( ! empty( $_ig ) ) $socials[] = [ 'label' => 'Instagram', 'href' => $_ig, 'hover' => '#E1306C',
                'svg' => '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/><path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>'];
            if ( ! empty( $_tk ) ) $socials[] = [ 'label' => 'TikTok', 'href' => $_tk, 'hover' => '#000',
                'svg' => '<path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.16 8.16 0 0 0 4.77 1.52V6.7a4.85 4.85 0 0 1-1-.01z"/>'];
            if ( ! empty( $_fb ) ) $socials[] = [ 'label' => 'Facebook', 'href' => $_fb, 'hover' => '#1877F2',
                'svg' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'];
            if ( ! empty( $_li ) ) $socials[] = [ 'label' => 'LinkedIn', 'href' => $_li, 'hover' => '#0077B5',
                'svg' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>'];
            $socials[] = [ 'label' => 'YouTube', 'href' => $_yt, 'hover' => '#ff0000',
                'svg' => '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>'];
            if ( ! empty( $_sp ) ) $socials[] = [ 'label' => 'Spotify', 'href' => $_sp, 'hover' => '#1DB954',
                'svg' => '<path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>'];
            foreach ( $socials as $s ) : ?>
            <a href="<?php echo esc_url( $s['href'] ); ?>" target="_blank" rel="noopener"
               aria-label="<?php echo esc_attr( $s['label'] ); ?>"
               class="iwa-social-icon text-gray-400 hover:text-gray-700 transition"
               style="--hover:<?php echo $s['hover']; ?>">
                <svg class="w-[15px] h-[15px]" fill="currentColor" viewBox="0 0 24 24"><?php echo $s['svg']; ?></svg>
            </a>
            <?php endforeach; ?>
            </div><!-- /.social icons -->
        </div>
    </div>

    <!-- ── Main header bar ─────────────────────────────── -->
    <div class="iwa-header-wrap">
        <div class="flex items-center justify-between gap-2 md:gap-6 py-3">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 flex-shrink-0 group" style="text-decoration:none;">
                <?php if ( ! empty( $_logo ) ) : ?>
                    <img src="<?php echo esc_url( $_logo ); ?>"
                         alt="<?php echo esc_attr( $_site_name ); ?>"
                         class="iwa-header-logo-img"
                         style="height:85px;max-height:85px;width:auto;display:block;object-fit:contain;flex-shrink:0;">
                <?php else : ?>
                    <span class="iwa-header-logo-badge" style="width:44px;height:44px;border-radius:0.75rem;background:#ff671f;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;flex-shrink:0;font-family:'Epilogue',sans-serif;">IWA</span>
                <?php endif; ?>
                <div class="iwa-header-logo-text" style="line-height:1.2;">
                    <p style="font-family:'<?php echo esc_attr( $_heading_font ); ?>',sans-serif; font-weight:700; font-size:0.9375rem; color:#1a1a1a; margin:0; white-space:nowrap;"><?php echo esc_html( $_site_name ); ?></p>
                    <?php if ( $_site_tag ) : ?>
                    <p style="font-size:0.7rem; color:#9ca3af; margin:0.2rem 0 0; white-space:nowrap; font-family:'<?php echo esc_attr( $_tagline_font ); ?>',sans-serif;"><?php echo esc_html( $_site_tag ); ?></p>
                    <?php endif; ?>
                </div>
            </a>

            <!-- Desktop nav (dynamic with dropdown) -->
            <nav class="hidden md:block iwa-primary-nav flex-1">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( [
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'iwa-nav-list',
                        'walker'         => new IWA_Nav_Walker(),
                        'fallback_cb'    => false,
                    ] );
                } else {
                    // Fallback hardcoded nav
                    ?>
                    <ul class="iwa-nav-list">
                        <li class="iwa-nav-item <?php echo is_front_page() ? 'iwa-current' : ''; ?>">
                            <a class="iwa-nav-link-item <?php echo is_front_page() ? 'active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                        </li>
                        <li class="iwa-nav-item <?php echo ( is_category() || is_single() ) ? 'iwa-current' : ''; ?>">
                            <a class="iwa-nav-link-item" href="<?php echo esc_url( $_st ); ?>">Read Stories</a>
                        </li>
                        <li class="iwa-nav-item">
                            <a class="iwa-nav-link-item" href="<?php echo esc_url( home_url( '/#podcast' ) ); ?>">Podcast</a>
                        </li>
                        <li class="iwa-nav-item">
                            <a class="iwa-nav-link-item" href="<?php echo esc_url( home_url( '/#about' ) ); ?>">About</a>
                        </li>
                        <li class="iwa-nav-item">
                            <a class="iwa-nav-link-item" href="<?php echo esc_url( home_url( '/#subscribe' ) ); ?>">Newsletter</a>
                        </li>
                        <?php if ( $_store_on ) : ?>
                        <li class="iwa-nav-item <?php echo ( is_woocommerce() || is_cart() || is_checkout() ) ? 'iwa-current' : ''; ?>">
                            <a class="iwa-nav-link-item <?php echo ( is_woocommerce() || is_cart() || is_checkout() ) ? 'active' : ''; ?>"
                               href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Shop</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <!-- Right: action icons or contact us -->
            <div class="flex items-center gap-1 flex-shrink-0">

                <?php if ( $_store_on ) : ?>

                <!-- Wishlist -->
                <?php $wl_page = get_page_by_path( 'iwa-wishlist' ); ?>
                <a href="<?php echo esc_url( $wl_page ? get_permalink( $wl_page ) : wc_get_page_permalink( 'myaccount' ) ); ?>"
                   class="relative p-2 text-gray-400 hover:text-red-500 transition" aria-label="Wishlist">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                    <span id="iwa-wl-header-count" class="hidden absolute -top-0.5 -right-0.5 w-[18px] h-[18px] rounded-full bg-red-400 text-white text-[10px] font-bold flex items-center justify-center leading-none">0</span>
                </a>

                <!-- Account -->
                <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>"
                   class="p-2 text-gray-400 hover:text-iwa-saffron transition"
                   aria-label="<?php echo is_user_logged_in() ? 'My Account' : 'Login'; ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                </a>

                <!-- Cart -->
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="relative p-2 text-gray-400 hover:text-iwa-saffron transition" aria-label="Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z"/>
                    </svg>
                    <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    <span class="iwa-cart-count absolute -top-0.5 -right-0.5 w-[18px] h-[18px] rounded-full bg-iwa-saffron text-white text-[10px] font-bold flex items-center justify-center leading-none<?php echo $cart_count ? '' : ' hidden'; ?>">
                        <?php echo absint( $cart_count ); ?>
                    </span>
                </a>

                <?php else : // Store offline ?>

                <?php if ( ! empty( $_contact ) ) : ?>
                <a href="<?php echo esc_url( $_contact ); ?>"
                   class="rounded-full border-2 border-iwa-saffron text-iwa-saffron px-4 py-1.5 text-sm font-semibold hover:bg-iwa-saffron hover:text-white transition hidden sm:inline-flex">
                    Contact Us
                </a>
                <?php endif; ?>

                <?php endif; ?>

                <!-- Mobile hamburger -->
                <button type="button" id="iwa-nav-toggle" class="md:hidden p-2 text-iwa-ink ml-1" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- ── Mobile drawer ──────────────────────────────── -->
    <div id="iwa-mobile-nav" class="md:hidden hidden border-t border-gray-100 bg-white">
        <nav class="flex flex-col py-3 px-4">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'iwa-mobile-menu',
                    'depth'          => 2,
                    'fallback_cb'    => false,
                ] );
            } else { ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"         class="iwa-mob-link">Home</a>
            <a href="<?php echo esc_url( $_st ); ?>"                    class="iwa-mob-link">Read Stories</a>
            <a href="<?php echo esc_url( home_url( '/#podcast' ) ); ?>" class="iwa-mob-link">Podcast</a>
            <a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"   class="iwa-mob-link">About</a>
            <a href="<?php echo esc_url( home_url( '/#subscribe' ) ); ?>" class="iwa-mob-link">Newsletter</a>
            <?php if ( $_store_on ) : ?>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="iwa-mob-link">Shop</a>
            <?php endif; ?>
            <?php } ?>
            <?php if ( $_store_on ) : ?>
            <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="iwa-mob-link">
                <?php echo is_user_logged_in() ? 'My Account' : 'Login'; ?>
            </a>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="iwa-mob-link">
                Cart <?php $c = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; if ($c) echo '(' . $c . ')'; ?>
            </a>
            <?php elseif ( ! empty( $_contact ) ) : ?>
            <a href="<?php echo esc_url( $_contact ); ?>" class="iwa-mob-link">Contact Us</a>
            <?php endif; ?>
        </nav>
    </div>

</header>

<!-- ── Subscribe Popup Modal ─────────────────────────────── -->
<div id="iwa-subscribe-modal" aria-modal="true" role="dialog" aria-label="Subscribe" style="display:none; position:fixed; inset:0; z-index:99999;">

    <!-- Backdrop -->
    <div id="iwa-modal-backdrop" style="position:absolute; inset:0; background:rgba(10,10,20,0.6); backdrop-filter:blur(4px);"></div>

    <!-- Card -->
    <div id="iwa-modal-card" style="position:relative; z-index:1; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:1.5rem;">
        <div style="display:flex; width:100%; max-width:860px; min-height:480px; border-radius:1.25rem; overflow:hidden; box-shadow:0 24px 64px rgba(0,0,0,.4); animation:iwaModalIn .28s ease;">

            <!-- Left panel: branding -->
            <div class="iwa-modal-left" style="flex:0 0 42%; background:linear-gradient(155deg,#1a1a1a 0%,#06038D 100%); padding:2.5rem 2rem; display:flex; flex-direction:column; justify-content:space-between; position:relative; overflow:hidden;">
                <!-- Decorative circles -->
                <div style="position:absolute; top:-60px; right:-60px; width:220px; height:220px; border-radius:50%; background:rgba(255,103,31,.12);"></div>
                <div style="position:absolute; bottom:-40px; left:-40px; width:160px; height:160px; border-radius:50%; background:rgba(4,106,56,.15);"></div>

                <div style="position:relative; z-index:1;">
                    <!-- Logo badge -->
                    <div style="display:inline-flex; align-items:center; gap:0.6rem; margin-bottom:1.75rem;">
                        <span style="width:36px; height:36px; border-radius:8px; background:#ff671f; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.9rem; font-family:'Epilogue',sans-serif; flex-shrink:0;">IWA</span>
                        <span style="color:rgba(255,255,255,.85); font-weight:600; font-size:0.85rem; font-family:'Plus Jakarta Sans',sans-serif;">Indian Women Abroad</span>
                    </div>

                    <h2 style="font-family:'Epilogue',sans-serif; font-size:1.65rem; font-weight:800; color:#fff; line-height:1.25; margin:0 0 1rem;">
                        <?php echo esc_html( $_pop_headline ); ?>
                    </h2>
                    <p style="color:rgba(255,255,255,.6); font-size:0.875rem; line-height:1.6; margin:0;">
                        <?php echo esc_html( $_pop_subtext ); ?>
                    </p>
                </div>

                <!-- Perks list -->
                <?php if ( ! empty( $_pop_perks ) ) : ?>
                <ul style="position:relative; z-index:1; list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:0.6rem;">
                    <?php foreach ( $_pop_perks as $perk ) : ?>
                    <li style="display:flex; align-items:center; gap:0.5rem; color:rgba(255,255,255,.75); font-size:0.8rem;">
                        <svg width="14" height="14" viewBox="0 0 20 20" fill="#ff671f"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143z" clip-rule="evenodd"/></svg>
                        <?php echo esc_html( $perk ); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Right panel: form -->
            <div style="flex:1; background:#fff; padding:2.5rem 2.25rem; display:flex; flex-direction:column; position:relative;">
                <!-- Close button -->
                <button type="button" id="iwa-modal-close"
                        aria-label="Close"
                        style="position:absolute; top:1.1rem; right:1.1rem; width:32px; height:32px; border-radius:50%; border:1.5px solid #e5e7eb; background:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#6b7280; font-size:1rem; transition:all .15s;"
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#fff'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div style="margin-bottom:1.75rem;">
                    <p style="font-size:0.7rem; font-weight:700; color:#ff671f; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 0.4rem;"><?php echo esc_html( $_pop_flabel ); ?></p>
                    <h3 style="font-family:'Epilogue',sans-serif; font-size:1.4rem; font-weight:700; color:#1a1a1a; margin:0 0 0.4rem;"><?php echo esc_html( $_pop_ftitle ); ?></h3>
                    <p style="color:#6b7280; font-size:0.85rem; margin:0;"><?php echo esc_html( $_pop_fsubtitle ); ?></p>
                </div>

                <!-- Subscription form -->
                <form class="iwa-sub-form" data-source="popup" style="flex:1;display:flex;flex-direction:column;gap:0.75rem;">
                    <div style="display:flex;gap:0.6rem;">
                        <div style="flex:1;">
                            <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.35rem;">First Name</label>
                            <input type="text" name="fname" placeholder="First name"
                                   style="width:100%;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.7rem 1rem;font-size:0.875rem;outline:none;box-sizing:border-box;transition:border-color .15s;"
                                   onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.35rem;">Last Name</label>
                            <input type="text" name="lname" placeholder="Last name"
                                   style="width:100%;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.7rem 1rem;font-size:0.875rem;outline:none;box-sizing:border-box;transition:border-color .15s;"
                                   onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.35rem;">Email Address <span style="color:#ff671f;">*</span></label>
                        <input type="email" name="email" placeholder="you@example.com" required
                               style="width:100%;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.7rem 1rem;font-size:0.875rem;outline:none;box-sizing:border-box;transition:border-color .15s;"
                               onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
                    </div>
                    <label style="display:flex;align-items:flex-start;gap:0.6rem;cursor:pointer;">
                        <input type="checkbox" name="consent" required
                               style="margin-top:3px;width:16px;height:16px;accent-color:#ff671f;flex-shrink:0;">
                        <span style="font-size:0.78rem;color:#6b7280;line-height:1.5;">
                            I agree to receive newsletters and updates from Indian Women Abroad. You can unsubscribe at any time.
                        </span>
                    </label>
                    <button type="submit"
                            style="width:100%;background:#ff671f;color:#fff;border:none;border-radius:0.625rem;padding:0.85rem;font-size:0.9rem;font-weight:600;cursor:pointer;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif;"
                            onmouseover="this.style.background='#ff8a4c'" onmouseout="this.style.background='#ff671f'">
                        Subscribe Now
                    </button>
                    <div class="iwa-sub-msg" style="display:none;padding:0.6rem 0.875rem;border-radius:0.5rem;font-size:0.85rem;font-weight:500;"></div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
@keyframes iwaModalIn {
    from { opacity:0; transform:scale(.95) translateY(12px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
@media (max-width: 640px) {
    .iwa-modal-left { display:none !important; }
}
</style>

<script>
(function () {
    var btn   = document.getElementById('iwa-nav-toggle');
    var menu  = document.getElementById('iwa-mobile-nav');
    if (btn && menu) btn.addEventListener('click', function () { menu.classList.toggle('hidden'); });

    /* ── Subscribe modal ── */
    var modal    = document.getElementById('iwa-subscribe-modal');
    var closeBtn = document.getElementById('iwa-modal-close');
    var backdrop = document.getElementById('iwa-modal-backdrop');

    function openModal() {
        if (!modal) return;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    /* Any element with class iwa-subscribe-open OR id iwa-subscribe-open opens the modal */
    document.querySelectorAll('.iwa-subscribe-open, #iwa-subscribe-open').forEach(function(el) {
        el.addEventListener('click', function(e){ e.preventDefault(); openModal(); });
    });

    /* Global helper so inline links like <a href="#" onclick="openIwaModal()"> work too */
    window.openIwaModal = openModal;

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });

    /* ── Subscription forms (popup + footer) ── */
    document.querySelectorAll('.iwa-sub-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var btn = form.querySelector('button[type="submit"]');
            var msg = form.querySelector('.iwa-sub-msg');
            var email   = form.querySelector('[name="email"]').value;
            var consent = form.querySelector('[name="consent"]').checked;
            var fnameEl = form.querySelector('[name="fname"]');
            var lnameEl = form.querySelector('[name="lname"]');
            var source  = form.dataset.source || 'website';

            btn.disabled = true;
            btn.textContent = 'Subscribing…';

            var data = new FormData();
            data.append('action',    'iwa_subscribe');
            data.append('nonce',     (window.iwaSubscribe && window.iwaSubscribe.nonce) || '');
            data.append('email',     email);
            data.append('consent',   consent ? '1' : '');
            data.append('source',    source);
            data.append('page_url',  window.location.href);
            if (fnameEl) data.append('fname', fnameEl.value);
            if (lnameEl) data.append('lname', lnameEl.value);

            fetch((window.iwaSubscribe && window.iwaSubscribe.ajaxurl) || '/wp-admin/admin-ajax.php', {
                method: 'POST', body: data
            })
            .then(function(r){ return r.json(); })
            .then(function(res) {
                msg.style.display = 'block';
                if (res.success) {
                    msg.style.background = '#f0fdf4';
                    msg.style.color = '#046A38';
                    msg.style.border = '1px solid #bbf7d0';
                    msg.textContent = res.data.message;
                    form.querySelector('[name="email"]').value = '';
                    if (fnameEl) fnameEl.value = '';
                    form.querySelector('[name="consent"]').checked = false;
                } else {
                    msg.style.background = '#fef2f2';
                    msg.style.color = '#dc2626';
                    msg.style.border = '1px solid #fecaca';
                    msg.textContent = res.data.message;
                }
                btn.disabled = false;
                btn.textContent = 'Subscribe Now';
            })
            .catch(function() {
                msg.style.display = 'block';
                msg.style.background = '#fef2f2';
                msg.style.color = '#dc2626';
                msg.textContent = 'Connection error — please try again.';
                btn.disabled = false;
                btn.textContent = 'Subscribe Now';
            });
        });
    });
})();
</script>
