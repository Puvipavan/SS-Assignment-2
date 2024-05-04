<?php
$node_id = "ba-node-$id";
?>

<div>
	<!-- Builderall video hosting Iframe -->
	<?php if(isset($settings->video_hosting) && $settings->video_hosting) :?>
		<div class="ba-module__video-hosting <?php echo $node_id?>">
			<iframe src="<?php echo esc_url($module->getEmbedLink()); ?>" frameborder="0" width="100%" height="100%"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	<?php else: ?>
		<p><?php echo __('Select a video hosting', 'ba-cheetah'); ?></p>
	<?php endif;?>
</div>
