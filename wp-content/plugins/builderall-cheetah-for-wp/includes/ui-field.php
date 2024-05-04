<script type="text/html" id="tmpl-ba-cheetah-field">
	<#
		var msgRestrict = badgePro = "";
		var isEnable =  true;
		if( !_.isUndefined(data.field.enabled) && data.field.enabled === false) {
			isEnable = false;
			msgRestrict = ' onclick="BACheetah._showProMessage(\''+data.field.label+'\');" style="cursor: pointer;"';
			badgePro = '<span class="ba-cheetah-pro-badge">PRO</span>';
		}
	#>

	<# if ( ! data.field.label ) { #>
	<td class="ba-cheetah-field-control" colspan="2">
	<# } else { #>
	<th class="ba-cheetah-field-label">
		
		<label for="{{data.name}}"{{{msgRestrict}}}>
			
			<# if ( 'button' === data.field.type ) { #>
			&nbsp;
			<# } else { #>
			{{{data.field.label}}}{{{badgePro}}}
				<# if ( undefined !== data.index ) { #>
				<# var index = data.index + 1; #>
				<span class="ba-cheetah-field-index">{{index}}</span>
				<# } #>
			<# } #>

			<# if ( data.responsive ) { #>
			<span class="ba-cheetah-field-responsive-toggle" data-mode="default" title="Responsive editing">
				<svg width="15" height="15"><use xlink:href="#ba-cheetah-icon--desktop"></use></svg>
			</span>
			<# } #>

			<# if ( data.field.help ) { #>
			<span class="ba-cheetah-help-tooltip">
				<svg class="ba-cheetah-help-tooltip-icon" width="16" height="16"><use xlink:href="#ba-cheetah-icon--help"></use></svg>
				<span class="ba-cheetah-help-tooltip-text">{{{data.field.help}}}</span>
			</span>
			<# } #>

		</label>
	</th>
	<td class="ba-cheetah-field-control">
	<# } 
	
		if (isEnable) { #>
			<div class="ba-cheetah-field-control-wrapper">
				<# if ( data.responsive ) { #>
				<i class="ba-cheetah-field-responsive-toggle dashicons dashicons-desktop" data-mode="default"></i>
				<# } #>
				<# var devices = [ 'default', 'medium', 'responsive' ];

				for ( var i = 0; i < devices.length; i++ ) {

					data.device = devices[ i ];

					if ( 'default' !== devices[ i ] && ! data.responsive ) {
						continue;
					}

					if ( data.responsive ) {
						data.name  = 'default' === devices[ i ] ? data.rootName : data.rootName + '_' + devices[ i ];
						data.value = data.settings[ data.name ] ? data.settings[ data.name ] : '';

						if ( 'object' === typeof data.responsive ) {
							for ( var key in data.responsive ) {
								if ( 'object' === typeof data.responsive[ key ] && undefined !== data.responsive[ key ][ devices[ i ] ] ) {
									data.field[ key ] = data.responsive[ key ][ devices[ i ] ];
								}
							}
						}
					#>
					<div class="ba-cheetah-field-responsive-setting ba-cheetah-field-responsive-setting-{{devices[ i ]}}" data-device="{{devices[ i ]}}">
					<# } #>
					<# if ( data.template.length ) {
						var template = wp.template( 'ba-cheetah-field-' + data.field.type ),
							field    = template( data ),
							before   = data.field.html_before ? data.field.html_before : '',
							after    = data.field.html_after ? data.field.html_after : '';
					#>
					{{{before}}}{{{field}}}{{{after}}}
					<# } else {
						var name  = data.name.replace( '[]', '' );
					#>
					<div class="ba-cheetah-legacy-field" data-field="{{name}}" />
					<# } #>
					<# if ( data.responsive ) { #>
					</div>
					<# } #>
				<# } #>
				<# if ( data.field.description ) { #>
				<span class="ba-cheetah-field-description">{{{data.field.description}}}</span>
				<# } #>
			</div>
		<# } #>
	</td>
</script>
