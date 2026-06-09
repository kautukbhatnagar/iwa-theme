<?php
/**
 * WooCommerce wrapper — uses our header/footer so all WC pages
 * (shop, product, cart, checkout, my-account) inherit the theme.
 */
get_header();
?>

<main class="py-10 lg:py-16 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php woocommerce_breadcrumb( [
            'delimiter'   => ' <span class="text-gray-300 mx-1">/</span> ',
            'wrap_before' => '<nav class="text-sm text-iwa-ink-soft mb-6 flex flex-wrap items-center gap-1">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
        ] ); ?>

        <?php woocommerce_content(); ?>

    </div>
</main>


<?php get_footer(); ?>
