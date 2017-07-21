<?php 
$dplugin_name = 'WooCommerce Products Compare';
$dplugin_link = 'http://berocket.com/product/woocommerce-products-compare';
$dplugin_price = 20;
$dplugin_desc = '';
@ include 'settings_head.php';
@ include 'discount.php';
?>
<div class="wrap">  
    <div id="icon-themes" class="icon32"></div>  
    <h2>Compare Products Settings</h2>  
    <?php settings_errors(); ?>  

    <?php $active_tab = isset( $_GET[ 'tab' ] ) ? @ $_GET[ 'tab' ] : 'general'; ?>  

    <h2 class="nav-tab-wrapper">  
        <a href="?page=br-compare-products&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'BeRocket_Compare_Products_domain') ?></a>
        <a href="?page=br-compare-products&tab=style" class="nav-tab <?php echo $active_tab == 'style' ? 'nav-tab-active' : ''; ?>"><?php _e('Style', 'BeRocket_Compare_Products_domain') ?></a>
        <a href="?page=br-compare-products&tab=text" class="nav-tab <?php echo $active_tab == 'text' ? 'nav-tab-active' : ''; ?>"><?php _e('Text', 'BeRocket_Compare_Products_domain') ?></a>
        <a href="?page=br-compare-products&tab=javascript" class="nav-tab <?php echo $active_tab == 'javascript' ? 'nav-tab-active' : ''; ?>"><?php _e('JavaScript', 'BeRocket_Compare_Products_domain') ?></a>
    </h2>  

    <form class="lmp_submit_form" method="post" action="options.php">  
        <?php 
        if( $active_tab == 'general' ) { 
            settings_fields( 'br_compare_products_general_settings' );
            do_settings_sections( 'br_compare_products_general_settings' );
        } else if( $active_tab == 'style' ) {
            settings_fields( 'br_compare_products_style_settings' );
            do_settings_sections( 'br_compare_products_style_settings' ); 
        } else if( $active_tab == 'text' ) {
            settings_fields( 'br_compare_products_text_settings' );
            do_settings_sections( 'br_compare_products_text_settings' ); 
        } else if( $active_tab == 'javascript' ) {
            settings_fields( 'br_compare_products_javascript_settings' );
            do_settings_sections( 'br_compare_products_javascript_settings' ); 
        }
        ?>             
        <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'BeRocket_Compare_Products_domain') ?>" />
    </form> 
</div>
<?php
$feature_list = array(
    'Display widget as toolbar on the bottom',
    'Fast compare on popup',
    'Compare by custom taxonomy',
    'Advanced customization',
    '<a href="http://www.addthis.com/" target="_blank">AddThis</a> buttons on campare page',
    'Show only difference or show all button',
    'Custom URL for every compare page',
);
@ include 'settings_footer.php';
?>