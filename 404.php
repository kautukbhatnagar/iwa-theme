<?php
/**
 * 404 — Page Not Found
 */
get_header();
?>

<main style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:4rem 1.5rem;background:#fafaf8;position:relative;overflow:hidden;">

    <!-- Decorative blobs -->
    <div style="position:absolute;top:-80px;right:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(255,103,31,.08) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;left:-60px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(22,101,52,.07) 0%,transparent 70%);pointer-events:none;"></div>

    <div style="max-width:640px;width:100%;text-align:center;position:relative;z-index:1;">

        <!-- 404 Number -->
        <div style="font-size:clamp(6rem,20vw,10rem);font-weight:900;line-height:1;letter-spacing:-0.05em;background:linear-gradient(135deg,#ff671f 0%,#ff9a5c 50%,#1a1a2e 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;user-select:none;">
            404
        </div>

        <!-- IWA badge -->
        <div style="display:inline-flex;align-items:center;gap:0.5rem;background:#fff;border:1.5px solid #e5e7eb;border-radius:9999px;padding:0.35rem 1rem;margin:0.5rem 0 1.5rem;box-shadow:0 1px 4px rgba(0,0,0,.06);">
            <span style="width:20px;height:20px;border-radius:6px;background:#ff671f;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:0.6rem;font-weight:800;">IWA</span>
            <span style="font-size:0.8rem;font-weight:600;color:#6b7280;">Indian Women Abroad</span>
        </div>

        <!-- Headline -->
        <h1 style="font-size:clamp(1.5rem,4vw,2.25rem);font-weight:800;color:#1a1a2e;line-height:1.2;margin:0 0 1rem;">
            This page got lost in translation
        </h1>

        <p style="font-size:1rem;color:#6b7280;line-height:1.7;margin:0 0 2.5rem;max-width:480px;display:inline-block;">
            The story you're looking for doesn't live here anymore — or maybe it never did.
            Let's get you back to the stories that matter.
        </p>

        <!-- CTA buttons -->
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;margin-bottom:3rem;">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               style="display:inline-flex;align-items:center;gap:0.5rem;background:#ff671f;color:#fff;padding:0.8rem 1.75rem;border-radius:9999px;font-size:0.95rem;font-weight:700;text-decoration:none;transition:background .15s;box-shadow:0 4px 14px rgba(255,103,31,.3);"
               onmouseover="this.style.background='#ff8a4c'" onmouseout="this.style.background='#ff671f'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7m-9 10V11m0 0H6m5 0h5"/></svg>
                Go Home
            </a>
            <a href="<?php echo esc_url( home_url( '/#podcast' ) ); ?>"
               style="display:inline-flex;align-items:center;gap:0.5rem;background:#fff;color:#1a1a2e;padding:0.8rem 1.75rem;border-radius:9999px;font-size:0.95rem;font-weight:700;text-decoration:none;border:1.5px solid #e5e7eb;transition:border-color .15s;"
               onmouseover="this.style.borderColor='#ff671f';this.style.color='#ff671f'" onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#1a1a2e'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                Latest Episodes
            </a>
        </div>

        <!-- Quick links -->
        <div style="border-top:1px solid #e5e7eb;padding-top:2rem;">
            <p style="font-size:0.8rem;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:600;margin:0 0 1rem;">Or explore</p>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;justify-content:center;">
                <?php
                $quick_links = [
                    [ 'label' => 'Stories',   'url' => home_url( '/category/stories-indian-women-abroad/' ) ],
                    [ 'label' => 'Podcast',   'url' => home_url( '/#podcast' ) ],
                    [ 'label' => 'About',     'url' => home_url( '/#about' ) ],
                    [ 'label' => 'Shop',      'url' => home_url( '/shop/' ) ],
                    [ 'label' => 'Contact',   'url' => home_url( '/contact/' ) ],
                ];
                foreach ( $quick_links as $link ) :
                ?>
                <a href="<?php echo esc_url( $link['url'] ); ?>"
                   style="background:#f3f4f6;color:#374151;padding:0.4rem 1rem;border-radius:9999px;font-size:0.85rem;font-weight:500;text-decoration:none;transition:background .15s,color .15s;"
                   onmouseover="this.style.background='#fff0e8';this.style.color='#ff671f'" onmouseout="this.style.background='#f3f4f6';this.style.color='#374151'">
                    <?php echo esc_html( $link['label'] ); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</main>

<?php get_footer(); ?>
