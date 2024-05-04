<?php
class BACheetahBookingCalendars {

	public static function getCalendars()
	{
		$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/booking/get-calendars/');
		if(wp_remote_retrieve_response_code($response) === 200) {
			return json_decode(wp_remote_retrieve_body($response), true);
		} else {
			return array();
		}
		
	}
}
