( function( $ ) {

	/**
	 * Helper for pinning the builder UI to the
	 * sides of the browser window.
	 *

	 * @class PinnedUI
	 */
	var PinnedUI = {

		minWidth: 320,

		maxWidth: 600,

		minHeight: 400,

		/**

		 * @method init
		 */
		init: function() {
			this.initPanel();
			this.pinOrUnpin();
			this.bind();
		},

		/**

		 * @method bind
		 */
		bind: function() {
			var win  = $( window ),
				body = $( 'body' );

			win.on( 'resize', _.throttle( this.windowResize.bind( this ), 250 ) );

			body.delegate( '.ba-cheetah-ui-pinned-collapse', 'click', this.collapse );
			body.delegate( '.ba-cheetah--content-library-panel .ba-cheetah--tabs', 'click', this.closeLightboxOnPanelClick );

			BACheetah.addHook( 'didShowLightbox', this.pinLightboxOnOpen.bind( this ) );
			BACheetah.addHook( 'didHideAllLightboxes', this.pinnedLightboxClosed.bind( this ) );
			BACheetah.addHook( 'endEditingSession', this.hide.bind( this ) );
			BACheetah.addHook( 'didHideEditingUI', this.hide.bind( this ) );
			BACheetah.addHook( 'publishButtonClicked', this.hide.bind( this ) );
			BACheetah.addHook( 'restartEditingSession', this.show.bind( this ) );
			BACheetah.addHook( 'didShowEditingUI', this.show.bind( this ) );
			BACheetah.addHook( 'didShowLightbox', this.uncollapse.bind(this) );
			BACheetah.addHook( 'willShowContentPanel', this.uncollapse.bind(this) );
			BACheetah.addHook( 'willShowContentPanel', this.closeLightboxOnPanelClick.bind(this) );
		},

		/**
		 * Checks to see if the UI is currently pinned or not.
		 *

		 * @method isPinned
		 * @return {Boolean}
		 */
		isPinned: function() {
			return $( '.ba-cheetah--content-library-panel' ).hasClass( 'ba-cheetah-ui-pinned' );
		},

		/**
		 * Pins the UI.
		 *

		 * @method pin
		 * @param {String} position
		 * @param {Boolean} savePosition
		 */
		pin: function( position, savePosition ) {
			this.pinPanel( position );
			this.pinLightboxes();

			if ( savePosition ) {
				this.savePosition();
			}

			BACheetah._resizeLayout();
			BACheetah.triggerHook( 'didPinContentPanel' );
		},

		/**
		 * Unpins the UI.
		 *

		 * @method unpin
		 * @param {Boolean} savePosition
		 */
		unpin: function( savePosition ) {
			this.unpinLightboxes();
			this.unpinPanel();

			if ( savePosition ) {
				this.savePosition();
			}

			BACheetah._resizeLayout();
			BACheetah.triggerHook( 'didUnpinContentPanel' );
		},

		/**
		 * Pins or unpins the UI based on the window size.
		 *

		 * @method pinOrUnpin
		 */
		pinOrUnpin: function()
		{
			var panel  = $( '.ba-cheetah--content-library-panel' ),
				pinned = this.isPinned();

			if ( panel.hasClass( 'ba-cheetah-ui-pinned-hidden' ) ) {
				return;
			} else if ( window.innerWidth <= this.maxWidth ) {
				if ( pinned ) {
					this.unpin( false );
				}
				this.disableDragAndResize();
			} else {
				if ( ! pinned ) {
					this.restorePosition();
				}
				this.enableDragAndResize();
			}
		},

		/**
		 * Shows the pinned UI if it has been hidden.
		 *

		 * @method show
		 */
		show: function()
		{
			var panel = $( '.ba-cheetah--content-library-panel' );

			if ( panel.hasClass( 'ba-cheetah-ui-pinned-hidden' ) ) {
				panel.removeClass( 'ba-cheetah-ui-pinned-hidden' );
				panel.show();
				this.restorePosition();
			}
		},

		/**
		 * Hides pinned lightboxes without unpinning them.
		 *

		 * @method hide
		 */
		hide: function()
		{
			var body  = $( 'body' ),
				panel = $( '.ba-cheetah--content-library-panel' );

			if ( this.isPinned() ) {
				this.uncollapse();
				panel.addClass( 'ba-cheetah-ui-pinned-hidden' );
				panel.hide();
				body.css( 'margin', '' );
				BACheetah._resizeLayout();
			}
		},

		/**
		 * Collapse all pinned UI elements.
		 *

		 * @method collapse
		 */
		collapse: function()
		{
			var button   = $( this ).find('i:visible'),
				body     = $( 'body' ),
				toggle   = button.data( 'toggle' ),
				position = button.data( 'position' ),
				panel 	 = $( '.ba-cheetah--content-library-panel' ),
				width    = panel.outerWidth();

			if ( 'hide' === toggle ) {
				panel.css( position, '-' + width + 'px' );
				body.css( 'margin-' + position, '' );
				body.addClass( 'ba-cheetah-ui-pinned-is-collapsed' );
			} else {
				panel.css( position, '0px' );
				body.css( 'margin-' + position, width + 'px' );
				body.removeClass( 'ba-cheetah-ui-pinned-is-collapsed' );
			}
		},

		/**
		 * Uncollapse all pinned UI elements.
		 *

		 * @method uncollapse
		 */
		uncollapse: function()
		{
			if ( this.isCollapsed() ) {
				$( '.ba-cheetah-ui-pinned-collapse:visible' ).trigger( 'click' );
			}
		},

		/**
		 * Return whether or not the panel is currently collapsed
		 *

		 * @method isCollapsed
		 */
		isCollapsed: function() {
			return $('body').hasClass('ba-cheetah-ui-pinned-is-collapsed');
		},

		/**
		 * Initializes pinning for the main content panel.
		 *

		 * @method initPanel
		 */
		initPanel: function() {
			var panel = $( '.ba-cheetah--content-library-panel' );

			panel.draggable( {
				cursor		: 'move',
				handle		: '.ba-cheetah--tabs',
				cancel		: '.ba-cheetah--tabs button',
				scroll		: false,
				drag		: this.drag.bind( this ),
				stop		: this.dragStop.bind( this ),
				start		: this.dragStart.bind( this ),
			} ).resizable( {
				handles		: 'e, w',
				minHeight	: this.minHeight,
				minWidth	: this.minWidth,
				maxWidth	: this.maxWidth,
				start		: this.resizeStart.bind( this ),
				stop		: this.resizeStop.bind( this )
			} );

			panel.addClass( 'ba-cheetah-ui-pinned-container' );
			panel.find( '.ui-resizable-e, .ui-resizable-w' ).hide();
		},

		/**
		 * Pins the main content panel.
		 *

		 * @method pinPanel
		 * @param {String} position
		 */
		pinPanel: function( position ) {
			var panel 	= $( '.ba-cheetah--content-library-panel' ),
				width   = panel.outerWidth(),
				body  	= $( 'body' ),
				preview = $( '.ba-cheetah-responsive-preview, .ba-cheetah-responsive-preview-mask' );
				content = $( BACheetah._contentClass ).parentsUntil( 'body' ).last();

			body.addClass( 'ba-cheetah-ui-is-pinned ba-cheetah-ui-is-pinned-' + position );
			body.addClass( 'ba-cheetah-content-panel-is-showing' );
			body.css( 'margin-' + position, width + 'px' );
			preview.css( 'margin-' + position, width + 'px' );

			// See BACheetah::open_content_wrapper
			if (!$('.ba-cheetah-ui-pinned-content-transform').length) {
				content.addClass( 'ba-cheetah-ui-pinned-content-transform' )
			}
			
			panel.addClass( 'ba-cheetah-ui-pinned ba-cheetah-ui-pinned-' + position );
			panel.find( '.ui-resizable-' + ( 'left' === position ? 'e' : 'w' ) ).show();
			panel.on( 'resize', _.throttle( this.resize.bind( this ), 250 ) );
			panel.attr( 'style', '' );
			BACheetah.ContentPanel.isShowing = true;
		},

        /**
		 * Unpins the main content panel.
         *

         * @method unpinPanel
         */
		unpinPanel: function() {
			var panel   = $( '.ba-cheetah--content-library-panel' ),
				tab 	= panel.find( '.ba-cheetah--panel-content .is-showing' ).data( 'tab' ),
				body    = $( 'body' ),
				preview = $( '.ba-cheetah-responsive-preview, .ba-cheetah-responsive-preview-mask' ),
				content = $( BACheetah._contentClass ).parentsUntil( 'body' ).last();

			body.css( 'margin-left', '' );
			body.css( 'margin-right', '' );
			body.removeClass( 'ba-cheetah-ui-is-pinned' );
			body.removeClass( 'ba-cheetah-ui-is-pinned-left' );
			body.removeClass( 'ba-cheetah-ui-is-pinned-right' );
			preview.css( 'margin-left', '' );
			preview.css( 'margin-right', '' );
			content.removeClass( 'ba-cheetah-lightbox-content-transform' );
			panel.removeClass( 'ba-cheetah-ui-pinned' );
			panel.removeClass( 'ba-cheetah-ui-pinned-left' );
			panel.removeClass( 'ba-cheetah-ui-pinned-right' );
			panel.find( '.ui-resizable-handle' ).hide();
			panel.off( 'resize' );
			panel.attr( 'style', '' );
			panel.find( '.ba-cheetah--tabs [data-tab=' + tab + ']' ).addClass( 'is-showing' );
        },

		/**
		 * Pins all open lightboxes.
		 *

		 * @method pinLightboxes
		 */
		pinLightboxes: function() {
			var self = this;

			$( '.ba-cheetah-lightbox-resizable' ).each( function() {
				self.pinLightbox( $( this ) );
			} );

			BACheetah._reinitEditorFields();
		},

		/**
		 * Pins a single lightbox.
		 *

		 * @method pinLightbox
		 * @param {Object} lightbox
		 */
		pinLightbox: function( lightbox ) {
			var panel   = $( '.ba-cheetah--content-library-panel' ),
				wrapper = lightbox.closest( '.ba-cheetah-lightbox-wrap' );

			if ( ! wrapper.closest( '.ba-cheetah-ui-pinned' ).length ) {
				panel.append( wrapper );
				lightbox.attr( 'style', '' );
				lightbox.draggable( 'disable' );
				lightbox.resizable( 'disable' );
			}

			if ( lightbox.is( ':visible' ) ) {
				panel.find( '.ba-cheetah--tabs .is-showing' ).removeClass( 'is-showing' );
			}
		},

		/**
		 * Pins a lightbox when it opens if it's not already pinned.
		 *

		 * @method pinLightboxOnOpen
		 * @param {Object} e
		 * @param {BACheetahLightbox} boxObject
		 */
		pinLightboxOnOpen: function( e, boxObject ) {
			var lightbox = boxObject._node.find( '.ba-cheetah-lightbox-resizable' );

			if ( ! lightbox.length ) {
				return;
			}

			if ( ! lightbox.hasClass( 'ba-cheetah-ui-pinning-initialized' ) ) {
				lightbox.draggable( 'option', 'start', this.dragStart.bind( this ) );
				lightbox.draggable( 'option', 'drag', this.drag.bind( this ) );
				lightbox.draggable( 'option', 'stop', this.dragStop.bind( this ) );
				lightbox.addClass( 'ba-cheetah-ui-pinning-initialized' );
			}

			if ( this.isPinned() ) {
				this.pinLightbox( lightbox );
			}

			BACheetah.addHook( 'responsive-editing-switched', this.resize );
		},

		/**
		 * Handles a pinned lightbox closing.
		 *

		 * @method pinnedLightboxClosed
		 */
		pinnedLightboxClosed: function() {
			var panel = $( '.ba-cheetah--content-library-panel' )
				tab   = null;

			if ( this.isPinned() ) {
				tab = panel.find( '.ba-cheetah--panel-content .is-showing' ).data( 'tab' );
				panel.find( '.ba-cheetah--tabs [data-tab=' + tab + ']' ).addClass( 'is-showing' );
			}

			$( '.bal-cheetah-lightbox' ).removeClass( 'ba-cheetah-lightbox-prevent-animation' );
		},

		/**
		 * Unpins all pinned lightboxes.
		 *

		 * @method unpinLightboxes
		 */
		unpinLightboxes: function() {
			var body  = $( 'body' ),
				panel = $( '.ba-cheetah--content-library-panel' );

			panel.find( '.ba-cheetah-lightbox-wrap' ).each( function() {
				var wrapper  = $( this ),
					lightbox = wrapper.find( '.bal-cheetah-lightbox' ),
					top		 = 0,
					left	 = 0,
					right	 = 0;

				lightbox.draggable( 'enable' );
				lightbox.resizable( 'enable' );
				lightbox.find( '.ui-resizable-handle' ).show();
				body.append( wrapper );

				if ( lightbox.is( ':visible' ) ) {
					top = parseInt( panel.css( 'top' ) ) - parseInt( wrapper.css( 'top' ) ) - parseInt( wrapper.css( 'padding-top' ) );
					left = parseInt( panel.css( 'left' ) ) - parseInt( wrapper.css( 'padding-left' ) );
					right = parseInt( panel.css( 'right' ) ) - parseInt( wrapper.css( 'padding-right' ) );

					lightbox.css( 'top', ( top < 0 ? 0 : top ) + 'px' );
					lightbox.css( ( BACheetahConfig.isRtl ? 'right' : 'left' ), ( BACheetahConfig.isRtl ? right : left ) + 'px' );
					lightbox.addClass( 'ba-cheetah-lightbox-prevent-animation' );
					body.removeClass( 'ba-cheetah-content-panel-is-showing' );
					BACheetah.ContentPanel.isShowing = false;
				} else {
					lightbox.css( {
						top  : '25px',
						left : '25px',
					} );
				}
			} );

			BACheetah._reinitEditorFields();
		},

		/**
		 * Closes lightboxes when a panel tab is clicked.
		 *

		 * @method closeLightboxOnPanelClick
		 */
		closeLightboxOnPanelClick: function() {
			BACheetah._triggerSettingsSave( false, true );
		},

		/**
		 * Unpins if pinned when the window is resized down to a
		 * small device size.
		 *

		 * @method windowResize
		 */
		windowResize: function()
		{
			this.pinOrUnpin();
		},

		/**
		 * Callback for when content panel resize starts.
		 *

		 * @method resizeStart
		 */
		resizeStart: function()
		{
			$( 'body' ).addClass( 'ba-cheetah-resizable-is-resizing' );

			BACheetah._destroyOverlayEvents();
			BACheetah._removeAllOverlays();
		},

		/**
		 * Callback for when content panel resizes.
		 *

		 * @method resize
		 */
		resize: function()
		{
			var body   	  = $( 'body' ),
				preview   = $( '.ba-cheetah-responsive-preview, .ba-cheetah-responsive-preview-mask' ),
				panel 	  = $( '.ba-cheetah--content-library-panel' ),
				width     = panel.outerWidth();

			if ( ! panel.is( ':visible' ) ) {
				body.css( 'margin', '' );
			} else if ( panel.hasClass( 'ba-cheetah-ui-pinned-left' ) ) {
				body.css( 'margin-left', width + 'px' );
				preview.css( 'margin-left', width + 'px' );
			} else if ( panel.hasClass( 'ba-cheetah-ui-pinned-right' ) ) {
				body.css( 'margin-right', width + 'px' );
				preview.css( 'margin-right', width + 'px' );
			}
		},

		/**
		 * Callback for when content panel resize stops.
		 *

		 * @method resizeStop
		 */
		resizeStop: function()
		{
			$( 'body' ).removeClass( 'ba-cheetah-resizable-is-resizing' );

			BACheetah._bindOverlayEvents();
			BACheetah._resizeLayout();
			this.savePosition();
		},

		/**
		 * Callback for when content panel drag starts.
		 *

		 * @method dragStart
		 */
		dragStart: function( e, ui )
		{
			var body 	= $( 'body' ),
				target  = $( e.target ),
				actions = $( '.ba-cheetah-bar-actions' );

			if ( ! $( '.ba-cheetah-lightbox-resizable:visible' ).length ) {
				actions.addClass( 'ba-cheetah-content-panel-pin-zone' );
			}

			body.addClass( 'ba-cheetah-draggable-is-dragging' );
			body.append( '<div class="ba-cheetah-ui-pin-zone ba-cheetah-ui-pin-zone-left"></div>' );
			body.append( '<div class="ba-cheetah-ui-pin-zone ba-cheetah-ui-pin-zone-right"></div>' );
			BACheetah._destroyOverlayEvents();
		},

		/**
		 * Callback for when content panel is dragged.
		 *

		 * @method drag
		 */
		drag: function( e, ui )
		{
			var body   	  = $( 'body' ),
				preview   = $( '.ba-cheetah-responsive-preview' ),
				win 	  = $( window ),
				winWidth  = preview.length ? preview.width() : win.width(),
				scrollTop = win.scrollTop(),
				panel     = $( '.ba-cheetah--content-library-panel' ),
				offsetTop = panel.offset().top,
				actions   = $( '.ba-cheetah-bar-actions' ),
				target    = $( e.target );

			if ( target.hasClass( 'ba-cheetah--content-library-panel' ) ) {
				if ( e.clientX < winWidth - 75 && offsetTop - scrollTop < 46 ) {
					actions.addClass( 'ba-cheetah-content-panel-pin-zone-hover' );
				} else {
					actions.removeClass( 'ba-cheetah-content-panel-pin-zone-hover' );
				}
			}

			if ( target.hasClass( 'ba-cheetah-ui-pinned' ) ) {
				this.unpinPanel();
			} else if ( e.clientX < 75 ) {
				body.addClass( 'ba-cheetah-ui-show-pin-zone ba-cheetah-ui-show-pin-zone-left' );
			} else if ( e.clientX > winWidth - 75 ) {
				body.addClass( 'ba-cheetah-ui-show-pin-zone ba-cheetah-ui-show-pin-zone-right' );
			} else {
				body.removeClass( 'ba-cheetah-ui-show-pin-zone' );
				body.removeClass( 'ba-cheetah-ui-show-pin-zone-left' );
				body.removeClass( 'ba-cheetah-ui-show-pin-zone-right' );
			}
		},

		/**
		 * Callback for when content panel drag stops.
		 *

		 * @method dragStop
		 */
		dragStop: function( e, ui )
		{
			var win      = $( window ),
				body     = $( 'body' ),
				actions  = $( '.ba-cheetah-bar-actions' ),
				zones    = $( '.ba-cheetah-ui-pin-zone' ),
				panel    = $( '.ba-cheetah--content-library-panel' ),
				lightbox = $( '.ba-cheetah-lightbox-resizable:visible' ),
				target   = $( e.target );

			body.removeClass( 'ba-cheetah-draggable-is-dragging' );
			actions.removeClass( 'ba-cheetah-content-panel-pin-zone' );
			actions.removeClass( 'ba-cheetah-content-panel-pin-zone-hover' );
			zones.remove();

			if ( lightbox.length && parseInt( lightbox.css( 'top' ) ) < 0 ) {
				lightbox.css( 'top', '0' );
			}

			if ( body.hasClass( 'ba-cheetah-ui-show-pin-zone' ) ) {
				if ( body.hasClass( 'ba-cheetah-ui-show-pin-zone-left' ) ) {
					this.pin( 'left', true );
				} else {
					this.pin( 'right', true );
				}
				body.removeClass( 'ba-cheetah-ui-show-pin-zone' );
				body.removeClass( 'ba-cheetah-ui-show-pin-zone-left' );
				body.removeClass( 'ba-cheetah-ui-show-pin-zone-right' );
			} else if( panel.find( '.bal-cheetah-lightbox' ).length ) {
				this.unpin( true );
				if ( 'module' === BACheetahConfig.userTemplateType || BACheetahConfig.simpleUi ) {
					panel.hide();
				}
			} else {
				panel.attr( 'style', '' );
				this.savePosition();
			}

			BACheetah._bindOverlayEvents();
		},

		/**
		 * Disables draggable and resizable.
		 *

		 * @method disableDragAndResize
		 */
		disableDragAndResize: function() {
			var panel 		= $( '.ba-cheetah--content-library-panel' ),
				lightboxes 	= $( '.ba-cheetah-lightbox-resizable' );

			panel.draggable( 'disable' );
			panel.resizable( 'disable' );

			lightboxes.draggable( 'disable' );
			lightboxes.resizable( 'disable' );
		},

		/**
		 * Enables draggable and resizable.
		 *

		 * @method enableDragAndResize
		 */
		enableDragAndResize: function() {
			var panel 		= $( '.ba-cheetah--content-library-panel' ),
				lightboxes 	= $( '.ba-cheetah-lightbox-resizable:not(.ba-cheetah-lightbox-width-full)' );

			panel.draggable( 'enable' );
			panel.resizable( 'enable' );

			if ( ! this.isPinned() ) {
				lightboxes.draggable( 'enable' );
				lightboxes.resizable( 'enable' );
			}
		},

		/**
		 * Save the position data for the pinned UI.
		 *

		 * @method savePosition
		 */
		savePosition: function()
		{
			var panel 	 = $( '.ba-cheetah--content-library-panel' ),
				lightbox = $( '.ba-cheetah-lightbox-resizable:visible' ),
				data  	 = {
					pinned: {
						width  	 : panel.outerWidth(),
						position : null
					}
				};

			if ( panel.hasClass( 'ba-cheetah-ui-pinned-left' ) ) {
				data.pinned.position = 'left';
			} else if ( panel.hasClass( 'ba-cheetah-ui-pinned-right' ) ) {
				data.pinned.position = 'right';
			} else if ( lightbox.length ) {
				data.lightbox = {
					width  	: lightbox.width(),
					height 	: lightbox.height(),
					top  	: parseInt( lightbox.css( 'top' ) ) < 0 ? '0px' : lightbox.css( 'top' ),
					left  	: lightbox.css( 'left' )
				};
			}

			BACheetahConfig.userSettings.pinned = data.pinned;

			if ( data.lightbox ) {
				BACheetahConfig.userSettings.lightbox = data.lightbox;
			}

			BACheetah.ajax( {
				action : 'save_pinned_ui_position',
				data   : data
			} );
		},

		/**
		 * Restores the pinned UI position for the current user.
		 *

		 * @method restorePosition
		 */
		restorePosition: function()
		{
			var panel    = $( '.ba-cheetah--content-library-panel' ),
				settings = BACheetahConfig.userSettings.pinned;

			if ( settings && settings.position ) {
				panel.css( 'width', settings.width + 'px' );
				this.pin( settings.position, false );
				panel.css( 'width', settings.width + 'px' );
			}
		},
	};

	$( function() {
		PinnedUI.init();
	} );

} )( jQuery );
