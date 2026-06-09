<?php
/**
 * IWA My Account — main wrapper
 */
defined( 'ABSPATH' ) || exit;
?>


<?php if ( is_user_logged_in() ) :
    $current_user = wp_get_current_user();
?>

<div id="iwa-account-layout">

    <!-- ── Sidebar ── -->
    <?php do_action( 'woocommerce_before_account_navigation' ); ?>
    <div id="iwa-account-sidebar">

        <div class="iwa-sid-user">
            <div class="iwa-sid-avatar">
                <?php echo esc_html( strtoupper( substr( $current_user->display_name, 0, 1 ) ) ); ?>
            </div>
            <div style="min-width:0;">
                <p class="iwa-sid-name"><?php echo esc_html( $current_user->display_name ); ?></p>
                <p class="iwa-sid-email"><?php echo esc_html( $current_user->user_email ); ?></p>
            </div>
        </div>

        <nav class="woocommerce-MyAccount-navigation iwa-sid-nav">
            <ul>
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                    $classes = wc_get_account_menu_item_classes( $endpoint );
                    $icon    = iwa_account_nav_icon( $endpoint );
                ?>
                <li class="<?php echo esc_attr( $classes ); ?>">
                    <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
                        <?php echo $icon; ?>
                        <span><?php echo esc_html( $label ); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

    </div>
    <?php do_action( 'woocommerce_after_account_navigation' ); ?>

    <!-- ── Content ── -->
    <div id="iwa-account-main" class="woocommerce-MyAccount-content">
        <?php do_action( 'woocommerce_account_content' ); ?>
    </div>

</div>

<?php else : ?>

<div class="woocommerce-MyAccount-content">
    <?php do_action( 'woocommerce_account_content' ); ?>
</div>

<?php endif; ?>
