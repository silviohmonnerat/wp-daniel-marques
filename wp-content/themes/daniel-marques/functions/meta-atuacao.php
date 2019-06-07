<?php
/* ---------------------------------------------------------------------------
 * Create new post type
 * --------------------------------------------------------------------------- */
function mfn_client_post_type() 
{
	$client_item_slug = mfn_opts_get( 'atuacao', 'atuacao' );
	
	$labels = array(
		'name' 					=> __('Indicações','mfn-opts'),
		'singular_name' 		=> __('Indicação','mfn-opts'),
		'add_new' 				=> __('Add New','mfn-opts'),
		'add_new_item' 			=> __('Add New Indicação','mfn-opts'),
		'edit_item' 			=> __('Edit Indicação','mfn-opts'),
		'new_item' 				=> __('New Indicação','mfn-opts'),
		'view_item' 			=> __('View Indicações','mfn-opts'),
		'search_items' 			=> __('Search Indicações','mfn-opts'),
		'not_found' 			=> __('No atuações found','mfn-opts'),
		'not_found_in_trash' 	=> __('No atuações found in Trash','mfn-opts'), 
		'parent_item_colon' 	=> ''
	  );
		
	$args = array(
		'labels' 				=> $labels,
		'menu_icon'				=> 'dashicons-location-alt',
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true, 
		'query_var' 			=> true,
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'rewrite' 				=> array( 'slug' => $client_item_slug, 'with_front'=>true ),
		'supports' 				=> array( 'title', 'custom-fields' ),
	); 
	  
	register_post_type( 'atuacao', $args );
	
}
add_action( 'init', 'mfn_client_post_type' );


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$mfn_atuacao_meta_box = array(
	'id' 		=> 'mfn-meta-atuacao',
	'title' 	=> __('Opção','mfn-opts'),
	'page' 		=> 'atuacao',
	'context' 	=> 'normal',
	'priority'	=> 'high',
	'fields' 	=> array(
			
		array(
			'id' 		=> 'mfn-address',
			'type' 		=> 'text',
			'title' 	=> __('Endereço', 'mfn-opts')
		),
		
		array(
			'id' 		=> 'mfn-infowindow',
			'type' 		=> 'textarea',
			'title' 	=> __('Informação', 'mfn-opts')
		),

		array(
			'id' 		=> 'mfn-pinmarker',
			'type' 		=> 'upload',
			'title' 	=> __('Imagem do marcador', 'mfn-opts')
		),	

	),
);


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/ 
function mfn_atuacao_meta_add() {
	global $mfn_atuacao_meta_box;
	add_meta_box($mfn_atuacao_meta_box['id'], $mfn_atuacao_meta_box['title'], 'mfn_atuacao_show_box', $mfn_atuacao_meta_box['page'], $mfn_atuacao_meta_box['context'], $mfn_atuacao_meta_box['priority']);
}
add_action('admin_menu', 'mfn_atuacao_meta_add');


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function mfn_atuacao_show_box() {
	global $MFN_Options, $mfn_atuacao_meta_box, $post;
	$MFN_Options->_enqueue();
 	
	// Use nonce for verification
	echo '<div id="mfn-wrapper">';
		echo '<input type="hidden" name="mfn_atuacao_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<table class="form-table">';
			echo '<tbody>';
	 
				foreach ($mfn_atuacao_meta_box['fields'] as $field) {
					$meta = get_post_meta($post->ID, $field['id'], true);
					if( ! key_exists('std', $field) ) $field['std'] = false;
					$meta = ( $meta || $meta==='0' ) ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES ));
					mfn_meta_field_input( $field, $meta );
				}
	 
			echo '</tbody>';
		echo '</table>';
	echo '</div>';

	$raw_address   = get_post_meta($post->ID, 'mfn-address', true);
	if($raw_address){
		$invalid_chars = array( " " => "+", "," => "", "?" => "", "&" => "", "=" => "" , "#" => "" );
		$raw_address   = trim( strtolower( str_replace( array_keys( $invalid_chars ), array_values( $invalid_chars ), $raw_address ) ) );

		$json             = wp_remote_get('http://maps.googleapis.com/maps/api/geocode/json?address="' . $raw_address . '"&sensor=false');
		$result           = wp_remote_retrieve_body( $json );
		$geocoded_address = json_decode( $result );

		$address                      = array();
		$address['lat']               = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lat );
		$address['long']              = sanitize_text_field( $geocoded_address->results[0]->geometry->location->lng );
		$address['formatted_address'] = sanitize_text_field( $geocoded_address->results[0]->formatted_address );
		$address_data                 = $geocoded_address->results[0]->address_components;
		$address['city']              = sanitize_text_field( $address_data[0]->long_name );
		$address['state_long']        = sanitize_text_field( $address_data[2]->short_name );
		$address['state_short']       = sanitize_text_field( $address_data[2]->short_name );
		$address['country_long']      = sanitize_text_field( $address_data[3]->long_name );
		$address['country_short']     = sanitize_text_field( $address_data[3]->short_name );
		
		if( get_post_meta($post->ID, 'geolocated', true) ){
			update_post_meta( $post->ID, 'geolocated', '1' );
		} else{
			add_post_meta( $post->ID, 'geolocated', '1' );
		}

		if( get_post_meta($post->ID, 'geolocation_city', true) ){
			update_post_meta( $post->ID, 'geolocation_city', $address['city'] );
		} else{
			add_post_meta( $post->ID, 'geolocated', $address['city'] );
		}

		if( get_post_meta($post->ID, 'geolocation_country_long', true) ){
			update_post_meta( $post->ID, 'geolocation_country_long', $address['country_long'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_country_long', $address['country_long'] );
		}

		if( get_post_meta($post->ID, 'geolocation_country_short', true) ){
			update_post_meta( $post->ID, 'geolocation_country_short', $address['country_short'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_country_short', $address['country_short'] );
		}

		if( get_post_meta($post->ID, 'geolocation_formatted_address', true) ){
			update_post_meta( $post->ID, 'geolocation_formatted_address', $address['formatted_address'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_formatted_address', $address['formatted_address'] );
		}

		if( get_post_meta($post->ID, 'geolocation_lat', true) ){
			update_post_meta( $post->ID, 'geolocation_lat', $address['lat'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_lat', $address['lat'] );
		}

		if( get_post_meta($post->ID, 'geolocation_long', true) ){
			update_post_meta( $post->ID, 'geolocation_long', $address['long'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_long', $address['long'] );
		}

		if( get_post_meta($post->ID, 'geolocation_state_long', true) ){
			update_post_meta( $post->ID, 'geolocation_state_long', $address['state_long'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_state_long', $address['state_long'] );
		}

		if( get_post_meta($post->ID, 'geolocation_state_short', true) ){
			update_post_meta( $post->ID, 'geolocation_state_short', $address['country_short'] );
		} else{
			add_post_meta( $post->ID, 'geolocation_state_short', $address['country_short'] );
		}

	}
}


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
function mfn_atuacao_save_data($post_id) {
	global $mfn_atuacao_meta_box;
 
	// verify nonce
	if( key_exists( 'mfn_atuacao_meta_nonce',$_POST ) ) {
		if ( ! wp_verify_nonce( $_POST['mfn_atuacao_meta_nonce'], basename(__FILE__) ) ) {
			return $post_id;
		}
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ( (key_exists('post_type', $_POST)) && ('page' == $_POST['post_type']) ) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
 
	foreach ($mfn_atuacao_meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		if( key_exists($field['id'], $_POST) ) {
			$new = $_POST[$field['id']];
		} else {
			// $new = ""; // problem with "quick edit"
			continue;
		}
 
		if ( isset($new) && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}

}
add_action('save_post', 'mfn_atuacao_save_data');