<?php
class BACheetahSupercheckout
{
	public static function getToken()
	{
		$supercheckout_token = get_option('_ba_cheetah_supercheckout_token');
		if ($supercheckout_token) {
			return $supercheckout_token;
		}

		if(BACheetahAuthentication::is_builderall_user()) {
			$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/supercheckout/get-token/' . BACheetahAuthentication::getSiteId());
			if (wp_remote_retrieve_response_code($response) === 200) {
				$data = json_decode(wp_remote_retrieve_body($response), true);
				$supercheckout_token = $data['token'];
				update_option('_ba_cheetah_supercheckout_token', $supercheckout_token);
				return $supercheckout_token;
			}
		}

		return null;
	}

	/**
	 * Undocumented function
	 *
	 * @param boolean $retry to avoid infinite loops
	 * @return void
	 */
	public static function getProducts($retry = true)
	{
		$products = array();

		$token = BACheetahSupercheckout::getToken();

		if($token) {

			$url = BA_CHEETAH_SC_URL . 'api/products';
			$args = array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type' => 'application/json',
					'Accept' => 'application/json'
				),
			);

			$response = wp_remote_get($url, $args);
			$status = wp_remote_retrieve_response_code($response);

			switch ($status) {
				case '200':
					$data = json_decode(wp_remote_retrieve_body($response), true);
					if(isset($data['products'])) {
						foreach($data['products'] as $product) {
							$products[$product['versions'][0]['hash']] = array(
								'hash' => $product['versions'][0]['hash'],
								'name' => $product['name'],
							);
						}
					}
					break;

				/**
				 * In case of cached token expired, renew the token and try again
				 */
				case '401':
					delete_option('_ba_cheetah_supercheckout_token');
					if ($retry) {
						$products = self::getProducts(false);
					}
					break;
				
				default:
					$products = array();
					break;
			}
		}

		return $products;

	}
}
