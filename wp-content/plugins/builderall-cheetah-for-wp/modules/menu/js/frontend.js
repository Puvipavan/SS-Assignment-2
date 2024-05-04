(function($) {

	BACheetahMenu = function( node ){

		this.menu 			= $(".ba-node-" + node + ".ba-module__menu .menu__container");
		this.menu_mobile 	= $(".ba-node-" + node + ".ba-module__menu .menu__mobile")

		this._initCollapses()
	};

	BACheetahMenu.prototype = {

		_initCollapses: function() {
			this._initCollapseHamburger();
			this._initCollapseMobileDropdownItems()
		},

		_toggleMobileMenu: function() {
			this.menu.slideToggle(300, function(e) {
				$(this).toggleClass('menu__container--open').removeAttr('style')
			})
		},

		_initCollapseHamburger: function() {
			const that = this
			this.menu_mobile.find('.menu__mobile-hamburger').on('click', function() {
				that._toggleMobileMenu()
			})
		},

		_initCollapseMobileDropdownItems: function() {
			const that = this

			this.menu.find('.menu-item > a').on('click', function(e) {
				if (BACheetahLayout._isMobile()) {
					if ($(this).parent().hasClass('menu-item-has-children')) {
						// open nested dropdown
						e.preventDefault();
						e.stopPropagation();
						$(this).closest('.menu-item-has-children').toggleClass('menu__item--open')
					}
					else {
						// close dropdown (for anchor links)
						that._toggleMobileMenu()
					}
				}
			})
		}

	};

})(jQuery);
