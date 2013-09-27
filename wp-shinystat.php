<?php
/*
Plugin Name: ShinyStat Widget
Plugin URI: http://www.ruscibar.it/wp-shinystat
Description: Adds ShinyStat counter to your Wordpress
Author: Paolo Rossi
Version: 0.3
Author URI: http://www.ruscibar.it/

*/

// Create Text Domain For Translations
load_plugin_textdomain('wp-shinystat', 'wp-content/plugins/wp-shinystat');

// The example widget class
class Shinystat_Widget extends WP_Widget {

// Widget Settings
	public function __construct() {
		$widget_ops = array( 'classname' => 'wp-shinystat', 'description' => __('Adds ShinyStat counter to your blog', 'wp-shinystat') );
		$control_ops = array( 'id_base' => 'widget-shinystat');
		$this->WP_Widget( 'widget-shinystat', __('Shinystat Widget', 'wp-shinystat'), $widget_ops, $control_ops );
	}


  // display the widget
  public function widget($args, $instance) {
		global $user_ID;
		global $user_level;

		if ( $instance['shinystat_code'] && ( !$user_ID || intval($user_level) <= 1 ) ) {
      echo $args['before_widget'];

      // Add tracker
      if ( $instance['shinystat_code'] ) {
        echo $instance['shinystat_code'];
      }

      echo $args['after_widget'];
    }
  }

  // update the widget
  public function update($new_instance, $old_instance) {
    $instance = array();

    // Validate tracker
    $valid_code = trim(stripslashes($new_instance['shinystat_code']));
    $n_m = preg_match('/<(script|a)(.+)(src|href)(.+)(shinystat\.(com|it)\/)(.+)<\/(script|a)>/im', $valid_code);
    if ($n_m == 0) {
      $valid_code = '<!-- NOT VALID CODE -->';
    }

    //Strip tags from title and name to remove HTML
    $instance['shinystat_code'] = ( ! empty( $valid_code ) ) ? $valid_code : '<!-- Zoo bar -->';

    return $instance;
  }

  // and of course the form for the widget options
  public function form($instance) {
    //Set up some default widget settings.
    $defaults = array( 'shinystat_code' => '');
    $instance = wp_parse_args( (array) $instance, $defaults );
?>
<div>
  <p style="text-align:left"><?php _e('ShinyStat provides real time web analytics and web counter for your website.', 'wp-shinystat'); ?></p>
  <p style="text-align:left"><?php _e('To make it work, you must <a href="http://www.shinystat.com">register</a>, copy the HTML code provided by ShinyStat and then paste it below.', 'wp-shinystat'); ?></p>
  <textarea id="<?php echo $this->get_field_id( 'shinystat_code' ); ?>" name="<?php echo $this->get_field_name( 'shinystat_code' ); ?>" style="width:370px;height:110px;padding:0px;margin:0px;"><?php echo $instance['shinystat_code']; ?></textarea>
  <input type="hidden" name="shinystat-submit" id="shinystat-submit" value="1" />
</div>
<?php
  }
}

// function to register my widget
function widget_shinystat_init() {
    register_widget( 'Shinystat_Widget' );
}

add_action( 'widgets_init', 'widget_shinystat_init' );

// Add settings link on plugin page
function your_plugin_settings_link($links) { 
  $settings_link = '<a href="widgets.php">' . __('Manage', 'wp-shinystat') . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );

?>
