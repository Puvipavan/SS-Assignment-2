<?php
class BACheetahWebinar {

	public static function getWebinars()
	{
        $response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/webinar/get-webinars');
        if(wp_remote_retrieve_response_code($response) === 200) {
            return json_decode(wp_remote_retrieve_body($response), true);
        } else {
            return array();
        }
	}
}
