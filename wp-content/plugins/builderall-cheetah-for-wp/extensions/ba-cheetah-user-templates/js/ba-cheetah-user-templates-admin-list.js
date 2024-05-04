( function( $ ) {
	
	/**
	 * Handles logic for the user templates admin list interface.
	 *
	 * @class BACheetahUserTemplatesAdminList

	 */
	BACheetahUserTemplatesAdminList = {
		
		/**
		 * Initializes the user templates admin list interface.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			this._setupAddNewButton();
			this._setupSearch();
			this._fixCategories();
		},

		/**
		 * Changes the Add New button URL to point to our
		 * custom Add New page.
		 *

		 * @access private
		 * @method _setupSearch
		 */
		_setupAddNewButton: function()
		{
			var url = BACheetahConfig.addNewURL + '&ba-cheetah-template-type=' + BACheetahConfig.userTemplateType;
				
			$( '.page-title-action' ).attr( 'href', url ).show();
		},

		/**
		 * Adds a hidden input to the search for the user
		 * template type.
		 *

		 * @access private
		 * @method _setupSearch
		 */
		_setupSearch: function()
		{
			var type  = BACheetahConfig.userTemplateType,
				input = '<input type="hidden" name="ba-cheetah-template-type" value="' + type + '">'

			$( '.search-box' ).after( input );
		},

		_fixCategories: function() {
			$('.type-ba-cheetah-template').each( function( i,v ) {

				el = $(v).find('.taxonomy-ba-cheetah-template-category a');
				url = el.attr('href') + '&ba-cheetah-template-type=' + BACheetahConfig.userTemplateType
				el.attr('href', url)
			})
		}
	};

	// Initialize
	$( function() { BACheetahUserTemplatesAdminList._init(); } );

} )( jQuery );
