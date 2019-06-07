<?php

class Widget_Facebook extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'facebook_widget',
			esc_html__( 'SM: Facebook' ),
			array( 'description' => esc_html__( 'Facebook page.' ), )
		);
	}

	public function widget( $args, $instance ) {

		extract( $args );
		$title    = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$facebook_app_id = isset( $instance['facebook_app_id'] ) ? $instance['facebook_app_id'] : '';
		$page_url        = isset( $instance['page_url'] ) ? $instance['page_url'] : '';
		$width           = isset( $instance['width'] ) ? $instance['width'] : '';
		$height          = isset( $instance['height'] ) ? $instance['height'] : '';
		$hide_cover      = isset( $instance['hide_cover']  ) && $instance['hide_cover'] ? 'true' : 'false';
		$show_facepile   = isset( $instance['show_facepile'] ) && $instance['show_facepile'] ? 'true' : 'false';
		$show_posts      = isset( $instance['show_posts'] ) && $instance['show_posts'] ? 'true' : 'false';

		echo $before_widget;

		if ( ! empty( $title ) ) { echo $before_title . $title . $after_title;	}

		ob_start(); 
	?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.4&appId=<?php echo esc_attr( $facebook_app_id ); ?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>

		<div class="boxAside">
			<div class="fb-page" data-href="<?php echo esc_url( $page_url ); ?>" data-width="<?php echo esc_attr( $width ); ?>" data-height="<?php echo esc_attr( $height ); ?>" data-hide-cover="<?php echo esc_attr( $hide_cover ); ?>" data-show-facepile="<?php echo esc_attr( $show_facepile ); ?>" data-show-posts="<?php echo esc_attr( $show_posts ); ?>"></div>
		</div>		

	<?php

		echo ob_get_clean();

		echo $after_widget;

	}

	public function form( $instance ) {

		$defaults = array(
			'title'           => 'Facebook',
			'facebook_app_id' => '',
			'page_url'        => 'https://www.facebook.com/',
			'width'           => '340',
			'height'          => '500',
			'hide_cover'      => 'false',
			'show_facepile'   => 'false',
			'show_posts'      => 'false',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'facebook_app_id' ) ); ?>"><?php esc_html_e( 'App ID:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook_app_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook_app_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['facebook_app_id'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"><?php esc_html_e( 'Page URL:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_url' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['page_url'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e( 'Width:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['width'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e( 'Height:' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['height'] ); ?>" />
		</p>
		<p>
			<input class='checkbox' <?php ! empty( $instance['hide_cover'] ) ? checked( $instance['hide_cover'], 'on' ) : ''; ?> id='<?php echo esc_attr( $this->get_field_id( 'hide_cover' ) ); ?>' name='<?php echo esc_attr( $this->get_field_name( 'hide_cover' ) ); ?>' type='checkbox' />
			<label for='<?php echo esc_attr( $this->get_field_id( 'hide_cover' ) ); ?>'>
				<?php esc_html_e( 'Hide cover' ); ?>
			</label>
		</p>
		<p>
			<input class='checkbox' <?php ! empty( $instance['show_facepile'] ) ? checked( $instance['show_facepile'], 'on' ) : ''; ?> id='<?php echo esc_attr( $this->get_field_id( 'show_facepile' ) ); ?>' name='<?php echo esc_attr( $this->get_field_name( 'show_facepile' ) ); ?>' type='checkbox' />
			<label for='<?php echo esc_attr( $this->get_field_id( 'show_facepile' ) ); ?>'>
				<?php esc_html_e( 'Show facepile' ); ?>
			</label>
		</p>
		<p>
			<input class='checkbox' <?php ! empty( $instance['show_posts'] ) ? checked( $instance['show_posts'], 'on' ) : ''; ?> id='<?php echo esc_attr( $this->get_field_id( 'show_posts' ) ); ?>' name='<?php echo esc_attr( $this->get_field_name( 'show_posts' ) ); ?>' type='checkbox' />
			<label for='<?php echo esc_attr( $this->get_field_id( 'show_posts' ) ); ?>'>
				<?php esc_html_e( 'Show posts' ); ?>
			</label>
		</p>
		<?php 
	}

	public function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['facebook_app_id'] = ( ! empty( $new_instance['facebook_app_id'] ) ) ? strip_tags( $new_instance['facebook_app_id'] ) : '';
		$instance['page_url']        = ( ! empty( $new_instance['page_url'] ) ) ? strip_tags( $new_instance['page_url'] ) : '';
		$instance['width']           = ( ! empty( $new_instance['width'] ) ) ? strip_tags( $new_instance['width'] ) : '';
		$instance['height']          = ( ! empty( $new_instance['height'] ) ) ? strip_tags( $new_instance['height'] ) : '';
		$instance['hide_cover']      = ( ! empty( $new_instance['hide_cover'] ) ) ? strip_tags( $new_instance['hide_cover'] ) : '';
		$instance['show_facepile']   = ( ! empty( $new_instance['show_facepile'] ) ) ? strip_tags( $new_instance['show_facepile'] ) : '';
		$instance['show_posts']      = ( ! empty( $new_instance['show_posts'] ) ) ? strip_tags( $new_instance['show_posts'] ) : '';

		return $instance;
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("Widget_Facebook");' ) );