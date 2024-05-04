(function ($) {
	
	BACheetah._registerModuleHelper('posts', {

		/**
		 * The 'init' method is called by the builder when 
		 * the settings form is opened.
		 *
		 * @method init
		 */
		init: function () {
			const form 					= $('.ba-cheetah-settings:visible');
			const typeField				= form.find('select[name=post_type]');
			const typeInitialValue 		= typeField.val()
			const fieldSelectorPrefix 	= '.ba-cheetah-settings:visible #ba-cheetah-field-'
			
			// get selectors
			const productHiddenFields = ['display_date', 'display_time', 'display_author', 'display_comments']
			this.priceSelector = fieldSelectorPrefix + 'display_price'
			this.productHiddenFieldsSelectors = productHiddenFields.map(function(field) {
				return fieldSelectorPrefix + field
			}).join(',')
			
			// add event listener
			const self = this
			typeField.on('change', function(){
				self.toggleMetaFields($(this).val())
			})

			// init
			this.toggleMetaFields(typeInitialValue)
		},

		// some meta fields don't make sense for the product post type
		toggleMetaFields(type) {
			if (type == 'product') {
				$(this.productHiddenFieldsSelectors).hide()
				$(this.priceSelector).show()
			} else {
				$(this.productHiddenFieldsSelectors).show()
				$(this.priceSelector).hide()
			}
		}
	});

})(jQuery);