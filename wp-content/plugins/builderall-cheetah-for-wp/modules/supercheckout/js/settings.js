(function ($) {

	BACheetah._registerModuleHelper('supercheckout', {

		/**
		 * The 'init' method is called by the builder when 
		 * the settings form is opened.
		 *
		 * @method init
		 */
		init: function () {
			this.getSiteProducts();
		},

		rules: {
            product: {
                required: true
            },
        },

		getSiteProducts: function() {
			BACheetah.getSelectOptions('get_builderall_supercheckout_products', 'product')
		},
		
	});

})(jQuery);