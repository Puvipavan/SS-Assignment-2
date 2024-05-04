<?php
$gallery_node_id = "ba-node-$id";
$photos          = $module->get_photos();
?>

<?php if (count($photos) === 0) : ?>
<div class="ba-module__gallery-no-media">
    <span><?php echo __('Choose your images to continue', 'ba-cheetah') ?></span>
</div>
<?php else : ?>
    <div class="ba-module__gallery <?php echo $gallery_node_id; ?> ba-module__gallery-<?php echo esc_attr($settings->layout); ?>">

        <?php foreach ($photos as $photo) : ?>
            <div class="gallery__item">
                <?php

                $url = 'none' === $settings->click_action ? '' : $photo->link;

                if ('lightbox' === $settings->click_action && isset($settings->lightbox_image_size)) {
                    if ('' !== $settings->lightbox_image_size) {
                        $size = $settings->lightbox_image_size;
                        $data = BACheetahPhoto::get_attachment_data($photo->id);

                        if (isset($data->sizes->{$size})) {
                            $url = $data->sizes->{$size}->url;
                        }
                    }
                }

                $photo->url = $url;

                BACheetah::render_module_html('photo', array(
                    'crop' => false,
                    'link_target' => '_self',
                    'link_type' => 'none' == $settings->click_action ? '' : $settings->click_action,
                    'link_url' => $url,
                    'photo' => $photo,
                    'photo_src' => $photo->src,
                    'show_caption' => $settings->show_captions,
                ));

                ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
