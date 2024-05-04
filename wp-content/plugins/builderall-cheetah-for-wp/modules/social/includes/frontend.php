<?php
$social_node_id = "ba-node-$id";
?>

<div class="ba-module__social <?php echo $social_node_id; ?>">
	<ul>
		<?php foreach ($settings->items as $item) : 
			if (!$item->icon) continue;
		?>
			<li class="social__item">
				<a 
					aria-label="<?php echo esc_attr($item->name) ?>"
					title="<?php echo esc_attr($item->name) ?>"
					href="<?php echo esc_url($item->link) ?>"
					target="_blank"
					rel="noopener noreferrer">
					<i class="<?php echo esc_attr($item->icon) ?>"></i>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>