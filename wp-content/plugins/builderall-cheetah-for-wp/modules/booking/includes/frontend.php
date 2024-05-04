<?php
$node_id = "ba-node-$id";
?>

<div>
	<!-- Builderall Booking Iframe -->
	<?php if(isset($settings->calendar) && $settings->calendar) :?>
		<div class="ba-module__booking <?php echo $node_id?>">
			<iframe src="<?php echo esc_url($module->getEmbedLink()); ?>" frameborder="0" width="100%" height="100%"></iframe>
		</div>
	<?php else: ?>
		<p><?php echo __('Select a calendar', 'ba-cheetah'); ?></p>
	<?php endif;?>
</div>