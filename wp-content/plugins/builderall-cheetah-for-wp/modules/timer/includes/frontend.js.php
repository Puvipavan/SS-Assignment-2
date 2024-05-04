<?php
if ($data = $module->get_data()): ?>

new BACheetahTimer(
	<?php echo json_encode($data)?>,
);

<?php endif; ?>