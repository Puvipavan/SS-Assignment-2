<#

var getOptions = function( start, end, saved ) {
	var i, value, selected, options = '';

	for ( i = start; i < end; i++ ) {
		value = String( i );
		value = 1 === value.length ? '0' + value : value;
		selected = value == saved ? ' selected="selected"' : '';
		options += '<option value="' + value + '"' + selected + '>' + value + '</option>';
	}

	return options;
}

#>
<select name="{{data.name}}[][hours]" class="ba-cheetah-time-field-hours">
	<# var hours = getOptions( 1, 13, data.value.hours ); #>
	{{{hours}}}
</select>
<select name="{{data.name}}[][minutes]" class="ba-cheetah-time-field-minutes">
	<# var minutes = getOptions( 0, 60, data.value.minutes ); #>
	{{{minutes}}}
</select>
<select name="{{data.name}}[][day_period]" class="ba-cheetah-time-field-day_period">
	<option value="am"<# if ( 'am' == data.value.day_period ) { #> selected="selected"<# } #>>am</option>
	<option value="pm"<# if ( 'pm' == data.value.day_period ) { #> selected="selected"<# } #>>pm</option>
</select>
