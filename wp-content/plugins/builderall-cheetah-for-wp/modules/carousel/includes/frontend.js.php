<?php
$settings->lengthPhotos = count($module->get_photos());

unset($settings->photos);
unset($settings->urls);
unset($settings->photo_data);


?>
<?php if ($settings->lengthPhotos !== 0) : ?>
<?php if ($settings->version === 2) : ?>
new BACheetahCarouselNew(
  '<?php echo $id ?>','<?php echo json_encode($settings); ?>'
);
<?php else: ?>
new BACheetahCarousel(
  '<?php echo $id ?>','<?php echo json_encode($settings); ?>'
);
<?php endif; ?>
<?php endif; ?>
