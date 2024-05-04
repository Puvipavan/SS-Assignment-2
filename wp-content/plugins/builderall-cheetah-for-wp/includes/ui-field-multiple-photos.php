<#

// Normalize the value so we have an array.
if ( '' !== data.value && 'string' === typeof data.value ) {

	data.value = JSON.parse( data.value );

	// Older versions might be double encoded.
	if ( 'string' === typeof data.value ) {
		data.value = JSON.parse( data.value );
	}

} else if ( '' === data.value ) {
	data.value = [];
}

if ( 1 === data.value.length ) {
	var selectedText = BACheetahStrings.photoSelectedNum.replace( '%d', 1 );
} else {
	var selectedText = BACheetahStrings.photoSelectedNum.replace( '%d', data.value.length );
}

var encodedValue = '' !== data.value && data.value.length ? JSON.stringify( data.value ) : '';

#>
<div class="ba-cheetah-multiple-photos-field ba-cheetah-media-field<# if ( '' === data.value ) { #> ba-cheetah-multiple-photos-empty<# } #><# if ( data.field.className ) { #> {{data.field.className}}<# } #>">
	<div class="ba-cheetah-multiple-photos-count">{{selectedText}}</div>
	<a class="ba-cheetah-multiple-photos-select" href="javascript:void(0);" onclick="return false;"><?php _e( 'Create Gallery', 'ba-cheetah' ); ?></a>
	<a class="ba-cheetah-multiple-photos-edit" href="javascript:void(0);" onclick="return false;"><?php _e( 'Edit Gallery', 'ba-cheetah' ); ?></a>
	<a class="ba-cheetah-multiple-photos-add" href="javascript:void(0);" onclick="return false;"><?php _e( 'Add Photos', 'ba-cheetah' ); ?></a>
	<input name="{{data.name}}" type="hidden" value='{{encodedValue}}' />
</div>
