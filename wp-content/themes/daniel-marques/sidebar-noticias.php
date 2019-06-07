<?php
/**
 * The Page Sidebar containing the widget area.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

// echo -------------------------------------------------------------
if ( is_active_sidebar( 'noticias' ) ) {
	
	echo '<div class="sidebar sidebar-noticias four columns">';
		echo '<div class="widget-area clearfix">';
		
			dynamic_sidebar( 'noticias' );
			
		echo '</div>';
	echo '</div>';	
}
