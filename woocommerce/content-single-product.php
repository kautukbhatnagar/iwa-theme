<?php
/**
 * IWA Single Product — custom layout
 */
defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' ); // outputs notices

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}

// ── Product data ─────────────────────────────────────────────
$main_id     = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$all_images  = array_values( array_filter( array_merge( [ $main_id ], $gallery_ids ) ) );

// Category badge (skip "Uncategorized")
$terms     = get_the_terms( $product->get_id(), 'product_cat' );
$badge_cat = null;
if ( $terms ) {
    foreach ( $terms as $t ) {
        if ( $t->slug !== 'uncategorized' ) { $badge_cat = $t; break; }
    }
    if ( ! $badge_cat ) $badge_cat = $terms[0];
}

// First approved review for testimonial
$reviews = get_comments( [ 'post_id' => get_the_ID(), 'status' => 'approve', 'number' => 1 ] );
$review  = $reviews[0] ?? null;

// Virtual / downloadable?
$is_virtual = $product->is_virtual() || $product->is_downloadable();

// Related products (4 max)
$related_ids = wc_get_related_products( $product->get_id(), 4 );
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'iwa-sp-wrap', $product ); ?>>

<!-- ══════════════════════════════════════════════════════════
     HERO: Image gallery (left) + Product details (right)
══════════════════════════════════════════════════════════ -->
<div class="iwa-sp-hero">

    <!-- ── Gallery mosaic ──────────────────────────────── -->
    <div class="iwa-sp-gallery">
        <?php if ( ! empty( $all_images ) ) :
            $imgs = $all_images;
        ?>
            <div class="iwa-sp-img-main">
                <?php echo wp_get_attachment_image( $imgs[0], 'large', false, [ 'class' => 'iwa-sp-img' ] ); ?>
            </div>
            <?php if ( count( $imgs ) >= 3 ) : ?>
            <div class="iwa-sp-img-row">
                <div class="iwa-sp-img-cell"><?php echo wp_get_attachment_image( $imgs[1], 'medium_large', false, [ 'class' => 'iwa-sp-img' ] ); ?></div>
                <div class="iwa-sp-img-cell"><?php echo wp_get_attachment_image( $imgs[2], 'medium_large', false, [ 'class' => 'iwa-sp-img' ] ); ?></div>
            </div>
            <?php elseif ( count( $imgs ) === 2 ) : ?>
            <div class="iwa-sp-img-row">
                <div class="iwa-sp-img-cell iwa-sp-img-cell--full"><?php echo wp_get_attachment_image( $imgs[1], 'large', false, [ 'class' => 'iwa-sp-img' ] ); ?></div>
            </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="iwa-sp-img-main">
                <img src="<?php echo esc_url( wc_placeholder_img_src( 'large' ) ); ?>"
                     alt="<?php the_title_attribute(); ?>" class="iwa-sp-img">
            </div>
        <?php endif; ?>
    </div><!-- /.gallery -->

    <!-- ── Product details ─────────────────────────────── -->
    <div class="iwa-sp-details">

        <?php woocommerce_show_product_sale_flash(); ?>

        <!-- Category badge -->
        <?php if ( $badge_cat ) : ?>
        <span class="iwa-sp-badge"><?php echo esc_html( strtoupper( $badge_cat->name ) ); ?></span>
        <?php endif; ?>

        <!-- Title -->
        <h1 class="iwa-sp-title"><?php the_title(); ?></h1>

        <!-- Price -->
        <div class="iwa-sp-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>

        <!-- Short description (may contain bullet list) -->
        <?php if ( $product->get_short_description() ) : ?>
        <div class="iwa-sp-short-desc">
            <?php echo wp_kses_post( $product->get_short_description() ); ?>
        </div>
        <?php endif; ?>

        <!-- Add to cart form -->
        <div class="iwa-sp-atc-wrap">
            <?php woocommerce_template_single_add_to_cart(); ?>
            <?php if ( $is_virtual ) : ?>
            <p class="iwa-sp-activation-note">INSTANT DIGITAL ACTIVATION UPON CHECKOUT</p>
            <?php endif; ?>
        </div>

        <!-- Testimonial (first review) -->
        <?php if ( $review ) : ?>
        <div class="iwa-sp-testimonial">
            <p class="iwa-sp-quote">"<?php echo esc_html( wp_trim_words( $review->comment_content, 35, '…' ) ); ?>"</p>
            <div class="iwa-sp-reviewer">
                <span class="iwa-sp-reviewer-dot"></span>
                <span class="iwa-sp-reviewer-name"><?php echo esc_html( $review->comment_author ); ?></span>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /.details -->

</div><!-- /.iwa-sp-hero -->

<!-- ══════════════════════════════════════════════════════════
     PRODUCT DESCRIPTION
══════════════════════════════════════════════════════════ -->
<?php if ( $product->get_description() ) : ?>
<div class="iwa-sp-desc-section">
    <div class="iwa-sp-desc-inner">
        <?php echo wp_kses_post( apply_filters( 'the_content', $product->get_description() ) ); ?>
    </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════════════
     RELATED PRODUCTS — "Expand Your Collection"
══════════════════════════════════════════════════════════ -->
<?php if ( ! empty( $related_ids ) ) : ?>
<div class="iwa-sp-related">

    <div class="iwa-sp-related-head">
        <div>
            <h2 class="iwa-sp-related-title">Expand Your Collection</h2>
            <p class="iwa-sp-related-sub">More ways to connect with your heritage.</p>
        </div>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="iwa-sp-viewall">VIEW ALL</a>
    </div>

    <div class="iwa-sp-related-grid">
        <?php foreach ( $related_ids as $rid ) :
            $rp = wc_get_product( $rid );
            if ( ! $rp || ! $rp->is_visible() ) continue;

            $rcats = get_the_terms( $rid, 'product_cat' );
            $rcat  = null;
            if ( $rcats ) {
                foreach ( $rcats as $t ) { if ( $t->slug !== 'uncategorized' ) { $rcat = $t; break; } }
                if ( ! $rcat ) $rcat = $rcats[0];
            }
        ?>
        <a href="<?php echo esc_url( $rp->get_permalink() ); ?>" class="iwa-sp-related-card">
            <div class="iwa-sp-related-img-wrap">
                <?php if ( $rp->get_image_id() ) : ?>
                    <?php echo $rp->get_image( 'medium_large', [ 'class' => 'iwa-sp-related-img' ] ); ?>
                <?php else : ?>
                    <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>"
                         class="iwa-sp-related-img" alt="">
                <?php endif; ?>
            </div>
            <?php if ( $rcat ) : ?>
            <span class="iwa-sp-related-cat"><?php echo esc_html( strtoupper( $rcat->name ) ); ?></span>
            <?php endif; ?>
            <h3 class="iwa-sp-related-name"><?php echo esc_html( $rp->get_name() ); ?></h3>
            <p class="iwa-sp-related-price"><?php echo wp_kses_post( $rp->get_price_html() ); ?></p>
        </a>
        <?php endforeach; ?>
    </div>

</div><!-- /.iwa-sp-related -->
<?php endif; ?>

</div><!-- #product -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
