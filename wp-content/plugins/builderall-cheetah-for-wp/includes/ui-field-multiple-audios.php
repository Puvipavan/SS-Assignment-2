<#

// Normalize the value so we have an array.
if ( '' !== data.value && 'string' === typeof data.value ) {

	data.value = JSON.parse( data.value );

	// Older versions might be double encoded.
	if ( 'string' === typeof data.value ) {
		data.value = JSON.parse( data.value );
	}
	if ( 'number' === typeof data.value ) {
		data.value = [ data.value ];
	}

} else if ( '' === data.value ) {
	data.value = [];
}

if ( 1 === data.value.length ) {
	var selectedText = BACheetahStrings.audioSelectedNum.replace( '%d', 1 );
} else {
	var selectedText = BACheetahStrings.audiosSelectedNum.replace( '%d', data.value.length );
}

var encodedValue = '' !== data.value && data.value.length ? JSON.stringify( data.value ) : '';

#>
<div class="ba-cheetah-multiple-audios-field ba-cheetah-media-field
	<# if ( '' === data.value ) { #> ba-cheetah-multiple-audios-empty<# } #>
	<# if ( data.field.className ) { #> {{data.field.className}}<# } #>"
	<# if ( data.field.toggle ) { data.field.toggle = JSON.stringify( data.field.toggle ); #>data-toggle='{{{data.field.toggle}}}'<# } #>>
	<div class="ba-cheetah-multiple-audios-count">{{selectedText}}</div>
	<a class="ba-cheetah-multiple-audios-select" href="javascript:void(0);" onclick="return false;"><?php _e( 'Select Audio', 'ba-cheetah' ); ?></a>
	<a class="ba-cheetah-multiple-audios-edit" href="javascript:void(0);" onclick="return false;"><?php _e( 'Edit Playlist', 'ba-cheetah' ); ?></a>
	<a class="ba-cheetah-multiple-audios-add" href="javascript:void(0);" onclick="return false;"><?php _e( 'Add Audio Files', 'ba-cheetah' ); ?></a>
	<input name="{{data.name}}" type="hidden" value='{{{encodedValue}}}' />
</div>
