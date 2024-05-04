( function( $ ) {
	
	/**
	 * Handles logic for the user templates admin add new interface.
	 *
	 * @class BACheetahUserTemplatesAdminAdd

	 */
	BACheetahUserTemplatesAdminAdd = {
		
		/**
		 * Initializes the user templates admin add new interface.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			this._bind();
		},

		/**
		 * Binds events for the Add New form.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$( 'select.ba-cheetah-template-type' ).on( 'change', this._templateTypeChange );
			$( 'form.ba-cheetah-new-template-form .dashicons-editor-help' ).tipTip();
			$( 'form.ba-cheetah-new-template-form' ).validate();
			
			this._templateTypeChange();
		},

		/**
		 * Callback for when the template type select changes.
		 *

		 * @access private
		 * @method _templateTypeChange
		 */
		_templateTypeChange: function()
		{
			var val    = $( 'select.ba-cheetah-template-type' ).val(),
				module = $( '.ba-cheetah-template-module-row' ),
				global = $( '.ba-cheetah-template-global-row' ),
				add    = $( '.ba-cheetah-template-add' );
			
			module.toggle( 'module' == val );
			global.toggle( ( 'row' == val || 'module' == val ) );
			
			add.val( BACheetahConfig.strings.addButton.add );
	
		}
	};
	
	// Initialize
	$( function() { BACheetahUserTemplatesAdminAdd._init(); } );

} )( jQuery );