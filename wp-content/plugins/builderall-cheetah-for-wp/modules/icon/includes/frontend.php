<?php
$node_id = "ba-node-$id";
?>

<div class="ba-module__icon <?php echo $node_id; ?>">

	<div class="icon__wrapper">

		<!-- open link -->
		<?php if ($settings->link): ?>
			<a href="<?php echo esc_url($settings->link) ?>" target="<?php echo esc_attr($settings->link_target) ?>">
		<?php endif; ?>

		<i class="<?php echo esc_attr($settings->icon); ?>"></i>

		<!-- close link -->
		<?php if ($settings->link): ?>
			</a>
		<?php endif; ?>

	</div>
</div>