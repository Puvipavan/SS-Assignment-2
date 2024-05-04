<?php

/* LAYOUT */

// form grid
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .mailingboss__form",
	'enabled' => !empty($settings->columns),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns . ', 1fr)',
	),
));

BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .mailingboss__form",
	'media' => 'medium',
	'enabled' => !empty($settings->columns_medium),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_medium . ', 1fr)',
	),
));

BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .mailingboss__form",
	'media' => 'responsive',
	'enabled' => !empty($settings->columns_responsive),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_responsive . ', 1fr)',
	),
));

// form grid gap
BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'gap',
	'selector'     => ".ba-node-$id .mailingboss__form",
	'prop'         => 'gap',
	'unit'		   => 'px'
));

/** BOX */
?>

.ba-node-<?php echo $id; ?> .mailingboss__form{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->box_background, 'initial'); ?>;
}

<?php

// Box padding
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'box_padding',
	'selector'     => ".ba-node-$id .mailingboss__form",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'box_padding_top',
		'padding-right'  => 'box_padding_right',
		'padding-bottom' => 'box_padding_bottom',
		'padding-left'   => 'box_padding_left',
	),
));

// Box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'box_border',
	'selector'  => ".ba-node-$id .mailingboss__form",
));

?>

<?php
/** LABELS */
?>

<?php

// Labels tipography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'label_typography',
	'selector'    => ".ba-node-$id .mailingboss__form .mailingboss-label",
));

// Label padding
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'label_padding',
	'selector'     => ".ba-node-$id .mailingboss__form .mailingboss-label",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'label_padding_top',
		'padding-right'  => 'label_padding_right',
		'padding-bottom' => 'label_padding_bottom',
		'padding-left'   => 'label_padding_left',
	),
));

?>

.ba-node-<?php echo $id; ?> .mailingboss-label {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->label_color, 'initial'); ?>;
}


<?php
/** FIELD */
?>

.ba-node-<?php echo $id; ?> .mailingboss__form input,
.ba-node-<?php echo $id; ?> .mailingboss__form textarea,
.ba-node-<?php echo $id; ?> .mailingboss__form select
{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->field_background_color); ?> !important;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->field_color); ?> !important;
}
<?php


BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'field_typography',
	'selector'     => ".ba-node-$id .mailingboss__form input, .ba-node-$id .mailingboss__form select, .ba-node-$id .mailingboss__form textarea",
));

// Fields padding
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'field_padding',
	'selector'     => ".ba-node-$id .mailingboss__form input, .ba-node-$id .mailingboss__form select, .ba-node-$id .mailingboss__form textarea",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'field_padding_top',
		'padding-right'  => 'field_padding_right',
		'padding-bottom' => 'field_padding_bottom',
		'padding-left'   => 'field_padding_left',
	),
));

// Fields border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'field_border',
	'selector' => ".ba-node-$id .mailingboss__form input, .ba-node-$id .mailingboss__form select, .ba-node-$id .mailingboss__form textarea",
	'important' => true
));

?>

<?php
/** Checkbox and radios */
?>

.ba-node-<?php echo $id; ?> .mailingboss-cr-label
{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->cr_color); ?> !important;
}
<?php

// CR Tipography
BACheetahCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'cr_typography',
	'selector'     => ".ba-node-$id .mailingboss-cr-label",
));

// CR padding
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'cr_padding',
	'selector'     => ".ba-node-$id .mailingboss-cr-label",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'cr_padding_top',
		'padding-right'  => 'cr_padding_right',
		'padding-bottom' => 'cr_padding_bottom',
		'padding-left'   => 'cr_padding_left',
	),
));

// Button
BACheetah::render_module_css( 'button', $id, $module->get_button_settings() );

?>