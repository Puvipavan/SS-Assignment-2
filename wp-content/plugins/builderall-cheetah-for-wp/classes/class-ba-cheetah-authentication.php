<?php

final class BACheetahAuthentication
{

	static public function init()
	{
		define('BA_CHEETAH_AUTENTICATED', self::is_authenticated());
		define('BA_CHEETAH_BUILDERALL', self::is_builderall_user());
		define('BA_CHEETAH_PRO', self::is_pro_user());
		define('BA_CHEETAH_PRO_LIFETIME', self::has_pro_lifetime_access());
		define('BA_CHEETAH_TOKEN', 'Qpe09cW1A3AWEH3DY5NPwMurWTOvgl75TyZavqFdUkibbdhQaXerOTNf3PHxrabxphoxuVOlfznE');

		// Authorization route
		add_action('rest_api_init', function () {

			register_rest_route('ba-cheetah/v1', '/oauth/redirect', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::redirect',
				'permission_callback' => '__return_true'
			));

			// Logout route
			register_rest_route('ba-cheetah/v1', '/oauth/logout', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::logout',
				'permission_callback' => '__return_true'
			));

			// Callback route
			register_rest_route('ba-cheetah/v1', '/oauth/callback', array(
				'methods' => 'GET',
				'callback' => __CLASS__ . '::oauthCallback',
				'args' => array(
					'token' => array(
						'required' => true
					),
					'state' => array(
						'required' => true
					)
				),
				'permission_callback' => '__return_true'
			));
		});

		// Notification
		$hook = is_network_admin() ? 'network_admin_notices' : 'admin_notices';
		add_action($hook, array('BACheetahAuthentication', 'render_notification'));

		// Add hook to pro user
		self::add_verification_pro_user();
	}


	/**
	 * Call the API to send the activation token to the user
	 *

	 * @return boolean
	 */
	static public function send_pro_user_token($email = null) {
		try {
			$user_email = ($email != null) ? $email : get_option('admin_email');
			$domain = get_option('siteurl');
			
			update_option('_ba_cheetah_pro_email', $user_email);

			$data = array(
				'email'   => $user_email,
				'domain' => $domain
			);

			$headers = array(
				'ba-token' => BA_CHEETAH_TOKEN,
				'editor-version' => BA_CHEETAH_VERSION
			);

			$templates_req = wp_remote_post(BA_CHEETAH_DASHBOARD_URL . 'api/ba-cheetah/generate-token/', array(
				'method'      => 'POST',
				'body'        => $data,
				'headers'	  => $headers
			));

			$template_data = (object) json_decode(wp_remote_retrieve_body($templates_req));
			if (wp_remote_retrieve_response_code($templates_req) === 200) {

				if($template_data->success == true) {
					wp_redirect(admin_url('admin.php?sent=1&page=ba-cheetah-settings#pro'));
				} else {
					BACheetahAdminSettings::add_error(__($template_data->message));
				}
			} else {
				BACheetahAdminSettings::add_error(__('We had a problem! Try again later.'));
			}

		} catch (\Throwable $th) {
			BACheetahAdminSettings::add_error(__( 'We had a problem! Try again later.' ));
		}
	}


	/**
	 * Check at builderall server if the user is pro or not
	 *

	 * @return boolean
	 */
	static public function active_pro_user( $token ) {
		try {
			$data = array(
				'token'   => $token
			);

			$headers = array(
				'ba-token' => BA_CHEETAH_TOKEN,
				'editor-version' => BA_CHEETAH_VERSION
			);

			$templates_req = wp_remote_post(BA_CHEETAH_DASHBOARD_URL . 'api/ba-cheetah/active/', array(
				'method'      => 'POST',
				'body'        => $data,
				'headers'	  => $headers
			));

			$template_data = (object) json_decode(wp_remote_retrieve_body($templates_req));
			if (wp_remote_retrieve_response_code($templates_req) === 200) {

				if($template_data->success == true) {
					if(!self::is_authenticated()) {
						update_option('_ba_cheetah_access_token', $template_data->token);
					}
					self::check_user_level();
				} else {
					BACheetahAdminSettings::add_error(__($template_data->message));
				}
			} else {
				BACheetahAdminSettings::add_error(__($template_data->message));
			}

		} catch (\Throwable $th) {
			BACheetahAdminSettings::add_error(__( 'We had a problem! Try again later.' ));
		}
	}

	static private function isCheetahProPlan($plan) {
		return (strpos($plan, 'cheetah-for-wordpress') !== false) ? true : false;
	}

	/**
	 * Check at builderall server if the user is pro or not
	 *

	 * @return boolean
	 */
	static public function check_user_level() {
		try {

			$user_email = $ba_user_email = self::getUserProEmail();
			$domain = get_option('siteurl');
		
			$user = self::user();
			if($user) {
				$ba_user_email = $user['email'];
			}

			$data = array(
				'email'   => $user_email,
				'builderall_email'   => $ba_user_email,
				'domain' => $domain,
			);

			$headers = array(
				'editor-version' => BA_CHEETAH_VERSION,
			);

			$templates_req = wp_remote_post(BA_CHEETAH_DASHBOARD_URL . 'api/ba-cheetah/user-level/', array(
				'method'      => 'POST',
				'body'        => $data,
				'headers'	  => $headers
			));

			$isPro = base64_encode('lite');
			if (wp_remote_retrieve_response_code($templates_req) === 200) {
				$template_data = json_decode(wp_remote_retrieve_body($templates_req));
				$isPro = base64_encode($template_data->level);
			}

			update_option('_ba_cheetah_access', $isPro);

			if( !self::is_authenticated() && self::is_pro_user()) {
				// Get the Token
				$userToken = self::callUserOfficeToken();
				if($userToken != false) {
					update_option('_ba_cheetah_access_token', $userToken);
				} else {
					update_option('_ba_cheetah_access', base64_encode('lite'));
					delete_option('_ba_cheetah_access_token');
					delete_option('_ba_cheetah_pro_email');
				}
			}

		} catch (\Throwable $th) {
			update_option('_ba_cheetah_access', base64_encode('lite'));
		}
	}

	/**
	 * Create an intervals array to use at cron recurrence
	 *

	 * @param array $schedules An array of intervals.
	 * @return array
	 */
	static public function isa_add_cron_recurrence_interval( $schedules ) {


		$schedules['every_minute'] = array(
			'interval'  => 60,
			'display'   => __( 'Every Minute' )
		);
		$schedules['every_three_minutes'] = array(
				'interval'  => 180,
				'display'   => __( 'Every 3 Minutes' )
		);

		$schedules['every_fifteen_minutes'] = array(
				'interval'  => 900,
				'display'   => __( 'Every 15 Minutes' )
		);

		$schedules['every_thirty_minutes'] = array(
				'interval'  => 1800,
				'display'   => __( 'Every 30 Minutes' )
		);

		// Native values for the recurrence are ‘hourly’, ‘daily’, and ‘twicedaily’

		return $schedules;
	}


	/**
	 * Add action to call builderall server and check if user is pro
	 *

	 * @return void
	 */

	static public function add_verification_pro_user() {
		add_filter( 'cron_schedules', __CLASS__ . '::isa_add_cron_recurrence_interval' );

		if ( ! wp_next_scheduled( 'ba_cheetah_pro_user') ) {
			wp_schedule_event( time(), 'twicedaily', 'ba_cheetah_pro_user');
		}

		add_action('ba_cheetah_pro_user', array('BACheetahAuthentication', 'check_user_level') );
	}

	// Save token
	static public function oauthCallback(WP_REST_Request $request)
	{
		if ($request['state'] === get_option('_ba_cheetah_request_state')) {
			if(update_option('_ba_cheetah_access_token', $request['token'])) {

				// Site id
				if(isset($request['site_id'])) {
					update_option('_ba_cheetah_site_id', $request['site_id']);
					self::getSiteId(true);
				}

				self::check_user_level();

				wp_redirect(admin_url('admin.php?page=ba-cheetah-settings#welcome'));
				exit;
			} else {
				return new WP_Error('error_update_auth_token', 'Error to update Authentication Token', array('status' => 403));
			}
		}

		return new WP_Error('invald_state', 'Invalid state', array('status' => 403));
	}

	/**
	 * getSiteId
	 *
	 * @return void
	 */
	static public function getSiteId($forceGetID = false)
	{
		$site_id = get_option('_ba_cheetah_site_id');

		if ($site_id && !$forceGetID) return $site_id;

		$data = http_build_query(array(
			'site_id' => $site_id,
			'url' => get_site_url(),
			'title' => get_bloginfo('name')
		));

		$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/auth/get-site-id?' . $data);
		if (wp_remote_retrieve_response_code($response) === 200) {
			$data = json_decode(wp_remote_retrieve_body($response), true);
			if (isset($data['site_id'])) {
				$site_id = $data['site_id'];
				update_option('_ba_cheetah_site_id', $site_id);
			}
		}

		return $site_id;
	}

	/**
	 * Redirects the user to the oauth screen passed as a token parameter
	 * which is also saved in the database to check the bearer token from the panel
	 * if the token was requested by that wordpress installation
	 *
	 * @return void
	 */
	static public function redirect()
	{

		$state = md5(uniqid(rand(), true));
		update_option('_ba_cheetah_request_state', $state);

		$query = http_build_query(array(
			'state' => $state,
			'redirect_url' => get_rest_url(null, 'ba-cheetah/v1/oauth/callback'),
			'entity' => get_bloginfo('name') != null ? get_bloginfo('name') : get_bloginfo('url'),
			'cancel_url' => get_admin_url(null, 'admin.php?page=ba-cheetah-settings#welcome'),
			'site_id' => get_option('_ba_cheetah_site_id')
		));

		wp_redirect(BA_CHEETAH_DASHBOARD_URL . 'oauth/approval?' . $query);

		exit;
	}

	static public function isSameEmailToBaAndPro() {
		$user = self::user();
		if($user) {
			$ba_user_email = $user['email'];
			$user_email = self::getUserProEmail();

			return ($ba_user_email == $user_email) ? true : false;
		}

		return false;
	}

	
	/**
	 * Return User Pro email register
	 *
	 * @return string
	 */
	static private function getUserProEmail() {
		$user_email = (get_option('_ba_cheetah_pro_email')) ? get_option('_ba_cheetah_pro_email') : get_option('admin_email');
		update_option('_ba_cheetah_pro_email', $user_email);

		return $user_email;
	}


	/**
	 * Return User Data from email
	 *
	 * @param string $email User email.
	 * @return Request
	 */
	static private function callUserOfficeToken($email = null) {
		try {
			$user_email = ($email != null) ? $email :  self::getUserProEmail();
			if(empty($user_email)) {
				$user_email = self::getUserProEmail();
			}
			
			$headers = array(
				'ba-token' => BA_CHEETAH_TOKEN,
				'editor-version' => BA_CHEETAH_VERSION
			);

			$templates_req = wp_remote_get(BA_CHEETAH_DASHBOARD_URL . 'api/ba-cheetah/user-office-token/' . $user_email, array(
				'method'      => 'GET',
				'headers'	  => $headers
			));

			$template_data = (object) json_decode(wp_remote_retrieve_body($templates_req));

			if (wp_remote_retrieve_response_code($templates_req) === 200) {
				return $template_data->token;
			}

			return false;

		} catch (\Throwable $th) {
			return false;
		}
	}

	static public function logoutProUser() {
		
		delete_option('_ba_cheetah_request_state');
		delete_option('_ba_cheetah_access_token');
		delete_option('_ba_cheetah_supercheckout_token');
		delete_option('_ba_cheetah_pro_email');
		
		update_option('_ba_cheetah_access', base64_encode('lite'));

		wp_redirect(admin_url('admin.php?page=ba-cheetah-settings#pro'));
		exit();
	}

	static public function logout() {
		$sameEmail = self::isSameEmailToBaAndPro();
		
		delete_option('_ba_cheetah_request_state');
		delete_option('_ba_cheetah_access_token');
		delete_option('_ba_cheetah_supercheckout_token');

		if($sameEmail) {
			delete_option('_ba_cheetah_pro_email');
		} else {
			$userToken = self::callUserOfficeToken();
			if($userToken != false) {
				update_option('_ba_cheetah_access_token', $userToken);
			}
		}

		self::check_user_level();
		wp_redirect(admin_url('admin.php?page=ba-cheetah-settings#integrations'));
		exit();
	}

	/**
	 * Shows notification for user to become pro
	 */

	public static function render_notification()
	{

		if (!self::notification_enabled()) {
			return false;
		}
		$btn = sprintf(
			'<p class="buttons">
				<a href="%s" class="button button-primary enable-stats">%s</a>&nbsp;
			</p>',
			get_rest_url(null, 'ba-cheetah/v1/oauth/redirect'),
			__("Link account", 'ba-cheetah')
		);

		$message = sprintf(
			/* translators: %s: branded builder name */
			__('Link your Builderall Builder for WordPress with your Builderall account to unlock integrations with Booking, Supercheckout, Mailingboss and more!', 'ba-cheetah'),
			BACheetahModel::get_branding()
		);

		echo '<div class="notice notice-warning is-dismissible" style="">';

		printf('<p>%s</p> %s', $message, $btn);

		echo '</div>';
	}

	private static function notification_enabled()
	{
		// If is BA or Pro User, does not show
		if(self::is_builderall_user() || self::is_pro_user()) {
			return false;
		}

		global $pagenow;
		$screen = get_current_screen();
		$show   = false;

		if ('dashboard' == $screen->id) {
			return true;
		}

		if(
			BACheetahUserTemplatesLayout::is_layout_content_type($screen->post_type)
		) {
			$show = true;
		}

		if ('admin.php' == $pagenow && isset($_GET['page']) && 'ba-cheetah-settings' == $_GET['page']) {
			$show = true;
		}

		if ('dashboard-network' == $screen->id) {
			$show = true;
		}

		if ('0' === get_site_option('ba_cheetah_account_connect_notification')) {
			$show = false;
		}

		if (!is_super_admin()) {
			$show = false;
		}

		return ($show && !get_site_option('ba_cheetah_account_connect_notification')) ? true : false;
	}


	/**
	 * Return user level access string
	 *
	 * @return string
	 */
	static public function get_user_level_access() {

		$level = get_option('_ba_cheetah_access');
		if(!isset($level)) {
			$level = 'lite';
			update_option('_ba_cheetah_access', base64_encode('lite'));
		}

		return base64_decode($level);
	}


	/**
	 * Return user authenticated
	 *
	 * @return boolean
	 */
	static public function is_authenticated() {
		return get_option('_ba_cheetah_access_token') != false;
	}


	/**
	 * Return if user is builderall or not
	 *
	 * @return boolean
	 */
	static public function is_builderall_user() {
		return (strpos(self::get_user_level_access(), 'builderall') !== false) ? self::is_authenticated() : false;
	}


	/**
	 * Return if user have lifetime access
	 *
	 * @return boolean
	 */
	static public function has_pro_lifetime_access() {
		return (strpos(self::get_user_level_access(), 'lifetime') !== false) ? true : false;
	}

	/**
	 * Return if user is pro or not
	 *
	 * @return boolean
	 */
	static public function is_pro_user() {
		return (strpos(self::get_user_level_access(), 'pro') !== false) ? true : false;
	}

	/**
	 * Return User data
	 *
	 * @return Array
	 */
	static private function getUser() {
		$response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/auth/me');
		if(wp_remote_retrieve_response_code($response) === 200) {
			return json_decode(wp_remote_retrieve_body($response), true);
		}

		return null;
	}


	/**
	 * Return a User Object is pro or not
	 *
	 * @param boolean $returnProUser Choose true if you want return User Pro (based in data from Token Authentication)
	 * @return Array
	 */
	static public function user($returnProUser = false) {
		if(self::is_authenticated()) {
			$user = self::getUser();

			if($user) {
				if(!$returnProUser && self::isCheetahProPlan($user['plan_slug'])) {
					return null;
				}
			
				return $user;
			}
		}
		return null;
	}

}

BACheetahAuthentication::init();
