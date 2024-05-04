<?php
class BACheetahMailingboss {

	public static function getLists()
	{
		if(BACheetahAuthentication::is_authenticated()) {
			$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/mailingboss/get-lists/');
			if(wp_remote_retrieve_response_code($response) === 200) {
				return json_decode(wp_remote_retrieve_body($response), true)['data'];
			}
		}

		return array();
	}

	public static function getListFields($list_uuid) {
		
		if(BACheetahAuthentication::is_authenticated()) {
			$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/mailingboss/get-list-fields/' . $list_uuid);
			if (wp_remote_retrieve_response_code($response) === 200) {
				$decoded = json_decode(wp_remote_retrieve_body($response), true);
				if (isset($decoded['success']) && $decoded['success'] == 'true') {
					return $decoded['data'];
				}
			}
		}

		return array();
	}
}