<?php $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_javascript_settings' ); ?>
<input name="br_compare_products_javascript_settings[settings_name]" type="hidden" value="br_compare_products_javascript_settings">
<table class="form-table">
    <tr>
        <th><?php _e( 'Before products load', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <textarea name="br_compare_products_javascript_settings[before_load]"><?php echo @ $options['before_load']; ?></textarea>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'After products load', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <textarea name="br_compare_products_javascript_settings[after_load]"><?php echo @ $options['after_load']; ?></textarea>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'Before remove product', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <textarea name="br_compare_products_javascript_settings[before_load]"><?php echo @ $options['before_remove']; ?></textarea>
        </td>
    </tr>
    <tr>
        <th><?php _e( 'After remove product', 'BeRocket_Compare_Products_domain' ) ?></th>
        <td>
            <textarea name="br_compare_products_javascript_settings[after_load]"><?php echo @ $options['after_remove']; ?></textarea>
        </td>
    </tr>
</table>