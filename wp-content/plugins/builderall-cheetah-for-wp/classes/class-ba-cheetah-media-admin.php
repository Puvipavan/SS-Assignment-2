<?php

function builderall_img_create_page()
{
    $title = __('Builderall Images', 'ba-cheetah');
    $name  = 'builderall-images';

    $usplash_settings_page = add_submenu_page(
        'upload.php',
        $title,
        $title,
        apply_filters('builderall_images_user_role', 'upload_files'),
        $name,
        'builderall_img_settings_page'
    );
    add_action('load-' . $usplash_settings_page, 'builderall_img_load_scripts'); // Add admin scripts.
}

add_action('admin_menu', 'builderall_img_create_page');

function builderall_img_load_scripts()
{
    add_action('admin_enqueue_scripts', 'builderall_img_enqueue_scripts');
}

function builderall_img_enqueue_scripts()
{
    builderall_img_scripts();
}

function builderall_img_settings_page()
{
    echo '<div class="ba-cheetah-media-container" id="ba-cheetah-media-container" data-media-popup="false">';
    echo '</div>';
}

function builderall_img_scripts()
{
    $file_name = 'builderall-media';

    $dir    = '/js/build/'; // Use minified libraries for WP_DEBUG.
    $suffix = (defined('WP_DEBUG') && WP_DEBUG) ? '.js' : '.min.js'; // Use minified libraries for SCRIPT_DEBUG.

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form', true);

    wp_enqueue_script('builderall-media-vue', BA_CHEETAH_URL . $dir . $file_name . $suffix, '', BA_CHEETAH_VERSION, true);

    BACheetahMedia::builderall_media_localize();
}

function builderall_img_media_upload_tabs_handler($tabs)
{
    $newtab = array('builderall_img_tab' => __('Builderall Images', 'ba-cheetah'));
    $tabs   = array_merge($tabs, $newtab);
    return $tabs;
}

add_filter('media_upload_tabs', 'builderall_img_media_upload_tabs_handler');


function builderall_img_media_buttons()
{
    echo '<a href="' . add_query_arg('tab', 'builderall_img_tab', esc_url(get_upload_iframe_src())) . '" class="thickbox button" title="' . esc_attr__('Builderall Images', 'ba-cheetah') . '">&nbsp;' . __('Builderall Images', 'builderall-images') . '&nbsp;</a>';
}

add_filter('media_buttons', 'builderall_img_media_buttons');

function media_upload_builderall_images_handler()
{
    wp_iframe('media_builderall_img_tab');
}

add_action('media_upload_builderall_img_tab', 'media_upload_builderall_images_handler');

function media_builderall_img_tab()
{
    builderall_img_scripts();
    ?>
    <div class="builderall-img-container" data-media-popup="true"></div><?php
}
