<?php
$_s    = function_exists( 'iwa_get_settings' ) ? iwa_get_settings() : [];
$_yt   = $_s['youtube_channel']    ?? 'https://www.youtube.com/@IndianWomenAbroad';
$_sp   = $_s['spotify_url']        ?? '';
$_apl  = $_s['apple_podcasts_url'] ?? '';
$_tag  = $_s['footer_tagline']     ?? 'Sharing the stories of women who often get lost in translation.';
$_st   = function_exists( 'iwa_stories_url' ) ? iwa_stories_url() : home_url( '/category/stories-indian-women-abroad/' );
$_logo = $_s['site_logo_url']      ?? '';
$_ig   = $_s['social_instagram']   ?? '';
$_tk   = $_s['social_tiktok']      ?? '';
$_fb   = $_s['social_facebook']    ?? '';
$_li   = $_s['social_linkedin']    ?? '';
$_em   = $_s['contact_email']      ?? '';
$_adr  = $_s['contact_address']    ?? '';
$_devl = $_s['dev_logo_url']       ?? '';
$_devk = $_s['dev_logo_link']      ?? '';
$_deva = $_s['dev_logo_alt']       ?? '';
?>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-iwa-ink text-white/80 pt-16 pb-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">

            <!-- ── Col 1: Brand + Social + Channels ── -->
            <div class="lg:col-span-1">

                <!-- Logo mark + site name -->
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3" style="text-decoration:none;">
                    <span style="width:42px;height:42px;border-radius:10px;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                        <?php if ( ! empty( $_logo ) ) : ?>
                            <img src="<?php echo esc_url( $_logo ); ?>" alt="IWA" style="width:36px;height:36px;object-fit:contain;">
                        <?php else : ?>
                            <span style="font-weight:800;font-size:0.85rem;color:#ff671f;font-family:'Epilogue',sans-serif;">IWA</span>
                        <?php endif; ?>
                    </span>
                    <span style="font-weight:700;font-size:0.95rem;color:#fff;line-height:1.25;">Indian Women<br>Abroad</span>
                </a>

                <!-- Tagline -->
                <p style="margin-top:1rem;font-size:0.85rem;color:rgba(255,255,255,.5);line-height:1.65;"><?php echo esc_html( $_tag ); ?></p>

                <!-- Social links as text list -->
                <?php
                $socials = [];
                if ( ! empty( $_ig ) ) $socials[] = [ 'label' => 'Instagram',  'url' => $_ig,  'hover' => '#E1306C' ];
                if ( ! empty( $_tk ) ) $socials[] = [ 'label' => 'TikTok',     'url' => $_tk,  'hover' => '#69C9D0' ];
                if ( ! empty( $_fb ) ) $socials[] = [ 'label' => 'Facebook',   'url' => $_fb,  'hover' => '#1877F2' ];
                if ( ! empty( $_li ) ) $socials[] = [ 'label' => 'LinkedIn',   'url' => $_li,  'hover' => '#0077B5' ];
                $socials[] = [ 'label' => 'YouTube', 'url' => $_yt, 'hover' => '#FF0000' ];
                ?>
                <?php if ( ! empty( $socials ) ) : ?>
                <div style="margin-top:1.25rem;">
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);font-weight:600;margin:0 0 0.6rem;">Follow us</p>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.35rem;">
                        <?php foreach ( $socials as $soc ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $soc['url'] ); ?>" target="_blank" rel="noopener"
                               style="font-size:0.875rem;color:rgba(255,255,255,.6);text-decoration:none;transition:color .15s;"
                               onmouseover="this.style.color='<?php echo esc_attr( $soc['hover'] ); ?>'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                                <?php echo esc_html( $soc['label'] ); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Channels -->
                <?php
                $channels = [];
                $channels[] = [ 'label' => 'YouTube', 'url' => $_yt, 'hover' => '#FF0000' ];
                if ( ! empty( $_sp ) )  $channels[] = [ 'label' => 'Spotify',        'url' => $_sp,  'hover' => '#1DB954' ];
                if ( ! empty( $_apl ) ) $channels[] = [ 'label' => 'Apple Podcasts', 'url' => $_apl, 'hover' => '#872EC4' ];
                ?>
                <div style="margin-top:1.5rem;">
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.35);font-weight:600;margin:0 0 0.6rem;">Channels</p>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.35rem;">
                        <?php foreach ( $channels as $ch ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $ch['url'] ); ?>" target="_blank" rel="noopener"
                               style="font-size:0.875rem;color:rgba(255,255,255,.6);text-decoration:none;transition:color .15s;"
                               onmouseover="this.style.color='<?php echo esc_attr( $ch['hover'] ); ?>'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                                <?php echo esc_html( $ch['label'] ); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>

            <!-- ── Col 2: Quick Links ── -->
            <div>
                <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo esc_url( $_st ); ?>" class="hover:text-iwa-saffron transition" style="color:rgba(255,255,255,.6);text-decoration:none;">Stories</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#podcast' ) ); ?>" class="hover:text-iwa-green transition" style="color:rgba(255,255,255,.6);text-decoration:none;">Podcast</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>" class="hover:text-iwa-blue transition" style="color:rgba(255,255,255,.6);text-decoration:none;">About</a></li>
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <li><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="hover:text-white transition" style="color:rgba(255,255,255,.6);text-decoration:none;">Shop</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="hover:text-white transition" style="color:rgba(255,255,255,.6);text-decoration:none;">Contact</a></li>
                </ul>
            </div>

            <!-- ── Col 3: Connect with Us ── -->
            <div>
                <h4 class="font-semibold text-white mb-4">Connect with Us</h4>
                <ul style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:0.75rem;">

                    <?php if ( ! empty( $_em ) ) : ?>
                    <li style="display:flex;align-items:flex-start;gap:0.6rem;">
                        <!-- Email icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:rgba(255,255,255,.35);flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:<?php echo esc_attr( $_em ); ?>"
                           style="font-size:0.875rem;color:rgba(255,255,255,.6);text-decoration:none;word-break:break-all;transition:color .15s;"
                           onmouseover="this.style.color='#ff671f'" onmouseout="this.style.color='rgba(255,255,255,.6)'">
                            <?php echo esc_html( $_em ); ?>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if ( ! empty( $_adr ) ) : ?>
                    <li style="display:flex;align-items:flex-start;gap:0.6rem;">
                        <!-- Location icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;color:rgba(255,255,255,.35);flex-shrink:0;margin-top:2px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span style="font-size:0.875rem;color:rgba(255,255,255,.6);line-height:1.6;white-space:pre-line;">
                            <?php echo nl2br( esc_html( $_adr ) ); ?>
                        </span>
                    </li>
                    <?php endif; ?>

                    <?php if ( empty( $_em ) && empty( $_adr ) ) : ?>
                    <li style="font-size:0.875rem;color:rgba(255,255,255,.3);font-style:italic;">
                        Add contact details in IWA Settings.
                    </li>
                    <?php endif; ?>

                </ul>
            </div>

            <!-- ── Col 4: Newsletter ── -->
            <div>
                <h4 class="font-semibold text-white mb-4">Newsletter</h4>
                <p class="text-sm mb-3" style="color:rgba(255,255,255,.5);">Get our best reads right to your inbox.</p>
                <form class="iwa-sub-form" data-source="footer" style="display:flex;flex-direction:column;gap:0.6rem;">
                    <!-- First Name + Last Name -->
                    <div style="display:flex;gap:0.5rem;">
                        <div style="flex:1;">
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:0.3rem;">First Name</label>
                            <input type="text" name="fname" placeholder="First name"
                                   style="width:100%;border-radius:0.5rem;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.07);padding:0.6rem 0.75rem;color:#fff;font-size:0.8rem;outline:none;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='rgba(255,255,255,.2)'">
                        </div>
                        <div style="flex:1;">
                            <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:0.3rem;">Last Name</label>
                            <input type="text" name="lname" placeholder="Last name"
                                   style="width:100%;border-radius:0.5rem;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.07);padding:0.6rem 0.75rem;color:#fff;font-size:0.8rem;outline:none;box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='rgba(255,255,255,.2)'">
                        </div>
                    </div>
                    <!-- Email -->
                    <div>
                        <label style="display:block;font-size:0.75rem;font-weight:600;color:rgba(255,255,255,.55);margin-bottom:0.3rem;">Email Address <span style="color:#ff671f;">*</span></label>
                        <input type="email" name="email" placeholder="you@example.com" required
                               style="width:100%;border-radius:0.5rem;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.07);padding:0.6rem 0.75rem;color:#fff;font-size:0.8rem;outline:none;box-sizing:border-box;"
                               onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='rgba(255,255,255,.2)'">
                    </div>
                    <!-- Consent -->
                    <label style="display:flex;align-items:flex-start;gap:0.5rem;cursor:pointer;">
                        <input type="checkbox" name="consent" required
                               style="margin-top:3px;width:14px;height:14px;accent-color:#ff671f;flex-shrink:0;">
                        <span style="font-size:0.72rem;color:rgba(255,255,255,.45);line-height:1.5;">
                            I agree to receive newsletters &amp; updates from Indian Women Abroad.
                        </span>
                    </label>
                    <button type="submit"
                            style="width:100%;border-radius:0.5rem;background:#ff671f;color:#fff;border:none;padding:0.65rem;font-size:0.875rem;font-weight:600;cursor:pointer;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif;"
                            onmouseover="this.style.background='#ff8a4c'" onmouseout="this.style.background='#ff671f'">
                        Subscribe
                    </button>
                    <div class="iwa-sub-msg" style="display:none;padding:0.5rem 0.75rem;border-radius:0.4rem;font-size:0.8rem;font-weight:500;"></div>
                </form>
            </div>

        </div><!-- /grid -->

        <!-- Bottom bar -->
        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm" style="color:rgba(255,255,255,.4);">&copy; <?php echo date( 'Y' ); ?> Indian Women Abroad. All rights reserved.</p>
            <div class="flex items-center gap-6 text-sm flex-wrap justify-center">
                <a href="#" style="color:rgba(255,255,255,.4);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='rgba(255,255,255,.8)'" onmouseout="this.style.color='rgba(255,255,255,.4)'">Privacy</a>
                <a href="#" style="color:rgba(255,255,255,.4);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='rgba(255,255,255,.8)'" onmouseout="this.style.color='rgba(255,255,255,.4)'">Terms</a>
                <a href="https://kautukbhatnagar.com" target="_blank" rel="noopener"
                   style="color:rgba(255,255,255,.3);text-decoration:none;transition:color .15s;" onmouseover="this.style.color='rgba(255,255,255,.6)'" onmouseout="this.style.color='rgba(255,255,255,.3)'">Custom WordPress Theme</a>
                <?php if ( ! empty( $_devl ) ) : ?>
                <a href="<?php echo esc_url( $_devk ?: '#' ); ?>" target="_blank" rel="noopener" style="display:flex;align-items:center;">
                    <img src="<?php echo esc_url( $_devl ); ?>" alt="<?php echo esc_attr( $_deva ); ?>" style="height:22px;width:auto;filter:brightness(0) invert(1);opacity:.45;">
                </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
