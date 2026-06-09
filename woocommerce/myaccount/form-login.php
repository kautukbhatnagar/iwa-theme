<?php
/**
 * IWA Login/Register forms
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="max-w-3xl mx-auto">
    <!-- Heading -->
    <div class="text-center mb-10">
        <span class="w-12 h-12 rounded-xl bg-iwa-saffron flex items-center justify-center text-white font-bold text-lg mx-auto mb-3">IWA</span>
        <h1 class="font-heading text-2xl lg:text-3xl font-bold text-iwa-ink">Welcome back</h1>
        <p class="text-iwa-ink-soft mt-1 text-sm">Sign in to your account or create a new one</p>
    </div>

    <div class="grid sm:grid-cols-2 gap-0 sm:gap-px bg-gray-200 rounded-2xl overflow-hidden shadow-sm">

        <!-- ── Login Form ──────────────────────────────── -->
        <div class="bg-white p-7 sm:p-8 rounded-2xl sm:rounded-none sm:rounded-l-2xl">
            <h2 class="font-heading font-bold text-xl text-iwa-ink mb-6">Sign In</h2>

            <form class="woocommerce-form woocommerce-form-login login" method="post">
                <?php do_action( 'woocommerce_login_form_start' ); ?>

                <div class="space-y-4">
                    <div>
                        <label for="username" class="block text-xs font-semibold text-iwa-ink uppercase tracking-wider mb-1.5">
                            <?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="text-red-400">*</span>
                        </label>
                        <input type="text" class="iwa-input w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm focus:border-iwa-saffron outline-none transition"
                               name="username" id="username" autocomplete="username email"
                               value="<?php echo esc_attr( isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '' ); ?>"
                               required>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-iwa-ink uppercase tracking-wider">
                                <?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="text-red-400">*</span>
                            </label>
                            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-xs text-iwa-saffron hover:text-iwa-saffron-light transition">
                                Forgot password?
                            </a>
                        </div>
                        <input type="password" class="iwa-input w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm focus:border-iwa-saffron outline-none transition"
                               name="password" id="password" autocomplete="current-password" required>
                    </div>

                    <?php do_action( 'woocommerce_login_form' ); ?>

                    <label class="flex items-center gap-2 text-sm text-iwa-ink-soft cursor-pointer">
                        <input type="checkbox" class="rounded accent-iwa-saffron" name="rememberme" value="forever" <?php echo ( isset( $_POST['rememberme'] ) ? checked( $_POST['rememberme'], 'forever', false ) : '' ); ?>>
                        <?php esc_html_e( 'Remember me', 'woocommerce' ); ?>
                    </label>
                </div>

                <button type="submit" class="w-full mt-6 rounded-full bg-iwa-saffron text-white py-3 font-semibold hover:bg-iwa-saffron-light transition text-sm"
                        name="login" value="<?php esc_attr_e( 'Sign in', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Sign In', 'woocommerce' ); ?>
                </button>

                <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <input type="hidden" name="redirect" value="<?php echo esc_url( apply_filters( 'woocommerce_login_redirect', wc_get_page_permalink( 'myaccount' ), null ) ); ?>">

                <?php do_action( 'woocommerce_login_form_end' ); ?>
            </form>
        </div>

        <!-- ── Register Form ──────────────────────────── -->
        <div class="bg-white p-7 sm:p-8 rounded-2xl sm:rounded-none sm:rounded-r-2xl border-t border-gray-200 sm:border-t-0 sm:border-l">
            <h2 class="font-heading font-bold text-xl text-iwa-ink mb-1">Create Account</h2>
            <p class="text-xs text-iwa-ink-soft mb-6">Join the IWA community</p>

            <form class="woocommerce-form woocommerce-form-register register" method="post">
                <?php do_action( 'woocommerce_register_form_start' ); ?>

                <div class="space-y-4">
                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                    <div>
                        <label for="reg_username" class="block text-xs font-semibold text-iwa-ink uppercase tracking-wider mb-1.5">
                            <?php esc_html_e( 'Username', 'woocommerce' ); ?> <span class="text-red-400">*</span>
                        </label>
                        <input type="text" class="iwa-input w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm focus:border-iwa-green outline-none transition"
                               name="username" id="reg_username" autocomplete="username"
                               value="<?php echo esc_attr( isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '' ); ?>">
                    </div>
                    <?php endif; ?>

                    <div>
                        <label for="reg_email" class="block text-xs font-semibold text-iwa-ink uppercase tracking-wider mb-1.5">
                            <?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="text-red-400">*</span>
                        </label>
                        <input type="email" class="iwa-input w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm focus:border-iwa-green outline-none transition"
                               name="email" id="reg_email" autocomplete="email"
                               value="<?php echo esc_attr( isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '' ); ?>" required>
                    </div>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                    <div>
                        <label for="reg_password" class="block text-xs font-semibold text-iwa-ink uppercase tracking-wider mb-1.5">
                            <?php esc_html_e( 'Password', 'woocommerce' ); ?> <span class="text-red-400">*</span>
                        </label>
                        <input type="password" class="iwa-input w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm focus:border-iwa-green outline-none transition"
                               name="password" id="reg_password" autocomplete="new-password" required>
                    </div>
                    <?php else : ?>
                    <div class="rounded-xl bg-green-50 border border-green-100 p-3 flex gap-2 text-xs text-iwa-green">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                        A password will be emailed to you automatically.
                    </div>
                    <?php endif; ?>
                </div>

                <?php do_action( 'woocommerce_register_form' ); ?>

                <button type="submit" class="w-full mt-6 rounded-full bg-iwa-green text-white py-3 font-semibold hover:bg-iwa-green-light transition text-sm"
                        name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
                    <?php esc_html_e( 'Create Account', 'woocommerce' ); ?>
                </button>

                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

                <?php do_action( 'woocommerce_register_form_end' ); ?>
            </form>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
