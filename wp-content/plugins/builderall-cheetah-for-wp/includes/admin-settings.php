<div class="wrap <?php BACheetahAdminSettings::render_page_class(); ?>">

	<h1 class="ba-cheetah-settings-heading">
		<?php BACheetahAdminSettings::render_page_heading(); ?>
	</h1>

	<?php BACheetahAdminSettings::render_update_message(); ?>

	<div class="ba-cheetah-settings-nav">
		<ul>
			<?php BACheetahAdminSettings::render_nav_items(); ?>
		</ul>
	</div>

	<div class="ba-cheetah-settings-content">
		<?php BACheetahAdminSettings::render_forms(); ?>
	</div>
</div>
