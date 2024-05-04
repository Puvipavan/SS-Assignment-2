<div class="ba-cheetah-form-field ba-cheetah-custom-field"<# if ( data.field.preview_text ) { #> data-preview-text="{{{data.field.preview_text}}}"<# } #>>
	<div class="ba-cheetah-form-field-preview-text">
		<#

		if ( 'string' === typeof data.value && '' !== data.value ) {
			data.value = JSON.parse( data.value );
		}

		if ( data.field.preview_text && 'object' === typeof data.value ) {

			var form = BACheetahSettingsConfig.forms[ data.field.form ],
				text = '';

			for ( var tab in form.tabs ) {

				for ( var section in form.tabs[ tab ].sections ) {

					var fields = form.tabs[ tab ].sections[ section ].fields;

					if ( fields[ data.field.preview_text ] ) {

						var field = fields[ data.field.preview_text ];

						if ( 'icon' === field.type ) {
							if ( '' !== data.value[ data.field.preview_text ] ) {
								text = '<i class="' + data.value[ data.field.preview_text ] + '"></i>';
							}
						} else if ( 'select' === field.type ) {
							text = field.options[ data.value[ data.field.preview_text ] ];
						} else if ( '' !== data.value[ data.field.preview_text ] ) {
							var tmp = document.createElement( 'div' );
							text = data.value[ data.field.preview_text ].toString().replace( /&#39;/g, "'" );
							tmp.innerHTML = text;
							text = ( tmp.textContent || tmp.innerText || '' ).replace( /^(.{35}[^\s]*).*/, "$1" ) + '...'
						}
					}
				}
			}
		}

		#>
		{{{text}}}
	</div>
	<#

	if ( 'object' === typeof data.value ) {
		data.value = BACheetah._getSettingsJSONForHTML( data.value );
	}

	var label = BACheetahStrings.editFormField.replace( '%s', data.field.label );

	#>

	<a class="ba-cheetah-form-field-edit" href="javascript:void(0);" onclick="return false;" data-type="{{data.field.form}}">{{{label}}}</a>

	<input name="{{data.name}}" type="hidden" value='{{data.value}}' />
</div>
