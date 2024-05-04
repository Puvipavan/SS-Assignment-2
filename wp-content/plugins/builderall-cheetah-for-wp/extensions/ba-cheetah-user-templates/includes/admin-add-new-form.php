<div class="wrap">

	<h1 class="wp-heading-inline"><?php _e('New Layout Element', 'ba-cheetah'); ?></h1>

	<p><?php _e( 'Add new builder content using the form below.', 'ba-cheetah' ); ?></p>

	<form class="ba-cheetah-new-template-form" name="ba-cheetah-new-template-form" method="POST">

		<table class="form-table" role="presentation">

			<tbody>

				<tr>
					<th>
						<label for="ba-cheetah-template[title]"><?php _e('Title', 'ba-cheetah'); ?></label>
					</th>
					<td>
						<input class="ba-cheetah-template-title regular-text" type="text" name="ba-cheetah-template[title]" required />
					</td>
				</tr>

				<tr>
					<th>
						<label for="ba-cheetah-template[type]"><?php _e('Type', 'ba-cheetah'); ?></label>
					</th>
					<td>
						<select class="ba-cheetah-template-type" name="ba-cheetah-template[type]" required>
							<option value=""><?php _e('Choose...', 'ba-cheetah'); ?></option>
							<?php foreach ($types as $type) : ?>
								<option value="<?php echo esc_attr($type['key']); ?>" <?php selected($selected_type, $type['key']); ?>><?php echo $type['label']; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr class="ba-cheetah-template-module-row">
					<th>
						<label for="ba-cheetah-template[module]"><?php _e('Element', 'ba-cheetah'); ?></label>
					</th>
					<td>
						<select class="ba-cheetah-template-module" name="ba-cheetah-template[module]" required>
							<option value=""><?php _e('Choose...', 'ba-cheetah'); ?></option>
							<?php foreach ($modules as $title => $group) : ?>
								<?php
								if (__('WordPress Widgets', 'ba-cheetah') == $title) {
									continue;
								}
								?>
								<optgroup label="<?php echo $title; ?>">
									<?php foreach ($group as $module) : ?>
										<option value="<?php echo esc_attr($module->slug); ?>"><?php echo esc_html($module->name); ?></option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>

				<tr class="ba-cheetah-template-global-row">
					<th>
						<label for="ba-cheetah-template[global]"><?php _e('Global', 'ba-cheetah'); ?></label>
						<i class="dashicons dashicons-editor-help" title="<?php esc_html_e('Global rows, columns and elements can be added to multiple pages and edited in one place.', 'ba-cheetah'); ?>"></i>
					</th>
					<td>
						<label>
							<input class="ba-cheetah-template-global" type="checkbox" name="ba-cheetah-template[global]" value="1" />
							<?php _e('Make this saved row or element global?', 'ba-cheetah'); ?>
						</label>
					</td>
				</tr>

				<?php do_action('ba_cheetah_user_templates_admin_add_form'); ?>

			</tbody>

		</table>

		<p class="submit">
			<input type="submit" class="ba-cheetah-template-add button button-primary button-large" value="<?php _e('Add', 'ba-cheetah'); ?>">
		</p>

		<?php wp_nonce_field('ba-cheetah-add-template-nonce', 'ba-cheetah-add-template'); ?>

	</form>
</div>