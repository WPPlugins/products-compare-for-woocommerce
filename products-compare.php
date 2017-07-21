<?php
/**
 * Plugin Name: Products Compare for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/products-compare-for-woocommerce/
 * Description: Allow your users to compare products of your shop by attributes and price.
 * Version: 1.0.5
 * Author: BeRocket
 * Requires at least: 4.0
 * Author URI: http://berocket.com
 * Text Domain: BeRocket_Compare_Products_domain
 * Domain Path: /languages/
 */
define( "BeRocket_Compare_Products_version", '1.0.5' );
define( "BeRocket_Compare_Products_domain", 'BeRocket_Compare_Products_domain'); 
define( "Compare_Products_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_Compare_Products_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'includes/functions.php');
require_once(plugin_dir_path( __FILE__ ).'includes/widget.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Class BeRocket_Compare_Products
 */
class BeRocket_Compare_Products {

    public static $info = array( 
        'id'        => 4,
        'version'   => BeRocket_Compare_Products_version,
        'plugin'    => '',
        'slug'      => '',
        'key'       => '',
        'name'      => ''
    );

    /**
     * Defaults values
     */
    public static $defaults = array(
        'br_compare_products_general_settings'  => array(
            'compare_page'                          => '',
            'attributes'                            => array(),
        ),
        'br_compare_products_style_settings'    => array(
            'button'                                => array(
                'bcolor'                                => '999999',
                'bwidth'                                => '0',
                'bradius'                               => '0',
                'fontsize'                              => '16',
                'fcolor'                                => '333333',
                'backcolor'                             => '9999ff',
            ),
            'table'                                 => array(
                'colwidth'                              => '200',
                'imgwidth'                              => '',
                'toppadding'                            => '0',
                'backcolor'                             => 'ffffff',
                'backcolorsame'                         => '',
                'margintop'                             => '',
                'marginbottom'                          => '',
                'marginleft'                            => '',
                'marginright'                           => '',
                'paddingtop'                            => '',
                'paddingbottom'                         => '',
                'paddingleft'                           => '',
                'paddingright'                          => '',
                'top'                                   => '',
                'bottom'                                => '',
                'left'                                  => '',
                'right'                                 => '',
                'bordercolor'                           => '',
                'samecolor'                             => '',
                'samecolorhover'                        => '',
            ),
        ),
        'br_compare_products_text_settings'     => array(
            'compare'                               => 'Compare',
            'add_compare'                           => 'Compare',
            'added_compare'                         => 'Added',
            'attribute'                             => 'Attributes',
            'availability'                          => 'Availability',
        ),
        'br_compare_products_javascript_settings'   => array(
            'before_load'                               => '',
            'after_load'                                => '',
        ),
        'br_compare_products_license_settings'  => array(
            'plugin_key'                            => '',
        ),
    );
    public static $values = array(
        'settings_name' => '',
        'option_page'   => 'br-compare-products',
        'premium_slug'  => 'woocommerce-products-compare',
    );
    
    function __construct () {
        global $br_wp_query_not_main;
        $br_wp_query_not_main = false;
        register_activation_hook(__FILE__, array( __CLASS__, 'activation' ) );

        if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && br_get_woocommerce_version() >= 2.1 ) {
            add_action ( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'get_compare_button' ), 30 );
            add_action ( 'lgv_advanced_after_price', array( __CLASS__, 'get_compare_button' ), 30 );
            add_action ( 'woocommerce_single_product_summary', array( __CLASS__, 'get_compare_button' ), 38 );
            add_action ( 'init', array( __CLASS__, 'init' ) );
            add_action ( 'admin_init', array( __CLASS__, 'admin_init' ) );
            add_action ( 'admin_menu', array( __CLASS__, 'options' ) );
            add_action ( 'wp_head', array( __CLASS__, 'wp_head_style' ) );
            add_filter ( 'the_content', array( __CLASS__, 'compare_page' ) );
            add_action ( "widgets_init", array ( __CLASS__, 'widgets_init' ) );
            add_action( "wp_ajax_br_get_compare_products", array ( __CLASS__, 'listener_products' ) );
            add_action( "wp_ajax_nopriv_br_get_compare_products", array ( __CLASS__, 'listener_products' ) );
            add_action( "wp_ajax_br_get_compare_list", array ( __CLASS__, 'compare_list' ) );
            add_action( "wp_ajax_nopriv_br_get_compare_list", array ( __CLASS__, 'compare_list' ) );
            add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
            $plugin_base_slug = plugin_basename( __FILE__ );
            add_filter( 'plugin_action_links_' . $plugin_base_slug, array( __CLASS__, 'plugin_action_links' ) );
        }
    }
    public static function plugin_action_links($links) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page='.self::$values['option_page'] ) . '" title="' . __( 'View Plugin Settings', 'BeRocket_products_label_domain' ) . '">' . __( 'Settings', 'BeRocket_products_label_domain' ) . '</a>',
		);
		return array_merge( $action_links, $links );
    }
    public static function plugin_row_meta($links, $file) {
        $plugin_base_slug = plugin_basename( __FILE__ );
        if ( $file == $plugin_base_slug ) {
			$row_meta = array(
				'docs'    => '<a href="http://berocket.com/docs/plugin/'.self::$values['premium_slug'].'" title="' . __( 'View Plugin Documentation', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Docs', 'BeRocket_products_label_domain' ) . '</a>',
				'premium'    => '<a href="http://berocket.com/product/'.self::$values['premium_slug'].'" title="' . __( 'View Premium Version Page', 'BeRocket_products_label_domain' ) . '" target="_blank">' . __( 'Premium Version', 'BeRocket_products_label_domain' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
    }
    public static function widgets_init() {
        register_widget("berocket_compare_products_widget");
    }

    public static function activation () {
        $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
        if ( ! $options['compare_page'] ) {
            $compare_page = array(
                'post_title' => 'Compare',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            );

            $post_id = wp_insert_post($compare_page);
            $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
            $options['compare_page'] = $post_id;
            update_option('br_compare_products_general_settings', $options);
        }
    }

    public static function compare_list() {
        set_query_var( 'is_full_screen', true );
        self::br_get_template_part('compare');
        wp_die();
    }

    public static function compare_page ($content) {
        global $wp_query, $br_wp_query_not_main;
        $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
        $page = $options['compare_page'];
        $page_id = @ $wp_query->queried_object->ID;
        if( ! empty( $page_id ) ) {
            $default_language = apply_filters( 'wpml_default_language', NULL );
            $page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true, $default_language );
            if ( $page == @ $page_id && ! @ $br_wp_query_not_main ) {
                $br_compare_uri = add_query_arg('compare', @ $_COOKIE['br_products_compare'], get_page_link($page));
                ?>
                <script>
                var br_compare_page = "<?php echo get_page_link($page); ?>";
                var br_compare_uri = "<?php echo $br_compare_uri; ?>";
                jQuery(document).ready(function() {
                    
                });
                </script>
                <?php if( @ $options['addthisID'] ) { ?>
                <div class="addthis_sharing_toolbox" data-url="<?php echo $br_compare_uri; ?>"></div>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $options['addthisID']; ?>"></script>
                <?php
                }
                self::br_get_template_part('compare');
                $br_wp_query_not_main = true;
            }
        }
        return $content;
    }

    public static function init () {
        wp_register_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'font-awesome' );
        wp_register_style( 'berocket_compare_products_style', plugins_url( 'css/products_compare.css', __FILE__ ), "", BeRocket_Compare_Products_version );
        wp_enqueue_style( 'berocket_compare_products_style' );
        wp_enqueue_script( 'berocket_jquery_cookie', plugins_url( 'js/jquery.cookie.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        wp_enqueue_script( 'berocket_compare_products_script', plugins_url( 'js/products_compare.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        wp_enqueue_script( 'jquery-mousewheel', plugins_url( 'js/jquery.mousewheel.min.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        $javascript = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_javascript_settings' );
        wp_localize_script(
            'berocket_compare_products_script',
            'the_compare_products_data',
            array(
                'ajax_url'      => admin_url( 'admin-ajax.php' ),
                'user_func'     => $javascript,
                'home_url'      => site_url(),
                'hide_same'     => __( 'Hide attributes with same values', 'BeRocket_Compare_Products_domain' ),
                'show_same'     => __( 'Show attributes with same values', 'BeRocket_Compare_Products_domain' ),
            )
        );
    }

    public static function admin_init () {
        wp_enqueue_script( 'berocket_aapf_widget-colorpicker', plugins_url( 'js/colpick.js', __FILE__ ), array( 'jquery' ) );
        wp_enqueue_script( 'berocket_compare_products_admin_script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
        wp_register_style( 'berocket_aapf_widget-colorpicker-style', plugins_url( 'css/colpick.css', __FILE__ ) );
        wp_enqueue_style( 'berocket_aapf_widget-colorpicker-style' );
        wp_register_style( 'berocket_compare_products_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_Compare_Products_version );
        wp_enqueue_style( 'berocket_compare_products_admin_style' );
        register_setting('br_compare_products_general_settings', 'br_compare_products_general_settings', array( __CLASS__, 'sanitize_compare_products_option' ));
        register_setting('br_compare_products_style_settings', 'br_compare_products_style_settings', array( __CLASS__, 'sanitize_compare_products_option' ));
        register_setting('br_compare_products_text_settings', 'br_compare_products_text_settings', array( __CLASS__, 'sanitize_compare_products_option' ));
        register_setting('br_compare_products_javascript_settings', 'br_compare_products_javascript_settings', array( __CLASS__, 'sanitize_compare_products_option' ));
        register_setting('br_compare_products_license_settings', 'br_compare_products_license_settings', array( __CLASS__, 'sanitize_compare_products_option' ));
        add_settings_section( 
            'br_compare_products_general_page',
            'General Settings',
            'br_compare_products_general_callback',
            'br_compare_products_general_settings'
        );

        add_settings_section( 
            'br_compare_products_style_page',
            'Style Settings',
            'br_compare_products_style_callback',
            'br_compare_products_style_settings'
        );

        add_settings_section( 
            'br_compare_products_text_page',
            'Style Settings',
            'br_compare_products_text_callback',
            'br_compare_products_text_settings'
        );

        add_settings_section( 
            'br_compare_products_javascript_page',
            'JavaScript Settings',
            'br_compare_products_javascript_callback',
            'br_compare_products_javascript_settings'
        );

        add_settings_section( 
            'br_compare_products_license_page',
            'License Settings',
            'br_compare_products_license_callback',
            'br_compare_products_license_settings'
        );
    }

    public static function options() {
        add_submenu_page( 'woocommerce', __('Compare Products settings', 'BeRocket_Compare_Products_domain'), __('Compare Products', 'BeRocket_Compare_Products_domain'), 'manage_options', 'br-compare-products', array(
            __CLASS__,
            'option_form'
        ) );
    }
    /**
     * Function add options form to settings page
     *
     * @access public
     *
     * @return void
     */
    public static function option_form() {
        $plugin_info = get_plugin_data(__FILE__, false, true);
        include Compare_Products_TEMPLATE_PATH . "settings.php";
    }
    /**
     * Load template
     *
     * @access public
     *
     * @param string $name template name
     *
     * @return void
     */
    public static function br_get_template_part( $name = '' ) {
        $template = '';

        // Look in your_child_theme/woocommerce-filters/name.php
        if ( $name ) {
            $template = locate_template( "woocommerce-compare-products/{$name}.php" );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( Compare_Products_TEMPLATE_PATH . "{$name}.php" ) ) {
            $template = Compare_Products_TEMPLATE_PATH . "{$name}.php";
        }

        // Allow 3rd party plugin filter template file from their plugin
        $template = apply_filters( 'compare_products_get_template_part', $template, $name );

        if ( $template ) {
            load_template( $template, false );
        }
    }
    public static function get_compare_button() {
        global $product, $wp_query;
        $product_id = br_wc_get_product_id($product);
        $default_language = apply_filters( 'wpml_default_language', NULL );
        $product_id = apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_language );
        $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_general_settings' );
        $text = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_text_settings' );
        echo '<a class="add_to_cart_button button br_compare_button';
        if ( self::is_set_cookie($product_id) ) {
            echo ' br_compare_added';
        }
        if ( @ $options['fast_compare'] ) {
            echo ' berocket_product_smart_compare';
        }
        $page_compare = $options['compare_page'];
        echo ' br_product_'.$product_id.'" data-id="'.$product_id.'" href="'.get_page_link($page_compare).'"><i class="fa fa-square-o"></i><i class="fa fa-check-square-o"></i><span class="br_compare_button_text" data-added="'.$text['added_compare'].'" data-not_added="'.$text['add_compare'].'">'.( self::is_set_cookie($product_id) ? $text['added_compare'] : $text['add_compare'] ).'</span></a>';
    }
    public static function get_all_compare_products() {
        if ( @ $_COOKIE['br_products_compare'] ) {
            $cookie = @ $_COOKIE['br_products_compare'];
            $products = explode( ',', $cookie );
            return $products;
        } else {
            return false;
        }
    }
    public static function is_set_cookie( $id ) {
        if ( @ $_COOKIE['br_products_compare'] ) {
            $cookie = @ $_COOKIE['br_products_compare'];
            if ( preg_match( "/(^".$id.",)|(,".$id."$)|(,".$id.",)|(^".$id."$)/", $cookie ) ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function listener_products() {
        set_query_var( 'type', 'image' );
        self::br_get_template_part('selected_products');
        wp_die();
    }
    public static function sanitize_compare_products_option( $input ) {
        $default = BeRocket_Compare_Products::$defaults[$input['settings_name']];
        $result = self::recursive_array_set( $default, $input );
        return $result;
    }
    public static function recursive_array_set( $default, $options ) {
        foreach( $default as $key => $value ) {
            if( array_key_exists( $key, $options ) ) {
                if( is_array( $value ) ) {
                    if( is_array( $options[$key] ) ) {
                        $result[$key] = self::recursive_array_set( $value, $options[$key] );
                    } else {
                        $result[$key] = self::recursive_array_set( $value, array() );
                    }
                } else {
                    $result[$key] = $options[$key];
                }
            } else {
                if( is_array( $value ) ) {
                    $result[$key] = self::recursive_array_set( $value, array() );
                } else {
                    $result[$key] = '';
                }
            }
        }
        foreach( $options as $key => $value ) {
            if( ! array_key_exists( $key, $result ) ) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
    public static function get_compare_products_option( $option_name ) {
        $options = get_option( $option_name );
        if ( @ $options && is_array ( $options ) ) {
            $options = array_merge( BeRocket_Compare_Products::$defaults[$option_name], $options );
        } else {
            $options = BeRocket_Compare_Products::$defaults[$option_name];
        }
        return $options;
    }
    public static function wp_head_style() {
        $options = BeRocket_Compare_Products::get_compare_products_option ( 'br_compare_products_style_settings' );
        echo '<style>';
        echo '.berocket_compare_widget_start .berocket_compare_widget .berocket_open_compare ,';
        echo '.berocket_compare_widget_toolbar .berocket_compare_widget .berocket_open_compare {';
        echo 'border-color: #'.str_replace( '#', '', $options['button']['bcolor'] ).';';
        echo 'border-width: '.$options['button']['bwidth'].'px;';
        echo 'border-radius: '.$options['button']['bradius'].'px;';
        echo 'font-size: '.$options['button']['fontsize'].'px;';
        echo 'color: #'.str_replace( '#', '', $options['button']['fcolor'] ).';';
        echo 'background-color: #'.str_replace( '#', '', $options['button']['backcolor'] ).';';
        echo '}';
        echo '.berocket_compare_box .br_moved_attr tr td {';
        echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
        echo '}';
        echo '.berocket_compare_box .berocket_compare_table_hidden {';
        echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
        echo '}';
        echo 'div.berocket_compare_box.berocket_full_screen_box {';
        echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
        echo '}';
        echo 'div.berocket_compare_box .berocket_compare_table td {';
        echo 'min-width: '.$options['table']['colwidth'].'px;';
        echo '}';
        echo 'div.berocket_compare_box .br_moved_attr tr td {';
        echo 'min-width: '.$options['table']['colwidth'].'px;';
        echo '}';
        echo '.berocket_compare_box .berocket_compare_table img {';
        echo 'width: '.$options['table']['imgwidth'].'px;';
        echo '}';
        echo '</style>';
    }
}

new BeRocket_Compare_Products;
