<?php
	BACheetahPopups::render_popup_js(
		"click",
		".ba-cheetah-node-$id .button__button",
		$settings->popup_id,
		$settings->click_action,
		(object) ['video_link' => $settings->video_link]
	);
?>
