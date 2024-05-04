(function($){

	/**
	 * Helper class for dealing with the builder's admin
	 * settings page.
	 *
	 * @class BACheetahAdminProUser

	 */
    BACheetahAdminProUser = {

        /**
        * Initializes the builder's admin settings page.
        *

        * @method init
        */
        init: function()
        {
            this._bind();
        },
        
        /**
		 * Binds events for the builder's admin settings page.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			
			$('.button-token .has-token').on('click', function() {
				BACheetahAdminProUser._showHideElements('token', false);
			});
			
			$('.btn-force-level').on('click', function() {
				BACheetahAdminProUser._showHideElements('force-check-level', false);
			});

			$('.btn-back-force-level').on('click', function() {
				BACheetahAdminProUser._showHideElements('button-token');
			});
			
		},
		
		/**
		* Show one element and hide the others
		*
 
		* @access private
		* @method _showHideElements
		*/
		 _showHideElements: function(element, showSubTitle = true)
		 {
			 $('.pro-block').hide();
			 if(showSubTitle) {
				$('.sub-title-pro').show();
			 }
			 $('.' + element).show();
		 },

    };

    /* Initializes the pro user script */
    $(function(){
        BACheetahAdminProUser.init();
    });

})(jQuery);