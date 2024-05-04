<?php
$node_id = "ba-node-$id";
?>

<ul class="ba-module__pricing-list <?php echo $node_id; ?>" data-photo-position="<?= esc_attr($settings->photo_position) ?>">
	<?php foreach ($settings->items as $key => $item): ?>
		<li class="pricing-list__item">

			<?php
				$pricing_table_image_settings = $module->get_image_settings($item);
				if (!empty($pricing_table_image_settings['photo_src'])): ?>
				<div class="pricing-list__image">
					<?php BACheetah::render_module_html('photo', $pricing_table_image_settings);?>
				</div>
			<?php endif;?>

			<div class="pricing-list__text">
				<h2 class="pricing-list__heading">
					<span class="pricing-list__title">
						<?= !$item->link ? $item->title : sprintf('<a href="%s" target="%s">%s</a>', esc_url($item->link), esc_attr($item->link_target), $item->title); ?>
					</span>
					<hr>
					<span class="pricing-list__price">
						<?= $item->price ?>
					</span>
				</h2>
				<div class="pricing-list__description">
					<?php echo (wp_kses_post($item->description)) ?>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
</ul>

