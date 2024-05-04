<#

// Make sure we have an options array.
if ( '' === data.field.options ) {
	data.field.options = [];
}

// JSON parse if needed.
if ( '' !== data.value && 'string' === typeof data.value ) {
	data.value = JSON.parse( data.value );
}

// Set the default value if we do not have one.
if ( '' === data.value ) {
	data.value = Object.keys( data.field.options );
}

// Make sure any new options are added to the value.
for ( var key in data.field.options ) {
	if ( jQuery.inArray( key, data.value ) === -1 ) {
		(data.value || []).push( key );
	}
}

var encodedValue = JSON.stringify( data.value );

#>
<div class="ba-cheetah-ordering-field-options<# if ( data.field.className ) { #> {{data.field.className}}<# } #>">
	<# for ( var i in data.value ) { #>
	<div class="ba-cheetah-ordering-field-option" data-key="{{data.value[ i ]}}">
		{{data.field.options[ data.value[ i ] ]}}
		<svg width="17" height="17" class="ba-cheetah-field-move"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
	</div>
	<# } #>
</div>
<input type="hidden" name="{{data.name}}" value='{{{encodedValue}}}' />
