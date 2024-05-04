(function ($) {
	
	var 	$lists = []
	const 	$t = BACheetahStrings.mailingboss_form;

	BACheetah._registerModuleHelper('mailingboss', {

		/**
		 * The 'init' method is called by the builder when 
		 * the settings form is opened.
		 *
		 * @method init
		 */
		init: function () {
			this.getUserLists()
		},

		rules: {
            mailingboss_list: {
                required: true
            },
        },

		getUserLists: function() {
			const form 			= $( '.ba-cheetah-settings:visible');
			const urlField  	= form.find('input[name=url_subscribe]');

			BACheetah.getSelectOptions('get_builderall_mailingboss_lists', 'mailingboss_list', function(options, element) {
				element.on('change', function() {
					const newVal = options.find(o => o.value == $(this).val())
					urlField.val(newVal.url_subscribe)
				})
			})
		},
		
	});

})(jQuery);