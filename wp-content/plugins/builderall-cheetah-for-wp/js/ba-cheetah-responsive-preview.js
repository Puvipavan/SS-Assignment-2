( function( $ ) {

	/**
	 * Helper for handling responsive preview in an iframe.
	 *

	 * @class BACheetahResponsivePreview
	 */
	BACheetahResponsivePreview = {

		/**
		 * Enters responsive preview mode.
		 *

		 * @method enter
		 */
		enter: function(mode) {
			this.render();
			if (mode) {
				this.switchTo(mode)
				this.activatePreviewMenuBarDeviceIcon(mode)
				this._showSize(mode)
			}
		},

		/**
		 * Exits responsive preview mode.
		 *

		 * @method exit
		 */
		exit: function() {
			this.destroy();
		},

		/**
		 * Highlight menu bar device icon on switch modes
		 *

		 * @param {String} mode
		 * @method switchTo
		 */
		activatePreviewMenuBarDeviceIcon: function( mode ) {

			const cheetahPreviewActionsDevices = $('.ba-cheetah--preview-actions .ba-preview-devices')

			// inactive
			const cbActionsInactive = cheetahPreviewActionsDevices.find('.ba-cheetah-button:not([data-mode="' + mode + '"])');
			cbActionsInactive.removeClass('ba-device-active')

			// active
			const cbActionActive = cheetahPreviewActionsDevices.find('.ba-cheetah-button[data-mode="' + mode + '"]');
			cbActionActive.addClass('ba-device-active')
		},

		/**
		 * Switch to a different device preview size.
		 *

		 * @param {String} mode
		 * @method switchTo
		 */
		switchTo: function( mode ) {
			var settings = BACheetahConfig.global,
				frame	 = $( '#ba-cheetah-preview-frame' ),
				width 	 = '100%';

			if ( 'responsive' == mode ) {
				width = ( '1' !== settings.responsive_preview && settings.responsive_breakpoint >= 360 ) ? 360 : settings.responsive_breakpoint;
				frame.width( width );
			} else if ( 'medium' == mode ) {
				width = ( '1' !== settings.responsive_preview && settings.medium_breakpoint >= 769 ) ? 769 : settings.medium_breakpoint;
				frame.width( width );
			}

			frame.width( width );
		},

		/**
		 * Renders the iframe for previewing the layout.
		 *

		 * @method render
		 */
		render: function( mode ) {
			var body	= $( 'body' ),
				src 	= BACheetahConfig.previewUrl,
				last	= $( '#ba-cheetah-preview-mask, #ba-cheetah-preview-frame' ),
				mask	= $( '<div id="ba-cheetah-preview-mask"></div>' ),
				size	= $( '<span class="ba-cheetah-preview-size"></span>')
				frame 	= $( '<iframe id="ba-cheetah-preview-frame" src="' + src + '"></iframe>' );
				wrap 	= $( '<div class="ba-preview-container">"' )

			last.remove();
			wrap.append( size )
			wrap.append( frame );
			body.css( 'overflow', 'hidden' );
			body.append(mask)
			body.append(wrap)
		},

		_showSize: function(mode) {
				var show_size = $('.ba-cheetah-preview-size' ),
				medium = ( '1' === BACheetahConfig.global.responsive_preview ) ? BACheetahConfig.global.medium_breakpoint : 769,
				responsive = ( '1' === BACheetahConfig.global.responsive_preview ) ? BACheetahConfig.global.responsive_breakpoint : 360,
				size_text = '';

			if ( 'responsive' === mode ) {
					size_text = BACheetahStrings.mobile + ' ' + responsive + 'px';
			} else if ( 'medium' === mode ) {
				size_text = BACheetahStrings.medium + ' ' + medium + 'px';
			}

			show_size.html('').html(size_text)
		},

		/**
		 * Removes the iframe for previewing the layout.
		 *

		 * @method destroy
		 */
		destroy: function() {
			$( '.ba-preview-container, #ba-cheetah-preview-mask' ).remove();
			$( 'body' ).css( 'overflow', 'visible' );
		},
	}
} )( jQuery );
