<?php

final class BACheetahMedia
{

    static public $directory_builderall = '/builderall-media';

    /**
     * Initializes hooks.
     *
     * Based on https://wordpress.org/plugins/instant-images/
     *
     * @return void
     * @since 1.8
     */
    static public function init()
    {
        // Activation
        register_activation_hook(BA_CHEETAH_FILE, __CLASS__ . '::activate');

        // Deactivation
        register_deactivation_hook(BA_CHEETAH_FILE, __CLASS__ . '::deactivate');

        // Enqueue media
        if(BA_CHEETAH_BUILDERALL) {
            add_action('wp_enqueue_media', array(__CLASS__, __CLASS__ . '::enqueue_media'));
        }

        self::includes();
    }

    static private function includes()
    {

        if (is_admin()) {
            require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-media-admin.php';
        }

        // REST API Routes.
        require_once BA_CHEETAH_DIR . '/includes/ba-cheetah-media.php';

    }

    static public function activate()
    {
        $upload_dir = wp_upload_dir();
        $dir        = $upload_dir['basedir'] . self::$directory_builderall;
        if (!is_dir($dir)) {
            wp_mkdir_p($dir);
        }
    }

    static public function deactivate()
    {
        $upload_dir = wp_upload_dir();
        $dir        = $upload_dir['basedir'] . self::$directory_builderall;

        if (is_dir($dir)) {
            foreach (glob($dir . '/*.*') as $filename) {
                if (is_file($filename)) {
                    unlink($filename);
                }
            }
            rmdir($dir);
        }
    }

    public static function builderall_media_localize($script = 'builderall-media-vue')
    {
        wp_localize_script(
            $script,
            'builderall_media_localize', array(
                'root' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest'),
                'ajax_url' => admin_url('admin-ajax.php'),
				'plugin_url' => BA_CHEETAH_URL,
                'admin_nonce' => wp_create_nonce('builderall_img_nonce'),

                'builderall_images' => __('Builderall Images', 'ba-cheetah'),

                'search_no_image_directory' => __('No image in this directory', 'ba-cheetah'),
                'search_no_image_directory_found' => __('No image with this search found', 'ba-cheetah'),

                'filter_by_directories' => __('Filter by directories', 'ba-cheetah'),
                'all_directories' => __('All directories', 'ba-cheetah'),
                'search' => __('Search', 'ba-cheetah'),
                'loading' => __('Loading', 'ba-cheetah'),
                'success' => __('Success', 'ba-cheetah'),
                'error' => __('Error', 'ba-cheetah'),
                'view_list' => __('View list', 'ba-cheetah'),
                'view_grid' => __('View grid', 'ba-cheetah'),
				'load_more' => __('Load more', 'ba-cheetah'),
				'showing' => __('Showing', 'ba-cheetah'),
				'total' => __('Total', 'ba-cheetah'),
            )
        );
    }

    static public function enqueue_media()
    {

        $file_name = 'builderall-media-router';

        $dir    = '/js/build/'; // Use minified libraries for WP_DEBUG.
        $suffix = (defined('WP_DEBUG') && WP_DEBUG) ? '.js' : '.min.js'; // Use minified libraries for SCRIPT_DEBUG.

        wp_enqueue_script('builderall-media-router', BA_CHEETAH_URL . $dir . $file_name . $suffix, '', BA_CHEETAH_VERSION, true);

        self::builderall_media_localize('builderall-media-router');
    }

}

BACheetahMedia::init();
