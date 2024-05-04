( function( $ ) {

	/**
	 * Manages undo/redo history for the builder.
	 */
	BACheetahHistoryManager = {

		/**
		 * Array of change state labels.
		 */
		states: [],

		/**
		 * Array index for the current state.
		 */
		position: 0,

		/**
		 * Whether a state is currently rendering or not.
		 */
		rendering: false,

		/**
		 * Initializes hooks for saving state when changes
		 * in the builder are made.
		 */
		init: function() {
			var config = BACheetahConfig.history
			var self = this

			this.states = config.states
			this.position = parseInt( config.position )
			this.setupMainMenuData()

			$.each( config.hooks, function( hook, label ) {
				BACheetah.addHook( hook, function( e, data ) {
					self.saveCurrentState( label, data )
				} )
			} )

			BACheetah.addHook( 'didPublishLayout', this.clearStatesOnPublish.bind( this ) )
			BACheetah.addHook( 'restartEditingSession', this.saveCurrentStateOnRestartSession.bind( this ) )
			BACheetah.addHook( 'historyItemClicked', this.itemClicked.bind( this ) )
			BACheetah.addHook( 'undo', this.onUndo.bind( this ) )
			BACheetah.addHook( 'redo', this.onRedo.bind( this ) )
		},

		/**
		 * Makes a request to save the current layout state.
		 */
		saveCurrentState: function( label, data ) {
			var self = this
			var data = 'undefined' === typeof data ? {} : data
			var moduleType = null

			if ( 'undefined' !== typeof data.moduleType && data.moduleType ) {
				moduleType = data.moduleType
			}

			BACheetah.ajax( {
				action: 'save_history_state',
				label: label,
				module_type: moduleType,
			}, function( response ) {
				var data = JSON.parse( response )
				self.states = data.states
				self.position = parseInt( data.position )
				self.setupMainMenuData()
			} )
		},

		/**
		 * Makes a request to save the current state when restarting
		 * the builder editing session if no states exist.
		 */
		saveCurrentStateOnRestartSession: function( e ) {
			if ( this.states.length ) {
				return
			}

			this.saveCurrentState( 'draft_created' )
		},

		/**
		 * Makes a request to clear all states for the current layout
		 * when publishing and exiting the builder.
		 */
		clearStatesOnPublish: function( e, data ) {
			var self = this

			this.states = []
			this.position = 0
			this.setupMainMenuData()

			BACheetah.ajax( {
				action: 'clear_history_states',
				post_id: BACheetahConfig.postId,
			}, function() {
				if ( ! data.shouldExit ) {
					self.saveCurrentState( 'draft_created' )
				}
			} )
		},

		/**
		 * Makes a request to render a layout state with
		 * the specified position.
		 */
		renderState: function( position ) {
			var self = this

			if ( this.rendering || ! this.states.length ) {
				return
			}
			if ( $( '.ba-cheetah-settings:visible' ).length ) {
				return
			}

			var timeout = setTimeout( BACheetah.showAjaxLoader, 2000 )
			this.rendering = true

			BACheetah.ajax( {
				action: 'render_history_state',
				position: position,
			}, function( response ) {
				var data = JSON.parse( response )
				if ( ! data.error ) {
					self.position = parseInt( data.position )
					BACheetah.triggerHook( 'didRestoreHistoryComplete', data )
					BACheetah._renderLayout( data.layout )
					self.setupMainMenuData()
				}
				clearTimeout( timeout )
				self.rendering = false
			} )
		},

		/**
		 * Renders the previous state.
		 */
		onUndo: function() {
			this.renderState( 'prev' )
		},

		/**
		 * Renders the next state.
		 */
		onRedo: function() {
			this.renderState( 'next' )
		},

		/**
		 * Adds history states to the main menu data.
		 */
		setupMainMenuData: function() {
			var labels = BACheetahConfig.history.labels
			var label = ''
			BACheetahConfig.mainMenu.history.items = []

			for ( var i = this.states.length - 1; i >= 0; i-- ) {

				if ( 'string' === typeof this.states[ i ] ) {
					label = labels[ this.states[ i ] ] ? labels[ this.states[ i ] ] : this.states[ i ]
				} else {
					label = labels[ this.states[ i ].label ] ? labels[ this.states[ i ].label ] : this.states[ i ].label

					if ( this.states[ i ].moduleType || this.states[ i ].label.indexOf( 'module' ) > -1 ) {
						label = label.replace( '%s', this.getModuleName( this.states[ i ].moduleType ) )
					}
				}

				BACheetahConfig.mainMenu.history.items.push( {
					eventName : 'historyItemClicked',
					type      : 'event',
					label     : wp.template( 'ba-cheetah-history-list-item' )( {
						label 		: label,
						current		: i === this.position ? 1 : 0,
						position    : i,
					} )
				} )
			}

			if ( ! BACheetahConfig.history.enabled ) {
				BACheetahConfig.mainMenu.history.items.push( {
					eventName : 'historyItemClicked',
					type      : 'event',
					label     : wp.template( 'ba-cheetah-history-list-item' )( {
						label 		: BACheetahConfig.history.labels.history_disabled,
						current		: 0,
						position    : 0,
					} )
				} )
			}

			if ( undefined !== BACheetah.MainMenu ) {
				BACheetah.MainMenu.renderPanel( 'history' )
			}

			/**
			 * Control the ui bar button states
			 */
			const canRedo = this.position < (this.states.length - 1)
			$('.ba-navbar-actions .ba-cheetah-button[data-action=redo]').prop('disabled', !canRedo)

			const canUndo = this.position > 0
			$('.ba-navbar-actions .ba-cheetah-button[data-action=undo]').prop('disabled', !canUndo)
		},

		/**
		 * Returns a module's name by passing the type.
		 */
		getModuleName: function( type ) {
			var modules = BACheetahConfig.contentItems.module
			var i = 0

			if ( 'widget' === type ) {
				return BACheetahStrings.widget
			}

			for ( ; i < modules.length; i++ ) {
				if ( 'undefined' === typeof modules[ i ].slug ) {
					continue
				}
				if ( type === modules[ i ].slug ) {
					return modules[ i ].name
				}
			}

			return BACheetahStrings.module
		},

		/**
		 * Callback for when a history item in the tools
		 * menu is clicked to render that state.
		 */
		itemClicked: function( e, item ) {
			var button = $( item ).find( '.ba-cheetah-history-list-item' )
			var position = button.attr( 'data-position' )
			var current = $( '.ba-cheetah-history-list-item[data-current=1]' )

			if ( $( '.ba-cheetah-settings:visible' ).length ) {
				BACheetah._closeNestedSettings()
				BACheetah._lightbox.close()
			}

			current.attr( 'data-current', 0 )
			button.attr( 'data-current', 1 )

			this.renderState( position )
		},
	}

	$( function() { BACheetahHistoryManager.init() } )

} )( jQuery );
