<?php
$tab_node_id = "ba-node-$id";
?>

<div class="<?php echo "ba-module__line $tab_node_id " ?> ">
	<div class="line__wrapper">
		<span class="line__border"></span>

		<?php 
		if ($module->has_content()): 
		?>
		
		<span class="line__content">
			<?php if ($settings->icon): ?>
				<i class="<?php echo esc_attr($settings->icon) ?>"></i>
			<?php endif; ?>
			<?php if ($settings->content_text): ?>
				<?php echo esc_html($settings->content_text) ?>
			<?php endif; ?>
		</span>

		<?php 
			if ($settings->content_justify == 'initial') {
				echo '<span class="line__border"></span>';
			}
		endif; 
		?>
	</div>
</div>