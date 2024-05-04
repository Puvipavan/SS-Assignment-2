<?php

if (isset($page_settings->popup) && (int) $page_settings->popup && isset($page_settings->popup_trigger) && !BACheetahModel::is_builder_active() && BA_CHEETAH_PRO) {
	BACheetahPopups::render_popup_js($page_settings->popup_trigger,	"",	$page_settings->popup,'popup',	(object) [
			'popup_scroll_point' => $page_settings->popup_scroll_point,
			'popup_timer_minutes' => $page_settings->popup_timer_minutes,
			'popup_timer_seconds' => $page_settings->popup_timer_seconds
		]
	);
}
?>
