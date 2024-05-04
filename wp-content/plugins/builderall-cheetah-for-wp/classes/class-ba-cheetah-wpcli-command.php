<?php

/**
 * WP Cli commands for Cheetah Builder.
 */
class BAcheetah_WPCLI_Command extends WP_CLI_Command {

	/**
	 * Deletes preview, draft and live CSS/JS asset cache for all posts.
	 *
	 * ## OPTIONS
	 *
	 * [--network]
	 * Clears the page builder cache for all sites on a network.
	 *
	 * [--all]
	 * Clears plugin and bb-theme cache.
	 *
	 * ## EXAMPLES
	 *
	 * 1. wp cheetah clearcache
	 *      - Clears the page builder cache for all the posts on the site.
	 * 2. wp cheetah clearcache --network
	 *      - Clears the page builder cache for all the posts on a network.
	*/
	public function clearcache( $args, $assoc_args ) {

		$network = false;
		$all     = false;

		if ( isset( $assoc_args['network'] ) && true == $assoc_args['network'] && is_multisite() ) {
			$network = true;
		}

		if ( isset( $assoc_args['all'] ) ) {

			// make sure theme functions are loaded.
			if ( class_exists( 'BACheetahCustomizer' ) ) {
				$all = true;
			} else {
				WP_CLI::error( __( '--all switch used but bb-theme is not active. If using multisite bb-theme must be active on the root site.', 'ba-cheetah' ) );
			}
		}

		if ( class_exists( 'BACheetahModel' ) ) {

			if ( true == $network ) {

				$blogs = get_sites();

				foreach ( $blogs as $keys => $blog ) {

					// Cast $blog as an array instead of WP_Site object
					if ( is_object( $blog ) ) {
						$blog = (array) $blog;
					}

					$blog_id = $blog['blog_id'];
					switch_to_blog( $blog_id );
					BACheetahModel::delete_asset_cache_for_all_posts();
					/* translators: %s: current blog name */
					WP_CLI::success( sprintf( _x( 'Cleared the Builderall Builder cache for blog %s', 'current blog name', 'ba-cheetah' ), get_option( 'home' ) ) );
					if ( $all ) {
						BACheetahCustomizer::refresh_css();
						/* translators: %s: current blog name */
						WP_CLI::success( sprintf( _x( 'Rebuilt the theme cache for blog %s', 'current blog name', 'ba-cheetah' ), get_option( 'home' ) ) );
					}
					restore_current_blog();
				}
			} else {
				BACheetahModel::delete_asset_cache_for_all_posts();
				WP_CLI::success( __( 'Cleared the Builderall Builder cache', 'ba-cheetah' ) );
				if ( $all ) {
					BACheetahCustomizer::refresh_css();
					WP_CLI::success( __( 'Rebuilt the theme cache', 'ba-cheetah' ) );
				}
			}
			/**
			 * After cache is cleared.
			 * @see ba_cheetah_cache_cleared
			 */
			do_action( 'ba_cheetah_cache_cleared' );
		}
	}
	
}

/**
 * Add WPCLI commands
 */
WP_CLI::add_command( 'cheetah', 'BAcheetah_WPCLI_Command' );
