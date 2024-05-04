<?php

/**
 * Logic to support various parts of WPML.
 *

 */
final class BACheetahWPML {

	/**

	 * @return void
	 */
	static public function init() {
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return;
		}
		add_filter( 'ba_cheetah_inline_editing_enabled', '__return_false' );
		add_filter( 'ba_cheetah_get_wp_widgets_exclude', __CLASS__ . '::filter_wp_widgets_exclude' );
		add_filter( 'ba_cheetah_node_template_post_id', __CLASS__ . '::filter_node_template_post_id' );
		add_filter( 'ba_cheetah_parent_template_node_id', __CLASS__ . '::filter_parent_template_node_id', 10, 3 );
	}

	/**
	 * Filter out the language switcher from the BB content panel
	 * as it must be added to a sidebar to work.
	 *

	 * @param array $exclude
	 * @return array
	 */
	static public function filter_wp_widgets_exclude( $exclude ) {
		$exclude[] = 'WPML_LS_Widget';
		return $exclude;
	}

	/**
	 * Returns the translated post ID for a node template. This makes
	 * it so the translated version of a global node will render.
	 *

	 * @param int $post_id
	 * @return int
	 */
	static public function filter_node_template_post_id( $post_id ) {
		global $sitepress;

		$post_type    = get_post_type( $post_id );
		$lang         = $sitepress->get_current_language();
		$wpml_post    = new WPML_Post_Element( $post_id, $sitepress );
		$trid         = $sitepress->get_element_trid( $post_id, "post_$post_type" );
		$translations = $sitepress->get_element_translations( $trid, "post_$post_type" );

		if ( is_array( $translations ) && isset( $translations[ $lang ] ) ) {
			$post_id = $translations[ $lang ]->element_id;
		}

		return $post_id;
	}

	/**
	 * Returns the translated root node ID for a node template. This makes
	 * it so the translated version of a global node will render.
	 *

	 * @param string template_node_id
	 * @param object $parent
	 * @param array $layout_data
	 * @return string
	 */
	static public function filter_parent_template_node_id( $template_node_id, $parent, $layout_data ) {
		if ( ! isset( $parent->template_root_node ) ) {
			return $template_node_id;
		}

		$root = BACheetahModel::get_node_template_root( $parent->type, $layout_data );

		if ( $root && isset( $root->template_root_node ) && isset( $root->template_node_id ) && ! empty( $root->template_node_id ) ) {
			$template_node_id = $root->template_node_id;
		}

		return $template_node_id;
	}
}

BACheetahWPML::init();
