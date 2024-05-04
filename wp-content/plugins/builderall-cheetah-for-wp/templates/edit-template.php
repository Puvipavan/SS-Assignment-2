<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<main id="main">

		<?php

		// Start the Loop.
		while (have_posts()) :
			the_post();
		?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div>
					<?php
					the_content();
					?>
				</div>

				<?php BACheetah::render_edit_link($post->ID, 'Edit content'); ?>

			</article>

		<?php
		endwhile; // End the loop.
		?>

	</main>


	<?php
	wp_footer();
	?>
</body>

</html>