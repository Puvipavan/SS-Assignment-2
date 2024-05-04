<?php
$node_id = "ba-node-$id";
?>

<div class="ba-module__progress <?php echo $node_id; ?>">
	<?php 
		if ($settings->show_counter == 'yes' && $settings->counter_position != 'bar')
		$module->counter_template() 
	?>

	<div class="progress__bar__outer">
		<div class="progress__bar__inner">
			<?php if ($settings->inner_text): ?>
				<span class="progress__bar__text"><?php echo esc_html($settings->inner_text) ?></span>
			<?php endif; ?>
			
			<?php 
				if ($settings->show_counter == 'yes' && $settings->counter_position == 'bar')
				$module->counter_template() 
			?>
		</div>
	</div>
</div>