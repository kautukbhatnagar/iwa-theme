<?php
/**
 * Product loop start
 */
defined( 'ABSPATH' ) || exit;

$columns = wc_get_loop_prop( 'columns', wc_get_default_products_per_row() );
?>
<ul class="woocommerce-loop products columns-<?php echo esc_attr( $columns ); ?>">
