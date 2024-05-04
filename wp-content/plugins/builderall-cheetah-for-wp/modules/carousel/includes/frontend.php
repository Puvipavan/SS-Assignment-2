<?php
$carousel_node_id = "ba-cheetah-carousel-$id";
$photos = $module->get_photos();

?>

<?php if (count($photos) === 0) : ?>
    <div class="ba-module__gallery-no-media">
        <span><?php echo __('Choose your images to continue', 'ba-cheetah') ?></span>
    </div>
<?php else : ?>

    <div class="<?php echo "ba-module__carousel $carousel_node_id" ?> ">
        <?php $module->render_carousel($id) ?>
    </div>
<?php endif; ?>

