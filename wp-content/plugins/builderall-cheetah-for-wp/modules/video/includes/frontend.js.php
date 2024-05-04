<?php if ( ! BACheetahModel::is_builder_active() && isset( $settings->sticky_on_scroll ) && 'yes' === $settings->sticky_on_scroll ) :?>
	new BACheetahVideo({
		id: '<?php echo $id; ?>',
		sticky: {
			position: '<?php echo isset($settings->sticky_position) ? $settings->sticky_position : 'bottom_right'; ?>',
			width: '<?php echo isset($settings->sticky_size) ? $settings->sticky_size : '300' ?>px',
			mobile:'<?php echo isset($settings->sticky_mobile) ? $settings->sticky_mobile : 'yes' ?>',
			distance_x:'<?php echo isset($settings->sticky_distance_x) ? $settings->sticky_distance_x : '5' ?>px',
			distance_y:'<?php echo isset($settings->sticky_distance_y) ? $settings->sticky_distance_y : '5' ?>px',
		}
	});
<?php endif; ?>