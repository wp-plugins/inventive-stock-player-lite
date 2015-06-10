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
/* global mejs, _wpmejsSettings */
(function ($) {
	// add mime-type aliases to MediaElement plugin support
	mejs.plugins.silverlight[0].types.push('video/x-ms-wmv');
	mejs.plugins.silverlight[0].types.push('audio/x-ms-wma');

	$(function () {
		var settings = {};

		if ( typeof _wpmejsSettings !== 'undefined' ) {
			settings = _wpmejsSettings;
		}

		settings.success = settings.success || function (mejs) {
			var autoplay, loop;

			if ( 'flash' === mejs.pluginType ) {
				autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
				loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;

				autoplay && mejs.addEventListener( 'canplay', function () {
					mejs.play();
				}, false );

				loop && mejs.addEventListener( 'ended', function () {
					mejs.play();
				}, false );
			}
		};

window['autoplay_on_start'] = false;
$( "audio" ).each(function( index ) {
	
  window[$(this).attr("id")] = new MediaElementPlayer('#'+$(this).attr("id"),{
	success: function (mediaElement, domObject) { 
         
		 
		// add event listener
        mediaElement.addEventListener('play', function(e) {
      
        if ($(this).closest(".inventive-stock-player-spectrum-check").hasClass("inventive-stock-player-spectrum-yes"))
		{

if (typeof AudioContext != "undefined") {
			 clearTimeout( spectrum_timeout );
			 $(".inventive_stock_audio_spectrum").css({"display":"block","opacity":0}).animate({"opacity":1},1000);
			 
			 inventive_stock_player.create_audio_element($(this).attr("id"),$(this).children("source").attr("src"),$(this).attr("autoplay"),$(this).attr("loop"),$(this).attr("preload")); 
}
		}
             
        }, false);  
		
	
		
  },
  
  });
  
  //audio = document.getElementById('audio-400-1');
  //audio.controls = true;
  //audio.loop = true;
  
 // console.log(window[$(this).attr("id")]);
 // console.log( index + ": " + $( this ).children("source").attr("src") );
});

$('.wp-video-shortcode').mediaelementplayer( settings );

		//$('.wp-audio-shortcode, .wp-video-shortcode').mediaelementplayer( settings );
		
	});

}(jQuery));
