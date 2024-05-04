new BACheetahMailingboss({
	node: '<?php echo $id; ?>',
	recaptcha: {
		url: '<?php echo get_rest_url(null, 'ba-cheetah/v1/recaptcha/verify')?>',
		enabled: <?php echo isset($settings->recaptcha_type) && $settings->recaptcha_type == 'v2_checkbox' && BACheetahRecaptchaV2::keys() ? 'true' : 'false'?>,
	},
	utm_bindings: {
		enabled: <?php echo isset($settings->utm_bindings) && $settings->utm_bindings == 'enabled' ? 'true' : 'false' ?>,
	}
});