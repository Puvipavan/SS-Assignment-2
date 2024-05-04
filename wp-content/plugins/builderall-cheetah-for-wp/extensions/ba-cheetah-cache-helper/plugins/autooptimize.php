<?php
namespace BACheetahCacheClear;
class Autooptimize {

	var $name = 'AutoOptimize';
	var $url  = 'https://wordpress.org/plugins/autoptimize/';

	var $filters = array( 'init' );

	static function run() {
		if ( class_exists( '\autoptimizeCache' ) ) {
			\autoptimizeCache::clearall();
		}
	}

	function filters() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			add_filter( 'autoptimize_filter_noptimize', '__return_true' );
		}
	}
}
