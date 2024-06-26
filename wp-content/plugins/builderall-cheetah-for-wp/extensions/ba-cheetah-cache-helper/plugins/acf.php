<?php
namespace BACheetahCacheClear;
class ACF {
	var $name    = 'Advanced Custom Fields';
	var $url     = 'https://wordpress.org/plugins/advanced-custom-fields/';
	var $actions = array( 'admin_init' );

	function run() {
		add_action( 'acf/save_post', function( $post_id ) {
			\BACheetahModel::delete_all_asset_cache( $post_id );

			// delete partials
			\BACheetahModel::delete_asset_cache_for_all_posts( '*layout-partial*' );
		});
	}
}
