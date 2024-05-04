(function ($) {
	
	BACheetah._registerModuleHelper('menu', {

		/**
		 * The 'init' method is called by the builder when 
		 * the settings form is opened.
		 *
		 * @method init
		 */
		init: function () {
			this._previewDropdowns()
			this._getMenus()
		},

		_getMenus: function() {
			BACheetah.getSelectOptions('get_menus', 'menu');
		},

		/**
		 * Automatically opens the first submenu while a submenu style setting is being made
		 *
		 * @method init
		 */
		_previewDropdowns: function() {
			const form 		= $('.ba-cheetah-settings:visible');
			const tabStyle 	= form.find('#ba-cheetah-settings-tab-style');
			const nodeId 	= form.attr('data-node')
			const that 		= this;

			tabStyle.find('.ba-cheetah-settings-section-header').on('click', function() {
				const section = $(this).closest('.ba-cheetah-settings-section')
				const isSettingDropdownRelated = section.attr('id').startsWith('ba-cheetah-settings-section-submenu')
				if (isSettingDropdownRelated && section.hasClass('ba-cheetah-settings-section-collapsed')) {
					that._openDropdown(nodeId)
				} else {
					that._closeDropdown(nodeId)
				}
			})
		},

		_openDropdown: function(nodeId) {
			$('.ba-cheetah-content-editing')
				.find('.ba-module__menu.ba-node-' + nodeId + ' .menu-item-has-children')
				.addClass('menu__item--open')
		},

		_closeDropdown: function(nodeId) {
			const element = $('.ba-cheetah-content-editing')
				.find('.ba-module__menu.ba-node-' + nodeId + ' .menu-item-has-children')
				.removeClass('menu__item--open')
		}

		
	});

})(jQuery);