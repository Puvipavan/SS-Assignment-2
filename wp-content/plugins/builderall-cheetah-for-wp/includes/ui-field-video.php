<#

var video = null;

if ( BACheetahSettingsConfig.attachments[ data.value ] ) {
	video = BACheetahSettingsConfig.attachments[ data.value ];
} else if ( ! _.isEmpty( data.value ) ) {
	video = {
		id: data.value,
		url: data.value,
		filename: data.value
	};
}

var className = data.field.className ? ' ' + data.field.className : '';

if ( ! data.value || ! video ) {
	className += ' ba-cheetah-video-empty';
}

#>
<div class="ba-cheetah-video-field ba-cheetah-media-field{{className}}">
	<a class="ba-cheetah-video-select" href="javascript:void(0);" onclick="return false;">
		<svg xmlns="http://www.w3.org/2000/svg" width="47.918" height="34.227" viewBox="0 0 47.918 34.227">
			<g transform="translate(-12.966 -18.404)">
				<path d="M10.777,46.227h20.8A6.726,6.726,0,0,0,38.3,39.5V18.751A6.726,6.726,0,0,0,31.578,12h-20.8A6.777,6.777,0,0,0,4,18.8V39.424a6.777,6.777,0,0,0,6.777,6.8ZM21.113,18.794a10.268,10.268,0,1,1-7.279,3.026,10.268,10.268,0,0,1,7.279-3.026Z" transform="translate(8.966 6.404)" fill="#c7d1db"/>
				<path d="M54.982,17.848,49.575,20.62a2.8,2.8,0,0,0-1.515,2.456V36.766a2.8,2.8,0,0,0,1.515,2.481l5.408,2.781a2.259,2.259,0,0,0,3.294-2.045V19.858a2.259,2.259,0,0,0-3.294-2.011Z" transform="translate(2.607 5.596)" fill="#c7d1db"/>
				<path d="M7.862,3.29H5.482V.91A.91.91,0,0,0,4.572,0H4.2a.91.91,0,0,0-.91.91V3.29H.91A.91.91,0,0,0,0,4.2v.373a.91.91,0,0,0,.91.91H3.289V7.862a.91.91,0,0,0,.91.91h.373a.91.91,0,0,0,.91-.91V5.483H7.862a.91.91,0,0,0,.91-.91V4.2A.91.91,0,0,0,7.862,3.29Z" transform="translate(34.504 31.148) rotate(90)" fill="#a9b3be"/>
			</g>
		</svg>
		<?php _e( 'Select Video', 'ba-cheetah' ); ?>
	</a>
	<div class="ba-cheetah-video-preview">
		<# if ( data.value && video ) { #>
		<div class="ba-cheetah-video-preview-img">
			<i class="fas fa-file-video"></i>
		</div>
		<span class="ba-cheetah-video-preview-filename">{{{video.filename}}}</span>
		<# } else { #>
		<div class="ba-cheetah-video-preview-img">
			<img src="<?php echo BA_CHEETAH_URL; ?>img/spacer.png" />
		</div>
		<span class="ba-cheetah-video-preview-filename"></span>
		<# } #>
		<br />
		<a class="ba-cheetah-video-replace" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Replace Video', 'ba-cheetah' ); ?>">
			<svg width="18" height="18"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
		</a>
		<# if ( data.field.show_remove ) { #>
		<a class="ba-cheetah-video-remove" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Remove Video', 'ba-cheetah' ); ?>">
			<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
		</a>
		<# } #>
		<!-- <div class="ba-cheetah-clear"></div> -->
	</div>
	<input name="{{data.name}}" type="hidden" value='{{{data.value}}}' />
</div>
