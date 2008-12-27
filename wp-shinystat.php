<?php
/*
Plugin Name: ShinyStat Widget
Plugin URI: http://www.ruscibar.it/wp-shinystat
Description: Adds ShinyStat counter to your blog.
Author: Paolo Rossi
Version: 0.2
Author URI: http://www.ruscibar.it/

Credits: based on MyBlogLog Widget
*/

### Create Text Domain For Translations
load_plugin_textdomain('wp-shinystat', 'wp-content/plugins/wp-shinystat');

// This gets called at the plugins_loaded action
function widget_shinystat_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// This saves options and prints the widget's config form.
	function widget_shinystat_control() {
		$options = $newoptions = get_option('widget_shinystat');
		if ( $_POST['shinystat-submit'] ) {
      $valid_code = trim(stripslashes($_POST['shinystat-code']));

      // Making sure it's a ShinyStat widget
      $n_m = preg_match('/<(script|a)(.+)(src|href)(.+)(shinystat\.(com|it)\/)(.+)<\/(script|a)>/im', $valid_code);
      if($n_m == 0) {
        $valid_code = '';
      }
      /*
      */
			$newoptions['shinystat_code'] = $valid_code;
      
      $frameless = trim(stripslashes($_POST['shinystat-frameless']));
      if ($frameless)
        $frameless = true;
      else
        $frameless = false;
              
  		$newoptions['shinystat_frameless'] = $frameless;
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_shinystat', $options);
		}
	?>
				<div>
				  <p style="text-align:left"><?php _e('ShinyStat provides you real time web analytics and web counter for your blog.', 'wp-shinystat'); ?></p>
				  <p style="text-align:left"><?php _e('To make it work, you must <a href="http://www.shinystat.com">register</a>, copy the HTML code provided by ShinyStat and then paste it below.', 'wp-shinystat'); ?></p>
         	<textarea id="shinystat-code" name="shinystat-code" style="width:370px;height:110px;padding:0px;margin:0px;"><?php echo wp_specialchars($options['shinystat_code'], true); ?></textarea>
         	<p style="text-align:left"><input type="checkbox" id="shinystat-frameless" name="shinystat-frameless" value="true" <?php if ($options['shinystat_frameless'] == true) { echo 'checked'; }?>/><label for="shinystat-frameless"><?php _e('Display widget without frame', 'wp-shinystat') ?></label></p>
				  <input type="hidden" name="shinystat-submit" id="shinystat-submit" value="1" />
				</div>
	<?php
	}

	// This prints the widget
	function widget_shinystat($args) {
		global $user_ID;
		global $user_level;

		extract($args);
		$defaults = array();
		$options = (array) get_option('widget_shinystat');

		foreach ( $defaults as $key => $value )
			if ( !isset($options[$key]) )
				$options[$key] = $defaults[$key];


		if ( $options['shinystat_code'] && ( !$user_ID || intval($user_level) <= 1 ) )
		{
  		if ($options['shinystat_frameless'] == true) {
    		echo '<li style="background-color: transparent; background-image: none">';
      } else {
    		echo $before_widget;
			  echo $before_title . "" . $after_title;
			}
			echo $options['shinystat_code'];
  		if ($options['shinystat_frameless'] == true)
  			echo '</li>';
      else  
    		echo $after_widget;
		}
		?>

<?php
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget('ShinyStat', 'widget_shinystat');
	register_widget_control('ShinyStat', 'widget_shinystat_control', 370, 300);
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_shinystat_init');

?>
