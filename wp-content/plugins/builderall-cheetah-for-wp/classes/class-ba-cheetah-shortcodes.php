<?php

/**
 * Helper class for builder shortcodes
 *

 */
final class BACheetahShortcodes {

	/**
	 * Adds all shortcodes for the builder.
	 *

	 * @return void
	 */
	static public function init() {
		add_shortcode( 'ba_cheetah_insert_layout', 'BACheetahShortcodes::insert_layout' );
		add_shortcode( 'ba_builder_insert_layout', 'BACheetahShortcodes::insert_layout' );
	}

	/**
	 * Renders a layout with the provided post ID and enqueues the
	 * necessary styles and scripts.
	 *

	 * @param array $attrs The shortcode attributes.
	 * @return string
	 */
	static public function insert_layout( $attrs ) {
		$builder_active = BACheetahModel::is_builder_active();
		$post_type      = isset( $attrs['type'] ) ? $attrs['type'] : get_post_types();
		$site_id        = isset( $attrs['site'] ) ? absint( $attrs['site'] ) : null;
		$args           = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
		);

		// Build the args array.
		if ( isset( $attrs['id'] ) ) {

			$args['orderby']             = 'post__in';
			$args['ignore_sticky_posts'] = true;

			if ( is_numeric( $attrs['id'] ) ) {
				$args['post__in'] = array( $attrs['id'] );
			} else {
				$args['post__in'] = explode( ',', $attrs['id'] );
			}
		} elseif ( isset( $attrs['slug'] ) && '' !== $attrs['slug'] ) {
			$args['orderby'] = 'name';
			$args['name']    = $attrs['slug'];
		} else {
			return;
		}

		$render = apply_filters( 'ba_cheetah_insert_layout_render', true, $attrs, $args );

		if ( ! $render ) {
			return;
		}

		// Render and return the layout.
		ob_start();

		if ( $builder_active ) {
			echo '<div class="ba-cheetah-shortcode-mask-wrap"><div class="ba-cheetah-shortcode-mask"></div>';
		}

		BACheetah::render_query( $args, $site_id );

		if ( $builder_active ) {
			echo '</div>';
		}

		return ob_get_clean();
	}
}

BACheetahShortcodes::init();
