<?php
/**
 * Template Name: IWA Contact Page
 *
 * Assign this template to any page via Page Attributes → Template.
 * Contact info is set in IWA Site → Site Settings → Contact Page.
 * Only fields with a value are displayed.
 */

// ── Handle form submission ────────────────────────────────
$form_sent  = false;
$form_error = '';

if ( isset( $_POST['iwa_contact_submit'] ) ) {
    if ( ! isset( $_POST['iwa_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iwa_contact_nonce'] ) ), 'iwa_contact_form' ) ) {
        $form_error = 'Security check failed. Please try again.';
    } else {
        $sender_name    = sanitize_text_field( wp_unslash( $_POST['contact_name']    ?? '' ) );
        $sender_email   = sanitize_email( wp_unslash( $_POST['contact_email_field'] ?? '' ) );
        $sender_subject = sanitize_text_field( wp_unslash( $_POST['contact_subject'] ?? '' ) );
        $sender_message = sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ?? '' ) );

        if ( ! $sender_name || ! $sender_email || ! $sender_message ) {
            $form_error = 'Please fill in all required fields.';
        } elseif ( ! is_email( $sender_email ) ) {
            $form_error = 'Please enter a valid email address.';
        } else {
            $to      = get_option( 'admin_email' );
            $subject = $sender_subject ?: 'New Contact Message from ' . $sender_name;
            $body    = "Name: {$sender_name}\nEmail: {$sender_email}\n\nMessage:\n{$sender_message}";
            $headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$sender_name} <{$sender_email}>" ];

            if ( wp_mail( $to, $subject, $body, $headers ) ) {
                $form_sent = true;
            } else {
                $form_error = 'Sorry, there was a problem sending your message. Please try again later.';
            }
        }
    }
}

// ── Contact info from IWA Settings ───────────────────────
$s = function_exists( 'iwa_get_settings' ) ? iwa_get_settings() : [];

$c_intro     = $s['contact_intro']     ?? '';
$c_email     = $s['contact_email']     ?? '';
$c_phone     = $s['contact_phone']     ?? '';
$c_address   = $s['contact_address']   ?? '';
$c_map       = $s['contact_map_url']   ?? '';
$c_instagram = $s['contact_instagram'] ?? '';
$c_twitter   = $s['contact_twitter']   ?? '';
$c_linkedin  = $s['contact_linkedin']  ?? '';

$has_info    = $c_email || $c_phone || $c_address;
$has_social  = $c_instagram || $c_twitter || $c_linkedin;

get_header();
?>

<main class="min-h-screen bg-gray-50/50">

    <!-- ── Page hero ─────────────────────────────────────── -->
    <div class="bg-iwa-ink py-14 lg:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-semibold uppercase tracking-widest text-iwa-saffron">Get in Touch</span>
            <h1 class="mt-3 text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                <?php echo esc_html( get_the_title() ?: 'Contact Us' ); ?>
            </h1>
            <?php if ( $c_intro ) : ?>
            <p class="mt-4 text-white/70 max-w-xl mx-auto leading-relaxed">
                <?php echo esc_html( $c_intro ); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Main content ──────────────────────────────────── -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <div class="grid lg:grid-cols-5 gap-10 lg:gap-16 items-start">

            <!-- Left: Contact information ─────────────────── -->
            <?php if ( $has_info || $has_social || $c_map ) : ?>
            <aside class="lg:col-span-2 space-y-8">

                <?php if ( $has_info ) : ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-bold text-iwa-ink text-lg">Contact Information</h2>

                    <?php if ( $c_email ) : ?>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-9 h-9 rounded-full bg-iwa-saffron/10 text-iwa-saffron flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs text-iwa-ink-soft font-medium uppercase tracking-wider mb-0.5">Email</p>
                            <a href="mailto:<?php echo esc_attr( $c_email ); ?>"
                               class="text-iwa-ink font-medium hover:text-iwa-saffron transition break-all">
                                <?php echo esc_html( $c_email ); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ( $c_phone ) : ?>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-9 h-9 rounded-full bg-iwa-green/10 text-iwa-green flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs text-iwa-ink-soft font-medium uppercase tracking-wider mb-0.5">Phone</p>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $c_phone ) ); ?>"
                               class="text-iwa-ink font-medium hover:text-iwa-green transition">
                                <?php echo esc_html( $c_phone ); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ( $c_address ) : ?>
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-9 h-9 rounded-full bg-iwa-blue/10 text-iwa-blue flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs text-iwa-ink-soft font-medium uppercase tracking-wider mb-0.5">Address</p>
                            <p class="text-iwa-ink font-medium whitespace-pre-line"><?php echo esc_html( $c_address ); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if ( $has_social ) : ?>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-iwa-ink text-lg mb-4">Follow Us</h2>
                    <div class="flex flex-wrap gap-3">
                        <?php if ( $c_instagram ) : ?>
                        <a href="<?php echo esc_url( $c_instagram ); ?>" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-iwa-ink hover:border-[#E1306C] hover:text-[#E1306C] transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            Instagram
                        </a>
                        <?php endif; ?>

                        <?php if ( $c_twitter ) : ?>
                        <a href="<?php echo esc_url( $c_twitter ); ?>" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-iwa-ink hover:border-black hover:text-black transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            X / Twitter
                        </a>
                        <?php endif; ?>

                        <?php if ( $c_linkedin ) : ?>
                        <a href="<?php echo esc_url( $c_linkedin ); ?>" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-iwa-ink hover:border-[#0A66C2] hover:text-[#0A66C2] transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            LinkedIn
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ( $c_map ) : ?>
                <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm aspect-video">
                    <iframe src="<?php echo esc_url( $c_map ); ?>"
                            width="100%" height="100%"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Map"></iframe>
                </div>
                <?php endif; ?>

            </aside>
            <?php endif; ?>

            <!-- Right: Contact form ────────────────────────── -->
            <div class="<?php echo ( $has_info || $has_social || $c_map ) ? 'lg:col-span-3' : 'lg:col-span-5'; ?>">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-8">
                    <h2 class="font-bold text-iwa-ink text-xl mb-6">Send Us a Message</h2>

                    <?php if ( $form_sent ) : ?>
                    <div class="rounded-xl bg-iwa-green/10 border border-iwa-green/30 text-iwa-green px-5 py-4 text-sm font-medium">
                        Thank you! Your message has been sent. We'll get back to you shortly.
                    </div>

                    <?php else : ?>

                    <?php if ( $form_error ) : ?>
                    <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm mb-6">
                        <?php echo esc_html( $form_error ); ?>
                    </div>
                    <?php endif; ?>

                    <form method="post" class="space-y-5" novalidate>
                        <?php wp_nonce_field( 'iwa_contact_form', 'iwa_contact_nonce' ); ?>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="contact_name" class="block text-sm font-semibold text-iwa-ink mb-1.5">
                                    Full Name <span class="text-iwa-saffron">*</span>
                                </label>
                                <input type="text" id="contact_name" name="contact_name" required
                                       value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_name'] ?? '' ) ) ); ?>"
                                       placeholder="Your name"
                                       class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-iwa-ink placeholder-gray-400 focus:outline-none focus:border-iwa-saffron transition">
                            </div>
                            <div>
                                <label for="contact_email_field" class="block text-sm font-semibold text-iwa-ink mb-1.5">
                                    Email Address <span class="text-iwa-saffron">*</span>
                                </label>
                                <input type="email" id="contact_email_field" name="contact_email_field" required
                                       value="<?php echo esc_attr( sanitize_email( wp_unslash( $_POST['contact_email_field'] ?? '' ) ) ); ?>"
                                       placeholder="you@example.com"
                                       class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-iwa-ink placeholder-gray-400 focus:outline-none focus:border-iwa-saffron transition">
                            </div>
                        </div>

                        <div>
                            <label for="contact_subject" class="block text-sm font-semibold text-iwa-ink mb-1.5">
                                Subject <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <input type="text" id="contact_subject" name="contact_subject"
                                   value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_subject'] ?? '' ) ) ); ?>"
                                   placeholder="What is this about?"
                                   class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-iwa-ink placeholder-gray-400 focus:outline-none focus:border-iwa-saffron transition">
                        </div>

                        <div>
                            <label for="contact_message" class="block text-sm font-semibold text-iwa-ink mb-1.5">
                                Message <span class="text-iwa-saffron">*</span>
                            </label>
                            <textarea id="contact_message" name="contact_message" required rows="6"
                                      placeholder="Write your message here..."
                                      class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-iwa-ink placeholder-gray-400 focus:outline-none focus:border-iwa-saffron transition resize-none"><?php echo esc_textarea( sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ?? '' ) ) ); ?></textarea>
                        </div>

                        <button type="submit" name="iwa_contact_submit" value="1"
                                class="inline-flex items-center gap-2 rounded-full bg-iwa-saffron text-white px-7 py-3 font-semibold hover:bg-iwa-saffron-light transition">
                            Send Message
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5"/>
                            </svg>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
input[type="text"].focus\:border-iwa-saffron:focus,
input[type="email"].focus\:border-iwa-saffron:focus,
textarea.focus\:border-iwa-saffron:focus {
    border-color: #ff671f;
}
</style>

<?php get_footer(); ?>
