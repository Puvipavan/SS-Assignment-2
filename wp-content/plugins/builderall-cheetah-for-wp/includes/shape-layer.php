<div class="ba-cheetah-layer ba-cheetah-shape-layer ba-cheetah-<?php echo $position; ?>-edge-layer ba-cheetah-shape-<?php echo esc_attr( $shape_name ); ?>">
	<svg class="<?php echo $svg_class; ?>" viewBox="<?php echo $view_box; ?>" preserveAspectRatio="<?php echo $preserve_aspect_ratio; ?>">

		<defs>
		<?php
		$is_in_builder = BACheetahModel::is_builder_active();
		if ( $is_in_builder || 'gradient' === $settings->{ $prefix . 'fill_style' } ) {

			$linear_gradient_id = "ba-cheetah-row-$id-$prefix-linear-gradient";
			$radial_gradient_id = "ba-cheetah-row-$id-$prefix-radial-gradient";
			$gradient_settings  = $settings->{ $prefix . 'fill_gradient' };
			$colors             = $gradient_settings['colors'];
			$stops              = $gradient_settings['stops'];

			// Radial Gradient
			$parts = explode( ' ', $gradient_settings['position'] );
			$cx    = BACheetahArt::get_int_for_position_name( $parts[0] );
			$cy    = BACheetahArt::get_int_for_position_name( $parts[1] );
			$r     = ( .5 === $cx && .5 === $cy ) ? .5 : 1;
			?>
			<linearGradient id="<?php echo $linear_gradient_id; ?>" gradientUnits="objectBoundingBox" gradientTransform="rotate(<?php echo $gradient_settings['angle']; ?> .5 .5)">
				<?php
				foreach ( $colors as $i => $color ) {
					$rgba = BACheetahColor::rgba_values_for_color( $color );
					?>
				<stop offset="<?php echo $stops[ $i ] . '%'; ?>" stop-color="<?php echo $rgba['rgb']; ?>" stop-opacity="<?php echo $rgba['a']; ?>" />
				<?php } ?>
			</linearGradient>
			<radialGradient  id="<?php echo $radial_gradient_id; ?>" cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="<?php echo $r; ?>">
				<?php
				foreach ( $colors as $i => $color ) {
					$rgba = BACheetahColor::rgba_values_for_color( $color );
					?>
				<stop offset="<?php echo $stops[ $i ] . '%'; ?>" stop-color="<?php echo $rgba['rgb']; ?>" stop-opacity="<?php echo $rgba['a']; ?>" />
				<?php } ?>
			</radialGradient>
		<?php } // End if ?>
		</defs>

		<g class="ba-cheetah-shape-content">
			<?php echo $content; ?>
		</g>
	</svg>
</div>
