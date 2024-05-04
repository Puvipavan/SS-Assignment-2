<?php
$acordion_node_selector = ".ba-node-$id .accordion__content";
?>

(function($) {

	$('<?php echo $acordion_node_selector ?>').each(function(index){

		const active_index = <?php echo $settings->active_index ?>;
		const faq_mode = <?php echo isset($settings->faq_mode) ? $settings->faq_mode : 1 ?>;

		const accordion_active = faq_mode ? active_index : index == 0 && active_index === 0 ? 0 : false

		console.log($(this), active_index, faq_mode, accordion_active)

		$(this).accordion({
			icons: { "header": '<?php echo esc_attr($settings->icon) ?>', "activeHeader": '<?php echo esc_attr($settings->icon_active) ?>' },
			active: accordion_active,
			collapsible: true,
			heightStyle: "content",
		});
	});

})(jQuery);