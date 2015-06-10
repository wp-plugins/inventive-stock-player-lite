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
if (!is_admin()):

add_filter( 'wp_audio_shortcode_override',
    function( $html, $atts )
    {
        if( strpos($atts['src'],'media.sndcdn.com') )
        {
            add_filter( 'wp_audio_extensions', 'wpse_152316_wp_audio_extensions' );
        }
        return $html;
    }
, PHP_INT_MAX, 2 );

function wpse_152316_wp_audio_extensions( $ext )
{
    remove_filter( current_filter(), __FUNCTION__ );
    $ext[] = '';
    return $ext;
}

add_filter( 'wp_audio_shortcode', function($html)
{
	
	$array = array();
	for ($x=0;$x<=100;$x++):
	$array[] = "_=".$x;
	endfor;
	$html = $html;
	$html = str_replace($array,"",$html);
	return $html;
	

});

//////////////filter of post thumbnail to show player if enabled
add_filter( 'post_thumbnail_html', function($html)
{
	global $post;
	
	///we check if this post has a stock video/audio connect
	if (( (is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_single_show', true ) == "1"))
	|| ( (!is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_list', true ) == "1")) ):
	//echo '<a href="#">';
	$stock_player = new inventive_stock_player();
	echo $stock_player->get_player("single","","","","post");
	//echo '</a>';
	else:
	return $html;
	endif;
});

function inventive_stock_player_above_title($title, $id) {
	
	
	//above title
 if (( (is_single($id)) && (get_post_meta( $id, 'inventive_stock_player_single_show', true ) == "2"))
	|| ( (!is_single($id)) && (get_post_meta( $id, 'inventive_stock_player_list', true ) == "2")) ):
    $stock_player = new inventive_stock_player();
	$result = '<a href="#">';
	$result .= $stock_player->get_player("single","","","","post");
	$result .= '</a>';
    $result .= $title;
	return $result;
	//under title
	elseif (( (is_single($id)) && (get_post_meta( $id, 'inventive_stock_player_single_show', true ) == "3"))
	|| ( (!is_single($id)) && (get_post_meta( $id, 'inventive_stock_player_list', true ) == "3")) ):
    $stock_player = new inventive_stock_player();
	$result = $title;
	$result .= '<a href="#">';
	$result .= $stock_player->get_player("single","","","","post");
	$result .= '</a>';
	return $result;
	else :
	return $title;
	endif;
}


add_filter('the_title', 'inventive_stock_player_above_title', 10, 2);

function inventive_stock_player_the_content($content) {
	global $post;

	//above content
 if (( (is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_single_show', true ) == "4"))
	|| ( (!is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_list', true ) == "4")) ):
    $stock_player = new inventive_stock_player();
	$result = '<a href="#">';
	$result .= $stock_player->get_player("single","","","","post");
	$result .= '</a>';
    $result .= $content;
	return $result;
	//under content
	elseif (( (is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_single_show', true ) == "5"))
	|| ( (!is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_list', true ) == "5")) ):
    $stock_player = new inventive_stock_player();
	$result = $content;
	$result .= '<a href="#">';
	$result .= $stock_player->get_player("single","","","","post");
	$result .= '</a>';
	return $result;
	else :
	return $content;
	endif;
}

add_filter('the_content', 'inventive_stock_player_the_content', 10, 2);


add_filter('the_title', 'inventive_stock_player_buy_single', 10, 2);

function inventive_stock_player_buy_single($title, $id) {

	//above content

 if ((( (is_single($id)) && (get_post_meta($id, 'inventive_stock_player_single_buy', true ) == 1)) ||
( (!is_single($id)) && (get_post_meta($id, 'inventive_stock_player_list_buy', true ) == 1))) && (get_post_type( $id ) != "kkct")):
 
    $stock_player = new inventive_stock_player();
	$result = $title;
	$result .= $stock_player->buy_button();
	return $result;
	
	else :
	
	return $title;
	
	endif;
}


/* make all products go to product page */
add_filter( 'woocommerce_loop_add_to_cart_link', 'woo_more_info_link' );
function woo_more_info_link( $link ) {
	
global $post;

///if post/product has show buy button switched on... and it's single, show it
if ((!is_single($post->ID)) && (get_post_meta( $post->ID, 'inventive_stock_player_list_buy', true ) == 1)):



else:

return $link;

endif;
}
endif;