<?php 
	function _theme_options() {

		if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() )
    	return false;
    	$saved_settings = get_option( ot_settings_id(), array() );

    	$custom_settings = array(
			'sections' => array( 
				array(
					'id'    => 'general_options',
					'title' => __( 'Geral', 'bazar' )
				),
			),
			'settings' => array(
				/**
				 * Section General Options
				*/
				array(
					'id'          => 'social_networks',
					'label'       => __('Perfis de rede sociais', 'bazar'),
					'desc'        => __('Entre com as urls dos seus perfils sociais. Por favor, cada url do perfil por linha.<br /><br />Exemplo:<br />
									<code>http://www.facebook.com/bazardotempo<br />http://www.pinterest.com/bazardotempo</code><br /><br />
									<strong>Sites suportados:</strong>
									<ul>
										<li>GitHub</li>
										<li>Flickr</li>
										<li>Twitter</li>
										<li>Facebook</li>
										<li>Google Plus</li>
										<li>Pinterest</li>
										<li>Tumblr</li>
										<li>Dribble</li>
										<li>Spotify</li>
										<li>Instagram</li>
										<li>Dropbox</li>
										<li>Evernote</li>
										<li>Flattr</li>
										<li>Skype</li>
										<li>SoundCloud</li>
									</ul>', 'bazar'),
					'type'        => 'textarea-simple',
					'section'     => 'general_options'
				),
			)
    	);


		/* allow settings to be filtered before saving */
		$custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

		/* settings are not the same update the DB */
		if ( $saved_settings !== $custom_settings ) {
			update_option( ot_settings_id(), $custom_settings ); 
		}

		/* Lets OptionTree know the UI Builder is being overridden */
		global $ot_has_custom_theme_options;
		$ot_has_custom_theme_options = true;
	}

	add_action( 'init', '_theme_options' );