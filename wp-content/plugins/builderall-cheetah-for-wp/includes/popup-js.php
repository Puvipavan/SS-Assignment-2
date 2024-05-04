<?php if (!BACheetahModel::is_builder_active()): ?>
	BACheetahLayout.makePopup(
		'<?php echo $trigger ?>',
		`<?php $type == 'popup' ? BACheetah::render_content_by_id($popup_id, 'div') : BACheetahPopups::render_popup_video($settings->video_link) ?>`,
		'<?php echo $target ?>',
		'<?php echo $popup_config['width'] ?>',
		'<?php echo BACheetahColor::hex_or_rgb( $popup_config['overlay_color'] ) ?>',
		JSON.parse('<?php echo json_encode($settings) ?>'),
	);
<?php endif; ?>