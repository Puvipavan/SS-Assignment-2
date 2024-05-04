<?php

if ( 'slideshow' == $settings->bg_type ) :

	$source = BACheetahModel::get_row_slideshow_source( $row );

	if ( ! empty( $source ) ) : ?>

	YUI({'logExclude': { 'yui': true } }).use('ba-cheetah-slideshow', function(Y) {

		slideshow = new Y.BA.Slideshow({
			autoPlay            : true,
			bgslideshow         : true,
			crop                : true,
			loadingImageEnabled : false,
			randomize           : <?php echo esc_attr($settings->ss_randomize); ?>,
			responsiveThreshold : 0,
			touchSupport        : false,
			source              : [{<?php echo $source; ?>}],
			speed               : <?php echo esc_attr($settings->ss_speed * 1000); ?>,
			stretchy            : true,
			stretchyType        : 'contain',
			transition          : '<?php echo esc_attr($settings->ss_transition); ?>',
			transitionDuration  : <?php echo $settings->ss_transitionDuration ? esc_attr($settings->ss_transitionDuration) : 1 ?>
		});

		jQuery( '.ba-cheetah-node-<?php echo $id; ?>' ).imagesLoaded( function(){
			slideshow.render('.ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-bg-slideshow');
		} );
	});
	
	<?php

	endif;

endif;

?>
