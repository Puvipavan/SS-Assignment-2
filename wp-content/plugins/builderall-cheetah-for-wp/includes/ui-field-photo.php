<#

var url = '';
var selectName = '';

if ( data.isMultiple ) {
	if ( data.settings[ data.rootName + '_src' ] ) {
		url = data.settings[ data.rootName + '_src' ][ data.index ];
	}
	selectName = data.rootName + '_src[]';
} else {
	url = data.settings[ data.name + '_src' ];
	selectName = data.name + '_src';
}

var photo = null;

if ( BACheetahSettingsConfig.attachments[ data.value ] ) {
	photo = BACheetahSettingsConfig.attachments[ data.value ];
	photo.isAttachment = true;
} else if ( typeof data.value !== 'undefined' && '' !== data.value && false !== data.value ) {
	if ( data.settings[ data.rootName + '_src' ] ) {
		photo = {
			id: data.value,
			url: url,
			filename: url.split( '/' ).pop(),
			isAttachment: false
		};
	} else {
		photo = {
			id: 0,
			url: data.value,
			filename: data.value.split( '/' ).pop(),
			isAttachment: false
		};
	}
}

var field = data.field;
var className = 'ba-cheetah-photo-field ba-cheetah-media-field';

if ( ! data.value || ! photo ) {
	className += ' ba-cheetah-photo-empty';
} else if ( photo ) {
	className += photo.isAttachment ? ' ba-cheetah-photo-has-attachment' : ' ba-cheetah-photo-no-attachment';
}

if ( field.className ) {
	className += ' ' + field.className;
}

var show = '';

if ( field.show ) {
	show = "data-show='" + JSON.stringify( field.show ) + "'";
}

#>
<div class="{{className}}">
	<a class="ba-cheetah-photo-select" href="javascript:void(0);" onclick="return false;">
		<svg xmlns="http://www.w3.org/2000/svg" width="54.156" height="40.444" viewBox="0 0 54.156 40.444">
			<path d="M8.238,18.917a9.527,9.527,0,1,1,7.962-2.7,9.5,9.5,0,0,1-7.962,2.7Z" transform="translate(54.156 21.447) rotate(90)" fill="#a9b3be"/>
			<path d="M8.8,3.681H6.135V1.018A1.018,1.018,0,0,0,5.117,0H4.7A1.018,1.018,0,0,0,3.681,1.018V3.681H1.018A1.018,1.018,0,0,0,0,4.7v.417A1.018,1.018,0,0,0,1.018,6.135H3.681V8.8A1.018,1.018,0,0,0,4.7,9.817h.417A1.018,1.018,0,0,0,6.135,8.8V6.135H8.8A1.018,1.018,0,0,0,9.817,5.117V4.7A1.018,1.018,0,0,0,8.8,3.681Z" transform="translate(49.559 26.006) rotate(90)" fill="#fff"/>
			<path d="M31.619,46.906H5.231A5.25,5.25,0,0,1,0,41.668V5.232A5.252,5.252,0,0,1,5.231,0H18.016a13.212,13.212,0,0,0,2.751,10.969L16.53,13.492a1.836,1.836,0,0,0,0,3.148,1.792,1.792,0,0,0,1.846,0l2.559-1.521A1.675,1.675,0,1,1,22.613,18L20.7,19.141,9.271,25.947a1.83,1.83,0,0,0,0,3.148l14.181,8.445L27.423,39.9a1.808,1.808,0,0,0,.893.234,1.836,1.836,0,0,0,.919-.246,1.8,1.8,0,0,0,.915-1.562V15.746c.263.014.518.021.758.021a13.291,13.291,0,0,0,5.944-1.412V41.668a5.255,5.255,0,0,1-5.232,5.238ZM10.048,10.049A3.351,3.351,0,1,0,13.4,13.4,3.353,3.353,0,0,0,10.048,10.049Z" transform="translate(46.906) rotate(90)" fill="#c7d1db"/>
		</svg>
		<?php _e( 'Select Photo', 'ba-cheetah' ); ?>
	</a>
	<div class="ba-cheetah-photo-preview">
		<div class="ba-cheetah-photo-preview-img">
			<img src="<# if ( photo ) { var src = BACheetah._getPhotoSrc( photo ); #>{{{src}}}<# } #>" />
		</div>
		<div class="ba-cheetah-photo-preview-controls">
			<select name="{{selectName}}" {{{show}}}>
				<# if ( photo && url ) {
					var sizes = BACheetah._getPhotoSizeOptions( photo, url );
				#>
				{{{sizes}}}
				<# } #>
			</select>
			<!--
			<div class="ba-cheetah-photo-preview-filename">
				<# if ( photo ) { #>{{{photo.filename}}}<# } #>
			</div>
			-->
			<a class="ba-cheetah-photo-edit" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Edit', 'ba-cheetah' ); ?>">
				<svg width="18" height="18"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
			</a>
			<# if ( data.field.show_remove ) { #>
			<a class="ba-cheetah-photo-remove" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Remove', 'ba-cheetah' ); ?>">
				<svg width="15.429" height="18" filter="brightness(0) invert(1)"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
			</a>
			<# } else { #>
			<!-- <a class="ba-cheetah-photo-replace" href="javascript:void(0);" onclick="return false;"><?php _e( 'Replace', 'ba-cheetah' ); ?></a> -->
			<# } #>
		</div>
	</div>
	<input name="{{data.name}}" type="hidden" value='{{data.value}}' />
</div>
