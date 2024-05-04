( function( $ ) {
	
	/**
	 * Handles logic for the user templates admin edit interface.
	 *
	 * @class BACheetahUserTemplatesAdminEdit

	 */
	BACheetahUserTemplatesAdminEdit = {
		
		/**
		 * Initializes the user templates admin edit interface.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			this._setupPageTitle();
		},

		/**
		 * Adds to correct title to the edit screen and changes the 
		 * Add New button URL to point to our custom Add New page.
		 *

		 * @access private
		 * @method _setupPageTitle
		 */
		_setupPageTitle: function()
		{
			var button = $( '.page-title-action' ),
				url    = BACheetahConfig.addNewURL + '&ba-cheetah-template-type=' + BACheetahConfig.userTemplateType,
				h1     = $( '.wp-heading-inline' );
				
			h1.html( BACheetahConfig.pageTitle + ' ' ).append( button );
			button.attr( 'href', url ).show();
		},
	};
	
	// Initialize
	$( function() { BACheetahUserTemplatesAdminEdit._init(); } );

} )( jQuery );