<?php 
	DEFINE( 'TOOLS', get_template_directory() . '/wp-toolbox/' );
	DEFINE( 'INC', TOOLS . '/inc/' );

	/* incluir libs php */
	$inc = array(
		'super-dump.php',
		'class-widget.php',
		'CPT.php'
	);
	foreach ( $inc as $key => $dep ) {
		require_once( INC . '/' . $dep );
	}
?>