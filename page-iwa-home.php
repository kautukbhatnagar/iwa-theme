<?php
/**
 * Template Name: IWA Home
 * Template Post Type: page
 */

// ── Settings ─────────────────────────────────────────────────
$s = function_exists( 'iwa_get_settings' ) ? iwa_get_settings() : [];

$founder_name        = $s['hero_founder_name']   ?? 'Mrinaal Datt';
$founder_title       = $s['hero_founder_title']  ?? 'Founder & Host';
$hero_description    = $s['hero_description']    ?? 'Founded by Mrinaal Datt, we amplify the voices of Indian women living abroad. Through podcasts and in-depth stories, we celebrate their journeys, challenges, and triumphs.';
$about_headline      = $s['about_headline']      ?? 'Empowering the Indian woman to own her global narrative.';
$about_body1         = $s['about_body1']         ?? 'We believe every woman has a story worth telling. From boardrooms to kitchen tables, from Bangalore to Boston—your experiences shape a collective voice that deserves to be heard.';
$about_body2         = $s['about_body2']         ?? 'Through our podcast, articles, and community, we create space for honest conversations about identity, ambition, and belonging.';
$stat1_value         = $s['stat1_value']         ?? '8M+';
$stat1_label         = $s['stat1_label']         ?? 'Indian women leave the country annually';
$stat2_value         = $s['stat2_value']         ?? '20+';
$stat2_label         = $s['stat2_label']         ?? 'Stories told on the platform';
$about_image_url     = $s['about_image_url']     ?? '';
$about_card_text     = $s['about_card_text']     ?? 'Join our vibrant community. Be part of a global sisterhood.';
$about_btn_text      = $s['about_btn_text']      ?? 'Join the Community';
$about_btn_url       = ! empty( $s['about_btn_url'] ) ? $s['about_btn_url'] : '#subscribe';
$youtube_channel     = $s['youtube_channel']     ?? 'https://www.youtube.com/@IndianWomenAbroad';
$spotify_url         = $s['spotify_url']         ?? '';
$apple_podcasts_url  = $s['apple_podcasts_url']  ?? '';
$newsletter_headline = $s['newsletter_headline'] ?? '&ldquo;Stories that feel like home, wherever you are.&rdquo;';
$newsletter_subtext  = $s['newsletter_subtext']  ?? 'Subscribe to our newsletter for the latest episodes, articles, and community updates.';
$footer_tagline      = $s['footer_tagline']      ?? 'Sharing the stories of women who often get lost in translation.';

// ── Podcast Episodes ─────────────────────────────────────────
$episodes_hardcoded = [
    [ 'id' => 'npdoMGcOh7c', 'url' => 'https://www.youtube.com/watch?v=npdoMGcOh7c', 'title' => 'Episode 1',  'topic' => 'stories'  ],
    [ 'id' => 'm_5Zn51bmWQ', 'url' => 'https://www.youtube.com/watch?v=m_5Zn51bmWQ', 'title' => 'Episode 2',  'topic' => 'culture'  ],
    [ 'id' => '35pPgvaaanY', 'url' => 'https://www.youtube.com/watch?v=35pPgvaaanY', 'title' => 'Episode 3',  'topic' => 'career'   ],
    [ 'id' => 'iMSnclkjofA', 'url' => 'https://www.youtube.com/watch?v=iMSnclkjofA', 'title' => 'Episode 4',  'topic' => 'travel'   ],
    [ 'id' => 'r7RtsxloRhM', 'url' => 'https://www.youtube.com/watch?v=r7RtsxloRhM', 'title' => 'Episode 5',  'topic' => 'wellness' ],
    [ 'id' => 'CTAU_gV8u6s', 'url' => 'https://www.youtube.com/watch?v=CTAU_gV8u6s', 'title' => 'Episode 6',  'topic' => 'finance'  ],
    [ 'id' => 'NN4BdwCRCJg', 'url' => 'https://www.youtube.com/watch?v=NN4BdwCRCJg', 'title' => 'Episode 7',  'topic' => 'stories'  ],
    [ 'id' => 'sHSYWLxuxZQ', 'url' => 'https://www.youtube.com/watch?v=sHSYWLxuxZQ', 'title' => 'Episode 8',  'topic' => 'culture'  ],
    [ 'id' => '0BlH9YC2TJM', 'url' => 'https://www.youtube.com/watch?v=0BlH9YC2TJM', 'title' => 'Episode 9',  'topic' => 'career'   ],
    [ 'id' => 'Dq_vZD7dP8o', 'url' => 'https://www.youtube.com/watch?v=Dq_vZD7dP8o', 'title' => 'Episode 10', 'topic' => 'travel'   ],
];

if ( post_type_exists( 'iwa_episode' ) ) {
    $ep_posts = get_posts( [ 'post_type' => 'iwa_episode', 'numberposts' => -1, 'orderby' => 'date', 'order' => 'DESC' ] );
    if ( ! empty( $ep_posts ) ) {
        $episodes = array_map( function( $p ) {
            $yt = get_post_meta( $p->ID, 'iwa_youtube_id', true );
            return [
                'id'      => $yt,
                'url'     => 'https://www.youtube.com/watch?v=' . $yt,
                'title'   => $p->post_title,
                'topic'   => get_post_meta( $p->ID, 'iwa_topic',       true ) ?: 'stories',
                'spotify' => get_post_meta( $p->ID, 'iwa_spotify_url', true ) ?: '',
                'apple'   => get_post_meta( $p->ID, 'iwa_apple_url',   true ) ?: '',
            ];
        }, $ep_posts );
    } else {
        $episodes = $episodes_hardcoded;
    }
} else {
    $episodes = $episodes_hardcoded;
}

// Latest episode = first in DESC-ordered list (most recently published)
$latest_episode = ! empty( $episodes ) ? $episodes[0] : $episodes_hardcoded[0];

// ── Stories — categories from admin settings (or fallback) ──────────────────
$home_cat_ids = array_filter( array_map( 'intval', explode( ',', $s['home_story_categories'] ?? '' ) ) );

if ( empty( $home_cat_ids ) ) {
    // Fallback: stories-indian-women-abroad category
    $fb_cat = get_category_by_slug( 'stories-indian-women-abroad' );
    if ( $fb_cat ) $home_cat_ids = [ $fb_cat->term_id ];
}

$featured_post = null;
$grid_posts    = [];
$brief_posts   = [];

if ( ! empty( $home_cat_ids ) ) {
    $home_story_posts = get_posts( [
        'category__in' => $home_cat_ids,
        'numberposts'  => 10,
        'orderby'      => 'date',
        'order'        => 'DESC',
    ] );
    if ( ! empty( $home_story_posts ) ) {
        $featured_post = $home_story_posts[0];
        $grid_posts    = array_slice( $home_story_posts, 1, 4 );
        $brief_posts   = array_slice( $home_story_posts, 5, 5 );
    }
}

$theme_uri  = get_stylesheet_directory_uri();
$stories_url = function_exists( 'iwa_stories_url' ) ? iwa_stories_url() : home_url( '/category/stories-indian-women-abroad/' );
?>
<?php get_header(); ?>
<style>#podcast-slider::-webkit-scrollbar{display:none;}</style>

<!-- ===================== HERO — LATEST EPISODE ===================== -->
<section id="home" class="bg-iwa-ink py-16 lg:py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            <!-- Left: episode info -->
            <div>
                <div class="inline-flex items-center gap-2 text-iwa-saffron">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                    </svg>
                    <span class="text-xs font-semibold uppercase tracking-widest">Latest Episode</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mt-4">
                    <?php echo esc_html( $latest_episode['title'] ); ?>
                </h1>

                <p class="mt-3 text-white/60 font-medium text-sm">Indian Women Abroad</p>

                <!-- Listen / Watch buttons -->
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="<?php echo esc_url( $latest_episode['url'] ); ?>" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-[#ff0000] text-white px-5 py-3 text-sm font-semibold hover:bg-[#cc0000] transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        Watch on YouTube
                    </a>

                    <?php if ( ! empty( $latest_episode['spotify'] ) ) : ?>
                    <a href="<?php echo esc_url( $latest_episode['spotify'] ); ?>" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-[#1DB954] text-white px-5 py-3 text-sm font-semibold hover:bg-[#1aa34a] transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                        Listen on Spotify
                    </a>
                    <?php endif; ?>

                    <?php if ( ! empty( $latest_episode['apple'] ) ) : ?>
                    <a href="<?php echo esc_url( $latest_episode['apple'] ); ?>" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-full bg-[#872EC4] text-white px-5 py-3 text-sm font-semibold hover:bg-[#7a29b0] transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 4.102a7.898 7.898 0 0 1 2.786 15.273c.07-.537.14-1.38.014-1.99l-1.037-4.383c.266-.532.399-1.134.399-1.753 0-1.686-.984-2.945-2.406-2.945-1.135 0-1.686.853-1.686 1.876 0 1.142.732 2.852.732 4.31 0 1.225-.657 2.208-2.026 2.208-1.464 0-2.548-1.546-2.548-3.78 0-1.974 1.435-3.36 3.487-3.36 2.618 0 4.152 1.96 4.152 4.315 0 2.617-1.646 4.712-3.94 4.712-.77 0-1.492-.4-1.74-.875l-.47 1.75c-.172.66-.634 1.484-.945 1.988A7.9 7.9 0 0 1 12 19.898a7.898 7.898 0 0 1 0-15.796z"/></svg>
                        Apple Podcasts
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Hosted by -->
                <div class="mt-10 pt-8 border-t border-white/10 flex items-center gap-3">
                    <img src="<?php echo esc_url( $theme_uri . '/images/hero-2.jpg' ); ?>"
                         alt="<?php echo esc_attr( $founder_name ); ?>"
                         class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                    <div>
                        <p class="text-xs text-white/50">Hosted by</p>
                        <p class="font-semibold text-white text-sm"><?php echo esc_html( $founder_name ); ?></p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-xs text-white/50"><?php echo count( $episodes ); ?> episodes</p>
                        <a href="#podcast" class="text-xs text-iwa-saffron hover:text-iwa-saffron-light transition">View all &darr;</a>
                    </div>
                </div>
            </div>

            <!-- Right: YouTube thumbnail -->
            <div>
                <a href="<?php echo esc_url( $latest_episode['url'] ); ?>" target="_blank" rel="noopener"
                   class="block relative aspect-video rounded-2xl overflow-hidden group shadow-2xl">
                    <!-- maxresdefault with hqdefault as fallback -->
                    <img id="hero-thumb"
                         src="https://img.youtube.com/vi/<?php echo esc_attr( $latest_episode['id'] ); ?>/maxresdefault.jpg"
                         alt="<?php echo esc_attr( $latest_episode['title'] ); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <!-- Play overlay -->
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition flex items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center shadow-xl group-hover:scale-110 transition">
                            <svg class="w-7 h-7 ml-1 text-iwa-ink" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Platform badges under thumbnail -->
                <?php if ( ! empty( $latest_episode['spotify'] ) || ! empty( $latest_episode['apple'] ) ) : ?>
                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-white/50">
                    <span>Also on</span>
                    <?php if ( ! empty( $latest_episode['spotify'] ) ) : ?>
                    <a href="<?php echo esc_url( $latest_episode['spotify'] ); ?>" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 text-white/70 hover:text-[#1DB954] transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                        Spotify
                    </a>
                    <?php endif; ?>
                    <?php if ( ! empty( $latest_episode['apple'] ) ) : ?>
                    <a href="<?php echo esc_url( $latest_episode['apple'] ); ?>" target="_blank" rel="noopener"
                       class="flex items-center gap-1.5 text-white/70 hover:text-[#872EC4] transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 4.102a7.898 7.898 0 0 1 2.786 15.273c.07-.537.14-1.38.014-1.99l-1.037-4.383c.266-.532.399-1.134.399-1.753 0-1.686-.984-2.945-2.406-2.945-1.135 0-1.686.853-1.686 1.876 0 1.142.732 2.852.732 4.31 0 1.225-.657 2.208-2.026 2.208-1.464 0-2.548-1.546-2.548-3.78 0-1.974 1.435-3.36 3.487-3.36 2.618 0 4.152 1.96 4.152 4.315 0 2.617-1.646 4.712-3.94 4.712-.77 0-1.492-.4-1.74-.875l-.47 1.75c-.172.66-.634 1.484-.945 1.988A7.9 7.9 0 0 1 12 19.898a7.898 7.898 0 0 1 0-15.796z"/></svg>
                        Apple Podcasts
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== STORIES ===================== -->
<section id="stories" class="py-16 lg:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl lg:text-4xl font-bold text-iwa-ink">Read the latest stories</h2>
        <p class="mt-2 text-iwa-ink-soft">In-depth articles and narratives of Indian women abroad.</p>
        <div class="mt-10 grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Featured story -->
                <?php if ( $featured_post ) :
                    $fp_url    = get_permalink( $featured_post->ID );
                    $fp_img    = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( $featured_post->ID ) : get_the_post_thumbnail_url( $featured_post->ID, 'large' );
                    $fp_date   = function_exists( 'iwa_format_date' ) ? iwa_format_date( $featured_post->ID ) : get_the_date( 'j M', $featured_post->ID );
                    $fp_author = get_the_author_meta( 'display_name', $featured_post->post_author );
                    $fp_excerpt = has_excerpt( $featured_post->ID )
                        ? get_the_excerpt( $featured_post->ID )
                        : wp_trim_words( $featured_post->post_content, 30, '&hellip;' );
                ?>
                <article class="rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm">
                    <?php if ( $fp_img ) : ?>
                    <a href="<?php echo esc_url( $fp_url ); ?>" class="block aspect-video">
                        <img src="<?php echo esc_url( $fp_img ); ?>" alt="" class="w-full h-full object-cover hover:opacity-95 transition">
                    </a>
                    <?php endif; ?>
                    <div class="p-6 lg:p-8">
                        <p class="text-xs text-iwa-ink-soft mb-2"><?php echo esc_html( $fp_date ); ?><?php if ( $fp_author ) echo ' &middot; ' . esc_html( $fp_author ); ?></p>
                        <h3 class="text-2xl lg:text-3xl font-bold text-iwa-ink leading-tight">
                            <a href="<?php echo esc_url( $fp_url ); ?>" class="hover:text-iwa-green transition">
                                <?php echo esc_html( $featured_post->post_title ); ?>
                            </a>
                        </h3>
                        <?php if ( $fp_excerpt ) : ?>
                        <p class="mt-4 text-iwa-ink-soft leading-relaxed"><?php echo esc_html( $fp_excerpt ); ?></p>
                        <?php endif; ?>
                        <p class="mt-4">
                            <a href="<?php echo esc_url( $fp_url ); ?>"
                               class="text-iwa-saffron font-semibold hover:text-iwa-saffron-light transition">Read More &rarr;</a>
                        </p>
                    </div>
                </article>
                <?php endif; ?>
                <!-- Grid -->
                <?php if ( ! empty( $grid_posts ) ) : ?>
                <div class="grid sm:grid-cols-2 gap-6">
                    <?php foreach ( $grid_posts as $gp ) :
                        $gp_url    = get_permalink( $gp->ID );
                        $gp_img    = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( $gp->ID ) : get_the_post_thumbnail_url( $gp->ID, 'medium' );
                        $gp_author = get_the_author_meta( 'display_name', $gp->post_author );
                    ?>
                    <article class="rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-md hover:border-iwa-blue/20 transition group">
                        <?php if ( $gp_img ) : ?>
                        <a href="<?php echo esc_url( $gp_url ); ?>" class="block aspect-[4/3] overflow-hidden">
                            <img src="<?php echo esc_url( $gp_img ); ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </a>
                        <?php endif; ?>
                        <div class="p-4">
                            <h4 class="font-bold text-iwa-ink group-hover:text-iwa-saffron transition line-clamp-2">
                                <a href="<?php echo esc_url( $gp_url ); ?>">
                                    <?php echo esc_html( $gp->post_title ); ?>
                                </a>
                            </h4>
                            <?php if ( $gp_author ) : ?>
                            <p class="text-sm text-iwa-ink-soft mt-1">By <?php echo esc_html( $gp_author ); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo esc_url( $gp_url ); ?>"
                               class="mt-2 text-sm font-medium text-iwa-green hover:text-iwa-green-light transition">Read More</a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <!-- Sidebar: The Brief -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 rounded-2xl bg-gray-50 border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-iwa-ink">The Brief</h3>
                        <a href="<?php echo esc_url( $stories_url ); ?>"
                           class="text-iwa-saffron font-semibold text-sm hover:text-iwa-saffron-light transition">View All</a>
                    </div>
                    <?php if ( ! empty( $brief_posts ) ) : ?>
                    <ul class="mt-6 space-y-4">
                        <?php foreach ( $brief_posts as $bp ) : ?>
                        <li class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <a href="<?php echo esc_url( get_permalink( $bp->ID ) ); ?>"
                               class="font-medium text-iwa-ink hover:text-iwa-blue transition line-clamp-2">
                                <?php echo esc_html( $bp->post_title ); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else : ?>
                    <p class="mt-6 text-sm text-iwa-ink-soft">More stories coming soon.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <p class="mt-10 text-center">
            <a href="<?php echo esc_url( $stories_url ); ?>"
               class="text-iwa-saffron font-semibold hover:text-iwa-saffron-light transition">
               Read all stories &rarr;
            </a>
        </p>
    </div>
</section>

<!-- ===================== PODCAST HUB ===================== -->
<section id="podcast" class="py-16 lg:py-24 bg-[#f7fef9]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-iwa-ink">The Podcast Hub</h2>
                <p class="mt-2 text-iwa-ink-soft max-w-2xl">All episodes, curated by topic. Also available on your favourite platform.</p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="<?php echo esc_url( $youtube_channel ); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full bg-[#ff0000] text-white px-4 py-2 text-sm font-semibold hover:bg-[#cc0000] transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    YouTube
                </a>
                <?php if ( ! empty( $spotify_url ) ) : ?>
                <a href="<?php echo esc_url( $spotify_url ); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full bg-[#1DB954] text-white px-4 py-2 text-sm font-semibold hover:bg-[#1aa34a] transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                    Spotify
                </a>
                <?php endif; ?>
                <?php if ( ! empty( $apple_podcasts_url ) ) : ?>
                <a href="<?php echo esc_url( $apple_podcasts_url ); ?>" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded-full bg-[#872EC4] text-white px-4 py-2 text-sm font-semibold hover:bg-[#7a29b0] transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 4.102a7.898 7.898 0 0 1 2.786 15.273c.07-.537.14-1.38.014-1.99l-1.037-4.383c.266-.532.399-1.134.399-1.753 0-1.686-.984-2.945-2.406-2.945-1.135 0-1.686.853-1.686 1.876 0 1.142.732 2.852.732 4.31 0 1.225-.657 2.208-2.026 2.208-1.464 0-2.548-1.546-2.548-3.78 0-1.974 1.435-3.36 3.487-3.36 2.618 0 4.152 1.96 4.152 4.315 0 2.617-1.646 4.712-3.94 4.712-.77 0-1.492-.4-1.74-.875l-.47 1.75c-.172.66-.634 1.484-.945 1.988A7.9 7.9 0 0 1 12 19.898a7.898 7.898 0 0 1 0-15.796z"/></svg>
                    Apple Podcasts
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filters + Nav -->
        <div class="mt-8 flex flex-wrap items-center gap-3">
            <div class="flex flex-wrap gap-2" id="podcast-filters" role="tablist">
                <button type="button" role="tab" aria-selected="true" data-filter="all"
                        class="podcast-filter rounded-full bg-iwa-saffron text-white px-4 py-2 text-sm font-medium">All</button>
                <?php
                $raw_topics = array_filter( array_map( 'trim', explode( "\n", $s['podcast_topics'] ?? "stories\ntravel\nculture\ncareer\nfinance\nwellness" ) ) );
                foreach ( $raw_topics as $t ) : ?>
                <button type="button" role="tab" aria-selected="false"
                        data-filter="<?php echo esc_attr( $t ); ?>"
                        class="podcast-filter rounded-full border border-gray-200 px-4 py-2 text-sm font-medium text-iwa-ink-soft hover:border-iwa-blue hover:text-iwa-blue transition">
                    <?php echo esc_html( ucfirst( $t ) ); ?>
                </button>
                <?php endforeach; ?>
            </div>
            <div class="flex items-center gap-0.5 ml-auto">
                <button type="button" id="podcast-prev" class="p-1.5 text-iwa-ink-soft hover:text-iwa-ink transition disabled:opacity-40 disabled:pointer-events-none" aria-label="Previous">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="podcast-next" class="p-1.5 text-iwa-ink-soft hover:text-iwa-ink transition disabled:opacity-40 disabled:pointer-events-none" aria-label="Next">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Slider -->
        <div class="mt-10 -mx-4 sm:mx-0 relative">
            <div id="podcast-slider"
                 class="flex gap-6 overflow-x-auto overflow-y-hidden snap-x snap-mandatory scroll-smooth px-4 sm:px-0"
                 style="-ms-overflow-style:none;scrollbar-width:none;">
                <?php foreach ( $episodes as $episode ) :
                    if ( empty( $episode['id'] ) ) continue; ?>
                <article class="podcast-card flex-shrink-0 w-72 snap-start rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-lg hover:border-iwa-green/30 transition"
                         data-topic="<?php echo esc_attr( $episode['topic'] ); ?>">
                    <a href="<?php echo esc_url( $episode['url'] ); ?>" target="_blank" rel="noopener" class="block relative aspect-video group">
                        <img src="https://img.youtube.com/vi/<?php echo esc_attr( $episode['id'] ); ?>/hqdefault.jpg"
                             alt="<?php echo esc_attr( $episode['title'] ); ?>"
                             class="w-full h-full object-cover" loading="lazy"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23f3f4f6%22 width=%22400%22 height=%22300%22/%3E%3C/svg%3E'">
                        <div class="absolute inset-0 bg-black/20 hover:bg-black/30 flex items-center justify-center transition">
                            <div class="w-14 h-14 rounded-full bg-iwa-saffron flex items-center justify-center text-white shadow-lg">
                                <svg class="w-7 h-7 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="p-4">
                        <h3 class="font-bold text-iwa-ink text-sm leading-snug">
                            <a href="<?php echo esc_url( $episode['url'] ); ?>" target="_blank" rel="noopener">
                                <?php echo esc_html( $episode['title'] ); ?>
                            </a>
                        </h3>
                        <div class="mt-2 flex items-center justify-between">
                            <p class="text-xs text-iwa-ink-soft">Indian Women Abroad</p>
                            <span class="text-xs text-iwa-ink-soft/70 bg-gray-100 rounded-full px-2 py-0.5">
                                <?php echo esc_html( ucfirst( $episode['topic'] ) ); ?>
                            </span>
                        </div>
                        <?php if ( ! empty( $episode['spotify'] ) || ! empty( $episode['apple'] ) ) : ?>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
                            <span class="text-[10px] text-iwa-ink-soft/60 mr-0.5">Also on</span>
                            <?php if ( ! empty( $episode['spotify'] ) ) : ?>
                            <a href="<?php echo esc_url( $episode['spotify'] ); ?>" target="_blank" rel="noopener"
                               title="Listen on Spotify"
                               class="flex items-center gap-1 rounded-full bg-[#1DB954]/10 text-[#1DB954] hover:bg-[#1DB954] hover:text-white px-2.5 py-1 text-[10px] font-semibold transition">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                                Spotify
                            </a>
                            <?php endif; ?>
                            <?php if ( ! empty( $episode['apple'] ) ) : ?>
                            <a href="<?php echo esc_url( $episode['apple'] ); ?>" target="_blank" rel="noopener"
                               title="Listen on Apple Podcasts"
                               class="flex items-center gap-1 rounded-full bg-[#872EC4]/10 text-[#872EC4] hover:bg-[#872EC4] hover:text-white px-2.5 py-1 text-[10px] font-semibold transition">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 4.102a7.898 7.898 0 0 1 2.786 15.273c.07-.537.14-1.38.014-1.99l-1.037-4.383c.266-.532.399-1.134.399-1.753 0-1.686-.984-2.945-2.406-2.945-1.135 0-1.686.853-1.686 1.876 0 1.142.732 2.852.732 4.31 0 1.225-.657 2.208-2.026 2.208-1.464 0-2.548-1.546-2.548-3.78 0-1.974 1.435-3.36 3.487-3.36 2.618 0 4.152 1.96 4.152 4.315 0 2.617-1.646 4.712-3.94 4.712-.77 0-1.492-.4-1.74-.875l-.47 1.75c-.172.66-.634 1.484-.945 1.988A7.9 7.9 0 0 1 12 19.898a7.898 7.898 0 0 1 0-15.796z"/></svg>
                                Apple
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <?php $episodes_url = ! empty( $s['episodes_url'] ) ? $s['episodes_url'] : $youtube_channel; ?>
        <div class="mt-10 text-center">
            <a href="<?php echo esc_url( $episodes_url ); ?>" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 rounded-full border-2 border-iwa-saffron text-iwa-saffron px-6 py-3 font-semibold hover:bg-iwa-saffron hover:text-white transition">
                View All Episodes
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Thumbnail fallback: maxresdefault → hqdefault
    var heroThumb = document.getElementById('hero-thumb');
    if (heroThumb) {
        heroThumb.onerror = function() {
            this.src = this.src.replace('maxresdefault', 'hqdefault');
            this.onerror = null;
        };
    }
    // Podcast filter
    var filters = document.querySelectorAll('.podcast-filter');
    var cards   = document.querySelectorAll('.podcast-card');
    var slider  = document.getElementById('podcast-slider');
    if (!filters.length || !cards.length || !slider) return;
    function filterCards(v) {
        cards.forEach(function (c) {
            c.style.display = (v === 'all' || c.getAttribute('data-topic') === v) ? '' : 'none';
        });
        filters.forEach(function (btn) {
            var active = btn.getAttribute('data-filter') === v;
            btn.setAttribute('aria-selected', active);
            if (active) {
                btn.classList.add('bg-iwa-saffron', 'text-white');
                btn.classList.remove('border', 'border-gray-200', 'text-iwa-ink-soft');
            } else {
                btn.classList.remove('bg-iwa-saffron', 'text-white');
                btn.classList.add('border', 'border-gray-200', 'text-iwa-ink-soft');
            }
        });
    }
    filters.forEach(function (btn) {
        btn.addEventListener('click', function () { filterCards(this.getAttribute('data-filter')); });
    });
});
(function () {
    var slider = document.getElementById('podcast-slider');
    var prev   = document.getElementById('podcast-prev');
    var next   = document.getElementById('podcast-next');
    if (!slider || !prev || !next) return;
    var cardW = 288 + 24;
    function upd() {
        prev.disabled = slider.scrollLeft <= 0;
        next.disabled = slider.scrollLeft >= slider.scrollWidth - slider.clientWidth - 2;
    }
    slider.addEventListener('scroll', upd);
    prev.addEventListener('click', function () { slider.scrollBy({ left: -cardW, behavior: 'smooth' }); });
    next.addEventListener('click', function () { slider.scrollBy({ left: cardW,  behavior: 'smooth' }); });
    upd();
})();
</script>

<!-- ===================== ABOUT / FOUNDER ===================== -->
<section id="about" class="py-16 lg:py-24 bg-iwa-blue text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold leading-tight">
                    <?php echo esc_html( $about_headline ); ?>
                </h2>
                <p class="mt-6 text-white/90 leading-relaxed"><?php echo esc_html( $about_body1 ); ?></p>
                <p class="mt-4 text-white/90 leading-relaxed"><?php echo esc_html( $about_body2 ); ?></p>
                <div class="mt-10 flex flex-wrap gap-10">
                    <div>
                        <p class="text-4xl font-bold text-iwa-saffron"><?php echo esc_html( $stat1_value ); ?></p>
                        <p class="text-white/70 mt-1"><?php echo esc_html( $stat1_label ); ?></p>
                    </div>
                    <div>
                        <p class="text-4xl font-bold text-iwa-green"><?php echo esc_html( $stat2_value ); ?></p>
                        <p class="text-white/70 mt-1"><?php echo esc_html( $stat2_label ); ?></p>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="<?php echo esc_url( ! empty( $about_image_url ) ? $about_image_url : $theme_uri . '/images/hero-2.jpg' ); ?>"
                     alt="<?php echo esc_attr( $founder_name ); ?>"
                     class="rounded-2xl w-full object-cover aspect-[4/3] shadow-xl object-center">
                <div class="absolute -bottom-4 -right-4 lg:right-0 max-w-sm bg-white text-iwa-ink rounded-xl p-6 shadow-xl border border-gray-100">
                    <p class="text-xs font-semibold text-iwa-saffron uppercase tracking-wider"><?php echo esc_html( $founder_title ); ?></p>
                    <h4 class="font-bold text-lg mt-1"><?php echo esc_html( $founder_name ); ?></h4>
                    <?php if ( ! empty( $about_card_text ) ) : ?>
                    <p class="mt-2 text-sm text-iwa-ink-soft"><?php echo esc_html( $about_card_text ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $about_btn_text ) ) : ?>
                    <a href="<?php echo esc_url( $about_btn_url ); ?>"
                       class="mt-4 inline-block rounded-full bg-iwa-saffron text-white px-5 py-2.5 font-semibold hover:bg-iwa-saffron-light transition text-sm">
                       <?php echo esc_html( $about_btn_text ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== NEWSLETTER ===================== -->
<section id="subscribe" class="py-16 lg:py-24 bg-gray-50/50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-3xl lg:text-4xl font-bold text-iwa-ink">
            <?php echo esc_html( $newsletter_headline ); ?>
        </p>
        <p class="mt-4 text-iwa-ink-soft"><?php echo esc_html( $newsletter_subtext ); ?></p>
        <form class="iwa-sub-form mt-8 max-w-md mx-auto text-left" data-source="home-newsletter">
            <div style="display:flex;gap:0.6rem;margin-bottom:0.65rem;">
                <input type="text" name="fname" placeholder="First name"
                       style="flex:1;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.72rem 1rem;font-size:0.875rem;outline:none;min-width:0;"
                       onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
                <input type="text" name="lname" placeholder="Last name"
                       style="flex:1;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.72rem 1rem;font-size:0.875rem;outline:none;min-width:0;"
                       onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div style="margin-bottom:0.65rem;">
                <input type="email" name="email" placeholder="Your email address" required
                       style="width:100%;border:1.5px solid #e5e7eb;border-radius:0.625rem;padding:0.72rem 1rem;font-size:0.875rem;outline:none;box-sizing:border-box;"
                       onfocus="this.style.borderColor='#ff671f'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <label style="display:flex;align-items:flex-start;gap:0.55rem;cursor:pointer;margin-bottom:0.85rem;text-align:left;">
                <input type="checkbox" name="consent" required
                       style="margin-top:3px;width:15px;height:15px;accent-color:#ff671f;flex-shrink:0;">
                <span style="font-size:0.78rem;color:#6b7280;line-height:1.5;">
                    I agree to receive newsletters and updates from Indian Women Abroad. Unsubscribe anytime.
                </span>
            </label>
            <button type="submit"
                    style="width:100%;background:#ff671f;color:#fff;border:none;border-radius:0.625rem;padding:0.8rem;font-size:0.9375rem;font-weight:600;cursor:pointer;transition:background .15s;font-family:'Plus Jakarta Sans',sans-serif;"
                    onmouseover="this.style.background='#ff8a4c'" onmouseout="this.style.background='#ff671f'">
                Subscribe
            </button>
            <div class="iwa-sub-msg" style="display:none;margin-top:0.65rem;padding:0.6rem 0.875rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;text-align:center;"></div>
        </form>
    </div>
</section>

<!-- ===================== IWA SHOP ===================== -->
<?php if ( class_exists( 'WooCommerce' ) ) :
    $shop_products = wc_get_products( [
        'limit'   => 4,
        'status'  => 'publish',
        'orderby' => 'date',
        'order'   => 'DESC',
    ] );
    if ( ! empty( $shop_products ) ) : ?>
<section id="shop" class="py-12 lg:py-16 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-sm font-semibold text-iwa-saffron uppercase tracking-wider mb-1">IWA Shop</p>
                <h2 class="text-2xl lg:text-3xl font-bold text-iwa-ink">Carry the community with you</h2>
            </div>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
               class="text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition whitespace-nowrap flex-shrink-0">
               View all &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <?php
            $hp_fallback_pool = [
                'https://images.unsplash.com/photo-1586495777744-4e6232bf4667?w=400&h=400&fit=crop',
                'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=400&h=400&fit=crop',
                'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=400&fit=crop',
                'https://images.unsplash.com/photo-1531251445707-1f000e1e87d0?w=400&h=400&fit=crop',
            ];
            $hp_currency = get_woocommerce_currency_symbol();
            foreach ( $shop_products as $product ) :
                $pid          = $product->get_id();
                $product_url  = get_permalink( $pid );
                $thumb        = get_the_post_thumbnail_url( $pid, 'medium_large' );
                if ( ! $thumb ) $thumb = $hp_fallback_pool[ $pid % count( $hp_fallback_pool ) ];
                $on_sale      = $product->is_on_sale();
                $reg          = (float) $product->get_regular_price();
                $sale         = (float) $product->get_sale_price();
                $disc         = ( $on_sale && $reg > 0 ) ? round( ( ( $reg - $sale ) / $reg ) * 100 ) : 0;
            ?>
            <article class="iwa-product-card group rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-lg transition flex flex-col relative">
                <!-- Image -->
                <div class="relative aspect-square overflow-hidden flex-shrink-0 bg-gray-50">
                    <a href="<?php echo esc_url( $product_url ); ?>" class="block w-full h-full">
                        <img src="<?php echo esc_url( $thumb ); ?>"
                             alt="<?php echo esc_attr( $product->get_name() ); ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                    </a>
                    <!-- Sale ribbon -->
                    <?php if ( $on_sale && $disc > 0 ) : ?>
                    <div class="absolute top-0 right-0 overflow-hidden w-16 h-16 pointer-events-none">
                        <div class="absolute top-3 -right-5 rotate-45 bg-iwa-green text-white text-[9px] font-bold tracking-wide text-center w-20 py-0.5 shadow-sm">
                            <?php echo $disc; ?>% OFF
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- Wishlist -->
                    <button type="button"
                            class="iwa-wishlist-btn absolute bottom-2 right-2 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center text-gray-400 hover:text-red-500 transition"
                            data-product-id="<?php echo absint( $pid ); ?>"
                            aria-label="Add to wishlist">
                        <svg class="w-4 h-4 iwa-heart-outline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        <svg class="w-4 h-4 iwa-heart-filled hidden text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001z"/></svg>
                    </button>
                </div>
                <!-- Info -->
                <div class="p-3 flex flex-col flex-1">
                    <h3 class="font-semibold text-iwa-ink text-sm leading-snug group-hover:text-iwa-saffron transition line-clamp-2 flex-1 mb-2">
                        <a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
                    </h3>
                    <!-- Price -->
                    <div class="flex items-baseline gap-2 flex-wrap mb-3">
                        <?php if ( $on_sale ) : ?>
                        <span class="text-sm font-bold text-iwa-ink"><?php echo esc_html( $hp_currency . number_format( $sale, 2 ) ); ?></span>
                        <span class="text-xs text-gray-400 line-through"><?php echo esc_html( $hp_currency . number_format( $reg, 2 ) ); ?></span>
                        <?php if ( $disc > 0 ) : ?>
                        <span class="text-[10px] font-bold text-iwa-green">(<?php echo $disc; ?>% OFF)</span>
                        <?php endif; ?>
                        <?php else : ?>
                        <span class="text-sm font-bold text-iwa-ink"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
                        <?php endif; ?>
                    </div>
                    <!-- Add to Cart -->
                    <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
                       data-quantity="1"
                       data-product_id="<?php echo absint( $pid ); ?>"
                       class="add_to_cart_button ajax_add_to_cart w-full text-center rounded-full bg-iwa-saffron text-white text-xs font-semibold py-2 px-3 hover:bg-iwa-saffron-light transition">
                        Add to Cart
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    <?php else : ?>
<section id="shop" class="py-12 lg:py-16 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-iwa-green uppercase tracking-wider">New</p>
        <p class="mt-2 text-xl font-bold text-iwa-ink">IWA Shop is open</p>
        <p class="mt-2 text-iwa-ink-soft max-w-md mx-auto text-sm">Curated merchandise and digital offerings for our community.</p>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="mt-4 inline-block text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">Browse the shop &rarr;</a>
    </div>
</section>
    <?php endif; ?>
<?php else : ?>
<section class="py-12 lg:py-16 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-iwa-green uppercase tracking-wider">Coming soon</p>
        <p class="mt-2 text-xl font-bold text-iwa-ink">IWA Shop</p>
        <p class="mt-2 text-iwa-ink-soft max-w-md mx-auto text-sm">Curated merchandise and digital offerings for our community.</p>
        <a href="#subscribe" class="mt-4 inline-block text-sm font-semibold text-iwa-blue hover:text-iwa-blue-light transition">Notify me when it&rsquo;s here &rarr;</a>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
