<?php
$node_id = "ba-node-$id";
?>

<div class="ba-module__pricing-table <?php echo $node_id; ?>">

	<div class="pricing-table__box">

		<?php if (isset($settings->ribbon_display) && $settings->ribbon_display == 'block'):?> 
		<div
			class="pricing-table__ribbon"
			style="<?php echo esc_attr(sprintf('--ribbon-depth:%s%s', $settings->ribbon_depth, $settings->ribbon_depth_unit)) ?>"
			data-position="<?php echo esc_attr($settings->ribbon_position) ?>"
			data-text="<?php echo esc_attr($settings->ribbon_text) ?>">
		</div>
		<?php endif; ?>

		<header class="pricing-table__header">
			<h3 class="pricing-table__title"><?php echo wp_kses_post($settings->title) ?></h3>
			<p class="pricing-table__subtitle"><?php echo wp_kses_post($settings->subtitle) ?></p>
		</header>

		<div class="pricing-table__price">
			<span class="pricing-table__currency"><?php echo esc_html($settings->currency) ?></span>
			<span class="pricing-table__value"><?php echo esc_html($settings->value) ?></span>
			<span class="pricing-table__period"><?php echo esc_html($settings->period) ?></span>
		</div>

		<?php 
		$pricing_table_image_settings = $module->get_image_settings();
		if (!empty($pricing_table_image_settings['photo_src'])): ?>
		<div class="pricing-table__image">
			<?php BACheetah::render_module_html('photo', $pricing_table_image_settings);?>
		</div>
		<?php endif; ?>

		<?php if (isset($settings->features) && $settings->show_features == 'yes') : ?>
			<ul class="pricing-table__features">
				<?php
				foreach ($settings->features as $key => $feature) :
					if (!$feature->description) continue;
				?>
					<li class="pricing-table__feature-item">
						<?php if ($feature->icon) : ?>
							<i 
								class="<?php echo esc_attr($feature->icon) ?>" 
								style="color: <?php echo BACheetahColor::hex_or_rgb($feature->icon_color) ?>">
							</i>
						<?php endif; ?>
						<span>
							<?php echo BACheetahListModule::ksesListItem($feature->description); ?>
						</span>
					</li>
					<li class="pricing-table__feature-item pricing-table__feature-item--divider">
						<hr>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if (!empty($settings->btn_text) || !empty($settings->btn_subtext)): ?>
			<div class="pricing-table__cta">
				<?php BACheetah::render_module_html('button', $module->get_button_settings());?>
			</div>
		<?php endif; ?>
	</div>
</div>