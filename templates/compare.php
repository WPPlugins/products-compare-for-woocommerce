<?php
$options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
$style = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_style_settings' );
$text = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_text_settings' );
$products = BeRocket_Compare_Products::get_all_compare_products();
$terms = array();
$name = array();
$name['attributes'] = array();
$name['custom'] = array();
if ( isset( $products ) && is_array( $products ) && count( $products ) > 0 ) {
    foreach ( $products as $product ) {
        $current_language= apply_filters( 'wpml_current_language', NULL );
        $product = apply_filters( 'wpml_object_id', $product, 'product', true, $current_language );
        $term = array();
        $post_get = wc_get_product($product);
        $attributes = $post_get->get_attributes();
        $taxonomies = get_post_taxonomies( $product );
        $term['id'] = $product;
        $term['title'] = $post_get->get_title();
        $term['image'] = $post_get->get_image();
        $term['price'] = $post_get->get_price_html();
        $term['link'] = $post_get->get_permalink();
        //$term['availability'] = $post_get->stock_status;
        $term['availability'] = $post_get->get_availability();
        $term['is_in_stock'] = $post_get->is_in_stock();
        $attributes_value = array();
        $attributes_name = array();
        foreach ( $attributes as $key => $attribute ) {
            if ( ! is_array( $options['attributes'] ) || in_array( $key, $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                $attributes_value[$key] = wc_get_product_terms( $product, $key );
                if ( is_object( @ $attributes_value[$key][0] ) ) {
                    $attr_value_temp = $attributes_value[$key];
                    $attributes_value[$key] = array();
                    foreach( @ $attr_value_temp as $term_i => $term_data ) {
                        $attributes_value[$key][] = $term_data->name;
                    }
                }
                $attributes_value[$key] = implode( ', ', $attributes_value[$key] );
                if ( ! isset( $name['attributes'][$key] ) ) {
                    $attributes_name[$key] = get_taxonomy( $key );
                    $attributes_name[$key] = $attributes_name[$key]->labels->name;
                }
                if(($key_delete = array_search($key, $taxonomies)) !== false) {
                    unset($taxonomies[$key_delete]);
                }
            }
        }
        $taxonomies = array_diff($taxonomies, array('product_type', 'product_shipping_class'));
        $term['attributes'] = $attributes_value;
        $name['attributes'] = $name['attributes'] + $attributes_name;
        $terms[] = $term;
    }
$javascript = '';
?>
<div class="berocket_compare_box">
    <table class="br_moved_attr">
        <?php 
        if ( ! is_array( $options['attributes'] ) || in_array( 'cp_available', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
            echo '<tr class="br_absolute2_cp_availability"><td>'.$text['availability'].'</td></tr>';
            echo '<tr class="br_absolute_attributes"><td class="br_block_nothing">&nbsp;</td></tr>';
        }
        if ( is_array( $name['attributes'] ) && count( $name['attributes'] ) > 0 ) {?>
            <?php foreach ( $name['attributes'] as $attr => $name_attr ) {
                echo '<tr class="br_absolute_'.$attr.'"><td>'.$name_attr.'</td>';
                echo '</tr>';
            } 
        }?>
    </table>
    <div class="berocket_compare_table_hidden">
        <table class="br_product_compare_name">
            <tbody>
                <tr>
                    <td class="br_product_hidden_first"><p>
                    </p></td>
                     <?php 
                    foreach ( $terms as $term ) {
                        echo '<td class="br_product_hidden_'.$term['id'].'">
                            <h3><a href="'.$term['link'].'">'.$term['title'].'</a></h3>';
                        if ( ! is_array( $options['attributes'] ) || in_array( 'cp_price', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                            echo '<p class="br_compare_price price">'.$term['price'].'</p>';
                        }
                        $default_language= apply_filters( 'wpml_default_language', NULL );
                        $default_product = apply_filters( 'wpml_object_id', $term['id'], 'product', true, $default_language );
                        echo '<a href="#remove" class="br_remove_compare_product_reload" data-id="'.$default_product.'"><i class="fa fa-times"></i></a>';
                        echo '</td>';
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="berocket_compare_table">
        <table>
            <tbody>
                <tr class="br_stock_br_top_first_block">
                    <td class="br_first_product">
                    </td>
                    <?php
                    foreach ( $terms as $term ) {
                        echo '<td class="br_product_'.$term['id'].'">';
                        if ( ! is_array( $options['attributes'] ) || in_array( 'cp_image', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                            echo '<a href="'.$term['link'].'">'.$term['image'].'</a>';
                        }
                        echo '</td>';
                        $javascript .= 'jQuery(document).ready( function() { var math_width = Math.round(jQuery(".berocket_compare_table .br_product_'.$term['id'].'").width()); jQuery(".br_product_hidden_'.$term['id'].' h3").css("width", math_width); jQuery(".br_product_hidden_'.$term['id'].' .br_compare_price").css("width", math_width); } );
                        ';
                    }
                    
                    $javascript .= 'jQuery(document).ready( function() { jQuery(".br_product_hidden_first p").css("width", Math.round(jQuery(".br_first_product").innerWidth())); } );
                    ';
                    $javascript .= "jQuery('.berocket_compare_table').css('padding-top', jQuery('.berocket_compare_table_hidden').height());";
                    echo '</tr>';
                    $is_first = true; 
                    if ( ! is_array( $options['attributes'] ) || in_array( 'cp_available', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                        echo '<tr class="br_stock2_cp_availability">';
                        echo '<td>'.$text['availability'].'</td>';
                        foreach ( $terms as $term ) {
                            $class_stock = '';
                            $text_stock = '';
                            if ( $term['availability'] && ! empty( $term['availability']['availability'] ) ) {
                                $product = wc_get_product($term['id']);
                                $availability_html = empty( $term['availability']['availability'] ) ? '' : '<p class="stock ' . esc_attr( $term['availability']['class'] ) . '">' . esc_html( $term['availability']['availability'] ) . '</p>';
                                $text_stock = apply_filters( 'woocommerce_stock_html', $availability_html, $term['availability']['availability'], $product );
                                $class_stock = esc_attr( $term['availability']['class'] );
                            } else {
                                if ( $term['is_in_stock'] ) {
                                    $text_stock = __( 'In stock', 'woocommerce' );
                                    $class_stock = 'in-stock';
                                } else {
                                    $text_stock = __( 'Out of stock', 'woocommerce' );
                                    $class_stock = 'out-of-stock';
                                }
                                $text_stock = '<p class="stock '.$class_stock.'">'.$text_stock.'</p>';
                            }
                            echo '<td class="'.$term['availability']['class'].'">';
                            echo $text_stock;
                            echo '</td>';
                        }
                        echo '</tr>';
                        if ( $is_first ) {
                            $javascript .= 'jQuery(document).ready( function() { if( jQuery(".br_stock2_cp_availability").length > 0 ) {jQuery(".br_moved_attr").css("top", jQuery(".br_stock2_cp_availability").position().top - 1); } }, 300 );
                            ';
                            $is_first = false;
                        }
                        $javascript .= 'jQuery(document).ready( function() { jQuery(".br_absolute2_cp_availability").css("height", Math.round(jQuery(".br_stock2_cp_availability").height())); } );
                        ';
                        $javascript .= 'jQuery(document).ready( function() { jQuery(".br_stock2_cp_availability").css("height", Math.round(jQuery(".br_stock2_cp_availability").height())); } );
                        ';
                    }
                    if ( is_array( $name['attributes'] ) && count( $name['attributes'] ) > 0 ) {?>
                        <tr class="br_stock_attributes"><td class="br_full_size_block" colspan="<?php echo (count($terms) + 1); ?>"><p><?php echo $text['attribute']; ?>&nbsp;</p></td></tr>
                        <?php 
                        $javascript .= 'jQuery(document).ready( function() { jQuery(".br_absolute_attributes").css("height", Math.round(jQuery(".br_stock_attributes").height())); } );
                        jQuery(document).ready( function() { jQuery(".br_stock_attributes").css("height", Math.round(jQuery(".br_stock_attributes").height())); } );
                        ';
                        foreach ( $name['attributes'] as $attr => $name_attr ) {
                            echo '<tr class="br_stock_'.$attr.'"><td>'.$name_attr.'</td>';
                            foreach ( $terms as $term ) {
                                echo '<td>'.@ $term['attributes'][$attr].'</td>';
                            }
                            echo '</tr>';
                            if ( $is_first ) {
                                $javascript .= 'jQuery(document).ready( function() { if( jQuery(".br_stock_'.$attr.'").length > 0 ) { jQuery(".br_moved_attr").css("top", jQuery(".br_stock_'.$attr.'").position().top - 1); } }, 300 );
                                ';
                                $is_first = false;
                            }
                            $javascript .= 'jQuery(document).ready( function() { jQuery(".br_absolute_'.$attr.'").css("height", Math.round(jQuery(".br_stock_'.$attr.'").height())); } );
                            jQuery(document).ready( function() { jQuery(".br_stock_'.$attr.'").css("height", Math.round(jQuery(".br_stock_'.$attr.'").height())); } );
                            ';
                        } 
                    }?>
            </tbody>
        </table>
    </div>
</div>
<?php
$javascript = "jQuery('.br_full_size_block p').css('line-height', '1em');" . $javascript;
?>
<script>
jQuery(document).ready(function () {
    var table_scroll = 0;
    var table_hidden_scroll = 0;
    berocket_load_compare_table();
    setTimeout( berocket_load_compare_table, 500 );
    setTimeout( berocket_load_compare_table, 5000 );
    setTimeout( berocket_load_compare_table, 25000 );
    jQuery('.berocket_compare_table_hidden').show();
    jQuery('.berocket_compare_table_hidden').css('top', 0 );
    jQuery(window).resize( function () {
        if( jQuery('.berocket_compare_box').length > 0 ) {
            setTimeout( berocket_load_compare_table, 300 );
        }
    });
    jQuery(window).scroll( function () {
        if(jQuery('.berocket_compare_box').length > 0 ) {
            if( ! jQuery('.berocket_compare_box').is('.berocket_full_screen_box') ) {
                var top = jQuery(window).scrollTop() - jQuery('.berocket_compare_box').offset().top + <?php echo (int) $style['table']['toppadding']; ?>;
                if ( top < 0 ) {
                    top = 0;
                } else if ( top > jQuery('.berocket_compare_box').height() - jQuery('.berocket_compare_table_hidden').height() ) {
                    top = jQuery('.berocket_compare_box').height() - jQuery('.berocket_compare_table_hidden').height();
                }
                jQuery('.berocket_compare_table_hidden').show();
                jQuery('.berocket_compare_table_hidden').css('top', top );
            }
        }
    });
    jQuery('.berocket_compare_table_hidden').scroll( function () {
        var left_initial = jQuery(this).scrollLeft();
        if( table_hidden_scroll != left_initial ) {
            var this_size = jQuery(this).find('table').width();
            var set_size = jQuery('.berocket_compare_table').find('table').width();
            left = set_size / this_size * left_initial + 0.5;
            left = parseInt(left);
            table_hidden_scroll = left_initial;
            table_scroll = left;
            jQuery('.berocket_compare_table').scrollLeft(left); 
            berocket_onscroll_compare(left);
        }
    });
    jQuery('.berocket_compare_table').scroll( function () {
        var left_initial = jQuery(this).scrollLeft();
        if( table_scroll != left_initial ) {
            var this_size = jQuery(this).find('table').width();
            var set_size = jQuery('.berocket_compare_table_hidden').find('table').width();
            left = set_size / this_size * left_initial + 0.5;
            left = parseInt(left);
            table_hidden_scroll = left;
            table_scroll = left_initial;
            jQuery('.berocket_compare_table_hidden').scrollLeft(left);
            berocket_onscroll_compare(left);
        }
    });
});
jQuery(document).ready(function () {
    jQuery('.berocket_compare_table_hidden').mousewheel( function( event ) {
        event.preventDefault();
        event.stopPropagation();
        var scroll = jQuery('.berocket_compare_table_hidden').scrollLeft() - ( ( event.deltaY + event.deltaX ) * event.deltaFactor );
        jQuery('.berocket_compare_table_hidden').scrollLeft(scroll);
        jQuery('.berocket_compare_table_hidden').scroll();
    });
    jQuery('.br_show_compare_dif').click(function(event) {
        event.preventDefault();
        if( jQuery(this).is('.br_hidden_same') ) {
            jQuery('.br_same_attr').show();
            jQuery(this).removeClass('br_hidden_same').text(the_compare_products_data.hide_same);
        } else {
            jQuery('.br_same_attr').hide();
            jQuery(this).addClass('br_hidden_same').text(the_compare_products_data.show_same);
        }
        setTimeout( berocket_load_compare_table, 50 );
    });
});
function berocket_load_compare_table() {
    <?php echo $javascript; ?>
}
function berocket_onscroll_compare(left) {
    jQuery('.br_full_size_block p').css('left', 10 + left);
}
</script>

<?php } ?>
