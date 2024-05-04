(function($) {

	/**
	 * Builds a gallery grid of items.
	 *
	 * @class BACheetahGalleryGrid

	 */
	BACheetahGalleryGrid = function(settings)
	{
		$.extend(this, settings);

		if($(this.wrapSelector).length > 0) {
			$(window).on('resize', $.proxy(this.resize, this));
			this.resize();
		}
	};

	/**
	 * Prototype for new instances.
	 *

	 * @property {Object} prototype
	 */
	BACheetahGalleryGrid.prototype = {

		/**
		 * A CSS selector for the element that wraps
		 * the gallery items.
		 *

		 * @property {String} wrapSelector
		 */
		wrapSelector    : '.ba-cheetah-gallery-grid',

		/**
		 * A CSS selector for the gallery items.
		 *

		 * @property {String} itemSelector
		 */
		itemSelector    : '> *',

		/**
		 * The maximum width of the items.
		 *

		 * @property {Number} itemWidth
		 */
		itemWidth       : 400,

		/**
		 * A ratio to use for the item height.
		 *

		 * @property {Number} itemHeight
		 */
		itemHeight      : 0.75,

		/**
		 * RTL support
		 *

		 * @property {Boolean} isRTL
		 */
		isRTL           : false,

		/**
		 * Callback that fires when the window is resized
		 * to resize the gallery items.
		 *

		 * @method resize
		 */
		resize: function()
		{
			if ( ! $(this.wrapSelector).length ) {
				return;
			}
			var winWidth    = $(window).width(),
				wrap        = $(this.wrapSelector),
				wrapWidth   = wrap[0].getBoundingClientRect().width,
				numCols     = winWidth > 480 ? Math.ceil(wrapWidth/this.itemWidth) : 1,
				items       = wrap.find(this.itemSelector),
				itemWidth   = wrapWidth/numCols,
				itemHeight  = itemWidth * this.itemHeight,
				direction   = this.isRTL ? 'right' : 'left';

			// Browser bug fix. One column images are streched otherwise.
			if ( 1 === numCols ) {
				itemWidth -= 0.5;
			}

			// Set the item width and height.
			items.css({
				'float'  : direction,
				'height' : itemHeight + 'px',
				'width'  : itemWidth + 'px'
			});
		}
	};

})(jQuery);
