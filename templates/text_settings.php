<?php $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_text_settings' ); ?>
<input name="br_compare_products_text_settings[settings_name]" type="hidden" value="br_compare_products_text_settings">
<table class="form-table">
    <tr>
        <th><?php _e( 'Text on compare button', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <input name="br_compare_products_text_settings[compare]" type='text' value="<?php echo $options['compare']; ?>"/>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Add to compare button', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <input name="br_compare_products_text_settings[add_compare]" type='text' value="<?php echo $options['add_compare']; ?>"/>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Add to compare button if product added', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <input name="br_compare_products_text_settings[added_compare]" type='text' value="<?php echo $options['added_compare']; ?>"/>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Attribute text', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <input name="br_compare_products_text_settings[attribute]" type='text' value="<?php echo $options['attribute']; ?>"/>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Availability text', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <input name="br_compare_products_text_settings[availability]" type='text' value="<?php echo $options['availability']; ?>"/>
        </td>
    </tr>
</table>