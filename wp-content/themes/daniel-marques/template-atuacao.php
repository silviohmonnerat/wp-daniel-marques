<?php
/**
 * Template Name: Atuação
 *
 * @package Betheme
 * @author Muffin Group
 */

get_header(); 
?>
	
<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
		
			
			<div class="entry-content" itemprop="mainContentOfPage">
				<?php 
					$mapposts = new WP_Query( array( 
						'post_type'      => 'atuacao',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'showposts'      => -1
					));
				?>	
				<?php if ( $mapposts->have_posts() ) : ?>
					
					<?php 
						if( $api_key = trim( mfn_opts_get( 'google-maps-api-key' ) ) ){
							$api_key = '?key='. $api_key;
						}
					?>
					<script src="https://maps.googleapis.com/maps/api/js<?php echo $api_key; ?>&v=3.exp&sensor=false"></script>
					<script type="text/javascript">
						(function($) {
							function init_mapa() {
								var args = {
									zoom               : 3,
									maxZoom            : 17,
									center             : new google.maps.LatLng(-22.886206, -43.119615),
									mapTypeId		   : google.maps.MapTypeId.ROADMAP,
									styles	           : [{
										"featureType":"all",
										"elementType":"all",
										"stylers":[
											{
												"invert_lightness":true
											},{
												"saturation":-80
											},{
												"lightness":30
											},{
												"gamma":0.5
											},{
												"hue":"#92aa7f"
											}
										] 
									}],
			                        zoomControl        : true,
			                        zoomControlOptions : {
			                            style: google.maps.ZoomControlStyle.SMALL
			                        },
			                        mapTypeControl     : false,
			                        scaleControl       : false,
			                        scrollwheel        : false,
			                        streetViewControl  : false,
			                        draggable          : true
								};

								var map = new google.maps.Map(document.getElementById("map"), args);								 
								setMarkers(map, locations);
							}

							function setMarkers(map, locations) {
								var image = new google.maps.MarkerImage('images/beachflag.png',
									new google.maps.Size(20, 32),
									new google.maps.Point(0,0),
									new google.maps.Point(0, 32)
								);
								var shadow = new google.maps.MarkerImage('images/beachflag_shadow.png',
									new google.maps.Size(37, 32),
									new google.maps.Point(0,0),
									new google.maps.Point(0, 32)
								);
								var shape = {
									coord: [1, 1, 1, 20, 18, 20, 18 , 1],
									type: 'poly'
								};
								var bounds     = new google.maps.LatLngBounds();
								var infowindow = new google.maps.InfoWindow();
								for (var i = 0; i < locations.length; i++) {
									var myLatLng = new google.maps.LatLng(locations[i].lat, locations[i].lng);
									var marker   = new google.maps.Marker({
										position: myLatLng,
										map: map,
										icon: locations[i].pin
									});

									google.maps.event.addListener(marker, 'click', (function(marker, i) {
										return function() {
									    	infowindow.setContent(locations[i].info);
									    	infowindow.open(map, marker);
									  	}
									})(marker, i));

									bounds.extend(myLatLng);
								}
								map.fitBounds(bounds);
							}

							$(document).ready( function(){
							 	init_mapa();
								$( window ).load( function() {
							        init_mapa();
							    });
								$( window ).resize( function() {
									init_mapa(); 
								});
							});
						})(jQuery);
					</script> 
					<!-- WordPress has found matching posts -->
					<div style="display: none;">
						<?php while ( $mapposts->have_posts() ) : $mapposts->the_post(); ?>
							<?php if ( get_post_meta($post->ID, 'geolocation_lat', true) !== '' && get_post_meta($post->ID, 'geolocation_long', true) !== '' ) : ?>
								<div class="mfn-infowindow" id="item<?php echo get_the_ID(); ?>">
									<h4><?php the_title(); ?></h4>
									<?php if ( get_post_meta($post->ID, 'mfn-infowindow', true) !== '' ) : ?>
										<p><?php echo get_post_meta($post->ID, 'mfn-infowindow', true); ?></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
					<?php 
						
					?>
					<script type="text/javascript">
						var locations = [
							<?php while ( $mapposts->have_posts() ) : $mapposts->the_post(); ?>			
								
								<?php if ( get_post_meta($post->ID, 'geolocation_lat', true) !== '' && get_post_meta($post->ID, 'geolocation_long', true) !== '' ) : ?>
								{
									latlng : new google.maps.LatLng(<?php echo get_post_meta($post->ID, 'geolocation_lat', true); ?>, <?php echo get_post_meta($post->ID, 'geolocation_long', true); ?>), 
									lat: '<?php echo get_post_meta($post->ID, 'geolocation_lat', true); ?>',
									lng: '<?php echo get_post_meta($post->ID, 'geolocation_long', true); ?>',
									info : document.getElementById('item<?php echo get_the_ID(); ?>'), 
									pin : '<?php if(get_post_meta($post->ID, 'mfn-pinmarker', true)){ echo get_post_meta($post->ID, 'mfn-pinmarker', true); } else {echo site_url().'/wp-content/themes/betheme/images/pin-projeto.png';} ?>'
								},
								<?php endif; ?>
							<?php endwhile; ?>
						];
					</script>
					<div class="wrapper-maps">	
						<div id="map" class="acf-map" style="width: 100%; height: 630px;"></div>
						<div class="legend-marker">
							<ul>
								<li class="green"><i class="icon-green"></i><span><?php _e( 'Indicações Legislativas Pontuais' ); ?></span></li>
								<li class="blue"><i class="icon-blue"></i><span><?php _e( 'Toda a Cidade de Niterói' ); ?></span></li>
							</ul>
						</div>
					</div>

				<?php endif; ?>

				<?php 
					while ( have_posts() ){
						the_post();							// Post Loop
						mfn_builder_print( get_the_ID() );	// Content Builder & WordPress Editor Content
					}
				?>
			
			</div>
	
		</div>
		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar(); ?>

	</div>
</div>

<?php get_footer();

// Omit Closing PHP Tags