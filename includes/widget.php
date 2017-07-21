<?php
/**
 * Compare Products widget
 */
class BeRocket_Compare_Products_Widget extends WP_Widget 
{
    public static $defaults = array(
        'title'         => '',
        'type'          => 'image',
    );
	public function __construct() {
        parent::__construct("berocket_compare_products_widget", "WooCommerce Products Compare",
            array("description" => "WooCommerce Products Compare List"));
    }
    /**
     * WordPress widget for display Compare Products
     */
    public function widget($args, $instance)
    {
        $settings = array_merge( BeRocket_Compare_Products_Widget::$defaults, $instance );
        set_query_var( 'title', apply_filters( 'compare_products_widget_title', $settings['title'] ) );
        set_query_var( 'type', apply_filters( 'compare_products_widget_type', $settings['type'] ) );
        BeRocket_Compare_Products::br_get_template_part('selected_products');
	}
    /**
     * Update widget settings
     */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( @ $new_instance['title'] );
        $instance['type'] = $new_instance['type'];
		return $instance;
	}
    /**
     * Widget settings form
     */
	public function form($instance)
	{
		$title = @ strip_tags($instance['title']);
		?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( @ $title ); ?>" />
        </p>
        <p>
            <select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
                <option value="image" <?php if ( @ $instance['type'] == 'image' ) echo 'selected'; ?>><?php _e( 'Image', 'BeRocket_Compare_Products_domain' ) ?></option>
                <option value="text" <?php if ( @ $instance['type'] == 'text' ) echo 'selected'; ?>><?php _e( 'Text', 'BeRocket_Compare_Products_domain' ) ?></option>
            </select>
        </p>
		<?php
	}
}
?>