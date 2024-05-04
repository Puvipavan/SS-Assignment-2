(function($){

	/**
	 * Custom lightbox for builder popups.
	 *
	 * @class BACheetahLightbox

	 */
	BACheetahLightbox = function( settings )
	{
		this._init( settings );
	};

	/**
	 * Closes the lightbox of a child element that
	 * is passed to this method.
	 *

	 * @static
	 * @method closeParent
	 * @param {Object} child An HTML element or jQuery reference to an element.
	 */
	BACheetahLightbox.closeParent = function( child )
	{
		var instanceId = $( child ).closest( '.ba-cheetah-lightbox-wrap' ).attr( 'data-instance-id' );

		if ( ! _.isUndefined( instanceId ) ) {
			BACheetahLightbox._instances[ instanceId ].close();
		}
	};

	/**
	 * Returns the classname for the resize control in lightbox headers.
	 *

	 * @static
	 * @method getResizableControlClass
	 * @return {String}
	 */
	BACheetahLightbox.getResizableControlClass = function()
	{
		var resizable = $( '.ba-cheetah-lightbox-resizable' ).eq( 0 ),
			className = 'far fa-window-maximize';

		if ( resizable.length && resizable.hasClass( 'ba-cheetah-lightbox-width-full' ) ) {
			className = 'far fa-window-minimize';
		}

		return className;
	};

	/**
	 * Unbinds events for all lightbox instances.
	 *

	 * @static
	 * @method unbindAll
	 */
	BACheetahLightbox.unbindAll = function()
	{
		var id;

		for ( id in BACheetahLightbox._instances ) {
			BACheetahLightbox._instances[ id ]._unbind();
		}
	};

	/**
	 * Binds events for all lightbox instances.
	 *

	 * @static
	 * @method bindAll
	 */
	BACheetahLightbox.bindAll = function()
	{
		var id;

		for ( id in BACheetahLightbox._instances ) {
			BACheetahLightbox._instances[ id ]._bind();
		}
	};

	/**
	 * Close all lightbox instances.
	 *

	 * @static
	 * @method closeAll
	 */
	BACheetahLightbox.closeAll = function()
	{
		var id;

		for ( id in BACheetahLightbox._instances ) {
			BACheetahLightbox._instances[ id ].close();
		}
	};

	/**
	 * An object that stores a reference to each
	 * lightbox instance that is created.
	 *

	 * @static
	 * @access private
	 * @property {Object} _instances
	 */
	BACheetahLightbox._instances = {};

	/**
	 * Prototype for new instances.
	 *

	 * @property {Object} prototype
	 */
	BACheetahLightbox.prototype = {

		/**
		 * A unique ID for this instance that's used to store
		 * it in the static _instances object.
		 *

		 * @access private
		 * @property {String} _id
		 */
		_id: null,

		/**
		 * A jQuery reference to the main wrapper div.
		 *

		 * @access private
		 * @property {Object} _node
		 */
		_node: null,

		/**
		 * Flag for whether the lightbox is visible or not.
		 *

		 * @access private
		 * @property {Boolean} _visible
		 */
		_visible: false,

		/**
		 * Whether closing the lightbox is allowed or not.
		 *

		 * @access private
		 * @property {Boolean} _allowClosing
		 */
		_allowClosing: true,

		/**
		 * A timeout used to throttle the resize event.
		 *

		 * @access private
		 * @property {Object} _resizeTimer
		 */
		_resizeTimer: null,

		/**
		 * Default config object.
		 *

		 * @access private
		 * @property {Object}  _defaults
		 * @property {String}  _defaults.className 		- A custom classname to add to the wrapper div.
		 * @property {Boolean} _defaults.destroyOnClose - Flag for whether the instance should be destroyed when closed.
		 * @property {Boolean} _defaults.resizable 		- Flag for Whether this instance should be resizable or not.
		 */
		_defaults: {
			className: '',
			destroyOnClose: false,
			resizable: false
		},

		/**
		 * Opens the lightbox. You can pass new content to this method.
		 * If no content is passed, the previous content will be shown.
		 *

		 * @method open
		 * @param {String} content HTML content to add to the lightbox.
		 */
		open: function(content)
		{
			var lightbox = this._node.find( '.bal-cheetah-lightbox' ),
				isPinned = ( lightbox.closest( '.ba-cheetah-ui-pinned' ).length ),
				settings = this._getPositionSettings();

			if ( ! isPinned && settings && this._defaults.resizable ) {
				lightbox.css( settings );
			}

			this._bind();
			this._node.show();
			this._visible = true;

			if(typeof content !== 'undefined') {
				this.setContent(content);
			}
			else {
				this._resize();
			}

			this.trigger('open');

			BACheetah.triggerHook('didShowLightbox', this);
		},

		/**
		 * Closes the lightbox.
		 *

		 * @method close
		 */
		close: function()
		{
			var parent = this._node.data('parent');

			if ( ! this._allowClosing ) {
				return;
			}

			this.trigger('beforeCloseLightbox');
			this._unbind();
			this._node.hide();
			this._visible = false;
			this.trigger('close');

			BACheetah.triggerHook('didHideLightbox');

			if ( this._defaults.resizable && _.isUndefined( parent ) ) {
				BACheetah.triggerHook('didHideAllLightboxes');
			}

			if(this._defaults.destroyOnClose) {
				this.destroy();
			}
		},

		/**
		 * Disables closing the lightbox.
		 *

		 * @method disableClose
		 */
		disableClose: function()
		{
			this._allowClosing = false;
		},

		/**
		 * Enables closing the lightbox.
		 *

		 * @method enableClose
		 */
		enableClose: function()
		{
			this._allowClosing = true;
		},

		/**
		 * Adds HTML content to the lightbox replacing any
		 * previously added content.
		 *

		 * @method setContent
		 * @param {String} content HTML content to add to the lightbox.
		 */
		setContent: function(content)
		{
			this._node.find('.ba-cheetah-lightbox-content').html(content);
			this._resize();
		},

		/**
		 * Uses the jQuery empty function to remove lightbox
		 * content and any related events.
		 *

		 * @method empty
		 */
		empty: function()
		{
			this._node.find('.ba-cheetah-lightbox-content').empty();
		},

		/**
		 * Bind an event to the lightbox.
		 *

		 * @method on
		 * @param {String} event The type of event to bind.
		 * @param {Function} callback A callback to fire when the event is triggered.
		 */
		on: function(event, callback)
		{
			this._node.on(event, callback);
		},

		/**
		 * Unbind an event from the lightbox.
		 *

		 * @method off
		 * @param {String} event The type of event to unbind.
		 * @param {Function} callback
		 */
		off: function(event, callback)
		{
			this._node.off(event, callback);
		},

		/**
		 * Trigger an event on the lightbox.
		 *

		 * @method trigger
		 * @param {String} event The type of event to trigger.
		 * @param {Object} params Additional parameters to pass to the event.
		 */
		trigger: function(event, params)
		{
			this._node.trigger(event, params);
		},

		/**
		 * Destroy the lightbox by removing all elements, events
		 * and object references.
		 *

		 * @method destroy
		 */
		destroy: function()
		{
			this._node.empty();
			this._node.remove();

			BACheetahLightbox._instances[this._id] = 'undefined';
			try{ delete BACheetahLightbox._instances[this._id]; } catch(e){}
		},

		/**
		 * Initialize this lightbox instance.
		 *

		 * @access private
		 * @method _init
		 * @param {Object} settings A setting object for this instance.
		 */
		_init: function(settings)
		{
			var i    = 0,
				prop = null;

			for(prop in BACheetahLightbox._instances) {
				i++;
			}

			this._defaults = $.extend({}, this._defaults, settings);
			this._id = new Date().getTime() + i;
			BACheetahLightbox._instances[this._id] = this;
			this._render();
			this._resizable();
		},

		/**
		 * Renders the main wrapper.
		 *

		 * @access private
		 * @method _render
		 */
		_render: function()
		{
			this._node = $( '<div class="ba-cheetah-lightbox-wrap" data-instance-id="'+ this._id +'"><div class="ba-cheetah-lightbox-mask"></div><div class="bal-cheetah-lightbox"><div class="ba-cheetah-lightbox-content-wrap"><div class="ba-cheetah-lightbox-content"></div></div></div></div>' );
			this._node.addClass( this._defaults.className );
			$( 'body' ).append( this._node );
		},

		/**
		 * Binds events for this instance.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( window ).on( 'resize.ba-cheetah-lightbox-' + this._id, this._delayedResize.bind( this ) );
		},

		/**
		 * Unbinds events for this instance.
		 *

		 * @access private
		 * @method _unbind
		 */
		_unbind: function()
		{
			$( window ).off( 'resize.ba-cheetah-lightbox-' + this._id );
		},

		/**
		 * Enable resizing for the lightbox.
		 *

		 * @method _resizable
		 */
		_resizable: function()
		{
			var body	  = $( 'body' ),
				mask      = this._node.find( '.ba-cheetah-lightbox-mask' ),
				lightbox  = this._node.find( '.bal-cheetah-lightbox' ),
				resizable = $( '.ba-cheetah-lightbox-resizable' ).eq( 0 );

			if ( this._defaults.resizable ) {

				mask.hide();
				lightbox.addClass( 'ba-cheetah-lightbox-resizable' );
				lightbox.delegate( '.ba-cheetah-lightbox-resize-toggle', 'click', this._resizeClicked.bind( this ) );

				lightbox.draggable( {
					cursor		: 'move',
					handle		: '.ba-cheetah-lightbox-header',
				} ).resizable( {
					handles		: 'all',
					minHeight	: 500,
					minWidth	: 380,
					start		: this._resizeStart.bind( this ),
					stop		: this._resizeStop.bind( this )
				} );

				if ( resizable.length && resizable.hasClass( 'ba-cheetah-lightbox-width-full' ) ) { // Setup nested
					lightbox.addClass( 'ba-cheetah-lightbox-width-full' );
					lightbox.draggable( 'disable' );
				} else { // Setup the main parent lightbox
					this._restorePosition();
				}
			}
			else {
				mask.show();
			}

			this._resize();
		},

		/**
		 * Resizes the lightbox after a delay.
		 *

		 * @access private
		 * @method _delayedResize
		 */
		_delayedResize: function()
		{
			clearTimeout( this._resizeTimer );

			this._resizeTimer = setTimeout( this._resize.bind( this ), 250 );
		},

		/**
		 * Resizes the lightbox.
		 *

		 * @access private
		 * @method _resize
		 */
		_resize: function()
		{
			var lightbox    = this._node.find( '.bal-cheetah-lightbox' ),
				boxTop      = parseInt( this._node.css( 'padding-top' ) ),
				boxBottom   = parseInt( this._node.css( 'padding-bottom' ) ),
				boxLeft     = parseInt( this._node.css( 'padding-left' ) ),
				boxRight    = parseInt( this._node.css( 'padding-right' ) ),
				boxHeight   = lightbox.height(),
				boxWidth    = lightbox.width(),
				win         = $( window ),
				winHeight   = win.height() - boxTop - boxBottom,
				winWidth    = win.width() - boxLeft - boxRight,
				top         = '0px';

			if ( ! this._defaults.resizable ) {
				if ( winHeight > boxHeight ) {
					top = '150px'; //( ( winHeight - boxHeight - 46 ) / 2 ) + 'px';
				}

				lightbox.attr( 'style', '' ).css( 'margin', top + ' auto 0' );
			}
			else {

				if ( boxWidth < 600 ) {
					lightbox.addClass( 'ba-cheetah-lightbox-width-slim' );
				} else {
					lightbox.removeClass( 'ba-cheetah-lightbox-width-slim' );
				}

				if ( boxWidth < 450 ) {
					lightbox.addClass( 'ba-cheetah-lightbox-width-micro' );
				} else {
					lightbox.removeClass( 'ba-cheetah-lightbox-width-micro' );
				}

				this._resizeEditors();
			}

			this.trigger( 'resized' );
		},

		/**
		 * Callback for when a user lightbox resize starts.
		 *

		 * @access private
		 * @method _resizeStart
		 */
		_resizeStart: function()
		{
			$( 'body' ).addClass( 'ba-cheetah-resizable-is-resizing' );
			$( '.ba-cheetah-lightbox:visible' ).append( '<div class="ba-cheetah-resizable-iframe-fix"></div>' );

			BACheetah._destroyOverlayEvents();
			BACheetah._removeAllOverlays();
		},

		/**
		 * Callback for when a user lightbox resize stops.
		 *

		 * @access private
		 * @method _resizeStop
		 */
		_resizeStop: function( e, ui )
		{
			var lightbox = $( '.ba-cheetah-lightbox-resizable:visible' );

			if ( parseInt( lightbox.css( 'top' ) ) < 0 ) {
				lightbox.css( 'top', '0' );
			}

			this._savePosition();

			$( 'body' ).removeClass( 'ba-cheetah-resizable-is-resizing' );
			$( '.ba-cheetah-resizable-iframe-fix' ).remove();

			BACheetah._bindOverlayEvents();
		},

		/**
		 * Resize to full or back to standard when the resize icon is clicked.
		 *

		 * @access private
		 * @method _expandLightbox
		 */
		_resizeClicked: function()
		{
			var lightboxes = $( '.ba-cheetah-lightbox-resizable' ),
				controls   = lightboxes.find( '.ba-cheetah-lightbox-resize-toggle' ),
				lightbox   = this._node.find( '.bal-cheetah-lightbox' );

			if ( lightbox.hasClass( 'ba-cheetah-lightbox-width-full' ) ) {
				this._resizeExitFull();
			} else {
				this._resizeEnterFull();
			}

			this._resize();
		},

		/**
		 * Resize to the full size lightbox.
		 *

		 * @access private
		 * @method _resizeEnterFull
		 */
		_resizeEnterFull: function()
		{
			var lightboxes = $( '.ba-cheetah-lightbox-resizable' ),
				controls   = lightboxes.find( '.ba-cheetah-lightbox-resize-toggle' ),
				lightbox   = this._node.find( '.bal-cheetah-lightbox' );

			controls.removeClass( 'fa-window-maximize' ).addClass( 'fa-window-minimize' );
			lightboxes.addClass( 'ba-cheetah-lightbox-width-full' );
			lightboxes.draggable( 'disable' );
			lightboxes.resizable( 'disable' );
		},

		/**
		 * Resize to the standard size lightbox.
		 *

		 * @access private
		 * @method _resizeEnterFull
		 */
		_resizeExitFull: function()
		{
			var lightboxes = $( '.ba-cheetah-lightbox-resizable' ),
				controls   = lightboxes.find( '.ba-cheetah-lightbox-resize-toggle' ),
				lightbox   = this._node.find( '.bal-cheetah-lightbox' );

			controls.removeClass( 'fa-window-minimize' ).addClass( 'fa-window-maximize' );
			lightboxes.removeClass( 'ba-cheetah-lightbox-width-full' );
			lightboxes.draggable( 'enable' );
			lightboxes.resizable( 'enable' );
		},

		/**
		 * Resizes text and code editor fields.
		 *

		 * @method _resizeEditors
		 */
		_resizeEditors: function()
		{
			$( '.ba-cheetah-lightbox-resizable' ).each( function() {

				var	lightbox 	 = $( this ),
					fieldsHeight = lightbox.find( '.ba-cheetah-settings-fields' ).height(),
					editors		 = lightbox.find( '.mce-edit-area > iframe, textarea.wp-editor-area, .ace_editor' ),
					editor 		 = null;

				if ( fieldsHeight < 350 ) {
					fieldsHeight = 350;
				}

				editors.each( function() {

					editor = $( this );

					if ( editor.hasClass( 'ace_editor' ) ) {
						editor.height( fieldsHeight - 60 );
						editor.closest( '.ba-cheetah-field' ).data( 'editor' ).resize();
					} else if ( editor.closest( '.mce-container-body' ).find( '.mce-toolbar-grp .mce-toolbar.mce-last' ).is( ':visible' ) ) {
						editor.height( fieldsHeight - 175 );
					} else {
						editor.height( fieldsHeight - 150 );
					}
				} );
			} );
		},

		/**
		 * Save the lightbox position for the current user.
		 *

		 * @access private
		 * @method _savePosition
		 */
		_savePosition: function()
		{
			var lightbox = this._node.find( '.bal-cheetah-lightbox' ),
				data     = {
					width  	: lightbox.width(),
					height 	: lightbox.height(),
					top  	: parseInt( lightbox.css( 'top' ) ) < 0 ? '0px' : lightbox.css( 'top' ),
					left  	: lightbox.css( 'left' )
				};

			if ( lightbox.closest( '.ba-cheetah-ui-pinned' ).length ) {
				return;
			}

			BACheetahConfig.userSettings.lightbox = data;

			BACheetah.ajax( {
				action : 'save_lightbox_position',
				data   : data
			} );
		},

		/**
		 * Restores the lightbox position for the current user.
		 *

		 * @access private
		 * @method _restorePosition
		 */
		_restorePosition: function()
		{
			var lightbox = this._node.find( '.bal-cheetah-lightbox' ),
				settings = this._getPositionSettings();
			if ( settings ) {
				lightbox.css( settings );
			} else {
				lightbox.css( {
					top  : 25,
					left : BACheetahConfig.isRtl ? '-' + 25 : 25
				} );
			}
		},

		/**
		 * Get the user settings for the lightbox position.
		 *
		 * Resize the height to 500px if the lightbox height is
		 * taller than the window and the window is taller than
		 * 546px (500px for lightbox min-height and 46px for the
		 * builder bar height).
		 *

		 * @access private
		 * @method _getPositionSettings
		 * @return {Object|Boolean}
		 */
		_getPositionSettings: function()
		{
			var settings = BACheetahConfig.userSettings.lightbox;

			if ( ! settings ) {
				return false;
			}
			var winHeight = window.innerHeight,
				height = parseInt( settings.height ),
				top    = parseInt( settings.top ),
				wleft  = parseInt( settings.left ),
				wtop   = parseInt( settings.top ),
				width  = parseInt( settings.width );

			// settings are off the screen to the right
			if( (wleft + width + 100) > screen.width ) {
				settings.left = screen.width - width - 250;
			}

			// settings are off the screen to the left
			if ( wleft < 0 ) {
				settings.left = 50;
			}
			if ( ( height > winHeight && winHeight > 546 ) || top + height > winHeight ) {
				if ( height > winHeight ) {
					settings.height = winHeight - 50;
				}
				settings.top = 0;
			}

			return settings;
		},
	};

})(jQuery);
