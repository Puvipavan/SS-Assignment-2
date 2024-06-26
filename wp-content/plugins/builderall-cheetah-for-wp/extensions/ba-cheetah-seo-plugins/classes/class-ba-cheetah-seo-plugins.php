<?php

/**
 * Add support for SEO plugins

 */
class BACheetahSeoPlugins {

	function __construct() {

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_head', array( $this, 'remove_yoast_meta_box_on_edit' ), 999 );

		add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'sitemap_exclude_post_type' ), 10, 2 );
		add_filter( 'wpseo_sitemap_exclude_taxonomy', array( $this, 'sitemap_exclude_taxonomy' ), 10, 2 );
		add_filter( 'manage_edit-ba-cheetah-template_columns', array( $this, 'remove_columns' ) );

		add_filter( 'the_seo_framework_post_type_disabled', array( $this, 'sf_type' ), 10, 2 );
		add_filter( 'the_seo_framework_sitemap_exclude_cpt', array( $this, 'sf_sitemap' ) );

		add_filter( 'rank_math/sitemap/excluded_post_types', array( $this, 'rankmath_types' ) );
	}

	function init() {
		global $pagenow;
		if ( BACheetahAJAX::doing_ajax() || 'post.php' !== $pagenow ) {
			return;
		}

		if ( defined( 'WPSEO_VERSION' ) ) {
			$this->enqueue_script( 'yoast' );
		} elseif ( class_exists( 'RankMath' ) ) {
			$this->enqueue_script( 'rankmath' );
		}
	}

	function rankmath_types( $post_types ) {
		unset( $post_types['ba-cheetah-template'] );
		return $post_types;
	}

	function remove_columns( $columns ) {

		// remove the Yoast SEO columns
		unset( $columns['wpseo-score'] );
		unset( $columns['wpseo-title'] );
		unset( $columns['wpseo-links'] );
		unset( $columns['wpseo-metadesc'] );
		unset( $columns['wpseo-focuskw'] );
		unset( $columns['wpseo-score-readability'] );
		// RankMath columns
		unset( $columns['rank_math_seo_details'] );
		unset( $columns['rank_math_title'] );
		unset( $columns['rank_math_description'] );
		return $columns;
	}

	function remove_yoast_meta_box_on_edit() {
		if ( function_exists( 'remove_meta_box' ) ) {
			remove_meta_box( 'wpseo_meta', 'ba-cheetah-template', 'normal' );
		}
	}

	function sitemap_exclude_post_type( $value, $post_type ) {
		if ( 'ba-cheetah-template' === $post_type ) {
			return true;
		}
		return $value;
	}

	function sitemap_exclude_taxonomy( $value, $taxonomy ) {
		if ( 'ba-cheetah-template-category' === $taxonomy ) {
			return true;
		}
		return $value;
	}

	function enqueue_script( $plugin ) {

		global $post;
		$orig = $post;

		if ( ! isset( $_GET['post'] ) ) {
			return false;
		}

		$post_id = absint( $_GET['post'] );

		$post_type = get_post_type( $post_id );

		if ( in_array( $post_type, array( 'ba-cheetah-theme-layout', 'ba-cheetah-template' ) ) ) {
				return false;
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'dequeue_layout_scripts' ), 10000 );

		if ( 'yoast' === $plugin ) {
			$deps = array();
		} else {
			$deps = array( 'wp-hooks', 'rank-math-analyzer' );
		}

		$data = $this->content_data();
		$post = $orig;

		if ( $data ) {
			wp_enqueue_script( 'bb-seo-scripts', BA_CHEETAH_SEO_PLUGINS_URL . "js/plugin-$plugin.js", $deps, false, true );
			wp_localize_script( 'bb-seo-scripts', 'bb_seo_data', array( 'content' => $data ) );
		}
	}

	function dequeue_layout_scripts() {
		global $wp_scripts;
		foreach ( $wp_scripts->queue as $item ) {
			if ( false !== strpos( $item, 'ba-cheetah-layout' ) ) {
				wp_dequeue_script( $item );
			}
		}
	}

	function content_data() {

		if ( ! isset( $_GET['post'] ) ) {
			return false;
		}

		$id = absint( $_GET['post'] );

		if ( ! get_post_meta( $id, '_ba_cheetah_enabled', true ) ) {
			return false;
		}
		ob_start();
		echo do_shortcode( "[ba_cheetah_insert_layout id=$id]" );
		$data = ob_get_clean();
		BACheetahModel::delete_all_asset_cache( $id );
		return str_replace( PHP_EOL, '', $data );
	}

	public function sf_type( $value, $post_type ) {
		if ( 'ba-cheetah-template' === $post_type ) {
			return true;
		}
		return $value;
	}

	public function sf_sitemap( $types ) {
		$types[] = 'ba-cheetah-template';
		return $types;
	}

}

new BACheetahSeoPlugins();
