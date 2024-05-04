<?php if ( $module->video_aspect_ratio() ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-wp-video {
		padding-bottom: <?php echo $module->video_aspect_ratio(); ?>%;
	}
<?php endif; ?>

<?php if ( 'hide' == $settings->play_pause ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-playpause-button {
		display: none !important;
	}	
<?php endif; ?>

<?php if ( 'hide' == $settings->timer ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-currenttime-container {
		display: none !important;
	}		
<?php endif; ?>

<?php if ( 'hide' == $settings->time_rail ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-time-rail {
		display: none !important;
	}		
<?php endif; ?>

<?php if ( 'hide' == $settings->duration ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-duration-container {
		display: none !important;
	}		
<?php endif; ?>

<?php if ( 'hide' == $settings->volume ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-volume-button {
		display: none !important;
	}		
<?php endif; ?>

<?php if ( 'hide' == $settings->full_screen ) : ?>
	.ba-cheetah-node-<?php echo $id; ?> .mejs-fullscreen-button {
		display: none !important;
	}		
<?php endif; ?>

<?php
	$hide_video_control_bar = ( 'hide' == $settings->play_pause
		&& 'hide' == $settings->timer
		&& 'hide' == $settings->time_rail
		&& 'hide' == $settings->duration
		&& 'hide' == $settings->volume
		&& 'hide' == $settings->full_screen );

	if ( $hide_video_control_bar ) :
		?>
		.ba-cheetah-node-<?php echo $id; ?> .mejs-controls {
			display: none !important;
		}
<?php endif; ?>

.ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-video-poster {
	display: <?php echo ( (isset($settings->video_lightbox) && 'yes' === $settings->video_lightbox) ? 'block' : 'none' ); ?>;
}

<?php
// border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'border',
	'selector'  => ".ba-cheetah-node-$id .ba-cheetah-wp-video, .ba-cheetah-node-$id .fluid-width-video-wrapper iframe",
));

// Click action - lightbox
if ( isset( $settings->video_lightbox ) && 'yes' == $settings->video_lightbox ) :
	?>
	.ba-cheetah-video-lightbox-wrap .mfp-content {
		background: #fff;
	}
	.ba-cheetah-video-lightbox-wrap .mfp-iframe-scaler iframe {
		left: 2%;
		height: 94%;
		top: 3%;
		width: 96%;
	}

	.mfp-wrap.ba-cheetah-video-lightbox-wrap .mfp-close,
	.mfp-wrap.ba-cheetah-video-lightbox-wrap .mfp-close:hover {
		color: #333!important;
		right: -4px;
		top: -10px!important;
	}
	<?php
endif;
?>
