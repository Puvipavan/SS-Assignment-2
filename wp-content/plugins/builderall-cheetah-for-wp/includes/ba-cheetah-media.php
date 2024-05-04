<?php

function get_user_images() {

    $response = BaCheetahAuthHttp::get(BA_CHEETAH_DASHBOARD_URL . 'api/file/user-images/');

    if(wp_remote_retrieve_response_code($response) === 200) {
        wp_send_json(json_decode(wp_remote_retrieve_body($response), true));
    } else {
        wp_send_json([]);
    }
}

// Authorization route
add_action('rest_api_init', function () {
    register_rest_route('ba-cheetah/v1', '/user-images', array(
        'methods' => 'GET',
        'callback' => 'get_user_images',
        'permission_callback' => function () {
            return true;
        }
    ));
});


add_action( 'rest_api_init', function () {
    register_rest_route(
        'ba-cheetah/v1',
        '/user-images/download',
        array(
            'methods'             => 'POST',
            'callback'            => 'builderall_images_download',
            'permission_callback' => function () {
                return true;
            },
        )
    );
});

function builderall_images_remote_file_exists( $url ) {
    if( strpos($url, 'https://storage.builderall.com') !== 0 ) {
        return false;
    }

    $response = wp_remote_head( $url );
    return 200 === wp_remote_retrieve_response_code( $response );
}

function builderall_images_download( WP_REST_Request $request ) {

    // Core WP includes.
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Get JSON Data.
    $data = json_decode( $request->get_body(), true ); // Get contents of request body.

    if ( $data ) {

        $id        = $data['id']; // Image ID.
        $image_url = $data['image_url']; // Image URL.
        $filename  = sanitize_text_field( $data['filename'] ); // The filename.
        $title     = sanitize_text_field( $data['title'] ); // Title.
        $alt       = sanitize_text_field( $data['alt'] ); // Alt text.
        $caption   = sanitize_text_field( $data['caption'] ); // Caption text.
        $cfilename = sanitize_title( $data['custom_filename'] ); // Custom filename.
        $parent_id = ( $data['parent_id'] ) ? sanitize_title( $data['parent_id'] ) : 0; // Parent post ID.
        $name      = ( ! empty( $cfilename ) ) ? $cfilename . '.jpg' : $filename; // Actual filename.

        // Check if remote file exists.
        if ( ! builderall_images_remote_file_exists( $image_url ) ) {
            // Errorhandling.
            $response = array(
                'success'    => false,
                'msg'        => __( 'Image does not exist or there was an error accessing the remote file.', 'ba-cheetah' ),
                'id'         => $id,
                'attachment' => '',
                'admin_url'  => admin_url(),
            );
            wp_send_json( $response );
        }

        // Send request to `wp_remote_get`.
        $response = wp_remote_get( $image_url );
        if ( is_wp_error( $response ) ) {
            return new WP_Error( 100, __( 'Image download failed, please try again. Errors:', 'ba-cheetah' ) . PHP_EOL . $response->get_error_message() );
        }

        // Get Headers.
        $type = wp_remote_retrieve_header( $response, 'content-type' );
        if ( ! $type ) {
            return new WP_Error( 100, __( 'Image type could not be determined', 'ba-cheetah' ) );
        }

        // Upload remote file.
        $mirror = wp_upload_bits( $name, null, wp_remote_retrieve_body( $response ) );

        // Build Attachment Data Array.
        $attachment = array(
            'post_title'     => $title,
            'post_excerpt'   => $caption,
            'post_content'   => '',
            'post_status'    => 'inherit',
            'post_mime_type' => $type,
        );

        // Insert as attachment.
        $image_id = wp_insert_attachment( $attachment, $mirror['file'], $parent_id );

        // Add Alt Text as Post Meta.
        update_post_meta( $image_id, '_wp_attachment_image_alt', $alt );

        // Generate Metadata.
        $attach_data = wp_generate_attachment_metadata( $image_id, $mirror['file'] );
        wp_update_attachment_metadata( $image_id, $attach_data );

        // Success.
        $response = array(
            'success'    => true,
            'msg'        => __( 'Image successfully uploaded to the media library!', 'ba-cheetah' ),
            'id'         => $id,
            'admin_url'  => admin_url(),
            'attachment' => array(
                'id'      => $image_id,
                'url'     => wp_get_attachment_url( $image_id ),
                'alt'     => $alt,
                'caption' => $caption,
            ),
        );

        wp_send_json( $response );

    } else {

        $response = array(
            'success'    => false,
            'msg'        => __( 'There was an error getting image details from the request, please try again.', 'ba-cheetah' ),
            'id'         => '',
            'attachment' => '',
            'url'        => '',
        );

        wp_send_json( $response );
    }
}

