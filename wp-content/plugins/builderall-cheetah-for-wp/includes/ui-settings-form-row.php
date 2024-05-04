<script type="text/html" id="tmpl-ba-cheetah-settings-row">
	<#
	var connections = false
	if ( 'undefined' !== typeof data.field.connections ) {
		connections = true
	}
	#>
	<# if ( data.isMultiple && data.supportsMultiple && data.template.length ) {
		var values = data.value,
			button = BACheetahStrings.addField.replace( '%s', data.field.label ),
			i	   = 0;

		data.name += '[]';

		var limit = 0;
		if ( 'undefined' !== typeof data.field.limit ) {
			limit = data.field.limit
		} #>
	<tbody id="ba-cheetah-field-{{data.rootName}}" class="ba-cheetah-field ba-cheetah-field-multiples" data-limit="{{limit}}" data-type="form" data-preview='{{{data.preview}}}' data-connections="{{{connections}}}">
		<tr>
			<# if ( ! data.field.label ) { #>
			<td colspan="2">
			<# } else { #>
			<td>
			<# } #>
				<a href="javascript:void(0);" onclick="return false;" class="ba-cheetah-field-add ba-cheetah-button ba-cheetah-button-primary" data-field="{{data.rootName}}">{{button}}</a>
			</td>
		</tr>
		<# for( ; i < values.length; i++ ) {
			data.index = i;
			data.value = values[ i ];
		#>
		<tr class="ba-cheetah-field-multiple" data-field="{{data.rootName}}">
			<#
			var isDragAndDrop = BACheetahSettingsConfig['defaults']['forms'][data.field.form].hasOwnProperty('row_node_id')
			var field = BACheetahSettingsForms.renderField( data ); #>
			{{{field}}}
			<td class="ba-cheetah-field-actions">
				<svg width="17" height="17" class="ba-cheetah-field-move"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
				<# if (!isDragAndDrop) { #>
					<svg width="14.205" height="18" class="ba-cheetah-field-copy"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>
				<# } #>
				<svg width="15.429" height="18" class="ba-cheetah-field-delete"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
			</td>
		</tr>
		<# } #>
	</tbody>
	<# } else { #>
	<tr id="ba-cheetah-field-{{data.name}}" class="ba-cheetah-field{{data.rowClass}}" data-type="{{data.field.type}}" data-is-style="{{data.field.is_style}}" data-preview='{{{data.preview}}}' data-connections="{{{connections}}}">
		<# var field = BACheetahSettingsForms.renderField( data ); #>
		{{{field}}}
	</tr>
	<# } #>
</script>
