<?php
/**
 * Single post template — individual story page
 */
get_header();

$_s = function_exists( 'iwa_get_settings' ) ? iwa_get_settings() : [];

// Sidebar: recent posts from admin-configured categories
$sidebar_cat_ids  = array_filter( array_map( 'intval', explode( ',', $_s['sidebar_categories'] ?? '' ) ) );
$sidebar_posts    = [];
if ( ! empty( $sidebar_cat_ids ) ) {
    $sidebar_posts = get_posts( [
        'category__in' => $sidebar_cat_ids,
        'post__not_in' => [ get_the_ID() ],
        'numberposts'  => 5,
        'orderby'      => 'date',
        'order'        => 'DESC',
    ] );
}
$has_sidebar = ! empty( $sidebar_posts );

while ( have_posts() ) : the_post();
    $thumb  = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( get_the_ID(), 'full' ) : get_the_post_thumbnail_url( get_the_ID(), 'full' );
    $date   = function_exists( 'iwa_format_date' ) ? iwa_format_date( get_the_ID() ) : get_the_date( 'j M Y' );
    $author = get_the_author();
    $cats   = get_the_category();

    // Related posts from the same category
    $related = [];
    if ( ! empty( $cats ) ) {
        $related = get_posts( [
            'category__in'   => wp_list_pluck( $cats, 'term_id' ),
            'post__not_in'   => [ get_the_ID() ],
            'posts_per_page' => 3,
            'orderby'        => 'rand',
        ] );
    }
?>

<article>

    <!-- Hero image -->
    <?php if ( $thumb ) : ?>
    <div class="w-full aspect-[21/8] overflow-hidden bg-gray-100">
        <img src="<?php echo esc_url( $thumb ); ?>"
             alt="<?php the_title_attribute(); ?>"
             class="w-full h-full object-cover">
    </div>
    <?php endif; ?>

    <!-- Article + optional sidebar -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 <?php echo $has_sidebar ? 'flex gap-12 items-start' : ''; ?>">
    <div class="<?php echo $has_sidebar ? 'flex-1 min-w-0' : 'max-w-3xl mx-auto w-full'; ?>"><?php // article column ?>

        <!-- Meta -->
        <div class="flex flex-wrap items-center gap-3 text-xs text-iwa-ink-soft mb-4">
            <?php foreach ( $cats as $cat ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
               class="rounded-full bg-iwa-saffron/10 text-iwa-saffron font-semibold px-2.5 py-0.5 hover:bg-iwa-saffron/20 transition">
                <?php echo esc_html( $cat->name ); ?>
            </a>
            <?php endforeach; ?>
            <span><?php echo esc_html( $date ); ?></span>
            <?php if ( $author ) : ?>
            <span>By <span class="font-medium text-iwa-ink"><?php echo esc_html( $author ); ?></span></span>
            <?php endif; ?>
        </div>

        <!-- Title -->
        <h1 class="font-heading text-3xl lg:text-5xl font-bold text-iwa-ink leading-tight">
            <?php the_title(); ?>
        </h1>

        <!-- Divider -->
        <hr class="mt-8 mb-8 border-gray-200">

        <!-- Body copy -->
        <div class="prose-story">
            <?php the_content(); ?>
        </div>

        <!-- Tags -->
        <?php the_tags( '<div class="mt-10 flex flex-wrap gap-2">', '', '</div>' ); ?>

        <!-- Author card -->
        <div class="mt-12 flex items-start gap-4 rounded-2xl bg-gray-50 border border-gray-100 p-6">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 56, '', '', [ 'class' => 'rounded-full flex-shrink-0' ] ); ?>
            <div>
                <p class="text-xs font-semibold text-iwa-saffron uppercase tracking-wider">Written by</p>
                <p class="font-bold text-iwa-ink mt-0.5"><?php echo esc_html( $author ); ?></p>
                <?php $bio = get_the_author_meta( 'description' );
                if ( $bio ) : ?>
                <p class="text-sm text-iwa-ink-soft mt-1"><?php echo esc_html( $bio ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Back link -->
        <?php
        $stories_url = function_exists( 'iwa_stories_url' ) ? iwa_stories_url() : home_url( '/category/stories-indian-women-abroad/' );
        ?>
        <p class="mt-8">
            <a href="<?php echo esc_url( $stories_url ); ?>"
               class="inline-flex items-center gap-2 text-sm font-semibold text-iwa-ink-soft hover:text-iwa-saffron transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Stories
            </a>
        </p>

    </div><?php // end article column ?>

    <?php if ( $has_sidebar ) : ?>
    <!-- Sidebar -->
    <aside style="width:280px;flex-shrink:0;" class="hidden lg:block">
        <div style="position:sticky;top:100px;">
            <h3 style="font-family:'Epilogue',sans-serif;font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin:0 0 1rem;">Recent Posts</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php foreach ( $sidebar_posts as $sp ) :
                    $sp_thumb = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( $sp->ID ) : get_the_post_thumbnail_url( $sp->ID, 'medium' );
                    $sp_cats  = get_the_category( $sp->ID );
                    $sp_cat   = ! empty( $sp_cats ) ? $sp_cats[0] : null;
                ?>
                <a href="<?php echo esc_url( get_permalink( $sp->ID ) ); ?>"
                   style="display:flex;gap:0.75rem;text-decoration:none;align-items:flex-start;group;">
                    <?php if ( $sp_thumb ) : ?>
                    <img src="<?php echo esc_url( $sp_thumb ); ?>"
                         alt="<?php echo esc_attr( $sp->post_title ); ?>"
                         style="width:64px;height:52px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                    <?php endif; ?>
                    <div style="min-width:0;">
                        <?php if ( $sp_cat ) : ?>
                        <span style="font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#ff671f;">
                            <?php echo esc_html( $sp_cat->name ); ?>
                        </span>
                        <?php endif; ?>
                        <p style="font-size:0.85rem;font-weight:600;color:#1a1a1a;line-height:1.35;margin:2px 0 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?php echo esc_html( $sp->post_title ); ?>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>
    <?php endif; ?>

    </div><?php // end outer flex wrapper ?>
</article>

<!-- ===================== RELATED STORIES ===================== -->
<?php if ( ! empty( $related ) ) : ?>
<section class="py-12 lg:py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-iwa-ink mb-8">More Stories</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ( $related as $rel ) :
                $rel_thumb = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( $rel->ID ) : get_the_post_thumbnail_url( $rel->ID, 'large' );
                $rel_date  = function_exists( 'iwa_format_date' ) ? iwa_format_date( $rel->ID ) : get_the_date( 'j M', $rel->ID );
            ?>
            <article class="rounded-xl overflow-hidden bg-white border border-gray-100 hover:shadow-md transition group">
                <?php if ( $rel_thumb ) : ?>
                <a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>" class="block aspect-[4/3] overflow-hidden">
                    <img src="<?php echo esc_url( $rel_thumb ); ?>"
                         alt="<?php echo esc_attr( $rel->post_title ); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="p-4">
                    <p class="text-xs text-iwa-ink-soft mb-1"><?php echo esc_html( $rel_date ); ?></p>
                    <h3 class="font-bold text-iwa-ink group-hover:text-iwa-green transition line-clamp-2">
                        <a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>">
                            <?php echo esc_html( $rel->post_title ); ?>
                        </a>
                    </h3>
                    <a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>"
                       class="mt-2 inline-block text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">
                        Read &rarr;
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Prose styles injected inline to avoid requiring a separate CSS file -->
<style>
.prose-story { font-family: 'Outfit', sans-serif; font-size: 1.0625rem; line-height: 1.85; color: #1a1a1a; }
.prose-story p { margin-bottom: 1.5rem; }
.prose-story h2 { font-family: 'Epilogue', sans-serif; font-size: 1.625rem; font-weight: 700; margin: 2.5rem 0 1rem; color: #1a1a1a; line-height: 1.3; }
.prose-story h3 { font-family: 'Epilogue', sans-serif; font-size: 1.25rem; font-weight: 700; margin: 2rem 0 0.75rem; color: #1a1a1a; }
.prose-story a { color: #ff671f; text-decoration: underline; text-underline-offset: 3px; }
.prose-story a:hover { color: #ff8a4c; }
.prose-story blockquote { border-left: 4px solid #ff671f; margin: 2rem 0; padding: 1rem 1.5rem; background: #fff7f3; border-radius: 0 0.75rem 0.75rem 0; font-style: italic; color: #4a4a4a; }
.prose-story ul, .prose-story ol { padding-left: 1.5rem; margin-bottom: 1.5rem; }
.prose-story li { margin-bottom: 0.5rem; }
.prose-story strong { font-weight: 700; }
.prose-story img { border-radius: 0.75rem; margin: 2rem auto; display: block; max-width: 100%; }
.prose-story hr { margin: 2.5rem 0; border-color: #e5e7eb; }
</style>

<?php endwhile; ?>

<?php get_footer(); ?>
