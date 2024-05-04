<?php

$node_id = "ba-node-$id";
$radius = (400 - $settings->stroke_width) / 2;
$gradient_id = 'ba-cheetah-progress-circle-gradient-'.$node_id;

?>

<div class="ba-module__progress-circle <?php echo $node_id; ?>">

	<svg 
		class="progress-circle__svg"
		width="400" height="400"
		viewBox="<?php echo esc_attr($module->get_view_box());?>"
		style="--radius: <?php echo esc_attr($radius) ?>; --progress-percent:0;"
	>

		<linearGradient id="<?php echo esc_attr($gradient_id)?>">
			<stop offset="0%" stop-color="<?php echo BACheetahColor::hex_or_rgb($settings->progress_bg_gradient_color_1)?>" />
			<stop offset="100%" stop-color="<?php echo BACheetahColor::hex_or_rgb($settings->progress_bg_gradient_color_2)?>" />
		</linearGradient>

		<circle
			style="--progress-percent: 100;"
			class="progress-circle__circle"
			stroke-dasharray="0"
			stroke="<?php echo BACheetahColor::hex_or_rgb($settings->circle_stroke)?>"
			stroke-width="<?php echo esc_attr($settings->stroke_width) ?>"
			fill="<?php echo BACheetahColor::hex_or_rgb($settings->circle_bg)?>"
			r="<?php echo esc_attr($radius) ?>"
			cx="50%"
			cy="50%"
		/>

      	<circle
			class="progress-circle__progress"
			stroke="<?php echo esc_attr($settings->progress_bg_type == 'solid' ? BACheetahColor::hex_or_rgb($settings->progress_bg_color) : 'url(#'.$gradient_id.')') ?>"
			stroke-width="<?php echo esc_attr($settings->stroke_width) ?>"
			stroke-linecap="<?php echo esc_attr($settings->stroke_linecap) ?>"
			fill="none"
			r="<?php echo esc_attr($radius) ?>"
			cx="50%"
			cy="50%"
		/>

		<?php if ($settings->number_enabled == 'yes'): ?>
			<text
				x="50%"
				y="50%"
				text-anchor="middle"
				fill="<?php echo BACheetahColor::hex_or_rgb($settings->number_color)?>"
				font-family="<?php echo esc_attr($settings->number_font['family']) ?>"
				font-size="<?php echo esc_attr($settings->number_size) ?>em"
				font-weight="<?php echo esc_attr($settings->number_font['weight']) ?>"
				dy=".3em">0
			</text>
		<?php endif; ?>
    </svg>
</div>
