<?php
$license_url = get_rest_url(null, 'ba-cheetah/v1/oauth/redirect');
$office_url = BA_CHEETAH_OFFICE_URL;
$changelog_url = 'https://wordpress.org/plugins/builderall-cheetah-for-wp/#developers';

?>
<div id="ba-cheetah-welcome-form" class="ba-cheetah-settings-form">

	<h2 class="ba-cheetah-settings-form-header"><?php _e('Welcome to Builderall Builder!', 'ba-cheetah'); ?></h2>

	<div class="ba-cheetah-settings-form-content ba-cheetah-welcome-page-content">

		<p><?php _e('Builderall Builder for Wordpress is Builderall\'s responsive drag\'n drop page builder for Wordpress. Now you can build fantastic pages, add your own header and footer, and take advantage of unique elements!', 'ba-cheetah'); ?></p>

		<p><?php _e('Ready to start building?')?></p>

		<p><?php printf(__('<a href="%s">Add a new page</a> and click "Open Builderall Builder"!', 'ba-cheetah'),  admin_url().'post-new.php?post_type=page')?></p>

		<hr>
		
		<div class="ba-cheetah-welcome-col-wrap">

			<div class="ba-cheetah-welcome-col">

				<h3><?php _e('Keep up to date', 'ba-cheetah'); ?></h3>

				<p><?php printf(__('Access our <a target="_blank" href="%s">Changelog</a> and stay up to date with all the Builderall Builder for Wordpress news.', 'ba-cheetah'), $changelog_url); ?></p>

			</div>

			<div class="ba-cheetah-welcome-col">
				<h3><?php _e('Need help?', 'ba-cheetah'); ?></h3>

				<p><?php printf(__('We have great tutorials and training videos on basic and advanced levels! Take a look at our <a target="_blank" href="%s">help center</a> and  <a target="_blank" href="%s">youtube channel</a>.', 'ba-cheetah'), BA_CHEETAH_HELP_URL, BA_CHEETAH_YOUTUBE_URL); ?></p>

				<p><?php printf(__('We also have an expert support team. If you are a Builderall customer, you can reach the support team through your Builderall <a target="_blank" href="%s">Office.</a>', 'ba-cheetah'), $office_url); ?> </p>
			</div>

		</div>

	</div>
</div>