<?php
/**
 * Misc functions that are not in classes.
 *
 * For 3rd party compatibility actions/filters see classes/class-ba-cheetah-compatibility.php
 */

/**
 * Siteground cache captures shutdown and breaks our dynamic js loading.
 * Siteground changed their plugin, this code has to run super early.

 */
if ( isset( $_GET['ba_cheetah_load_settings_config'] ) ) {
	add_filter( 'option_siteground_optimizer_fix_insecure_content', '__return_false' );
}

/**
 * Try to unserialize data normally.
 * Uses a preg_callback to fix broken data caused by serialized data that has broken offsets.
 *

 * @param string $data unserialized string
 * @return array
 */
function ba_cheetah_maybe_fix_unserialize( $data ) {
	// @codingStandardsIgnoreStart
	$unserialized = @unserialize( $data );
	// @codingStandardsIgnoreEnd
	if ( ! $unserialized ) {
		$unserialized = unserialize( preg_replace_callback( '!s:(\d+):"(.*?)";!', 'ba_cheetah_maybe_fix_unserialize_callback', $data ) );
	}
	return $unserialized;
}

/**
 * Callback function for ba_cheetah_maybe_fix_unserialize()
 *

 */
function ba_cheetah_maybe_fix_unserialize_callback( $match ) {
	return ( strlen( $match[2] ) == $match[1] ) ? $match[0] : 's:' . strlen( $match[2] ) . ':"' . $match[2] . '";';
}

/**
 * Set sane settings for SSL

 */
function ba_cheetah_set_curl_safe_opts( $handle ) {
	curl_setopt( $handle, CURLOPT_SSL_VERIFYPEER, 1 );
	curl_setopt( $handle, CURLOPT_SSL_VERIFYHOST, 2 );
	curl_setopt( $handle, CURLOPT_CAINFO, ABSPATH . WPINC . '/certificates/ca-bundle.crt' );
	return $handle;
}

/**
 * Fix pagination on category archive layout.

 */
function ba_cheetah_theme_builder_cat_archive_post_grid( $query ) {
	if ( ! $query ) {
		return;
	}

	return;
	

	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_archive || ! $query->is_category ) {
		return;
	}

	$args       = array(
		'post_type'   => 'ba-cheetah-theme-layout',
		'post_status' => 'publish',
		'fields'      => 'ids',
		'meta_query'  => array(
			'relation' => 'OR',
			array(
				'key'     => '_ba_cheetah_theme_builder_locations',
				'value'   => 'general:site',
				'compare' => 'LIKE',
			),
			array(
				'key'     => '_ba_cheetah_theme_builder_locations',
				'value'   => 'taxonomy:category',
				'compare' => 'LIKE',
			),
			array(
				'key'     => '_ba_cheetah_theme_builder_locations',
				'value'   => 'general:archive',
				'compare' => 'LIKE',
			),
		),
	);
	$post_grid  = null;
	$object     = null;
	$exclusions = array();

	if ( $query->get( 'cat' ) ) {
		$term = get_term( $query->get( 'cat' ), 'category' );
	} elseif ( $query->get( 'category_name' ) ) {
		$term = get_term_by( 'slug', $query->get( 'category_name' ), 'category' );
	}

	if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
		$term_id              = (int) $term->term_id;
		$object               = 'taxonomy:category:' . $term_id;
		$args['meta_query'][] = array(
			'key'     => '_ba_cheetah_theme_builder_locations',
			'value'   => $object,
			'compare' => 'LIKE',
		);
	}

	$layout_query = new WP_Query( $args );
	if ( $layout_query->post_count > 0 ) {

		foreach ( $layout_query->posts as $i => $post_id ) {
			$exclude    = false;

			if ( ! $exclude ) {
				$data = BACheetahModel::get_layout_data( 'published', $post_id );
				if ( ! empty( $data ) ) {

					foreach ( $data as $node_id => $node ) {

						if ( 'module' != $node->type ) {
							continue;
						}

						if ( ! isset( $node->settings->type ) || 'post-grid' != $node->settings->type ) {
							continue;
						}

						// Check for `post-grid` with custom query source.
						if ( 'custom_query' == $node->settings->data_source ) {
							$post_grid = BACheetahLoop::custom_query( $node->settings );
							break;
						}
					}
				}
			}

			if ( $post_grid ) {
				break;
			}
		}
	}

	return $post_grid;
}

/**
 * Fix canonical for singular layout with post-grid module pagination.

 */
function ba_cheetah_theme_builder_has_post_grid() {
	
	return false;

	if ( empty( $layout_ids ) ) {
		return false;
	}

	foreach ( $layout_ids as $layout_id ) {
		$data = BACheetahModel::get_layout_data( 'published', $layout_id );

		foreach ( $data as $node_id => $node ) {
			if ( 'module' != $node->type ) {
				continue;
			}

			if ( isset( $node->settings->type ) && 'post-grid' == $node->settings->type ) {
				return true;
			}
		}
	}

	return false;
}
