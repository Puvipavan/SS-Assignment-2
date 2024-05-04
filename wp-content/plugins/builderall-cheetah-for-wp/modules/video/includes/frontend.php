<?php
	$schema = $module->get_structured_data();
?>

<div class="ba-cheetah-video ba-cheetah-<?php echo ( 'media_library' == $settings->video_type ) ? 'wp' : 'embed'; ?>-video"<?php $schema ? BACheetah::print_schema( ' itemscope itemtype="https://schema.org/VideoObject"' ) : ''; ?>>
	<?php

	if ( $schema ) {
		echo $schema;
	}

	if (isset( $settings->sticky_on_scroll ) && 'yes' === $settings->sticky_on_scroll ) {
		echo '<button class="ba-cheetah-video-close-button"><span class="dashicons dashicons-no"></span></button>';
	}


	// $module->render_poster_html();
	$module->render_video_html( $schema );

	?>
</div>
