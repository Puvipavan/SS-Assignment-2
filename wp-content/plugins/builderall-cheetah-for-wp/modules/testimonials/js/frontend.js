(function ($) {

	BACheetahTestimonials = function (options) {

		this.options = options
		this._init()
	};

	BACheetahTestimonials.prototype = {

		_init: function () {

			let cols = null,
				breakpoints = new Object();
			const breakMedium = BACheetahLayoutConfig.breakpoints.medium,
				breakResponsive = BACheetahLayoutConfig.breakpoints.small;

			cols = this._isEditing() ? this._getColsByResponsiveMode() : this.options.cols;

			breakpoints[breakMedium] = new Object();
			breakpoints[breakResponsive] = new Object();

			// responsive support
			if (this.options.colsMedium) {
				breakpoints[breakMedium]['perPage'] = this.options.colsMedium
			}
			if (this.options.colsResponsive) {
				breakpoints[breakResponsive]['perPage'] = this.options.colsResponsive
			}

			var splide = new Splide(this.options.node, {
				type: 'loop',
				pauseOnHover: true,
				gap: this.options.gap,
				padding: 5, //The box has overflow:hidden. I need to see the shadows
				perPage: cols,
				breakpoints: breakpoints,
				autoplay: this.options.autoplay,
				pagination: this.options.pagination,
				speed: this.options.speed,
				interval: this.options.interval,
			}).mount();

			if (this._isEditing()) {
				this._initResponsiveEditingPreview(splide)
			}
		},


		_isEditing() {
			return typeof BACheetahResponsiveEditing == 'object' && typeof BACheetah == 'object';
		},

		/**
		 * The "Splide" responsive mode works based on the size of the viewport. 
		 * To allow previewing the columns in responsive editing, the responsive configuration of the field is simulated in the "perPage" 
		 * option (not in breakpoints) when in the corresponding responsive mode 
		 */

		_getColsByResponsiveMode: function () {

			const mode = BACheetahResponsiveEditing._mode;

			if (mode == 'medium' && this.options.colsMedium) {
				return this.options.colsMedium
			}
			else if (mode == 'responsive' && this.options.colsResponsive) {
				return this.options.colsResponsive
			}
			else {
				return this.options.cols
			}
		},

		/**
		 * the number of columns is updated every time the edit mode device size is changed
		 * @param {Splide} instance 
		 */

		_initResponsiveEditingPreview(instance) {
			const that = this;
			BACheetah.addHook('responsive-editing-switched', function (event) {
				instance.options.perPage = that._getColsByResponsiveMode()
			})
		}

	};

})(jQuery);
