<?php
$node_id = "ba-node-$id";
?>

<div class="ba-module__mega-menu <?php echo $node_id; ?>">

	<nav class="mega-menu__nav">
		<ul class="mega-menu__nav__items">
			<?php foreach ($settings->mega_menu_items as $key => $item) : ?>

				<li class="mega-menu__nav__item" data-target="<?php echo esc_attr("mega-menu__content__item-$node_id-$key") ?>" data-index="<?php echo $key?>">
					<?php if ($item->dragdrop == 'no' && $item->link): ?>
						<a href="<?php echo esc_url($item->link)?>" target="<?php echo esc_attr($item->link_target); ?>">
							<?php $module->get_title($item) ?>
						</a>

					<?php else: ?>
						<?php $module->get_title($item) ?>
					<?php endif; ?>					
				</li>

			<?php endforeach; ?>
		</ul>
	</nav>

	<section class="mega-menu__content">
		<?php foreach ($settings->mega_menu_items as $key => $item) : 
			if ($item->dragdrop == 'no')
				continue;
			?>
			<div class="mega-menu__content__item" id="<?php echo esc_attr("mega-menu__content__item-$node_id-$key") ?>">
				<?php
					if ('yes' === $item->dragdrop) {
						BACheetah::render_row_to_module($item->row_node_id, $id, $module->slug, $key);
					} 
				?>
			</div>
		<?php endforeach; ?>
	</section>
</div>