<?php
final class BACheetahPopups
{

	static public $post_type = 'ba-cheetah-popup';

	/**
	 * Popups list for config select
	 *
	 * @return array
	 */
	public static function get_popups_to_select($default = false) {
		$posts = get_posts(array(
			'post_type' => self::$post_type,
			'nopaging' => true,
		));
		$popups = array();
		if ($default) {
			array_push($popups, array(
				'value' => '',
				'label' => 'Select One...'
			));
		}
		foreach ($posts as $key => $post) {
			array_push($popups, array(
				'value' => $post->ID,
				'label' => $post->post_title
			));
		}
		return $popups;
	}

	/**
	 * Settings for a specific popup.
	 * If dont have own settings, return global
	 *
	 * @param int $popup_id
	 * @return array
	 */

    public static function get_popup_settings($popup_id = null) {
        // Global settings
        $global_settings = BACheetahModel::get_global_settings();

        // Popup width by id
        $popup_width = get_post_meta($popup_id, "ba-cheetah-popup-width", true);

        if(empty($popup_width)) {
            $popup_width = $global_settings->popup_width ? $global_settings->popup_width.$global_settings->popup_width_unit : '700px';
        }

        return [
            'width' => $popup_width,
            'overlay_color' => $global_settings->popup_overlay_color ? $global_settings->popup_overlay_color : 'rgba(0,0,0,0.8)'
        ];
    }

	/**
	 * Lite users canot access to popups
	 * 
	 * @return array
	 */

	public static function get_popup_action() {
		return array('popup' => __( 'Popup', 'ba-cheetah' ));
	}

	/**
	 * Display the html markup for video popup
	 *
	 * @param String $src
	 * @return void
	 */

	public static function render_popup_video($url = '') {

		if (!filter_var($url, FILTER_VALIDATE_URL)) return;

		$video_settings = [
			'video_type' => 'embed',
			'embed_code' => $url,
			'autoplay' => '0',
			'loop' => '0',
			'sticky_on_scroll' => 'no'
		];

		require BA_CHEETAH_DIR . 'includes/popup-video-template.php';
	}

	/**
	 * Render javascript trigger for a popup id or video
	 *
	 * @param string $target the popup target
	 * @param string $action popup or video
	 * @param integer $popup_id
	 * @param string $video_link
	 * @return void
	 */
	
	public static function render_popup_js($trigger = 'click', $target = null, $popup_id = null, $type = 'popup', stdClass $settings = null) {

		$trigger_validations = [
			'click' => !is_null($target)
				&& in_array($type, array('popup', 'video'))
				&& (($type == 'popup' && $popup_id) || $type == 'video' && isset($settings->video_link) && filter_var($settings->video_link, FILTER_VALIDATE_URL)),
			'scroll' => isset($settings->popup_scroll_point),
			'timer' => isset($settings->popup_timer_minutes) 
				&& isset($settings->popup_timer_seconds)
				&& $settings->popup_timer_minutes + $settings->popup_timer_seconds > 0,
			'exit' => true
		];

		if (!in_array($trigger, array_keys($trigger_validations)) || $trigger_validations[$trigger] !== true) return;

		$popup_config = self::get_popup_settings($type == 'popup' ? $popup_id : null);
		
		require BA_CHEETAH_DIR . 'includes/popup-js.php';
	}
}
