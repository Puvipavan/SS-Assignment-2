(function($){

	BACheetah.registerModuleHelper('button', {

		init: function()
		{
			this._getPopups()
		},

		_getPopups: function() {
			BACheetah.getSelectOptions('get_popups', 'popup_id');
		}
	});

})(jQuery);