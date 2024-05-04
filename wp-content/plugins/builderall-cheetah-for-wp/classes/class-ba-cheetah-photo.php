<?php

/**
 * Helper class for working with photos.
 *

 */
final class BACheetahPhoto {

	/**
	 * Returns an array of data for sizes that are
	 * defined for WordPress images.
	 *

	 * @return array
	 */
	static public function sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $size ) {

			// Hidden size added in 4.4 for responsive images. We don't need it.
			if ( 'medium_large' == $size ) {
				continue;
			}

			$sizes[ $size ] = array( 0, 0 );

			if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $size ][0] = get_option( $size . '_size_w' );
				$sizes[ $size ][1] = get_option( $size . '_size_h' );
			} elseif ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$sizes[ $size ] = array(
					$_wp_additional_image_sizes[ $size ]['width'],
					$_wp_additional_image_sizes[ $size ]['height'],
				);
			}
		}

		return $sizes;
	}

	/**
	 * Returns an object with data for an attachment using
	 * wp_prepare_attachment_for_js based on the provided id.
	 *

	 * @param string $id The attachment id.
	 * @return object
	 */
	static public function get_attachment_data( $id ) {
		$data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
			return json_decode( json_encode( $data ) );
		}

		return $data;
	}

	/**
	 * Renders the thumb URL for a photo object.
	 *

	 * @param object $photo An object with photo data.
	 * @return void
	 */
	static public function get_thumb( $photo ) {
		if ( empty( $photo ) ) {
			$url = BA_CHEETAH_URL . 'img/spacer.png';
		} elseif ( ! isset( $photo->sizes ) ) {
			$url = $photo->url;
		} elseif ( ! empty( $photo->sizes->thumbnail ) ) {
			$url = $photo->sizes->thumbnail->url;
		} else {
			$url = $photo->sizes->full->url;
		}

		echo esc_url($url);
	}

	/**
	 * Renders the options for a photo select field.
	 *

	 * @param string $selected The selected URL.
	 * @param object $photo An object with photo data.
	 * @return void
	 */
	static public function get_src_options( $selected, $photo ) {
		if ( ! isset( $photo->sizes ) ) {
			echo '<option value="' . esc_attr($photo->url) . '" selected="selected">' . _x( 'Full Size', 'Image size.', 'ba-cheetah' ) . '</option>';
		} else {

			$titles = array(
				'full'      => _x( 'Full Size', 'Image size.', 'ba-cheetah' ),
				'large'     => _x( 'Large', 'Image size.', 'ba-cheetah' ),
				'medium'    => _x( 'Medium', 'Image size.', 'ba-cheetah' ),
				'thumbnail' => _x( 'Thumbnail', 'Image size.', 'ba-cheetah' ),
			);

			foreach ( $photo->sizes as $key => $val ) {

				if ( ! isset( $titles[ $key ] ) ) {
					$titles[ $key ] = ucwords( str_replace( array( '_', '-' ), ' ', $key ) );
				}

				echo '<option value="' . esc_attr($val->url) . '" ' . selected( basename( $selected ), basename( $val->url ) ) . '>' . wp_kses_post($titles[ $key ] . ' - ' . $val->width . ' x ' . $val->height) . '</option>';
			}
		}
	}
}
