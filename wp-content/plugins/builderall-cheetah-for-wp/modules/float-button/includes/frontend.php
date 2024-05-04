<?php
$float_button_node_id = "ba-node-$id";
?>

<div class="ba-module__float-button <?php echo $float_button_node_id; ?>">
    <?php if($settings->menu !== 'true'): ?>
	<ul>
		<?php foreach ($settings->items as $item) :
			if (!$item->icon) continue;
		?>
			<li class="float-button__item">
				<a
					aria-label="<?php echo esc_attr($item->name) ?>"
					title="<?php echo esc_attr($item->name) ?>"
					href="<?php echo esc_url($item->link) ?>"
					target="_blank"
					rel="noopener noreferrer">
					<i class="<?php echo esc_attr($item->icon) ?>"></i>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
    <?php else:?>
        <div class="container-float-button-with-menu">
            <div class="float-button__menu-anim">
                <?php if ($settings->v_alignment === 'flex-end'):?>
                <ul>
                    <?php foreach ($settings->items as $item) :
                        if (!$item->icon) continue;
                        ?>
                        <li class="float-button__item">
                            <a
                                    aria-label="<?php echo esc_attr($item->name) ?>"
                                    title="<?php echo esc_attr($item->name) ?>"
                                    href="<?php echo esc_url($item->link) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                <i class="<?php echo esc_attr($item->icon) ?>"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif;?>
                <button class="float-button__menu float-button__menu-toggle" type="button"
                        style="
                                background-color: <?php echo BACheetahColor::hex_or_rgb($settings->bg_icon_color); ?>;
                                ">
                    <i class="<?php echo esc_attr($settings->icon) ?>"
                       style="
                               font-size: <?php echo esc_attr($settings->icon_size) ?>px;
                               color: <?php echo BACheetahColor::hex_or_rgb($settings->icon_color); ?>
                               "
                       aria-hidden="true"></i>
                </button>
                <?php if ($settings->v_alignment === 'flex-start'):?>
                    <ul>
                        <?php foreach ($settings->items as $item) :
                            if (!$item->icon) continue;
                            ?>
                            <li class="float-button__item">
                                <a
                                        aria-label="<?php echo esc_attr($item->name) ?>"
                                        title="<?php echo esc_attr($item->name) ?>"
                                        href="<?php echo esc_url($item->link) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer">
                                    <i class="<?php echo esc_attr($item->icon) ?>"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif;?>
            </div>
        </div>
    <?php endif;?>
</div>
