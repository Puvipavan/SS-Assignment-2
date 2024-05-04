( function( $ ) {

	/**
	 * Revisions manager for the builder.
	 *

	 * @class Revisions
	 */
	var Revisions = {

		/**
		 * Initialize builder revisions.
		 *

		 * @method init
		 */
		init: function()
		{
			this.setupMainMenuData();

			$( '.ba-cheetah--revision-actions select' ).on( 'change', this.selectChanged );
			$( '.ba-cheetah-cancel-revision-preview' ).on( 'click', this.exitPreview.bind( this ) );
			$( '.ba-cheetah-apply-revision-preview' ).on( 'click', this.applyClicked.bind( this ) );

			BACheetah.addHook( 'revisionItemClicked', this.itemClicked.bind( this ) );
			BACheetah.addHook( 'didPublishLayout', this.refreshItems.bind( this ) );
		},

		/**
		 * Adds the revision items to the main menu data.
		 *

		 * @method setupMainMenuData
		 */
		setupMainMenuData: function()
		{
			var posts    = BACheetahConfig.revisions.posts,
				authors  = BACheetahConfig.revisions.authors,
				template = wp.template( 'ba-cheetah-revision-list-item' ),
				select   = $( '.ba-cheetah--revision-actions select' ),
				date     = '',
				author   = '',
				i        = 0;

			BACheetahConfig.mainMenu.revisions.items = [];
			select.html( '' );

			if ( 0 === posts.length ) {

				BACheetahConfig.mainMenu.revisions.items.push( {
					eventName : 'noRevisionsMessage',
					type      : 'event',
					label     : wp.template( 'ba-cheetah-no-revisions-message' )()
				} );

			} else {

				for ( ; i < posts.length; i++ ) {

					date   = BACheetahStrings.revisionDate.replace( '%s', posts[ i ].date.diff );
					date  += ' (' + posts[ i ].date.published + ')';
					author = BACheetahStrings.revisionAuthor.replace( '%s', authors[ posts[ i ].author ].name );

					BACheetahConfig.mainMenu.revisions.items.push( {
						eventName : 'revisionItemClicked',
						type      : 'event',
						label     : template( {
							id          : posts[ i ].id,
							date 		: date,
							author 		: author,
							avatar		: authors[ posts[ i ].author ].avatar
						} )
					} );

					select.append( '<option value="' + posts[ i ].id + '">' + date + '</option>' );
				}
			}

			if ( undefined !== BACheetah.MainMenu ) {
				BACheetah.MainMenu.renderPanel( 'revisions' )
			}
		},

		/**
		 * Refreshes the items in the revisions menu.
		 *

		 * @method refreshItems
		 */
		refreshItems: function()
		{
			BACheetah.ajax( {
				action: 'refresh_revision_items'
			}, this.refreshItemsComplete.bind( this ) );
		},

		/**
		 * Re-renders the revision items when they have been refreshed.
		 *

		 * @method preview
		 * @param {Number} id
		 */
		refreshItemsComplete: function( response )
		{
			BACheetahConfig.revisions = BACheetah._jsonParse( response );

			this.setupMainMenuData();
		},

		/**
		 * Callback for when a revision item is clicked
		 * to preview a revision.
		 *

		 * @method itemClicked
		 * @param {Object} e
		 * @param {Object} item
		 */
		itemClicked: function( e, item )
		{
			var id = $( item ).find( '.ba-cheetah-revision-list-item' ).attr( 'data-revision-id' );

			// Save existing settings first if any exist. Don't proceed if it fails.
			if ( ! BACheetah._triggerSettingsSave( false, true ) ) {
				return;
			}

			$( '.ba-cheetah--revision-actions select' ).val( id );

			this.preview( id );
		},

		/**
		 * Callback for when the revision select is changed.
		 *

		 * @method selectChanged
		 * @param {Object} e
		 */
		selectChanged: function( e )
		{
			Revisions.preview( $( this ).val() );
		},

		/**
		 * Restores a revision when the apply button is clicked.
		 *

		 * @method applyClicked
		 * @param {Object} e
		 */
		applyClicked: function( e )
		{
			var id = $( '.ba-cheetah--revision-actions select' ).val();

			Revisions.restore( id );
		},

		/**
		 * Previews a revision with the specified ID.
		 *

		 * @method preview
		 * @param {Number} id
		 */
		preview: function( id )
		{
			$( '.ba-cheetah--revision-actions' ).css( 'display', 'flex' );
			BACheetah.triggerHook( 'didEnterRevisionPreview' );
			BACheetah.showAjaxLoader();

			BACheetah.ajax( {
				action		: 'render_revision_preview',
				revision_id : id
			}, this.previewRenderComplete.bind( this ) );
		},

		/**
		 * Previews a revision with the specified ID.
		 *

		 * @method previewRenderComplete
		 * @param {String} response
		 */
		previewRenderComplete: function( response )
		{
			BACheetah._renderLayout( response, function() {
				BACheetah._destroyOverlayEvents();
				BACheetah._removeAllOverlays();
			} );
		},

		/**
		 * Exits a revision preview and restores the original layout.
		 *

		 * @method exitPreview
		 */
		exitPreview: function()
		{
			$( '.ba-cheetah--revision-actions' ).hide();
			BACheetah.triggerHook( 'didExitRevisionPreview' );
			BACheetah._bindOverlayEvents();
			BACheetah._updateLayout();
		},

		/**
		 * Restores the layout to a revision with the specified ID.
		 *

		 * @method restore
		 * @param {Number} id
		 */
		restore: function( id )
		{
			$( '.ba-cheetah--revision-actions' ).hide();
			BACheetah.triggerHook( 'didExitRevisionPreview' );
			BACheetah.showAjaxLoader();
			BACheetah._bindOverlayEvents();

			BACheetah.ajax( {
				action		: 'restore_revision',
				revision_id : id
			}, Revisions.restoreComplete );
		},

		/**
		 * Callback for when a revision is restored.
		 *

		 * @method restoreComplete
		 * @param {String} response
		 */
		restoreComplete: function( response ) {
			var data = BACheetah._jsonParse( response );
			BACheetah._renderLayout( data.layout );
			BACheetah.triggerHook( 'didRestoreRevisionComplete', data.config );

			settings = data.settings
			if( typeof( settings.css ) != "undefined" && settings.css !== null) {
    		BACheetahSettingsConfig.settings.layout.css = settings.css
			}
			if( typeof( settings.js ) != "undefined" && settings.js !== null) {
				BACheetahSettingsConfig.settings.layout.js = settings.js
			}
		}

	};

	$( function() { Revisions.init(); } );

} )( jQuery );
