<div class="ba-cheetah-service-settings">
	<table class="ba-cheetah-form-table">
		<#

		var service_type = null,
			services     = {},
			options 	 = { '' : '<?php esc_html_e( 'Choose...', 'ba-cheetah' ); ?>' },
			key			 = '',
			fields		 = {},
			html		 = '';

		if ( data.section.services && 'all' !== data.section.services ) {
			service_type = data.section.services;
		}

		if ( ! service_type ) {
			services = BACheetahConfig.services;
		}
		else {
			for ( key in BACheetahConfig.services ) {
				if ( BACheetahConfig.services[ key ].type == service_type ) {
					services[ key ] = BACheetahConfig.services[ key ];
				}
			}
		}

		for ( key in services ) {
			options[ key ] = services[ key ].name;
		}

		var fields = {
			service: {
				row_class : 'ba-cheetah-service-select-row',
				className : 'ba-cheetah-service-select',
				type      : 'select',
				label     : '<?php esc_html_e( 'Service', 'ba-cheetah' ); ?>',
				options   : options,
				preview   : {
					type 	: 'none'
				}
			}
		};

		html = BACheetahSettingsForms.renderFields( fields, data.settings );

		#>
		{{{html}}}
	</table>
</div>
