<?php
/**
 * The template for displaying content in the single.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post-wrapper-content">

		<?php
			// Content Builder & WordPress Editor Content
			mfn_builder_print( $post->ID );	
		?>

		<div class="section section-post-footer">
			<div class="section_wrapper clearfix">
			
				<div class="column one post-pager">
					<?php
						// List of pages
						wp_link_pages(array(
							'before'			=> '<div class="pager-single">',
							'after'				=> '</div>',
							'link_before'		=> '<span>',
							'link_after'		=> '</span>',
							'next_or_number'	=> 'number'
						));
					?>
				</div>
				
			</div>
		</div>
		
	</div>

	<?php if( mfn_opts_get( 'blog-comments' ) ): ?>
		<div class="section section-post-comments">
			<div class="section_wrapper clearfix">
			
				<div class="column one comments">
					<?php comments_template( '', true ); ?>
				</div>
				
			</div>
		</div>
	<?php endif; ?>

</div>