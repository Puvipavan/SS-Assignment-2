<?php
namespace BACheetahCacheClear;
class Varnish {

	static function run() {

		$settings = \BACheetahCacheClear\Plugin::get_settings();
		if ( ! $settings['varnish'] ) {
			return false;
		}
		/**
		 * @see ba_cheetah_varnish_url
		 * @since 2.3.2
		 */
		@wp_remote_request( apply_filters( 'ba_cheetah_varnish_url', get_site_url() ), array( // phpcs:ignore
			'method' => 'BAN',
		) );
	}
}
