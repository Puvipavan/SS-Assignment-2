<?php get_header(); ?>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<main id="main">

		<?php

		// Start the Loop.
		while (have_posts()) :
			the_post();
		?>

			<main id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<div>
					<?php
					the_content();
					?>
				</div>

				<?php BACheetah::render_edit_link($post->ID, 'Edit content'); ?>

			</main>

		<?php
		endwhile; // End the loop.
		?>



	</main>

	<?php get_footer(); ?>
</body>

</html>