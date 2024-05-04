<#

var className = data.field.className ? data.field.className : '';
var slider = data.field.slider;
var units = data.field.units;

#>
<div class="ba-cheetah-unit-field-inputs">
	<div class="ba-cheetah-unit-field-input">
		<input
		class="{{className}}"
		type="number"
		name="{{data.name}}"
		value="{{{data.value}}}"
		placeholder="<# if ( data.field.placeholder ) {  #>{{data.field.placeholder}}<# } #>"
		autocomplete="off"
		/>
		<# if ( slider ) {
			slider = JSON.stringify( slider );
		#>
		<div
			class="ba-cheetah-field-popup-slider"
			data-input="{{data.name}}"
			data-slider="{{slider}}"
		>
			<div class="ba-cheetah-field-popup-slider-arrow"></div>
			<div class="ba-cheetah-field-popup-slider-input"></div>
		</div>
		<# } #>
	</div>
	<# if ( units ) { #>
	<div class="ba-cheetah-unit-field-input ba-cheetah-unit-field-unit-select">
		<# if ( units.length > 1 ) {
			var unit = {
				name: 'undefined' !== typeof data.unit_name ? data.unit_name : data.name + '_unit',
				value: 'undefined' !== typeof data.unit_value ? data.unit_value : data.settings[ data.name + '_unit' ],
			};
		#>
		<select class="ba-cheetah-field-unit-select" name="{{unit.name}}">
			<# for ( var i = 0; i < units.length; i++ ) {
				var selected = units[i] === unit.value ? ' selected="selected"' : '';
				var label = '' === units[i] ? '&mdash;' : units[i];
			#>
			<option value="{{units[i]}}"{{{selected}}}>{{{label}}}</option>
			<# } #>
		</select>
		<# } else { #>
		<div class="ba-cheetah-field-unit-select">{{units[0]}}</div>
		<# } #>
	</div>
	<# } #>
</div>
