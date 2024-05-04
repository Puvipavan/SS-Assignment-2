( function( $ ) {

	BACheetah.registerModuleHelper( 'video', {

		submit: function()
		{

			var form      = $( '.ba-cheetah-settings' ),
				enabled     = form.find( 'select[name=schema_enabled]' ).val(),
				name        = form.find( 'input[name=name]' ).val(),
				description = form.find( 'input[name=description]' ).val();
				thumbnail   = form.find( 'input[name=thumbnail]' ).val();
				update      = form.find( 'input[name=up_date]' ).val();

			if( 'no' === enabled ) {
				return true;
			}

			if ( 0 === name.length ) {
				BACheetah.alert( 
					BACheetahStrings.schemaAllRequiredMessage,
					BACheetahStrings.settingsHaveErrors,
					'ba-cheetah-lightbox-confirm-icon',
					'validation-error.svg'
				);
				return false;
			}
			else if ( 0 === description.length ) {
				BACheetah.alert( 
					BACheetahStrings.schemaAllRequiredMessage,
					BACheetahStrings.settingsHaveErrors,
					'ba-cheetah-lightbox-confirm-icon',
					'validation-error.svg'
				);
				return false;
			}
			else if ( 0 === thumbnail.length ) {
				BACheetah.alert( 
					BACheetahStrings.schemaAllRequiredMessage,
					BACheetahStrings.settingsHaveErrors,
					'ba-cheetah-lightbox-confirm-icon',
					'validation-error.svg'
				);

				return false;
			}
			else if( 0 === update.length ) {
				BACheetah.alert( 
					BACheetahStrings.schemaAllRequiredMessage,
					BACheetahStrings.settingsHaveErrors,
					'ba-cheetah-lightbox-confirm-icon',
					'validation-error.svg'
				);
				return false;
			}

			return true;
		}
	});
})(jQuery);
