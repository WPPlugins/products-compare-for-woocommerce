<div class="berocket_compare_widget_start">
<?php
$products = BeRocket_Compare_Products::get_all_compare_products();
$options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
$text = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_text_settings' );

?>
<?php if ( $title ) {
    echo '<h3>'.$title.'</h3>';
} ?>
<div class="berocket_compare_widget berocket_compare_widget_<?php echo $type; ?>" data-type="<?php echo $type; ?>">
    <ul>
    <?php
    if ( isset( $products ) && is_array( $products ) && count( $products ) > 0 ) {
        foreach ( $products as $product ) {
            $term = array();
            $current_language= apply_filters( 'wpml_current_language', NULL );
            $default_language= apply_filters( 'wpml_default_language', NULL );
            $product = apply_filters( 'wpml_object_id', $product, 'product', true, $current_language );
            $default_product = apply_filters( 'wpml_object_id', $product, 'product', true, $default_language );
            $post_get = wc_get_product($product);
            $title = $post_get->get_title();
            $image = $post_get->get_image();
            $link = $post_get->get_permalink();
            echo '<li class="br_widget_compare_product_'.$default_product.'">';
            echo '<a href="#remove" class="br_remove_compare_product" data-id="'.$default_product.'"><i class="fa fa-times"></i></a>';
            echo '<a href="'.$link.'">';
            if ( @ $type != 'text' ) {
                echo $image;
            }
            echo '<span>'.$title.'</span>';
            echo '</a></li>';
        }
    }
    ?>
    </ul>
    <?php 
        $page_compare = $options['compare_page'];
    if ( isset( $products ) && is_array( $products ) && count( $products ) > 0 ) { ?>
    <a class="berocket_open_compare" href="<?php echo get_page_link($page_compare); ?>"><?php echo $text['compare']; ?></a>
    <?php } ?>
</div>
</div>
