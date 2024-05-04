<div class="ba-cheetah-link-field">
	<div class="ba-cheetah-link-field-input-wrap">
		<input type="text" name="{{data.name}}" value="{{{data.value}}}" class="text ba-cheetah-link-field-input" placeholder="<# if ( data.field.placeholder ) { #>{{data.field.placeholder}}<# } else { #><?php _ex( 'http://www.example.com', 'Link placeholder', 'ba-cheetah' ); ?><# } #>" />
		<button class="ba-cheetah-link-field-select ba-cheetah-button ba-cheetah-button-light" href="javascript:void(0);" onclick="return false;"><?php _e( 'Select', 'ba-cheetah' ); ?></button>
	</div>

	<div class="ba-cheetah-link-field-options-wrap">
		<# if ( data.field.show_target ) {
			var value = data.settings[ data.name + '_target' ];
			var checked = '_blank' === value ? 'checked' : '';
		#>
		<label>
			<input type="checkbox" class="ba-cheetah-link-field-target-cb" {{checked}} />
			<input type="hidden" name="{{data.name}}_target" value="{{value}}" />
			<span><?php _e( 'New Window', 'ba-cheetah' ); ?></span>
		</label>
		<# } #>
		<# if ( data.field.show_nofollow ) {
			var value = data.settings[ data.name + '_nofollow' ];
			var checked = 'yes' === value ? 'checked' : '';
		#>
		<label>
			<input type="checkbox" class="ba-cheetah-link-field-nofollow-cb" {{checked}} />
			<input type="hidden" name="{{data.name}}_nofollow" value="{{value}}" />
			<span><?php _e( 'No Follow', 'ba-cheetah' ); ?></span>
		</label>
		<# } #>

		<# if ( data.field.show_download ) {
			var value = data.settings[ data.name + '_download' ];
			var checked = 'yes' === value ? 'checked' : '';
		#>
		<label>
			<input type="checkbox" class="ba-cheetah-link-field-download-cb" {{checked}} />
			<input type="hidden" name="{{data.name}}_download" value="{{value}}" />
			<span><?php _e( 'Force Download', 'ba-cheetah' ); ?></span>
		</label>
		<# } #>

		<# if ( ! ( data.field.show_target && data.field.show_nofollow ) ) { #>
				<label></label>
		<# } #>
	</div>

	<div class="ba-cheetah-link-field-search">
		<span class="ba-cheetah-link-field-search-title"><?php _e( 'Enter a post title to search.', 'ba-cheetah' ); ?></span>
		<input type="text" name="{{data.name}}-search" class="text text-full ba-cheetah-link-field-search-input" placeholder="<?php esc_attr_e( 'Start typing...', 'ba-cheetah' ); ?>" />
		<button class="ba-cheetah-link-field-search-cancel ba-cheetah-button ba-cheetah-button-light" href="javascript:void(0);" onclick="return false;"><?php _e( 'Cancel', 'ba-cheetah' ); ?></button>
	</div>
</div>
