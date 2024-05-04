<script type="text/javascript">

BACheetahAdminSettingsConfig = {
	roles: <?php echo json_encode( BACheetahUserAccess::get_all_roles() ); ?>,
	userAccess: <?php echo json_encode( BACheetahUserAccess::get_saved_settings() ); ?>
};

BACheetahAdminSettingsStrings = {
	deselectAll: '<?php esc_attr_e( 'Deselect All', 'ba-cheetah' ); ?>',
	noneSelected: '<?php esc_attr_e( 'None Selected', 'ba-cheetah' ); ?>',
	select: '<?php esc_attr_e( 'Select...', 'ba-cheetah' ); ?>',
	selected: '<?php esc_attr_e( 'Selected', 'ba-cheetah' ); ?>',
	selectAll: '<?php esc_attr_e( 'Select All', 'ba-cheetah' ); ?>',
	selectFile: '<?php esc_attr_e( 'Select File', 'ba-cheetah' ); ?>',
	uninstall: '<?php esc_attr_e( 'Please type "uninstall" in the box below to confirm that you really want to uninstall the page builder and all of its data.', 'ba-cheetah' ); ?>'
};

</script>
