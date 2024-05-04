var BACheetahLayoutConfig = {
	anchorLinkAnimations : {
		duration 	: 1000,
		easing		: 'swing',
		offset 		: 100
	},
	paths : {
		pluginUrl : '<?php echo BA_CHEETAH_URL; ?>',
		wpAjaxUrl : '<?php echo admin_url( 'admin-ajax.php' ); ?>'
	},
	breakpoints : {
		small  : <?php echo BACheetahUtils::sanitize_number( $global_settings->responsive_breakpoint ); ?>,
		medium : <?php echo BACheetahUtils::sanitize_number( $global_settings->medium_breakpoint ); ?>
	},
	waypoint: {
		offset: 80
	},
	tracking: {
		pixel: {
			id: <?php echo json_encode(get_option( '_ba_cheetah_facebook_pixel_id', '' )) ?>
		}
	}
};
