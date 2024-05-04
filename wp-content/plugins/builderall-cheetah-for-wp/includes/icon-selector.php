<div class="ba-cheetah-lightbox-header">
	<h1><?php _e( 'Select Icon', 'ba-cheetah' ); ?></h1>
	<div class="ba-cheetah-icons-filter">
		<input type="text" class="ba-cheetah-icons-filter-text" placeholder="Search..." />
		<select class="ba-cheetah-icons-filter-select">
			<option value="all"><?php _ex( 'All Libraries', 'Select option for showing all icon libraries.', 'ba-cheetah' ); ?></option>
			<?php foreach ( $icon_sets as $set_key => $set_data ) : ?>
			<option value="<?php echo esc_attr($set_key); ?>"><?php echo $set_data['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<div class="ba-cheetah-icons-list">
	<?php foreach ( $icon_sets as $set_key => $set_data ) : ?>
	<div class="ba-cheetah-icons-section ba-cheetah-<?php echo $set_key; ?>">
		<h2><?php echo esc_html($set_data['name']); ?></h2>
		<?php foreach ( $set_data['icons'] as $icon ) : ?>
			<?php if ( ! empty( $set_data['prefix'] ) ) : ?>
			<i class="<?php echo $set_data['prefix'] . ' ' . $icon; ?>"></i>
			<?php else : ?>
			<i class="<?php echo $icon; ?>"></i>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>
<div class="ba-cheetah-lightbox-footer ba-cheetah-icon-selector-footer">
	<a class="ba-cheetah-icon-selector-cancel ba-cheetah-button ba-cheetah-button-large" href="javascript:void(0);" onclick="return false;"><?php _e( 'Cancel', 'ba-cheetah' ); ?></a>
</div>
