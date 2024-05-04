( function( $ ) {

	/**

	 * @class BACheetahExport
	 */
	BACheetahExport = {

		/**
		 * Initializes custom exports for the builder.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			$( '#export-filters input[value=ba-cheetah-template]' ).on( 'change', BACheetahExport._showTemplateFilters );
			$( '#export-filters input[value=ba-cheetah-theme-layout]' ).on( 'change', BACheetahExport._showTemplateFilters );
			$( '#ba-cheetah-template-export-select' ).on( 'change', BACheetahExport._templateSelectChange );
		},

		/**
		 * Shows the template filters when the template radio
		 * button is clicked.
		 *

		 * @access private
		 * @method _showTemplateFilters
		 */
		_showTemplateFilters: function()
		{
			var filters = $( '#ba-cheetah-template-filters' );

			filters.find( 'select' ).val( 'all' );
			$( this ).closest( 'p' ).after( filters );
			$( '#ba-cheetah-template-export-posts' ).hide();
			filters.slideDown();
		},

		/**
		 * Called when the template select is changed and shows
		 * all templates to select from when the value is set
		 * to selected.
		 *

		 * @access private
		 * @method _templateSelectChange
		 */
		_templateSelectChange: function()
		{
			var filter  = $( '#ba-cheetah-template-filters' ),
				posts   = $( '#ba-cheetah-template-export-posts' ),
				spinner = filter.find( '.spinner' );

			if ( 'all' == $( this ).val() ) {
				spinner.removeClass( 'is-active' );
				posts.hide();
			}
			else {

				posts.empty();
				posts.show();
				spinner.addClass( 'is-active' );

				$.post( ajaxurl, {
					action: 'ba_cheetah_export_templates_data',
					type: $( 'input[name=content]:checked' ).val(),
					_wpnonce: window.ba_cheetah_export_nonce
				}, BACheetahExport._templateDataLoaded );
			}
		},

		/**
		 * Called when the template data is loaded.
		 *

		 * @access private
		 * @method _templateDataLoaded
		 */
		_templateDataLoaded: function( response )
		{
			var filter  	= $( '#ba-cheetah-template-filters' ),
				posts   	= $( '#ba-cheetah-template-export-posts' ),
				spinner 	= filter.find( '.spinner' ),
				data 		= JSON.parse( response ),
				i			= 0;

			for ( i in data ) {
				posts.append( '<p><label><input type="checkbox" name="ba-cheetah-export-template[]" value="' + data[ i ].id + '" /> ' + data[ i ].title + '</label></p>' );
			}

			spinner.removeClass( 'is-active' );
		}
	};

	$( BACheetahExport._init );

} )( jQuery );
