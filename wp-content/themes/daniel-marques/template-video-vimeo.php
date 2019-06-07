<?php
/**
 * Template Name: Video Vimeo
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