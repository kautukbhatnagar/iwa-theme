<?php
/**
 * IWA Checkout Form
 * Overrides WooCommerce default checkout template.
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// Non-JS fallback
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
    return;
}
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout iwa-checkout-form"
      action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <?php if ( $checkout->get_checkout_fields() ) : ?>

    <div class="iwa-checkout-grid">

        <!-- ── Left column: customer details ─────────────────────── -->
        <div class="iwa-checkout-left">

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div id="customer_details">
                <?php do_action( 'woocommerce_checkout_billing' ); ?>
                <?php do_action( 'woocommerce_checkout_shipping' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <!-- Additional information -->
            <?php foreach ( $checkout->get_checkout_fields() as $fieldset_key => $fieldset ) :
                if ( 'billing' === $fieldset_key || 'shipping' === $fieldset_key ) continue;
                $show_fieldset = false;
                foreach ( $fieldset as $field_key => $field ) {
                    if ( ! isset( $field['type'] ) ) { $field['type'] = 'text'; }
                    if ( 'hidden' !== $field['type'] ) { $show_fieldset = true; break; }
                }
                if ( ! $show_fieldset ) continue;
            ?>
            <div class="iwa-checkout-section mt-6">
                <h3 class="iwa-checkout-section-title"><?php echo esc_html( isset( $fieldset['title'] ) ? $fieldset['title'] : __( 'Additional Information', 'woocommerce' ) ); ?></h3>
                <?php foreach ( $fieldset as $key => $field ) : ?>
                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

        </div><!-- /.iwa-checkout-left -->

        <!-- ── Right column: order review + payment ──────────────── -->
        <div class="iwa-checkout-right">

            <div class="iwa-order-review-wrap">

                <h3 class="iwa-checkout-section-title"><?php esc_html_e( 'Your Order', 'woocommerce' ); ?></h3>

                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>

                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

            </div>

        </div><!-- /.iwa-checkout-right -->

    </div><!-- /.iwa-checkout-grid -->

    <?php endif; ?>

</form>

