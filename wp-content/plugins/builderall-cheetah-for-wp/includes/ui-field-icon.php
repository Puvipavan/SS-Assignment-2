<#

var field = data.field;
var className = 'ba-cheetah-icon-field ba-cheetah-media-field';

if ( '' === data.value ) {
	className += ' ba-cheetah-icon-empty';
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
	<a class="ba-cheetah-icon-select" href="javascript:void(0);" onclick="return false;">
		<svg xmlns="http://www.w3.org/2000/svg" width="46.817" height="43.442" viewBox="0 0 46.817 43.442">
			<g transform="translate(-1784.234 -463.055)">
				<path d="M19303.2,20185.867a2.116,2.116,0,0,1-.668-.105c-2-.662-2.437-4.207-2.9-7.963l-.006-.037v-.012c-.236-1.959-.529-4.4-1.049-5.131-.523-.715-2.656-1.729-4.537-2.623l-.008-.006-.01-.006-.006,0c-3.4-1.625-6.607-3.16-6.641-5.256l0-.041c.006-2.129,3.262-3.641,6.709-5.238,1.891-.879,4.033-1.875,4.559-2.594s.838-3.057,1.105-5.121l.008-.061c.5-3.742.961-7.277,2.98-7.924a2.082,2.082,0,0,1,.641-.1c1.49,0,3.188,1.461,5.275,3.654-.02.193-.037.408-.049.676a12.729,12.729,0,0,0,11.023,13.188,13.412,13.412,0,0,0,1.652.105,12.85,12.85,0,0,0,3.344-.445c-.91,1.646-1.754,3.275-1.754,4.037.01.916,1.133,2.975,2.123,4.793,1.314,2.42,2.672,4.922,2.705,6.682a2.478,2.478,0,0,1-.432,1.525c-.527.719-1.5,1.068-2.982,1.068a31.332,31.332,0,0,1-5.533-.779l-.033-.006a28.935,28.935,0,0,0-4.473-.662,2.466,2.466,0,0,0-.707.08c-.842.275-2.471,1.988-3.906,3.5l0,.006C19307.295,20183.537,19305.09,20185.867,19303.2,20185.867Z" transform="translate(21970.102 -18824.32) rotate(90)" fill="#c7d1db"/>
				<path d="M8.238,18.917a9.527,9.527,0,1,1,7.962-2.7,9.5,9.5,0,0,1-7.962,2.7Z" transform="translate(1831.052 487.5) rotate(90)" fill="#a9b3be"/>
				<path d="M8.8,3.681H6.135V1.018A1.018,1.018,0,0,0,5.117,0H4.7A1.018,1.018,0,0,0,3.681,1.018V3.681H1.018A1.018,1.018,0,0,0,0,4.7v.417A1.018,1.018,0,0,0,1.018,6.135H3.681V8.8A1.018,1.018,0,0,0,4.7,9.817h.417A1.018,1.018,0,0,0,6.135,8.8V6.135H8.8A1.018,1.018,0,0,0,9.817,5.117V4.7A1.018,1.018,0,0,0,8.8,3.681Z" transform="translate(1826.454 492.059) rotate(90)" fill="#fff"/>
			</g>
		</svg>
		<?php _e( 'Add icon', 'ba-cheetah' ); ?>
	</a>
	<div class="ba-cheetah-icon-preview">
		<i class="{{{data.value}}}" data-icon="{{{data.value}}}"></i>
		<a class="ba-cheetah-icon-replace" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Replace', 'ba-cheetah' ); ?>">
			<svg width="18" height="18"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
		</a>
		<# if ( data.field.show_remove ) { #>
		<a class="ba-cheetah-icon-remove" href="javascript:void(0);" onclick="return false;" title="<?php _e( 'Remove', 'ba-cheetah' ); ?>">
			<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
		</a>
		<# } #>
	</div>
	<input name="{{data.name}}" type="hidden" value="{{{data.value}}}" {{{show}}} />
</div>
