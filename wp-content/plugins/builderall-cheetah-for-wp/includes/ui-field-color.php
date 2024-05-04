<div class="ba-cheetah-color-picker<# if ( data.field.className ) { #> {{data.field.className}}<# } if ( data.field.show_reset ) { #> ba-cheetah-color-picker-has-reset<# } #>">
	<span class="ba-cheetah-color-picker-color<# if ( '' === data.value ) { #> ba-cheetah-color-picker-empty<# } #><# if ( data.field.show_alpha ) { #> ba-cheetah-color-picker-alpha-enabled<# } #>">
		<svg class="ba-cheetah-color-picker-icon" width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
		    <g fill-rule="evenodd">
		        <path d="M17.7037706,2.62786498 L15.3689327,0.292540631 C14.9789598,-0.0975135435 14.3440039,-0.0975135435 13.954031,0.292540631 L10.829248,3.41797472 L8.91438095,1.49770802 L7.4994792,2.91290457 L8.9193806,4.33310182 L0,13.2493402 L0,18 L4.74967016,18 L13.6690508,9.07876094 L15.0839525,10.4989582 L16.4988542,9.08376163 L14.5789876,7.16349493 L17.7037706,4.03806084 C18.0987431,3.64800667 18.0987431,3.01791916 17.7037706,2.62786498 Z M3.92288433,16 L2,14.0771157 L10.0771157,6 L12,7.92288433 L3.92288433,16 Z"></path>
		    </g>
		</svg>
	</span>
	<# if ( data.field.show_reset ) { #>
		<button class="ba-cheetah-color-picker-clear">
			<svg width="15.429" height="18" fill="#95accc"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
		</button>
	<# } #>
	<!-- user interaction -->
	<input name="{{data.name}}" type="text" value="{{{data.value}}}" class="ba-cheetah-color-picker-value-input" placeholder="<?php echo _e('Paste color here...', 'ba-cheetah')?>" />
	
	<!-- real stored value  -->
	<input name="{{data.name}}" type="hidden" readonly value="{{{data.value}}}" class="ba-cheetah-color-picker-value" />
	<div class="ba-cheetah-clear"></div>
</div>
