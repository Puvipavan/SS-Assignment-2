(function($) {

	BACheetahMegaMenu = function( node, isBuilderActive = false){

		this.menu 			= $(".ba-node-" + node + ".ba-module__mega-menu");
		this.menu_nav		= this.menu.find('.mega-menu__nav');
		this.menu_nav_items	= this.menu.find('.mega-menu__nav .mega-menu__nav__item');
		this.menu_content	= this.menu.find('.mega-menu__content')
		this.index			= null

		this._initCollapses()

		if (isBuilderActive) {
			this._activateMenuItemByIndex(0)
		}
	};

	BACheetahMegaMenu.prototype = {

		_initCollapses: function() {
			this._initDropdownItems()
		},

		_activateMenuItemByIndex(index) {

			const menu_item_active = this.menu_nav.find(`.mega-menu__nav__item[data-index=${index}]`);

			const is_mobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

			this.menu_nav_items.removeClass('mega-menu__nav__item--active')

			if (this.index !== index) {
				this.menu_content.children().hide()
				menu_item_active.addClass('mega-menu__nav__item--active')
				if (is_mobile) menu_item_active.siblings().hide()
			} else {
				if (is_mobile) menu_item_active.siblings().show()
			}

			this.index = index
			this.menu_content.find('#' + menu_item_active.attr('data-target')).toggle()
		},

		_activeteMenuItem(target) {
			if (!target.find('a').length) {
				this._activateMenuItemByIndex(target.attr('data-index'))
			}
		},

		_initDropdownItems: function() {
			const self = this;
			this.menu_nav_items.on('click', function(e) {
				self._activeteMenuItem($(this))
			})
		}

	};

})(jQuery);
