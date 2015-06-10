<?php
/*
	Plugin Name: Inventive stock video and audio player - lite
	Plugin URI: http://www.inventive3d.com
	Description: This free wordpress plugin is created for videomakers and musician which have their video and audioproject on different music libraries and 			    need to have an unique video/audio player for their wordpress site.
	Just paste the link from your favourite library and magically it will be played inside your wordpress website with the default wp audio player.
	You can also add a buy link in post and woocommerce products.
	There is support for: audiojungle, pond5, videohive, luckstock, soundcloud.
	It extends wordpress audio/video shortocode, plus there is a cool spectrum analyzer for audio!
	Plugin offers these features in posts/products, a widget and a shortcode.
	In the lite version you can use the only the widget.
	Author: Francesco Puglisi - Inventive 3d
	Author URI: http://www.inventive3d.com/
	Version: 1
	Text Domain: inventive-stock-video-audio-player-lite
    Domain Path: /
	License: GNU General Public License v2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html

	----------------------------------------------------------------------------------------------------------------*/

	/*
	* Written by Francesco Puglisi <info@inventive3d.com>, June 2015
	*/
	add_action('init','inventive_stock_player_textdomain'); 

function inventive_stock_player_textdomain(){

        load_plugin_textdomain( 'inventive_stock_player', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

}

		function load_inventive_stock_player_admin_script() {

		wp_register_style( '1nventive_stock_player', plugins_url( 'style.css' , __FILE__ ), '1.5', true );
wp_enqueue_style( '1nventive_stock_player' );
		}
		
		add_action( 'admin_enqueue_scripts', 'load_inventive_stock_player_admin_script' );
		
		
			function load_inventive_stock_player_script() {

		wp_register_style( '1nventive_stock_player', plugins_url( 'style.css' , __FILE__ ), '1.5', true );
wp_enqueue_style( '1nventive_stock_player' );
 wp_enqueue_script( 'inventive-stock-player-script-handle', plugins_url( 'js/scripts.js', __FILE__ ), '', false, true ); 
		}
		
		
		add_action( 'wp_enqueue_scripts', 'load_inventive_stock_player_script' );
		
		
		if (is_admin()):
		
		add_action( 'admin_enqueue_scripts', 'inventive_stock_player_add_color_picker' );
		function inventive_stock_player_add_color_picker( $hook ) {
 
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'inventive-stock-player-admin-script-handle', plugins_url( 'js/admin_scripts.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    
}
		
		endif;
		
		//include main class
		include('inc/classes.php');
		include('inc/widget.php');

 
// define the wp_mediaelement_fallback callback
function filter_wp_mediaelement_fallback( $s, $url ) 
{
    return false;
};
        
// add the filter
add_filter( 'wp_mediaelement_fallback', 'filter_wp_mediaelement_fallback', 10, 2 );

include_once('inc/filters.php');

/** Step 2 (from text above). */
add_action( 'admin_menu', 'inventive_stock_player_menu' );

/** Step 1. */
function inventive_stock_player_menu() {
	add_options_page( 'Inventive stock player', 'Inventive stock player', 'manage_options', '', 'inventive_stock_player_general_options' );
}

/** Step 3. */
function inventive_stock_player_general_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	include('admin/general-options.php');
	echo '</div>';
}


////we add custom color to player
function inventive_stock_player_custom_css()
		{                   
		$player_background = get_option('inventive_stock_player_bg_color');
		
		echo '<script>
		var inventive_stock_player_bars_color,inventive_stock_player_bars_number, inventive_stock_player_bars_width, inventive_stock_player_bars_distance;
		';
		if (get_option('inventive_stock_player_spectrum_bars_color')) echo 'inventive_stock_player_bars_color = "'.get_option('inventive_stock_player_spectrum_bars_color').'";';
		if (get_option('inventive_stock_player_spectrum_bars_number')) echo 'inventive_stock_player_bars_number = "'.get_option('inventive_stock_player_spectrum_bars_number').'";';
		if (get_option('inventive_stock_player_spectrum_bars_width')) echo 'inventive_stock_player_bars_width = "'.get_option('inventive_stock_player_spectrum_bars_width').'";';
		if (get_option('inventive_stock_player_spectrum_bars_distance')) echo 'inventive_stock_player_bars_distance = "'.get_option('inventive_stock_player_spectrum_bars_distance').'";';
		
		echo '</script>';
		
		echo '<style>';	
		
		echo '.mejs-controls {
		background: '.$player_background.' !important;
		
		}';
		
		echo ' .inventive_stock_audio_spectrum {';
		
	
			if (get_option('inventive_stock_player_spectrum_bg_color')) echo 'background: '.get_option('inventive_stock_player_spectrum_bg_color');
			
			
			echo '}';
		
		echo  '</style>';
		
		if (get_option('inventive_stock_player_spectrum_position')) $position = get_option('inventive_stock_player_spectrum_position');
		else $position = "spectrum-bottom-full-width";
		echo '<canvas id="analyser_render" class="inventive_stock_audio_spectrum '.$position.'"></canvas>';
		
		}
		add_action('wp_footer', 'inventive_stock_player_custom_css');
		
		
		add_action( 'wp_enqueue_scripts', 'inventive_stock_player_mediaelement_settings' );
function inventive_stock_player_mediaelement_settings() {
        wp_deregister_script( 'wp-mediaelement' );
        wp_register_script( 'wp-mediaelement', plugins_url("/js/wp-mediaelement.js", __FILE__), array( 'mediaelement' ), false, true );
}

     
?>