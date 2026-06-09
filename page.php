<?php
/**
 * Generic page template — Cart, Checkout, My Account, and any other WP page.
 */
get_header();
?>

<main style="min-height:100vh; padding: 2.5rem 0 4rem;">
    <div style="max-width:80rem; margin:0 auto; padding:0 1.5rem; width:100%; box-sizing:border-box;">

        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
        endif;
        ?>

    </div>
</main>


<?php get_footer(); ?>
