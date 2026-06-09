<?php
/**
 * Fallback template — the main experience is via page-iwa-home.php
 */
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body style="font-family:sans-serif;padding:2rem;background:#fff;color:#1a1a1a;">
    <p>Please visit the <a href="<?php echo esc_url(home_url('/')); ?>" style="color:#ff671f;">homepage</a>.</p>
    <?php wp_footer(); ?>
</body>
</html>
