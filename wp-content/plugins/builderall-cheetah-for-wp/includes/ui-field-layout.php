<div class="ba-cheetah-layout-field">
	<# for ( var key in data.field.options ) { #>
	<div class="ba-cheetah-layout-field-option<# if ( key == data.value ) { #> ba-cheetah-layout-field-option-selected<# } #>" data-value="{{key}}">
		<img src="{{{data.field.options[ key ]}}}" />
	</div>
	<# } #>
	<div class="ba-cheetah-clear"></div>
	<input name="{{data.name}}" type="hidden" value='{{{data.value}}}' />
</div>
