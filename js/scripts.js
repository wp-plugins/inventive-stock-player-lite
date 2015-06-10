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
	
"use strict";

var canvas, ctx, source, context, analyzer, fbc_array, bars, bar_x, bar_width, bar_height, spectrum_timeout, last_id, spectrum_timeout;

var inventive_stock_player = new Object;

inventive_stock_player = {

    spectrum: function(id,url) {
		if (context != undefined){
			if (typeof context.close != "undefined") context.close();
			}
		inventive_stock_player.initMp3Player(id,url);
		
	
},

browser: function(what){
    var ua= navigator.userAgent, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
		if (what == "version") return (tem[1] || '');
		else return 'IE';
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\bOPR\/(\d+)/);
        if((tem!= null)&&(what=="version")) return tem[1];
		if((tem!= null)&&(what=="name")) return 'OPERA';
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null){
		 M.splice(1, 1, tem[1]);
    if (what == "name") return M.join(' ');
	}
},

create_audio_element: function(id,url,autoplay,loop,preload)
{
	
    var audioElement = document.createElement("audio");
   
audioElement.id = id;

if (window['autoplay_on_start'] === false) audioElement.autoplay = true;
audioElement.id = id;

if (preload == "auto") audioElement.preload = "auto";
else audioElement.preload = "none";

if (loop == "loop") audioElement.loop = true;

audioElement.className = 'wp-audio-shortcode';
            var sourceElement = document.createElement("source");
            sourceElement.type = "audio/mpeg";
            sourceElement.src = url;
			console.log(context);
			if (context != undefined){
			if (typeof context.close != "undefined") context.close();
			}
			var id_elaborato = id.split("-");
		 id_elaborato = id_elaborato[0]+"-"+id_elaborato[1];
		 
            audioElement.appendChild(sourceElement);
			//alert("#inventive-stock-player-hidden-"+id); 
			
		
		 jQuery("#"+id).closest("a").children(".inventive-hidden-stock-audio-container").append(audioElement);
         //jQuery("#inventive-stock-player-hidden-"+id_elaborato).append(audioElement);
		 //jQuery("#"+id).first().remove();
		 
		 
  jQuery("#"+id).closest("a").children(".inventive-stock-player-spectrum-yes").html(jQuery("#"+id).closest("a").children(".inventive-hidden-stock-audio-container").html());
		//jQuery("#inventive-stock-player-spectrum-"+id_elaborato).html(jQuery("#inventive-stock-player-hidden-"+id_elaborato).html());
	jQuery("#"+id).closest("a").children(".inventive-hidden-stock-audio-container").find("source").attr("src","");
	jQuery("#"+id).closest("a").children(".inventive-hidden-stock-audio-container").html("");
	
	
		 window[id] = new MediaElementPlayer('#'+id,{
	success: function (mediaElement, domObject) { 
	
	        inventive_stock_player.initMp3Player(id,"");
         
        	 mediaElement.addEventListener('pause', function(e) {
			jQuery(".inventive_stock_audio_spectrum").css({"display":"none"});	
           // clearTimeout( spectrum_timeout );
			//inventive_stock_player.create_audio_element(id,jQuery("#"+id).children("source").attr("src")); 
			//console.log(id+" in pausa");
		 });
		 
		// add event listener
        mediaElement.addEventListener('play', function(e) {
           
        if (jQuery("#"+id).closest(".inventive-stock-player-spectrum-check").hasClass("inventive-stock-player-spectrum-yes"))
		{
			
 jQuery(".inventive_stock_audio_spectrum").css({"display":"block","opacity":0}).animate({"opacity":1},1000);
 
if ((last_id != id) && (last_id != "undefined"))
{
	 window['autoplay_on_start'] = false;
	inventive_stock_player.create_audio_element(id,jQuery("#"+id).children("source").attr("src")); 
	
}
			//inventive_stock_player.spectrum(id,jQuery("#"+id).children("source").attr("src"));
			last_id = id;
		}
             
        }, false);  
	
    },
  
  });

    return audioElement;


	
},

frame_looper : function(id) {
    var fps = 60;
	
	if (!inventive_stock_player_bars_color) inventive_stock_player_bars_color = "#FFF"; 
	if (!inventive_stock_player_bars_number) inventive_stock_player_bars_number = 100; 
	if (!inventive_stock_player_bars_distance) inventive_stock_player_bars_distance = 3; 
	if (!inventive_stock_player_bars_width) inventive_stock_player_bars_width = 2; 

    spectrum_timeout = setTimeout(function() {
        inventive_stock_player.frame_looper(id);
        // Drawing code goes here
  console.log(id);

	//window.requestAnimationFrame(inventive_stock_player.frame_looper());
	fbc_array = new Uint8Array(analyzer.frequencyBinCount);
	analyzer.getByteFrequencyData(fbc_array);
	ctx.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas
	ctx.fillStyle = inventive_stock_player_bars_color; // Color of the bars
	bars = inventive_stock_player_bars_number;
	for (var i = 0; i < bars; i++) {
		bar_x = i * inventive_stock_player_bars_distance;
		bar_width = inventive_stock_player_bars_width;
		bar_height = -(fbc_array[i] / 2);
		//  fillRect( x, y, width, height ) // Explanation of the parameters below
		ctx.fillRect(bar_x, canvas.height, bar_width, bar_height);
		
	} }, 1000 / fps);
},

initMp3Player : function(id,url){
    clearTimeout( spectrum_timeout );
    
	
	var audio = document.getElementById(id);
	console.log("player inizializzato");
	context = new AudioContext(); // AudioContext object instance
	analyzer = context.createAnalyser(); // AnalyserNode method
	canvas = document.getElementById('analyser_render');
	ctx = canvas.getContext('2d');
	// Re-route audio playback into the processing graph of the AudioContext
	source = context.createMediaElementSource(audio); 
	source.connect(analyzer);
	analyzer.connect(context.destination);
	last_id = id;
	inventive_stock_player.frame_looper(id);
	
}

	


};

jQuery(document).ready(function($){
	if ($('.woocommerce-breadcrumb').length > 0){
	$('.woocommerce-breadcrumb').get(0).lastChild.nodeValue = "";
	$('.woocommerce-breadcrumb').show();
	}
});

