(function( $ ) {

	/**
	 * Logic for the builder's help tour.
	 *
	 * @class BACheetahTour

	 */
	BACheetahTour = {

		/**
		 * A reference to the Bootstrap Tour object.
		 *

		 * @access private
		 * @property {Tour} _tour
		 */
		_tour: null,

		/**
		 * Starts the tour or restarts it if it
		 * has already run.
		 *

		 * @method start
		 */
		start: function()
		{
			if ( ! BACheetahTour._tour ) {
				BACheetahTour._tour = new Tour( BACheetahTour._config() );
				BACheetahTour._tour.init();
			}
			else {
				BACheetahTour._tour.restart();
			}

			// Save existing settings first if any exist. Don't proceed if it fails.
			if ( ! BACheetah._triggerSettingsSave( false, true ) ) {
				return;
			}
			
			const isHeaderFooterPopup = ['ba-cheetah-header', 'ba-cheetah-footer', 'ba-cheetah-popup'].includes(BACheetahConfig.userTemplateType)

			if (isHeaderFooterPopup) {
				BACheetahTour._tour.start();
			}

			// Wait for the menu opening animation to finish to start the tour
			else {
				BACheetah.triggerHook('openMainMenu');
				setTimeout(() => {
					BACheetahTour._tour.start();
				}, 450)
			}
		},

		/**
		 * Returns a config object for the tour.
		 *

		 * @access private
		 * @method _config
		 * @return {Object}
		 */
		_config: function()
		{
			var config = {
				storage     : false,
				backdrop	: true,
				autoscroll  : false,
				onStart     : BACheetahTour._onStart,
				onPrev      : BACheetahTour._onPrev,
				onNext      : BACheetahTour._onNext,
				onEnd       : BACheetahTour._onEnd,
				template    : function(i, step) { 
					return `<div class="popover" role="tooltip"> 
						<span class="tour-steps-count">${((i + 1) + "/" + stepsCount)}</span> 
						<i class="fas fa-times" data-role="end"></i> 
						<div class="arrow"></div>
						<div class="popover-content"></div> 
						<div class="popover-navigation clearfix"> 
						<button class="ba-cheetah-button ba-cheetah-button-primary ba-cheetah-tour-next" data-role="next">${BACheetahStrings.tourNext}</button> 
						</div> 
					</div>`;
				},
				steps       : [
					{
						element     : '.ba-cheetah--menu .ba-cheetah-mode-toggle',
						placement   : 'right',
						content     : BACheetahStrings.tourMode
					},
					{
						element     : '.ba-cheetah--menu-item[data-event="showPageSettings"]',
						placement   : 'right',
						content     : BACheetahStrings.tourPageSettings,
						onHidden: function() {
							BACheetah.triggerHook('closeMainMenu');
						},
					},
					{
						element     : '.ba-cheetah--content-library-panel',
						placement   : 'left',
						content     : BACheetahStrings.tourTemplates,
						onShow		: function() {
							BACheetah.ContentPanel.show('templates');
						},
					},
					{
						element     : '.ba-cheetah--content-library-panel #ba-cheetah-blocks-rows',
						placement   : 'left',
						content     : BACheetahStrings.tourAddRows,
						onShow      : function() {
							BACheetah.ContentPanel.show('rows');
						}
					},
					{
						element     : '.ba-cheetah--content-library-panel .ba-cheetah--panel-view[data-tab="modules"]',
						placement   : 'left',
						content     : BACheetahStrings.tourAddContent,
						onShow      : function() {
							BACheetah.ContentPanel.show('modules');
						}
					},
					{
						element     : '.ba-cheetah-button-group.ba-navbar-devices',
						placement   : 'bottom',
						content     : BACheetahStrings.tourMobileEditing
					},
					{
						element     : '.ba-cheetah-done-button',
						placement   : 'bottom',
						content     : BACheetahStrings.tourDoneButton,
						onShow      : function() {
							BACheetahTour._dimSection( 'body' );
						}
					},
					{
						orphan      : true,
						backdrop    : true,
						content     : BACheetahStrings.tourFinished,
						template    : '<div class="popover" role="tooltip"> <div class="arrow"></div> <i class="fas fa-times" data-role="end"></i> <h3 class="popover-title"></h3> <div class="popover-content"></div> <div class="popover-navigation clearfix"> <button class="ba-cheetah-button ba-cheetah-button-primary ba-cheetah-tour-next" data-role="end">' + BACheetahStrings.tourEnd + '</button> </div> </div>',
					}
				]
			};

			const stepsCount = config.steps.length - 1 // last is orphan

			return config;
		},

		/**
		 * Callback for when the tour starts.
		 *

		 * @access private
		 * @method _onStart
		 */
		_onStart: function()
		{
			var body = $( 'body' );
			body.scrollTop(0);

			body.append( '<div class="ba-cheetah-tour-mask"></div>' );
		},

		/**
		 * Callback for when the tour is navigated
		 * to the previous step.
		 *

		 * @access private
		 * @method _onPrev
		 */
		_onPrev: function()
		{
			$( '.ba-cheetah-tour-dimmed' ).remove();
		},

		/**
		 * Callback for when the tour is navigated
		 * to the next step.
		 *

		 * @access private
		 * @method _onNext
		 */
		_onNext: function()
		{
			$( '.ba-cheetah-tour-dimmed' ).remove();
		},

		/**
		 * Callback for when the tour ends.
		 *

		 * @access private
		 * @method _onEnd
		 */
		_onEnd: function()
		{
			$( 'body' ).off( 'ba-cheetah.template-selector-loaded' );
			$( '.ba-cheetah-tour-mask' ).remove();
			$( '.ba-cheetah-tour-dimmed' ).remove();
			$( '.ba-cheetah-tour-placeholder-content' ).remove();
			$( '.ba-cheetah-tour-demo-content' ).removeClass( 'ba-cheetah-tour-demo-content' );

			BACheetah._setupEmptyLayout();
			BACheetah._highlightEmptyCols();
			BACheetah._showPanel();
			BACheetah._initTemplateSelector();
		},

		/**
		 * Dims a section of the page.
		 *

		 * @access private
		 * @method _dimSection
		 * @param {String} selector A CSS selector for the section to dim.
		 */
		_dimSection: function( selector )
		{
			$( selector ).find( '.ba-cheetah-tour-dimmed' ).remove();
			$( selector ).append( '<div class="ba-cheetah-tour-dimmed"></div>' );
		}
	};

})( jQuery );
