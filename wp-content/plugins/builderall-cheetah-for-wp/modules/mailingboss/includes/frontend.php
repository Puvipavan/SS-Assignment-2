<?php
$node_id = "ba-node-$id";
?>

<div class="ba-module__mailingboss <?php echo $node_id; ?>">
	<?php
	if (isset($settings->mailingboss_list) && $settings->mailingboss_list) :
		$fields = $module->getFields();
		$action = $settings->url_subscribe ? $settings->url_subscribe : sprintf('https://member.mailingboss.com/index.php/lists/%s/subscribe', $settings->mailingboss_list);
		$mailingossFields = new BACheetahMailingbossFields($settings, $fields);
	?>
		<form
			class="mailingboss__form"
			action="<?= $action; ?>"
			method="post"
			accept-charset="utf-8"
			target="<?php esc_attr($settings->target ? $settings->target : '_blank') ?>"
		>

			<?php
			// fields
			if (empty($fields)) {
				echo 'Invalid or missing Mailingboss form. Please refresh the element.';
			} else {
				$mailingossFields->render();

				$module->renderRecaptcha();

				// submit button
				BACheetah::render_module_html('button', $module->get_button_settings() ); 
			}
			?>
		</form>

	<?php else : ?>

		<p><?php echo __('Select a list', 'ba-cheetah'); ?></p>

	<?php endif; ?>
</div>