<?php
$node_id = isset( $settings->id ) && ! empty( $settings->id ) ? $settings->id : "ba-node-$id";

if ($settings->image == BACheetahCardModule::DEFAULT_PHOTO) {
	$settings->image_src = BACheetahCardModule::DEFAULT_PHOTO;
}
?>

<div class="<?php echo "ba-module__card $node_id " ?> ">
	<?php if (!empty($settings->image)): ?>
		<div class="card__image">
			<?php if ($settings->btn_click_action == 'link' && isset($settings->btn_link)): ?>
				<a href="<?php echo esc_url($settings->btn_link)?>" target="<?php echo esc_attr($settings->btn_link_target)?>" >
				<figure style="background-image: url(<?php echo esc_url($settings->image_src)?>)"></figure>
				</a>
			<?php else: ?>
				<figure style="background-image: url(<?php echo esc_url($settings->image_src)?>)"></figure>
			<?php endif;?>
		</div>
	<?php endif ?>

	<div class="card__content">
		<h3 class="card__title">
			<?php echo esc_html($settings->title) ?>
		</h3>

		<?php if (!empty($settings->subtitle)): ?>
		<p class="card__subtitle">
			<?php echo wp_kses_post($settings->subtitle) ?>
		</p>
		<?php endif ?>

		<div class="card__text">
			<?php echo wp_kses_post($settings->text) ?>
		</div>

		<?php 
		if (!empty($settings->btn_text) || !empty($settings->btn_icon)) {
			BACheetah::render_module_html('button', $module->get_button_settings());
		}
		?>
	</div>
</div>