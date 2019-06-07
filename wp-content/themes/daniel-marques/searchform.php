<?php
/**
 * The main template file.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$translate['search-placeholder'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-placeholder','Enter your search') : __('Enter your search','betheme');
?>

<a href="#" class="icon_close"><i class="icon-cancel"></i></a>
<div class="search-form">
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if( mfn_opts_get('header-search') == 'shop' ): ?>
			<input type="hidden" name="post_type" value="product" />
		<?php endif;?>

		<input type="text" class="field" name="s" id="s" placeholder="<?php echo $translate['search-placeholder']; ?>" />			
		<button type="submit" class="submit"><i class="icon_search icon-search"></i></button>	
		
	</form>
</div>