(function($){

	/**
	 * Helper class for the icon selector lightbox.
	 *
	 * @class BACheetahIconSelector

	 */
	BACheetahIconSelector = {

		/**
		 * A reference to the lightbox HTML content that is
		 * loaded via AJAX.
		 *

		 * @access private
		 * @property {String} _content
		 */
		_content    : null,

		/**
		 * A reference to a BACheetahLightbox object.
		 *

		 * @access private
		 * @property {BACheetahLightbox} _lightbox
		 */
		_lightbox   : null,

		/**
		 * A flag for whether the content has already
		 * been rendered or not.
		 *

		 * @access private
		 * @property {Boolean} _rendered
		 */
		_rendered   : false,

		/**
		 * The text that is used to filter the selection
		 * of visible icons.
		 *

		 * @access private
		 * @property {String} _filterText
		 */
		_filterText : '',

		/**
		 * Opens the icon selector lightbox.
		 *

		 * @method open
		 * @param {Function} callback A callback that fires when an icon is selected.
		 */
		open: function(callback)
		{
			if(!BACheetahIconSelector._rendered) {
				BACheetahIconSelector._render();
			}

			if(BACheetahIconSelector._content === null) {

				BACheetahIconSelector._lightbox.open('<div class="ba-cheetah-lightbox-loading"></div>');

				BACheetah.ajax({
					action: 'render_icon_selector'
				}, BACheetahIconSelector._getContentComplete);
			}
			else {
				BACheetahIconSelector._lightbox.open();
			}

			BACheetahIconSelector._lightbox.on('icon-selected', function(event, icon){
				BACheetahIconSelector._lightbox.off('icon-selected');
				BACheetahIconSelector._lightbox.close();
				callback(icon);
			});
		},

		/**
		 * Renders a new instance of BACheetahLightbox.
		 *

		 * @access private
		 * @method _render
		 */
		_render: function()
		{
			BACheetahIconSelector._lightbox = new BACheetahLightbox({
				className: 'ba-cheetah-icon-selector'
			});

			BACheetahIconSelector._rendered = true;

			BACheetah.addHook( 'endEditingSession', function() {
				BACheetahIconSelector._lightbox.close()
			} );
		},

		/**
		 * Callback for when the lightbox content
		 * has been returned via AJAX.
		 *

		 * @access private
		 * @method _getContentComplete
		 * @param {String} response The JSON with the HTML lightbox content.
		 */
		_getContentComplete: function(response)
		{
			var data = BACheetah._jsonParse(response);

			BACheetahIconSelector._content = data.html;
			BACheetahIconSelector._lightbox.setContent(data.html);
			$('.ba-cheetah-icons-filter-select').on('change', BACheetahIconSelector._filter);
			$('.ba-cheetah-icons-filter-text').on('keyup', BACheetahIconSelector._filter);
			$('.ba-cheetah-icons-list i').on('click', BACheetahIconSelector._select);
			$('.ba-cheetah-icon-selector-cancel').on('click', $.proxy(BACheetahIconSelector._lightbox.close, BACheetahIconSelector._lightbox));
		},

		/**
		 * Filters the selection of visible icons based on
		 * the library select and search input text.
		 *

		 * @access private
		 * @method _filter
		 */
		_filter: function()
		{
			var section = $( '.ba-cheetah-icons-filter-select' ).val(),
				text    = $( '.ba-cheetah-icons-filter-text' ).val();

			// Filter sections.
			if ( 'all' == section ) {
				$( '.ba-cheetah-icons-section' ).show();
			}
			else {
				$( '.ba-cheetah-icons-section' ).hide();
				$( '.ba-cheetah-' + section ).show();
			}

			// Filter icons.
			BACheetahIconSelector._filterText = text;

			if ( '' !== text ) {
				$( '.ba-cheetah-icons-list i' ).each( BACheetahIconSelector._filterIcon );
			}
			else {
				$( '.ba-cheetah-icons-list i' ).show();
			}
		},

		/**
		 * Shows or hides an icon based on the filter text.
		 *

		 * @access private
		 * @method _filterIcon
		 */
		_filterIcon: function()
		{
			var icon = $( this );

			if ( -1 == icon.attr( 'class' ).indexOf( BACheetahIconSelector._filterText ) ) {
				icon.hide();
			}
			else {
				icon.show();
			}
		},

		/**
		 * Called when an icon is selected and fires the
		 * icon-selected event on the lightbox.
		 *

		 * @access private
		 * @method _select
		 */
		_select: function()
		{
			var icon = $(this).attr('class');

			BACheetahIconSelector._lightbox.trigger('icon-selected', icon);
		}
	};

})(jQuery);
