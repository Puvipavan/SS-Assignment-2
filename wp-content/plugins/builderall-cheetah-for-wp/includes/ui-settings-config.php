( function( $ ) {
	BACheetahSettingsConfig = 'undefined' === typeof BACheetahSettingsConfig ? {} : BACheetahSettingsConfig;
	$.extend( BACheetahSettingsConfig, <?php echo BACheetahUtils::json_encode( $settings ); ?> );
	if ( 'undefined' !== typeof BACheetah ) {
		BACheetah.triggerHook( 'settingsConfigLoaded' );
	}
} )( jQuery );
