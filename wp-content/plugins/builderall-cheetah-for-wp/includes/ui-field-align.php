<#

var defaults = {
	left: '<svg xmlns="http://www.w3.org/2000/svg" width="21.548" height="11.971" viewBox="0 0 21.548 11.971"><path d="M16.168,7.788H4.2a1.2,1.2,0,1,0,0,2.394H16.168a1.2,1.2,0,0,0,0-2.394ZM4.2,14.971H23.351a1.2,1.2,0,1,0,0-2.394H4.2a1.2,1.2,0,1,0,0,2.394ZM3,4.2a1.2,1.2,0,0,0,1.2,1.2H23.351a1.2,1.2,0,0,0,0-2.394H4.2A1.2,1.2,0,0,0,3,4.2Z" transform="translate(-3 -3)" /></svg>',
	center: '<svg xmlns="http://www.w3.org/2000/svg" width="21.548" height="11.971" viewBox="0 0 21.548 11.971"><path d="M4.2,14.971H23.351a1.2,1.2,0,1,0,0-2.394H4.2a1.2,1.2,0,1,0,0,2.394ZM7.788,8.985a1.2,1.2,0,0,0,1.2,1.2h9.577a1.2,1.2,0,0,0,0-2.394H8.985A1.2,1.2,0,0,0,7.788,8.985ZM3,4.2a1.2,1.2,0,0,0,1.2,1.2H23.351a1.2,1.2,0,0,0,0-2.394H4.2A1.2,1.2,0,0,0,3,4.2Z" transform="translate(-3 -3)"/></svg>',
	right: '<svg xmlns="http://www.w3.org/2000/svg" width="21.548" height="11.971" viewBox="0 0 21.548 11.971"><path d="M4.2,14.971H23.351a1.2,1.2,0,1,0,0-2.394H4.2a1.2,1.2,0,1,0,0,2.394Zm7.183-4.788H23.351a1.2,1.2,0,1,0,0-2.394H11.38a1.2,1.2,0,0,0,0,2.394ZM3,4.2a1.2,1.2,0,0,0,1.2,1.2H23.351a1.2,1.2,0,0,0,0-2.394H4.2A1.2,1.2,0,0,0,3,4.2Z" transform="translate(-3 -3)"/></svg>',
};

var values = data.field.values;
var options = {};

if ( values ) {
	for ( var option in defaults ) {
		if ( values[ option ] ) {
			options[ values[ option ] ] = defaults[ option ];
		}
	}
} else {
	options = defaults;
}

var field = wp.template( 'ba-cheetah-field-button-group' )( {
	name: data.name,
	value: data.value,
	field: {
		options: options,
	},
} );

#>
{{{field}}}
