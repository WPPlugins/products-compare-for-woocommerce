<?php $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' ); ?>
<input name="br_compare_products_general_settings[settings_name]" type="hidden" value="br_compare_products_general_settings">
<table class="form-table">
    <tr>
        <th><?php _e( 'Compare Page', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <select name="br_compare_products_general_settings[compare_page]">
                <?php 
                $pages = get_pages();
                foreach ( $pages as $page ) {
                    echo '<option value="'.$page->ID.'"'.( ( $options['compare_page'] == $page->ID ) ? ' selected' : '' ).'>'.$page->post_title.'</option>';
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Uses Attributes', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <?php
            $attributes2 = wc_get_attribute_taxonomies();
            
            $attributes = array();
            foreach ( $attributes2 as $attr ) {
                $attributes['pa_'.$attr->attribute_name] = $attr->attribute_label;
            }
            $attributes = array(
                'cp_price' => 'Price',
                'cp_available' => 'Availability',
                'cp_image' => 'Image',
            ) + $attributes;
            foreach ( $attributes as $attr => $attr_label ) {
                $checked = '';
                if ( ( is_array( $options['attributes'] ) && in_array( $attr, $options['attributes'] ) ) || ! is_array( $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                    $checked = ' checked';
                }
                echo '<p><label><input name="br_compare_products_general_settings[attributes][]" type="checkbox" value="'.$attr.'"'.$checked.'>'.$attr_label.'</label></p>';
            }
            ?>
        </td>
    </tr>
</table>
