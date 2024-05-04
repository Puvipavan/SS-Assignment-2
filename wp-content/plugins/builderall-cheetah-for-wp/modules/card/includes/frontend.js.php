<?php
	BACheetahPopups::render_popup_js(
		"click",
		".ba-cheetah-node-$id .button__button",
		$settings->btn_popup_id,
		$settings->btn_click_action,
		(object) ['video_link' => $settings->btn_video_link]
	);
?>
