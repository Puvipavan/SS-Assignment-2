<?php

/**
 * Handles the revisions UI for the builder.
 *

 */
final class BACheetahRevisions {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		add_filter( 'ba_cheetah_ui_js_config', __CLASS__ . '::ui_js_config' );
		add_filter( 'ba_cheetah_main_menu', __CLASS__ . '::main_menu_config' );
	}

	/**
	 * Adds revision data to the UI JS config.
	 *

	 * @param array $config
	 * @return array
	 */
	static public function ui_js_config( $config ) {
		$config['revisions']       = self::get_config( $config['postId'] );
		$config['revisions_count'] = isset( $config['revisions']['posts'] ) && is_array( $config['revisions']['posts'] ) ? count( $config['revisions']['posts'] ) : 0;
		return $config;
	}

	/**
	 * Gets the revision config for a post.
	 *

	 * @param int $post_id
	 * @return array
	 */
	static public function get_config( $post_id ) {
		$revisions    = wp_get_post_revisions( $post_id, array(
			'numberposts' => apply_filters( 'ba_cheetah_revisions_number', 25 ),
		) );
		$current_time = time();
		$config       = array(
			'posts'   => array(),
			'authors' => array(),
		);

		$current_data = serialize( get_post_meta( $post_id, '_ba_cheetah_data', true ) );

		if ( count( $revisions ) > 1 ) {

			foreach ( $revisions as $revision ) {

				$revision_data = serialize( get_post_meta( $revision->ID, '_ba_cheetah_data', true ) );

				if ( ! current_user_can( 'read_post', $revision->ID ) ) {
					continue;
				}
				if ( wp_is_post_autosave( $revision ) ) {
					continue;
				}

				if ( $revision_data == $current_data ) {
					continue;
				}

				$timestamp = strtotime( $revision->post_date );

				$config['posts'][] = array(
					'id'     => $revision->ID,
					'author' => $revision->post_author,
					'date'   => array(
						'published' => gmdate( 'F j', $timestamp ),
						'diff'      => human_time_diff( $timestamp, $current_time ),
					),
				);

				if ( ! isset( $config['authors'][ $revision->post_author ] ) ) {
					$config['authors'][ $revision->post_author ] = array(
						'name'   => get_the_author_meta( 'display_name', $revision->post_author ),
						'avatar' => sprintf( '<img height="30" width="30" class="avatar avatar-30 photo" src="%s" />', esc_url( get_avatar_url( $revision->post_author, 30 ) ) ),
					);
				}
			}
		}

		return $config;
	}

	/**
	 * Adds revision data to the main menu config.
	 *

	 * @param array $config
	 * @return array
	 */
	static public function main_menu_config( $config ) {
		$config['main']['items'][35] = array(
			'label' => __( 'Revisions', 'ba-cheetah' ),
			'icon'	=> 'ba-cheetah-icon--revisions',
			'type'  => 'view',
			'view'  => 'revisions',
		);

		$config['revisions'] = array(
			'name'       => __( 'Revisions', 'ba-cheetah' ),
			'isShowing'  => false,
			'isRootView' => false,
			'items'      => array(),
		);

		return $config;
	}

	/**
	 * Renders the layout for a revision preview in the builder.
	 *

	 * @param int $revision_id
	 * @return array
	 */
	static public function render_preview( $revision_id ) {
		BACheetahModel::set_post_id( $revision_id );

		return BACheetahAJAXLayout::render();
	}

	/**
	 * Restores the current layout to a revision with the specified ID.
	 *

	 * @param int $revision_id
	 * @return array
	 */
	static public function restore( $revision_id ) {
		$data = BACheetahModel::get_layout_data( 'published', $revision_id );

		BACheetahModel::update_layout_data( $data );
		$settings = get_post_meta( $revision_id, '_ba_cheetah_data_settings', true );
		update_post_meta( BACheetahModel::get_post_id(), '_ba_cheetah_draft_settings', $settings );
		return array(
			'layout'   => BACheetahAJAXLayout::render(),
			'config'   => BACheetahUISettingsForms::get_node_js_config(),
			'settings' => $settings,
		);
	}
}

BACheetahRevisions::init();
