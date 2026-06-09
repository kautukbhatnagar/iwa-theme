<?php
/**
 * IWA My Account Dashboard
 */
defined( 'ABSPATH' ) || exit;

$current_user  = wp_get_current_user();
$orders        = wc_get_orders( [ 'customer' => get_current_user_id(), 'limit' => 3 ] );
$orders_page   = wc_get_account_endpoint_url( 'orders' );
$address_page  = wc_get_account_endpoint_url( 'edit-address' );
$account_page  = wc_get_account_endpoint_url( 'edit-account' );
?>

<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border-radius: 1rem; padding: 1.75rem; margin-bottom: 1.5rem; color: #fff;">
    <p style="color: rgba(255,255,255,0.55); font-size: 0.8rem; margin: 0 0 0.35rem; font-family: 'Outfit', sans-serif;">Welcome back</p>
    <h2 style="font-family: 'Epilogue', sans-serif; font-size: 1.4rem; font-weight: 700; margin: 0 0 0.25rem; color: #fff;">
        <?php echo esc_html( $current_user->display_name ); ?>
    </h2>
    <p style="color: rgba(255,255,255,0.45); font-size: 0.8rem; margin: 0; font-family: 'Outfit', sans-serif;">
        <?php echo esc_html( $current_user->user_email ); ?>
    </p>
</div>

<!-- Quick actions -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1.5rem;">
    <?php
    $quick = [
        [ 'href' => $orders_page,  'label' => 'My Orders',       'color' => '#ff671f',
          'icon' => '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.015H3.75V6.75zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zM3.75 12h.007v.015H3.75V12zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm-.375 5.25h.007v.015H3.75v-.015zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z"/></svg>' ],
        [ 'href' => $address_page, 'label' => 'Addresses',        'color' => '#06038D',
          'icon' => '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0z"/></svg>' ],
        [ 'href' => $account_page, 'label' => 'Account Details',  'color' => '#046A38',
          'icon' => '<svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>' ],
    ];
    foreach ( $quick as $q ) : ?>
    <a href="<?php echo esc_url( $q['href'] ); ?>"
       style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.5rem; border-radius:0.875rem; border:1px solid #f3f4f6; background:#fff; padding:1.25rem 0.75rem; text-align:center; text-decoration:none; transition:box-shadow .15s, border-color .15s;"
       onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'; this.style.borderColor='#fce8de';"
       onmouseout="this.style.boxShadow=''; this.style.borderColor='#f3f4f6';">
        <span style="color:<?php echo $q['color']; ?>;"><?php echo $q['icon']; ?></span>
        <span style="font-size:0.75rem; font-weight:600; color:#1a1a1a; font-family:'Plus Jakarta Sans',sans-serif;"><?php echo esc_html( $q['label'] ); ?></span>
    </a>
    <?php endforeach; ?>
</div>

<!-- Recent Orders -->
<?php if ( ! empty( $orders ) ) : ?>
<div>
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.875rem;">
        <h3 style="font-family:'Epilogue',sans-serif; font-weight:700; font-size:0.9375rem; color:#1a1a1a; margin:0;">Recent Orders</h3>
        <a href="<?php echo esc_url( $orders_page ); ?>"
           style="font-size:0.75rem; font-weight:600; color:#ff671f; text-decoration:none;">View all &rarr;</a>
    </div>

    <div style="border:1px solid #f3f4f6; border-radius:0.875rem; overflow:hidden;">
        <?php foreach ( $orders as $order ) : ?>
        <div style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem; background:#fff; border-bottom:1px solid #f9fafb;">
            <!-- Order # + date -->
            <div style="flex:1; min-width:0;">
                <p style="font-size:0.8125rem; font-weight:700; color:#1a1a1a; margin:0;">
                    #<?php echo esc_html( $order->get_order_number() ); ?>
                </p>
                <p style="font-size:0.7rem; color:#9ca3af; margin:0.15rem 0 0; font-family:'Outfit',sans-serif;">
                    <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                </p>
            </div>
            <!-- Status badge -->
            <?php
            $is_complete = ( $order->get_status() === 'completed' );
            $badge_bg    = $is_complete ? '#dcfce7' : '#fff7ed';
            $badge_color = $is_complete ? '#046A38' : '#ff671f';
            ?>
            <span style="border-radius:9999px; padding:0.2rem 0.625rem; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.04em; background:<?php echo $badge_bg; ?>; color:<?php echo $badge_color; ?>; white-space:nowrap; flex-shrink:0;">
                <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
            </span>
            <!-- Total -->
            <span style="font-size:0.8125rem; font-weight:700; color:#1a1a1a; flex-shrink:0;">
                <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
            </span>
            <!-- View link -->
            <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"
               style="font-size:0.75rem; font-weight:600; color:#ff671f; text-decoration:none; white-space:nowrap; flex-shrink:0;">View</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php else : ?>

<div style="border:1px solid #f3f4f6; border-radius:0.875rem; background:#f9fafb; padding:2.5rem 1rem; text-align:center;">
    <p style="font-size:0.875rem; color:#9ca3af; margin:0 0 1rem; font-family:'Outfit',sans-serif;">You haven't placed any orders yet.</p>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
       style="display:inline-block; border-radius:9999px; background:#ff671f; color:#fff; padding:0.625rem 1.5rem; font-size:0.875rem; font-weight:600; text-decoration:none;">
        Start Shopping
    </a>
</div>

<?php endif; ?>
