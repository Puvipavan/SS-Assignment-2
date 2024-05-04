<?php

class BaCheetahAuthHttp
 {

	static private function mergeAuthHeader($args = []) {

		$args = array_merge($args, array('timeout' => 30000));
		
		$token = get_option('_ba_cheetah_access_token');

		if ($token) {
			$args['headers']['Authorization'] = 'Bearer ' . $token;
			$args['headers']['editor-version'] = BA_CHEETAH_VERSION;
		}

		return $args;
	}

	static private function response($response) {

		// Logout the user if the token is expired or invalid
		if(wp_remote_retrieve_response_code($response) === 401) {
			if(!BA_CHEETAH_PRO) {
				delete_option('_ba_cheetah_access_token');
			}
		}
		
		return $response;
	}

	static public function get($url, $args = array())
	{
		return self::response((new WP_Http())->get($url, self::mergeAuthHeader($args)));
	}

	static public function post($url, $args = array())
	{
		return self::response((new WP_Http())->post($url, self::mergeAuthHeader($args)));
	}

	static public function request($url, $args = array())
	{
		return self::response((new WP_Http())->request($url, self::mergeAuthHeader($args)));
	}

}