<?php

$source = $module->get_source();

?>
    <div class="ba-module__slideshow">
        <?php if ( empty($source) ) : ?>
            <div class="ba-module__slideshow-no-media"><span><?php echo __('Choose your images to continue', 'ba-cheetah') ?></span></div>
        <?php endif; ?>
    </div>

