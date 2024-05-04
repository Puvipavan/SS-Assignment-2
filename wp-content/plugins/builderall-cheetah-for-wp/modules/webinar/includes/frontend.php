<?php
$node_id = "ba-node-$id";
?>

<div>
	<!-- Builderall webinar Iframe -->
	<?php if(isset($settings->webinar_id_secret) && $settings->webinar_id_secret) :?>
		<div class="ba-module__webinar <?php echo $node_id?>">
			<iframe src="<?php echo esc_url($module->getEmbedLink()); ?>" frameborder="0" width="100%" height="100%"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	<?php else: ?>
		<p><?php echo __('Select a webinar', 'ba-cheetah'); ?></p>
	<?php endif;?>
</div>
