<div id="ba-cheetah-integrations-form" class="ba-cheetah-settings-form">
	<h3 class="ba-cheetah-settings-form-header"><?= __('Builderall', 'ba-cheetah'); ?></h3>
	
	<?php
	$user = BACheetahAuthentication::user();
	$showLinkButton = $showUnlinkButton = false;
	if ($user) {
		$showUnlinkButton = true;
	}
	else {
		$showLinkButton = true;
	}
	?>
	<?php if ($user) : ?>
		<p><?php printf(__('Your Builderall Builder for Wordpress is linked to the Builderall account <b> %s </b> and you are able to use all Builderall Modules.', 'ba-cheetah'), $user['email']); ?></p>
	<?php endif; ?>
	

	<!-- 
		Form
	-->

	<form id="integrations-form" action="<?php BACheetahAdminSettings::render_form_action( 'integrations' ); ?>" method="post">
			
		<!-- 
			Wathermark
		-->
		
		<?php 
		
		if (BACheetahAuthentication::is_builderall_user()) :
			$watermark = get_option( '_ba_cheetah_watermark', array('show' => false, 'position' => 'left') );
		?>
		<table class="form-table">
			<tr>
				<td>
					<label for="ba-cheetah-show-watermark">
					<input type="checkbox" name="ba-cheetah-show-watermark" id="ba-cheetah-show-watermark" value="1" <?php echo checked($watermark['show'], true) ?> /> 
						<?php echo __('Show Watermark', 'ba-cheetah'); ?>
					</label>
				</td>
				<td>
					<label for="ba-cheetah-watermark-position"><?php echo __('Position', 'ba-cheetah'); ?>:</label>
					<select name="ba-cheetah-watermark-position" id="ba-cheetah-watermark-position">
						<option value="left" <?php echo selected($watermark['position'], 'left') ?>><?php echo __('Left', 'ba-cheetah'); ?></option>
						<option value="right" <?php echo selected($watermark['position'], 'right')?>><?php echo __('Right', 'ba-cheetah'); ?></option>
					</select>
				</td>
			</tr>
		</table>

		<?php endif;?>

		<!-- 
			Buttons
		-->


		<?php if ($showUnlinkButton) : ?>
		<p>
			<div class="buttons">
				<a href="<?= get_rest_url(null, 'ba-cheetah/v1/oauth/logout'); ?>" class="button button-primary" onclick="return confirm('<?= __('By unlinking your account you will lose access to all Builderall Integrations, do you want to continue?', 'ba-cheetah'); ?>')"><?= __('Unlink with my Builderall account', 'ba-cheetah'); ?></a>
			</div>
		</p>
		<?php elseif($showLinkButton) : ?>
			<p>
				<?= __('Link your Builderall Builder for WordPress with your Builderall account to unlock integrations with Booking, Supercheckout, Mailingboss and more!'); ?></p><p>
				<div class="buttons">
					<a href="<?= get_rest_url(null, 'ba-cheetah/v1/oauth/redirect'); ?>" class="button button-primary"><?= __("Link with my Builderall account", 'ba-cheetah'); ?></a>
				</div>
			</p>
		<?php endif;?>

		<hr>

		<!-- 
			Recaptcha
		-->

		<h3 class="ba-cheetah-settings-form-header"><?= __('Google Recaptcha', 'ba-cheetah'); ?></h3>

		<section class="ba-cheetah-admin-form-section">
			<p><?php echo __('Enter your Recaptcha keys to enable it in Mailingboss.', 'ba-cheetah')?></p>
			
			<fieldset>
				<label for="recaptcha_sitekey">Site Key</label>
				<input name="recaptcha_sitekey" minlength="20" id="recaptcha_sitekey" type="text" value="<?php echo get_option('_ba_cheetah_recaptcha_sitekey', '') ?>" placeholder="YOUR_SITE_KEY" class="regular-text code">
			</fieldset>
			<fieldset>
				<label for="recaptcha_secretkey">Secret Key</label>
				<input name="recaptcha_secretkey" minlength="20" id="recaptcha_secretkey" type="text" value="<?php echo get_option('_ba_cheetah_recaptcha_secretkey', '') ?>" placeholder="YOUR_SECRET_KEY" class="regular-text code">
			</fieldset>

			<a href="https://www.google.com/recaptcha/admin/create" target="_blank">
				<?php echo __('Get Keys', 'ba-cheetah') ?>
			</a>
		</section>

		<hr>

		<!-- 
			Pixel
		-->

		<h3 class="ba-cheetah-settings-form-header"><?= __('Facebook Pixel', 'ba-cheetah'); ?></h3>

		<section class="ba-cheetah-admin-form-section">
			<p><?php echo __('After entering the Facebook Pixel ID, it will run on all pages built with Builderall Builder. You can disable this for each page or add custom events in page settings and click events.', 'ba-cheetah'); ?></p>

			<fieldset>
				<label for="pixel_id">Pixel ID</label>
				<input name="pixel_id" minlength="10" pattern="[0-9]+" id="pixel_id" type="text" value="<?php echo get_option('_ba_cheetah_facebook_pixel_id', '') ?>" class="regular-text code" placeholder="PIXEL_ID">
			</fieldset>

			<a href="https://www.facebook.com/events_manager2" target="_blank">
				<?php echo __('Get My Pixel ID', 'ba-cheetah') ?>
			</a>
		</section>

		<!-- 
			Submit
		-->

		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'ba-integrations', 'ba-cheetah-integrations-config-nonce' ); ?>
		</p>
	</form>
	
</div>
