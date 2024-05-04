(function($){

	/**
	 * Helper class for dealing with the builder's layout location page
	 *
	 * @class BACheetahAdminLayoutRules

	 */
	BACheetahAdminLayoutRules = {

		init: function () {
			this._bind();
			this._checkSelecAllCheckbox()
		},

		/**
		 * Binds events for the builder's admin settings page.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$('.ba-cheetah-layout-all-cb').on('click', BACheetahAdminLayoutRules._layoutAllCheckboxClicked);
			$('select[name="ba-cheetah-header-option"]').on('change', BACheetahAdminLayoutRules._changeHeaderLayoutOption);
			$('select[name="ba-cheetah-footer-option"]').on('change', BACheetahAdminLayoutRules._changeFooterLayoutOption);
			$('.ba-cheetah-layout-location-cb').on('change', BACheetahAdminLayoutRules._checkSelecAllCheckbox);

			$(document).ready(BACheetahAdminLayoutRules._changeHeaderLayoutOption);
			$(document).ready(BACheetahAdminLayoutRules._changeFooterLayoutOption);
		},

		_checkSelecAllCheckbox: function () {
			if ($('.ba-cheetah-layout-location-cb:checked').length === $('.ba-cheetah-layout-location-cb').length) {
				$('.ba-cheetah-layout-all-cb').prop('checked', true);
			} else {
				$('.ba-cheetah-layout-all-cb').prop('checked', false);
			}
		},

		_layoutAllCheckboxClicked: function() {
			if ($(this).is(':checked')) {
				$('.ba-cheetah-layout-location-cb').prop('checked', true);
			} else {
				$('.ba-cheetah-layout-location-cb').prop('checked', false);
			}
		},

		_changeHeaderLayoutOption: function () {
			if ($('select[name="ba-cheetah-header-option"]').val() == 'custom') {
				$('#ba-cheetah-custom-header-selector').show();
			} else {
				$('#ba-cheetah-custom-header-selector').hide();
			}
		},

		_changeFooterLayoutOption: function () {
			if ($('select[name="ba-cheetah-footer-option"]').val() == 'custom') {
				$('#ba-cheetah-custom-footer-selector').show();
			} else {
				$('#ba-cheetah-custom-footer-selector').hide();
			}
		}

	};

	/* Initializes the builder's admin settings. */
	$(function(){
		BACheetahAdminLayoutRules.init();
	});

})(jQuery);
