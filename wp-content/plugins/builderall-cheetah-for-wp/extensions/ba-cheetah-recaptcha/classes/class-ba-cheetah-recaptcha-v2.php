<?php

/**
 * Recaptcha class
 *

 */
final class BACheetahRecaptchaV2 {

	/**
	 * Define route to verify recaptcha
	 *

	 * @return void
	 */
	static public function init() {
		self::register_validation_route();
	}

	static public function register_validation_route ()
	{
		add_action( 'rest_api_init', function () {
			register_rest_route( 'ba-cheetah/v1', '/recaptcha/verify', array(
			  'methods' => 'POST',
			  'callback' => __CLASS__ . '::verify',
			  'permission_callback' => function () {
				return true;
			}
			));
		});
	}

	/**
	 * Verify recaptcha response
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	static public function verify(WP_REST_Request $request)
	{
		$g_recaptcha_response = $request->get_param('response');

		$req = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
			'method'      => 'POST',
			'body'        => array(
				'secret' 	=> get_option('_ba_cheetah_recaptcha_secretkey'),
				'response' 	=> $g_recaptcha_response,
				'remoteip' 	=> $_SERVER['REMOTE_ADDR']
			),
		));

		if (wp_remote_retrieve_response_code($req) === 200) {
			$body = json_decode(wp_remote_retrieve_body($req), true);
			if ($body['success']) {
				return new WP_REST_Response( $body, 200 );
			} else {
				return new WP_Error( 'recaptcha_validation_exception', 'Recaptcha Exception', $body['error-codes'] );
			}
			
		} else {
			dd('request error');
		}
	}


	/**
	 * Get keys
	 *
	 * @return stdClass or boolean in case of invalid
	 */
	static public function keys ()
	{
		$sitekey = get_option('_ba_cheetah_recaptcha_sitekey');
		$secretkey = get_option('_ba_cheetah_recaptcha_secretkey');

		if ( $sitekey && $secretkey ) {
			$keys = new stdClass;
			$keys->sitekey = $sitekey;
			$keys->secretkey = $secretkey;
			return $keys;
		}
		return false;
	}


	/**
	 * Render frontend recaptcha tag
	 *
	 * @return void
	 */
	static public function render ()
	{
		if ($keys = self::keys()) {
			if (BACheetahModel::is_builder_active()) {
				echo '<small>Recaptcha enabled. Challenge will be shown on the live page</small>';
			}
			else {
				echo sprintf('<div class="g-recaptcha" data-sitekey="%s"></div>', $keys->sitekey);
			}
		}
		else {
			echo 'Some Recaptcha keys are missing';
		}
	}

}

BACheetahRecaptchaV2::init();
