<?php
/**
 * List Blog Recents Posts
 *
 * @since Secad Widget 1.0
 */
class Widget_Count_Post_Views extends WP_Widget {

	/* ---------------------------------------------------------------------------
	 * Constructor
	 * --------------------------------------------------------------------------- */
	function __construct(){
		
		$widget_ops = array( 'classname' => 'widget_count_post_views', 'description' => __( 'Lista as postagens mais lidas do blog.', 'mfn-opts' ) );
		
		parent::__construct( 'widget_count_post_views', __( 'Posts mais lidos', 'mfn-opts' ), $widget_ops );
		
		$this->alt_option_name = 'widget_count_post_views';

		// Executa a ação
    	add_action( 'init', array($this, 'tutsup_session_start') );

    	add_action( 'get_header',  array($this, 'tp_count_post_views') );
	}

	function tutsup_session_start() {
        // Inicia uma sessão PHP
        if ( ! session_id() ) session_start();
    }

    // Conta os views do post
    function tp_count_post_views () {	
        // Garante que vamos tratar apenas de posts
        if ( is_single() ) {
        
            // Precisamos da variável $post global para obter o ID do post
            global $post;
            
            // Se a sessão daquele posts não estiver vazia
            if ( empty( $_SESSION[ 'tp_post_counter_' . $post->ID ] ) ) {
                
                // Cria a sessão do posts
                $_SESSION[ 'tp_post_counter_' . $post->ID ] = true;
            
                // Cria ou obtém o valor da chave para contarmos
                $key = 'tp_post_counter';
                $key_value = get_post_meta( $post->ID, $key, true );
                
                // Se a chave estiver vazia, valor será 1
                if ( empty( $key_value ) ) { // Verifica o valor
                    $key_value = 1;
                    update_post_meta( $post->ID, $key, $key_value );
                } else {
                    // Caso contrário, o valor atual + 1
                    $key_value += 1;
                    update_post_meta( $post->ID, $key, $key_value );
                } // Verifica o valor
                
            } // Checa a sessão
            
        } // is_single
        
        return;
        
    }    

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	/* ---------------------------------------------------------------------------
	 * Outputs the HTML for this widget.
	 * --------------------------------------------------------------------------- */
	function widget( $args, $instance ) {

		if ( ! isset( $args['widget_id'] ) ) $args['widget_id'] = null;
		extract( $args, EXTR_SKIP );

		global $post;

		echo $before_widget;
		
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base);
		if( $title ) echo $before_title . $title . $after_title;

		$args = array(
	        'posts_per_page'      => $instance['count'] ? intval($instance['count']) : 0, // Máximo de 5 artigos
			'showposts'           => $instance['count'] ? intval($instance['count']) : 0, // Máximo de 5 artigos
	        'no_found_rows'       => true,              // Não conta linhas
	        'post_status'         => 'publish',         // Somente posts publicados
	        'ignore_sticky_posts' => true,              // Ignora posts fixos
	        'orderby'             => 'meta_value_num',  // Ordena pelo valor da post meta
	        'meta_key'            => 'tp_post_counter', // A nossa post meta
	        'order'               => 'DESC'             // Ordem decrescente
		);
		$r = new WP_Query( apply_filters( 'widget_posts_args', $args ) );

		$output = false;
		if ($r->have_posts()){           
			$output .= '<div class="mais-vistos Recent_posts">';
				$output .= '<ul>';
					while ( $r->have_posts() ){
						$r->the_post();
						$tp_post_counter = get_post_meta( $post->ID, 'tp_post_counter', true );
						
						$class = ' format';
						if( ! has_post_thumbnail( get_the_ID() ) ){
							if( ! in_array( get_post_format(), array('quote','link') ) ){
								$class .= ' no-img';
							}
						}
							
						$output .= '<li class="post'. $class .'">';
							$output .= '<a href="'. get_permalink() .'">';
							
		                       	$output .= '<span class="date"><i class="icon-clock"></i>'. get_the_date() .'</span>';
		                       	
								$output .= '<div class="photo">';
									if( has_post_thumbnail() ) $output .= get_the_post_thumbnail( get_the_ID(), 'blog-navi', array('class'=>'scale-with-grid' ) );
									$output .= '<span class="c">'. $tp_post_counter .'</span>';
								$output .= '</div>';
					
								$output .= '<div class="desc">';
									$output .= '<h6>'. get_the_title() .'</h6>';									
		                       	$output .= '</div>';

		                       	
	                       	$output .= '</a>';
                       	$output .= '</li>';
                       	
					}
					wp_reset_postdata();
				$output .= '</ul>';
			$output .= '</div>'."\n";
		}
		echo $output;
	
		echo $after_widget;
	}

	/* ---------------------------------------------------------------------------
	 * Deals with the settings when they are saved by the admin.
	 * --------------------------------------------------------------------------- */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['count'] 		= (int) $new_instance['count'];
		
		return $instance;
	}

	
	/* ---------------------------------------------------------------------------
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 * --------------------------------------------------------------------------- */
	function form( $instance ) {
		
		$title		= isset( $instance['title']) ? esc_attr( $instance['title'] ) : '';
		$count		= isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;

		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'mfn-opts' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php _e( 'Number of posts:', 'mfn-opts' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" size="3"/>
			</p>
			
		<?php
	}
}
register_widget( 'Widget_Count_Post_Views' );