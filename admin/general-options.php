<?php
/*
	Inventive stock video and audio player - lite
	http://www.inventive3d.com
	This free wordpress plugin is created for videomakers and musician which have their video and audioproject on different music libraries and 			    need to have an unique video/audio player for their wordpress site.
	Just paste the link from your favourite library and magically it will be played inside your wordpress website with the default wp audio player.
	You can also add a buy link in post and woocommerce products.
	There is support for: audiojungle, pond5, videohive, luckstock, soundcloud.
	It extends wordpress audio/video shortocode, plus there is a cool spectrum analyzer for audio!
	Plugin offers these features in posts/products, a widget and a shortcode.
	In the lite version you can use the only the widget.

	----------------------------------------------------------------------------------------------------------------*/

	/*
	* Written by Francesco Puglisi <info@inventive3d.com>, June 2015
	*/
	
if (isset($_POST['inventive_stock_player_bg_color'])):


$fields_array = array(
	'inventive_stock_player_list',
	'inventive_stock_player_list_buy',
	'inventive_stock_player_list_autoplay',
	'inventive_stock_player_list_loop',
	'inventive_stock_player_list_preload',
	'inventive_stock_player_single_show',
	'inventive_stock_player_single_buy',
	'inventive_stock_player_single_autoplay',
	'inventive_stock_player_single_loop',
	'inventive_stock_player_single_preload',
	'inventive_stock_player_bg_color',
	'inventive_stock_player_buttons_color',
	'inventive_stock_player_spectrum',
	'inventive_stock_player_spectrum_bg_color',
	'inventive_stock_player_spectrum_bars_color',
	'inventive_stock_player_spectrum_background_opacity',
	'inventive_stock_player_spectrum_bars_number',
	'inventive_stock_player_spectrum_bars_width',
	'inventive_stock_player_spectrum_bars_distance',
	'inventive_stock_player_spectrum_position'
	
	);
foreach ($fields_array as $field):
if (isset($_POST[$field])) update_option( $field, sanitize_text_field($_POST[$field]) );
else update_option( $field, "" );
endforeach;
endif;


?>
<div class="wrap">
<h2><?php _e("Inventive stock player","inventive_stock_player"); ?></h2>
<form method="post" action="">

<table class="form-table">
<tbody>

<tr>
<th scope="row"><?php _e("Default configuration", "inventive_stock_player"); ?>:</th>

<td id="front-static-pages">
<?php
$fields_array = array(
	'list' => get_option( 'inventive_stock_player_list'),
	'list_buy' => get_option( 'inventive_stock_player_list_buy'),
	'list_autoplay' => get_option( 'inventive_stock_player_list_autoplay'),
	'list_loop' => get_option( 'inventive_stock_player_list_loop', true ),
	'list_preload' => get_option( 'inventive_stock_player_list_preload' ),
	'single_show' => get_option( 'inventive_stock_player_single_show' ),
	'single_buy' => get_option( 'inventive_stock_player_single_buy' ),
	'single_autoplay' => get_option( 'inventive_stock_player_single_autoplay' ),
	'single_loop' => get_option( 'inventive_stock_player_single_loop' ),
	'single_preload' => get_option( 'inventive_stock_player_single_preload' ),
	'spectrum' => get_option( 'inventive_stock_player_spectrum' )
	);


$stock_fields = new inventive_admin_config();
$stock_fields->stock_fields($fields_array);
?>

</td>
</tr>
<tr>
<th><?php _e("Player background color","inventive_stock_player"); ?></th>
<td>
<input name="inventive_stock_player_bg_color" id="inventive_stock_player_bg_color" value="<?php echo get_option( 'inventive_stock_player_bg_color' ); ?>">
</td>
</tr>
<tr>
<th><?php _e("Spectrum analyzer options","inventive_stock_player"); ?></th>
<td>
<p><?php _e("Background color","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_bg_color" id="inventive_stock_player_spectrum_bg_color" value="<?php echo get_option( 'inventive_stock_player_spectrum_bg_color' ); ?>"></p>
<p><?php _e("Bars color","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_bars_color" id="inventive_stock_player_spectrum_bars_color" value="<?php echo get_option( 'inventive_stock_player_spectrum_bars_color' ); ?>"></p>
<p><?php _e("Background opacity","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_background_opacity" id="inventive_stock_player_spectrum_background_opacity" value="<?php echo get_option( 'inventive_stock_player_spectrum_background_opacity' ); ?>"></p>
<p><?php _e("Bars number","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_bars_number" id="inventive_stock_player_spectrum_bars_number" value="<?php echo get_option( 'inventive_stock_player_spectrum_bars_number' ); ?>"></p>
<p><?php _e("Bars width","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_bars_width" id="inventive_stock_player_spectrum_bars_width" value="<?php echo get_option( 'inventive_stock_player_spectrum_bars_width' ); ?>"></p>
<p><?php _e("Bars distance","inventive_stock_player"); ?> <input name="inventive_stock_player_spectrum_bars_distance" id="inventive_stock_player_spectrum_bars_distance" value="<?php echo get_option( 'inventive_stock_player_spectrum_bars_distance' ); ?>"></p>
<p>
<p><?php _e("Spectrum position","inventive_stock_player"); ?>
<select name="inventive_stock_player_spectrum_position" id="inventive_stock_player_spectrum_position">
<option value="spectrum-bottom-full-width" <?php if (get_option( 'inventive_stock_player_spectrum_position' ) == "spectrum-bottom-full-width") echo "SELECTED"; ?>><?php _e("Bottom full width","inventive_stock_player"); ?></option>
<option value="spectrum-bottom-left" <?php if (get_option( 'inventive_stock_player_spectrum_position' ) == "spectrum-bottom-left") echo "SELECTED"; ?>><?php _e("Bottom left","inventive_stock_player"); ?></option>
<option value="spectrum-top-left" <?php if (get_option( 'inventive_stock_player_spectrum_position' ) == "spectrum-top-left") echo "SELECTED"; ?>><?php _e("top left","inventive_stock_player"); ?></option>
<option value="spectrum-bottom-right" <?php if (get_option( 'inventive_stock_player_spectrum_position' ) == "spectrum-bottom-right") echo "SELECTED"; ?>><?php _e("Bottom right","inventive_stock_player"); ?></option>
<option value="spectrum-top-right" <?php if (get_option( 'inventive_stock_player_spectrum_position' ) == "spectrum-top-right") echo "SELECTED"; ?>><?php _e("top right","inventive_stock_player"); ?></option>
</select>
</p>
</p>
</td>
</tr>

</tbody></table>


<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Salva le modifiche"></p>

</form>
</div>