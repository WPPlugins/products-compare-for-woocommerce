<?php @ $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_style_settings' );
$defaults = BeRocket_Compare_Products::$defaults[ 'br_compare_products_style_settings' ]; ?>
<input name="br_compare_products_style_settings[settings_name]" type="hidden" value="br_compare_products_style_settings">
<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="6" style="text-align: center; font-size: 2em;"><?php _e('Compare Button on Widgets', 'BeRocket_Compare_Products_domain') ?></th></tr>
        <tr>
            <th><?php _e('Border color', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Border width', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Border radius', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Size', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Font color', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Background', 'BeRocket_Compare_Products_domain') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="admin-column-color">
                <div class="colorpicker_field" data-color="<?php echo @ $options['button']['bcolor'] ?>"></div>
                <input data-default="<?php echo $defaults['button']['bcolor']; ?>" type="hidden" value="<?php echo @ $options['button']['bcolor'] ?>" name="br_compare_products_style_settings[button][bcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_Compare_Products_domain') ?>" class="theme_default button">
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['bwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[button][bwidth]" value="<?php echo @ $options['button']['bwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['bradius']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[button][bradius]" value="<?php echo @ $options['button']['bradius'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['button']['fontsize']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[button][fontsize]" value="<?php echo @ $options['button']['fontsize'] ?>" />
            </td>
            <td class="admin-column-color">
                <div class="colorpicker_field" data-color="<?php echo @ $options['button']['fcolor'] ?>"></div>
                <input data-default="<?php echo $defaults['button']['fcolor']; ?>" type="hidden" value="<?php echo @ $options['button']['fcolor'] ?>" name="br_compare_products_style_settings[button][fcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_Compare_Products_domain') ?>" class="theme_default button">
            </td>
            <td class="admin-column-color">
                <div class="colorpicker_field" data-color="<?php echo @ $options['button']['backcolor'] ?>"></div>
                <input data-default="<?php echo $defaults['button']['backcolor']; ?>" type="hidden" value="<?php echo @ $options['button']['backcolor'] ?>" name="br_compare_products_style_settings[button][backcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_Compare_Products_domain') ?>" class="theme_default button">
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="6" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
<table class="form-table berocket_compare_products_styler">
    <thead>
        <tr><th colspan="4" style="text-align: center; font-size: 2em;"><?php _e('Table', 'BeRocket_Compare_Products_domain') ?></th></tr>
        <tr>
            <th><?php _e('Column minimum width', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Image width', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Padding top', 'BeRocket_Compare_Products_domain') ?></th>
            <th><?php _e('Background color', 'BeRocket_Compare_Products_domain') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <input data-default="<?php echo $defaults['table']['colwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[table][colwidth]" value="<?php echo @ $options['table']['colwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['table']['imgwidth']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[table][imgwidth]" value="<?php echo @ $options['table']['imgwidth'] ?>" />
            </td>
            <td>
                <input data-default="<?php echo $defaults['table']['toppadding']; ?>" type="text" placeholder="<?php _e('Theme Default', 'BeRocket_Compare_Products_domain') ?>" name="br_compare_products_style_settings[table][toppadding]" value="<?php echo @ $options['table']['toppadding'] ?>" />
            </td>
            <td class="admin-column-color">
                <div class="colorpicker_field" data-color="<?php echo @ $options['table']['backcolor'] ?>"></div>
                <input data-default="<?php echo $defaults['table']['backcolor']; ?>" type="hidden" value="<?php echo @ $options['table']['backcolor'] ?>" name="br_compare_products_style_settings[table][backcolor]" />
                <input type="button" value="<?php _e('Default', 'BeRocket_Compare_Products_domain') ?>" class="theme_default button">
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th class="manage-column admin-column-theme" colspan="4" scope="col">
                <input class="all_theme_default button" type="button" value="Set all to theme default">
                <div style="clear:both;"></div>
            </th>
        </tr>
    </tfoot>
</table>
