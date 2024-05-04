<?php
namespace BACheetahCacheClear;
class Defines {

	var $actions = array(
		'ba_cheetah_init_ui',
	);

	static function run() {
		\BACheetahCacheClear\Plugin::define( 'DONOTMINIFY' );
		\BACheetahCacheClear\Plugin::define( 'DONOTCACHEPAGE' );
	}
}
