<?php do_action( 'ba_cheetah_before_ui_bar_title' ); ?>
<span class="ba-cheetah-bar-title">
		
	<div class="ba-cheetah-bar-title-icon" title="<?php _e( 'Toggle Main Menu', 'ba-cheetah' ); ?>">
		<svg class="ba-cheetah-icon-burger-closed" xmlns="http://www.w3.org/2000/svg" width="28.805" height="17.556" viewBox="0 0 28.805 17.556">
			<path id="list" d="M1.68,17.556a1.68,1.68,0,1,1,0-3.36H27.124a1.68,1.68,0,1,1,0,3.36Zm0-7.1a1.68,1.68,0,1,1,0-3.36H27.124a1.68,1.68,0,1,1,0,3.36Zm0-7.1A1.68,1.68,0,1,1,1.68,0H27.125a1.68,1.68,0,0,1,0,3.36Z" fill="#2c6bfe"/>
		</svg>
		<svg class="ba-cheetah-icon-burger-open" xmlns="http://www.w3.org/2000/svg" height="28.805" width="17.676" viewBox="0 0 29 17.676">
			<path id="list" d="M13.291,17.676a1.692,1.692,0,0,1,0-3.383H27.309a1.692,1.692,0,0,1,0,3.383Zm-11.6-7.147a1.691,1.691,0,1,1,0-3.382H27.309a1.691,1.691,0,1,1,0,3.382Zm0-7.147A1.691,1.691,0,1,1,1.692,0H15.709a1.691,1.691,0,1,1,0,3.382Z" fill="#2c6bfe"></path>
		</svg>
	</div>


	<div class="ba-cheetah-editor-logo">
		<img src="<?php echo $icon_url; ?>" />
		<span class="ba-cheetah-bar-divider"></span>
		<div class="ba-cheetah-layout-title" title="<?php echo esc_attr( $pretitle . ': ' . $title ); ?>">
			<?php echo esc_html( $title ); ?>
		</div>
	</div>

</span>
<?php do_action( 'ba_cheetah_after_ui_bar_title' ); ?>
