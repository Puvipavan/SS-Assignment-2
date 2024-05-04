<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php if (!current_theme_supports('title-tag')) : ?>
		<title>
			<?php echo wp_get_document_title(); ?>
		</title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<?php if($ba_cheetah_header_id != 'disabled'): ?>
		<div class="ba-header-container">
			<div class="ba-header <?= get_post_meta($ba_cheetah_header_id, 'ba-cheetah-fixed-header', true) == 1 ? 'ba-header-is-fixed' : ''; ?>">
				<?php
				BACheetah::render_content_by_id($ba_cheetah_header_id, 'header');
				BACheetah::render_edit_link($ba_cheetah_header_id, 'Edit header');
				?>
			</div>
		</div>
		<?php endif; ?>