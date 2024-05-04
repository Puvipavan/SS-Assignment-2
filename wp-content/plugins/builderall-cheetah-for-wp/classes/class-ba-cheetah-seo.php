<?php
/**

 */
final class BACheetahSEO {
	public function __construct() {
		$this->filters();
	}
	private function filters() {
		/**
		 * WordPress 5.5 adds native support for sitemaps so we need to remove our post type and taxonomy.
		 */
		add_filter( 'wp_sitemaps_post_types', function( $post_types ) {
				unset( $post_types['ba-cheetah-template'] );
				return $post_types;
		} );
		add_filter( 'wp_sitemaps_taxonomies', function( $taxonomies ) {
				unset( $taxonomies['ba-cheetah-template-category'] );
				return $taxonomies;
		} );
	}
}
new BACheetahSEO;
