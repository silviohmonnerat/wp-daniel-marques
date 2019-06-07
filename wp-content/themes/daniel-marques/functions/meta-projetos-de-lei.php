<?php
/* ---------------------------------------------------------------------------
 * Create new post type
 * --------------------------------------------------------------------------- */
function mfn_projetos_de_lei_post_type(){
	$client_item_slug = mfn_opts_get( 'projeto-de-lei-slug', 'projeto-de-lei-item' );
	
	$labels = array(
		'name' 					=> __('Projetos de Lei','mfn-opts'),
		'singular_name' 		=> __('Projetos de Lei','mfn-opts'),
		'add_new' 				=> __('Add novo','mfn-opts'),
		'add_new_item' 			=> __('Add novo Projetos de Lei','mfn-opts'),
		'edit_item' 			=> __('Editar Projetos de Lei','mfn-opts'),
		'new_item' 				=> __('novo Projetos de Lei','mfn-opts'),
		'view_item' 			=> __('ver Projetos de Lei','mfn-opts'),
		'search_items' 			=> __('Buscar Projetos de Lei','mfn-opts'),
		'not_found' 			=> __('Nada encontrado','mfn-opts'),
		'not_found_in_trash' 	=> __('Nada encontrado na lixeira','mfn-opts'), 
		'parent_item_colon' 	=> ''
	  );
		
	$args = array(
		'labels' 				=> $labels,
		'menu_icon'				=> 'dashicons-paperclip',
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true, 
		'query_var' 			=> true,
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'rewrite' 				=> array( 'slug' => $client_item_slug, 'with_front' => true ),
		'supports' 				=> array( 'title', 'editor', 'thumbnail' ),
	); 
	  
	register_post_type( 'projeto_de_lei', $args );
	
}
add_action( 'init', 'mfn_projetos_de_lei_post_type' );


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$mfn_projeto_de_lei_meta_box = array(
	'id' 		=> 'mfn-meta-projeto_de_lei',
	'title' 	=> __('Opção','mfn-opts'),
	'page' 		=> 'projeto_de_lei',
	'context' 	=> 'normal',
	'priority'	=> 'high',
	'fields' 	=> array(
						
		array(
			'id' 		=> 'mfn-projeto-lei-pdf',
			'type' 		=> 'upload',
			'title' 	=> __('Adicionar PDF Projetos de lei', 'mfn-opts')
		),	

	),
);


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/ 
function mfn_projeto_de_lei_meta_add() {
	global $mfn_projeto_de_lei_meta_box;
	add_meta_box($mfn_projeto_de_lei_meta_box['id'], $mfn_projeto_de_lei_meta_box['title'], 'mfn_projeto_de_lei_show_box', $mfn_projeto_de_lei_meta_box['page'], $mfn_projeto_de_lei_meta_box['context'], $mfn_projeto_de_lei_meta_box['priority']);
}
add_action('admin_menu', 'mfn_projeto_de_lei_meta_add');


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function mfn_projeto_de_lei_show_box() {
	global $MFN_Options, $mfn_projeto_de_lei_meta_box, $post;
	$MFN_Options->_enqueue();
 	
	// Use nonce for verification
	echo '<div id="mfn-wrapper">';
		echo '<input type="hidden" name="mfn_projeto_de_lei_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		echo '<table class="form-table">';
			echo '<tbody>';
	 
				foreach ($mfn_projeto_de_lei_meta_box['fields'] as $field) {
					$meta = get_post_meta($post->ID, $field['id'], true);
					if( ! key_exists('std', $field) ) $field['std'] = false;
					$meta = ( $meta || $meta==='0' ) ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES ));
					mfn_meta_field_input( $field, $meta );
				}
	 
			echo '</tbody>';
		echo '</table>';
	echo '</div>';
}


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
function mfn_projeto_de_lei_save_data($post_id) {
	global $mfn_projeto_de_lei_meta_box;
 
	// verify nonce
	if( key_exists( 'mfn_projeto_de_lei_meta_nonce',$_POST ) ) {
		if ( ! wp_verify_nonce( $_POST['mfn_projeto_de_lei_meta_nonce'], basename(__FILE__) ) ) {
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
 
	foreach ($mfn_projeto_de_lei_meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		if( key_exists($field['id'], $_POST) ) {
			$new = $_POST[$field['id']];
		} else {
//			$new = ""; // problem with "quick edit"
			continue;
		}
 
		if ( isset($new) && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'mfn_projeto_de_lei_save_data');