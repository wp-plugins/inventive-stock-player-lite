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
class inventive_stock_player_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'inventive_stock_player_widget', // Base ID
			__( 'Inventive stock player', 'inventive_stock_player' ), // Name
			array( 'description' => __( 'Width this widget you can create a player for stock video and audio from famous stock libraries', 'inventive_stock_player' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		echo '<a href="#">';
		$stock_player = new inventive_stock_player();
	    echo $stock_player->get_player("single",$instance,"",$args['widget_id'],"widget");
		echo '</a>';
	    //print_r($instance);
		
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'inventive_stock_player' );
		$link = ! empty( $instance['link'] ) ? $instance['link'] : __( '', 'inventive_stock_player' );
		$autoplay = ! empty( $instance['autoplay'] ) ? $instance['autoplay'] : __( '', 'inventive_stock_player' );
		$loop = ! empty( $instance['loop'] ) ? $instance['loop'] : __( '', 'inventive_stock_player' );
		$preload = ! empty( $instance['preload'] ) ? $instance['preload'] : __( '', 'inventive_stock_player' );
		$spectrum = ! empty( $instance['spectrum'] ) ? $instance['spectrum'] : __( '', 'inventive_stock_player' );
		$link_local = ! empty( $instance['link_local'] ) ? $instance['link_local'] : __( '', 'inventive_stock_player' );
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <?php
        echo '<p>'.__('Paste here your stock video/audio link (audiojungle, soundcloud)','inventive_stock_player').'</p> <input name="'.$this->get_field_name( 'link' ).'" id="'.$this->get_field_id( 'link' ).'" class="inventive_stock_player" value="'.esc_attr( $link ).'">';
	
	   ?>
    <?php /////autoply option ?>
	<p><?php _e( 'Autoplay', 'inventive_stock_player' ); ?>
	<input type="checkbox" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" class="inventive_stock_player_checkbox" value ="on" <?php if (esc_attr( $autoplay ) == "on") echo "checked" ?>/></p>
    
    <?php /////loop option ?>
	<p><?php _e( 'Loop', 'inventive_stock_player' ); ?>
	<input type="checkbox" name="<?php echo $this->get_field_name( 'loop' ); ?>" id="<?php echo $this->get_field_id( 'loop' ); ?>" class="inventive_stock_player_checkbox" value ="on" <?php if (esc_attr( $loop ) == "on") echo "checked" ?>/></p>
    
    <?php /////preload option ?>
	<p><?php _e( 'Preload', 'inventive_stock_player' ); ?>
	<input type="checkbox" name="<?php echo $this->get_field_name( 'preload' ); ?>" id="<?php echo $this->get_field_id( 'preload' ); ?>" class="inventive_stock_player_checkbox" value ="auto" <?php if (esc_attr( $preload ) == "auto") echo "checked" ?>/></p>
    
     <?php /////spectrum option ?>
	<p><?php _e( 'Show spectrum analyzer?', 'inventive_stock_player' ); ?>
	<input type="checkbox" name="<?php echo $this->get_field_name( 'spectrum' ); ?>" id="<?php echo $this->get_field_id( 'spectrum' ); ?>" class="inventive_stock_player_checkbox" value ="1" <?php if (esc_attr( $spectrum ) == "1") echo "checked" ?>/></p>
    
    <?php /////spectrum option ?>
	<input type="hidden" name="<?php echo $this->get_field_name( 'link_local' ); ?>" id="<?php echo $this->get_field_id( 'link_local' ); ?>" class="inventive_stock_player_checkbox" value ="<?php echo esc_attr( $link_local); ?>"/>
	
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		

		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
		$instance['referral'] = ( ! empty( $new_instance['referral'] ) ) ? strip_tags( $new_instance['referral'] ) : '';
		$instance['buy'] = ( ! empty( $new_instance['buy'] ) ) ? strip_tags( $new_instance['buy'] ) : '';
		$instance['autoplay'] = ( ! empty( $new_instance['autoplay'] ) ) ? strip_tags( $new_instance['autoplay'] ) : '';
		$instance['loop'] = ( ! empty( $new_instance['loop'] ) ) ? strip_tags( $new_instance['loop'] ) : '';
		$instance['preload'] = ( ! empty( $new_instance['preload'] ) ) ? strip_tags( $new_instance['preload'] ) : '';
		$instance['spectrum'] = ( ! empty( $new_instance['spectrum'] ) ) ? strip_tags( $new_instance['spectrum'] ) : '';
		$instance['link_local'] = ( ! empty( $new_instance['link_local'] ) ) ? strip_tags( $new_instance['link_local'] ) : '';
		
		if ($instance['spectrum'] == 1):
		$save_stream = new inventive_admin_config();
		$filename = $save_stream->store_file($new_instance['link'],"widget");
        $instance['link_local'] = ( ! empty( $filename ) ) ? strip_tags( $filename ) : '';
		else:
		//we reset the link local field and unlink the file
		$vars = new inventive_stock_player_libraries();
		$dirname = $vars->upload_dir("basedir"); 
		unlink($dirname."/".$instance['link_local'].".mp3");
		$instance['link_local'] = "";

		endif;

		return $instance;
	}

}

///register widget
add_action( 'widgets_init', function(){
     register_widget( 'inventive_stock_player_widget' );
});
?>