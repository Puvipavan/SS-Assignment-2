( function( $ ) {

	/**
	 * Helper for handling responsive editing logic.
	 *

	 * @class BACheetahResponsiveEditing
	 */
	BACheetahResponsiveEditing = {

		/**
		 * The current editing mode we're in.
		 *

		 * @private
		 * @property {String} _mode
		 */
		_mode: 'default',

		/**
		 * Refreshes the media queries for the responsive preview
		 * if necessary.
		 *

		 * @method refreshPreview
		 * @param {Function} callback
		 */
		refreshPreview: function( callback )
		{
			var width;

			if ( $( '.ba-cheetah-responsive-preview' ).length && 'default' !== this._mode ) {

				if ( 'responsive' == this._mode ) {
					width = BACheetahConfig.global.responsive_breakpoint >= 320 ? 320 : BACheetahConfig.global.responsive_breakpoint;
					BACheetahSimulateMediaQuery.update( width, callback );
				}
				else if ( 'medium' == this._mode ) {
					width = BACheetahConfig.global.medium_breakpoint >= 769 ? 769 : BACheetahConfig.global.medium_breakpoint;
					BACheetahSimulateMediaQuery.update( width, callback );
				}

				BACheetah._resizeLayout();

			} else if ( callback ) {
				callback();
			}
		},

		/**
		 * Initializes responsive editing.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			this._bind();
			this._initMediaQueries();
		},

		/**
		 * Bind events.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			BACheetah.addHook( 'endEditingSession', this._clearPreview );
			BACheetah.addHook( 'didEnterRevisionPreview', this._clearPreview );
			BACheetah.addHook( 'responsiveEditing', this._menuToggleClicked );
			BACheetah.addHook( 'preview-init', this._switchAllSettingsToCurrentMode );
			BACheetah.addHook( 'responsive-editing-switched', this._showSize );

			$( 'body' ).delegate( '.ba-cheetah-field-responsive-toggle', 'click', this._settingToggleClicked );
			$( 'body' ).delegate( '.ba-cheetah-responsive-preview-message button', 'click', this._previewToggleClicked );
		},

		/**
		 * Initializes faux media queries.
		 *

		 * @access private
		 * @method _initMediaQueries
		 */
		_initMediaQueries: function()
		{
			// Don't simulate media queries for stylesheets that match these paths.
			BACheetahSimulateMediaQuery.ignore(
				[
					BACheetahConfig.pluginUrl,
					BACheetahConfig.relativePluginUrl
				]
			)

			BACheetahSimulateMediaQuery.ignore( BACheetahConfig.responsiveIgnore );

			// Reparse stylesheets that match these paths on each update.
			BACheetahSimulateMediaQuery.reparse( [
				BACheetahConfig.postId + '-layout-draft.css',
				BACheetahConfig.postId + '-layout-draft-partial.css',
				BACheetahConfig.postId + '-layout-preview.css',
				BACheetahConfig.postId + '-layout-preview-partial.css',
				'ba-cheetah-global-css',
				'ba-cheetah-layout-css'
			] );
		},

		_showSize: function() {
				var show_size = $('.ba-cheetah-responsive-preview-message .size' ),
				medium = ( '1' === BACheetahConfig.global.responsive_preview ) ? BACheetahConfig.global.medium_breakpoint : 769,
				responsive = ( '1' === BACheetahConfig.global.responsive_preview ) ? BACheetahConfig.global.responsive_breakpoint : 360,
				size_text = '';

			if ( $('.ba-cheetah-responsive-preview').hasClass('ba-cheetah-preview-responsive') ) {
					size_text = BACheetahStrings.mobile + ' ' + responsive + 'px';
			} else if ( $('.ba-cheetah-responsive-preview').hasClass('ba-cheetah-preview-medium') ) {
				size_text = BACheetahStrings.medium + ' ' + medium + 'px';
			}

			show_size.html('').html(size_text)
		},

		/**
		 * Switches to either mobile, tablet or desktop editing.
		 *

		 * @access private
		 * @method _switchTo
		 */
		_switchTo: function( mode, callback )
		{
			var html		= $( 'html' ),
				body        = $( 'body' ),
				content     = $( BACheetah._contentClass ),
				preview     = $( '.ba-cheetah-responsive-preview' ),
				mask        = $( '.ba-cheetah-responsive-preview-mask' ),
				placeholder = $( '.ba-cheetah-content-placeholder' ),
				width       = null;

			// Save the new mode.
			BACheetahResponsiveEditing._mode = mode;
			BACheetahResponsiveEditing._switchAllSettingsTo(mode)

			// Setup the preview.
			if ( 'default' == mode ) {

				if ( 0 === placeholder.length ) {
					return;
				}

				html.removeClass( 'ba-cheetah-responsive-preview-enabled' );
				placeholder.after( content );
				placeholder.remove();
				preview.remove();
				mask.remove();
			}
			else if ( 0 === preview.length ) {
				html.addClass( 'ba-cheetah-responsive-preview-enabled' );
				content.after( '<div class="ba-cheetah-content-placeholder"></div>' );
				body.prepend( wp.template( 'ba-cheetah-responsive-preview' )() );
				$( '.ba-cheetah-responsive-preview' ).addClass( 'ba-cheetah-preview-' + mode );
				$( '.ba-cheetah-responsive-preview-content' ).append( content );
			}
			else {
				preview.removeClass( 'ba-cheetah-preview-responsive ba-cheetah-preview-medium' );
				preview.addClass( 'ba-cheetah-preview-' + mode  );
			}

			// Set the content width and apply media queries.
			if ( 'responsive' == mode ) {
				width = ( '1' !== BACheetahConfig.global.responsive_preview && BACheetahConfig.global.responsive_breakpoint >= 360 ) ? 360 : BACheetahConfig.global.responsive_breakpoint;
				content.width( width );
				BACheetahSimulateMediaQuery.update( width, callback );
				BACheetahResponsiveEditing._setMarginPaddingPlaceholders();
			}
			else if ( 'medium' == mode ) {
				width = ( '1' !== BACheetahConfig.global.responsive_preview && BACheetahConfig.global.medium_breakpoint >= 769 ) ? 769 : BACheetahConfig.global.medium_breakpoint;
				content.width( width );
				BACheetahSimulateMediaQuery.update( width, callback );
				BACheetahResponsiveEditing._setMarginPaddingPlaceholders();
			}
			else {
				content.width( '' );
				BACheetahSimulateMediaQuery.update( null, callback );
			}

			// Set the content background color.
			this._setContentBackgroundColor();

			// Resize the layout.
			BACheetah._resizeLayout();

			// Preview all responsive settings.
			this._previewFields();

			// Active navbar button
			this._activeNavbarMode(mode)

			// Broadcast the switch.
			BACheetah.triggerHook( 'responsive-editing-switched', mode );
		},

		/**
		 * Active mode button in cheetah navbar
		 *
		 * @access private
		 * @method _activeNavbarMode
		 */
		_activeNavbarMode: function(mode)
		{
			const cheetahNavbarActionsDevices = $('.ba-navbar-actions .ba-navbar-devices')

			// inactive
			const cbActionsInactive = cheetahNavbarActionsDevices.find('.ba-cheetah-button:not([data-mode="' + mode + '"])');
			cbActionsInactive.removeClass('ba-device-active')

			// active
			const cbActionActive = cheetahNavbarActionsDevices.find('.ba-cheetah-button[data-mode="' + mode + '"]');
			cbActionActive.addClass('ba-device-active')
		},

		/**
		 * Sets the background color for the builder content
		 * in a responsive preview.
		 *

		 * @access private
		 * @method _setContentBackgroundColor
		 */
		_setContentBackgroundColor: function()
		{
			var content     = $( BACheetah._contentClass ),
				preview     = $( '.ba-cheetah-responsive-preview' ),
				placeholder = $( '.ba-cheetah-content-placeholder' ),
				parents     = placeholder.parents(),
				parent      = null,
				color       = '#fff',
				i           = 0;

			if ( 0 === preview.length ) {
				content.css( 'background-color', '' );
			}
			else {

				for( ; i < parents.length; i++ ) {

					color = parents.eq( i ).css( 'background-color' );

					if ( color != 'rgba(0, 0, 0, 0)' ) {
						break;
					}
				}

				content.css( 'background-color', color );
			}
		},

		/**
		 * Switches to the given mode and scrolls to an
		 * active node if one is present.
		 *

		 * @access private
		 * @method _switchToAndScroll
		 */
		_switchToAndScroll: function( mode )
		{
			var nodeId  = $( '.ba-cheetah-settings' ).data( 'node' ),
				element = undefined === nodeId ? undefined : $( '.ba-cheetah-node-' + nodeId );

			BACheetahResponsiveEditing._switchTo( mode, function() {

				if ( undefined !== element && element ) {

						var win     = $( window ),
							content = $( '.ba-cheetah-responsive-preview-content' );

						if ( content.length ) {
							content.scrollTop( 0 );
							content.scrollTop( element.offset().top - 150 );
						} else {
							$( 'html, body' ).scrollTop( element.offset().top - 100 );
						}
				}
			} );
		},

		/**
		 * Switches all responsive settings in a settings form
		 * to the given mode.
		 *

		 * @access private
		 * @method _switchAllSettingsTo
		 * @param {String} mode
		 */
		_switchAllSettingsTo: function( mode )
		{
			var iconName = '';

			$( '.ba-cheetah-field-responsive-setting' ).hide();

			if ( 'default' == mode ) {
				iconName = '#ba-cheetah-icon--desktop';
			}
			else if ( 'medium' == mode ) {
				iconName = '#ba-cheetah-icon--tablet';
			}
			else {
				iconName = '#ba-cheetah-icon--smartphone';
			}

			const toggleWrapper = $( '.ba-cheetah-field-responsive-toggle' )
			
			toggleWrapper.data( 'mode', mode );
			toggleWrapper.find('svg use').attr('xlink:href', iconName)

			$( '.ba-cheetah-field-responsive-setting-' + mode ).css( 'display', 'inline-block' );
		},

		/**
		 * Switches all responsive settings in a settings form
		 * to the current mode.
		 *

		 * @access private
		 * @method _switchAllSettingsToCurrentMode
		 */
		_switchAllSettingsToCurrentMode: function()
		{
			var self = BACheetahResponsiveEditing;

			self._switchAllSettingsTo( self._mode );

			BACheetah.triggerHook( 'responsive-editing-switched', self._mode );
		},

		/**
		 * Set Placeholders for Padding and Margin
		 *

		 * @access private
		 * @method _setMarginPaddingPlaceholders
		 */
		_setMarginPaddingPlaceholders: function()
		{
			var paddingDefaultID    = '#ba-cheetah-field-padding .ba-cheetah-field-responsive-setting-default',
				paddingDefault      = {
					'values'        : {
						'top'       : $( paddingDefaultID + ' input[ name="padding_top" ]').val(),
						'right'     : $( paddingDefaultID + ' input[ name="padding_right" ]').val(),
						'bottom'    : $( paddingDefaultID + ' input[ name="padding_bottom" ]').val(),
						'left'      : $( paddingDefaultID + ' input[ name="padding_left" ]').val(),
					},
					'placeholders'  : {
						'top'       : $( paddingDefaultID + ' input[ name="padding_top" ]').attr('placeholder'),
						'right'     : $( paddingDefaultID + ' input[ name="padding_right" ]').attr('placeholder'),
						'bottom'    : $( paddingDefaultID + ' input[ name="padding_bottom" ]').attr('placeholder'),
						'left'      : $( paddingDefaultID + ' input[ name="padding_left" ]').attr('placeholder'),
					}
				},
				paddingMediumID     = '#ba-cheetah-field-padding .ba-cheetah-field-responsive-setting-medium',
				paddingMedium       = {
					'values'        : {
						'top'       : $( paddingMediumID + ' input[ name="padding_top_medium" ]').val(),
						'right'     : $( paddingMediumID + ' input[ name="padding_right_medium" ]').val(),
						'bottom'    : $( paddingMediumID + ' input[ name="padding_bottom_medium" ]').val(),
						'left'      : $( paddingMediumID + ' input[ name="padding_left_medium" ]').val(),
					},
					'placeholders'  : {
						'top'       : '',
						'right'     : '',
						'bottom'    : '',
						'left'      : '',
					}
				},
				paddingResponsiveID  = '#ba-cheetah-field-padding .ba-cheetah-field-responsive-setting-responsive',
				paddingResponsive    = {
					'values'        : {
						'top'       : $( paddingMediumID + ' input[ name="padding_top_responsive" ]').val(),
						'right'     : $( paddingMediumID + ' input[ name="padding_right_responsive" ]').val(),
						'bottom'    : $( paddingMediumID + ' input[ name="padding_bottom_responsive" ]').val(),
						'left'      : $( paddingMediumID + ' input[ name="padding_left_responsive" ]').val(),
					},
					'placeholders'  : {
						'top'       : '',
						'right'     : '',
						'bottom'    : '',
						'left'      : '',
					}
				},
				marginDefaultID     = '#ba-cheetah-field-margin .ba-cheetah-field-responsive-setting-default',
				marginDefault       = {
					'values'        : {
						'top'       : $( marginDefaultID + ' input[ name="margin_top" ]').val(),
						'right'     : $( marginDefaultID + ' input[ name="margin_right" ]').val(),
						'bottom'    : $( marginDefaultID + ' input[ name="margin_bottom" ]').val(),
						'left'      : $( marginDefaultID + ' input[ name="margin_left" ]').val(),
					},
					'placeholders'  : {
						'top'       : $( marginDefaultID + ' input[ name="margin_top" ]').attr('placeholder'),
						'right'     : $( marginDefaultID + ' input[ name="margin_right" ]').attr('placeholder'),
						'bottom'    : $( marginDefaultID + ' input[ name="margin_bottom" ]').attr('placeholder'),
						'left'      : $( marginDefaultID + ' input[ name="margin_left" ]').attr('placeholder'),
					}
				},
				marginMediumID      = '#ba-cheetah-field-margin .ba-cheetah-field-responsive-setting-medium',
				marginMedium        = {
					'values'        : {
						'top'       : $( marginMediumID + ' input[ name="margin_top_medium" ]').val(),
						'right'     : $( marginMediumID + ' input[ name="margin_right_medium" ]').val(),
						'bottom'    : $( marginMediumID + ' input[ name="margin_bottom_medium" ]').val(),
						'left'      : $( marginMediumID + ' input[ name="margin_left_medium" ]').val(),
					},
					'placeholders'	: {
						'top'       : marginDefault.values.top ? marginDefault.values.top : marginDefault.placeholders.top,
						'right'     : marginDefault.values.right ? marginDefault.values.right : marginDefault.placeholders.right,
						'bottom'    : marginDefault.values.bottom ? marginDefault.values.bottom : marginDefault.placeholders.bottom,
						'left'      : marginDefault.values.left ? marginDefault.values.left : marginDefault.placeholders.left,
					}
				},
				marginResponsiveID  = '#ba-cheetah-field-margin .ba-cheetah-field-responsive-setting-responsive',
				marginResponsive    = {
					'values'        : {
						'top'       : $( marginResponsiveID + ' input[ name="margin_top_responsive" ]').val(),
						'right'     : $( marginResponsiveID + ' input[ name="margin_right_responsive" ]').val(),
						'bottom'    : $( marginResponsiveID + ' input[ name="margin_bottom_responsive" ]').val(),
						'left'      : $( marginResponsiveID + ' input[ name="margin_left_responsive" ]').val(),
					},
					'placeholders'  : {
						'top'       : '',
						'right'     : '',
						'bottom'    : '',
						'left'      : '',
					}
				};

			// --- Set Padding Placeholders (Medium)---
			// -- top --
			if ( '' != paddingDefault.values.top ) {
				$( paddingMediumID + ' input[ name="padding_top_medium"] ').attr( 'placeholder', paddingDefault.values.top );
			} else {
				$( paddingMediumID + ' input[ name="padding_top_medium"] ').attr( 'placeholder', paddingDefault.placeholders.top );
			}

			// -- right --
			if ( '' != paddingDefault.values.right ) {
				$( paddingMediumID + ' input[ name="padding_right_medium"] ').attr( 'placeholder', paddingDefault.values.right );
			} else {
				$( paddingMediumID + ' input[ name="padding_right_medium"] ').attr( 'placeholder', paddingDefault.placeholders.right );
			}

			// -- bottom --
			if ( '' != paddingDefault.values.bottom ) {
				$( paddingMediumID + ' input[ name="padding_bottom_medium"] ').attr( 'placeholder', paddingDefault.values.bottom );
			} else {
				$( paddingMediumID + ' input[ name="padding_bottom_medium"] ').attr( 'placeholder', paddingDefault.placeholders.bottom );
			}

			// -- left --
			if ( '' != paddingDefault.values.left ) {
				$( paddingMediumID + ' input[ name="padding_left_medium"] ').attr( 'placeholder', paddingDefault.values.left );
			} else {
				$( paddingMediumID + ' input[ name="padding_left_medium"] ').attr( 'placeholder', paddingDefault.placeholders.left );
			}

			// --- Set Padding Placeholders (Responsive) ---
			// -- top --
			if ( '' != paddingMedium.values.top ) {
				$( paddingResponsiveID + ' input[ name="padding_top_responsive"] ').attr( 'placeholder', paddingMedium.values.top );
			} else if ( '' != paddingDefault.values.top ) {
				$( paddingResponsiveID + ' input[ name="padding_top_responsive"] ').attr( 'placeholder', paddingDefault.values.top );
			} else {
				$( paddingResponsiveID + ' input[ name="padding_top_responsive"] ').attr( 'placeholder', paddingDefault.placeholders.top );
			}

			// -- right --
			if ( '' != paddingMedium.values.right ) {
				$( paddingResponsiveID + ' input[ name="padding_right_responsive"] ').attr( 'placeholder', paddingMedium.values.right );
			} else if ( '' != paddingDefault.values.right ) {
				$( paddingResponsiveID + ' input[ name="padding_right_responsive"] ').attr( 'placeholder', paddingDefault.values.right );
			} else {
				$( paddingResponsiveID + ' input[ name="padding_right_responsive"] ').attr( 'placeholder', paddingDefault.placeholders.right );
			}

			// -- bottom --
			if ( '' != paddingMedium.values.bottom ) {
				$( paddingResponsiveID + ' input[ name="padding_bottom_responsive"] ').attr( 'placeholder', paddingMedium.values.bottom );
			} else if ( '' != paddingDefault.values.bottom ) {
				$( paddingResponsiveID + ' input[ name="padding_bottom_responsive"] ').attr( 'placeholder', paddingDefault.values.bottom );
			} else {
				$( paddingResponsiveID + ' input[ name="padding_bottom_responsive"] ').attr( 'placeholder', paddingDefault.placeholders.bottom );
			}

			// -- left --
			if ( '' != paddingMedium.values.left ) {
				$( paddingResponsiveID + ' input[ name="padding_left_responsive"] ').attr( 'placeholder', paddingMedium.values.left );
			} else if ( '' != paddingDefault.values.left ) {
				$( paddingResponsiveID + ' input[ name="padding_left_responsive"] ').attr( 'placeholder', paddingDefault.values.left );
			} else {
				$( paddingResponsiveID + ' input[ name="padding_left_responsive"] ').attr( 'placeholder', paddingDefault.placeholders.left );
			}

			// --- Set Margin Placeholders (Medium) ---
			// -- top --
			if ( '' != marginDefault.values.top ) {
				$( marginMediumID + ' input[ name="margin_top_medium" ]').attr( 'placeholder', marginDefault.values.top );
			} else {
				$( marginMediumID + ' input[ name="margin_top_medium" ]').attr( 'placeholder', marginDefault.placeholders.top );
			}

			// -- right --
			if ( '' != marginDefault.values.right ) {
				$( marginMediumID + ' input[ name="margin_right_medium" ]').attr( 'placeholder', marginDefault.values.right );
			} else {
				$( marginMediumID + ' input[ name="margin_right_medium" ]').attr( 'placeholder', marginDefault.placeholders.right );
			}

			// -- bottom --
			if ( '' != marginDefault.values.bottom ) {
				$( marginMediumID + ' input[ name="margin_bottom_medium" ]').attr( 'placeholder', marginDefault.values.bottom );
			} else {
				$( marginMediumID + ' input[ name="margin_bottom_medium" ]').attr( 'placeholder', marginDefault.placeholders.bottom );
			}

			// -- left --
			if ( '' != marginDefault.values.left ) {
				$( marginMediumID + ' input[ name="margin_left_medium" ]').attr( 'placeholder', marginDefault.values.left );
			} else {
				$( marginMediumID + ' input[ name="margin_left_medium" ]').attr( 'placeholder', marginDefault.placeholders.left );
			}

			// --- Set Margin Placeholders (Responsive) ---
			// -- top --
			if ( '' != marginMedium.values.top ) {
				$( marginResponsiveID + ' input[ name="margin_top_responsive" ]').attr( 'placeholder', marginMedium.values.top );
			} else if ( '' != marginDefault.values.top ) {
				$( marginResponsiveID + ' input[ name="margin_top_responsive" ]').attr( 'placeholder', marginDefault.values.top );
			} else {
				$( marginResponsiveID + ' input[ name="margin_top_responsive" ]').attr( 'placeholder', marginDefault.placeholders.top );
			}

			// -- right --
			if ( '' != marginMedium.values.right ) {
				$( marginResponsiveID + ' input[ name="margin_right_responsive" ]').attr( 'placeholder', marginMedium.values.right );
			} else if ( '' != marginDefault.values.right ) {
				$( marginResponsiveID + ' input[ name="margin_right_responsive" ]').attr( 'placeholder', marginDefault.values.right );
			} else {
				$( marginResponsiveID + ' input[ name="margin_right_responsive" ]').attr( 'placeholder', marginDefault.placeholders.right );
			}

			// -- bottom --
			if ( '' != marginMedium.values.bottom ) {
				$( marginResponsiveID + ' input[ name="margin_bottom_responsive" ]').attr( 'placeholder', marginMedium.values.bottom );
			} else if ( '' != marginDefault.values.bottom ) {
				$( marginResponsiveID + ' input[ name="margin_bottom_responsive" ]').attr( 'placeholder', marginDefault.values.bottom );
			} else {
				$( marginResponsiveID + ' input[ name="margin_bottom_responsive" ]').attr( 'placeholder', marginDefault.placeholders.bottom );
			}

			// -- left --
			if ( '' != marginMedium.values.left ) {
				$( marginResponsiveID + ' input[ name="margin_left_responsive" ]').attr( 'placeholder', marginMedium.values.left );
			} else if ( '' != marginDefault.values.left ) {
				$( marginResponsiveID + ' input[ name="margin_left_responsive" ]').attr( 'placeholder', marginDefault.values.left );
			} else {
				$( marginResponsiveID + ' input[ name="margin_left_responsive" ]').attr( 'placeholder', marginDefault.placeholders.left );
			}

		},

		/**
		 * Callback for when the responsive toggle of a setting
		 * is clicked.
		 *

		 * @access private
		 * @method _settingToggleClicked
		 */
		_settingToggleClicked: function()
		{
			var toggle  = $( this ),
				mode    = toggle.data( 'mode' );

			if ( 'default' == mode ) {
				mode  = 'medium';
			}
			else if ( 'medium' == mode ) {
				mode  = 'responsive';
			}
			else {
				mode  = 'default';
			}

			BACheetahResponsiveEditing._switchAllSettingsTo( mode );
			BACheetahResponsiveEditing._switchToAndScroll( mode );

			toggle.siblings( '.ba-cheetah-field-responsive-setting:visible' ).find( 'input' ).focus();
		},

		/**
		 * Callback for when the main menu item is clicked.
		 *

		 * @access private
		 * @method _menuToggleClicked
		 */
		_menuToggleClicked: function()
		{
			var mode = BACheetahResponsiveEditing._mode;

			if ( 'default' == mode ) {
				mode = 'medium';
			} else if ( 'medium' == mode ) {
				mode = 'responsive';
			} else {
				mode = 'default';
			}

			BACheetah.MainMenu.hide();
			BACheetahResponsiveEditing._switchAllSettingsTo( mode );
			BACheetahResponsiveEditing._switchToAndScroll( mode );
		},

		/**
		 * Callback for when the switch buttons of the responsive
		 * preview header are clicked.
		 *

		 * @access private
		 * @method _previewToggleClicked
		 */
		_previewToggleClicked: function()
		{
			var mode = $( this ).data( 'mode' );
			console.log('aqui fioo')
			BACheetahResponsiveEditing._switchAllSettingsTo( mode );
			BACheetahResponsiveEditing._switchToAndScroll( mode );
		},

		/**
		 * Clears the responsive editing preview and reverts
		 * to the default view.
		 *

		 * @access private
		 * @method _clearPreview
		 */
		_clearPreview: function()
		{
			BACheetahResponsiveEditing._switchToAndScroll( 'default' );
		},

		/**
		 * Callback for when the responsive preview changes
		 * to live preview CSS for responsive fields.
		 *

		 * @access private
		 * @method _previewFields
		 */
		_previewFields: function()
		{
			var mode = BACheetahResponsiveEditing._mode,
				form = $( '.ba-cheetah-settings:visible' );

			if ( 0 === form.length || undefined === form.attr( 'data-node' ) ) {
				return;
			}

			BACheetah.triggerHook( 'responsive-editing-before-preview-fields', mode );

			form.find( '.ba-cheetah-settings-tab' ).each( function() {

				var tab = $( this );
				tab.css( 'display', 'block' );

				tab.find( '.ba-cheetah-field-responsive-setting-' + mode + ':visible' ).each( function() {

					var field = $( this ),
						parent = field.closest( '.ba-cheetah-field' ),
						type = parent.data( 'type' ),
						preview = parent.data( 'preview' ),
						hasConnection = parent.find( '.ba-cheetah-field-connection-visible' ).length;

					if ( 'refresh' == preview.type ) {
						return;
					}

					if ( hasConnection ) {
						if ( 'photo' === type && 'default' !== mode ) {
							field.find( '.ba-cheetah-photo-remove' ).trigger( 'click' );
						}
					} else{
						field.find( 'input' ).trigger( 'keyup' );
						field.find( 'select' ).trigger( 'change' );
					}
				} );

				tab.css( 'display', '' );
			} );

			BACheetah.triggerHook( 'responsive-editing-after-preview-fields', mode );
		},
	};

	$( function() { BACheetahResponsiveEditing._init() } );

} )( jQuery );
