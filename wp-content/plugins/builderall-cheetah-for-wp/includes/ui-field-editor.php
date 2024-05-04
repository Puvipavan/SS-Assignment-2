<#

var wpautop = 0,
	buttons = 1,
	rows    = 16;

if ( undefined === data.field.wpautop || true === data.field.wpautop ) {
	wpautop = 1;
}
if ( false === data.field.media_buttons ) {
	buttons = 0;
}
if ( data.field.rows ) {
	rows = data.field.rows;
}

#>
<div class="ba-cheetah-editor-field" data-name="{{data.name}}" data-wpautop="{{wpautop}}" data-buttons="{{buttons}}" data-rows="{{rows}}">
	<textarea>{{data.value}}</textarea>
</div>
