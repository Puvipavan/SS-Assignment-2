<?php
$node_id = isset($settings->id) && !empty($settings->id) ? $settings->id : "ba-node-$id";
$effect = isset($settings->effect) && !empty($settings->effect) ? $settings->effect : "flip";
$logoP = isset($settings->position_logo) && !empty($settings->position_logo) ? $settings->position_logo : "center";

if ($settings->image == BACheetahFlipBoxModule::DEFAULT_PHOTO) {
    $settings->image_src = BACheetahFlipBoxModule::DEFAULT_PHOTO;
}
if ($settings->image_internal == BACheetahFlipBoxModule::DEFAULT_PHOTO_INTERNAL) {
    $settings->image_internal_src = BACheetahFlipBoxModule::DEFAULT_PHOTO_INTERNAL;
}
?>

<?php if ($settings->reverse === '0'): ?>

    <div class="<?php echo "ba-module__flip-box $node_id $effect" ?> ">

        <div class="flip-box__content" <?php if (!empty($settings->image)): echo 'style="background-image: url(' . esc_url($settings->image_src) . ')"'; endif; ?>>

            <?php if (!empty($settings->use_logo) && $settings->use_logo === 'enabled'): ?>
                <div class="img-logo-content">
                    <div class="img-logo <?php echo $logoP ?>" <?php echo 'style="background-image: url(' . esc_url($settings->logo_src) . '); height:' . $settings->logo_height . 'px; width:' . $settings->logo_width . 'px;"'; ?>></div>
                </div>
            <?php endif; ?>

            <h3 class="flip-box__title">
                <?php echo esc_html($settings->title) ?>
            </h3>

            <?php if (!empty($settings->subtitle)): ?>
                <p class="flip-box__subtitle">
                    <?php echo wp_kses_post($settings->subtitle) ?>
                </p>
            <?php endif ?>
        </div>


        <div class="flip-box__content internal" <?php if (!empty($settings->image_internal)): echo 'style="background-image: url(' . esc_url($settings->image_internal_src) . ')"'; endif; ?>>

            <h3 class="flip-box__title_internal">
                <?php echo esc_html($settings->title_internal) ?>
            </h3>

            <?php if (!empty($settings->subtitle)): ?>
                <p class="flip-box__subtitle_internal">
                    <?php echo wp_kses_post($settings->subtitle_internal) ?>
                </p>
            <?php endif ?>

            <?php
            if (!empty($settings->btn_text) || !empty($settings->btn_icon)) {
                BACheetah::render_module_html('button', $module->get_button_settings());
            }
            ?>
        </div>
    </div>
<?php else: ?>

    <div class="<?php echo "ba-module__flip-box reverse__flip-box $node_id $effect" ?> ">

        <div class="flip-box__content" <?php if (!empty($settings->image_internal)): echo 'style="background-image: url(' . esc_url($settings->image_internal_src) . ')"'; endif; ?>>
            <h3 class="flip-box__title">
                <?php echo esc_html($settings->title_internal) ?>
            </h3>

            <?php if (!empty($settings->subtitle)): ?>
                <p class="flip-box__subtitle">
                    <?php echo wp_kses_post($settings->subtitle_internal) ?>
                </p>
            <?php endif ?>

            <?php
            if (!empty($settings->btn_text) || !empty($settings->btn_icon)) {
                BACheetah::render_module_html('button', $module->get_button_settings());
            }
            ?>

        </div>

        <div class="flip-box__content internal" <?php if (!empty($settings->image)): echo 'style="background-image: url(' . esc_url($settings->image_src) . ')"'; endif; ?>>

            <?php if (!empty($settings->use_logo) && $settings->use_logo === 'enabled'): ?>
                <div class="img-logo-content">
                    <div class="img-logo <?php echo $logoP ?>" <?php echo 'style="background-image: url(' . esc_url($settings->logo_src) . '); height:' . $settings->logo_height . 'px; width:' . $settings->logo_width . 'px;"'; ?>></div>
                </div>
            <?php endif; ?>

            <h3 class="flip-box__title_internal">
                <?php echo esc_html($settings->title) ?>
            </h3>

            <?php if (!empty($settings->subtitle)): ?>
                <p class="flip-box__subtitle_internal">
                    <?php echo wp_kses_post($settings->subtitle) ?>
                </p>
            <?php endif ?>
        </div>

    </div>

<?php endif; ?>
