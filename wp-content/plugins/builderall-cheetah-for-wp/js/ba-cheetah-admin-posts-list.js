(function($){

	/**
	 * Helper class for dealing with the post edit screen.
	 *
	 * @class BACheetahAdminPostsListCount

	 * @static
	 */
	BACheetahAdminPostsListCount = {

		/**
		 * Initializes the builder enabled count for the post edit screen.
		 *

		 * @method init
		 */
		init: function()
		{
			this._setupLink();
		},
		_setupLink: function() {
			var ul = $('ul.subsubsub')

			var count = window.ba_cheetah_enabled_count.count;
			var brand = window.ba_cheetah_enabled_count.brand;
			var clicked = window.ba_cheetah_enabled_count.clicked;
			var type = window.ba_cheetah_enabled_count.type;
			var bb_class = '';

			if ( clicked ) {
				bb_class += 'current'
			}
			ul.append( '|&nbsp;<li class="bb"><a class="' + bb_class + '" href="edit.php?post_type=' + type + '&bbsort">' + brand + ' <span class="count">(' + count +')</span></a></li>');
		}

	};

	$(function(){
		BACheetahAdminPostsListCount.init()
	});
})(jQuery);
