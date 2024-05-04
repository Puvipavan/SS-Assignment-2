<#
data.value = '';
var select = wp.template( 'ba-cheetah-field-select' )( data );
#>
<div class="ba-cheetah-preset-select-controls" data-presets="{{data.field.presets}}" data-prefix="{{data.field.prefix}}">{{{select}}}</div>
