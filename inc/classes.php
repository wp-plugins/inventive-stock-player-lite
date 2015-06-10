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
class inventive_admin_config {

function store_file($link,$type)
{
///creiamo cartella in uploads se non esiste
$upload_dir = wp_upload_dir(); 
$dirname = $upload_dir['basedir'].'/stock-audio-video';
if( ! file_exists( $dirname ) ):
    wp_mkdir_p( $dirname );	
	endif;
	
////estrepoliamo l'indirizzo
   		$a = array('link' => $link);
		$stock_player = new inventive_stock_player();
	    $address = $stock_player->get_player("single",$a,"address",$type);
		
$store_stream = new inventive_admin_config();
$substitutions = array(":","/",".","=","-","?");
$filename = substr(str_replace($substitutions,"",$address),0,50);
$store_stream->save_stream($address, $upload_dir['basedir'].'/stock-audio-video'."/".$filename.".mp3");	

return $filename;
}
	
function save_stream($source, $destination)
{

	try {
	if (!file_exists( $destination )) :
		$data = file_get_contents($source);
		$handle = fopen($destination, "w");
		fwrite($handle, $data);
		fclose($handle);
		return true;	
		endif;
	} catch (Exception $e) {
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
	}
	return false;

}
	
function stock_fields($fields_array){
	
	
    //spectrum
	echo '<p>'.__('Show spectrum analyzer?','inventive-stock-player').'';
	?>
	<input type="checkbox" name="inventive_stock_player_spectrum" id="inventive_stock_player_spectrum" class="inventive_stock_player_checkbox" value ="1" <?php if ($fields_array['spectrum'] == 1) echo "checked" ?>/></p>
	 
 <?php
	
}

}

class inventive_stock_player_libraries {
	
public function spectrum_check($id)
{
$vars = new inventive_stock_player_libraries();
$dirname = $vars->upload_dir("basedir"); 


if (( get_post_meta( $id, 'inventive_stock_player_spectrum', true ) == 1) && (file_exists( $dirname.'/'.get_post_meta( $id, 'inventive_stock_player_link_local', true ).".mp3" ))):
return true;	
endif;





}
	
public function upload_dir($type)
{
$upload_dir = wp_upload_dir(); 
$dirname = $upload_dir[$type].'/stock-audio-video';
return $dirname;	
}


public function audio_jungle($link,$id,$array_player,$type)
{
	$vars = new inventive_stock_player_libraries();
//if spectrum is set to yes and we have local mp3 file

		if ( ( ($vars->spectrum_check($id) === true) && ($type != "widget") ) || ((isset($array_player['link_local']) && $array_player['link_local'] != ""))) :

	$dirname = $vars->upload_dir("baseurl"); 
	if ($type == "widget") $address = $dirname.'/'.$array_player['link_local'].".mp3";
	else $address = $dirname.'/'.get_post_meta( $id, 'inventive_stock_player_link_local', true ).".mp3";
	return $address;
	
	else:	
	
if (isset($url_file)) $url_file = get_post_meta( $id, 'inventive_stock_player_audio_jungle', true );
else $url_file = "";
	
	if (!$url_file):
	///si deve fare un fetch della pagina ed andare a prendere l'url del preview
	$response = wp_remote_get( $link );
	if( is_array($response) ) :
    $html = $response['body'];

	$chunk = explode('<a href="https://0.s3.envato.com/files/',$html);
	$file_id = explode("preview.mp3",$chunk[1]);
	$address = 'https://0.s3.envato.com/files/'.$file_id[0].'preview.mp3';
	
	if ($id) update_post_meta(get_the_ID(), 'inventive_stock_player_audio_jungle', $address);
	
	endif; //se il wp_Remote_get è andato a buon fine

	else:
    //we have already saved our meta, to avoid scraping audiojungle page
	$address = $url_file;
	
	endif; //

	return $address;	
	
	endif;
}


public function soundcloud($link,$id,$array_player,$type)
{
	
	$vars = new inventive_stock_player_libraries();

//if spectrum is set to yes and we have local mp3 file
	if ( ( ($vars->spectrum_check($id) === true) && ($type != "widget") ) || ((isset($array_player['link_local']) && $array_player['link_local'] != ""))) :
	
	
	$dirname = $vars->upload_dir("baseurl"); 
	if ($type == "widget") $address = $dirname.'/'.$array_player['link_local'].".mp3";
	else $address = $dirname.'/'.get_post_meta( $id, 'inventive_stock_player_link_local', true ).".mp3";
	return $address;


else:

if (isset($url_file)) $url_file = get_post_meta( get_the_ID(), 'inventive_stock_player_soundcloud', true );
else $url_file = "";
	
	if (!$url_file):
	///si deve fare un fetch della pagina ed andare a prendere l'url del preview
	
	$data = json_decode(file_get_contents('https://api.soundcloud.com/resolve.json?url='.$link.'&client_id=09a81026441cfd500abfd7f613cd4c9c'), true);
	$stream = json_decode(file_get_contents('https://api.sndcdn.com/i1/tracks/'.$data['id'].'/streams?client_id=09a81026441cfd500abfd7f613cd4c9c'),true);
	$address = $stream["http_mp3_128_url"];
	
	if ($id) :
	update_post_meta(get_the_ID(), 'inventive_stock_player_luckstock', $address);
	endif;
	
	else:
    //we have already saved our meta, to avoid scraping page
	$address = $url_file;

	
	endif;
	
	return $address;
	
	endif;
	

	
}
	
}

class inventive_stock_player {
	
public function buy_button()
{
	
}
public function autoplay($target)
{
global $post;

if ($target == "list"):

if (get_post_meta( get_the_ID(), 'inventive_stock_player_list_autoplay', true ) == 1) $autoplay = "on";
																			 else $autoplay = "";
																			 
else :

if (get_post_meta( get_the_ID(), 'inventive_stock_player_single_autoplay', true ) == 1) $autoplay = "on";
																			   else $autoplay = "";	

endif;

return $autoplay;
}

public function loop($target)
{
global $post;

if ($target == "list"):

if (get_post_meta( get_the_ID(), 'inventive_stock_player_list_loop', true ) == 1) $loop = "on";
																			 else $loop = "";
																			 
else :

if (get_post_meta( get_the_ID(), 'inventive_stock_player_single_loop', true ) == 1) $loop = "on";
																			   else $loop = "";	

endif;

return $loop;
}

public function preload($target)
{
global $post;

if ($target == "list"):

if (get_post_meta( get_the_ID(), 'inventive_stock_player_list_preload', true ) == 1) $preload = "auto";
																			    else $preload = "none";
																			 
else :

if (get_post_meta( get_the_ID(), 'inventive_stock_player_single_preload', true ) == 1) $preload = "auto";
																			      else $preload = "none";	

endif;

return $preload;
}
	
public function get_player($target,$array_player,$what,$specific_id,$post_type) {
	
	if (is_array($array_player)):
	$link = $array_player['link'];
	$id = "";
	else :
	$link = "";
	$id = get_the_ID();
	endif;
   
	$type = "";
	$attr = "";
	$poster = "";
	$result = "";
	$stock_player_libraries = new inventive_stock_player_libraries();
	$stock_player_type = new inventive_stock_player();
	
	global $product;
	
	if ($link == "") $link = get_post_meta( $id, 'inventive_stock_player_link', true );
	
if ($link != "") :

	
	///audiojungle
	if  (strpos($link, "audiojungle.net") !== false):
	$type = "audio";
	
	$address = $stock_player_libraries->audio_jungle($link,$id,$array_player,$post_type);

	endif;
	
	///soundcloud
	if  (strpos($link, "soundcloud.com") !== false):
	
	$type = "audio";
	$address = $stock_player_libraries->soundcloud($link,$id,$array_player,$post_type);
	
	endif;
	
	
	
	///////////////////config for video/audio
	if (is_array($array_player)):
	
	$autoplay = $array_player['autoplay'];
	$loop = $array_player['loop'];
	$preload = $array_player['preload'];
	
	$id = $specific_id;
	
	
	else :
	
	$autoplay = $stock_player_type->autoplay($target);
	$loop = $stock_player_type->loop($target);
	$preload = $stock_player_type->preload($target);
	
	endif;
	
	
    if ($what == "address"):
	return $address;
	else:
	
	if ($type == "audio"):
	
	
	$attr = array(
	'src'      => $address,
	'loop'     => $loop,
	'autoplay' => $autoplay,
	'preload' => $preload
	);
	
	$result = wp_audio_shortcode( $attr );
	
	
	if (is_array($array_player)):

	if ( $array_player['spectrum'] == 1) :
	$spectrum = "inventive-stock-player-spectrum-yes";
	else:
	$spectrum = "";
	endif;
	
	else:
	///if the files has spectrum analyzer enabled and eveything is ok
	if ( $stock_player_libraries->spectrum_check($id) === true) :
	$spectrum = "inventive-stock-player-spectrum-yes";
	else:
	$spectrum = "";
	endif;
	
	endif;
	
	
	endif;
	
	if ($type == "video"):
	$spectrum = "";
	$attr = array(
	'src'      => $address,
	'poster' => $poster,
	'loop'     => $loop,
	'autoplay' => $autoplay,
	'preload' => $preload,
	'width' => '',
	'height' => ''
	);
	$result = wp_video_shortcode( $attr );
	endif;

if (!isset($spectrum)) $spectrum = "";
return '<div class="inventive-stock-audio-container inventive-hidden-stock-audio-container" id="inventive-stock-player-hidden-audio-'.$id.'"></div><div   id="inventive-stock-player-spectrum-audio-'.$id.'" class="inventive-stock-audio-container inventive-stock-audio-container inventive-stock-player-spectrum-check '.$spectrum.'">'.$result.'</div>';

endif;

endif;
}

}

?>