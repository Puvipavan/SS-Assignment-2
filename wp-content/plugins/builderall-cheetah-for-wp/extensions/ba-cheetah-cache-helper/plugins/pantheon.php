<?php
namespace BACheetahCacheClear;
class Pantheon {

	var $name = 'Pantheon Hosting';
	var $url  = 'https://pantheon.io/';

	static function run() {
		if ( function_exists( 'pantheon_wp_clear_edge_all' ) ) {
			$ret = pantheon_wp_clear_edge_all();
		}
	}
}
