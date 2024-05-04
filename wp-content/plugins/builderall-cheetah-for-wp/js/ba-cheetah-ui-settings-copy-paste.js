( function( $ ) {

	BACheetahSettingsCopyPaste = {

		init: function() {
			BACheetah.addHook( 'settings-form-init', this.initExportButton );
			BACheetah.addHook( 'settings-form-init', this.initImportButton );
		},

		initExportButton: function() {

			new ClipboardJS( 'button.module-export-all', {
				text: function( trigger ) {
					var nodeId    = $( '.ba-cheetah-module-settings' ).data( 'node' ),
						form      = $( '.ba-cheetah-module-settings[data-node=' + nodeId + ']' ),
						type      = $( '.ba-cheetah-module-settings' ).data( 'type' ),
						settings  = BACheetah._getSettings( form ),
						d         = new Date(),
						date      = d.toDateString(),
						wrap      = '/// {type:' + type + '} ' + date + ' ///',
						btn		  = $( 'button.module-export-all' ),
						btnText	  = btn.attr( 'title' );

					btn.text( BACheetahStrings.module_import.copied );
					setTimeout( function() { btn.text( btnText ) }, 1000 );

					return wrap + "\n" + JSON.stringify( settings );
				}
			});

			new ClipboardJS( 'button.module-export-style', {
				text: function( trigger ) {
					var nodeId    = $( '.ba-cheetah-module-settings' ).data( 'node' ),
						form      = $( '.ba-cheetah-module-settings[data-node=' + nodeId + ']' ),
						type      = $( '.ba-cheetah-module-settings' ).data( 'type' ),
						settings  = BACheetah._getSettings( form ),
						d         = new Date(),
						date      = d.toDateString(),
						wrap      = '/// {type:' + type + '} ' + date + ' ///',
						btn		  = $( 'button.module-export-style' ),
						btnText	  = btn.attr( 'title' ),
						styles	  = {};

					for ( var key in settings ) {
						var singleInput = form.find( '[name="' + key + '"]' ),
							arrayInput = form.find( '[name*="' + key + '["]' ),
							isStyle = false;

						if ( singleInput.length ) {
							isStyle = singleInput.closest( '.ba-cheetah-field' ).data( 'is-style' );
						} else if ( arrayInput.length ) {
							isStyle = arrayInput.closest( '.ba-cheetah-field' ).data( 'is-style' );
						}

						if ( isStyle ) {
							styles[ key ] = settings[ key ];
						}
					}

					btn.text( BACheetahStrings.module_import.copied );
					setTimeout( function() { btn.text( btnText ) }, 1000 );

					return wrap + "\n" + JSON.stringify( styles );
				}
			});
		},

		initImportButton: function() {

			$( 'button.module-import-apply' ).click( function() {
				var form        = $( '.ba-cheetah-settings-lightbox .ba-cheetah-settings' ),
					data        = $( '.module-import-input' ).val(),
					t           = data.match( /\/\/\/\s\{type:([_a-z0-9-]+)/ ),
					type        = false,
					moduleType  = $( '.ba-cheetah-module-settings' ).data( 'type' ),
					errorDiv    = $( '.ba-cheetah-settings-lightbox .module-import-error' );

				errorDiv.hide();

				if( t && typeof t[1] !== 'undefined' ) {
					type = t[1];
				}

				if ( type && type === moduleType ) {
					var cleandata = data.replace( /\/\/\/.+\/\/\//, '' );
					try {
						var importedSettings = JSON.parse( cleandata );
					} catch ( err ) {
						var importedSettings = false;
						errorDiv.html( BACheetahStrings.module_import.error ).show();
						return false;
					}
				} else {
					errorDiv.html( BACheetahStrings.module_import.type ).show();
					return false;
				}

				if ( importedSettings ) {
					var nodeId = form.attr( 'data-node' );

					var merged = $.extend( {}, BACheetahSettingsConfig.nodes[ nodeId ], importedSettings );

					BACheetahSettingsConfig.nodes[ nodeId ] = merged;

					BACheetah.ajax( {
						action          : 'save_settings',
						node_id         : nodeId,
						settings        : merged
					}, BACheetah._saveSettingsComplete.bind( this, true, null ) );

					BACheetah.triggerHook( 'didSaveNodeSettings', {
						nodeId   : nodeId,
						settings : merged
					} );

					BACheetah._lightbox.close();
				}
			});
		},
	};

	$( function() {
		BACheetahSettingsCopyPaste.init();
	} );

} )( jQuery );
