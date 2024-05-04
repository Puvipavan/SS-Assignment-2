<?php
$settings = get_option( '_ba_cheetah_general_settings' );
$canvasCheked = ($settings) ? ($settings['canvas-mode'] == 1) ? 'checked="checked"' : '' : 'checked="checked"';
?>

<div id="ba-cheetah-general-form" class="ba-cheetah-settings-form">
	<form id="editing-form" action="<?php BACheetahAdminSettings::render_form_action( 'general' ); ?>" method="post">
		<h3 class="ba-cheetah-settings-form-header"><?= __('Canvas Mode', 'ba-cheetah'); ?></h3>
		<label><input type="checkbox" name="ba-cheetah-canvas-mode" value="1" <?php echo $canvasCheked; ?> /><?php echo __('Create new pages in canvas mode', 'ba-cheetah'); ?></label>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'ba-general-config', 'ba-cheetah-general-settings-nonce' ); ?>
		</p>
	</form>
</div>