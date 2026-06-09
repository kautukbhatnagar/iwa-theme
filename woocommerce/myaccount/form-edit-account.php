<?php
/**
 * IWA My Account — Edit Account Details
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<div class="rounded-2xl border border-gray-100 bg-white overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="font-heading font-bold text-base text-iwa-ink"><?php esc_html_e( 'Account Details', 'woocommerce' ); ?></h2>
        <p class="text-xs text-iwa-ink-soft mt-0.5"><?php esc_html_e( 'Update your name, email, and password.', 'woocommerce' ); ?></p>
    </div>

    <form class="p-6 space-y-5" action="" method="post"
          <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>

        <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

        <!-- Name row -->
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="account_first_name" class="iwa-label">
                    <?php esc_html_e( 'First name', 'woocommerce' ); ?> <abbr class="required text-iwa-saffron" title="required">*</abbr>
                </label>
                <input type="text" id="account_first_name" name="account_first_name" autocomplete="given-name"
                       class="iwa-input"
                       value="<?php echo esc_attr( $user->first_name ); ?>">
            </div>
            <div>
                <label for="account_last_name" class="iwa-label">
                    <?php esc_html_e( 'Last name', 'woocommerce' ); ?> <abbr class="required text-iwa-saffron" title="required">*</abbr>
                </label>
                <input type="text" id="account_last_name" name="account_last_name" autocomplete="family-name"
                       class="iwa-input"
                       value="<?php echo esc_attr( $user->last_name ); ?>">
            </div>
        </div>

        <!-- Display name -->
        <div>
            <label for="account_display_name" class="iwa-label">
                <?php esc_html_e( 'Display name', 'woocommerce' ); ?> <abbr class="required text-iwa-saffron" title="required">*</abbr>
            </label>
            <input type="text" id="account_display_name" name="account_display_name"
                   class="iwa-input"
                   value="<?php echo esc_attr( $user->display_name ); ?>">
            <p class="text-xs text-iwa-ink-soft mt-1"><?php esc_html_e( 'This will be shown publicly.', 'woocommerce' ); ?></p>
        </div>

        <!-- Email -->
        <div>
            <label for="account_email" class="iwa-label">
                <?php esc_html_e( 'Email address', 'woocommerce' ); ?> <abbr class="required text-iwa-saffron" title="required">*</abbr>
            </label>
            <input type="email" id="account_email" name="account_email" autocomplete="email"
                   class="iwa-input"
                   value="<?php echo esc_attr( $user->user_email ); ?>">
        </div>

        <?php do_action( 'woocommerce_edit_account_form' ); ?>

        <!-- Password section -->
        <div class="border-t border-gray-100 pt-5">
            <h3 class="font-heading font-bold text-sm text-iwa-ink mb-4"><?php esc_html_e( 'Password Change', 'woocommerce' ); ?></h3>
            <p class="text-xs text-iwa-ink-soft mb-4"><?php esc_html_e( 'Leave blank to keep your current password.', 'woocommerce' ); ?></p>

            <div class="space-y-4">
                <div>
                    <label for="password_current" class="iwa-label"><?php esc_html_e( 'Current password', 'woocommerce' ); ?></label>
                    <input type="password" id="password_current" name="password_current" autocomplete="off"
                           class="iwa-input">
                </div>
                <div>
                    <label for="password_1" class="iwa-label"><?php esc_html_e( 'New password', 'woocommerce' ); ?></label>
                    <input type="password" id="password_1" name="password_1" autocomplete="new-password"
                           class="iwa-input">
                </div>
                <div>
                    <label for="password_2" class="iwa-label"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
                    <input type="password" id="password_2" name="password_2" autocomplete="new-password"
                           class="iwa-input">
                </div>
            </div>
        </div>

        <?php do_action( 'woocommerce_edit_account_form_end' ); ?>

        <div class="flex justify-end pt-2">
            <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
            <input type="hidden" name="action" value="save_account_details">
            <button type="submit" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"
                    class="rounded-full bg-iwa-saffron text-white font-semibold px-8 py-2.5 text-sm hover:bg-iwa-saffron-light transition">
                <?php esc_html_e( 'Save Changes', 'woocommerce' ); ?>
            </button>
        </div>

    </form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

