<?php
// render the popup css
if ($settings->click_action == 'popup' && isset($settings->popup_id) && (int) $settings->popup_id) {
	BACheetah::enqueue_layout_styles_scripts_by_id($settings->popup_id);
}

// Alignment
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'align',
	'selector'     => ".ba-cheetah-node-$id .ba-module__button",
	'prop'         => 'justify-content',
) );

// Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'padding',
	'selector'     => ".ba-cheetah-node-$id .button__button",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'padding_top',
		'padding-right'  => 'padding_right',
		'padding-bottom' => 'padding_bottom',
		'padding-left'   => 'padding_left',
	),
) );

// Typography
BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'typography',
	'selector'     => ".ba-cheetah-node-$id .button__button .button__text, .ba-cheetah-node-$id .button__button:visited .button__text",
));

// Typography Subtext
BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'sub_typography',
	'selector'     => ".ba-cheetah-node-$id .button__button .button__subtext, .ba-cheetah-node-$id .button__button:visited .button__subtext",
));

// Icon size - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_size',
	'selector'     => ".ba-cheetah-node-$id .ba-cheetah-module-content .button__button .button__icon",
	'prop'         => 'font-size',
));

// Border
BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-cheetah-node-$id .button__button, .ba-cheetah-node-$id .button__button:visited",
) );

// Default Border hover color
if ( ! empty( $settings->border_hover_color ) && is_array( $settings->border ) ) {
	$settings->border['color'] = $settings->border_hover_color;
}

// Border - Hover
BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-cheetah-node-$id .button__button:hover, .ba-cheetah-node-$id .button__button:focus",
) );

?>

.ba-cheetah-node-<?php echo $id; ?> > .button__button {
	<?php if ($settings->width == 'custom' ) : ?>
	width: <?php echo $settings->custom_width . $settings->custom_width_unit; ?>;
	<?php endif; ?>

	<?php if ($settings->width == 'full') : ?>
	flex: 1;
	-ms-flex: 1;
	-webkit-box-flex: 1;
	<?php endif; ?>

	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->bg_color_type, $settings->bg_color, $settings->bg_gradient)?> !important;

	<?php if (!empty( $settings->gap)) : ?>
	gap: <?php echo $settings->gap; ?>px;
	<?php endif; ?>
}

<?php if ( ! empty( $settings->text_color ) ) : ?>
.ba-cheetah-node-<?php echo $id; ?> > .button__button .button__text {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->sub_text_color ) ) : ?>
.ba-cheetah-node-<?php echo $id; ?> > .button__button .button__subtext {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->sub_text_color ); ?>;
}
<?php endif; ?>


.ba-cheetah-node-<?php echo $id; ?> > .button__button .button__icon {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->icon_color ); ?>;
}

<?php 
	$hover_bg = $settings->bg_hover_color ? $settings->bg_hover_color : $settings->bg_color; 
	$hover_bg_gradient = BACheetahColor::is_valid_gradient($settings->bg_hover_gradient) ? $settings->bg_hover_gradient : $settings->bg_gradient; 
?>

.ba-cheetah-node-<?php echo $id; ?> > .button__button:hover,
.ba-cheetah-node-<?php echo $id; ?> > .button__button:focus {
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->bg_hover_color_type, $hover_bg, $hover_bg_gradient)?> !important;
}


<?php if ( ! empty( $settings->text_hover_color ) ) : ?>
.ba-cheetah-node-<?php echo $id; ?> > .button__button:hover,
.ba-cheetah-node-<?php echo $id; ?> > .button__button:focus,
.ba-cheetah-node-<?php echo $id; ?> > .button__button:hover *,
.ba-cheetah-node-<?php echo $id; ?> > .button__button:focus * {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_hover_color ); ?>;
}
<?php endif; ?>


<?php
// Transition
if ( 'enable' == $settings->button_transition ) :?>
.ba-cheetah-node-<?php echo $id; ?> > .button__button,
.ba-cheetah-node-<?php echo $id; ?> > .button__button * {
	transition: all 0.2s linear !important;
	-moz-transition: all 0.2s linear !important;
	-webkit-transition: all 0.2s linear !important;
	-o-transition: all 0.2s linear !important;
}
<?php endif; ?>