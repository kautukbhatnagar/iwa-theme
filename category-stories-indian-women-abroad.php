<?php
/**
 * Template for: Stories Archive (category: stories-indian-women-abroad)
 */
get_header();
?>

<main class="py-12 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Page heading -->
        <header class="mb-10">
            <p class="text-sm font-semibold text-iwa-saffron uppercase tracking-wider mb-2">Stories</p>
            <h1 class="text-4xl lg:text-5xl font-bold text-iwa-ink leading-tight">Indian Women Abroad</h1>
            <p class="mt-3 text-lg text-iwa-ink-soft">Real stories of Indian women living, working, and thriving across the world.</p>
        </header>

        <?php if ( have_posts() ) : ?>

        <!-- Story list -->
        <div class="divide-y divide-gray-100">
            <?php while ( have_posts() ) : the_post();
                $thumb = function_exists( 'iwa_thumbnail' ) ? iwa_thumbnail( get_the_ID() ) : get_the_post_thumbnail_url( get_the_ID(), 'large' );
                $date  = function_exists( 'iwa_format_date' ) ? iwa_format_date( get_the_ID() ) : get_the_date( 'j M' );
                $author = get_the_author();
            ?>
            <article class="py-8 flex flex-col sm:flex-row gap-6 group">

                <!-- Thumbnail -->
                <?php if ( $thumb ) : ?>
                <a href="<?php the_permalink(); ?>" class="flex-shrink-0 block w-full sm:w-48 aspect-[4/3] sm:aspect-auto sm:h-32 overflow-hidden rounded-xl">
                    <img src="<?php echo esc_url( $thumb ); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300 rounded-xl" loading="lazy">
                </a>
                <?php endif; ?>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <!-- Meta -->
                    <div class="flex flex-wrap items-center gap-3 text-xs text-iwa-ink-soft mb-2">
                        <span class="rounded-full bg-iwa-saffron/10 text-iwa-saffron font-semibold px-2.5 py-0.5">Story</span>
                        <span><?php echo esc_html( $date ); ?></span>
                        <?php if ( $author ) : ?>
                        <span>By <?php echo esc_html( $author ); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h2 class="text-lg lg:text-xl font-bold text-iwa-ink leading-snug group-hover:text-iwa-green transition">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>

                    <!-- Excerpt -->
                    <p class="mt-2 text-sm text-iwa-ink-soft leading-relaxed line-clamp-2">
                        <?php echo wp_kses_post( get_the_excerpt() ); ?>
                    </p>

                    <!-- Read more -->
                    <a href="<?php the_permalink(); ?>"
                       class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-iwa-saffron hover:text-iwa-saffron-light transition">
                        Continue Reading
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php
        $pagination = paginate_links( [
            'prev_text' => '&larr; Newer',
            'next_text' => 'Older &rarr;',
            'type'      => 'array',
        ] );
        if ( $pagination ) : ?>
        <nav class="mt-12 flex flex-wrap justify-center gap-2" aria-label="Stories pagination">
            <?php foreach ( $pagination as $page_link ) : ?>
            <span class="[&>a]:px-4 [&>a]:py-2 [&>a]:rounded-full [&>a]:border [&>a]:border-gray-200 [&>a]:text-sm [&>a]:font-medium [&>a]:text-iwa-ink-soft [&>a]:hover:border-iwa-saffron [&>a]:hover:text-iwa-saffron [&>a]:transition
                         [&>span.current]:px-4 [&>span.current]:py-2 [&>span.current]:rounded-full [&>span.current]:bg-iwa-saffron [&>span.current]:text-white [&>span.current]:text-sm [&>span.current]:font-medium">
                <?php echo wp_kses_post( $page_link ); ?>
            </span>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <?php else : ?>
        <div class="py-24 text-center text-iwa-ink-soft">
            <p class="text-lg">No stories yet. Check back soon!</p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mt-4 inline-block text-iwa-saffron font-semibold hover:text-iwa-saffron-light transition">&larr; Back to Home</a>
        </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
