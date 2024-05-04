.ba-node-<?php echo $id; ?> .timer__counter {
	gap: <?php echo $settings->box_gap.$settings->box_gap_unit; ?>;
}

.ba-node-<?php echo $id; ?> .timer__counter .timer__box {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->box_background); ?>;
}

.ba-node-<?php echo $id; ?> .timer__counter .timer__box,
.ba-node-<?php echo $id; ?> .timer__counter .timer__colon {
	padding-top: <?php echo $settings->box_padding ?>px;
	padding-bottom: <?php echo $settings->box_padding ?>px;
}
.ba-node-<?php echo $id; ?> .timer__counter .timer__colon,
.ba-node-<?php echo $id; ?> .timer__counter .timer__box output {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->numbers_color); ?>;
}

.ba-node-<?php echo $id; ?> .timer__counter .timer__box .timer__label {
	display: <?php echo $settings->labels_display; ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->labels_color); ?>;
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->labels_background); ?>;
}

.ba-node-<?php echo $id; ?> .timer__counter .timer__colon {
	display: <?php echo $settings->colons_display; ?>;
}
.ba-node-<?php echo $id; ?> .timer__message {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->message_color); ?>;
}

<?php 
// box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'box_border',
	'selector'  => ".ba-node-$id .timer__counter .timer__box",
));

// number typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'numbers_typography',
	'selector'    => ".ba-node-$id .timer__counter .timer__box output, .ba-node-$id .timer__counter .timer__colon",
));

// label typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'labels_typography',
	'selector'    => ".ba-node-$id .timer__counter .timer__box .timer__label",
));

// box- width
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'box_width',
	'selector'     => ".ba-node-$id .timer__counter .timer__box",
	'prop'         => 'flex-basis',
));

// message typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'message_typography',
	'selector'    => ".ba-node-$id .timer__message",
));
