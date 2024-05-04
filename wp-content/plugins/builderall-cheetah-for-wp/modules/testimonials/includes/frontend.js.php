<?php
$node_selector = ".ba-node-$id";

if ($module->is_carousel_active()):
?>

(function($) {

	new BACheetahTestimonials({
		node			: <?php echo "'$node_selector'" ?>,
		cols			: <?php echo esc_attr($settings->columns) ?>,
		gap				: <?php echo esc_attr($settings->gap) ?>,
		colsMedium		: <?php echo esc_attr(!empty($settings->columns_medium) ? $settings->columns_medium : 0) ?>,
		colsResponsive	: <?php echo esc_attr(!empty($settings->columns_responsive) ? $settings->columns_responsive : 0)?>,
		autoplay		: <?php echo $settings->autoplay; ?>,
		pagination		: <?php echo $settings->pagination; ?>,
		speed			: <?php echo $settings->transition_speed * 1000; ?>,
		interval		: <?php echo $settings->interval * 1000; ?>,
	})
})(jQuery);

<?php endif; ?>