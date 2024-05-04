<?php
$tab_node_id = "ba-node-$id";
?>

<div class="<?php echo "ba-module__tab $tab_node_id tab--$settings->direction" ?> ">
	<ul class="tab__nav">
		<?php 
			foreach ($settings->items as $key => $item): 
			if (empty($item->title) && empty($item->icon)) continue;
		?>
			<li>
				<a href="<?php echo "#tab-$tab_node_id-$key" ?>">
					<?php if ($item->icon) echo '<i class="' . esc_attr($item->icon) . '"></i>'; ?>
					<?php echo esc_html($item->title) ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php 
		foreach ($settings->items as $key => $item):
		if (empty($item->title) && empty($item->icon)) continue; 
	?>
		<div 
			class="tab__body"
			id="<?php echo "tab-$tab_node_id-$key" ?>"
			style="<?php echo esc_attr($key > 0 ? 'display: none' : '') ?>"> <!-- prevent fouc -->
			<?php 
				if('no' === $item->dragdrop) {
					global $wp_embed;
					echo wpautop( $wp_embed->autoembed( wp_kses_post($item->body) ) ); 
				} else {
					BACheetah::render_row_to_module($item->row_node_id, $id, $module->slug, $key);
				}
				
			?>
		</div>
	<?php endforeach; ?>
</div>