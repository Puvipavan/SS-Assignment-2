<?php
$list_node_id = "ba-node-$id";
?>

<div class="ba-module__list <?php echo $list_node_id; ?>">
	<ul>
		<?php
			foreach ($settings->items as $key => $item) : 
				if (!$item->text) continue;
			
			?>
			<li class="list__item">

				<?php if ( ($settings->default_icon || $item->icon) /* && ('yes' === $item->show_icon) */) : ?>
					<span class="list__icon">
						<i class="<?php echo esc_attr($item->icon ? $item->icon : $settings->default_icon) ?>"></i>
					</span>
				<?php endif; ?>

				<div class="list__text">
					<?php
					// if('no' === $item->dragdrop) {

					if ($item->link) {
						echo '<a href="' . esc_url($item->link) . '" target="' . esc_attr($item->link_target) . '">';
						echo BACheetahListModule::ksesListItem($item->text);
						echo '</a>';
					} else {
						echo BACheetahListModule::ksesListItem($item->text);
					}

					// } else {
					// 	BACheetah::render_row_to_module($item->row_node_id, $id);
					// } 
					?>
			</li>

			<li class="list__divider"><hr></li>
		<?php endforeach; ?>
	</ul>
</div>