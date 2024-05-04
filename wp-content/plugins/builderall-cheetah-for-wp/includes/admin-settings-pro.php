<?php
	$user_email = get_option('_ba_cheetah_pro_email') ? get_option('_ba_cheetah_pro_email') : get_option('admin_email');

	$sent = null;
	if(isset($_GET["sent"])) :
		$sent = $_GET["sent"];
	endif;
?>

	<div id="ba-cheetah-pro-form" class="ba-cheetah-settings-form">
		<h3 class="ba-cheetah-settings-form-header"><?php _e( 'Builderall Builder Pro', 'ba-cheetah' ); ?></h3>
	
		
			<?php
				if (BACheetahAuthentication::is_pro_user()) :
				?>
					<p><?php echo __('Yeah!! You are Builderall Builder Pro. Enjoy your Access!'); ?></p>
					<?php
						if(!BACheetahAuthentication::is_builderall_user()) : ?>
							<form id="pro-form" action="<?php BACheetahAdminSettings::render_form_action( 'pro' ); ?>" method="post">
								<div class="buttons">
									<a href="<?=BACheetahLoader::get_office_url()?>us/office" class="center button-primary" target="_blank">
										<?= __("Builderall Builder Pro Dashboard", 'ba-cheetah'); ?>
										<span class="dashicons dashicons-external" style="margin: 3px 5px;"></span>
									</a>
								</div>
								<br>
								<p>
									<?php wp_nonce_field( 'remove-pro-access', 'ba-cheetah-pro-user-access-nonce' ); ?>
									<a href="javascript:document.getElementById('pro-form').submit();" onClick="return confirm('<?= __('You will lose your Pro access, do you want to continue?', 'ba-cheetah'); ?>')" class="btn-force-level"><?php echo __('Remove my License!'); ?></a>
								</p>
							</form>
					<?php	
						endif;
					?>
				<?php
				else :
				?>
					<p class="pro-block sub-title-pro" <?php if($sent == 1) : echo 'style="display:none;"'; endif; ?>><?php echo __('Are you a PRO user? If so, please click on the button below to generate the Token and active your Builderall Builder PRO.'); ?></p>
					<div class="error" style="display:none;"></div>
					<div class="success" style="display:none;"></div>

					<form id="pro-form" action="<?php BACheetahAdminSettings::render_form_action( 'pro' ); ?>" method="post">
						<div class="pro-block token" <?php if($sent == null) : echo 'style="display:none;"'; endif; ?>>
							<p><?php echo __('We sent a Token to the email:'); ?> <span class="email_sent"><strong><?php echo $user_email; ?></strong></span></p>
							<p><?php echo __('The Token will expire in 5 minutes!'); ?></p>
							<input type="text" name="token" id="token" value="" placeholder="<?php echo __('Insert your Token here!') ?>">
							<br><br>
							<div class="ba-cheetah-user-access-setting">
								<div class="buttons">
									<center>
										<input type="submit" name="update" class="left button-primary" value="<?php esc_attr_e( 'Activate', 'ba-cheetah' ); ?>" style="width:100%" />
										<?php wp_nonce_field( 'pro-user', 'ba-cheetah-pro-user-access-nonce' ); ?>
									</center>
								</div>
							</div>
					</form>
					<form id="pro-form" action="<?php BACheetahAdminSettings::render_form_action( 'pro' ); ?>" method="post">
							<div class="ba-cheetah-user-access-setting">
								<div class="buttons">
									<center>
										<input type="submit" name="update" class="right send-token button" value="<?= __("Send me the Token again", 'ba-cheetah'); ?>" />
										<?php wp_nonce_field( 'pro-user-token', 'ba-cheetah-pro-user-access-nonce' ); ?>
									</center>
								</div>
							</div>
						</div>
					</form>
					<form id="pro-form" action="<?php BACheetahAdminSettings::render_form_action( 'pro' ); ?>" method="post">
						<div class="pro-block button-token"  <?php if($sent == 1) : echo 'style="display:none;"'; endif; ?>>
							<div>
								<p><?php echo __('Please verify if the email below is the same email used to purchase Builderall Builder PRO.'); ?></p>
								<input type="text" name="cheetah_email" placeholder="Email" value="<?=$user_email;?>">
							</div>
							<div class="ba-cheetah-user-access-setting">
								<div class="buttons">
									<input type="submit" name="update" class="right send-token button button-primary" value="<?= __("Send me the Token email", 'ba-cheetah'); ?>" />
									<?php wp_nonce_field( 'pro-user-token', 'ba-cheetah-pro-user-access-nonce' ); ?>
								</div>
							</div>
							<div class="ba-cheetah-user-access-setting">
								<div class="buttons">
									<a href="javascript:;" class="left has-token button"><?= __("I already have a Token", 'ba-cheetah'); ?></a>
								</div>
							</div>
							<center>
								<a href="javascript:;" class="btn-force-level"><?php echo __('Refresh my user status'); ?></a>
							</center>
						</div>
					</form>
					
					<div class="pro-block force-check-level" style="display: none;">
						<form id="pro-form" action="<?php BACheetahAdminSettings::render_form_action( 'pro' ); ?>" method="post">
							<div class="ba-cheetah-settings-form-content">
					
								<div class="ba-cheetah-user-access-group">
									<h3><?php echo __('Is there something wrong?'); ?></h3>
									<p style="padding: 15px 20px 40px 20px !important;">
										<?php echo __('Have you lost your Builderall Builder Pro access? Try check your level again!'); ?>
										<br><br>
										<input type="submit" name="check-level" class="right send-token button button-primary" value="<?= __("Check now!", 'ba-cheetah'); ?>" />
										<?php wp_nonce_field( 'check-pro-level', 'ba-cheetah-pro-user-access-nonce' ); ?>
									</p>
								</div>
							</div>
							<a href="javascript:;" class="btn-back-force-level"><?php echo __('Back'); ?></a>
						</form>
					</div>
					
			<?php endif; ?>
		</form>
	</div>