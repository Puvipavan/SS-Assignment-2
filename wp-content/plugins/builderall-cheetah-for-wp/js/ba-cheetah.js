(function($){

	/**
	 * The main builder interface class.
	 *

	 * @class BACheetah
	 */
	BACheetah = {

		/**
		 * An instance of BACheetahPreview for working
		 * with the current live preview.
		 *

		 * @property {BACheetahPreview} preview
		 */
		preview                     : null,

		/**
		 * An instance of BACheetahLightbox for displaying a list
		 * of actions a user can take such as publish or cancel.
		 *

		 * @access private
		 * @property {BACheetahLightbox} _actionsLightbox
		 */
		_actionsLightbox            : null,

		/**
		 * An array of AJAX data that needs to be requested
		 * after the current request has finished.
		 *

		 * @property {Array} _ajaxQueue
		 */
		_ajaxQueue                  : [],

		/**
		 * A reference to the current AJAX request object.
		 *

		 * @property {Object} _ajaxRequest
		 */
		_ajaxRequest                : null,

		/**
		 * A reference to the disabled modules object.
		 *

		 * @property {Object} _disabledModules
		 */
		_disabledModules                : [],

		/**
		 * A reference to the disabled modules object, full information.
		 *

		 * @property {Object} _disabledModulesFull
		 */
		
		_disabledModulesFull                : [],

		/**
		 * An object that holds data for column resizing.
		 *

		 * @access private
		 * @property {Object} _colResizeData
		 */
		_colResizeData              : null,

		/**
		 * A flag for whether a column is being resized or not.
		 *

		 * @access private
		 * @property {Boolean} _colResizing
		 */
		_colResizing              	: false,

		/**
		 * The CSS class of the main content wrapper for the
		 * current layout that is being worked on.
		 *

		 * @access private
		 * @property {String} _contentClass
		 */
		_contentClass               : false,

		/**
		 * Whether dragging has been enabled or not.
		 *

		 * @access private
		 * @property {Boolean} _dragEnabled
		 */
		_dragEnabled                : false,

		/**
		 * Whether an element is currently being dragged or not.
		 *

		 * @access private
		 * @property {Boolean} _dragging
		 */
		_dragging                   : false,

		/**
		 * The initial scroll top of the window when a drag starts.
		 * Used to reset the scroll top when a drag is cancelled.
		 *

		 * @access private
		 * @property {Boolean} _dragging
		 */
		_dragInitialScrollTop       : 0,

		/**
		 * The URL to redirect to when a user leaves the builder.
		 *

		 * @access private
		 * @property {String} _exitUrl
		 */
		_exitUrl                    : null,

		/**
		 * An instance of BACheetahAJAXLayout for rendering
		 * the layout via AJAX.
		 *

		 * @property {BACheetahAJAXLayout} _layout
		 */
		_layout                     : null,

		/**
		 * An array of layout data that needs to be rendered
		 * after the current rendered is finished.
		 *

		 * @property {Array} _layoutQueue
		 */
		_layoutQueue                 : [],

		/**
		 * A cached copy of custom layout CSS that is used to
		 * revert changes if the cancel button is clicked.
		 *

		 * @property {String} _layoutSettingsCSSCache
		 */
		_layoutSettingsCSSCache     : null,

		/**
		 * A timeout for throttling custom layout CSS changes.
		 *

		 * @property {Object} _layoutSettingsCSSTimeout
		 */
		_layoutSettingsCSSTimeout   : null,

		/**
		 * An instance of BACheetahLightbox for displaying settings.
		 *

		 * @access private
		 * @property {BACheetahLightbox} _lightbox
		 */
		_lightbox                   : null,

		/**
		 * A timeout for refreshing the height of lightbox scrollbars
		 * in case the content changes from dynamic settings.
		 *

		 * @access private
		 * @property {Object} _lightboxScrollbarTimeout
		 */
		_lightboxScrollbarTimeout   : null,

		/**
		 * An array that's used to cache which module settings
		 * CSS and JS assets have already been loaded so they
		 * are only loaded once.
		 *

		 * @access private
		 * @property {Array} _loadedModuleAssets
		 */
		_loadedModuleAssets         : [],

		/**
		 * An object used to store module settings helpers.
		 *

		 * @access private
		 * @property {Object} _moduleHelpers
		 */
		_moduleHelpers              : {},

		/**
		 * An instance of wp.media used to select multiple photos.
		 *

		 * @access private
		 * @property {Object} _multiplePhotoSelector
		 */
		_multiplePhotoSelector      : null,

		/**
		 * A jQuery reference to a group that a new column
		 * should be added to once it's finished rendering.
		 *

		 * @access private
		 * @property {Object} _newColParent
		 */
		_newColParent          		: null,

		/**
		 * The position a column should be added to within
		 * a group once it finishes rendering.
		 *

		 * @access private
		 * @property {Number} _newColPosition
		 */
		_newColPosition        		: 0,

		/**
		 * A jQuery reference to a row that a new column group
		 * should be added to once it's finished rendering.
		 *

		 * @access private
		 * @property {Object} _newColGroupParent
		 */
		_newColGroupParent          : null,

		/**
		 * The position a column group should be added to within
		 * a row once it finishes rendering.
		 *

		 * @access private
		 * @property {Number} _newColGroupPosition
		 */
		_newColGroupPosition        : 0,

		/**
		 * A jQuery reference to a new module's parent.
		 *

		 * @access private
		 * @property {Object} _newModuleParent
		 */
		_newModuleParent          	: null,

		/**
		 * The position a new module should be added at once
		 * it finishes rendering.
		 *

		 * @access private
		 * @property {Number} _newModulePosition
		 */
		_newModulePosition        	: 0,

		/**
		 * The position a row should be added to within
		 * the layout once it finishes rendering.
		 *

		 * @access private
		 * @property {Number} _newRowPosition
		 */
		_newRowPosition             : 0,

		/**
		 * The ID of a template that the user has selected.
		 *

		 * @access private
		 * @property {Number} _selectedTemplateId
		 */
		_selectedTemplateId         : null,

		/**
		 * The type of template that the user has selected.
		 * Possible values are "core" or "user".
		 *

		 * @access private
		 * @property {String} _selectedTemplateType
		 */
		_selectedTemplateType       : null,

		_selectedTemplateContentCentralId	: 0,

		/**
		 * An instance of wp.media used to select a single photo.
		 *

		 * @access private
		 * @property {Object} _singlePhotoSelector
		 */
		_singlePhotoSelector        : null,

		/**
		 * An instance of wp.media used to select a single video.
		 *

		 * @access private
		 * @property {Object} _singleVideoSelector
		 */
		_singleVideoSelector        : null,

		/**
		 * An instance of wp.media used to select a multiple audio.
		 *

		 * @access private
		 * @property {Object} _multipleAudiosSelector
		 */
		_multipleAudiosSelector        : null,


		/**

		 */
		_codeDisabled: false,

		/**
		 * Initializes the builder interface.
		 *

		 * @access private
		 * @method _init
		 */
		_init: function()
		{
			BACheetah._initJQueryReadyFix();
			BACheetah._initGlobalErrorHandling();
			BACheetah._initPostLock();
			BACheetah._initClassNames();
			BACheetah._initMediaUploader();
			BACheetah._initOverflowFix();
			BACheetah._initScrollbars();
			BACheetah._initLightboxes();
			BACheetah._initDropTargets();
			BACheetah._initSortables();
			BACheetah._initStrings();
			BACheetah._initTipTips();
			BACheetah._initTinyMCE();
			BACheetah._bindEvents();
			BACheetah._bindOverlayEvents();
			BACheetah._setupEmptyLayout();
			BACheetah._highlightEmptyCols();
			BACheetah._fillDisabledModules();

			BACheetah.addHook('didInitUI', BACheetah._showTourOrTemplates.bind(BACheetah) );
			BACheetah.addHook('endEditingSession', BACheetah._doStats.bind(this) );

			BACheetah.triggerHook('init');
		},

		/**
		 * Prevent errors thrown in jQuery's ready function
		 * from breaking subsequent ready calls.
		 *

		 * @access private
		 * @method _initJQueryReadyFix
		 */
		_initJQueryReadyFix: function()
		{
			if ( BACheetahConfig.debug ) {
				return;
			}

			jQuery.fn.oldReady = jQuery.fn.ready;

			jQuery.fn.ready = function( fn ) {
				return jQuery.fn.oldReady( function() {
					try {
						if ( 'function' == typeof fn ) {
							fn( $ );
						}
					}
					catch ( e ){
						BACheetah.logError( e );
					}
				});
			};
		},

		/**
		 * Try to prevent errors from third party plugins
		 * from breaking the builder.
		 *

		 * @access private
		 * @method _initGlobalErrorHandling
		 */
		_initGlobalErrorHandling: function()
		{
			if ( BACheetahConfig.debug ) {
				return;
			}

			window.onerror = function( message, file, line, col, error ) {
				BACheetah.logGlobalError( message, file, line, col, error );
				return true;
			};
		},

		/**
		 * Send a wp.heartbeat request to lock editing of this
		 * post so it can only be edited by the current user.
		 *

		 * @access private
		 * @method _initPostLock
		 */
		_initPostLock: function()
		{
			if(typeof wp.heartbeat != 'undefined') {

				wp.heartbeat.interval(30);

				wp.heartbeat.enqueue('ba_cheetah_post_lock', {
					post_id: BACheetahConfig.postId
				});
			}
		},

		/**
		 * Initializes html and body classes as well as the
		 * builder content class for this post.
		 *

		 * @access private
		 * @method _initClassNames
		 */
		_initClassNames: function()
		{
			var html = $( 'html' ),
				body = $( 'body' );

			html.addClass('ba-cheetah-edit');
			body.addClass( 'ba-cheetah' );

			if ( BACheetahConfig.simpleUi ) {
				body.addClass( 'ba-cheetah-simple' );
			}

			BACheetah._contentClass = '.ba-cheetah-content-' + BACheetahConfig.postId;

			$( BACheetah._contentClass ).addClass( 'ba-cheetah-content-editing' );
		},

		/**
		 * Initializes the WordPress media uploader so any files
		 * uploaded will be attached to the current post.
		 *

		 * @access private
		 * @method _initMediaUploader
		 */
		_initMediaUploader: function()
		{
			wp.media.model.settings.post.id = BACheetahConfig.postId;
		},

		/**
		 * Third party themes that set their content wrappers to
		 * overflow:hidden break builder overlays. We set them
		 * to overflow:visible while editing.
		 *

		 * @access private
		 * @method _initOverflowFix
		 */
		_initOverflowFix: function()
		{
			$(BACheetah._contentClass).parents().css('overflow', 'visible');
		},

		/**
		 * Initializes Nano Scroller scrollbars for the
		 * builder interface.
		 *

		 * @access private
		 * @method _initScrollbars
		 */
		_initScrollbars: function()
		{
			var scrollers = $('.ba-cheetah-nanoscroller').nanoScroller({
				alwaysVisible: true,
				preventPageScrolling: true,
				paneClass: 'ba-cheetah-nanoscroller-pane',
				sliderClass: 'ba-cheetah-nanoscroller-slider',
				contentClass: 'ba-cheetah-nanoscroller-content'
			}),
				settingsScroller = scrollers.filter('.ba-cheetah-settings-fields'),
				pane = settingsScroller.find('.ba-cheetah-nanoscroller-pane');

			if ( pane.length ) {
				var display = pane.get(0).style.display;
				var content = settingsScroller.find('.ba-cheetah-nanoscroller-content');

				if ( display === "none" ) {
					content.removeClass('has-scrollbar');
				} else {
					content.addClass('has-scrollbar');
				}
			}
		},

		/**
		 * Initializes jQuery sortables for drag and drop.
		 *

		 * @access private
		 * @method _initSortables
		 */
		_initSortables: function()
		{
			var defaults = {
				appendTo: BACheetah._contentClass,
				cursor: 'move',
				cursorAt: {
					left: 85,
					top: 20
				},
				distance: 1,
				helper: BACheetah._blockDragHelper,
				start : BACheetah._blockDragStart,
				sort: BACheetah._blockDragSort,
				change: BACheetah._blockDragChange,
				stop: BACheetah._blockDragStop,
				placeholder: 'ba-cheetah-drop-zone',
				tolerance: 'intersect'
			},
			rowConnections 	  = '',
			columnConnections = '',
			moduleConnections = '';

			// Module Connections.
			if ( 'row' == BACheetahConfig.userTemplateType )  {
				moduleConnections = BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target, ' +
							  		BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-content';
			}
			else if ( 'column' == BACheetahConfig.userTemplateType ) {
				moduleConnections = BACheetah._contentClass + ' .ba-cheetah-col-group-drop-target, ' +
			                        BACheetah._contentClass + ' .ba-cheetah-col-drop-target, ' +
			                        BACheetah._contentClass + ' .ba-cheetah-col-content';
			}
			else {
				moduleConnections = BACheetah._contentClass + ' .ba-cheetah-row-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target, ' +
							  		BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col:not(.ba-cheetah-node-loading):not(.ba-cheetah-node-global) .ba-cheetah-col-content';
			}

			// Column Connections.
			if ( 'row' == BACheetahConfig.userTemplateType )  {
				columnConnections = BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target';
			}
			else {
				columnConnections = BACheetah._contentClass + ' .ba-cheetah-row-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
									BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target';
			}

			// Row Connections.
			if ( BACheetahConfig.nestedColumns ) {
				rowConnections = moduleConnections;
			}
			else if ( 'row' == BACheetahConfig.userTemplateType )  {
				rowConnections = BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
								 BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target';
			}
			else {
				rowConnections = BACheetah._contentClass + ' .ba-cheetah-row-drop-target, ' +
								 BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-group-drop-target, ' +
								 BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-node-loading) .ba-cheetah-col-drop-target';
			}

			// Row layouts from the builder panel.
			$('.ba-cheetah-rows').sortable($.extend({}, defaults, {
				connectWith: rowConnections,
				items: '.ba-cheetah-block-row',
				stop: BACheetah._rowDragStop
			}));

			// Row templates from the builder panel.
			$('.ba-cheetah-row-templates').sortable($.extend({}, defaults, {
				cancel: '.ba-cheetah-block-row-template[data-compatible="false"], .ba-cheetah-button, .ba-cheetah--template-incompatible',
				connectWith: BACheetah._contentClass + ' .ba-cheetah-row-drop-target',
				items: '.ba-cheetah-block-row-template:not(.ba-cheetah-block-disabled)',
				stop: BACheetah._nodeTemplateDragStop
			}));

			// Saved rows from the builder panel.
			$('.ba-cheetah-saved-rows').sortable($.extend({}, defaults, {
				cancel: '.ba-cheetah-node-template-actions, .ba-cheetah-node-template-edit, .ba-cheetah-node-template-delete',
				connectWith: BACheetah._contentClass + ' .ba-cheetah-row-drop-target',
				items: '.ba-cheetah-block-saved-row',
				stop: BACheetah._nodeTemplateDragStop
			}));

			// Saved columns from the builder panel.
			$('.ba-cheetah-saved-columns').sortable($.extend({}, defaults, {
				cancel: '.ba-cheetah-node-template-actions, .ba-cheetah-node-template-edit, .ba-cheetah-node-template-delete',
				connectWith: columnConnections,
				items: '.ba-cheetah-block-saved-column',
				stop: BACheetah._nodeTemplateDragStop
			}));

			// Modules from the builder panel.
			$('.ba-cheetah-modules, .ba-cheetah-widgets').sortable($.extend({}, defaults, {
				connectWith: moduleConnections,
				items: '.ba-cheetah-block-module:not(.ba-cheetah-block-disabled)',
				stop: BACheetah._moduleDragStop
			}));

			// Module templates from the builder panel.
			$('.ba-cheetah-module-templates').sortable($.extend({}, defaults, {
				connectWith: moduleConnections,
				items: '.ba-cheetah-block-module-template',
				stop: BACheetah._nodeTemplateDragStop
			}));

			// Saved modules from the builder panel.
			$('.ba-cheetah-saved-modules').sortable($.extend({}, defaults, {
				cancel: '.ba-cheetah-node-template-actions, .ba-cheetah-node-template-edit, .ba-cheetah-node-template-delete',
				connectWith: moduleConnections,
				items: '.ba-cheetah-block-saved-module',
				stop: BACheetah._nodeTemplateDragStop
			}));

			// Rows
			$('.ba-cheetah-row-sortable-proxy').sortable($.extend({}, defaults, {
				connectWith: BACheetah._contentClass + ' .ba-cheetah-row-drop-target',
				helper: BACheetah._rowDragHelper,
				start: BACheetah._rowDragStart,
				stop: BACheetah._rowDragStop
			}));

			// Columns
			$('.ba-cheetah-col-sortable-proxy').sortable($.extend({}, defaults, {
				connectWith: moduleConnections,
				helper: BACheetah._colDragHelper,
				start: BACheetah._colDragStart,
				stop: BACheetah._colDragStop
			}));

			// Modules
			$(BACheetah._contentClass + ' .ba-cheetah-col-content').sortable($.extend({}, defaults, {
				connectWith: moduleConnections,
				handle: '.ba-cheetah-module-sortable-proxy',
				helper: BACheetah._moduleDragHelper,
				items: '.ba-cheetah-module, .ba-cheetah-col-group',
				start: BACheetah._moduleDragStart,
				stop: BACheetah._moduleDragStop
			}));

			// Drop targets
			$(BACheetah._contentClass + ' .ba-cheetah-row-drop-target').sortable( defaults );
			$(BACheetah._contentClass + ' .ba-cheetah-col-group-drop-target').sortable( defaults );
			$(BACheetah._contentClass + ' .ba-cheetah-col-drop-target').sortable( defaults );
		},

		/**
		 * Refreshes the items for all jQuery sortables so any
		 * new items will be recognized.
		 *

		 * @access private
		 * @method _refreshSortables
		 */
		_refreshSortables: function()
		{
			$( '.ui-sortable' ).sortable( 'refresh' );
		},

		/**
		 * Initializes text translation
		 *

		 * @access private
		 * @method _initStrings
		 */
		_initStrings: function()
		{
			$.validator.messages.required = BACheetahStrings.validateRequiredMessage;
		},


		/**
		 * Initializes disabled modules array
		 *

		 * @access private
		 * @method _fillDisabledModules
		 */
		 _fillDisabledModules: function()
		 {
			var builderallModules = BACheetahConfig.contentItems.builderall;
			var modulesBuilderallSlugs = Object.keys(builderallModules);

			var proModules = BACheetahConfig.contentItems.pro;
			var modulesProSlugs = Object.keys(proModules);
			
			if ( !BACheetahConfig.isBuilderallUser) {

				if ( !BACheetahConfig.isProUser ) {
					// not BUILDERALL and not PRO
					// MERGE PRO AND BUILDERALL
					BACheetah._disabledModules = modulesBuilderallSlugs;
					BACheetah._disabledModulesFull = builderallModules;
					for( var k in proModules) {
						if ( -1 === $.inArray( k, BACheetah._disabledModules ) ) {
							BACheetah._disabledModules.push(k);
							BACheetah._disabledModulesFull[k] = proModules[k];
						}
					}
				} else {
					// not BUILDERALL and PRO
					// INSERT JUST BA LIST THAT AREN'T IN THE PRO LIST
					for( var k in builderallModules) {
						if ( -1 === $.inArray( k, modulesProSlugs ) ) {
							BACheetah._disabledModules.push(k);
							BACheetah._disabledModulesFull[k] = builderallModules[k];
						}
					}
				}

			} else if ( !BACheetahConfig.isProUser ) {
				// BUILDERALL and not PRO
				// INSERT JUST PRO LIST THAT AREN'T IN THE BA LIST
				for( var k in proModules) {
					if ( -1 === $.inArray( k, modulesBuilderallSlugs ) ) {
						BACheetah._disabledModules.push(k);
						BACheetah._disabledModulesFull[k] = proModules[k];
					}
				}
			} // Else... All modules enabled!!
		},

		/**
		 * Binds most of the events for the builder interface.
		 *

		 * @access private
		 * @method _bindEvents
		 */
		_bindEvents: function()
		{
			var isTouch = BACheetahLayout._isTouch();

			/* Links */
			$excludedLinks = $('.ba-cheetah-bar a, .ba-cheetah--content-library-panel a, .ba-cheetah-page-nav .nav a'); // links in ui shouldn't be disabled.
			$('a').not($excludedLinks).on('click', BACheetah._preventDefault);
			$('.ba-cheetah-page-nav .nav a').on('click', BACheetah._headerLinkClicked);
			$('body').delegate('.ba-cheetah-content a', 'click', BACheetah._preventDefault);
			$('body').delegate('button.ba-cheetah-button', 'mouseup', this._buttonMouseUp.bind(this) );

			/* Heartbeat */
			$(document).on('heartbeat-tick', BACheetah._initPostLock);

			/* Unload Warning */
			$(window).on('beforeunload', BACheetah._warnBeforeUnload);

			/* Submenus */
			$('body').delegate('.ba-cheetah-has-submenu', 'click touchend', BACheetah._submenuParentClicked);
			$('body').delegate('.ba-cheetah-has-submenu a', 'click touchend', BACheetah._submenuChildClicked);
			$('body').delegate('.ba-cheetah-submenu', 'mouseenter', BACheetah._submenuMouseenter);
			$('body').delegate('.ba-cheetah-submenu', 'mouseleave', BACheetah._submenuMouseleave);
			$('body').delegate('.ba-cheetah-submenu .ba-cheetah-has-submenu', 'mouseenter', BACheetah._submenuNestedParentMouseenter);

			/* Panel */
			$('.ba-cheetah-panel-actions .ba-cheetah-panel-close').on('click', BACheetah._closePanel);
			$('.ba-cheetah-blocks-section-title').on('click', BACheetah._blockSectionTitleClicked);
			$('body').delegate('.ba-cheetah-node-template-actions', 'mousedown', BACheetah._stopPropagation);
			$('body').delegate('.ba-cheetah-node-template-edit', 'mousedown', BACheetah._stopPropagation);
			$('body').delegate('.ba-cheetah-node-template-delete', 'mousedown', BACheetah._stopPropagation);
			$('body').delegate('.ba-cheetah-node-template-edit', 'click', BACheetah._editNodeTemplateClicked);
			$('body').delegate('.ba-cheetah-node-template-delete', 'click', BACheetah._deleteNodeTemplateClicked);
			$('body').delegate('.ba-cheetah-block:not(.ba-cheetah-block-disabled)', 'mousedown', BACheetah._blockDragInit );
			$('body').on('mouseup', BACheetah._blockDragCancel);

			/* Actions Lightbox */
			$('body').delegate('.ba-cheetah-actions .ba-cheetah-cancel-button', 'click', BACheetah._cancelButtonClicked);

			/* Tools Actions */
			$('body').delegate('.ba-cheetah-save-user-template-button', 'click', BACheetah._saveUserTemplateClicked);
			$('body').delegate('.ba-cheetah-duplicate-layout-button', 'click', BACheetah._duplicateLayoutClicked);
			$('body').delegate('.ba-cheetah-layout-settings-button', 'click', BACheetah._layoutSettingsClicked);
			$('body').delegate('.ba-cheetah-layout-settings .ba-cheetah-settings-save', 'click', BACheetah._saveLayoutSettingsClicked);
			$('body').delegate('.ba-cheetah-layout-settings .ba-cheetah-settings-cancel', 'click', BACheetah._cancelLayoutSettingsClicked);
			$('body').delegate('.ba-cheetah-global-settings-button', 'click', BACheetah._globalSettingsClicked);
			$('body').delegate('.ba-cheetah-set-page-template-button', 'click', BACheetah._pageSettingsClicked);
			$('body').delegate('.ba-cheetah-global-settings .ba-cheetah-settings-save', 'click', BACheetah._saveGlobalSettingsClicked);
			$('body').delegate('.ba-cheetah-page-settings .ba-cheetah-settings-save', 'click', BACheetah._savePageSettingsClicked);
			$('body').delegate('.ba-cheetah-global-settings .ba-cheetah-settings-cancel', 'click', BACheetah._cancelLayoutSettingsClicked);

			/* Template Panel Tab */
			$('body').delegate('.ba-cheetah-user-template', 'click', BACheetah._userTemplateClicked);
			$('body').delegate('.ba-cheetah-user-template-edit', 'click', BACheetah._editUserTemplateClicked);
			$('body').delegate('.ba-cheetah-user-template-delete', 'click', BACheetah._deleteUserTemplateClicked);
			$('body').delegate('.ba-cheetah-template-replace-button', 'click', BACheetah._templateReplaceClicked);
			$('body').delegate('.ba-cheetah-template-append-button', 'click', BACheetah._templateAppendClicked);
			$('body').delegate('.ba-cheetah-template-actions .ba-cheetah-cancel-button', 'click', BACheetah._templateCancelClicked);

			/* User Template Settings */
			$('body').delegate('.ba-cheetah-user-template-settings .ba-cheetah-settings-save', 'click', BACheetah._saveUserTemplateSettings);

			/* Help Actions */
			$('body').delegate('.ba-cheetah-help-tour-button', 'click', BACheetah._startHelpTour);
			$('body').delegate('.ba-cheetah-knowledge-base-button', 'click', BACheetah._viewKnowledgeBaseClicked);
			$('body').delegate('.ba-cheetah-forums-button', 'click', BACheetah._visitForumsClicked);

			/* Alert Lightbox */
			$('body').delegate('.ba-cheetah-alert-close', 'click', BACheetah._alertClose);
			$('body').delegate('.ba-cheetah-feedback-close', 'click', BACheetah._closeFeedback);			

			/* General Overlays */
			$('body').delegate('.ba-cheetah-block-overlay', 'contextmenu', BACheetah._onContextmenu);

			/* Rows */
			$('body').delegate('.ba-cheetah-row-overlay .ba-cheetah-block-remove', 'click touchend', BACheetah._deleteRowClicked);
			$('body').delegate('.ba-cheetah-row-overlay .ba-cheetah-block-copy', 'click touchend', BACheetah._rowCopyClicked);
			$('body').delegate('.ba-cheetah-row-overlay .ba-cheetah-block-move', 'mousedown', BACheetah._rowDragInit);
			$('body').delegate('.ba-cheetah-row-overlay .ba-cheetah-block-move', 'touchstart', BACheetah._rowDragInitTouch);
			$('body').delegate('.ba-cheetah-row-overlay .ba-cheetah-block-settings', 'click touchend', BACheetah._rowSettingsClicked);
			$('body').delegate('.ba-cheetah-row-settings .ba-cheetah-settings-save', 'click', BACheetah._saveSettings);
			// Row touch or mouse specific events.
			if ( isTouch ) {
				$('body').delegate('.ba-cheetah-row-overlay', 'touchend', BACheetah._rowSettingsClicked);
			} else {
				$('body').delegate('.ba-cheetah-row-overlay', 'click', BACheetah._rowSettingsClicked);
			}

			/* Rows Submenu */
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-row-reset', 'click touchend', BACheetah._resetRowWidthClicked);

			/* Columns */
			$('body').delegate('.ba-cheetah-col-overlay .ba-cheetah-block-move', 'mousedown', BACheetah._colDragInit);
			$('body').delegate('.ba-cheetah-col-overlay .ba-cheetah-block-move', 'touchstart', BACheetah._colDragInitTouch);
			$('body').delegate('.ba-cheetah-block-col-copy', 'click touchend', BACheetah._copyColClicked);
			$('body').delegate('.ba-cheetah-col-overlay .ba-cheetah-block-remove', 'click touchend', BACheetah._deleteColClicked);
			$('body').delegate('.ba-cheetah-col-overlay .ba-cheetah-block-settings', 'click touchend', BACheetah._colSettingsClicked);
			$('body').delegate('.ba-cheetah-col-settings .ba-cheetah-settings-save', 'click', BACheetah._saveSettings);

			// Column touch or mouse specific events.
			if ( isTouch ) {
				$('body').delegate('.ba-cheetah-col-overlay', 'touchend', BACheetah._colSettingsClicked);
			} else {
				$('body').delegate('.ba-cheetah-col-overlay', 'click', BACheetah._colSettingsClicked);
			}

			/* Columns Submenu */
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-move', 'mousedown', BACheetah._colDragInit);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-move', 'touchstart', BACheetah._colDragInitTouch);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-edit', 'click touchend', BACheetah._colSettingsClicked);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-delete', 'click touchend', BACheetah._deleteColClicked);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-reset', 'click touchend', BACheetah._resetColumnWidthsClicked);
			$('body').delegate('.ba-cheetah-block-col-submenu li', 'mouseenter', BACheetah._showColHighlightGuide);
			$('body').delegate('.ba-cheetah-block-col-submenu li', 'mouseleave', BACheetah._removeColHighlightGuides);

			/* Columns Submenu (Parent Column) */
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-move-parent', 'mousedown', BACheetah._colDragInit);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-move-parent', 'touchstart', BACheetah._colDragInitTouch);
			$('body').delegate('.ba-cheetah-block-col-submenu .ba-cheetah-block-col-edit-parent', 'click touchend', BACheetah._colSettingsClicked);

			/* Modules */
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-remove', 'click touchend', BACheetah._deleteModuleClicked);
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-copy', 'click touchend', BACheetah._moduleCopyClicked);
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-move', 'mousedown', BACheetah._moduleDragInit);
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-move', 'touchstart', BACheetah._moduleDragInitTouch);
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-settings', 'click touchend', BACheetah._moduleSettingsClicked);
			$('body').delegate('.ba-cheetah-module-settings .ba-cheetah-settings-save', 'click', BACheetah._saveModuleClicked);
			$('body').delegate('.ba-cheetah-module-overlay .ba-cheetah-block-col-settings', 'click touchend', BACheetah._colSettingsClicked);

			// Module touch or mouse specific events.
			if ( isTouch ) {
				$('body').delegate('.ba-cheetah-module-overlay', 'touchend', BACheetah._moduleSettingsClicked);
			} else {
				$('body').delegate('.ba-cheetah-module-overlay', 'click', BACheetah._moduleSettingsClicked);
			}

			/* Node Templates */
			$('body').delegate('.ba-cheetah-settings-save-as', 'click', BACheetah._showNodeTemplateSettings);
			$('body').delegate('.ba-cheetah-node-template-settings .ba-cheetah-settings-save', 'click', BACheetah._saveNodeTemplate);
			$('body').delegate('.ba-cheetah-content-panel-saved-view .ba-cheetah-settings-section-header', 'click', BACheetah._collapseSavedTemplates);

			/* Settings */
			$('body').delegate('.ba-cheetah-settings-tabs a', 'click', BACheetah._settingsTabClicked);
			$('body').delegate('.ba-cheetah-settings-tabs a', 'show', BACheetah._calculateSettingsTabsOverflow);
			$('body').delegate('.ba-cheetah-settings-tabs a', 'hide', BACheetah._calculateSettingsTabsOverflow);
			$('body').delegate('.ba-cheetah-settings-cancel', 'click', BACheetah._settingsCancelClicked);

			/* Settings Tabs Overflow menu */
			$('body').delegate('.ba-cheetah-settings-tabs-overflow-menu > a', 'click', BACheetah._settingsTabsToOverflowMenuItemClicked.bind(this));
			$('body').delegate('.ba-cheetah-settings-tabs-more', 'click', BACheetah._toggleTabsOverflowMenu.bind(this) );
			$('body').delegate('.ba-cheetah-settings-tabs-overflow-click-mask', 'click', BACheetah._hideTabsOverflowMenu.bind(this));

			/* Tooltips */
			//$('body').delegate('.ba-cheetah-help-tooltip-icon', 'mouseover', BACheetah._showHelpTooltip);
			//$('body').delegate('.ba-cheetah-help-tooltip-icon', 'mouseout', BACheetah._hideHelpTooltip);

			/* Multiple Fields */
			$('body').delegate('.ba-cheetah-field-add', 'click', BACheetah._addFieldClicked);
			$('body').delegate('.ba-cheetah-field-copy', 'click', BACheetah._copyFieldClicked);
			$('body').delegate('.ba-cheetah-field-delete', 'click', BACheetah._deleteFieldClicked);

			/* Photo Fields */
			$('body').delegate('.ba-cheetah-photo-field .ba-cheetah-photo-select', 'click', BACheetah._selectSinglePhoto);
			$('body').delegate('.ba-cheetah-photo-field .ba-cheetah-photo-edit', 'click', BACheetah._selectSinglePhoto);
			$('body').delegate('.ba-cheetah-photo-field .ba-cheetah-photo-replace', 'click', BACheetah._selectSinglePhoto);
			$('body').delegate('.ba-cheetah-photo-field .ba-cheetah-photo-remove', 'click', BACheetah._singlePhotoRemoved);

			/* Multiple Photo Fields */
			$('body').delegate('.ba-cheetah-multiple-photos-field .ba-cheetah-multiple-photos-select', 'click', BACheetah._selectMultiplePhotos);
			$('body').delegate('.ba-cheetah-multiple-photos-field .ba-cheetah-multiple-photos-edit', 'click', BACheetah._selectMultiplePhotos);
			$('body').delegate('.ba-cheetah-multiple-photos-field .ba-cheetah-multiple-photos-add', 'click', BACheetah._selectMultiplePhotos);

			/* Video Fields */
			$('body').delegate('.ba-cheetah-video-field .ba-cheetah-video-select', 'click', BACheetah._selectSingleVideo);
			$('body').delegate('.ba-cheetah-video-field .ba-cheetah-video-replace', 'click', BACheetah._selectSingleVideo);
			$('body').delegate('.ba-cheetah-video-field .ba-cheetah-video-remove', 'click', BACheetah._singleVideoRemoved);

			/* Multiple Audio Fields */
			$('body').delegate('.ba-cheetah-multiple-audios-field .ba-cheetah-multiple-audios-select', 'click', BACheetah._selectMultipleAudios);
			$('body').delegate('.ba-cheetah-multiple-audios-field .ba-cheetah-multiple-audios-edit', 'click', BACheetah._selectMultipleAudios);
			$('body').delegate('.ba-cheetah-multiple-audios-field .ba-cheetah-multiple-audios-add', 'click', BACheetah._selectMultipleAudios);

			/* Icon Fields */
			$('body').delegate('.ba-cheetah-icon-field .ba-cheetah-icon-select', 'click', BACheetah._selectIcon);
			$('body').delegate('.ba-cheetah-icon-field .ba-cheetah-icon-replace', 'click', BACheetah._selectIcon);
			$('body').delegate('.ba-cheetah-icon-field .ba-cheetah-icon-remove', 'click', BACheetah._removeIcon);

			/* Settings Form Fields */
			$('body').delegate('.ba-cheetah-form-field .ba-cheetah-form-field-edit', 'click', BACheetah._formFieldClicked);
			$('body').delegate('.ba-cheetah-form-field-settings .ba-cheetah-settings-save', 'click', BACheetah._saveFormFieldClicked);

			/* Layout Fields */
			$('body').delegate('.ba-cheetah-layout-field-option', 'click', BACheetah._layoutFieldClicked);

			/* Links Fields */
			$('body').delegate('.ba-cheetah-link-field-select', 'click', BACheetah._linkFieldSelectClicked);
			$('body').delegate('.ba-cheetah-link-field-search-cancel', 'click', BACheetah._linkFieldSelectCancelClicked);

			/* Loop Settings Fields */
			//$('body').delegate('.ba-cheetah-loop-data-source-select select[name=data_source]', 'change', BACheetah._loopDataSourceChange);
			$('body').delegate('.ba-cheetah-custom-query select[name=post_type]', 'change', BACheetah._customQueryPostTypeChange);

			/* Text Fields - Add Predefined Value Selector */
			$('body').delegate('.ba-cheetah-text-field-add-value', 'change', BACheetah._textFieldAddValueSelectChange);

			/* Number Fields */
			$('body').delegate('.ba-cheetah-field input[type=number]', 'focus', BACheetah._onNumberFieldFocus );
			$('body').delegate('.ba-cheetah-field input[type=number]', 'blur', BACheetah._onNumberFieldBlur );

			// Live Preview
			BACheetah.addHook( 'didCompleteAJAX', BACheetah._refreshSettingsPreviewReference );
			BACheetah.addHook( 'didRenderLayoutComplete', BACheetah._refreshSettingsPreviewReference );
		},

		/**
		 * Remove events when ending the edit session

		 * @access private
		 */
		_unbindEvents: function() {
			$('a').off('click', BACheetah._preventDefault);
			$('.ba-cheetah-page-nav .nav a').off('click', BACheetah._headerLinkClicked);
			$('body').undelegate('.ba-cheetah-content a', 'click', BACheetah._preventDefault);
		},

		/**
		 * Rebind events when restarting the edit session

		 * @access private
		 */
		_rebindEvents: function() {
			$('a').on('click', BACheetah._preventDefault);
			$('.ba-cheetah-page-nav .nav a').on('click', BACheetah._headerLinkClicked);
			$('body').delegate('.ba-cheetah-content a', 'click', BACheetah._preventDefault);
		},

		/**
		 * Binds the events for overlays that appear when
		 * mousing over a row, column or module.
		 *

		 * @access private
		 * @method _bindOverlayEvents
		 */
		_bindOverlayEvents: function()
		{
			var content = $(BACheetah._contentClass);

			content.delegate('.ba-cheetah-row', 'mouseenter touchstart', BACheetah._rowMouseenter);
			content.delegate('.ba-cheetah-row', 'mouseleave', BACheetah._rowMouseleave);
			// content.delegate('.ba-cheetah-row-overlay', 'mouseleave', BACheetah._rowMouseleave);
			content.delegate('.ba-cheetah-col', 'mouseenter touchstart', BACheetah._colMouseenter);
			content.delegate('.ba-cheetah-col', 'mouseleave', BACheetah._colMouseleave);
			
			content.delegate('.ba-cheetah-module', 'mouseenter touchstart', BACheetah._moduleMouseenter);
			content.delegate('.ba-cheetah-row-type-row-inside .ba-cheetah-module', 'mousemove touchstart', BACheetah._moduleMouseenter);

			content.delegate('.ba-cheetah-module', 'mouseleave', BACheetah._moduleMouseleave);
		},

		/**
		 * Unbinds the events for overlays that appear when
		 * mousing over a row, column or module.
		 *

		 * @access private
		 * @method _destroyOverlayEvents
		 */
		_destroyOverlayEvents: function()
		{
			var content = $(BACheetah._contentClass);

			content.undelegate('.ba-cheetah-row', 'mouseenter touchstart', BACheetah._rowMouseenter);
			content.undelegate('.ba-cheetah-row', 'mouseleave', BACheetah._rowMouseleave);
			// content.undelegate('.ba-cheetah-row-overlay', 'mouseleave', BACheetah._rowMouseleave);
			content.undelegate('.ba-cheetah-col', 'mouseenter touchstart', BACheetah._colMouseenter);
			content.undelegate('.ba-cheetah-col', 'mouseleave', BACheetah._colMouseleave);
			content.undelegate('.ba-cheetah-module', 'mouseenter touchstart mousemove', BACheetah._moduleMouseenter);
			content.undelegate('.ba-cheetah-module', 'mouseleave', BACheetah._moduleMouseleave);
		},

		/**
		 * Hides overlays when the contextmenu event is fired on them.
		 * This allows us to inspect the actual node in the console
		 * instead of getting the overlay.
		 *

		 * @access private
		 * @method _onContextmenu
		 * @param {Object} e The event object.
		 */
		_onContextmenu: function( e )
		{
		    $( this ).hide();
		},

		/**
		 * Prevents the default action for an event.
		 *

		 * @access private
		 * @method _preventDefault
		 * @param {Object} e The event object.
		 */
		_preventDefault: function( e )
		{
			e.preventDefault();
		},

		/**
		 * Prevents propagation of an event.
		 *

		 * @access private
		 * @method _stopPropagation
		 * @param {Object} e The event object.
		 */
		_stopPropagation: function( e )
		{
			e.stopPropagation();
		},

		/**
		 * Launches the builder for another page if a link in the
		 * builder theme header is clicked.
		 *

		 * @access private
		 * @method _headerLinkClicked
		 * @param {Object} e The event object.
		 */
		_headerLinkClicked: function(e)
		{
			var link = $(this),

			href = link.attr('href');

			// ignore links with a #hash
			if( this.hash ) {
				return;
			}

			e.preventDefault();

			if ( BACheetahConfig.isUserTemplate )  {
				return;
			}

			BACheetah._exitUrl = href.indexOf('?') > -1 ? href : href + '?ba_builder';
			BACheetah.triggerHook('triggerDone');
		},

		/**
		 * Warns the user that their changes won't be saved if
		 * they leave the page while editing settings.
		 *

		 * @access private
		 * @method _warnBeforeUnload
		 * @return {String} The warning message.
		 */
		_warnBeforeUnload: function()
		{
			var rowSettings     = $('.ba-cheetah-row-settings').length > 0,
				colSettings     = $('.ba-cheetah-col-settings').length > 0,
				moduleSettings  = $('.ba-cheetah-module-settings').length > 0;

			if(rowSettings || colSettings || moduleSettings) {
				return BACheetahStrings.unloadWarning;
			}
		},

		/* Lite Version
		----------------------------------------------------------*/

		/**
		 * Opens a new window with the upgrade URL when the
		 * upgrade button is clicked.
		 *

		 * @access private
		 * @method _proClicked
		 */
		 _proClicked: function()
		 {
			 window.open(BACheetahConfig.landingpageUrl);
		 },

		/**
		 * Opens a new window with the upgrade URL when the
		 * upgrade button is clicked.
		 *

		 * @access private
		 * @method _upgradeClicked
		 */
		_upgradeClicked: function()
		{
			window.open(BACheetahConfig.upgradeUrl);
		},

		/**
		 * Toggles the pro module section in lite.
		 *

		 */

		/*
		_toggleProModules: function()
		{
			var button = $( '.ba-cheetah-blocks-pro-expand' ),
				closed = $( '.ba-cheetah-blocks-pro-closed' ),
				open = $( '.ba-cheetah-blocks-pro-open' );

			button.toggleClass( 'ba-cheetah-blocks-pro-expand-rotate' );

			if ( closed.length ) {
				closed.removeClass( 'ba-cheetah-blocks-pro-closed' );
				closed.addClass( 'ba-cheetah-blocks-pro-open' );
			} else {
				open.removeClass( 'ba-cheetah-blocks-pro-open' );
				open.addClass( 'ba-cheetah-blocks-pro-closed' );
			}
		},
		*/

		/**
		 * Shows the the pro message lightbox.
		 *

		 */
		_showProMessage: function( feature )
		{
			var alert = new BACheetahLightbox({
					className: 'ba-cheetah-pro-lightbox',
					destroyOnClose: true
				}),
				template = wp.template( 'ba-cheetah-pro-lightbox' );

			alert.open( template( { feature : feature } ) );
		},

		_showFeedbackMessage: function()
		{
			var alert = new BACheetahLightbox({
				className: 'ba-cheetah-feedback-alert',
				destroyOnClose: false
			}),
			template = wp.template( 'ba-cheetah-feedback-alert' );

			alert.open( template() );
		},

		/* TipTips
		----------------------------------------------------------*/

		/**
		 * Initializes tooltip help messages.
		 *

		 * @access private
		 * @method _initTipTips
		 */
		_initTipTips: function()
		{
			var tips = $('.ba-cheetah-tip:not(.ba-cheetah-has-tip)');

			tips.each( function(){
				var ele = $( this );
				ele.addClass( 'ba-cheetah-has-tip' );
				if ( undefined == ele.attr( 'data-title' ) ) {
					ele.attr( 'data-title', ele.attr( 'title' ) );
				}
			} )

			if ( ! BACheetahLayout._isTouch() ) {
				tips.tipTip( {
				defaultPosition : 'top',
				delay           : 300,
				maxWidth        : 'auto'
				} );
			}
		},

		/**
		 * Removes all tooltip help messages from the screen.
		 *

		 * @access private
		 * @method _hideTipTips
		 */
		_hideTipTips: function()
		{
			$('#tiptip_holder').stop().hide();
		},

		/* Submenus
		----------------------------------------------------------*/

		/**
		 * Callback for when the parent of a submenu is clicked.
		 *

		 * @access private
		 * @method _submenuParentClicked
		 * @param {Object} e The event object.
		 */
		_submenuParentClicked: function( e )
		{
			var body     = $( 'body' ),
				parent 	 = $( this ),
				submenu  = parent.find( '.ba-cheetah-submenu' );

			if ( parent.hasClass( 'ba-cheetah-submenu-open' ) ) {
				body.removeClass( 'ba-cheetah-submenu-open' );
				parent.removeClass( 'ba-cheetah-submenu-open' );
				parent.removeClass( 'ba-cheetah-submenu-right' );
			}
			else {

				if( parent.offset().left + submenu.width() > $( window ).width() ) {
					parent.addClass( 'ba-cheetah-submenu-right' );
				}

				body.addClass( 'ba-cheetah-submenu-open' );
				parent.addClass( 'ba-cheetah-submenu-open' );
			}

			submenu.closest('.ba-cheetah-row-overlay').addClass('ba-cheetah-row-menu-active');

			BACheetah._hideTipTips();
			e.preventDefault();
			e.stopPropagation();
		},

		/**
		 * Callback for when the child of a submenu is clicked.
		 *

		 * @access private
		 * @method _submenuChildClicked
		 * @param {Object} e The event object.
		 */
		_submenuChildClicked: function( e )
		{
			var body   = $( 'body' ),
				parent = $( this ).parents( '.ba-cheetah-has-submenu' );

			if ( ! parent.parents( '.ba-cheetah-has-submenu' ).length ) {
				body.removeClass( 'ba-cheetah-submenu-open' );
				parent.removeClass( 'ba-cheetah-submenu-open' );
			}
		},

		/**
		 * Callback for when the mouse enters a submenu.
		 *

		 * @access private
		 * @method _submenuMouseenter
		 * @param {Object} e The event object.
		 */
		_submenuMouseenter: function( e )
		{
			var menu 	= $( this ),
				timeout = menu.data( 'timeout' );

			if ( 'undefined' != typeof timeout ) {
				clearTimeout( timeout );
			}
		},

		/**
		 * Callback for when the mouse leaves a submenu.
		 *

		 * @access private
		 * @method _submenuMouseleave
		 * @param {Object} e The event object.
		 */
		_submenuMouseleave: function( e )
		{
			var body    = $( 'body' ),
				menu 	= $( this ),
				timeout = setTimeout( function() {
					body.removeClass( 'ba-cheetah-submenu-open' );
					menu.closest( '.ba-cheetah-has-submenu' ).removeClass( 'ba-cheetah-submenu-open z-index-up' );
				}, 500 );

			menu.closest('.ba-cheetah-row-overlay').removeClass('ba-cheetah-row-menu-active');

			menu.data( 'timeout', timeout );
		},

		/**
		 * Callback for when the mouse enters the parent
		 * of a nested submenu.
		 *

		 * @access private
		 * @method _submenuNestedParentMouseenter
		 * @param {Object} e The event object.
		 */
		_submenuNestedParentMouseenter: function( e )
		{
			var parent 	 = $( this ),
				submenu  = parent.find( '.ba-cheetah-submenu' );

			if( parent.width() + parent.offset().left + submenu.width() > $( window ).width() ) {
				parent.addClass( 'ba-cheetah-submenu-right' );
			}
		},

		/**
		 * Closes all open submenus.
		 *

		 * @access private
		 * @method _closeAllSubmenus
		 */
		_closeAllSubmenus: function()
		{
			$( '.ba-cheetah-submenu-open' ).removeClass( 'ba-cheetah-submenu-open' );
		},

		/* Bar
		----------------------------------------------------------*/

		/**
		 * Fires blur on mouse up to avoid focus ring when clicked with mouse.
		 *

		 * @access private
		 * @method _buttonMouseUp
		 * @param {Event} e
		 * @return void
		 */
		_buttonMouseUp: function(e) {
			$(e.currentTarget).blur();
		},

		/* Panel
		----------------------------------------------------------*/

		/**
		 * Closes the builder's content panel.
		 *

		 * @access private
		 * @method _closePanel
		 */
		_closePanel: function()
		{
			BACheetah.triggerHook('hideContentPanel');
		},

		/**
		 * Opens the builder's content panel.
		 *

		 * @access private
		 * @method _showPanel
		 */
		_showPanel: function()
		{
			BACheetah.triggerHook('showContentPanel');
		},

		/**
		 * Toggle the panel open or closed.
		 *

		 * @access private
		 * @method _togglePanel
		 */
		_togglePanel: function()
		{
			BACheetah.triggerHook('toggleContentPanel');
		},

		/**
		 * Opens or closes a section in the builder's content panel
		 * when a section title is clicked.
		 *

		 * @access private
		 * @method _blockSectionTitleClicked
		 */
		_blockSectionTitleClicked: function()
		{
			var title   = $(this),
				section = title.parent();

			if(section.hasClass('ba-cheetah-active')) {
				section.removeClass('ba-cheetah-active');
			}
			else {
				$('.ba-cheetah-blocks-section').removeClass('ba-cheetah-active');
				section.addClass('ba-cheetah-active');
			}

			BACheetah._initScrollbars();
		},

		/* Save Actions
		----------------------------------------------------------*/

		/**
		* Publish the current layout
		*

		* @access private
		* @method _publishLayout
		* @param {Boolean} shouldExit Whether or not builder should exit after publish
		* @param {Boolean} openLightbox Whether or not to keep the lightboxes open.
		* @return void
		*/
		_publishLayout: function( shouldExit, openLightbox ) {
			// Save existing settings first if any exist. Don't proceed if it fails.
			if ( ! BACheetah._triggerSettingsSave( openLightbox, true ) ) {
				return;
			}

			if ( _.isUndefined( shouldExit ) ) {
				var shouldExit = true;
			}

			BACheetah.ajax( {
				action: 'save_layout',
				publish: true,
				exit: shouldExit ? 1 : 0,
			}, this._onPublishComplete.bind( this, shouldExit ) );
		},

		/**
		 * Publishes the layout when the publish button is clicked.
		 *

		 * @access private
		 * @param bool whether or not builder should exit after publish
		 * @method _publishButtonClicked
		 */
		_publishButtonClicked: function( shouldExit )
		{
			BACheetah._publishLayout( shouldExit );
		},

		/**
		 * Fires on successful ajax publish.
		 *

		 * @access private
		 * @param bool whether or not builder should exit after publish
		 * @return void
		 */
		_onPublishComplete: function( shouldExit ) {
			if ( shouldExit ) {
				if ( BACheetahConfig.shouldRefreshOnPublish ) {
					BACheetah._exit();
				} else {
					BACheetah._exitWithoutRefresh();
				}
			}

			// Change the admin bar status dot to green if it isn't already
			$('#wp-admin-bar-ba-cheetah-frontend-edit-link .ba-cheetah-admin-bar-status-dot').css('color', '#6bc373');

			BACheetah.triggerHook( 'didPublishLayout', {
				shouldExit: shouldExit,
			} );

			// force reload the page
			if (BACheetahConfig.postType == 'page' || BACheetahConfig.postType == 'post') {
				if (!BACheetah._displaySurvey()) {
					BACheetah._exit();
				}
			}
		},

		/**
		 * Maybe display a satisfaction survey after publish a layout
		 * @returns bool
		 */

		_displaySurvey: function() {
			let shoulddDisplaySurvey = false;
			let expires = localStorage.getItem('_ba_cheetah_feedback_expires');
			let now = new Date();
			if (expires) {
				// dont show again clicked
				if (expires == 'forever') {
					shoulddDisplaySurvey = false;
				}
				else {
					// compare stored date
					expires = new Date(parseInt(expires))
					console.log(expires, now)
					if (expires < now) {
						shoulddDisplaySurvey = true;
					}
				}
			} else {
				// no data in localStorage
				shoulddDisplaySurvey = true;
			}

			if (shoulddDisplaySurvey) {
				// update next display
				const nextDisplay = now.setDate(now.getDate() + 60); // now.setMinutes(now.getMinutes() + 5)
				localStorage.setItem('_ba_cheetah_feedback_expires', nextDisplay)
				BACheetah._showFeedbackMessage()
			}
			return shoulddDisplaySurvey;
		},

		/**
		 * Close feedback button with settimeout to avoid popup block
		 */
		_closeFeedback: function () {
			const checked = $('.ba-cheetah-feedback-alert #ba-cheetah-feedback').prop('checked')
			if (checked) {
				localStorage.setItem('_ba_cheetah_feedback_expires', 'forever')
			}
			setTimeout(function(){
				BACheetah._exit();
			}, 1000)
		},

		/**
		 * Exits the builder when the save draft button is clicked.
		 *

		 * @access private
		 * @method _draftButtonClicked
		 */
		_draftButtonClicked: function()
		{
			BACheetah.showAjaxLoader();

			BACheetah.ajax({
				action: 'save_draft'
			}, BACheetah._exit);
		},

		/**
		 * Clears changes to the layout when the discard draft button
		 * is clicked.
		 *

		 * @access private
		 * @method _discardButtonClicked
		 */
		_discardButtonClicked: function()
		{
			var result = confirm(BACheetahStrings.discardMessage);

			if(result) {

				BACheetah.showAjaxLoader();

				BACheetah.ajax({
					action: 'clear_draft_layout'
				}, function() {
					BACheetah.triggerHook('didDiscardChanges');
					BACheetah._exit();
				});
			} else {
				BACheetah.triggerHook('didCancelDiscard');
			}
		},

		/**
		 * Closes the actions lightbox when the cancel button is clicked.
		 *

		 * @access private
		 * @method _cancelButtonClicked
		 */
		_cancelButtonClicked: function()
		{
			BACheetah._exitUrl = null;
			BACheetah._actionsLightbox.close();
		},

		/**
		 * Redirects the user to the _exitUrl if defined, otherwise
		 * it redirects the user to the current post without the
		 * builder active.
		 *


		 * @access private
		 * @method _exit
		 */
		_exit: function()
		{
			var href = window.location.href;

			try {
				var bacheetah = typeof window.opener.BACheetah != 'undefined'
			}
			catch(err) {
				var bacheetah = false
			}

			if ( BACheetahConfig.isUserTemplate && typeof window.opener != 'undefined' && window.opener ) {

				if ( bacheetah ) {
					if ( 'undefined' === typeof BACheetahGlobalNodeId ) {
						window.opener.BACheetah._updateLayout();
					} else {
						window.opener.BACheetah._updateNode( BACheetahGlobalNodeId );
					}
				}

				window.close();
			}
			else {

				if ( BACheetah._exitUrl ) {
					href = BACheetah._exitUrl;
				}
				else {
					href = href.replace( '?ba_cheetah&', '?' );
					href = href.replace( '?ba_cheetah', '' );
					href = href.replace( '&ba_cheetah', '' );

					href = href.replace( '?ba_builder&', '?' );
					href = href.replace( '?ba_builder', '' );
					href = href.replace( '&ba_builder', '' );
				}

				window.location.href = href;
			}
		},

		/**
		 * Allow the editing session to end but don't redirect to any url.
		 *

		 * @return void
		 */
		_exitWithoutRefresh: function() {
			var href = window.location.href;

			try {
				var bacheetah = typeof window.opener.BACheetah != 'undefined'
			}
			catch(err) {
				var bacheetah = false
			}

			if ( BACheetahConfig.isUserTemplate && bacheetah && window.opener ) {

				if ( bacheetah ) {
					if ( 'undefined' === typeof BACheetahGlobalNodeId ) {
						window.opener.BACheetah._updateLayout();
					} else {
						window.opener.BACheetah._updateNode( BACheetahGlobalNodeId );
					}
				}

				window.close();
			}
			else {
				BACheetah.triggerHook('endEditingSession');
			}
		},

		/* Tools Actions
		----------------------------------------------------------*/

		/**
		 * Duplicates the current post and builder layout.
		 *

		 * @access private
		 * @method _duplicateLayoutClicked
		 */
		_duplicateLayoutClicked: function()
		{
			BACheetah.showAjaxLoader();

			BACheetah.ajax({
				action: 'duplicate_post'
			}, BACheetah._duplicateLayoutComplete);
		},

		/**
		 * Redirects the user to the post edit screen of a
		 * duplicated post when duplication is complete.
		 *

		 * @access private
		 * @method _duplicatePageComplete
		 * @param {Number} The ID of the duplicated post.
		 */
		_duplicateLayoutComplete: function(response)
		{
			var adminUrl = BACheetahConfig.adminUrl;

			window.location.href = adminUrl + 'post.php?post='+ response +'&action=edit';
		},

		/* Layout Settings
		----------------------------------------------------------*/

		/**
		 * Shows the layout settings lightbox when the layout
		 * settings button is clicked.
		 *

		 * @access private
		 * @method _layoutSettingsClicked
		 */
		_layoutSettingsClicked: function()
		{
			BACheetahSettingsForms.render( {
				id        : 'layout',
				className : 'ba-cheetah-layout-settings',
				settings  : BACheetahSettingsConfig.settings.layout
			}, function() {
				BACheetah._layoutSettingsInitCSS();
			} );
		},

		/**
		 * Initializes custom layout CSS for live preview.
		 *

		 * @access private
		 * @method _layoutSettingsInitCSS
		 */
		_layoutSettingsInitCSS: function()
		{
			var css = $( '.ba-cheetah-settings #ba-cheetah-field-css textarea:not(.ace_text-input)' );

			css.on( 'change', BACheetah._layoutSettingsCSSChanged );

			BACheetah._layoutSettingsCSSCache = css.val();
		},

		/**
		 * Sets a timeout for throttling custom layout CSS changes.
		 *

		 * @access private
		 * @method _layoutSettingsCSSChanged
		 */
		_layoutSettingsCSSChanged: function()
		{
			if ( BACheetah._layoutSettingsCSSTimeout ) {
				clearTimeout( BACheetah._layoutSettingsCSSTimeout );
			}

			BACheetah._layoutSettingsCSSTimeout = setTimeout( $.proxy( BACheetah._layoutSettingsCSSDoChange, this ), 600 );
		},

		/**
		 * Updates the custom layout CSS when changes are made in the editor.
		 *

		 * @access private
		 * @method _layoutSettingsCSSDoChange
		 */
		_layoutSettingsCSSDoChange: function()
		{
			var form	 = $( '.ba-cheetah-settings' ),
				textarea = $( this ),
				field    = textarea.parents( '#ba-cheetah-field-css' );

			if ( field.find( '.ace_error' ).length > 0 ) {
				return;
			}
			else if ( form.hasClass( 'ba-cheetah-layout-settings' ) ) {
				$( '#ba-cheetah-layout-css' ).html( textarea.val() );
			}
			else {
				$( '#ba-cheetah-global-css' ).html( textarea.val() );
			}

			BACheetah._layoutSettingsCSSTimeout = null;
		},

		/**
		 * Saves the layout settings when the save button is clicked.
		 *

		 * @access private
		 * @method _saveLayoutSettingsClicked
		 */
		_saveLayoutSettingsClicked: function()
		{
			var form     = $( this ).closest( '.ba-cheetah-settings' ),
				data     = form.serializeArray(),
				settings = {},
				i        = 0;

			for( ; i < data.length; i++) {
				settings[ data[ i ].name ] = data[ i ].value;
			}

			BACheetah.showAjaxLoader();
			BACheetah._lightbox.close();
			BACheetah._layoutSettingsCSSCache = null;

			BACheetah.ajax( {
				action: 'save_layout_settings',
				settings: settings
			}, function() {
				BACheetah.triggerHook( 'didSaveLayoutSettingsComplete', settings );
				BACheetah._updateLayout();
			} );
		},

		/**
		 * Reverts changes made when the cancel button for the layout
		 * settings has been clicked.
		 *

		 * @access private
		 * @method _cancelLayoutSettingsClicked
		 */
		_cancelLayoutSettingsClicked: function()
		{
			var form = $( '.ba-cheetah-settings' );

			if ( form.hasClass( 'ba-cheetah-layout-settings' ) ) {
				$( '#ba-cheetah-layout-css' ).html( BACheetah._layoutSettingsCSSCache );
			}
			else {
				$( '#ba-cheetah-global-css' ).html( BACheetah._layoutSettingsCSSCache );
			}

			BACheetah._layoutSettingsCSSCache = null;
		},

		/* Global Settings
		----------------------------------------------------------*/

		/**
		 * Shows the global builder settings lightbox when the global
		 * settings button is clicked.
		 *

		 * @access private
		 * @method _globalSettingsClicked
		 */
		_globalSettingsClicked: function()
		{
			BACheetahSettingsForms.render( {
				id        : 'global',
				className : 'ba-cheetah-global-settings',
				settings  : BACheetahSettingsConfig.settings.global
			}, function() {
				BACheetah.preview = new BACheetahPreview( { type: 'global' } );
				BACheetah._layoutSettingsInitCSS();
			} );
		},

		/**
		 * Shows the page settings lightbox when the global
		 * settings button is clicked.
		 *

		 * @access private
		 * @method _globalSettingsClicked
		 */
		
		_pageSettingsClicked: function () {
			BACheetahSettingsForms.render({
				id: 'page',
				className: 'ba-cheetah-page-settings',
				settings: BACheetahSettingsConfig.settings.page
			}, function () {
				BACheetah.preview = new BACheetahPreview( { type: 'page' } );
				BACheetah._initLayoutPartsSelect();
				BACheetah._layoutSettingsInitCSS();
			});
		},

		/**
		 * Allows to reload popups, headers and footers in the page settings by ajax
		 */

		_initLayoutPartsSelect: function()
		{
			BACheetah.getSelectOptions('get_popups', 'popup');
			BACheetah.getSelectOptions('get_headers_for_select', 'ba-cheetah-custom-header-id');
			BACheetah.getSelectOptions('get_footers_for_select', 'ba-cheetah-custom-footer-id');			 
		},

		_savePageSettingsClicked: function () {

			const 	form 		= $(this).closest('.ba-cheetah-settings'),
					valid 		= form.validate().form(),
					newConfig	= BACheetah._getSettings(form),
					oldConfig 	= BACheetah._getOriginalSettings( form );

			// reload if changed layout configs
			const keysNeedReload = [
				'layout',
				'ba-cheetah-custom-footer-id',
				'ba-cheetah-custom-header-id',
				'ba-cheetah-footer-option',
				'ba-cheetah-header-option'
			]
			const needReload = keysNeedReload.some(key => newConfig[key] != oldConfig[key])
			
			if (needReload) {
				if (!confirm(BACheetahStrings.confirmLayoutSave)) {
					return
				}
			}
			
			if (valid) {
				BACheetah.showAjaxLoader();
				BACheetah._layoutSettingsCSSCache = null;
				BACheetahSettingsConfig.settings.page = newConfig

				BACheetah.ajax({
					action: 'save_page_settings',
					settings: newConfig
				}, function() {
					BACheetah._savePageSettingsComplete(needReload)
				});

				BACheetah._lightbox.close();
			}
		},

		/**
		 * Saves the global settings when the save button is clicked.
		 *

		 * @access private
		 * @method _saveGlobalSettingsClicked
		 */
		_saveGlobalSettingsClicked: function()
		{

			var form     = $(this).closest('.ba-cheetah-settings'),
				valid    = form.validate().form(),
				settings = BACheetah._getSettings( form );

			if(valid) {

				BACheetah.showAjaxLoader();
				BACheetah._layoutSettingsCSSCache = null;

				BACheetah.ajax({
					action: 'save_global_settings',
					settings: settings
				}, BACheetah._saveGlobalSettingsComplete);

				BACheetah._lightbox.close();
			}
		},

		/**
		 * Saves the global settings when the save button is clicked.
		 *

		 * @access private
		 * @method _saveGlobalSettingsComplete
		 * @param {String} response
		 */
		_saveGlobalSettingsComplete: function( response )
		{
			BACheetah.triggerHook( 'didSaveGlobalSettingsComplete', BACheetah._jsonParse( response ) );
			BACheetah._updateLayout();
		},

		_savePageSettingsComplete: function( needReload = false )
		{
			if (needReload) {
				location.reload()
			}
		},

		/* Template Selector
		----------------------------------------------------------*/

		/**
		 * Shows the template selector when the builder is launched
		 * if the current layout is empty.
		 *

		 * @access private
		 * @method _initTemplateSelector
		 */
		_initTemplateSelector: function()
		{
			var rows = $(BACheetah._contentClass).find('.ba-cheetah-row'),
				layoutHasContent = ( rows.length > 0 );

			if( ! layoutHasContent ) {
				BACheetah.ContentPanel.show('modules');
			}
		},

		/**
		* Opens a confirmation window when completely replacing editor content
		*
		* @param callback function performed by clicking the ok button
		* @access private
		* @method _confirmReplaceTemplate
		*/

		_confirmReplaceTemplate(callback) {
			const alert = new BACheetahLightbox({
				className: 'ba-cheetah-lightbox-confirm-icon',
				destroyOnClose: true
			}),

			template = wp.template('ba-cheetah-lightbox-confirm-icon');

			alert.open( template( { 
				message 	: BACheetahStrings.changeTemplateMessage,
				title		: BACheetahStrings.areYouSure,
				icon		: 'important-warning.svg',
				cancelable 	: true,
			}));

			alert._node.find('.ba-cheetah-alert-confirm').on('click', callback)
		},

		/**
		* Show options for inserting or appending a template when a template is selected.
		* This logic was moved from `_templateClicked` to unbind it from the specific event.
		*

		* @access private
		* @method _requestTemplateInsert
		*/
		_requestTemplateInsert: function(index, type, content_central_id) {
			// if there are existing rows in the layout
			if( BACheetah.layoutHasContent() ) {

				// If the template is blank, no need to ask
				if(index == 0) { 
					BACheetah._confirmReplaceTemplate(function() {
						BACheetah._lightbox._node.hide();
						BACheetah._applyTemplate(0, false, type, content_central_id);
					})
				}
				// present options Replace or Append
				else {
					BACheetah._selectedTemplateId = index;
					BACheetah._selectedTemplateType = type;
					BACheetah._selectedTemplateContentCentralId = content_central_id;
					BACheetah._showTemplateActions();
					BACheetah._lightbox._node.hide();
				}
			}
			// if there are no rows, just insert the template.
			else {
				BACheetah._applyTemplate(index, false, type, content_central_id);
			}
		},

		/**
		 * Shows the actions lightbox for replacing and appending templates.
		 *

		 * @access private
		 * @method _showTemplateActions
		 */
		_showTemplateActions: function()
		{
			var buttons = [];

			buttons[ 10 ] = {
				'key': 'template-replace',
				'label': BACheetahStrings.templateReplace
			};

			buttons[ 20 ] = {
				'key': 'template-append',
				'label': BACheetahStrings.templateAppend
			};

			BACheetah._showActionsLightbox({
				'className': 'ba-cheetah-template-actions',
				'title': BACheetahStrings.actionsLightboxTitle,
				'buttons': buttons
			});
		},

		/**
		 * Replaces the current layout with a template when the replace
		 * button is clicked.
		 *

		 * @access private
		 * @method _templateReplaceClicked
		 */
		_templateReplaceClicked: function()
		{
			BACheetah._confirmReplaceTemplate(function() {
				BACheetah._actionsLightbox.close();
				BACheetah._applyTemplate(BACheetah._selectedTemplateId, false, BACheetah._selectedTemplateType, BACheetah._selectedTemplateContentCentralId);
			})
		},

		/**
		 * Append a template to the current layout when the append
		 * button is clicked.
		 *

		 * @access private
		 * @method _templateAppendClicked
		 */
		_templateAppendClicked: function()
		{
			BACheetah._actionsLightbox.close();
			BACheetah._applyTemplate(BACheetah._selectedTemplateId, true, BACheetah._selectedTemplateType, BACheetah._selectedTemplateContentCentralId);
		},

		/**
		 * Shows the template selector when the cancel button of
		 * the template actions lightbox is clicked.
		 *

		 * @access private
		 * @method _templateCancelClicked
		 */
		_templateCancelClicked: function()
		{
			BACheetah.triggerHook( 'showContentPanel' );
		},

		/**
		 * Applys a template to the current layout by either appending
		 * it or replacing the current layout with it.
		 *

		 * @access private
		 * @method _applyTemplate
		 * @param {Number} id The template id.
		 * @param {Boolean} append Whether the new template should be appended or not.
		 * @param {String} type The type of template. Either core or user.
		 */
		_applyTemplate: function (id, append, type, content_central_id = 0)
		{
			
			append  = typeof append === 'undefined' || !append ? '0' : '1';
			type    = typeof type === 'undefined' ? 'core' : type;

			BACheetah._lightbox.close();
			BACheetah.showAjaxLoader();

			if(type == 'core') {
				BACheetah.ajax({
					action: 'apply_template',
					template_id: id,
					append: append,
					content_central_id: content_central_id
				}, BACheetah._applyTemplateComplete);
			}
			else {

				BACheetah.ajax({
					action: 'apply_user_template',
					template_id: id,
					append: append
				}, BACheetah._applyUserTemplateComplete);
			}

			BACheetah.triggerHook('didApplyTemplate');
		},

		/**
		 * Callback for when applying a template completes.


		 * @access private
		 * @method _applyTemplateComplete
		 * @param  {String} response
		 */
		_applyTemplateComplete:  function( response )
		{
			var data = BACheetah._jsonParse( response );
			
			if (!BACheetah._hasBaCheetahError( data )) {
				BACheetah._renderLayout( data.layout );
				BACheetah.triggerHook( 'didApplyTemplateComplete', data.config );
			} else {
				BACheetah.hideAjaxLoader();
			}

		},

		/**
		 * Callback for when applying a user template completes.


		 * @access private
		 * @method _applyUserTemplateComplete
		 * @param  {string} response
		 */
		_applyUserTemplateComplete: function( response )
		{
			var data = BACheetah._jsonParse( response );

			if ( null !== data.layout_css ) {
				$( '#ba-cheetah-layout-css' ).html( data.layout_css );
			}

			BACheetah._renderLayout( data.layout );
			BACheetah.triggerHook( 'didApplyTemplateComplete', data.config );
		},

		/* User Template Settings
		----------------------------------------------------------*/

		/**
		 * Shows the settings for saving a user defined template
		 * when the save template button is clicked.
		 *

		 * @access private
		 * @method _saveUserTemplateClicked
		 */
		_saveUserTemplateClicked: function()
		{
			BACheetahSettingsForms.render( {
				id        : 'user_template',
				className : 'ba-cheetah-user-template-settings',
				rules 	  : {
					name: {
						required: true
					}
				}
			} );
		},

		/**
		 * Saves user template settings when the save button is clicked.
		 *

		 * @access private
		 * @method _saveUserTemplateSettings
		 */
		_saveUserTemplateSettings: function()
		{
			var form     = $(this).closest('.ba-cheetah-settings'),
				valid    = form.validate().form(),
				settings = BACheetah._getSettings(form);

			if(valid) {

				BACheetah.ajax({
					action: 'save_user_template',
					settings: settings
				}, BACheetah._saveUserTemplateSettingsComplete);

				BACheetah._lightbox.close();
			}
		},

		/**
		 * Shows a success alert when user template settings have saved.
		 *

		 * @access private
		 * @method _saveUserTemplateSettingsComplete
		 */
		_saveUserTemplateSettingsComplete: function(data)
		{
			if ( !data ) return;
			var data = BACheetah._jsonParse(data);

			BACheetahConfig.contentItems.template.push(data);
			BACheetah.triggerHook('contentItemsChanged');
		},

		/**
		 * Callback for when a user clicks a user defined template in
		 * the template selector.
		 *

		 * @access private
		 * @method _userTemplateClicked
		 */
		_userTemplateClicked: function()
		{
			var id = $(this).attr('data-id');

			if($(BACheetah._contentClass).children('.ba-cheetah-row').length > 0) {

				if(id == 'blank') {
					BACheetah._confirmReplaceTemplate(function() {
						BACheetah._lightbox._node.hide();
						BACheetah._applyTemplate('blank', false, 'user');
					})
				}
				else {
					BACheetah._selectedTemplateId = id;
					BACheetah._selectedTemplateType = 'user';
					BACheetah._showTemplateActions();
					BACheetah._lightbox._node.hide();
				}
			}
			else {
				BACheetah._applyTemplate(id, false, 'user');
			}
		},

		/**
		 * Launches the builder in a new tab to edit a user
		 * defined template when the edit link is clicked.
		 *

		 * @access private
		 * @method _editUserTemplateClicked
		 * @param {Object} e The event object.
		 */
		_editUserTemplateClicked: function(e)
		{
			e.preventDefault();
			e.stopPropagation();

			window.open($(this).attr('href'));
		},

		/**
		 * Deletes a user defined template when the delete link is clicked.
		 *

		 * @access private
		 * @method _deleteUserTemplateClicked
		 * @param {Object} e The event object.
		 */
		_deleteUserTemplateClicked: function(e)
		{
			var template = $( this ).closest( '.ba-cheetah-user-template' ),
				id		 = template.attr( 'data-id' ),
				all		 = $( '.ba-cheetah-user-template[data-id=' + id + ']' ),
				parent   = null,
				index    = null,
				i        = null,
				item     = null;

			if ( confirm( BACheetahStrings.deleteTemplate ) ) {

				BACheetah.ajax( {
					action: 'delete_user_template',
					template_id: id
				} );

				// Remove the item from library
				for(i in BACheetahConfig.contentItems.template) {
					item = BACheetahConfig.contentItems.template[i];
					if (item.postId == id) {
						index = i;
					}
				}
				if (!_.isNull(index)) {
					BACheetahConfig.contentItems.template.splice(index, 1);
					BACheetah.triggerHook('contentItemsChanged');
				}
			}

			e.stopPropagation();
		},

		/* Help Actions
		----------------------------------------------------------*/

		/**
		 * Opens a new window with the knowledge base URL when the
		 * view knowledge base button is clicked.
		 *

		 * @access private
		 * @method _viewKnowledgeBaseClicked
		 */
		_viewKnowledgeBaseClicked: function()
		{
			window.open( BACheetahConfig.help.knowledge_base_url );
		},

		/**
		 * Opens a new window with the forums URL when the
		 * visit forums button is clicked.
		 *

		 * @access private
		 * @method _visitForumsClicked
		 */
		_visitForumsClicked: function()
		{
			window.open( BACheetahConfig.help.forums_url );
		},

		/* Help Tour
		----------------------------------------------------------*/

		/**
		 * Shows the help tour or template selector when the builder
		 * is launched.
		 *

		 * @access private
		 * @method _showTourOrTemplates
		 */
		_showTourOrTemplates: function()
		{
			if ( ! BACheetahConfig.simpleUi && ! BACheetahConfig.isUserTemplate ) {

				BACheetah._initTemplateSelector();
				
				if ( BACheetahConfig.help.tour && BACheetahConfig.newUser) {
					BACheetah._showTourLightbox();
				}
				/*
				else {
					BACheetah._initTemplateSelector();
				}
				*/
			}
		},

		/**
		 * Save browser stats when builder is loaded.

		 */
		_doStats: function() {
			if( 1 == BACheetahConfig.statsEnabled ) {

				args = {
					'screen-width': screen.width,
					'screen-height': screen.height,
					'pixel-ratio': window.devicePixelRatio,
					'user-agent': window.navigator.userAgent,
					'isrtl': BACheetahConfig.isRtl
				}

				BACheetah.ajax({
					action: 'save_browser_stats',
					browser_data: args
				});
			}
		},

		/**
		 * Shows the actions lightbox with a welcome message for new
		 * users asking if they would like to take the tour.
		 *

		 * @access private
		 * @method _showTourLightbox
		 */
		_showTourLightbox: function()
		{

			const alert = new BACheetahLightbox({
				className: 'ba-cheetah-lightbox-confirm-icon',
				destroyOnClose: true
			});

			template = wp.template('ba-cheetah-lightbox-confirm-icon');

			alert.open( template( { 
				message 	: BACheetahStrings.welcomeMessage,
				title		: BACheetahStrings.welcome,
				icon		: 'welcome.svg',
				confirmText : BACheetahStrings.start,
				cancelable 	: false,
			}));

			alert._node.find('.ba-cheetah-alert-confirm').on('click', BACheetahTour.start)

		},

		/**
		 * Starts the help tour.
		 *

		 * @access private
		 * @method _startHelpTour
		 */
		_startHelpTour: function()
		{
			BACheetah._actionsLightbox.close();
			BACheetahTour.start();
		},

		/* Layout
		----------------------------------------------------------*/

		/**
		 * Shows a message to drop a row or module to get started
		 * if the layout is empty.
		 *

		 * @access private
		 * @method _setupEmptyLayout
		 */
		_setupEmptyLayout: function()
		{
			var content = $(BACheetah._contentClass);

			if ( BACheetahConfig.isUserTemplate && 'module' == BACheetahConfig.userTemplateType ) {
				return;
			}
			else if ( BACheetahConfig.isUserTemplate && 'column' == BACheetahConfig.userTemplateType ) {
				return;
			}
			else {
				content.removeClass('ba-cheetah-empty');
				content.find('.ba-cheetah-empty-message').remove();

				if ( ! content.find( '.ba-cheetah-row, .ba-cheetah-block' ).length ) {
					content.addClass('ba-cheetah-empty');
					content.append('<span class="ba-cheetah-empty-message">'+ BACheetahStrings.emptyMessage +'</span>');
					BACheetah._initSortables();
				}
			}
		},

		/**
		 * Sends an AJAX request to re-render a single node.
		 *

		 * @access private
		 * @method _updateNode
		 * @param {String} nodeId
		 * @param {Function} callback
		 */
		_updateNode: function( nodeId, callback )
		{
			if ( ! $( '.ba-cheetah-node-' + nodeId ).length ) {
				return;
			}

			BACheetah._showNodeLoading( nodeId );

			BACheetah.ajax( {
				action  : 'render_node',
				node_id : nodeId
			}, function( response ) {
				BACheetah._renderLayout( BACheetah._jsonParse( response ), callback );
			}.bind( this ) );
		},

		/**
		 * Sends an AJAX request to render the layout and is typically
		 * used as a callback to many of the builder's save operations.
		 *

		 * @access private
		 * @method _updateLayout
		 */
		_updateLayout: function()
		{
			BACheetah.showAjaxLoader();

			BACheetah.ajax({
				action: 'render_layout'
			}, BACheetah._renderLayout);
		},

		/**
		 * Removes the current layout and renders a new layout using
		 * the provided data. Will render a node instead of the layout
		 * if data.partial is true.
		 *

		 * @access private
		 * @method _renderLayout
		 * @param {Object} data The layout data. May also be a JSON encoded string.
		 * @param {Function} callback A function to call when the layout has finished rendering.
		 */
		_renderLayout: function( data, callback )
		{
			if ( BACheetah._layout ) {
				BACheetah._layoutQueue.push( {
					data: data,
					callback: callback,
				} );
			} else {
				BACheetah._layout = new BACheetahAJAXLayout( data, callback );
			}
		},

		/**
		 * Called by the layout's JavaScript file once it's loaded
		 * to finish rendering the layout.
		 *

		 * @access private
		 * @method _renderLayoutComplete
		 */
		_renderLayoutComplete: function()
		{
			if ( BACheetah._layout ) {
				BACheetah._layout._complete();
				BACheetah._layout = null;
			}

			if ( BACheetah._layoutQueue.length ) {
				var item = BACheetah._layoutQueue.shift();
				BACheetah._layout = new BACheetahAJAXLayout( item.data, item.callback );
			}
		},

		/**
		 * Trigger the resize event on the window so elements
		 * in the layout that rely on JavaScript know to resize.
		 *

		 * @access private
		 * @method _resizeLayout
		 */
		_resizeLayout: function()
		{
			$(window).trigger('resize');

			if(typeof YUI !== 'undefined') {
				YUI().use('node-event-simulate', function(Y) {
					Y.one(window).simulate("resize");
				});
			}
		},

		/**
		 * Checks to see if any rows exist in the layout, or if it is blank.
		 *

		 * @method layoutHasContent
		 * @return {Boolean}
		 */
		layoutHasContent: function()
		{
            if( $(BACheetah._contentClass).children('.ba-cheetah-row').length > 0) {
                return true;
            } else {
                return false;
            }
        },

		/**
		 * Initializes MediaElements.js audio and video players.
		 *

		 * @access private
		 * @method _initMediaElements
		 */
		_initMediaElements: function()
		{
			var settings = {};

			if(typeof $.fn.mediaelementplayer != 'undefined') {

				if(typeof _wpmejsSettings !== 'undefined') {
					settings.pluginPath = _wpmejsSettings.pluginPath;
				}

				$('.wp-audio-shortcode, .wp-video-shortcode').not('.mejs-container').mediaelementplayer(settings);
			}
		},

		/* Generic Drag and Drop
		----------------------------------------------------------*/

		/**
		 * Inserts drop targets for nodes such as rows, columns
		 * and column groups since making those all sortables
		 * makes sorting really jumpy.
		 *

		 * @access private
		 * @method _initDropTargets
		 */
		_initDropTargets: function()
		{
			var notGlobal = 'row' == BACheetahConfig.userTemplateType ? '' : ':not(.ba-cheetah-node-global)',
				rows      = $( BACheetah._contentClass + ' .ba-cheetah-row:not(.ba-cheetah-row-type-row-inside)' ),
				row       = null,
				groups    = $( BACheetah._contentClass + ' .ba-cheetah-row' + notGlobal ).find( '.ba-cheetah-col-group' ),
				group     = null,
				cols      = null,
				rootCol   = 'column' == BACheetahConfig.userTemplateType ? $( BACheetah._contentClass + '> .ba-cheetah-col' ).eq(0) : null,
				i         = 0;

			// Remove old drop targets.
			$( '.ba-cheetah-col-drop-target' ).remove();
			$( '.ba-cheetah-col-group-drop-target' ).remove();
			$( '.ba-cheetah-row-drop-target' ).remove();

			// Row drop targets.
			$( BACheetah._contentClass ).append( '<div class="ba-cheetah-drop-target ba-cheetah-row-drop-target"></div>' );
			rows.prepend( '<div class="ba-cheetah-drop-target ba-cheetah-row-drop-target"></div>' );
			rows.append( '<div class="ba-cheetah-drop-target ba-cheetah-drop-target-last ba-cheetah-row-drop-target ba-cheetah-row-drop-target-last"></div>' );

			// Add group drop targets to empty rows.
			for ( ; i < rows.length; i++ ) {

				row = rows.eq( i );

				if ( 0 === row.find( '.ba-cheetah-col-group' ).length ) {
					row.find( '.ba-cheetah-row-content' ).prepend( '<div class="ba-cheetah-drop-target ba-cheetah-col-group-drop-target"></div>' );
				}
			}

			// Add drop target to root parent column.
			if ( rootCol && 0 === groups.length ) {
				groups = rootCol.find( '.ba-cheetah-col-group' );

				rootCol.append( '<div class="ba-cheetah-drop-target ba-cheetah-col-drop-target"></div>' );
				rootCol.append( '<div class="ba-cheetah-drop-target ba-cheetah-drop-target-last ba-cheetah-col-drop-target ba-cheetah-col-drop-target-last"></div>' );
			}

			// Loop through the column groups.
			for ( i = 0; i < groups.length; i++ ) {

				group = groups.eq( i );
				cols  = group.find( '> .ba-cheetah-col' );

				// Column group drop targets.
				if ( ! group.hasClass( 'ba-cheetah-col-group-nested' ) ) {
					group.append( '<div class="ba-cheetah-drop-target ba-cheetah-col-group-drop-target"></div>' );
					group.append( '<div class="ba-cheetah-drop-target ba-cheetah-drop-target-last ba-cheetah-col-group-drop-target ba-cheetah-col-group-drop-target-last"></div>' );
				}

				// Column drop targets.
				cols.append( '<div class="ba-cheetah-drop-target ba-cheetah-col-drop-target"></div>' );
				cols.append( '<div class="ba-cheetah-drop-target ba-cheetah-drop-target-last ba-cheetah-col-drop-target ba-cheetah-col-drop-target-last"></div>' );
			}
		},

		/**
		 * Returns a helper element for a drag operation.
		 *

		 * @access private
		 * @method _blockDragHelper
		 * @param {Object} e The event object.
		 * @param {Object} item The item being dragged.
		 * @return {Object} The helper element.
		 */
		_blockDragHelper: function (e, item)
		{
			var helper = item.clone();

			item.clone().insertAfter(item);
			helper.addClass('ba-cheetah-block-drag-helper');

			return helper;
		},

		/**
		 * Initializes a drag operation.
		 *

		 * @access private
		 * @method _blockDragInit
		 * @param {Object} e The event object.
		 */
		_blockDragInit: function( e )
		{
			var target        = $( e.currentTarget ),
				node          = null,
				scrollTop     = $( window ).scrollTop(),
				initialPos    = 0;

			// Set the _dragEnabled flag.
			BACheetah._dragEnabled = true;

			// Save the initial scroll position.
			BACheetah._dragInitialScrollTop = scrollTop;

			// Get the node to scroll to once the node highlights have affected the body height.
			if ( target.closest( '[data-node]' ).length > 0 ) {

				// Set the node to a node instance being dragged.
				node = target.closest( '[data-node]' );

				// Mark this node as initialized for dragging.
				node.addClass( 'ba-cheetah-node-drag-init' );
			}
			else if ( target.hasClass( 'ba-cheetah-block' ) ) {

				// Set the node to the first visible row instance.
				$( '.ba-cheetah-row' ).each( function() {
					if ( node === null && $( this ).offset().top - scrollTop > 0 ) {
						node = $( this );
					}
				} );
			}

			// Get the initial scroll position of the node.
			if ( node !== null ) {
				initialPos = node.offset().top - scrollTop;
			}

			// Setup the UI for dragging.
			BACheetah._highlightRowsAndColsForDrag( target );
			BACheetah._adjustColHeightsForDrag();
			BACheetah._disableGlobalRows();
			BACheetah._disableGlobalCols();
			BACheetah._destroyOverlayEvents();
			BACheetah._initSortables();
			$( 'body' ).addClass( 'ba-cheetah-dragging' );
			$( '.ba-cheetah-empty-message' ).hide();
			$( '.ba-cheetah-sortable-disabled' ).removeClass( 'ba-cheetah-sortable-disabled' );

			// Remove all action overlays if this isn't a touch for a proxy item.
			if ( 'touchstart' !== e.type && ! $( e.target ).hasClass( 'ba-cheetah-sortable-proxy-item ' ) ) {
				BACheetah._removeAllOverlays();
			}

			// Scroll to the node that is dragging.
			if ( initialPos > 0 ) {
				scrollTo( 0, node.offset().top - initialPos );
			}

			BACheetah.triggerHook('didInitDrag');
		},

		/**
		 * Callback that fires when dragging starts.
		 *

		 * @access private
		 * @method _blockDragStart
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_blockDragStart: function(e, ui)
		{
			// Let the builder know dragging has started.
			BACheetah._dragging = true;

			// Removed the drag init class as we're done with that.
			$( '.ba-cheetah-node-drag-init' ).removeClass( 'ba-cheetah-node-drag-init' );

			BACheetah.triggerHook('didStartDrag');
		},

		/**
		 * Callback that fires when an element that is being
		 * dragged is sorted.
		 *

		 * @access private
		 * @method _blockDragSort
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_blockDragSort: function(e, ui)
		{
			var parent = ui.placeholder.parent(),
				title  = BACheetahStrings.insert;

			// Prevent sorting?
			if ( BACheetah._blockPreventSort( ui.item, parent ) ) {
				return;
			}

			// Find the placeholder title.
			if(parent.hasClass('ba-cheetah-col-content')) {
				if(ui.item.hasClass('ba-cheetah-block-row')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else if(ui.item.hasClass('ba-cheetah-col-sortable-proxy-item')) {
					title = BACheetahStrings.column;
				}
				else if(ui.item.hasClass('ba-cheetah-block-module')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else if(ui.item.hasClass('ba-cheetah-block-saved-module') || ui.item.hasClass('ba-cheetah-block-module-template')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else {
					title = ui.item.attr('data-name');
				}
			}
			else if(parent.hasClass('ba-cheetah-col-drop-target')) {
				title = '';
			}
			else if (parent.hasClass('ba-cheetah-col-group-drop-target')) {
				title = '';
			}
			else if(parent.hasClass('ba-cheetah-row-drop-target')) {
				if(ui.item.hasClass('ba-cheetah-block-row')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else if(ui.item.hasClass('ba-cheetah-block-saved-row')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else if(ui.item.hasClass('ba-cheetah-block-saved-column')) {
					title = ui.item.find('.ba-cheetah-block-title').text();
				}
				else if(ui.item.hasClass('ba-cheetah-row-sortable-proxy-item')) {
					title = BACheetahStrings.row;
				}
				else {
					title = BACheetahStrings.newRow;
				}
			}

			// Set the placeholder title.
			ui.placeholder.html(title);

			// Add the global class?
			if ( ui.item.hasClass( 'ba-cheetah-node-global' ) ||
				 ui.item.hasClass( 'ba-cheetah-block-global' ) ||
				 $( '.ba-cheetah-node-dragging' ).hasClass( 'ba-cheetah-node-global' )
			) {
				ui.placeholder.addClass( 'ba-cheetah-drop-zone-global' );
			}
			else {
				ui.placeholder.removeClass( 'ba-cheetah-drop-zone-global' );
			}
		},

		/**
		 * Callback that fires when an element that is being
		 * dragged position changes.
		 *
		 * What we're doing here keeps it from appearing jumpy when draging
		 * between columns. Without this you'd see the placeholder jump into
		 * a column position briefly when you didn't intend for it to.
		 *

		 * @access private
		 * @method _blockDragChange
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_blockDragChange: function( e, ui )
		{
			ui.placeholder.css( 'opacity', '0' );
			ui.placeholder.animate( { 'opacity': '1' }, 100 );
		},

		/**
		 * Prevents sorting of items that shouldn't be sorted into
		 * specific areas.
		 *

		 * @access private
		 * @method _blockPreventSort
		 * @param {Object} item The item being sorted.
		 * @param {Object} parent The new parent.
		 */
		_blockPreventSort: function( item, parent )
		{
			var prevent     = false,
				isRowBlock  = item.hasClass( 'ba-cheetah-block-row' ),
				isCol       = item.hasClass( 'ba-cheetah-col-sortable-proxy-item' ),
				isParentCol = parent.hasClass( 'ba-cheetah-col-content' ),
				isColTarget = parent.hasClass( 'ba-cheetah-col-drop-target' ),
				group       = parent.parents( '.ba-cheetah-col-group:not(.ba-cheetah-col-group-nested)' ),
				nestedGroup = parent.parents( '.ba-cheetah-col-group-nested' );
				isInsideRow	= parent.hasClass('ba-cheetah-row-content') && parent.closest('.ba-cheetah-row-type-row-inside')

			// Prevent columns in nested columns.
			if ( ( isRowBlock || isCol ) && isParentCol && nestedGroup.length > 0 ) {
				prevent = true;
			}

			// Prevent 1 column from being nested in an empty column.
			if ( isParentCol && ! parent.find( '.ba-cheetah-module, .ba-cheetah-col' ).length ) {

				if ( isRowBlock && '1-col' == item.data( 'cols' ) ) {
					prevent = true;
				}
				else if ( isCol ) {
					prevent = true;
				}
			}

			// Prevent 5 or 6 columns from being nested.
			if ( isRowBlock && isParentCol && $.inArray( item.data( 'cols' ), [ '5-cols', '6-cols' ] ) > -1 ) {
				prevent = true;
			}

			// Prevent columns with nested columns from being dropped in nested columns.
			if ( isCol && $( '.ba-cheetah-node-dragging' ).find( '.ba-cheetah-col-group-nested' ).length > 0 ) {

				if ( isParentCol || ( isColTarget && nestedGroup.length > 0 ) ) {
					prevent = true;
				}
			}

			// Prevent more than 12 columns.
			if ( isColTarget && group.length > 0 && 0 === nestedGroup.length && group.find( '> .ba-cheetah-col:visible' ).length > 11 ) {
				prevent = true;
			}

			// Prevent more than 4 nested columns.
			if ( isColTarget && nestedGroup.length > 0 && nestedGroup.find( '.ba-cheetah-col:visible' ).length > 3 ) {
				prevent = true;
			}

			// Prevent new row inside row inside /o\
			if (isInsideRow) {
				prevent = true;
			}

			// Add the disabled class if we are preventing a sort.
			if ( prevent ) {
				parent.addClass( 'ba-cheetah-sortable-disabled' );
			}

			return prevent;
		},

		/**
		 * Cleans up when a drag operation has stopped.
		 *

		 * @access private
		 * @method _blockDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_blockDragStop: function( e, ui )
		{
			var scrollTop  = $( window ).scrollTop(),
				parent     = ui.item.parent(),
				initialPos = null;

			// Get the node to scroll to once removing the node highlights affects the body height.
			if ( parent.hasClass( 'ba-cheetah-drop-target' ) && parent.closest( '[data-node]' ).length ) {
				parent = parent.closest( '[data-node]' );
				initialPos = parent.offset().top - scrollTop;
			}
			else {
				initialPos = parent.offset().top - scrollTop;
			}

			// Show the panel if a block was dropped back in.
			if ( parent.hasClass( 'ba-cheetah-blocks-section-content' ) ) {
				BACheetah._showPanel();
			}

			// Finish dragging.
			BACheetah._dragEnabled = false;
			BACheetah._dragging = false;
			BACheetah._bindOverlayEvents();
			BACheetah._removeEmptyRowAndColHighlights();
			BACheetah._highlightEmptyCols();
			BACheetah._enableGlobalRows();
			BACheetah._enableGlobalCols();
			BACheetah._setupEmptyLayout();
			$( 'body' ).removeClass( 'ba-cheetah-dragging' );

			// Scroll the page back to where it was.
			scrollTo( 0, parent.offset().top - initialPos );

			BACheetah.triggerHook('didStopDrag');
		},

		/**
		 * Cleans up when a drag operation has canceled.
		 *

		 * @access private
		 * @method _blockDragCancel
		 */
		_blockDragCancel: function()
		{
			if ( BACheetah._dragEnabled && ! BACheetah._dragging ) {
				BACheetah._dragEnabled = false;
				BACheetah._dragging = false;
				BACheetah._bindOverlayEvents();
				BACheetah._removeEmptyRowAndColHighlights();
				BACheetah._highlightEmptyCols();
				BACheetah._enableGlobalRows();
				BACheetah._setupEmptyLayout();
				$( 'body' ).removeClass( 'ba-cheetah-dragging' );
				$( '.ba-cheetah-node-drag-init' ).removeClass( 'ba-cheetah-node-drag-init' );
				$( '.ba-cheetah-node-dragging' ).removeClass( 'ba-cheetah-node-dragging' );
				scrollTo( 0, BACheetah._dragInitialScrollTop );
			}
		},

		/**
		 * Reorders a node within its parent.
		 *

		 * @access private
		 * @method _reorderNode
		 * @param {String} nodeId The node ID of the node.
		 * @param {Number} position The new position.
		 */
		_reorderNode: function( nodeId, position )
		{
			BACheetah.ajax( {
				action: 'reorder_node',
				node_id: nodeId,
				position: position
			}, function( response ) {
				var data = JSON.parse( response );
				var hook = 'didMove' + data.nodeType.charAt(0).toUpperCase() + data.nodeType.slice(1);
				BACheetah.triggerHook( 'didMoveNode', data );
				BACheetah.triggerHook( hook, data );
			} );
		},

		/**
		 * Moves a node to a new parent.
		 *

		 * @access private
		 * @method _moveNode
		 * @param {String} newParent The node ID of the new parent.
		 * @param {String} nodeId The node ID of the node.
		 * @param {Number} position The new position.
		 */
		_moveNode: function( newParent, nodeId, position )
		{
			BACheetah.ajax( {
				action: 'move_node',
				new_parent: newParent,
				node_id: nodeId,
				position: position
			}, function( response ) {
				var data = JSON.parse( response );
				var hook = 'didMove' + data.nodeType.charAt(0).toUpperCase() + data.nodeType.slice(1);
				BACheetah.triggerHook( 'didMoveNode', data );
				BACheetah.triggerHook( hook, data );
			} );
		},

		/**
		 * Removes all node overlays and hides any tooltip helpies.
		 *

		 * @access private
		 * @method _removeAllOverlays
		 */
		_removeAllOverlays: function()
		{
			BACheetah._removeRowOverlays();
			BACheetah._removeColOverlays();
			BACheetah._removeColHighlightGuides();
			BACheetah._removeModuleOverlays();
			BACheetah._hideTipTips();
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Appends a node action overlay to the layout.
		 *

		 * @access private
		 * @method _appendOverlay
		 * @param {Object} node A jQuery reference to the node this overlay is associated with.
		 * @param {Object} template A rendered wp.template.
		 * @return {Object} The overlay element.
		 */
		_appendOverlay: function( node, template )
		{
			var overlayPos 	= 0,
				overlay 	= null,
				isRow		= node.hasClass( 'ba-cheetah-row' ),
				content		= isRow ? node.find( '> .ba-cheetah-row-content-wrap' ) : node.find( '> .ba-cheetah-node-content' ),
				margins 	= {
					'top' 		: parseInt( content.css( 'margin-top' ), 10 ),
					'bottom' 	: parseInt( content.css( 'margin-bottom' ), 10 )
				};

			// Append the template.
			node.append( template );

			// Add the active class to the node.
			node.addClass( 'ba-cheetah-block-overlay-active' );

			// Init TipTips
			BACheetah._initTipTips();

			// Get a reference to the overlay.
			overlay = node.find( '> .ba-cheetah-block-overlay' );

			// Adjust the overlay positions to account for negative margins.
			if ( margins.top < 0 ) {
				overlayPos = parseInt( overlay.css( 'top' ), 10 );
				overlayPos = isNaN( overlayPos ) ? 0 : overlayPos;
				overlay.css( 'top', ( margins.top + overlayPos ) + 'px' );
			}
			if ( margins.bottom < 0 ) {
				overlayPos = parseInt( overlay.css( 'bottom' ), 10 );
				overlayPos = isNaN( overlayPos ) ? 0 : overlayPos;
				overlay.css( 'bottom', ( margins.bottom + overlayPos ) + 'px' );
			}

			return overlay;
		},

		/**
		 * Builds the overflow menu for an overlay if necessary.
		 *

		 * @access private
		 * @method _buildOverlayOverflowMenu
		 * @param {Object} overlay The overlay object.
		 */
		_buildOverlayOverflowMenu: function( overlay )
		{
			var header        = overlay.find( '.ba-cheetah-block-overlay-header' ),
				actions       = overlay.find( '.ba-cheetah-block-overlay-actions' ),
				hasRules	  = overlay.find( '.ba-cheetah-block-has-rules' ),
				original      = actions.data( 'original' ),
				actionsWidth  = 0,
				items         = null,
				itemsWidth    = 0,
				item          = null,
				i             = 0,
				visibleItems  = [],
				overflowItems = [],
				menuData      = [],
				template	  = wp.template( 'ba-cheetah-overlay-overflow-menu' );


			// Use the original copy if we have one.
			if ( undefined != original ) {
				actions.after( original );
				actions.remove();
				actions = original;
			}

			// Save a copy of the original actions.
			actions.data( 'original', actions.clone() );

			// Get the actions width and items. Subtract any padding plus 2px (8px)
			actionsWidth  = Math.floor(actions[0].getBoundingClientRect().width) - 8;
			items         = actions.find( ' > i, > span.ba-cheetah-has-submenu' );

			// Add the width of the visibility rules indicator if there is one.
			if ( hasRules.length && actionsWidth + hasRules.outerWidth() > header.outerWidth() ) {
				itemsWidth += hasRules.outerWidth();
			}

			// Find visible and overflow items.
			for( ; i < items.length; i++ ) {

				item        = items.eq( i );
				itemsWidth += Math.floor(item[0].getBoundingClientRect().width);

				if ( itemsWidth > actionsWidth ) {
					overflowItems.push( item );
					item.remove();
				}
				else {
					visibleItems.push( item );
				}
			}

			// Build the menu if we have overflow items.
			if ( overflowItems.length > 0 ) {

				if( visibleItems.length > 0 ) {
					overflowItems.unshift( visibleItems.pop().remove() );
				}

				for( i = 0; i < overflowItems.length; i++ ) {

					if ( overflowItems[ i ].is( '.ba-cheetah-has-submenu' ) ) {
						menuData.push( {
							type    : 'submenu',
							label   : overflowItems[ i ].find( '.fa, .fas, .far' ).data( 'title' ),
							submenu : overflowItems[ i ].find( '.ba-cheetah-submenu' )[0].outerHTML
						} );
					}
					else {
						menuData.push( {
							type      : 'action',
							label     : overflowItems[ i ].data( 'title' ),
							className : overflowItems[ i ].removeClass( function( i, c ) {
											return c.replace( /ba-cheetah-block-([^\s]+)/, '' );
										} ).attr( 'class' )
						} );
					}
				}

				actions.append( template( menuData ) );
				BACheetah._initTipTips();
			}
		},

		/* Rows
		----------------------------------------------------------*/

		/**
		 * Removes all row overlays from the page.
		 *

		 * @access private
		 * @method _removeRowOverlays
		 */
		_removeRowOverlays: function()
		{
			$('.ba-cheetah-row').removeClass('ba-cheetah-block-overlay-active');
			$('.ba-cheetah-row-overlay').remove();
			$('.ba-cheetah-module').removeClass('ba-cheetah-module-adjust-height');
			$('body').removeClass( 'ba-cheetah-row-resizing' );
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Removes all row overlays from the page.
		 *

		 * @access private
		 * @method _removeRowOverlays
		 */
		_disableGlobalRows: function()
		{
			if ( 'row' == BACheetahConfig.userTemplateType ) {
				return;
			}

			$('.ba-cheetah-row.ba-cheetah-node-global').addClass( 'ba-cheetah-node-disabled' );
		},

		/**
		 * Removes all global column overlays from the page.
		 *

		 * @access private
		 * @method _disableGlobalCols
		 */
		_disableGlobalCols: function()
		{
			if ( 'column' == BACheetahConfig.userTemplateType ) {
				return;
			}

			$('.ba-cheetah-row:not(.ba-cheetah-node-global) .ba-cheetah-col.ba-cheetah-node-global').addClass( 'ba-cheetah-node-disabled' );
		},

		/**
		 * Removes all row overlays from the page.
		 *

		 * @access private
		 * @method _removeRowOverlays
		 */
		_enableGlobalRows: function()
		{
			if ( 'row' == BACheetahConfig.userTemplateType ) {
				return;
			}

			$( '.ba-cheetah-node-disabled' ).removeClass( 'ba-cheetah-node-disabled' );
		},

		/**
		 * Re-enable global column from the page.
		 *

		 * @access private
		 * @method _enableGlobalCols
		 */
		_enableGlobalCols: function()
		{
			if ( 'column' == BACheetahConfig.userTemplateType ) {
				return;
			}

			$( '.ba-cheetah-node-disabled' ).removeClass( 'ba-cheetah-node-disabled' );
		},

		/**
		 * Shows an overlay with actions when the mouse enters a row.
		 *

		 * @access private
		 * @method _rowMouseenter
		 */
		_rowMouseenter: function()
		{
			var row        	= $( this ),
                rowTop     	= row.offset().top,
                childTop   	= null,
                overlay    	= null,
                template   	= wp.template( 'ba-cheetah-row-overlay' ),
				mode 		= BACheetahResponsiveEditing._mode;


			if ( row.closest( '.ba-cheetah-node-loading' ).length ) {
				return;
			}
            else if ( ! row.hasClass( 'ba-cheetah-block-overlay-active' ) ) {

				// Remove existing overlays.
				if ( ! row.hasClass('ba-cheetah-row-type-row-inside')) {
					BACheetah._removeRowOverlays();
				}

                // Append the overlay.
                overlay = BACheetah._appendOverlay( row, template( {
                    node : row.attr('data-node'),
	                global : row.hasClass( 'ba-cheetah-node-global' ),
					hasRules : row.hasClass( 'ba-cheetah-node-has-rules' ),
					rulesTextRow : row.attr('data-rules-text'),
					rulesTypeRow : row.attr('data-rules-type'),
					inside : row.hasClass('ba-cheetah-row-type-row-inside'),
                } ) );

                // Adjust the overlay position if covered by negative margin content.
                row.find( '.ba-cheetah-node-content:visible' ).each( function(){
                    var top = $( this ).offset().top;
                    childTop = ( null === childTop || childTop > top ) ? top : childTop;
                } );

                if ( null !== childTop && childTop < rowTop ) {
	                overlay.css( 'top', ( childTop - rowTop - 30 ) + 'px' );
                }

                // Put action headers on the bottom if they're hidden.
                if ( ( 'default' === mode && overlay.offset().top < 43 ) || ( 'default' !== mode && 0 === row.index() ) ) {
                    overlay.addClass( 'ba-cheetah-row-overlay-header-bottom' );
                }

                // Adjust the height of modules if needed.
                row.find( '.ba-cheetah-module' ).each( function(){
                    var module = $( this );
                    if ( module.outerHeight( true ) < 20 ) {
                        module.addClass( 'ba-cheetah-module-adjust-height' );
                    }
                } );

                // Build the overlay overflow menu if needed.
                BACheetah._buildOverlayOverflowMenu( overlay );
            }
		},

		/**
		 * Removes overlays when the mouse leaves a row.
		 *

		 * @access private
		 * @method _rowMouseleave
		 * @param {Object} e The event object.
		 */
		_rowMouseleave: function(e)
		{
			var target			= $( e.target ),
				toElement       = $(e.toElement) || $(e.relatedTarget),
				isOverlay       = toElement.hasClass('ba-cheetah-row-overlay'),
				isOverlayChild  = toElement.closest('.ba-cheetah-row-overlay').length > 0,
				isTipTip        = toElement.is('#tiptip_holder'),
				isTipTipChild   = toElement.closest('#tiptip_holder').length > 0;

			if ( target.closest( '.ba-cheetah-block-col-resize' ).length ) {
				return;
			}
			if(isOverlay || isOverlayChild || isTipTip || isTipTipChild) {
				return;
			}

			BACheetah._removeRowOverlays();
		},

		/**
		 * Returns a helper element for row drag operations.
		 *

		 * @access private
		 * @method _rowDragHelper
		 * @return {Object} The helper element.
		 */
		_rowDragHelper: function()
		{
			return $('<div class="ba-cheetah-block-drag-helper">' + BACheetahStrings.row + '</div>');
		},

		/**
		 * Initializes dragging for row. Rows themselves aren't sortables
		 * as nesting that many sortables breaks down quickly and draggable by
		 * itself is slow. Instead, we are programmatically triggering the drag
		 * of our helper div that isn't a nested sortable but connected to the
		 * sortables in the main layout.
		 *

		 * @access private
		 * @method _rowDragInit
		 * @param {Object} e The event object.
		 */
		_rowDragInit: function( e )
		{
			var handle = $( e.target ),
				helper = $( '.ba-cheetah-row-sortable-proxy-item' ),
				row    = handle.closest( '.ba-cheetah-row' );

			row.addClass( 'ba-cheetah-node-dragging' );

			BACheetah._blockDragInit( e );

			e.target = helper[ 0 ];

			helper.trigger( e );
		},

		/**
		 * @method _rowDragInitTouch
		 * @param {Object} e The event object.
		 */
		_rowDragInitTouch: function( startEvent )
		{
			var handle = $( startEvent.target ),
				helper = $( '.ba-cheetah-row-sortable-proxy-item' ),
				row    = handle.closest( '.ba-cheetah-row' ),
				moved  = false;

			handle.on( 'touchmove', function( moveEvent ) {
				if ( ! moved ) {
					startEvent.currentTarget = row[0];
					BACheetah._rowDragInit( startEvent );
					moved = true;
				}
				helper.trigger( moveEvent );
			} );

			handle.on( 'touchend', function( endEvent ) {
				helper.trigger( endEvent );
			} );
		},

		/**
		 * Callback that fires when dragging starts for a row.
		 *

		 * @access private
		 * @method _rowDragStart
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_rowDragStart: function( e, ui )
		{
			var rows = $( BACheetah._contentClass + ' .ba-cheetah-row' ),
				row  = $( '.ba-cheetah-node-dragging' );

			if ( 1 === rows.length ) {
				$( BACheetah._contentClass ).addClass( 'ba-cheetah-empty' );
			}

			row.hide();

			BACheetah._blockDragStart( e, ui );
		},

		/**
		 * Callback for when a row drag operation completes.
		 *

		 * @access private
		 * @method _rowDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_rowDragStop: function( e, ui )
		{
			var item     = ui.item,
				parent   = item.parent(),
				row      = null,
				group    = null,
				position = 0;

			BACheetah._blockDragStop( e, ui );

			// A row was dropped back into the row list.
			if ( parent.hasClass( 'ba-cheetah-rows' ) ) {
				item.remove();
				return;
			}
			// A row was dropped back into the sortable proxy.
			else if ( parent.hasClass( 'ba-cheetah-row-sortable-proxy' ) ) {
				$( '.ba-cheetah-node-dragging' ).removeClass( 'ba-cheetah-node-dragging' ).show();
				return;
			}
			// Add a new row.
			else if ( item.hasClass( 'ba-cheetah-block' ) ) {

				// Cancel the drop if the sortable is disabled?
				if ( parent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
					item.remove();
					BACheetah._showPanel();
					return;
				}
				// A new row was dropped into column.
				else if ( parent.hasClass( 'ba-cheetah-col-content' ) ) {
					BACheetah._addColGroup(
						item.closest( '.ba-cheetah-col' ).attr( 'data-node' ),
						item.attr( 'data-cols' ),
						parent.find( '> .ba-cheetah-module, .ba-cheetah-col-group, .ba-cheetah-block' ).index( item )
					);
				}
				// A new row was dropped next to a column.
				else if ( parent.hasClass( 'ba-cheetah-col-drop-target' ) ) {
					BACheetah._addCols(
						parent.closest( '.ba-cheetah-col' ),
						parent.hasClass( 'ba-cheetah-col-drop-target-last' ) ? 'after' : 'before',
						item.attr( 'data-cols' ),
						parent.closest( '.ba-cheetah-col-group-nested' ).length > 0
					);
				}
				// A new row was dropped into a column group position.
				else if ( parent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {

					group    = item.closest( '.ba-cheetah-col-group' );
					position = item.closest( '.ba-cheetah-row' ).find( '.ba-cheetah-row-content > .ba-cheetah-col-group' ).index( group );

					BACheetah._addColGroup(
						item.closest( '.ba-cheetah-row' ).attr( 'data-node' ),
						item.attr( 'data-cols' ),
						parent.hasClass( 'ba-cheetah-drop-target-last' ) ? position + 1 : position
					);
				}
				// A row was dropped into a row position.
				else {

					row = item.closest( '.ba-cheetah-row' );
					position = ! row.length ? 0 : $( BACheetah._contentClass + ' > .ba-cheetah-row' ).index( row );

					BACheetah._addRow(
						item.attr('data-cols'),
						parent.hasClass( 'ba-cheetah-drop-target-last' ) ? position + 1 : position
					);
				}

				// Remove the helper.
				item.remove();

				// Show the builder panel.
				BACheetah._showPanel();

				// Show the module list.
				$( '.ba-cheetah-modules' ).siblings( '.ba-cheetah-blocks-section-title' ).eq( 0 ).trigger( 'click' );
			}
			// Reorder a row.
			else {

				row = $( '.ba-cheetah-node-dragging' ).removeClass( 'ba-cheetah-node-dragging' ).show();

				// Make sure a single row wasn't dropped back into the main layout.
				if ( ! parent.parent().hasClass( 'ba-cheetah-content' ) ) {

					// Move the row in the UI.
					if ( parent.hasClass( 'ba-cheetah-drop-target-last' ) ) {
						parent.parent().after( row );
					}
					else {
						parent.parent().before( row );
					}

					// Reorder the row.
					BACheetah._reorderNode(
						row.attr('data-node'),
						row.index()
					);
				}

				// Revert the proxy to its parent.
				$( '.ba-cheetah-row-sortable-proxy' ).append( ui.item );
			}
		},

		/**
		 * Adds a new row to the layout.
		 *

		 * @access private
		 * @method _addRow
		 * @param {String} cols The type of column layout to use.
		 * @param {Number} position The position of the new row.
		 * @param {String} module Optional. The node ID of an existing module to move to this row.
		 */
		_addRow: function(cols, position, module)
		{
			BACheetah._showNodeLoadingPlaceholder( $( BACheetah._contentClass ), position );

			BACheetah._newRowPosition = position;

			BACheetah.ajax({
				action: 'render_new_row',
				cols: cols,
				position: position,
				module: module,
			}, BACheetah._addRowComplete);
		},

		/**
		 * Adds the HTML for a new row to the layout when the AJAX
		 * add operation is complete.
		 *

		 * @access private
		 * @method _addRowComplete
		 * @param {String} response The JSON response with the HTML for the new row.
		 */
		_addRowComplete: function(response)
		{
			var data 	= 'object' === typeof response ? response : BACheetah._jsonParse(response),
				content = $(BACheetah._contentClass),
				rowId   = $(data.html).data('node');

			// Add new row info to the data.
			data.nodeParent 	= content;
			data.nodePosition 	= BACheetah._newRowPosition;

			// Render the layout.
			BACheetah._renderLayout( data, function(){
				BACheetah._removeNodeLoadingPlaceholder( $( '.ba-cheetah-node-' + rowId ) );
				BACheetah.triggerHook( 'didAddRow', rowId );
			} );
		},

		/**
		 * Callback for when the delete row button is clicked.
		 *

		 * @access private
		 * @method _deleteRowClicked
		 * @param {Object} e The event object.
		 */
		_deleteRowClicked: function( e )
		{
			var row    = $(this).closest('.ba-cheetah-row'),
				result = null;

			if(!row.find('.ba-cheetah-module').length) {
				BACheetah._deleteRow(row);
			}
			else {
				result = confirm(BACheetahStrings.deleteRowMessage);

				if(result) {
					BACheetah._deleteRow(row);
				}
			}

			BACheetah._removeAllOverlays();
			e.stopPropagation();
		},

		/**
		 * Deletes a row.
		 *

		 * @access private
		 * @method _deleteRow
		 * @param {Object} row A jQuery reference of the row to delete.
		 */
		_deleteRow: function(row)
		{
			var nodeId = row.attr('data-node');

			BACheetah.ajax({
				action: 'delete_node',
				node_id: nodeId
			});

			row.empty();
			row.remove();
			BACheetah._setupEmptyLayout();
			BACheetah._removeRowOverlays();
			BACheetah.triggerHook( 'didDeleteRow', nodeId );
		},

		/**
		 * Duplicates a row.
		 *

		 * @access private
		 * @method _rowCopyClicked
		 * @param {Object} e The event object.
		 */
		_rowCopyClicked: function(e)
		{
			var win		 	= $( window ),
				row      	= $( this ).closest( '.ba-cheetah-row' ),
				nodeId   	= row.attr( 'data-node' ),
				clone    	= row.clone(),
				form	 	= $( '.ba-cheetah-settings[data-node]' ),
				formNodeId 	= form.attr( 'data-node' ),
				formNode	= ( formNodeId === nodeId ) ? row : row.find( '[data-node="' + formNodeId + '"]' ),
				settings 	= null;

			if ( form.length && formNode.length ) {
				settings = BACheetah._getSettings( form );
				BACheetahSettingsConfig.nodes[ formNodeId ] = settings;
			}

			clone.addClass( 'ba-cheetah-node-' + nodeId + '-clone ba-cheetah-node-clone' );
			clone.find( '.ba-cheetah-block-overlay' ).remove();
			row.after( clone );
			BACheetah._showNodeLoading( nodeId + '-clone' );

			if ( win.scrollTop() + win.height() < clone.offset().top ) {
				$( 'html, body' ).animate( {
					scrollTop: clone.offset().top + clone.height() - win.height()
				}, 500 );
			}

			BACheetah.ajax( {
				action: 'copy_row',
				node_id: nodeId,
				settings: settings,
				settings_id: formNodeId
			}, function( response ) {
				var data = BACheetah._jsonParse( response );
				data.nodeParent = $( BACheetah._contentClass );
				data.nodePosition = $( BACheetah._contentClass + ' > .ba-cheetah-row' ).index( clone );
				data.duplicatedRow = nodeId;
				data.onAddNewHTML = function() { clone.remove() };
				BACheetah._rowCopyComplete( data );
			} );

			e.stopPropagation();
		},

		/**
		 * Callback for when a row has been duplicated.
		 *

		 * @access private
		 * @method _rowCopyComplete
		 * @param {Object} data
		 */
		_rowCopyComplete: function( data )
		{
			BACheetah._renderLayout( data, function() {
				BACheetah.triggerHook( 'didDuplicateRow', {
					newNodeId : data.nodeId,
					oldNodeId : data.duplicatedRow
				} );
			} );
		},

		/**
		 * Shows the settings lightbox and loads the row settings
		 * when the row settings button is clicked.
		 *

		 * @access private
		 * @method _rowSettingsClicked
		 */
		_rowSettingsClicked: function( e )
		{
			var button = $( this ),
				nodeId = button.closest( '.ba-cheetah-row' ).attr( 'data-node' ),
				global = button.closest( '.ba-cheetah-block-overlay-global' ).length > 0,
				win    = null;

			if ( global && 'row' != BACheetahConfig.userTemplateType ) {
				if ( BACheetahConfig.userCanEditGlobalTemplates ) {
					win = window.open( $( '.ba-cheetah-row[data-node="' + nodeId + '"]' ).attr( 'data-template-url' ) );
					win.BACheetahGlobalNodeId = nodeId;
				}
			}
			else if ( button.hasClass( 'ba-cheetah-block-settings' ) ) {

				BACheetahSettingsForms.render( {
					id        : 'row',
					nodeId    : nodeId,
					className : 'ba-cheetah-row-settings',
					attrs     : 'data-node="' + nodeId + '"',
					buttons   : ! global && ! BACheetahConfig.simpleUi ? ['save-as'] : [],
					badges    : global ? [ BACheetahStrings.global ] : [],
					settings  : BACheetahSettingsConfig.nodes[ nodeId ],
					preview	  : {
						type: 'row'
					}
				}, function() {
					$( '#ba-cheetah-field-width select' ).on( 'change', BACheetah._rowWidthChanged );
					$( '#ba-cheetah-field-content_width select' ).on( 'change', BACheetah._rowWidthChanged );
				} );
			}

			e.stopPropagation();
		},

		/**
		 * Shows or hides the row max-width setting when the
		 * row or row content width is changed.
		 *

		 * @access private
		 * @method _rowWidthChanged
		 */
		_rowWidthChanged: function()
		{
			var rowWidth     = $( '#ba-cheetah-field-width select' ).val(),
				contentWidth = $( '#ba-cheetah-field-content_width select' ).val(),
				maxWidth     = $( '#ba-cheetah-field-max_content_width' );

			if ( 'fixed' == rowWidth || ( 'full' == rowWidth && 'fixed' == contentWidth ) ) {
				maxWidth.show();
			} else {
				maxWidth.hide();
			}
		},

		/**
		 * Resets the max-width of a row.
		 *

		 * @access private
		 * @method _resetRowWidthClicked
		 */
		_resetRowWidthClicked: function( e )
		{
			var button   = $( this ),
				row      = button.closest( '.ba-cheetah-row' ),
				nodeId   = row.attr( 'data-node' ),
				content  = row.find( '.ba-cheetah-row-content' ),
				width    = BACheetahConfig.global.row_width + 'px',
				settings = $( '.ba-cheetah-row-settings' );

			if ( row.hasClass( 'ba-cheetah-row-fixed-width' ) ) {
				row.css( 'max-width', width );
			}

			content.css( 'max-width', width );

			if ( settings.length ) {
				settings.find( '[name=max_content_width]' ).val( '' );
			}

			BACheetah.ajax({
				action	: 'resize_row_content',
				node	: nodeId,
				width   : ''
			});

			BACheetah._closeAllSubmenus();
			BACheetah.triggerHook( 'didResetRowWidth', nodeId );

			e.stopPropagation();
		},

		/* Columns
		----------------------------------------------------------*/

		/**
		 * Adds a dashed border to empty columns.
		 *

		 * @access private
		 * @method _highlightEmptyCols
		 */
		_highlightEmptyCols: function()
		{
			var notGlobal = 'row' == BACheetahConfig.userTemplateType || 'column' == BACheetahConfig.userTemplateType ? '' : ':not(.ba-cheetah-node-global)',
				rows 	  = $(BACheetah._contentClass + ' .ba-cheetah-row' + notGlobal),
				cols 	  = $(BACheetah._contentClass + ' .ba-cheetah-col' + notGlobal);

			cols.removeClass('ba-cheetah-col-highlight').find('.ba-cheetah-col-content').css( 'height', '' );

			cols.each(function(){

				var col = $(this);

				if(col.find('.ba-cheetah-module, .ba-cheetah-col').length === 0) {
					col.addClass('ba-cheetah-col-highlight');
				}
			});
		},

		/**
		 * Sets up dashed borders to show where things can
		 * be dropped in rows and columns.
		 *

		 * @access private
		 * @method _highlightRowsAndColsForDrag
		 * @param {Object} target The event target for the drag.
		 */
		_highlightRowsAndColsForDrag: function( target )
		{
			var notGlobal = 'row' == BACheetahConfig.userTemplateType ? '' : ':not(.ba-cheetah-node-global)';

			// Do not highlight root parent column.
			if ( 'column' == BACheetahConfig.userTemplateType ) {
				notGlobal = ':not(:first)';
			}

			// Highlight rows.
			$( BACheetah._contentClass + ' .ba-cheetah-row' ).addClass( 'ba-cheetah-row-highlight' );

			// Highlight columns.
			if ( ! target || ! target.closest( '.ba-cheetah-row-overlay' ).length ) {
				$( BACheetah._contentClass + ' .ba-cheetah-col' + notGlobal ).addClass( 'ba-cheetah-col-highlight' );
			}
		},

		/**
		 * Remove any column highlights
		 *

		 * @access private
		 * @method _removeEmptyRowAndColHighlights
		 */
		_removeEmptyRowAndColHighlights: function() {
			$( '.ba-cheetah-row-highlight' ).removeClass('ba-cheetah-row-highlight');
			$( '.ba-cheetah-col-highlight' ).removeClass('ba-cheetah-col-highlight');
		},

		/**
		 * Adjust the height of columns with modules in them
		 * to account for the drop zone and keep the layout
		 * from jumping around.
		 *

		 * @access private
		 * @method _adjustColHeightsForDrag
		 */
		_adjustColHeightsForDrag: function()
		{
			var notGlobalRow = 'row' == BACheetahConfig.userTemplateType ? '' : '.ba-cheetah-row:not(.ba-cheetah-node-global) ',
				notGlobalCol = 'column' == BACheetahConfig.userTemplateType ? '' : '.ba-cheetah-col:not(.ba-cheetah-node-global) ',
				content      = $( BACheetah._contentClass ),
				notNested    = content.find( notGlobalRow + '.ba-cheetah-col-group:not(.ba-cheetah-col-group-nested) > ' + notGlobalCol + '> .ba-cheetah-col-content' ),
				nested       = content.find( notGlobalRow + '.ba-cheetah-col-group-nested ' + notGlobalCol + '.ba-cheetah-col-content' ),
				col          = null,
				i            = 0;

			$( '.ba-cheetah-node-drag-init' ).hide();

			for ( ; i < nested.length; i++ ) {
				BACheetah._adjustColHeightForDrag( nested.eq( i ) );
			}

			for ( i = 0; i < notNested.length; i++ ) {
				BACheetah._adjustColHeightForDrag( notNested.eq( i ) );
			}

			$( '.ba-cheetah-node-drag-init' ).show();
		},

		/**
		 * Adjust the height of a single column for dragging.
		 *

		 * @access private
		 * @method _adjustColHeightForDrag
		 */
		_adjustColHeightForDrag: function( col )
		{
			if ( col.find( '.ba-cheetah-module:visible, .ba-cheetah-col:visible' ).length ) {
				col.height( col.height() + 45 );
			}
		},

		/**
		 * Adds a border guide to a column when the column
		 * actions submenu is open for a module.
		 *

		 * @access private
		 * @method _showColHighlightGuide
		 */
		_showColHighlightGuide: function()
		{
			var li         = $( this ),
				link       = li.find( 'a' ),
				col        = li.closest( '.ba-cheetah-col' ),
				parentCol  = col.parents( '.ba-cheetah-col' ),
				guide      = $( '<div class="ba-cheetah-col-highlight-guide"></div>' ),
				guideTop   = null,
				overlayTop = li.closest( '.ba-cheetah-block-overlay' ).offset().top;

			if ( link.hasClass( 'ba-cheetah-block-col-move-parent' ) || link.hasClass( 'ba-cheetah-block-col-edit-parent' ) ) {
				col = parentCol;
			}
			if ( col.hasClass( 'ba-cheetah-col-highlight' ) ) {
				return;
			}

			col.find( '> .ba-cheetah-col-content' ).append( guide );
			col.addClass( 'ba-cheetah-col-has-highlight-guide' );

			guideTop = guide.offset().top;

			if ( guideTop > overlayTop ) {
				guide.css( 'top', ( overlayTop - guideTop + 4 ) + 'px' );
			}
		},

		/**
		 * Removes all column highlight guides.
		 *

		 * @access private
		 * @method _showColHighlightGuide
		 */
		_removeColHighlightGuides: function()
		{
			$( '.ba-cheetah-col-has-highlight-guide' ).removeClass( 'ba-cheetah-col-has-highlight-guide' );
			$( '.ba-cheetah-col-highlight-guide' ).remove();
		},

		/**
		 * Shows an overlay with actions when the mouse enters a column.
		 *

		 * @access private
		 * @method _colMouseenter
		 */
		_colMouseenter: function()
		{
			var col 	 	  	= $( this ),
				group           = col.closest( '.ba-cheetah-col-group' ),
				groupLoading    = group.hasClass( 'ba-cheetah-col-group-has-child-loading' ),
				global		  	= col.hasClass( 'ba-cheetah-node-global' ),
				parentGlobal  	= col.parents( '.ba-cheetah-node-global' ).length > 0,
				numCols		  	= col.closest( '.ba-cheetah-col-group' ).find( '> .ba-cheetah-col' ).length,
				index           = group.find( '> .ba-cheetah-col' ).index( col ),
				first   		= 0 === index,
				last    		= numCols === index + 1,
				hasChildCols    = col.find( '.ba-cheetah-col' ).length > 0,
				hasModules      = col.find('.ba-cheetah-module').length > 0,
				parentCol       = col.parents( '.ba-cheetah-col' ),
				parentGroup     = parentCol.closest( '.ba-cheetah-col-group' ),
				hasParentCol    = parentCol.length > 0,
				isColTemplate   = 'undefined' !== typeof col.data('template-url'),
				isRootCol       = 'column' == BACheetahConfig.userTemplateType && ! hasParentCol;
				numParentCols	= hasParentCol ? parentGroup.find( '> .ba-cheetah-col' ).length : 0,
				parentIndex     = parentGroup.find( '> .ba-cheetah-col' ).index( parentCol ),
				parentFirst     = hasParentCol ? 0 === parentIndex : false,
				parentLast      = hasParentCol ? numParentCols === parentIndex + 1 : false,
				row				= col.closest('.ba-cheetah-row'),
				rowIsFixedWidth = !! row.find('.ba-cheetah-row-fixed-width').addBack('.ba-cheetah-row-fixed-width').length,
				userCanResizeRows = BACheetahConfig.rowResize.userCanResizeRows,
				hasRules		= col.hasClass( 'ba-cheetah-node-has-rules' ),
				template 		= wp.template( 'ba-cheetah-col-overlay' ),
				overlay			= null;

			if ( BACheetahConfig.simpleUi && ! global ) {
				return;
			}
			else if ( global && parentGlobal && hasModules && ! isColTemplate ) {
				return;
			}
			else if ( global && 'column' == BACheetahConfig.userTemplateType && hasModules ) {
				return;
			}
			else if ( ! global && col.find( '.ba-cheetah-module' ).length > 0 ) {
				return;
			}
			else if ( col.find( '.ba-cheetah-node-loading-placeholder' ).length > 0 ) {
				return;
			}
			else if ( ! hasModules && hasChildCols ) {
				return;
			}
			else if ( parentGlobal && hasChildCols && ! isColTemplate ) {
				return;
			}
			else if ( col.closest( '.ba-cheetah-node-loading' ).length ) {
				return;
			}
			else if ( ! col.hasClass( 'ba-cheetah-block-overlay-active' ) ) {

				// Remove existing overlays.
				BACheetah._removeColOverlays();
				BACheetah._removeModuleOverlays();

				// Append the template.
				overlay = BACheetah._appendOverlay( col, template( {
					global	      		: global,
					groupLoading  		: groupLoading,
					numCols	      		: numCols,
					first         		: first,
					last   	      		: last,
					isRootCol     		: isRootCol,
					hasChildCols  		: hasChildCols,
					hasParentCol  		: hasParentCol,
					parentFirst   		: parentFirst,
					parentLast    		: parentLast,
					numParentCols 		: numParentCols,
					rowIsFixedWidth 	: rowIsFixedWidth,
					userCanResizeRows 	: userCanResizeRows,
					hasRules			: hasRules,
				} ) );

				// Build the overlay overflow menu if needed.
				BACheetah._buildOverlayOverflowMenu( overlay );

				// Init column resizing.
				BACheetah._initColDragResizing();
			}

			$( 'body' ).addClass( 'ba-cheetah-block-overlay-muted' );
		},

		/**
		 * Removes overlays when the mouse leaves a column.
		 *

		 * @access private
		 * @method _colMouseleave
		 * @param {Object} e The event object.
		 */
		_colMouseleave: function(e)
		{
			var col             = $(this),
				target			= $( e.target ),
				toElement       = $(e.toElement) || $(e.relatedTarget),
				hasModules      = col.find('.ba-cheetah-module').length > 0,
				global			= col.hasClass( 'ba-cheetah-node-global' ),
				isColTemplate	= 'undefined' !== typeof col.data('template-url'),
				isTipTip        = toElement.is('#tiptip_holder'),
				isTipTipChild   = toElement.closest('#tiptip_holder').length > 0;

			if ( target.closest( '.ba-cheetah-block-col-resize' ).length ) {
				return;
			}
			if( isTipTip || isTipTipChild ) {
				return;
			}
			if( hasModules && ! isColTemplate ) {
				return;
			}

			BACheetah._removeColOverlays();
			BACheetah._removeColHighlightGuides();
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Removes all column overlays from the page.
		 *

		 * @access private
		 * @method _removeColOverlays
		 */
		_removeColOverlays: function()
		{
			var cols = $( '.ba-cheetah-col' );

			cols.removeClass('ba-cheetah-block-overlay-active');
			cols.find('.ba-cheetah-col-overlay').remove();
			$('body').removeClass('ba-cheetah-block-overlay-muted');
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Returns a helper element for column drag operations.
		 *

		 * @access private
		 * @method _colDragHelper
		 * @return {Object} The helper element.
		 */
		_colDragHelper: function()
		{
			return $('<div class="ba-cheetah-block-drag-helper">' + BACheetahStrings.column + '</div>');
		},

		/**
		 * Initializes dragging for columns. Columns themselves aren't sortables
		 * as nesting that many sortables breaks down quickly and draggable by
		 * itself is slow. Instead, we are programmatically triggering the drag
		 * of our helper div that isn't a nested sortable but connected to the
		 * sortables in the main layout.
		 *

		 * @access private
		 * @method _colDragInit
		 * @param {Object} e The event object.
		 */
		_colDragInit: function( e )
		{
			var handle = $( e.target ),
				helper = $( '.ba-cheetah-col-sortable-proxy-item' ),
				col    = handle.closest( '.ba-cheetah-col' );

			if ( handle.hasClass( 'ba-cheetah-block-col-move-parent' ) ) {
				col = col.parents( '.ba-cheetah-col' );
			}

			col.addClass( 'ba-cheetah-node-dragging' );

			BACheetah._blockDragInit( e );
			BACheetah._removeColHighlightGuides();

			e.target = helper[ 0 ];

			helper.trigger( e );
		},

		/**
		 * @method _colDragInitTouch
		 * @param {Object} e The event object.
		 */
		_colDragInitTouch: function( startEvent )
		{
			var handle = $( startEvent.target ),
				helper = $( '.ba-cheetah-col-sortable-proxy-item' ),
				col    = handle.closest( '.ba-cheetah-col' ),
				module = handle.closest( '.ba-cheetah-module' ),
				moved  = false;

			handle.on( 'touchmove', function( moveEvent ) {
				if ( ! moved ) {
					startEvent.currentTarget = col[0];
					BACheetah._colDragInit( startEvent );
					moved = true;
				}
				helper.trigger( moveEvent );
			} );

			handle.on( 'touchend', function( endEvent ) {
				helper.trigger( endEvent );
			} );
		},

		/**
		 * Callback that fires when dragging starts for a column.
		 *

		 * @access private
		 * @method _colDragStart
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_colDragStart: function( e, ui )
		{
			var col = $( '.ba-cheetah-node-dragging' );

			col.hide();

			BACheetah._resetColumnWidths( col.parent() );
			BACheetah._blockDragStart( e, ui );
		},

		/**
		 * Callback that fires when dragging stops for a column.
		 *

		 * @access private
		 * @method _colDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_colDragStop: function( e, ui )
		{
			BACheetah._blockDragStop( e, ui );

			var col        = $( '.ba-cheetah-node-dragging' ).removeClass( 'ba-cheetah-node-dragging' ).show(),
				colId      = col.attr( 'data-node' ),
				newParent  = ui.item.parent(),
				oldGroup   = col.parent(),
				oldGroupId = oldGroup.attr( 'data-node' )
				newGroup   = newParent.closest( '.ba-cheetah-col-group' ),
				newGroupId = newGroup.attr( 'data-node' ),
				newRow     = newParent.closest('.ba-cheetah-row'),
				position   = 0;

			// Cancel if a column was dropped into itself.
			if ( newParent.closest( '[data-node="' + colId + '"]' ).length ) {
				BACheetah._resetColumnWidths( oldGroup );
			}
			// Cancel the drop if the sortable is disabled?
			else if ( newParent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
				BACheetah._resetColumnWidths( oldGroup );
			}
			// A column was dropped back into the sortable proxy.
			else if ( newParent.hasClass( 'ba-cheetah-col-sortable-proxy' ) ) {
				BACheetah._resetColumnWidths( oldGroup );
			}
			// A column was dropped into a column.
			else if ( newParent.hasClass( 'ba-cheetah-col-content' ) ) {

				// Remove the column.
				col.remove();

				// Remove empty old groups (needs to be done here for correct position).
				if ( 0 === oldGroup.find( '.ba-cheetah-col' ).length ) {
					oldGroup.remove();
				}

				// Find the new group position.
				position = newParent.find( '> .ba-cheetah-module, .ba-cheetah-col-group, .ba-cheetah-col-sortable-proxy-item' ).index( ui.item );

				// Add the new group.
				BACheetah._addColGroup( newParent.closest( '.ba-cheetah-col' ).attr('data-node'), colId, position );
			}
			// A column was dropped into a column position.
			else if ( newParent.hasClass( 'ba-cheetah-col-drop-target' ) ) {

				// Move the column in the UI.
				if ( newParent.hasClass( 'ba-cheetah-col-drop-target-last' ) ) {
					newParent.parent().after( col );
				}
				else {
					newParent.parent().before( col );
				}

				// Reset the UI column widths.
				BACheetah._resetColumnWidths( newGroup );

				// Save the column move via AJAX.
				if ( oldGroupId == newGroupId ) {
					BACheetah.ajax( {
						action: 'reorder_col',
						node_id: colId,
						position: col.index()
					}, function() {
						BACheetah.triggerHook( 'didMoveColumn' );
					} );
				}
				else {
					BACheetah.ajax( {
						action: 'move_col',
						node_id: colId,
						new_parent: newGroupId,
						position: col.index(),
						resize: [ oldGroupId, newGroupId ]
					}, function() {
						BACheetah.triggerHook( 'didMoveColumn' );
					} );
				}

				// Trigger a layout resize.
				BACheetah._resizeLayout();
			}
			// A column was dropped into a column group position.
			else if ( newParent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {

				// Remove the column.
				col.remove();

				// Remove empty old groups (needs to be done here for correct position).
				if ( 0 === oldGroup.find( '.ba-cheetah-col' ).length ) {
					oldGroup.remove();
				}

				// Find the new group position.
				position = newRow.find( '.ba-cheetah-row-content > .ba-cheetah-col-group' ).index( newGroup );
				position = newParent.hasClass( 'ba-cheetah-drop-target-last' ) ? position + 1 : position;

				// Add the new group.
				BACheetah._addColGroup( newRow.attr('data-node'), colId, position );
			}
			// A column was dropped into a row position.
			else if ( newParent.hasClass( 'ba-cheetah-row-drop-target' ) ) {

				// Remove the column.
				col.remove();

				// Find the new row position.
				position = newParent.closest( '.ba-cheetah-content' ).find( '.ba-cheetah-row' ).index( newRow );
				position = newParent.hasClass( 'ba-cheetah-drop-target-last' ) ? position + 1 : position;

				// Add the new row.
				BACheetah._addRow( colId, position );
			}

			// Remove empty old groups.
			if ( 0 === oldGroup.find( '.ba-cheetah-col' ).length ) {
				oldGroup.remove();
			}

			// Revert the proxy to its parent.
			$( '.ba-cheetah-col-sortable-proxy' ).append( ui.item );

			// Finish the drag.
			BACheetah._highlightEmptyCols();
			BACheetah._initDropTargets();
			BACheetah._initSortables();
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Shows the settings lightbox and loads the column settings
		 * when the column settings button is clicked.
		 *

		 * @access private
		 * @method _colSettingsClicked
		 * @param {Object} e The event object.
		 */
		_colSettingsClicked: function(e)
		{
			var button  		   = $( this ),
				parentModuleInside = button.parent().closest('.ba-cheetah-module-inside-overlay');
				col      		   = button.closest('.ba-cheetah-col'),
				content  		   = col.find( '> .ba-cheetah-col-content' ),
				hasSubmenu  	   = button.parent().find( 'ul.ba-cheetah-submenu' ).length > 0,
				global   		   = button.closest( '.ba-cheetah-block-overlay-global' ).length > 0,
				isGlobalCol		   = button.closest( '.ba-cheetah-block-overlay-global' ).hasClass( 'ba-cheetah-col-overlay' ),
				isColTemplate 	   = 'column' != BACheetahConfig.userTemplateType && 'undefined' !== typeof col.attr( 'data-template-url' ),
				nodeId   		   = null;

			// Add class z-index-up to show submenu above all elements
			if(parentModuleInside.length > 0) {
				parentModuleInside.addClass('z-index-up');
			}
			
			if ( BACheetah._colResizing ) {
				return;
			}
			if ( global && ! BACheetahConfig.userCanEditGlobalTemplates ) {
				return;
			}

			if ( hasSubmenu && ! button.hasClass( 'ba-cheetah-col-overlay') ) {
				return;
			}

			if ( button.hasClass( 'ba-cheetah-block-col-edit-parent' ) ) {
			    col = col.parents( '.ba-cheetah-col' );
			}

			nodeId = col.attr('data-node');

			if ( global && isGlobalCol && isColTemplate ) {
				if ( BACheetahConfig.userCanEditGlobalTemplates ) {
					win = window.open( $( '.ba-cheetah-col[data-node="' + nodeId + '"]' ).attr( 'data-template-url' ) );
					win.BACheetahGlobalNodeId = nodeId;

				}
			}
			else {

				BACheetahSettingsForms.render( {
					id        : 'col',
					nodeId    : nodeId,
					className : 'ba-cheetah-col-settings',
					attrs     : 'data-node="' + nodeId + '"',
					buttons   : ! global && ! BACheetahConfig.simpleUi ? ['save-as'] : [],
					badges    : global ? [ BACheetahStrings.global ] : [],
					settings  : BACheetahSettingsConfig.nodes[ nodeId ],
					preview   : {
						type: 'col'
					}
				}, function() {
					if ( col.siblings( '.ba-cheetah-col' ).length === 0  ) {
						$( '#ba-cheetah-field-size, #ba-cheetah-field-equal_height, #ba-cheetah-field-content_alignment' ).hide();
					}
				} );
			}

			e.stopPropagation();
		},

		/**
		 * Callback for when the copy column button is clicked.
		 *

		 * @access private
		 * @method _copyColClicked
		 * @param {Object} e The event object.
		 */
		_copyColClicked: function( e )
		{
			var col    		= $( this ).closest( '.ba-cheetah-col' ),
				nodeId 		= col.attr( 'data-node' ),
				clone  		= col.clone(),
				group  		= col.parent(),
				form	 	= $( '.ba-cheetah-settings[data-node]' ),
				formNodeId 	= form.attr( 'data-node' ),
				formNode	= ( formNodeId === nodeId ) ? col : col.find( '[data-node="' + formNodeId + '"]' ),
				settings 	= null;

			if ( form.length && formNode.length ) {
				settings = BACheetah._getSettings( form );
				BACheetahSettingsConfig.nodes[ formNodeId ] = settings;
			}

			clone.addClass( 'ba-cheetah-node-' + nodeId + '-clone ba-cheetah-node-clone' );
			clone.find( '.ba-cheetah-block-overlay' ).remove();
			col.after( clone );

			BACheetah._showNodeLoading( nodeId + '-clone' );
			BACheetah._resetColumnWidths( group );

			BACheetah.ajax( {
				action: 'copy_col',
				node_id: nodeId,
				settings: settings,
				settings_id: formNodeId
			}, function( response ){
				var data = BACheetah._jsonParse( response );
				data.nodeParent = group;
				data.nodePosition = clone.index();
				data.duplicatedColumn = nodeId;
				data.onAddNewHTML = function() { clone.remove() };
				BACheetah._copyColComplete( data );
			} );

			e.stopPropagation();
		},

		/**
		 * Callback for when a column has been duplicated.
		 *

		 * @access private
		 * @method _copyColComplete
		 * @param {Object} data
		 */
		_copyColComplete: function( data )
		{
			BACheetah._renderLayout( data, function(){
				BACheetah._resetColumnWidths( data.nodeParent );
				BACheetah.triggerHook( 'didDuplicateColumn', {
					newNodeId : data.nodeId,
					oldNodeId : data.duplicatedColumn
				} );
			} );
		},

		/**
		 * Callback for when the delete column button is clicked.
		 *

		 * @access private
		 * @method _deleteColClicked
		 * @param {Object} e The event object.
		 */
		_deleteColClicked: function( e )
		{
			var button         = $( this ),
				col            = button.closest( '.ba-cheetah-col' ),
				parentGroup    = col.closest( '.ba-cheetah-col-group' ),
				parentCol      = col.parents( '.ba-cheetah-col' ),
				hasParentCol   = parentCol.length > 0,
				parentChildren = parentCol.find( '> .ba-cheetah-col-content > .ba-cheetah-module, > .ba-cheetah-col-content > .ba-cheetah-col-group' ),
				siblingCols    = col.siblings( '.ba-cheetah-col' ),
				insideModuleCols = col.closest('.ba-cheetah-row-type-row-inside').find('.ba-cheetah-col').length,
				isColInsideMod = col.closest( '.ba-cheetah-module' ).length > 0,
				result         = true;

			if (insideModuleCols == 1) {
				BACheetah.alert(BACheetahStrings.cantDeleteLastRowInside)
				e.stopPropagation();
				return;
			}
			else if ( col.find( '.ba-cheetah-module' ).length > 0 ) {
				result = confirm( BACheetahStrings.deleteColumnMessage );
			}			

			// Handle deleting of nested columns.
			if ( hasParentCol && 1 === parentChildren.length && !isColInsideMod) {
				if ( 0 === siblingCols.length ) {
					col = parentCol;
				}
				else if ( 1 === siblingCols.length && ! siblingCols.find( '.ba-cheetah-module' ).length ) {
					col = parentGroup;
				}
			}

			if ( result ) {
				BACheetah._deleteCol( col );
				BACheetah._removeAllOverlays();
				BACheetah._highlightEmptyCols();
				BACheetah._resizeLayout();
			}

			e.stopPropagation();
		},

		/**
		 * Deletes a column.
		 *

		 * @access private
		 * @method _deleteCol
		 * @param {Object} col A jQuery reference of the column to delete (can also be a group).
		 */
		_deleteCol: function(col)
		{
			var nodeId = col.attr('data-node'),
				row    = col.closest('.ba-cheetah-row'),
				group  = col.closest('.ba-cheetah-col-group'),
				cols   = null,
				width  = 0;

			col.remove();
			rowCols   = row.find('.ba-cheetah-row-content > .ba-cheetah-col-group > .ba-cheetah-col');
			groupCols = group.find(' > .ba-cheetah-col');

			if(0 === rowCols.length && 'row' != BACheetahConfig.userTemplateType && 'column' != BACheetahConfig.userTemplateType) {
				BACheetah._deleteRow(row);
			}
			else {

				if(0 === groupCols.length) {
					group.remove();
				}
				else {

					if ( 6 === groupCols.length ) {
						width = 16.65;
					}
					else if ( 7 === groupCols.length ) {
						width = 14.28;
					}
					else {
						width = Math.round( 100 / groupCols.length * 100 ) / 100;
					}

					groupCols.css('width', width + '%');

					BACheetah.triggerHook( 'didResetColumnWidths', {
						cols : groupCols
					} );
				}

				BACheetah.ajax({
					action          : 'delete_col',
					node_id         : nodeId,
					new_width       : width
				});

				BACheetah._initDropTargets();
				BACheetah._initSortables();
				BACheetah.triggerHook( 'didDeleteColumn', nodeId );
			}
		},

		/**
		 * Inserts a column (or columns) before or after another column.
		 *

		 * @access private
		 * @method _addCols
		 * @param {Object} col A jQuery reference of the column to insert before or after.
		 * @param {String} insert Either before or after.
		 * @param {String} type The type of column(s) to insert.
		 * @param {Boolean} nested Whether these columns are nested or not.
		 * @param {String} module Optional. The node ID of an existing module to move to this group.
		 */
		_addCols: function( col, insert, type, nested, module )
		{
			var parent   = col.closest( '.ba-cheetah-col-group' ),
				position = parent.find( '.ba-cheetah-col' ).index( col );

			type   = typeof type == 'undefined' ? '1-col' : type;
			nested = typeof nested == 'undefined' ? false : nested;

			if ( 'after' == insert ) {
				position++;
			}

			BACheetah._showNodeLoadingPlaceholder( parent, position );
			BACheetah._removeAllOverlays();

			BACheetah.ajax( {
				action          : 'render_new_columns',
				node_id         : col.attr('data-node'),
				insert 			: insert,
				type            : type,
				nested			: nested ? 1 : 0,
				module			: module,
			}, BACheetah._addColsComplete );
		},

		/**
		 * Adds the HTML for columns to the layout when the AJAX add
		 * operation is complete. Adds a module if one is queued to
		 * go in a new column.
		 *

		 * @access private
		 * @method _addColsComplete
		 * @param {Object|String} response The JSON response with the HTML for the new column(s).
		 */
		_addColsComplete: function( response )
		{
			var data = 'object' === typeof response ? response : BACheetah._jsonParse( response ),
				col = null;

			data.nodeParent   = BACheetah._newColParent;
			data.nodePosition = BACheetah._newColPosition;

			// Render the layout.
			BACheetah._renderLayout( data, function() {
				BACheetah._removeNodeLoadingPlaceholder( $( '.ba-cheetah-node-' + data.nodeId ) );
				BACheetah.triggerHook( 'didAddColumn', data.nodeId );
				BACheetah.triggerHook( 'didResetColumnWidths', {
					cols : $( '.ba-cheetah-node-' + data.nodeId ).find( '> .ba-cheetah-col' )
				} );
			} );
		},

		/**
		 * Adds a new column group to the layout.
		 *

		 * @access private
		 * @method _addColGroup
		 * @param {String} nodeId The node ID of the parent row.
		 * @param {String} cols The type of column layout to use.
		 * @param {Number} position The position of the new column group.
		 * @param {String} module Optional. The node ID of an existing module to move to this group.
		 */
		_addColGroup: function(nodeId, cols, position, module)
		{
			var parent = $( '.ba-cheetah-node-' + nodeId );

			// Save the new column group info.
			BACheetah._newColGroupPosition = position;

			if ( parent.hasClass( 'ba-cheetah-col' ) ) {
				BACheetah._newColGroupParent = parent.find( ' > .ba-cheetah-col-content' );
			}
			else {
				BACheetah._newColGroupParent = parent.find( '.ba-cheetah-row-content' );
			}

			// Show the loader.
			BACheetah._showNodeLoadingPlaceholder( BACheetah._newColGroupParent, position );

			// Send the request.
			BACheetah.ajax({
				action      : 'render_new_column_group',
				cols        : cols,
				node_id     : nodeId,
				position    : position,
				module		: module,
			}, BACheetah._addColGroupComplete);
		},

		/**
		 * Adds the HTML for a new column group to the layout when
		 * the AJAX add operation is complete. Adds a module if one
		 * is queued to go in the new column group.
		 *

		 * @access private
		 * @method _addColGroupComplete
		 * @param {String} response The JSON response with the HTML for the new column group.
		 */
		_addColGroupComplete: function(response)
		{
			var data    = BACheetah._jsonParse(response),
				html    = $(data.html),
				groupId = html.data('node'),
				colId   = html.find('.ba-cheetah-col').data('node');

			// Add new column group info to the data.
			data.nodeParent 	= BACheetah._newColGroupParent;
			data.nodePosition 	= BACheetah._newColGroupPosition;

			// Render the layout.
			BACheetah._renderLayout( data, function(){

				// Added the nested columns class if needed.
				if ( data.nodeParent.hasClass( 'ba-cheetah-col-content' ) ) {
					data.nodeParent.parents( '.ba-cheetah-col' ).addClass( 'ba-cheetah-col-has-cols' );
				}

				// Remove the loading placeholder.
				BACheetah._removeNodeLoadingPlaceholder( $( '.ba-cheetah-node-' + groupId ) );
				BACheetah.triggerHook( 'didAddColumnGroup', groupId );
			} );
		},

		/**
		 * Initializes draggables for column resizing.
		 *

		 * @access private
		 * @method _initColDragResizing
		 */
		_initColDragResizing: function()
		{
			$( '.ba-cheetah-block-col-resize' ).not( '.ba-cheetah-block-row-resize' ).draggable( {
				axis 	: 'x',
				start 	: BACheetah._colDragResizeStart,
				drag	: BACheetah._colDragResize,
				stop 	: BACheetah._colDragResizeStop
			} );
		},

		/**
		 * Fires when dragging for a column resize starts.
		 *

		 * @access private
		 * @method _colDragResizeStart
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_colDragResizeStart: function( e, ui )
		{
			// Setup resize vars.
			var handle 		   = $( ui.helper ),
				direction 	   = '',
				resizeParent   = handle.hasClass( 'ba-cheetah-block-col-resize-parent' ),
				parentCol      = resizeParent ? handle.closest( '.ba-cheetah-col' ).parents( '.ba-cheetah-col' ) : null,
				group		   = resizeParent ? parentCol.parents( '.ba-cheetah-col-group' ) : handle.closest( '.ba-cheetah-col-group' ),
				cols 		   = group.find( '> .ba-cheetah-col' ),
				col 		   = resizeParent ? parentCol : handle.closest( '.ba-cheetah-col' ),
				colId		   = col.attr( 'data-node' ),
				colSetting	   = $( '[data-node=' + colId + '] #ba-cheetah-field-size input' ),
				sibling 	   = null,
				siblingId 	   = null,
				siblingSetting = null,
				availWidth     = 100,
				i 			   = 0,
				setting		   = null,
				settingType    = null;

			// Find the direction and sibling.
			if ( handle.hasClass( 'ba-cheetah-block-col-resize-e' ) ) {
				direction = 'e';
				sibling   = col.nextAll( '.ba-cheetah-col' ).first();
			}
			else {
				direction = 'w';
				sibling   = col.prevAll( '.ba-cheetah-col' ).first();
			}

			siblingId 	   = sibling.attr( 'data-node' );
			siblingSetting = $( '[data-node=' + siblingId + '] #ba-cheetah-field-size input' );

			// Find the available width.
			for ( ; i < cols.length; i++ ) {

				if ( cols.eq( i ).data( 'node' ) == col.data( 'node' ) ) {
					continue;
				}
				if ( cols.eq( i ).data( 'node' ) == sibling.data( 'node' ) ) {
					continue;
				}

				availWidth -= parseFloat( cols.eq( i )[ 0 ].style.width );
			}

			// Find the setting if a column form is open.
			if ( colSetting.length ) {
				setting = colSetting;
				settingType = 'col';
			} else if ( siblingSetting.length ) {
				setting = siblingSetting;
				settingType = 'sibling';
			}

			// Build the resize data object.
			BACheetah._colResizeData = {
				handle			: handle,
				feedbackLeft	: handle.find( '.ba-cheetah-block-col-resize-feedback-left' ),
				feedbackRight	: handle.find( '.ba-cheetah-block-col-resize-feedback-right' ),
				direction		: direction,
				groupWidth		: group.outerWidth(),
				col 			: col,
				colWidth 		: parseFloat( col[ 0 ].style.width ) / 100,
				sibling 		: sibling,
				offset  		: ui.position.left,
				availWidth		: availWidth,
				setting			: setting,
				settingType		: settingType
			};

			// Set the resizing flag.
			BACheetah._colResizing = true;

			// Add the body col resize class.
			$( 'body' ).addClass( 'ba-cheetah-col-resizing' );

			// Close the builder panel and destroy overlay events.
			BACheetah._closePanel();
			BACheetah._destroyOverlayEvents();

			// Trigger the col-resize-start hook.
			BACheetah.triggerHook( 'col-resize-start' );
		},

		/**
		 * Fires when dragging for a column resize is in progress.
		 *

		 * @access private
		 * @method _colDragResize
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_colDragResize: function( e, ui )
		{
			// Setup resize vars.
			var data 			= BACheetah._colResizeData,
				directionRef	= BACheetahConfig.isRtl ? 'w' : 'e',
				overlay 		= data.handle.closest( '.ba-cheetah-block-overlay' ),
				change 			= ( data.offset - ui.position.left ) / data.groupWidth,
				colWidth 		= directionRef == data.direction ? ( data.colWidth - change ) * 100 : ( data.colWidth + change ) * 100,
				colRound 		= Math.round( colWidth * 100 ) / 100,
				siblingWidth	= data.availWidth - colWidth,
				siblingRound	= Math.round( siblingWidth * 100 ) / 100,
				minRound		= 8,
				maxRound		= Math.round( ( data.availWidth - minRound ) * 100 ) / 100;

			// Set the min/max width if needed.
			if ( colRound < minRound ) {
				colRound 		= minRound;
				siblingRound 	= maxRound;
			}
			else if ( siblingRound < minRound ) {
				colRound 		= maxRound;
				siblingRound 	= minRound;
			}

			// Set the feedback values.
			if ( directionRef == data.direction ) {
				data.feedbackLeft.html( colRound.toFixed( 1 ) + '%'  ).show();
				data.feedbackRight.html( siblingRound.toFixed( 1 ) + '%'  ).show();
			}
			else {
				data.feedbackLeft.html( siblingRound.toFixed( 1 ) + '%'  ).show();
				data.feedbackRight.html( colRound.toFixed( 1 ) + '%'  ).show();
			}

			// Set the width attributes.
			data.col.css( 'width', colRound + '%' );
			data.sibling.css( 'width', siblingRound + '%' );

			// Update the setting if the col or sibling's settings are open.
			if ( data.setting ) {
				if ( 'col' === data.settingType ) {
					data.setting.val( parseFloat( data.col[ 0 ].style.width ) );
				} else if ( 'sibling' === data.settingType ) {
					data.setting.val( parseFloat( data.sibling[ 0 ].style.width ) );
				}
			}

			// Build the overlay overflow menu if needed.
			BACheetah._buildOverlayOverflowMenu( overlay );

			// Trigger the col-resize-drag hook.
			BACheetah.triggerHook( 'col-resize-drag' );
		},

		/**
		 * Fires when dragging for a column resize stops.
		 *

		 * @access private
		 * @method _colDragResizeStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_colDragResizeStop: function( e, ui )
		{
			var data      	 = BACheetah._colResizeData,
				overlay   	 = BACheetah._colResizeData.handle.closest( '.ba-cheetah-block-overlay' ),
				colId     	 = data.col.data( 'node' ),
				colWidth  	 = parseFloat( data.col[ 0 ].style.width ),
				siblingId 	 = data.sibling.data( 'node' ),
				siblingWidth = parseFloat( data.sibling[ 0 ].style.width );

			// Hide the feedback divs.
			BACheetah._colResizeData.feedbackLeft.hide();
			BACheetah._colResizeData.feedbackRight.hide();

			// Save the resize data.
			BACheetah.ajax({
				action			: 'resize_cols',
				col_id			: colId,
				col_width		: colWidth,
				sibling_id		: siblingId,
				sibling_width	: siblingWidth
			});

			// Build the overlay overflow menu if needed.
			BACheetah._buildOverlayOverflowMenu( overlay );

			// Reset the resize data.
			BACheetah._colResizeData = null;

			// Remove the body col resize class.
			$( 'body' ).removeClass( 'ba-cheetah-col-resizing' );

			// Rebind overlay events.
			BACheetah._bindOverlayEvents();

			// Set the resizing flag to false with a timeout so other events get the right value.
			setTimeout( function() { BACheetah._colResizing = false; }, 50 );

			// Trigger the col-resize-stop hook.
			BACheetah.triggerHook( 'col-resize-stop' );

			BACheetah.triggerHook( 'didResizeColumn', {
				colId			: colId,
				colWidth		: colWidth,
				siblingId		: siblingId,
				siblingWidth	: siblingWidth
			} );
		},

		/**
		 * Resets the widths of all columns in a row when the
		 * Reset Column Widths button is clicked.
		 *

		 * @access private
		 * @method _resetColumnWidthsClicked
		 * @param {Object} e The event object.
		 */
		_resetColumnWidthsClicked: function( e )
		{
			var button   = $( this ),
				isRow    = !! button.closest( '.ba-cheetah-row-overlay' ).length,
				group    = null,
				groups   = null,
				groupIds = [],
				children = null,
				i        = 0,
				settings = $( '.ba-cheetah-col-settings' ),
				col		 = null;

			if ( isRow ) {
				groups = button.closest( '.ba-cheetah-row' ).find( '.ba-cheetah-row-content > .ba-cheetah-col-group' );
			} else {
				groups = button.parents( '.ba-cheetah-col-group' ).last();
			}

			groups.each( function() {

				group = $( this );
				children = group.find( '.ba-cheetah-col-group' );
				groupIds.push( group.data( 'node' ) );
				BACheetah._resetColumnWidths( group );

				for ( i = 0; i < children.length; i++ ) {
					BACheetah._resetColumnWidths( children.eq( i ) );
					groupIds.push( children.eq( i ).data( 'node' ) );
				}
			} );

			if ( settings.length ) {
				col = $( '.ba-cheetah-node-' + settings.attr( 'data-node' ) );
				settings.find( '#ba-cheetah-field-size input' ).val( parseFloat( col[ 0 ].style.width ) );
			}

			BACheetah.ajax({
				action		: 'reset_col_widths',
				group_id	: groupIds
			}, function() {
				BACheetah.triggerHook( 'didResetColumnWidthsComplete' );
			});

			BACheetah.triggerHook( 'col-reset-widths' );
			BACheetah._closeAllSubmenus();

			e.stopPropagation();
		},

		/**
		 * Resets the widths of all columns in a group.
		 *

		 * @access private
		 * @method _resetColumnWidths
		 * @param {Object} e The event object.
		 */
		_resetColumnWidths: function( group )
		{
			var cols  = group.find( ' > .ba-cheetah-col:visible' ),
				width = 0;

			if ( 6 === cols.length ) {
				width = 16.65;
			}
			else if ( 7 === cols.length ) {
				width = 14.28;
			}
			else {
				width = Math.round( 100 / cols.length * 100 ) / 100;
			}

			cols.css( 'width', width + '%' );

			BACheetah.triggerHook( 'didResetColumnWidths', {
				cols : cols
			} );
		},

		/* Modules
		----------------------------------------------------------*/

		/**
		 * Shows an overlay with actions when the mouse enters a module.
		 *

		 * @access private
		 * @method _moduleMouseenter
		 */
		_moduleMouseenter: function(e)
		{
			var module        = $( this ),
				moduleName    = module.attr( 'data-name' ),
				global		  = module.hasClass( 'ba-cheetah-node-global' ),
				parentGlobal  = module.parents( '.ba-cheetah-node-global' ).length > 0,
				group         = module.parents( '.ba-cheetah-col-group' ).last(),
				moduleParent  = module.parents( '.ba-cheetah-module' ),
				groupLoading  = group.hasClass( 'ba-cheetah-col-group-has-child-loading' ),
				numCols		  = module.closest( '.ba-cheetah-col-group' ).find( '> .ba-cheetah-col' ).length,
				col           = module.closest( '.ba-cheetah-col' ),
				colFirst      = 0 === col.index(),
				colLast       = numCols === col.index() + 1,
				parentCol     = col.parents( '.ba-cheetah-col' ),
				hasRowInside  = module.find( '.ba-cheetah-row-type-row-inside' ).length > 0,
				isModuleInside   = module.parent().closest('.ba-cheetah-module').length > 0,
				hasParentCol  = parentCol.length > 0,
				numParentCols = hasParentCol ? parentCol.closest( '.ba-cheetah-col-group' ).find( '> .ba-cheetah-col' ).length : 0,
				parentFirst   = hasParentCol ? 0 === parentCol.index() : false,
				parentLast    = hasParentCol ? numParentCols === parentCol.index() + 1 : false,
				isRootCol     = 'column' == BACheetahConfig.userTemplateType && ! hasParentCol,
				row			  = module.closest('.ba-cheetah-row'),
				isGlobalRow   = row.hasClass( 'ba-cheetah-node-global' ),
				rowIsFixedWidth = !! row.find('.ba-cheetah-row-fixed-width').addBack('.ba-cheetah-row-fixed-width').length,
				userCanResizeRows = BACheetahConfig.rowResize.userCanResizeRows,
				hasRules	  = module.hasClass( 'ba-cheetah-node-has-rules' ),
				rulesTextModule     = module.attr('data-rules-text'),
				rulesTypeModule     = module.attr('data-rules-type'),
				rulesTextCol    = col.attr('data-rules-text'),
				rulesTypeCol    = col.attr('data-rules-type'),
				colHasRules	  = col.hasClass( 'ba-cheetah-node-has-rules' ),
				template	  = wp.template( 'ba-cheetah-module-overlay' ),
				overlay       = null;

			if (e.type == 'mouseenter' && isModuleInside) {
				return;
			}
			else if ( global && parentGlobal && 'row' != BACheetahConfig.userTemplateType && isGlobalRow ) {
				return;
			}
			else if ( global && parentGlobal && 'column' != BACheetahConfig.userTemplateType && ! isGlobalRow  ) {
				return;
			}
			else if ( module.closest( '.ba-cheetah-node-loading' ).length ) {
				return;
			}
			else if ( module.find( '.ba-cheetah-inline-editor:visible' ).length ) {
				return;
			} else if ( ! module.hasClass( 'ba-cheetah-block-overlay-active' ) ) {
				
				// Remove existing overlays.
				BACheetah._removeColOverlays();
				BACheetah._removeModuleOverlays();
				
				// Append the template.
				overlay = BACheetah._appendOverlay( module, template( {
					global 		  		: global,
					moduleName	  		: moduleName,
					groupLoading  		: groupLoading,
					numCols		  		: numCols,
					colFirst      		: colFirst,
					colLast       		: colLast,
					isRootCol     		: isRootCol,
					hasParentCol  		: hasParentCol,
					numParentCols 		: numParentCols,
					parentFirst   		: parentFirst,
					parentLast    		: parentLast,
					rowIsFixedWidth 	: rowIsFixedWidth,
					userCanResizeRows : userCanResizeRows,
					hasRules          : hasRules,
					rulesTextModule   : rulesTextModule,
					rulesTypeModule   : rulesTypeModule,
					rulesTextCol      : rulesTextCol,
					rulesTypeCol      : rulesTypeCol,
					colHasRules       : colHasRules,
					hasRowInside      : hasRowInside,
				} ) );

				// Build the overlay overflow menu if necessary.
				BACheetah._buildOverlayOverflowMenu( overlay );

				// Init column resizing.
				BACheetah._initColDragResizing();
			}

			$( 'body' ).addClass( 'ba-cheetah-block-overlay-muted' );
		},

		/**
		 * Removes overlays when the mouse leaves a module.
		 *

		 * @access private
		 * @method _moduleMouseleave
		 * @param {Object} e The event object.
		 */
		 _moduleHeaderMouseenter: function(e)
		{
			console.log('***header');
		},

		/**
		 * Removes overlays when the mouse leaves a module.
		 *

		 * @access private
		 * @method _moduleMouseleave
		 * @param {Object} e The event object.
		 */
		_moduleMouseleave: function(e)
		{
			var module          = $(this),
				hasModuleInside = module.find( '.ba-cheetah-module' ).length > 0,
				isModuleInside  = module.parent().closest('.ba-cheetah-module').length > 0,
				target			= $( e.target ),
				toElement       = $(e.toElement) || $(e.relatedTarget),
				isTipTip        = toElement.is('#tiptip_holder'),
				isTipTipChild   = toElement.closest('#tiptip_holder').length > 0;

			if ( target.closest( '.ba-cheetah-block-col-resize' ).length ) {
				return;
			}
			if(isTipTip || isTipTipChild) {
				return;
			}

			//if(!hasModuleInside) {
				BACheetah._removeModuleOverlays(isModuleInside);
				BACheetah._removeColHighlightGuides();
			//}
		},

		/**
		 * Removes all module overlays from the page.
		 *

		 * @access private
		 * @method _removeModuleOverlays
		 */
		_removeModuleOverlays: function(isModuleInside)
		{
			var modules = $('.ba-cheetah-module');

			modules.removeClass('ba-cheetah-block-overlay-active');
			modules.find('.ba-cheetah-module-overlay').remove();
			
			
			$('body').removeClass('ba-cheetah-block-overlay-muted');
			BACheetah._closeAllSubmenus();
		},

		/**
		 * Returns a helper element for module drag operations.
		 *

		 * @access private
		 * @method _moduleDragHelper
		 * @param {Object} e The event object.
		 * @param {Object} item The element being dragged.
		 * @return {Object} The helper element.
		 */
		_moduleDragHelper: function(e, item)
		{
			return $('<div class="ba-cheetah-block-drag-helper">' + item.attr('data-name') + '</div>');
		},

		/**
		 * @method _moduleDragInit
		 * @param {Object} e The event object.
		 */
		_moduleDragInit: function( e )
		{
			var handle = $( e.target ),
				module = handle.closest( '.ba-cheetah-module' );

			BACheetah._blockDragInit( e );

			module.append( '<div class="ba-cheetah-module-sortable-proxy"></div>' );

			e.target = module.find( '.ba-cheetah-module-sortable-proxy' )[0];

			module.trigger( e );
		},

		/**
		 * @method _moduleDragInitTouch
		 * @param {Object} e The event object.
		 */
		_moduleDragInitTouch: function( startEvent )
		{
			var handle = $( startEvent.target ),
				module = handle.closest( '.ba-cheetah-module' ),
				moved  = false;

			handle.on( 'touchmove', function( moveEvent ) {
				if ( ! moved ) {
					startEvent.currentTarget = module[0];
					BACheetah._moduleDragInit( startEvent );
					moved = true;
				}
				moveEvent.target = module.find( '.ba-cheetah-module-sortable-proxy' )[0];
				$( moveEvent.target ).trigger( moveEvent );
			} );

			handle.on( 'touchend', function( endEvent ) {
				endEvent.target = module.find( '.ba-cheetah-module-sortable-proxy' )[0];
				$( endEvent.target ).trigger( endEvent );
				endEvent.stopPropagation();
				module.find( '.ba-cheetah-module-sortable-proxy' ).remove();
			} );
		},

		/**
		 * Callback that fires when dragging starts for a module.
		 *

		 * @access private
		 * @method _moduleDragStart
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_moduleDragStart: function( e, ui )
		{
			$( ui.item ).data( 'original-position', ui.item.index() );

			BACheetah._removeRowOverlays();
			BACheetah._blockDragStart( e, ui );
		},

		/**
		 * Callback for when a module drag operation completes.
		 *

		 * @access private
		 * @method _moduleDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_moduleDragStop: function(e, ui)
		{
			BACheetah._blockDragStop( e, ui );

			var item     = ui.item,
				parent   = item.parent(),
				node     = null,
				position = 0,
				parentId = 0;

			// Remove temporary sortable proxies used for custom handles.
			$( '.ba-cheetah-module-sortable-proxy' ).remove();

			// The module has pro or builderall restriction.
			if ( 0 <= $.inArray( item.attr( 'data-type' ), BACheetah._disabledModules ) ) {
				item.remove();
				BACheetah._showProMessage(BACheetah._disabledModulesFull[item.attr( 'data-type' )].name);
				return;
			}

			// Prevent recursive levels of row inside
			const wasDropedInside = item.parent().closest('.ba-cheetah-module').length;
			if (wasDropedInside && BACheetahConfig.modulesRowInsideEnabled.includes(item.attr( 'data-type' ))) {
				item.remove();
				BACheetah.alert(BACheetahStrings.dropModuleRecursivellyMessage)
				return;
			}

			// A module was dropped back into the module list.
			if ( parent.hasClass( 'ba-cheetah-modules' ) || parent.hasClass( 'ba-cheetah-widgets' ) ) {
				item.remove();
				return;
			}
			// A new module was dropped.
			else if ( item.hasClass( 'ba-cheetah-block' ) ) {

				// Cancel the drop if the sortable is disabled?
				if ( parent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
					item.remove();
					BACheetah._showPanel();
					return;
				}
				// A new module was dropped into a row position.
				else if ( parent.hasClass( 'ba-cheetah-row-drop-target' ) ) {
					parent   = item.closest('.ba-cheetah-content');
					parentId = 0;
					node     = item.closest('.ba-cheetah-row');
					position = parent.find( '.ba-cheetah-row' ).index( node );
				}
				// A new module was dropped into a column group position.
				else if ( parent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {
					parent   = item.closest( '.ba-cheetah-row-content' );
					parentId = parent.closest( '.ba-cheetah-row' ).attr( 'data-node' );
					node     = item.closest( '.ba-cheetah-col-group' );
					position = parent.find( ' > .ba-cheetah-col-group' ).index( node );
				}
				// A new module was dropped into a column position.
				else if ( parent.hasClass( 'ba-cheetah-col-drop-target' ) ) {
					parent   = item.closest( '.ba-cheetah-col-group' );
					parentId = parent.attr( 'data-node' );
					node     = item.closest( '.ba-cheetah-col' );
					position = parent.find( ' > .ba-cheetah-col' ).index( node );
				}
				// A new module was dropped into a column.
				else {
					position = parent.find( '> .ba-cheetah-module, .ba-cheetah-col-group, .ba-cheetah-block' ).index( item );
					parentId = item.closest( '.ba-cheetah-col' ).attr( 'data-node' );
				}

				// Increment the position?
				if ( item.closest( '.ba-cheetah-drop-target-last' ).length ) {
					position += 1;
				}

				// Add the new module.
				BACheetah._addModule( parent, parentId, item.attr( 'data-type' ), position, item.attr( 'data-widget' ), item.attr( 'data-alias' ) );

				// Remove the drag helper.
				item.remove();
			}
			// Cancel the drop if the sortable is disabled?
			else if ( parent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
				$( e.target ).append( ui.item );
				$( e.target ).children().eq( ui.item.data( 'original-position' ) ).before( ui.item );
				BACheetah._highlightEmptyCols();
				return;
			}
			// A module was dropped into a row position.
			else if ( parent.hasClass( 'ba-cheetah-row-drop-target' ) ) {
				node     = item.closest( '.ba-cheetah-row' );
				position = item.closest( '.ba-cheetah-content' ).children( '.ba-cheetah-row' ).index( node );
				position = item.closest( '.ba-cheetah-drop-target-last' ).length ? position + 1 : position;
				BACheetah._addRow( '1-col', position, item.attr( 'data-node' ) );
				item.remove();
			}
			// A module was dropped into a column group position.
			else if ( parent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {
				node     = item.closest( '.ba-cheetah-col-group' );
				position = item.closest( '.ba-cheetah-row-content ').find( ' > .ba-cheetah-col-group' ).index( node );
				position = item.closest( '.ba-cheetah-drop-target-last' ).length ? position + 1 : position;
				BACheetah._addColGroup( item.closest( '.ba-cheetah-row' ).attr( 'data-node' ), '1-col', position, item.attr( 'data-node' ) );
				item.remove();
			}
			// A module was dropped into a column position.
			else if ( parent.hasClass( 'ba-cheetah-col-drop-target' ) ) {
				node     = item.closest( '.ba-cheetah-col' );
				position = item.closest( '.ba-cheetah-col-drop-target-last' ).length ? 'after' : 'before';
				BACheetah._addCols( node, position, '1-col', item.closest( '.ba-cheetah-col-group-nested' ).length > 0, item.attr( 'data-node' ) );
				item.remove();
			}
			// A module was dropped into another column.
			else {
				BACheetah._reorderModule( item );
			}

			BACheetah._resizeLayout();
		},

		/**
		 * Reorders a module within a column.
		 *

		 * @access private
		 * @method _reorderModule
		 * @param {Object} module The module element being dragged.
		 */
		_reorderModule: function(module)
		{
			var newParent = module.closest('.ba-cheetah-col').attr('data-node'),
				oldParent = module.attr('data-parent'),
				nodeId    = module.attr('data-node'),
				position  = module.index();

			if(newParent == oldParent) {
				BACheetah._reorderNode( nodeId, position );
			}
			else {
				module.attr('data-parent', newParent);
				BACheetah._moveNode( newParent, nodeId, position );
			}
		},

		/**
		 * Callback for when the delete module button is clicked.
		 *

		 * @access private
		 * @method _deleteModuleClicked
		 * @param {Object} e The event object.
		 */
		_deleteModuleClicked: function(e)
		{
			var module = $(this).closest('.ba-cheetah-module'),
				result = confirm(BACheetahStrings.deleteModuleMessage);

			if(result) {
				BACheetah._deleteModule(module);
				BACheetah._removeAllOverlays();
			}

			e.stopPropagation();
		},

		/**
		 * Deletes a module.
		 *

		 * @access private
		 * @method _deleteModule
		 * @param {Object} module A jQuery reference of the module to delete.
		 */
		_deleteModule: function(module)
		{
			var row    = module.closest('.ba-cheetah-row'),
				nodeId = module.attr('data-node');

			BACheetah.ajax({
				action: 'delete_node',
				node_id: nodeId
			});

			module.empty();
			module.remove();
			row.removeClass('ba-cheetah-block-overlay-muted');
			BACheetah._highlightEmptyCols();
			BACheetah._removeAllOverlays();
			BACheetah.triggerHook( 'didDeleteModule', {
				nodeId: nodeId,
				moduleType: module.attr( 'data-type' ),
			} );
		},

		/**
		 * Duplicates a module.
		 *

		 * @access private
		 * @method _moduleCopyClicked
		 * @param {Object} e The event object.
		 */
		_moduleCopyClicked: function(e)
		{
			var win		 = $( window ),
				module   = $( this ).closest( '.ba-cheetah-module' ),
				nodeId   = module.attr( 'data-node' ),
				parent   = module.parent(),
				clone    = module.clone(),
				form	 = $( '.ba-cheetah-module-settings[data-node=' + nodeId + ']' ),
				settings = null;

			if ( form.length ) {
				settings = BACheetah._getSettings( form );
				BACheetahSettingsConfig.nodes[ nodeId ] = settings;
			}

			clone.addClass( 'ba-cheetah-node-' + nodeId + '-clone' + ' ba-cheetah-node-clone' );
			clone.find( '.ba-cheetah-block-overlay' ).remove();
			module.after( clone );
			BACheetah._showNodeLoading( nodeId + '-clone' );

			if ( win.scrollTop() + win.height() < clone.offset().top ) {
				$( 'html, body' ).animate( {
					scrollTop: clone.offset().top + clone.height() - win.height()
				}, 500 );
			}

			BACheetah.ajax({
				action: 'copy_module',
				node_id: nodeId,
				settings: settings
			}, function( response ) {
				var data = BACheetah._jsonParse( response );
				data.nodeParent   = parent;
				data.nodePosition = parent.find( ' > .ba-cheetah-col-group, > .ba-cheetah-module' ).index( clone );
				data.duplicatedModule = nodeId;
				data.onAddNewHTML = function() { clone.remove() };
				BACheetah._moduleCopyComplete( data );
			} );

			e.stopPropagation();
		},

		/**
		 * Callback for when a module has been duplicated.
		 *

		 * @access private
		 * @method _moduleCopyComplete
		 * @param {Object}
		 */
		_moduleCopyComplete: function( data )
		{
			BACheetah._renderLayout( data, function(){
				BACheetah.triggerHook( 'didDuplicateModule', {
					newNodeId  : data.nodeId,
					oldNodeId  : data.duplicatedModule,
					moduleType : data.moduleType,
				} );
			} );
		},

		/**
		 * Shows the settings lightbox and loads the module settings
		 * when the module settings button is clicked.
		 *

		 * @access private
		 * @method _moduleSettingsClicked
		 * @param {Object} e The event object.
		 */
		_moduleSettingsClicked: function(e)
		{
			var button   = $( this ),
				type     = button.closest( '.ba-cheetah-module' ).attr( 'data-type' ),
				name     = button.closest( '.ba-cheetah-module' ).attr( 'data-name' ),
				nodeId   = button.closest( '.ba-cheetah-module' ).attr( 'data-node' ),
				parentId = button.closest( '.ba-cheetah-col' ).attr( 'data-node' ),
				global 	 = button.closest( '.ba-cheetah-block-overlay-global' ).length > 0;

			e.stopPropagation();

			if ( -1 != jQuery.inArray( type, BACheetah._disabledModules ) ) {
				BACheetah._showProMessage(name);
				return;
			}

			if ( BACheetah._colResizing ) {
				return;
			}
			if ( global && ! BACheetahConfig.userCanEditGlobalTemplates ) {
				return;
			}

			BACheetah._showModuleSettings( {
				type     : type,
				nodeId   : nodeId,
				parentId : parentId,
				global   : global
			} );
		},

		/**
		 * Shows the lightbox and loads the settings for a module.
		 *

		 * @access private
		 * @method _showModuleSettings
		 * @param {Object} data
		 * @param {Function} callback
		 */
		_showModuleSettings: function( data, callback )
		{
			if ( ! BACheetahSettingsConfig.modules ) {
				return;
			}

			var config   = BACheetahSettingsConfig.modules[ data.type ],
				settings = data.settings ? data.settings : BACheetahSettingsConfig.nodes[ data.nodeId ],
				head 	 = $( 'head' ),
				layout   = null;

			// Add settings CSS and JS.
			if ( -1 === $.inArray( data.type, BACheetah._loadedModuleAssets ) ) {
				if ( '' !== config.assets.cssurl ) {

					// create link
					let style  		= document.createElement('link');
					style.href 		= config.assets.cssurl;
					style.rel  		= 'stylesheet';

					// append
					head.append(style);
				}
				if ( '' !== config.assets.jsurl ) {

					// create script
					let script 		= document.createElement('script');
					script.src	 	= config.assets.jsurl;

					// append
					head.append(script);
				}
				BACheetah._loadedModuleAssets.push( data.type );
			}

			// Render the form.
			BACheetahSettingsForms.render( {
				type	  : 'module',
				id        : data.type,
				nodeId    : data.nodeId,
				className : 'ba-cheetah-module-settings ba-cheetah-' + data.type + '-settings',
				attrs     : 'data-node="' + data.nodeId + '" data-parent="' + data.parentId + '" data-type="' + data.type + '"',
				buttons   : ! data.global && ! BACheetahConfig.simpleUi ? ['save-as'] : [],
				badges    : data.global ? [ BACheetahStrings.global ] : [],
				settings  : settings,
				legacy    : data.legacy,
				helper    : BACheetah._moduleHelpers[ data.type ],
				rules     : BACheetah._moduleHelpers[ data.type ] ? BACheetah._moduleHelpers[ data.type ].rules : null,
				messages  : BACheetah._moduleHelpers[ data.type ] ? BACheetah._moduleHelpers[ data.type ].messages : null,
				hide      : ( ! BACheetahConfig.userCanEditGlobalTemplates && data.global ) ? true : false,
				preview   : {
					type     : 'module',
					layout   : data.layout,
					callback : function() {
						BACheetah.triggerHook( 'didAddModule', {
							nodeId: data.nodeId,
							moduleType: settings.type,
						} );
					}
				}
			}, callback );
		},
		/**
		 * Validates the module settings and saves them if
		 * the form is valid.
		 *

		 * @access private
		 * @method _saveModuleClicked
		 */
		_saveModuleClicked: function()
		{
			var form      = $(this).closest('.ba-cheetah-settings'),
				type      = form.attr('data-type'),
				id        = form.attr('data-node'),
				helper    = BACheetah._moduleHelpers[type],
				valid     = true;

			if(typeof helper !== 'undefined') {

				form.find('label.error').remove();
				form.validate().hideErrors();
				valid = form.validate().form();

				if(valid) {
					valid = helper.submit();
				}
			}
			if(valid) {
				BACheetah._saveSettings();
			}
			else {
				BACheetah._toggleSettingsTabErrors();
			}
		},

		/**
		 * Adds a new module to a column and loads the settings.
		 *

		 * @access private
		 * @method _addModule
		 * @param {Object} parent A jQuery reference to the new module's parent.
		 * @param {String} parentId The node id of the new module's parent.
		 * @param {String} type The type of module to add.
		 * @param {Number} position The position of the new module within its parent.
		 * @param {String} widget The type of widget if this module is a widget.
		 * @param {String} alias A module alias key if this module is an alias to another module.
		 */
		_addModule: function( parent, parentId, type, position, widget, alias )
		{
			// Show the loader.
			BACheetah._showNodeLoadingPlaceholder( parent, position );

			// Save the new module data.
			if ( parent.hasClass( 'ba-cheetah-col-group' ) ) {
				BACheetah._newModuleParent 	 = null;
				BACheetah._newModulePosition = 0;
			}
			else {
				BACheetah._newModuleParent 	 = parent;
				BACheetah._newModulePosition = position;
			}

			// Send the request.
			BACheetah.ajax( {
				action          : 'render_new_module',
				parent_id       : parentId,
				type            : type,
				position        : position,
				node_preview    : 1,
				widget          : typeof widget === 'undefined' ? '' : widget,
				alias           : typeof alias === 'undefined' ? '' : alias
			}, BACheetah._addModuleComplete );
		},

		/**
		 * Shows the settings lightbox and sets the content when
		 * the module settings have finished loading.
		 *

		 * @access private
		 * @method _addModuleComplete
		 * @param {String} response The JSON encoded response.
		 */
		_addModuleComplete: function( response )
		{
			var data = BACheetah._jsonParse( response );

			// Setup a preview layout if we have one.
			if ( data.layout ) {
				if ( BACheetah._newModuleParent ) {
					BACheetah._newModuleParent.find( '.ba-cheetah-node-loading-placeholder' ).hide();
				}
				data.layout.nodeParent 	 = BACheetah._newModuleParent;
				data.layout.nodePosition = BACheetah._newModulePosition;
			}

			// Make sure we have settings before rendering the form.
			if ( ! data.settings ) {
				data.settings = BACheetahSettingsConfig.defaults.modules[ data.type ];
			}

			// Render the module if a settings form is already open.
			if ( $( 'form.ba-cheetah-settings' ).length ) {
				if ( data.layout ) {
					BACheetah._renderLayout( data.layout );
				}
			} else {
				BACheetah._showModuleSettings( data, function() {
					$( '.ba-cheetah-module-settings' ).data( 'new-module', '1' );
					// if you drag a wordpress widget and the lightbox is not pinned, it already performs the save
					if (data.type == 'widget' && BACheetahConfig.userSettings.pinned.position) {
						BACheetah._saveSettings();
					}
				});
			}
		},

		/**
		 * Registers a helper class for a module's settings.
		 *

		 * @method registerModuleHelper
		 * @param {String} type The type of module.
		 * @param {Object} obj The module helper.
		 */
		registerModuleHelper: function(type, obj)
		{
			var defaults = {
				rules: {},
				init: function(){},
				submit: function(){ return true; },
				preview: function(){}
			};

			BACheetah._moduleHelpers[type] = $.extend({}, defaults, obj);
		},

		/**
		 * Deprecated. Use the public method registerModuleHelper instead.
		 *

		 * @access private
		 * @method _registerModuleHelper
		 * @param {String} type The type of module.
		 * @param {Object} obj The module helper.
		 */
		_registerModuleHelper: function(type, obj)
		{
			BACheetah.registerModuleHelper(type, obj);
		},

		/* Node Templates
		----------------------------------------------------------*/

		/**
		 * Saves a node's settings and shows the node template settings
		 * when the Save As button is clicked.
		 *

		 * @access private
		 * @method _showNodeTemplateSettings
		 * @param {Object} e An event object.
		 */
		_showNodeTemplateSettings: function( e )
		{
			var form     = $( '.ba-cheetah-settings-lightbox .ba-cheetah-settings' ),
				nodeId   = form.attr( 'data-node' ),
				title    = BACheetahStrings.saveModule;

			if ( form.hasClass( 'ba-cheetah-row-settings' ) ) {
				title = BACheetahStrings.saveRow;
			}
			else if ( form.hasClass( 'ba-cheetah-col-settings' ) ) {
				title = BACheetahStrings.saveColumn;
			}

			if ( ! BACheetah._triggerSettingsSave( false, false, false ) ) {
				return false;
			}

			BACheetahSettingsForms.render( {
				id        : 'node_template',
				nodeId    : nodeId,
				title     : title,
				attrs     : 'data-node="' + nodeId + '"',
				className : 'ba-cheetah-node-template-settings',
				rules     : {
					name: {
						required: true
					}
				}
			}, function() {
				if ( ! BACheetahConfig.userCanEditGlobalTemplates ) {
					$( '#ba-cheetah-field-global' ).hide();
				}
			} );
		},

		/**
		 * Saves a node as a template when the save button is clicked.
		 *

		 * @access private
		 * @method _saveNodeTemplate
		 */
		_saveNodeTemplate: function()
		{
			var form   = $( '.ba-cheetah-node-template-settings' ),
				nodeId = form.attr( 'data-node' ),
				valid  = form.validate().form();

			if ( valid ) {

				BACheetah._showNodeLoading( nodeId );

				BACheetah.ajax({
					action	 : 'save_node_template',
					node_id  : nodeId,
					settings : BACheetah._getSettings( form )
				}, function( response ) {
					BACheetah._saveNodeTemplateComplete( response );
					BACheetah._hideNodeLoading( nodeId );
				} );

				BACheetah._lightbox.close();
			}
		},

		/**
		 * Callback for when a node template has been saved.
		 *

		 * @access private
		 * @method _saveNodeTemplateComplete
		 */
		_saveNodeTemplateComplete: function( response )
		{
			var data 		   = BACheetah._jsonParse( response ),
				panel 		   = $( '.ba-cheetah-saved-' + data.type + 's' ),
				blocks 		   = panel.find( '.ba-cheetah-block' ),
				block   	   = null,
				text    	   = '',
				name    	   = data.name.toLowerCase(),
				i			   = 0,
				template 	   = wp.template( 'ba-cheetah-node-template-block' ),
				newLibraryItem = {
					name: data.name,
					isGlobal: data.global,
					content: data.type,
					id: data.id,
					postID: data.postID,
					kind: "template",
					type: "user",
					link: data.link,
					category: {
						uncategorized: BACheetahStrings.uncategorized
					}
				};

			BACheetahConfig.contentItems.template.push(newLibraryItem);
			BACheetah.triggerHook('contentItemsChanged');

			// Update the layout for global templates.
			if ( data.layout ) {
				BACheetah._renderLayout( data.layout );
				BACheetah.triggerHook( 'didSaveGlobalNodeTemplate', data.config );
			}

			// Add the new template to the builder panel.
			if ( 0 === blocks.length ) {
				panel.append( template( data ) );
			}
			else {

				for ( ; i < blocks.length; i++ ) {

					block = blocks.eq( i );
					text  = block.text().toLowerCase().trim();

					if ( 0 === i && name < text ) {
						panel.prepend( template( data ) );
						break;
					}
					else if ( name < text ) {
						block.before( template( data ) );
						break;
					}
					else if ( blocks.length - 1 === i ) {
						panel.append( template( data ) );
						break;
					}
				}
			}

			// Remove the no templates placeholder.
			panel.find( '.ba-cheetah-block-no-node-templates' ).remove();
		},

		/**
		 * Performs collapse of the saved nodes tab
		 * 
		 * @access private
		 * @param {Object} e The event object.
		 */
		_collapseSavedTemplates: function(e) {
			const wrap = $(e.target).closest('.ba-cheetah-settings-section')
			wrap.find('.ba-cheetah-blocks-section-content').slideToggle(300, function(e) {
				wrap.toggleClass('ba-cheetah-settings-section-collapsed')
			})
		},

		/**
		 * Callback for when a node template drag from the
		 * builder panel has stopped.
		 *

		 * @access private
		 * @method _nodeTemplateDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_nodeTemplateDragStop: function( e, ui )
		{
			BACheetah._blockDragStop( e, ui );

			var item   		= ui.item,
				parent 		= item.parent(),
				parentId	= null,
				position 	= 0,
				node        = null,
				action 		= '',
				callback	= null;
				content_central_id 	= 0;

			// A node template was dropped back into the templates list.
			if ( parent.hasClass( 'ba-cheetah-blocks-section-content' ) ) {
				item.remove();
				return;
			}
			// A saved row was dropped.
			else if ( item.hasClass( 'ba-cheetah-block-saved-row' ) || item.hasClass( 'ba-cheetah-block-row-template' ) ) {
				node     = item.closest( '.ba-cheetah-row' );
				position = ! node.length ? 0 : $( BACheetah._contentClass + ' .ba-cheetah-row' ).index( node );
				position = parent.hasClass( 'ba-cheetah-drop-target-last' ) ? position + 1 : position;
				parentId = null;
				action	 = 'render_new_row_template';
				callback = BACheetah._addRowComplete;
				BACheetah._newRowPosition = position;
				BACheetah._showNodeLoadingPlaceholder( $( BACheetah._contentClass ), position );
			}
			// A saved column was dropped.
			else if ( item.hasClass( 'ba-cheetah-block-saved-column' ) ) {
				node       = item.closest( '.ba-cheetah-col' ),
				colGroup   = parent.closest( '.ba-cheetah-col-group' ),
				colGroupId = colGroup.attr( 'data-node' );

				action	 = 'render_new_col_template';
				callback = BACheetah._addColsComplete;

				// Cancel the drop if the sortable is disabled?
				if ( parent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
					item.remove();
					BACheetah._showPanel();
					return;
				}
				// A column was dropped into a row position.
				else if ( parent.hasClass( 'ba-cheetah-row-drop-target' ) ) {
					node     = item.closest( '.ba-cheetah-row' ),
					parentId = 0;
					parent   = $( BACheetah._contentClass );
					position = ! node.length ? 0 : parent.find( '.ba-cheetah-row' ).index( node );
				}
				// A column was dropped into a column group position.
				else if ( parent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {
					parent   = item.closest( '.ba-cheetah-row-content' );
					parentId = item.closest( '.ba-cheetah-row' ).attr( 'data-node' );
					position = item.closest( '.ba-cheetah-row' ).find( '.ba-cheetah-row-content > .ba-cheetah-col-group' ).index( item.closest( '.ba-cheetah-col-group' ) );
				}
				// A column was dropped into a column position.
				else if ( parent.hasClass( 'ba-cheetah-col-drop-target' ) ) {
				    parent   = item.closest('.ba-cheetah-col-group');
				    position = parent.children('.ba-cheetah-col').index( item.closest('.ba-cheetah-col') );
				    parentId = parent.attr('data-node');
				}

				// Increment the position?
				if ( item.closest( '.ba-cheetah-drop-target-last' ).length ) {
					position += 1;
				}

				if ( parent.hasClass( 'ba-cheetah-col-group' ) ) {
					BACheetah._newColParent   = null;
				}
				else {
					BACheetah._newColParent   = parent;
				}

				BACheetah._newColPosition = position;

				// Show the loader.
				BACheetah._showNodeLoadingPlaceholder( parent, position );
			}
			// A saved module was dropped.
			else if ( item.hasClass( 'ba-cheetah-block-saved-module' ) || item.hasClass( 'ba-cheetah-block-module-template' ) ) {

				action	 = 'render_new_module';
				callback = BACheetah._addModuleComplete;

				// Cancel the drop if the sortable is disabled?
				if ( parent.hasClass( 'ba-cheetah-sortable-disabled' ) ) {
					item.remove();
					BACheetah._showPanel();
					return;
				}
				// Dropped into a row position.
				else if ( parent.hasClass( 'ba-cheetah-row-drop-target' ) ) {
					parent   = item.closest('.ba-cheetah-content');
					parentId = 0;
					position = parent.find( '.ba-cheetah-row' ).index( item.closest('.ba-cheetah-row') );
				}
				// Dropped into a column group position.
				else if ( parent.hasClass( 'ba-cheetah-col-group-drop-target' ) ) {
					parent   = item.closest( '.ba-cheetah-row-content' );
					parentId = parent.closest( '.ba-cheetah-row' ).attr( 'data-node' );
					position = parent.find( ' > .ba-cheetah-col-group' ).index( item.closest( '.ba-cheetah-col-group' ) );
				}
				// Dropped into a column position.
				else if ( parent.hasClass( 'ba-cheetah-col-drop-target' ) ) {
					parent   = item.closest('.ba-cheetah-col-group');
					position = parent.children('.ba-cheetah-col').index( item.closest('.ba-cheetah-col') );
					parentId = parent.attr('data-node');
				}
				// Dropped into a column.
				else {
					position = parent.children( '.ba-cheetah-module, .ba-cheetah-block' ).index( item );
					parentId = item.closest( '.ba-cheetah-col' ).attr( 'data-node' );
				}

				// Increment the position?
				if ( item.closest( '.ba-cheetah-drop-target-last' ).length ) {
					position += 1;
				}

				// Save the new module data.
				if ( parent.hasClass( 'ba-cheetah-col-group' ) ) {
					BACheetah._newModuleParent 	 = null;
					BACheetah._newModulePosition = 0;
				}
				else {
					BACheetah._newModuleParent 	 = parent;
					BACheetah._newModulePosition = position;
				}

				// Show the loader.
				BACheetah._showNodeLoadingPlaceholder( parent, position );
			}

			// Apply and render the node template.
			BACheetah.ajax({
				action	 	  : action,
				template_id   : item.attr( 'data-id' ),
				template_type : item.attr( 'data-type' ),
				parent_id     : parentId,
				position 	  : position,
				content_central_id: item.attr('data-content-central-id'),
			}, function( response ) {
				if (action.indexOf('row') > -1 || action.indexOf('col') > -1) {
					var data = BACheetah._jsonParse( response );
					if(!BACheetah._hasBaCheetahError(data)){
						
						if (action.indexOf('row') > -1) {
							BACheetah.triggerHook( 'didApplyRowTemplateComplete', data.config );
						} else {
							BACheetah.triggerHook('didApplyColTemplateComplete', data.config);
						}

						callback( data.layout );
					} else {
						$('.ba-cheetah-node-loading-placeholder').remove();
						BACheetah._rowDragInit(e);
					}

				} else {
					callback( response );
				}
			} );

			// Remove the helper.
			item.remove();
		},

		_hasBaCheetahError: function(data) {
			if(data.ba_cheetah_error) {
				BACheetah.alert(data.ba_cheetah_error);
				return true;
			}
			return false;
		},

		/**
		 * Launches the builder in a new tab to edit a user
		 * defined node template when the edit link is clicked.
		 *

		 * @access private
		 * @method _editUserTemplateClicked
		 * @param {Object} e The event object.
		 */
		_editNodeTemplateClicked: function( e )
		{
			e.preventDefault();
			e.stopPropagation();

			window.open( $( this ).attr( 'href' ) );
		},

		/**
		 * Fires when the delete node template icon is clicked in the builder's panel.
		 *

		 * @access private
		 * @method _deleteNodeTemplateClicked
		 * @param {Object} e The event object.
		 */
		_deleteNodeTemplateClicked: function( e )
		{
			var button 		= $( e.target ),
				section 	= button.closest( '.ba-cheetah-blocks-section' ),
				panel   	= section.find( '.ba-cheetah-blocks-section-content' ),
				blocks  	= panel.find( '.ba-cheetah-block' ),
				block  		= button.closest( '.ba-cheetah-block' ),
				global 		= block.hasClass( 'ba-cheetah-block-global' ),
				callback 	= global ? BACheetah._updateLayout : undefined,
				message     = global ? BACheetahStrings.deleteGlobalTemplate : BACheetahStrings.deleteTemplate,
				index       = null;

			if ( confirm( message ) ) {

				// Delete the UI block.
				block.remove();

				// Add the no templates placeholder?
				if ( 1 === blocks.length ) {
					if ( block.hasClass( 'ba-cheetah-block-saved-row' ) ) {
						panel.append( '<span class="ba-cheetah-block-no-node-templates">' + BACheetahStrings.noSavedRows + '</span>' );
					}
					else {
						panel.append( '<span class="ba-cheetah-block-no-node-templates">' + BACheetahStrings.noSavedModules + '</span>' );
					}
				}

				// Show the loader?
				if ( block.hasClass( 'ba-cheetah-block-global' ) ) {
					BACheetah.showAjaxLoader();
				}

				// Delete the template.
				BACheetah.ajax({
					action 	     : 'delete_node_template',
					template_id  : block.attr( 'data-id' )
				}, callback);

				// Remove the item from library
				index = _.findIndex(BACheetahConfig.contentItems.template, {
					id: block.attr('data-id'),
					type: 'user'
				});

				BACheetahConfig.contentItems.template.splice(index, 1);
				BACheetah.triggerHook('contentItemsChanged');
			}
		},

		/* Settings
		----------------------------------------------------------*/

		/**
		 * Initializes logic for settings forms.
		 *

		 * @access private
		 * @method _initSettingsForms
		 */
		_initSettingsForms: function()
		{
			BACheetah._initSettingsSections();
			BACheetah._initButtonGroupFields();
			BACheetah._initCompoundFields();
			BACheetah._CodeFieldSSLCheck();
			BACheetah._initCodeFields();
			BACheetah._initColorPickers();
			BACheetah._initGradientPickers();
			BACheetah._initIconFields();
			BACheetah._initPhotoFields();
			BACheetah._initSelectFields();
			BACheetah._initEditorFields();
			BACheetah._initMultipleFields();
			BACheetah._initAutoSuggestFields();
			BACheetah._initLinkFields();
			BACheetah._initFontFields();
			BACheetah._initOrderingFields();
			BACheetah._initTimezoneFields();
			BACheetah._initDimensionFields();
			BACheetah._initFieldPopupSliders();
			BACheetah._initPresetFields();
			BACheetah._initDragDropFields();
			//BACheetah._focusFirstSettingsControl();
			BACheetah._calculateSettingsTabsOverflow();
			BACheetah._lightbox._resizeEditors();

			$( '.ba-cheetah-settings-fields' ).css( 'visibility', 'visible' );
			$( '.ba-cheetah-settings button' ).on( 'click', function( e ) { e.preventDefault() } )
			/**
		     * Hook for settings form init.
		     */
		    BACheetah.triggerHook('settings-form-init');
		},

		/**
		 * Destroys all active settings forms.
		 *

		 * @access private
		 * @method _destroySettingsForms
		 */
		_destroySettingsForms: function()
		{
			BACheetah._destroyEditorFields();
		},

		/**
		 * Inserts settings forms rendered with PHP. This method is only around for
		 * backwards compatibility with third party settings forms that are
		 * still being rendered via AJAX. Going forward, all settings forms
		 * should be rendered on the frontend using BACheetahSettingsForms.render.
		 *

		 * @access private
		 * @method _setSettingsFormContent
		 * @param {String} html
		 */
		_setSettingsFormContent: function( html )
		{
			$( '.ba-cheetah-legacy-settings' ).remove();
			$( 'body' ).append( html );
		},

		/**
		 * Shows the content for a settings form tab when it is clicked.
		 *

		 * @access private
		 * @method _settingsTabClicked
		 * @param {Object} e The event object.
		 */
		_settingsTabClicked: function(e)
		{
			var tab  = $( this ),
				form = tab.closest( '.ba-cheetah-settings' ),
				id   = tab.attr( 'href' ).split( '#' ).pop();

			BACheetah._resetSettingsTabsState();

			form.find( '.ba-cheetah-settings-tab' ).removeClass( 'ba-cheetah-active' );
			form.find( '#' + id ).addClass( 'ba-cheetah-active' );
			form.find( '.ba-cheetah-settings-tabs .ba-cheetah-active' ).removeClass( 'ba-cheetah-active' );
			form.find( 'a[href*=' + id + ']' ).addClass( 'ba-cheetah-active' );

			if ( BACheetahConfig.rememberTab ) {
				localStorage.setItem( 'ba-cheetah-settings-tab', id );
			} else {
				localStorage.setItem( 'ba-cheetah-settings-tab', '' );
			}

			BACheetah._focusFirstSettingsControl();

			e.preventDefault();
		},

		_resetSettingsTabsState: function() {
			var $lightbox = $('.bal-cheetah-lightbox:visible');

			BACheetah._hideTabsOverflowMenu();

			$lightbox.find('.ba-cheetah-settings-tabs .ba-cheetah-active').removeClass('ba-cheetah-active');
			$lightbox.find('.ba-cheetah-settings-tabs-overflow-menu .ba-cheetah-active').removeClass('ba-cheetah-active');
			$lightbox.find('.ba-cheetah-contains-active').removeClass('ba-cheetah-contains-active');
		},

		/**
		* Measures tabs and adds extra items to overflow menu.
		*

		* @access private
		* @return void
		* @method _settingsTabsToOverflowMenu
		*/
		_calculateSettingsTabsOverflow: function() {

			var $lightbox = $('.bal-cheetah-lightbox:visible'),
				lightboxWidth = $lightbox.outerWidth(),
				isSlim = $lightbox.hasClass('ba-cheetah-lightbox-width-slim'),
				$tabWrap = $lightbox.find('.ba-cheetah-settings-tabs'),
				$overflowMenu = $lightbox.find('.ba-cheetah-settings-tabs-overflow-menu'),
				$overflowMenuBtn = $lightbox.find('.ba-cheetah-settings-tabs-more'),
				$tabs = $tabWrap.find('a'),
				shouldEjectRemainingTabs = false,
				tabsAreaWidth = lightboxWidth - 60, /* 60 is size of "more" btn */
				tabsWidthTotal = 0,
				tabPadding = isSlim ? ( 8 * 2 ) : ( 15 * 2 );

			// Reset the menu
			$overflowMenu.html('');
			BACheetah._hideTabsOverflowMenu();

			$tabs.removeClass('ba-cheetah-overflowed');

			// Measure each tab
			$tabs.each(function() {

				if ( !$(this).is(":visible") ) {
					return true;
				}

				// Calculate size until too wide for tab area.
				if ( !shouldEjectRemainingTabs ) {

					// Width of text + padding + bumper space
						var currentTabWidth = $(this).textWidth() + tabPadding + 12;
					tabsWidthTotal += currentTabWidth;

					if ( tabsWidthTotal >= tabsAreaWidth ) {
						shouldEjectRemainingTabs = true;
					} else {
					}
				}

				if ( shouldEjectRemainingTabs ) {

					var label = $(this).html(),
						handle = $(this).attr('href'),
						classAttr = "";

					if ( $(this).hasClass('ba-cheetah-active') ) {
						classAttr = 'ba-cheetah-active';
					}
					if ( $(this).hasClass('error') ) {
						classAttr += ' error';
					}
					if ( classAttr !== '' ) {
						classAttr = 'class="' + classAttr + '"';
					}

					var $item = $('<a href="' + handle + '" ' + classAttr + '>' + label + '</a>');

					$overflowMenu.append( $item );
					$(this).addClass('ba-cheetah-overflowed');
				} else {

					$(this).removeClass('ba-cheetah-overflowed');
				}

			});

			if ( shouldEjectRemainingTabs ) {
				$lightbox.addClass('ba-cheetah-lightbox-has-tab-overflow');
			} else {
				$lightbox.removeClass('ba-cheetah-lightbox-has-tab-overflow');
			}

			if ( $overflowMenu.find('.ba-cheetah-active').length > 0 ) {
				$overflowMenuBtn.addClass('ba-cheetah-contains-active');
			} else {
				$overflowMenuBtn.removeClass('ba-cheetah-contains-active');
			}

			if ( $overflowMenu.find('.error').length > 0 ) {
				$overflowMenuBtn.addClass('ba-cheetah-contains-errors');
			} else {
				$overflowMenuBtn.removeClass('ba-cheetah-contains-errors');
			}
		},

		/**
		* Trigger the orignal tab when a menu item is clicked.
		*

		* @var {Event} e
		* @return void
		*/
		_settingsTabsToOverflowMenuItemClicked: function(e) {
			var $item = $(e.currentTarget),
				handle = $item.attr('href'),
				$tabsWrap = $item.closest('.ba-cheetah-lightbox-header-wrap').find('.ba-cheetah-settings-tabs'),
				$tab = $tabsWrap.find('a[href="' + handle + '"]'),
				$moreBtn = $tabsWrap.find('.ba-cheetah-settings-tabs-more');

			BACheetah._resetSettingsTabsState();
			$tab.trigger('click');
			$item.addClass('ba-cheetah-active');
			$moreBtn.addClass('ba-cheetah-contains-active');
			BACheetah._hideTabsOverflowMenu();
			e.preventDefault();
		},

		/**
		* Check if overflow menu contains any tabs
		*

		* @return bool
		*/
		_hasOverflowTabs: function() {
			var $lightbox = $('.bal-cheetah-lightbox:visible'),
				$tabs = $lightbox.find('.ba-cheetah-settings-tabs-overflow-menu a');

			if ( $tabs.length > 0 ) {
				return true;
			} else {
				return false;
			}
		},

		/**
		* Show the overflow menu
		*
		*/
		_showTabsOverflowMenu: function() {

			if ( ! BACheetah._hasOverflowTabs() ) return;

			var $lightbox = $('.bal-cheetah-lightbox:visible');
			$lightbox.find('.ba-cheetah-settings-tabs-overflow-menu').css('display', 'flex');
			$lightbox.find('.ba-cheetah-settings-tabs-overflow-click-mask').show();
			this.isShowingSettingsTabsOverflowMenu = true;
		},

		/**
		* Hide the overflow menu
		*/
		_hideTabsOverflowMenu: function() {
			var $lightbox = $('.bal-cheetah-lightbox:visible');
			$lightbox.find('.ba-cheetah-settings-tabs-overflow-menu').css('display', 'none');
			$lightbox.find('.ba-cheetah-settings-tabs-overflow-click-mask').hide();
			this.isShowingSettingsTabsOverflowMenu = false;
		},

		/**
		* Toggle the overflow menu
		*/
		_toggleTabsOverflowMenu: function( e ) {
			if ( BACheetah.isShowingSettingsTabsOverflowMenu ) {
				BACheetah._hideTabsOverflowMenu();
			} else {
				BACheetah._showTabsOverflowMenu();
			}
			e.stopPropagation();
		},

		/**
		 * Setup section toggling for all sections
		 *

		 * @access private
		 * @method _initSettingsSections
		 * @return void
		 */
		_initSettingsSections: function() {
			$( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-settings-section' ).each( BACheetah._initSection );
		},

		/**
		 * Reverts an active preview and hides the lightbox when
		 * the cancel button of a settings lightbox is clicked.
		 *

		 * @access private
		 * @method _settingsCancelClicked
		 * @param {Object} e The event object.
		 */
		_settingsCancelClicked: function(e)
		{
			var nestedLightbox = $( '.ba-cheetah-lightbox[data-parent]' ),
				moduleSettings = $('.ba-cheetah-module-settings'),
				existingNodes  = null,
				previewModule  = null,
				previewCol     = null,
				existingCol    = null,
				isRootCol      = 'column' == BACheetahConfig.userTemplateType;

			// Close a nested settings lightbox.
			if ( nestedLightbox.length > 0 ) {
				BACheetah._closeNestedSettings();
				return;
			}
			// Delete a new module preview?
			else if(moduleSettings.length > 0 && typeof moduleSettings.data('new-module') != 'undefined') {

				existingNodes = $(BACheetah.preview.state.html);
				previewModule = $('.ba-cheetah-node-' + moduleSettings.data('node'));
				previewCol    = previewModule.closest('.ba-cheetah-col');
				existingCol   = existingNodes.find('.ba-cheetah-node-' + previewCol.data('node'));

				if(existingCol.length > 0 || isRootCol) {
					BACheetah._deleteModule(previewModule);
				}
				else {
					BACheetah._deleteCol(previewCol);
				}
			}
			else if (BACheetah.preview && BACheetah.preview.type && BACheetah.preview.type == 'global' || BACheetah.preview.type == 'page') {
				BACheetah.preview.revertGlobalPagePreview()
			}

			// Do a standard preview revert.
			else if( BACheetah.preview ) {
				BACheetah.preview.revert();
			}

			BACheetah.preview = null;
			BACheetahLightbox.closeParent(this);
			BACheetah.triggerHook( 'didCancelNodeSettings' );
		},

		/**
		 * Focus the first visible control in a settings panel
		 *

		 */
		_focusFirstSettingsControl: function() {
			var form   = $( '.ba-cheetah-settings:visible' ),
				tab    = form.find( '.ba-cheetah-settings-tab.ba-cheetah-active' ),
				nodeId = form.data( 'node' ),
				field  = tab.find('.ba-cheetah-field').first(),
				input  = field.find( 'input:not([type="hidden"]), textarea, select, button, a, .ba-cheetah-editor-field' ).first();

			// Don't focus fields that have an inline editor.
			if ( nodeId && $( '.ba-cheetah-node-' + nodeId + ' .ba-cheetah-inline-editor' ).length ) {
				return;
			}

			if ( 'undefined' !== typeof tinyMCE && input.hasClass('ba-cheetah-editor-field') ) {
				// TinyMCE fields
				var id = input.find('textarea.wp-editor-area').attr('id');
				tinyMCE.get( id ).focus();
			} else {
				// Disable animations
				/*
				setTimeout(function() {
					input.focus().css('animation-name', 'ba-cheetah-grab-attention');
				}, 300 );
				*/
			}

			// Grab attention
			// field.css('animation-name', 'ba-cheetah-grab-attention');
			field.on('animationend', function() {
				field.css('animation-name', '');
			});
		},

		/**
		 * Initializes validation logic for a settings form.
		 *

		 * @access private
		 * @method _initSettingsValidation
		 * @param {Object} rules The validation rules object.
		 * @param {Object} messages Custom messages to show for invalid fields.
		 */
		_initSettingsValidation: function(rules, messages)
		{
			var form = $('.ba-cheetah-settings').last();

			if ( ! messages ) {
				messages = {}
			}

			form.validate({
				ignore: '.ba-cheetah-ignore-validation',
				rules: rules,
				messages: messages,
				errorPlacement: BACheetah._settingsErrorPlacement
			});
		},

		/**
		 * Places a validation error after the invalid field.
		 *

		 * @access private
		 * @method _settingsErrorPlacement
		 * @param {Object} error The error element.
		 * @param {Object} element The invalid field.
		 */
		_settingsErrorPlacement: function(error, element)
		{
			error.appendTo(element.parent());
		},

		/**
		 * Resets all tab error icons and then shows any for tabs
		 * that have fields with errors.
		 *

		 * @access private
		 * @method _toggleSettingsTabErrors
		 */
		_toggleSettingsTabErrors: function()
		{
			var form      = $('.ba-cheetah-settings:visible'),
				tabs      = form.find('.ba-cheetah-settings-tab'),
				tab       = null,
				tabErrors = null,
				i         = 0;

			for( ; i < tabs.length; i++) {

				tab = tabs.eq(i);
				tabErrors = tab.find('label.error');
				tabLink = form.find('.ba-cheetah-settings-tabs a[href*='+ tab.attr('id') +']');
				tabLink.find('.ba-cheetah-error-icon').remove();
				tabLink.removeClass('error');

				if(tabErrors.length > 0) {
					tabLink.append('<span class="ba-cheetah-error-icon"></span>');
					tabLink.addClass('error');
				}
			}

			BACheetah._calculateSettingsTabsOverflow();
		},

		/**
		 * Returns an object with key/value pairs for all fields
		 * within a settings form.
		 *

		 * @access private
		 * @method _getSettings
		 * @param {Object} form The settings form element.
		 * @return {Object} The settings object.
		 */
		_getSettings: function( form )
		{
			BACheetah._updateEditorFields();

			var data     	= form.serializeArray(),
				i        	= 0,
				k        	= 0,
				value	 	= '',
				name     	= '',
				key      	= '',
				keys      	= [],
				matches	 	= [],
				settings 	= {};

			// Loop through the form data.
			for ( i = 0; i < data.length; i++ ) {

				value = data[ i ].value.replace( /\r/gm, '' ).replace( /&#39;/g, "'" );

				// Don't save text editor textareas.
				if ( data[ i ].name.indexOf( 'flrich' ) > -1 ) {
					continue;
				}
				// Support foo[]... setting keys.
				else if ( data[ i ].name.indexOf( '[' ) > -1 ) {

					name 	= data[ i ].name.replace( /\[(.*)\]/, '' );
					key  	= data[ i ].name.replace( name, '' );
					keys	= [];
					matches = key.match( /\[[^\]]*\]/g );

					// Remove [] from the keys.
					for ( k = 0; k < matches.length; k++ ) {

						if ( '[]' == matches[ k ] ) {
							continue;
						}

						keys.push( matches[ k ].replace( /\[|\]/g, '' ) );
					}

					// foo[][key][key] or foo[][key][][key]
					if ( key.match( /\[\]\[[^\]]*\]\[[^\]]+\]/ ) || key.match( /\[\]\[[^\]]*\]\[\]\[[^\]]+\]/ ) ) {

						if ( 'undefined' == typeof settings[ name ] ) {
							settings[ name ] = {};
						}
						if ( 'undefined' == typeof settings[ name ][ keys[ 0 ] ] ) {
							settings[ name ][ keys[ 0 ] ] = {};
						}

						settings[ name ][ keys[ 0 ] ][ keys[ 1 ] ] = value;

					}
					// foo[][key][]
					else if ( key.match( /\[\]\[[^\]]*\]\[\]/ ) ) {

						if ( 'undefined' == typeof settings[ name ] ) {
							settings[ name ] = {};
						}
						if ( 'undefined' == typeof settings[ name ][ keys[ 0 ] ] ) {
							settings[ name ][ keys[ 0 ] ] = [];
						}

						settings[ name ][ keys[ 0 ] ].push( value );
					}
					// foo[][key]
					else if ( key.match( /\[\]\[[^\]]*\]/ ) ) {

						if ( 'undefined' == typeof settings[ name ] ) {
							settings[ name ] = {};
						}

						settings[ name ][ keys[ 0 ] ] = value;

					}
					// foo[]
					else if ( key.match( /\[\]/ ) ) {

						if ( 'undefined' == typeof settings[ name ] ) {
							settings[ name ] = [];
						}

						settings[ name ].push( value );
					}
				}
				// Standard name/value pair.
				else {
					settings[ data[ i ].name ] = value;
				}
			}

			// Update auto suggest values.
			for ( key in settings ) {

				if ( 'undefined' != typeof settings[ 'as_values_' + key ] ) {

					settings[ key ] = $.grep(
						settings[ 'as_values_' + key ].split( ',' ),
						function( n ) {
							return n !== '';
						}
					).join( ',' );

					try {
						delete settings[ 'as_values_' + key ];
					}
					catch( e ) {}
				}
			}

			// In the case of multi-select or checkboxes we need to put the blank setting back in.
			$.each( form.find( '[name]' ), function( key, input ) {
				var name = $( input ).attr( 'name' ).replace( /\[(.*)\]/, '' );
				if ( ! ( name in settings ) ) {
					settings[ name ] = '';
				}
			});

			// Merge in the original settings in case legacy fields haven't rendered yet.
			settings = $.extend( {}, BACheetah._getOriginalSettings( form ), settings );

			// Return the settings.
			return settings;
		},

		/**
		 * Returns JSON encoded settings to be used in HTML form elements.
		 *

		 * @access private
		 * @method _getSettingsJSONForHTML
		 * @param {Object} settings The settings object.
		 * @return {String} The settings JSON.
		 */
		_getSettingsJSONForHTML: function( settings )
		{
			return JSON.stringify( settings ).replace( /\'/g, '&#39;' ).replace( '<wbr \/>', '<wbr>' );
		},

		/**
		 * Returns the original settings for a settings form. This is only
		 * used to work with legacy PHP settings fields.
		 *

		 * @access private
		 * @method _getOriginalSettings
		 * @param {Object} form The settings form element.
		 * @param {Boolean} all Whether to include all of the settings or just those with fields.
		 * @return {Object} The settings object.
		 */
		_getOriginalSettings: function( form, all )
		{
			var formJSON = form.find( '.ba-cheetah-settings-json' ),
				nodeId	 = form.data( 'node' ),
				config   = BACheetahSettingsConfig.nodes,
				original = null,
				settings = {};

			if ( nodeId && config[ nodeId ] ) {
				original = config[ nodeId ];
			} else if ( formJSON.length ) {
				original = BACheetah._jsonParse( formJSON.val().replace( /&#39;/g, "'" ) );
			}

			if ( original ) {
				for ( key in original ) {
					if ( $( '#ba-cheetah-field-' + key ).length || all ) {
						settings[ key ] = original[ key ];
					}
				}
			}

			return settings;
		},

		/**
		 * Gets the settings that are saved to see if settings
		 * have changed when saving or canceling.
		 *

		 * @method getSettingsForChangedCheck
		 * @param {Object} form
		 * @return {Object}
		 */
		_getSettingsForChangedCheck: function( nodeId, form ) {
			var settings = BACheetah._getSettings( form );

			// Make sure we're getting the original setting if even it
			// was changed by inline editing before the form loaded.
			if ( nodeId ) {
				var node = $( '.ba-cheetah-node-' + nodeId );

				if ( node.hasClass( 'ba-cheetah-module' ) ) {
					var type = node.data( 'type' );
					var config = BACheetahSettingsConfig.editables[ type ];

					if ( config && BACheetahSettingsConfig.nodes[ nodeId ] ) {
						for ( var key in config ) {
							settings[ key ] = BACheetahSettingsConfig.nodes[ nodeId ][ key ]
						}
					}
				}
			}

			return settings;
		},

		/**
		 * Saves the settings for the current settings form, shows
		 * the loader and hides the lightbox.
		 *

		 * @access private
		 * @method _saveSettings
		 * @param {Boolean} render Whether the layout should render after saving.
		 */
		_saveSettings: function( render )
		{
			var form      = $( '.ba-cheetah-settings-lightbox .ba-cheetah-settings' ),
				newModule = form.data( 'new-module' ),
				nodeId    = form.attr( 'data-node' ),
				settings  = BACheetah._getSettings( form ),
				preview   = BACheetah.preview;

			// Default to true for render.
			if ( BACheetah.isUndefined( render ) || ! BACheetah.isBoolean( render ) ) {
				render = true;
			}

			// Only proceed if the settings have changed.
			if ( preview && ! preview._settingsHaveChanged() && BACheetah.isUndefined( newModule ) ) {
				BACheetah._lightbox.close();
				return;
			}

			function finishSavingSettings() {

				// Show the loader.
				BACheetah._showNodeLoading( nodeId );

				// Update the settings config object.
				BACheetahSettingsConfig.nodes[ nodeId ] = settings;

				// Make the AJAX call.
				BACheetah.ajax( {
					action          : 'save_settings',
					node_id         : nodeId,
					settings        : settings
				}, BACheetah._saveSettingsComplete.bind( this, render, preview ) );

				// Trigger the hook.
				BACheetah.triggerHook( 'didSaveNodeSettings', {
					nodeId   : nodeId,
					settings : settings
				} );

				// Close the lightbox.
				BACheetah._lightbox.close();
			}

			if ( BACheetahConfig.userCaps.unfiltered_html ) {
				finishSavingSettings()
			} else {
				BACheetahSettingsForms.showLightboxLoader()
				BACheetah.ajax( {
					action          : 'verify_settings',
					settings        : settings,
				}, function( response ) {
					if ( 'true' === response ) {
						finishSavingSettings()
					} else {
						msg = '<p style="font-weight:bold;text-align:center;">' + BACheetahStrings.noScriptWarn.heading + '</p>';
						if ( BACheetahConfig.userCaps.global_unfiltered_html ) {
							msg += '<p>' + BACheetahStrings.noScriptWarn.global + '</p>';
						} else {
							msg += '<p>' + BACheetahStrings.noScriptWarn.message + '</p>';
						}

						msg += '<p><div class="ba-cheetah-diff"></div></p>';
						BACheetahSettingsForms.hideLightboxLoader()
						BACheetah.alert( msg );
						data = $.parseJSON(response);
						if ( '' !== data.diff  ) {
							$('.ba-cheetah-diff').html( data.diff );
							$('.ba-cheetah-diff').prepend( '<p>' + BACheetahStrings.codeErrorDetected + '</p>');
							$('.ba-cheetah-diff .diff-deletedline').each(function(){
								if ( $(this).find('del').length < 1 ) {
									$(this).css('background-color', 'rgb(255, 192, 203, 0.7)').css('padding', '10px').css('border', '1px solid pink');
								} else {
									$(this).find('del').css('background-color', 'rgb(255, 192, 203, 0.7)').css('border', '1px solid pink');
								}
							});
							console.log( '============' );
							console.log( 'key: ' + data.key );
							console.log( 'value: ' + data.value );
							console.log( 'parsed: ' + data.parsed );
							console.log( '============' );
						}

					}
				} );
			}
		},

		/**
		 * Renders a new layout when the settings for the current
		 * form have finished saving.
		 *

		 * @access private
		 * @method _saveSettingsComplete
		 * @param {Boolean} render Whether the layout should render after saving.
		 * @param {Object} preview The preview object for this settings save.
		 * @param {String} response The layout data from the server.
		 */
		_saveSettingsComplete: function( render, preview, response )
		{
			var data 	 	= BACheetah._jsonParse( response ),
				type	 	= data.layout.nodeType,
				moduleType	= data.layout.moduleType,
				hook	 	= 'didSave' + type.charAt(0).toUpperCase() + type.slice(1) + 'SettingsComplete',
				callback 	= function() {
					if( preview && data.layout.partial && data.layout.nodeId === preview.nodeId ) {
						preview.clear();
						preview = null;
					}
				};

			if ( true === render ) {
				BACheetah._renderLayout( data.layout, callback );
			} else {
				callback();
			}

			BACheetah.triggerHook( 'didSaveNodeSettingsComplete', {
				nodeId   	: data.node_id,
				nodeType 	: type,
				moduleType	: moduleType,
				settings 	: data.settings
			} );

			BACheetah.triggerHook( hook, {
				nodeId   	: data.node_id,
				nodeType 	: type,
				moduleType	: moduleType,
				settings 	: data.settings
			} );
		},

		/**
		 * Triggers a click on the settings save button so all save
		 * logic runs for any form that is currently in the lightbox.
		 *

		 * @access private
		 * @method _triggerSettingsSave
		 * @param {Boolean} disableClose
		 * @param {Boolean} showAlert
		 * @param {Boolean} destroy
		 * @return {Boolean}
		 */
		_triggerSettingsSave: function( disableClose, showAlert, destroy )
		{
			var form	   = BACheetah._lightbox._node.find( 'form.ba-cheetah-settings' ),
				lightboxId = BACheetah._lightbox._node.data( 'instance-id' ),
				lightbox   = BACheetahLightbox._instances[ lightboxId ],
				nested     = $( '.ba-cheetah-lightbox-wrap[data-parent]:visible' ),
				changed    = false,
				valid	   = true;

			disableClose = _.isUndefined( disableClose ) ? false : disableClose;
			showAlert 	 = _.isUndefined( showAlert ) ? false : showAlert;
			destroy 	 = _.isUndefined( destroy ) ? true : destroy;

			if ( form.length ) {

				// Save any nested settings forms.
				if ( nested.length ) {

					// Save the form.
					nested.find( '.ba-cheetah-settings-save' ).trigger( 'click' );

					// Don't proceed if not saved.
					if ( nested.find( 'label.error' ).length || $( '.ba-cheetah-alert-lightbox:visible' ).length ) {
						valid = false;
					}
				}

				// Do a validation check of the main form to see if we should save.
				if ( valid && ! form.validate().form() ) {
					valid = false;
				}

				// Check to see if the main settings have changed.
				changed = BACheetahSettingsForms.settingsHaveChanged();

				// Save the main settings form if it has changes.
				if ( valid && changed ) {

					// Disable lightbox close?
					if ( disableClose ) {
						lightbox.disableClose();
					}

					// Save the form.
					form.find( '.ba-cheetah-settings-save' ).trigger( 'click' );

					// Enable lightbox close if it was disabled.
					if ( disableClose ) {
						lightbox.enableClose();
					}

					// Don't proceed if not saved.
					if ( form.find( 'label.error' ).length || $( '.ba-cheetah-alert-lightbox:visible' ).length ) {
						valid = false;
					}
				}

				// Destroy the settings form?
				if ( destroy ) {

					BACheetah._destroySettingsForms();

					// Destroy the preview if settings don't have changes.
					if ( ! changed && BACheetah.preview ) {
						BACheetah.preview.clear();
						BACheetah.preview = null;
					}
				}

				// Close the main lightbox if it doesn't have changes and closing isn't disabled.
				if ( ! changed && ! disableClose ) {
					lightbox.close();
				}
			}

			if ( ! valid ) {
				BACheetah.triggerHook( 'didFailSettingsSave' );
				BACheetah._toggleSettingsTabErrors();
				if ( showAlert && ! $( '.ba-cheetah-alert-lightbox:visible' ).length ) {
					BACheetah.alert( 
						BACheetahStrings.settingsHaveErrorsCorrect,
						BACheetahStrings.settingsHaveErrors,
						'ba-cheetah-lightbox-confirm-icon',
						'validation-error.svg'
					);
				}
			} else {
				BACheetah.triggerHook( 'didTriggerSettingsSave' );
			}

			return valid;
		},

		/**
		 * Refreshes preview references for a node's settings panel
		 * in case they have been broken by work in the layout.
		 *

		 * @access private
		 * @method _refreshSettingsPreviewReference
		 */
		_refreshSettingsPreviewReference: function()
		{
			if ( BACheetah.preview ) {
				BACheetah.preview._initElementsAndClasses();
			}
		},

		/* Nested Settings Forms
		----------------------------------------------------------*/

		/**
		 * Opens a nested settings lightbox.
		 *

		 * @access private
		 * @method _openNestedSettings
		 * @return object The settings lightbox object.
		 */
		_openNestedSettings: function( settings )
		{
			if ( settings.className && -1 === settings.className.indexOf( 'ba-cheetah-settings-lightbox' ) ) {
				settings.className += ' ba-cheetah-settings-lightbox';
			}

			settings = $.extend( {
				className: 'ba-cheetah-lightbox  ba-cheetah-settings-lightbox',
				destroyOnClose: true,
				resizable: true
			}, settings );

			var parentBoxWrap  = $( '.ba-cheetah-lightbox-wrap:visible' ),
				parentBox      = parentBoxWrap.find( '.bal-cheetah-lightbox' ),
				nestedBoxObj   = new BACheetahLightbox( settings ),
				nestedBoxWrap  = nestedBoxObj._node,
				nestedBox      = nestedBoxWrap.find( '.bal-cheetah-lightbox' );

			parentBoxWrap.hide();
			nestedBoxWrap.attr( 'data-parent', parentBoxWrap.attr( 'data-instance-id' ) );
			nestedBox.attr( 'style', parentBox.attr( 'style' ) );
			nestedBoxObj.on( 'resized', BACheetah._calculateSettingsTabsOverflow );
			nestedBoxObj.open( '<div class="ba-cheetah-lightbox-loading"></div>' );

			return nestedBoxObj;
		},

		/**
		 * Opens the active nested settings lightbox.
		 *

		 * @access private
		 * @method _closeNestedSettings
		 */
		_closeNestedSettings: function()
		{
			var nestedBoxWrap = $( '.ba-cheetah-lightbox[data-parent]:visible' ),
				nestedBox     = nestedBoxWrap.find( '.bal-cheetah-lightbox' ),
				nestedBoxId   = nestedBoxWrap.attr( 'data-instance-id' ),
				nestedBoxObj  = BACheetahLightbox._instances[ nestedBoxId ],
				parentBoxId   = nestedBoxWrap.attr( 'data-parent' ),
				parentBoxWrap = $( '[data-instance-id="' + parentBoxId + '"]' ),
				parentBox     = parentBoxWrap.find( '.bal-cheetah-lightbox' ),
				parentBoxForm = parentBoxWrap.find('form'),
				parentBoxObj  = BACheetahLightbox._instances[ parentBoxId ];

			if ( ! nestedBoxObj ) {
				return
			}

			nestedBoxObj.on( 'close', function() {
				parentBox.attr( 'style', nestedBox.attr( 'style' ) );
				parentBoxWrap.show();
				parentBoxObj._resize();
				parentBoxWrap.find( 'label.error' ).remove();
				parentBoxForm.validate().hideErrors();
				BACheetah._toggleSettingsTabErrors();
				BACheetah._initMultipleFields();
			} );

			nestedBoxObj.close();
		},

		/* Tooltips
		----------------------------------------------------------*/

		/**
		 * Shows a help tooltip in the settings lightbox.
		 *

		 * @access private
		 * @method _showHelpTooltip
		 */
		_showHelpTooltip: function()
		{
			$(this).siblings('.ba-cheetah-help-tooltip-text').fadeIn();
		},

		/**
		 * Hides a help tooltip in the settings lightbox.
		 *

		 * @access private
		 * @method _hideHelpTooltip
		 */
		_hideHelpTooltip: function()
		{
			$(this).siblings('.ba-cheetah-help-tooltip-text').fadeOut();
		},

		/**
		 * Setup section toggling
		 *

		 * @access private
		 * @method _initSection
		 * @return void
		 */
		_initSection: function() {
			var wrap = $(this),
				button = wrap.find('.ba-cheetah-settings-section-header');

			button.on('click', function() {
				// wrap.toggleClass('ba-cheetah-settings-section-collapsed')
				wrap.siblings().has('.ba-cheetah-settings-section-header')
					.addClass('ba-cheetah-settings-section-collapsed')
					.find('.ba-cheetah-settings-section-content').slideUp(300)

				wrap.find('.ba-cheetah-settings-section-content').slideToggle(300, function(e) {
					wrap.toggleClass('ba-cheetah-settings-section-collapsed')
				})				
			});
		},

		/* Align Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all button group fields within a settings form.
		 *

		 * @access private
		 * @method _initButtonGroupFields
		 */
		_initButtonGroupFields: function()
		{
			$( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-button-group-field' ).each( BACheetah._initButtonGroupField );
		},

		/**
		 * Initializes a button group field within a settings form.
		 *

		 * @access private
		 * @method _initButtonGroupField
		 */
		_initButtonGroupField: function()
		{
			var wrap = $( this ),
				options = wrap.find( '.ba-cheetah-button-group-field-option' ),
				input = wrap.find( 'input' ),
				allowUnselect = input.attr( 'data-unselectable' ) == 'true';

			options.on( 'click', function() {
				var option = $( this );
				if ( '1' == option.attr( 'data-selected' ) ) {
					if (allowUnselect) {
						option.attr( 'data-selected', '0' );
						input.val( '' ).trigger( 'change' );
					}
				} else {
					options.attr( 'data-selected', '0' );
					option.attr( 'data-selected', '1' );
					input.val( option.data( 'value' ) ).trigger( 'change' );
				}
			} );

			// Handle value being changed externally
			input.on( 'change', function( e ) {
				var value = input.val(),
					option = options.filter( '[data-value="' + value + '"]' );

				// Unset other options
				options.attr('data-selected', '0' );

				// Set the matching one.
				option.attr( 'data-selected', '1' );

			});
		},

		/* Compound Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all compound fields within a settings form.
		 *

		 * @access private
		 * @method _initCompoundFields
		 */
		_initCompoundFields: function()
		{
			$( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-compound-field' ).each( BACheetah._initCompoundField );
		},

		/**
		 * Initializes a compound field within a settings form.
		 *

		 * @access private
		 * @method _initCompoundField
		 */
		_initCompoundField: function()
		{
			var wrap = $( this ),
				sections = wrap.find( '.ba-cheetah-compound-field-section' ),
				toggles = wrap.find( '.ba-cheetah-compound-field-section-toggle' ),
				dimensions = wrap.find( '.ba-cheetah-compound-field-setting' ).has( '.ba-cheetah-dimension-field-units' );

			sections.each( function() {
				var section = $( this );
				if ( ! section.find( '.ba-cheetah-compound-field-section-toggle' ).length ) {
					section.addClass( 'ba-cheetah-compound-field-section-visible' );
				}
			} );

			toggles.on( 'click', function() {
				var toggle = $( this ),
					field = toggle.closest( '.ba-cheetah-field' ),
					section = toggle.closest( '.ba-cheetah-compound-field-section' ),
					className = '.' + section.attr( 'class' ).split( ' ' ).join( '.' );

				field.find( className ).toggleClass( 'ba-cheetah-compound-field-section-visible' );
			} );

			// Init linking for compound dimension fields.
			dimensions.each( function() {
				var field = $( this ),
					label = field.find( '.ba-cheetah-compound-field-label' ),
					icon = '<i class="ba-cheetah-dimension-field-link ba-cheetah-tip dashicons dashicons-admin-links" title="Link Values"></i>';

				if ( ! label.length || field.find( '.ba-cheetah-shadow-field' ).length ) {
					return;
				}

				label.append( icon );
			} );
		},

		/* Auto Suggest Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all auto suggest fields within a settings form.
		 *

		 * @access private
		 * @method _initAutoSuggestFields
		 */
		_initAutoSuggestFields: function()
		{
			var fields = $('.ba-cheetah-settings:visible .ba-cheetah-suggest-field'),
				field  = null,
				values = null,
				name   = null,
				data   = [];

			fields.each( function() {
				field = $( this );
				if ( '' !== field.attr( 'data-value' ) ) {
					BACheetahSettingsForms.showFieldLoader( field );
					data.push( {
						name   : field.attr( 'name' ),
						value  : field.attr( 'data-value' ),
						action : field.attr( 'data-action' ),
						data   : field.attr( 'data-action-data' ),
					} );
				}
			} );

			if ( data.length ) {
				BACheetah.ajax( {
					action: 'get_autosuggest_values',
					fields: data
				}, function( response ) {
					values = BACheetah._jsonParse( response );
					for ( name in values ) {
						$( '.ba-cheetah-suggest-field[name="' + name + '"]' ).attr( 'data-value', values[ name ] );
					}
					fields.each( BACheetah._initAutoSuggestField );
				} );
			} else {
				fields.each( BACheetah._initAutoSuggestField );
			}
		},

		/**
		 * Initializes a single auto suggest field.
		 *

		 * @access private
		 * @method _initAutoSuggestField
		 */
		_initAutoSuggestField: function()
		{
			var field = $(this);

			field.autoSuggest(BACheetah._ajaxUrl({
				'ba_cheetah_action'         : 'ba_cheetah_autosuggest',
				'ba_cheetah_as_action'      : field.data('action'),
				'ba_cheetah_as_action_data' : field.data('action-data'),
				'_wpnonce'			: BACheetahConfig.ajaxNonce
			}), $.extend({}, {
				asHtmlID                    : field.attr('name'),
				selectedItemProp            : 'name',
				searchObjProps              : 'name',
				minChars                    : 2,
				keyDelay                    : 1000,
				fadeOut                     : false,
				usePlaceholder              : true,
				emptyText                   : BACheetahStrings.noResultsFound,
				showResultListWhenNoMatch   : true,
				preFill                     : field.data('value'),
				queryParam                  : 'ba_cheetah_as_query',
				afterSelectionAdd           : BACheetah._updateAutoSuggestField,
				afterSelectionRemove        : BACheetah._updateAutoSuggestField,
				selectionLimit              : field.data('limit'),
				canGenerateNewSelections    : false
			}, field.data( 'args' )));

			BACheetahSettingsForms.hideFieldLoader( field );
		},

		/**
		 * Updates the value of an auto suggest field.
		 *

		 * @access private
		 * @method _initAutoSuggestField
		 * @param {Object} element The auto suggest field.
		 * @param {Object} item The current selection.
		 * @param {Array} selections An array of selected values.
		 */
		_updateAutoSuggestField: function(element, item, selections)
		{
			$(this).siblings('.as-values').val(selections.join(',')).trigger('change');
		},

		/* Code Fields
		----------------------------------------------------------*/

		/**
		 * SiteGround ForceSSL fix
		 */
		 _CodeFieldSSLCheck: function() {
			 $('body').append('<div class="sg-test" style="display:none"><svg xmlns="http://www.w3.org/2000/svg"></svg></div>');

			 if ( 'https://www.w3.org/2000/svg' === $('.sg-test').find('svg').attr('xmlns') ) {
				 BACheetah._codeDisabled = true;
			 }
			 $('.sg-test').remove()
		 },

		/**
		 * Initializes all code fields in a settings form.
		 *

		 * @access private
		 * @method _initCodeFields
		 */
		_initCodeFields: function()
		{
			if ( ! BACheetah._codeDisabled ) {
				$( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-code-field' ).each( BACheetah._initCodeField );
			}
		},

		/**
		 * Initializes a single code field in a settings form.
		 *

		 * @access private
		 * @method _initCodeField
		 */
		_initCodeField: function()
		{
			var field    = $( this ),
				settings = field.closest( '.ba-cheetah-settings' ),
				textarea = field.find( 'textarea' ),
				editorId = textarea.attr( 'id' ),
				mode     = textarea.data( 'editor' ),
				wrap     = textarea.data( 'wrap' ),
				editDiv  = $( '<div>', {
					position:   'absolute',
					height:     parseInt( textarea.attr( 'rows' ), 10 ) * 20
				} ),
				editor = null,
				global_layout = ( settings.hasClass('ba-cheetah-global-settings') || settings.hasClass('ba-cheetah-layout-settings' ) ) ? true : false;

			editDiv.insertBefore( textarea );
			textarea.css( 'display', 'none' );
			ace.require( 'ace/ext/language_tools' );
			editor = ace.edit( editDiv[0] );
			editor.$blockScrolling = Infinity;
			editor.getSession().setValue( textarea.val() );
			editor.getSession().setMode( 'ace/mode/' + mode );

			if ( wrap ) {
				editor.getSession().setUseWrapMode( true );
			}

			editor.setOptions( BACheetahConfig.AceEditorSettings );

			editor.getSession().on( 'change', function( e ) {
				textarea.val( editor.getSession().getValue() ).trigger( 'change' );
			} );

			/**
			 * Watch the editor for annotation changes and let the
			 * user know if there are any errors.
			 */
			editor.getSession().on( 'changeAnnotation', function() {
				var annot = editor.getSession().getAnnotations();
				var saveBtn = settings.find( '.ba-cheetah-settings-save' );
				var errorBtn = settings.find( '.ba-cheetah-settings-error' );
				var hasError = false;

				for ( var i = 0; i < annot.length; i++ ) {
					if ( annot[ i ].text.indexOf( 'DOCTYPE' ) > -1 ) {
						continue;
					}
					if ( annot[ i ].text.indexOf( 'Named entity expected' ) > -1 ) {
						continue;
					}
					if ( annot[ i ].text.indexOf( '@supports' ) > -1 ) {
						continue;
					}

					if ( 'error' === annot[ i ].type ) {
						hasError = true;
						break;
					}
				}

				val = editor.getSession().getValue();

				if( global_layout && hasError && null !== val.match( /<\/iframe>|<\/script>/gm ) ) {
					saveBtn.addClass( 'ba-cheetah-settings-error' );
					saveBtn.on( 'click', BACheetah._showCodeFieldCriticalError );
				}
				if ( hasError && ! saveBtn.hasClass( 'ba-cheetah-settings-error' ) && errorBtn.length && BACheetahConfig.CheckCodeErrors ) {
					saveBtn.addClass( 'ba-cheetah-settings-error' );
					saveBtn.on( 'click', BACheetah._showCodeFieldError );
				}
				if ( ! hasError ) {
					errorBtn.removeClass( 'ba-cheetah-settings-error' );
					errorBtn.off( 'click', BACheetah._showCodeFieldError );
					errorBtn.off( 'click', BACheetah._showCodeFieldCriticalError );
				}
			});
			textarea.closest( '.ba-cheetah-field' ).data( 'editor', editor );
		},

		/**
		 * Shows the code error alert when a code field
		 * has an error.
		 *

		 * @access private
		 * @method _showCodeFieldError
		 */
		_showCodeFieldError: function( e ) {
			e.stopImmediatePropagation();
			BACheetah.confirm( {
			    message: BACheetahStrings.codeError,
			    cancel: function(){
					var saveBtn = $( '.ba-cheetah-settings:visible .ba-cheetah-settings-save' );
					saveBtn.removeClass( 'ba-cheetah-settings-error' );
					saveBtn.off( 'click', BACheetah._showCodeFieldError );
					saveBtn.trigger( 'click' );
				},
			    strings: {
			        ok: BACheetahStrings.codeErrorFix,
			        cancel: BACheetahStrings.codeErrorIgnore
			    }
			} );
		},

		_showCodeFieldCriticalError: function( e ) {
			e.stopImmediatePropagation();
			BACheetah.alert( BACheetahStrings.codeerrorhtml );
		},

		/* Multiple Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all multiple fields in a settings form.
		 *

		 * @access private
		 * @method _initMultipleFields
		 */
		_initMultipleFields: function()

		{
			var multiples = $('.ba-cheetah-settings:visible .ba-cheetah-field-multiples'),
				multiple  = null,
				fields    = null,
				i         = 0,
				cursorAt  = BACheetahConfig.isRtl ? { left: 10 } : { right: 10 },
				limit     = multiples.attr( 'data-limit' ) || 0,
				count     = $('tbody.ba-cheetah-field-multiples').find('tr').length || 0

			if( parseInt(limit) > 0 && count -1 >= parseInt( limit ) ) {
				$('.ba-cheetah-field-copy').hide()
				$('.ba-cheetah-field-add').fadeOut()
			} else {
				$('.ba-cheetah-field-copy, .ba-cheetah-field-add').show()
			}

			for( ; i < multiples.length; i++) {

				multiple = multiples.eq(i);
				fields = multiple.find('.ba-cheetah-field-multiple');

				if(fields.length === 1) {
					fields.eq(0).find('.ba-cheetah-field-actions').addClass('ba-cheetah-field-actions-single');
				}
				else {
					fields.find('.ba-cheetah-field-actions').removeClass('ba-cheetah-field-actions-single');
				}
			}

			$('.ba-cheetah-field-multiples').sortable({
				items: '.ba-cheetah-field-multiple',
				cursor: 'move',
				cursorAt: cursorAt,
				distance: 5,
				opacity: 0.5,
				placeholder: 'ba-cheetah-field-dd-zone',
				stop: BACheetah._fieldDragStop,
				tolerance: 'pointer',
				axis: "y"
			});
		},

		/**
		 * Adds a new multiple field to the list when the add
		 * button is clicked.
		 *

		 * @access private
		 * @method _addFieldClicked
		 */
		_addFieldClicked: function()
		{
			var button      = $(this),
				fieldName   = button.attr('data-field'),
				fieldRow    = button.closest('tr').siblings('tr[data-field='+ fieldName +']').last(),
				clone       = fieldRow.clone(),
				form   		= clone.find( '.ba-cheetah-form-field' ),
				formType	= null,
				defaultVal  = null,
				index       = parseInt(fieldRow.find('label span.ba-cheetah-field-index').html(), 10) + 1;

			clone.find('th label span.ba-cheetah-field-index').html(index);
			clone.find('.ba-cheetah-form-field-preview-text').html('');
			clone.find('.ba-cheetah-form-field-before').remove();
			clone.find('.ba-cheetah-form-field-after').remove();
			clone.find('input, textarea, select').val('');
			fieldRow.after(clone);
			BACheetah._initMultipleFields();

			if ( form.length ) {
				formType = form.find( '.ba-cheetah-form-field-edit' ).data( 'type' );
				form.find( 'input' ).val( JSON.stringify( BACheetahSettingsConfig.defaults.forms[ formType ] ) );
			}
			else {
				form = button.closest('form.ba-cheetah-settings');
				formType = form.data( 'type' );

				if ( formType && form.hasClass( 'ba-cheetah-module-settings' ) ) {
					defaultVal = BACheetahSettingsConfig.defaults.modules[ formType ][ fieldName ][0];
					clone.find('input, textarea, select').val( defaultVal );
				}
			}
		},

		/**
		 * Copies a multiple field and adds it to the list when
		 * the copy button is clicked.
		 *

		 * @access private
		 * @method _copyFieldClicked
		 */
		_copyFieldClicked: function()
		{
			var button      = $(this),
				row         = button.closest('tr'),
				clone       = row.clone(),
				index       = parseInt(row.find('label span.ba-cheetah-field-index').html(), 10) + 1;

			clone.find('th label span.ba-cheetah-field-index').html(index);
			row.after(clone);
			BACheetah._renumberFields(row.parent());
			BACheetah._initMultipleFields();
			BACheetah.preview.delayPreview();
		},

		/**
		 * Deletes a multiple field from the list when the
		 * delete button is clicked.
		 *

		 * @access private
		 * @method _deleteFieldClicked
		 */
		_deleteFieldClicked: function()
		{
			var row     = $(this).closest('tr'),
				parent  = row.parent(),
				result  = confirm(BACheetahStrings.deleteFieldMessage);

			if(result) {
				row.remove();
				BACheetah._renumberFields(parent);
				BACheetah._initMultipleFields();
				BACheetah.preview.delayPreview();
			}
		},

		/**
		 * Renumbers the labels for a list of multiple fields.
		 *

		 * @access private
		 * @method _renumberFields
		 * @param {Object} table A table element with multiple fields.
		 */
		_renumberFields: function(table)
		{
			var rows = table.find('.ba-cheetah-field-multiple'),
				i    = 0;

			for( ; i < rows.length; i++) {
				rows.eq(i).find('th label span.ba-cheetah-field-index').html(i + 1);
			}
		},

		/**
		 * Returns an element for multiple field drag operations.
		 *

		 * @access private
		 * @method _fieldDragHelper
		 * @return {Object} The helper element.
		 */
		_fieldDragHelper: function()
		{
			return $('<div class="ba-cheetah-field-dd-helper"></div>');
		},

		/**
		 * Renumbers and triggers a preview when a multiple field
		 * has finished dragging.
		 *

		 * @access private
		 * @method _fieldDragStop
		 * @param {Object} e The event object.
		 * @param {Object} ui An object with additional info for the drag.
		 */
		_fieldDragStop: function(e, ui)
		{
			BACheetah._renumberFields(ui.item.parent());

			BACheetah.preview.delayPreview();
		},

		/* Select Fields
		----------------------------------------------------------*/

		/**
		 * Initializes select fields for a settings form.
		 *

		 * @access private
		 * @method _initSelectFields
		 */
		_initSelectFields: function()
		{
			var selects = $( '.ba-cheetah-settings:visible' ).find( 'select' );

			selects.on( 'change', BACheetah._settingsSelectChanged );
			selects.trigger( 'change' );
			selects.on( 'change', BACheetah._calculateSettingsTabsOverflow );

			// Button groups use the same options and toggling behavior as selects.
			var buttonGroups = $( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-button-group-field input[type=hidden]' );

			buttonGroups.on( 'change', BACheetah._settingsSelectChanged );
			buttonGroups.trigger( 'change' );
			buttonGroups.on( 'change', BACheetah._calculateSettingsTabsOverflow );
		},

		/**
		 * Callback for when a settings form select has been changed.
		 * If toggle data is present, other fields will be toggled
		 * when this select changes.
		 *

		 * @access private
		 * @method _settingsSelectChanged
		 */
		_settingsSelectChanged: function()
		{
			var select  = $(this),
				toggle  = select.attr('data-toggle'),
				hide    = select.attr('data-hide'),
				trigger = select.attr('data-trigger'),
				val     = select.val(),
				i       = 0,
				selectElem = select.attr( 'name' ),
				allowToggle = false;

			// TOGGLE sections, fields or tabs.
			if(typeof toggle !== 'undefined') {

				toggle = BACheetah._jsonParse(toggle);
				allowToggle = true;

				for(i in toggle) {
					if ( allowToggle ){
						BACheetah._settingsSelectToggle(toggle[i].fields, 'hide', '#ba-cheetah-field-');
						BACheetah._settingsSelectToggle(toggle[i].sections, 'hide', '#ba-cheetah-settings-section-');
						BACheetah._settingsSelectToggle(toggle[i].tabs, 'hide', 'a[href*=ba-cheetah-settings-tab-', ']');
					}
				}

				if(typeof toggle[val] !== 'undefined') {
					if ( allowToggle ){
						BACheetah._settingsSelectToggle(toggle[val].fields, 'show', '#ba-cheetah-field-');
						BACheetah._settingsSelectToggle(toggle[val].sections, 'show', '#ba-cheetah-settings-section-');
						BACheetah._settingsSelectToggle(toggle[val].tabs, 'show', 'a[href*=ba-cheetah-settings-tab-', ']');
					}
				}
			}

			// HIDE sections, fields or tabs.
			if(typeof hide !== 'undefined') {

				hide = BACheetah._jsonParse(hide);

				for(i in hide) {
					BACheetah._settingsSelectToggle(hide[i].fields, 'show', '#ba-cheetah-field-');
					BACheetah._settingsSelectToggle(hide[i].sections, 'show', '#ba-cheetah-settings-section-');
					BACheetah._settingsSelectToggle(hide[i].tabs, 'show', 'a[href*=ba-cheetah-settings-tab-', ']');
				}

				if(typeof hide[val] !== 'undefined') {
					BACheetah._settingsSelectToggle(hide[val].fields, 'hide', '#ba-cheetah-field-');
					BACheetah._settingsSelectToggle(hide[val].sections, 'hide', '#ba-cheetah-settings-section-');
					BACheetah._settingsSelectToggle(hide[val].tabs, 'hide', 'a[href*=ba-cheetah-settings-tab-', ']');
				}
			}

			// TRIGGER select inputs.
			if(typeof trigger !== 'undefined') {

				trigger = BACheetah._jsonParse(trigger);

				if(typeof trigger[val] !== 'undefined') {
					if(typeof trigger[val].fields !== 'undefined') {
						for(i = 0; i < trigger[val].fields.length; i++) {
							$('#ba-cheetah-field-' + trigger[val].fields[i]).find('select').trigger('change');
						}
					}
				}
			}
		},

		/**

		 * @access private
		 * @method _settingsSelectToggle
		 * @param {Array} inputArray
		 * @param {Function} func
		 * @param {String} prefix
		 * @param {String} suffix
		 */
		_settingsSelectToggle: function(inputArray, func, prefix, suffix)
		{
			var i = 0;

			suffix = 'undefined' == typeof suffix ? '' : suffix;

			if(typeof inputArray !== 'undefined') {

				for( ; i < inputArray.length; i++) {

					$('.ba-cheetah-settings:visible').find(prefix + inputArray[i] + suffix)[func]();

					// Resize code editor fields.
					$( prefix + inputArray[i] + suffix ).parent().find( '.ba-cheetah-field[data-type="code"]' ).each( function() {
						if ( ! BACheetah._codeDisabled ) {
							$( this ).data( 'editor' ).resize();
						}
					} );
				}
			}
		},

		/* Color Pickers
		----------------------------------------------------------*/

		/**
		 * Initializes color picker fields for a settings form.
		 *

		 * @access private
		 * @method _initColorPickers
		 */
		_initColorPickers: function()
		{

			var colorPresets 	   = BACheetahConfig.colorPresets ? BACheetahConfig.colorPresets : [];
			BACheetah.colorPicker  = new BACheetahColorPicker({
				mode: 'hsl',
				elements: '.ba-cheetah-color-picker .ba-cheetah-color-picker-value',
				presets: colorPresets,
				labels: {
					colorPresets 		: BACheetahStrings.colorPresets,
					colorPicker 		: BACheetahStrings.colorPicker,
					placeholder			: BACheetahStrings.placeholder,
					removePresetConfirm	: BACheetahStrings.removePresetConfirm,
					noneColorSelected	: BACheetahStrings.noneColorSelected,
					alreadySaved		: BACheetahStrings.alreadySaved,
					noPresets			: BACheetahStrings.noPresets,
					presetAdded			: BACheetahStrings.presetAdded,
				}
			});

			$( BACheetah.colorPicker ).on( 'presetRemoved presetAdded presetSorted', function( event, data ) {
				BACheetah.ajax({
					action: 'save_color_presets',
					presets: data.presets
				});
			});

		},

		/* Color Pickers
		----------------------------------------------------------*/

		/**
		 * Initializes gradient picker fields for a settings form.
		 *

		 * @access private
		 * @method _initGradientPickers
		 */
		_initGradientPickers: function()
		{
			$( '.ba-cheetah-settings:visible .ba-cheetah-gradient-picker' ).each( BACheetah._initGradientPicker );
		},

		/**
		 * Initializes a single gradient picker field.
		 *

		 * @access private
		 * @method _initGradientPicker
		 */
		_initGradientPicker: function()
		{
			var picker = $( this ),
				type = picker.find( '.ba-cheetah-gradient-picker-type-select' ),
				angle = picker.find( '.ba-cheetah-gradient-picker-angle-wrap' ),
				position = picker.find( '.ba-cheetah-gradient-picker-position' );

			type.on( 'change', function() {
				if ( 'linear' === $( this ).val() ) {
					angle.show();
					position.hide();
				} else {
					angle.hide();
					position.show();
				}
			} );
		},

		/* Single Photo Fields
		----------------------------------------------------------*/

		/**
		 * Initializes photo fields for a settings form.
		 *

		 * @access private
		 * @method _initPhotoFields
		 */
		_initPhotoFields: function()
		{
			var selects = $( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-photo-field select' );

			selects.on( 'change', BACheetah._toggleSettingsOnIconChange );
			selects.trigger( 'change' );
		},

		/**
		 * Initializes the single photo selector.
		 *

		 * @access private
		 * @method _initSinglePhotoSelector
		 */
		_initSinglePhotoSelector: function()
		{
			if(BACheetah._singlePhotoSelector === null) {
				BACheetah._singlePhotoSelector = wp.media({
					title: BACheetahStrings.selectPhoto,
					button: { text: BACheetahStrings.selectPhoto },
					library : { type : BACheetahConfig.uploadTypes.image },
					multiple: false
				});
				BACheetah._singlePhotoSelector.on( 'open', BACheetah._wpmedia_reset_errors );
				_wpPluploadSettings['defaults']['multipart_params']['ba_cheetah_upload_type']= 'photo';
			}
		},

		/**
		 * Shows the single photo selector.
		 *

		 * @access private
		 * @method _selectSinglePhoto
		 */
		_selectSinglePhoto: function()
		{
			BACheetah._initSinglePhotoSelector();
			BACheetah._singlePhotoSelector.once('open', $.proxy(BACheetah._singlePhotoOpened, this));
			BACheetah._singlePhotoSelector.once('select', $.proxy(BACheetah._singlePhotoSelected, this));
			BACheetah._singlePhotoSelector.open();
		},

		/**
		 * Callback for when the single photo selector is shown.
		 *

		 * @access private
		 * @method _singlePhotoOpened
		 */
		_singlePhotoOpened: function()
		{
			var selection   = BACheetah._singlePhotoSelector.state().get('selection'),
				wrap        = $(this).closest('.ba-cheetah-photo-field'),
				photoField  = wrap.find('input[type=hidden]'),
				photo       = photoField.val(),
				attachment  = null;

			if($(this).hasClass('ba-cheetah-photo-replace')) {
				selection.reset();
				wrap.addClass('ba-cheetah-photo-empty');
				photoField.val('');
			}
			else if(photo !== '') {
				attachment = wp.media.attachment(photo);
				attachment.fetch();
				selection.add(attachment ? [attachment] : []);
			}
			else {
				selection.reset();
			}
		},

		/**
		 * Callback for when a single photo is selected.
		 *

		 * @access private
		 * @method _singlePhotoSelected
		 */
		_singlePhotoSelected: function()
		{
			var photo      = BACheetah._singlePhotoSelector.state().get('selection').first().toJSON(),
				wrap       = $(this).closest('.ba-cheetah-photo-field'),
				photoField = wrap.find('input[type=hidden]'),
				preview    = wrap.find('.ba-cheetah-photo-preview img'),
				srcSelect  = wrap.find('select');

			photoField.val(photo.id);
			preview.attr('src', BACheetah._getPhotoSrc(photo));
			wrap.removeClass('ba-cheetah-photo-empty').removeClass('ba-cheetah-photo-no-attachment');
			wrap.find('label.error').remove();
			srcSelect.show();
			srcSelect.html(BACheetah._getPhotoSizeOptions(photo));
			srcSelect.trigger('change');
			BACheetahSettingsConfig.attachments[ photo.id ] = photo;
		},

		/**
		 * Clears a photo that has been selected in a single photo field.
		 *

		 * @access private
		 * @method _singlePhotoRemoved
		 */
		_singlePhotoRemoved: function()
		{
			BACheetah._initSinglePhotoSelector();

			var state       = BACheetah._singlePhotoSelector.state(),
				selection   = 'undefined' != typeof state ? state.get('selection') : null,
				wrap        = $(this).closest('.ba-cheetah-photo-field'),
				photoField  = wrap.find('input[type=hidden]'),
				srcSelect   = wrap.find('select');

			if ( selection ) {
				selection.reset();
			}

			wrap.addClass('ba-cheetah-photo-empty');
			photoField.val('');
			srcSelect.html('<option value="" selected></option>');
			srcSelect.trigger('change');
		},

		/**
		 * Returns the src URL for a photo.
		 *

		 * @access private
		 * @method _getPhotoSrc
		 * @param {Object} photo A photo data object.
		 * @return {String} The src URL for a photo.
		 */
		_getPhotoSrc: function(photo)
		{
			if(typeof photo.sizes === 'undefined') {
				return photo.url;
			}
			else if(typeof photo.sizes.thumbnail !== 'undefined') {
				return photo.sizes.thumbnail.url;
			}
			else {
				return photo.sizes.full.url;
			}
		},

		/**
		 * Builds the options for a photo size select.
		 *

		 * @access private
		 * @method _getPhotoSizeOptions
		 * @param {Object} photo A photo data object.
		 * @param {String} selectedSize The selected photo size if one is set.
		 * @return {String} The HTML for the photo size options.
		 */
		_getPhotoSizeOptions: function( photo, selectedSize )
		{
			var html     = '',
				size     = null,
				selected = null,
				check    = null,
				title    = '',
				titles = {
					full      : BACheetahStrings.fullSize,
					large     : BACheetahStrings.large,
					medium    : BACheetahStrings.medium,
					thumbnail : BACheetahStrings.thumbnail
				};

			if(typeof photo.sizes === 'undefined' || 0 === photo.sizes.length) {
				html += '<option value="' + photo.url + '">' + BACheetahStrings.fullSize + '</option>';
			}
			else {

				// Check the selected value without the protocol so we get a match if
				// a site has switched to HTTPS since selecting this photo (#641).
				if ( selectedSize ) {
					selectedSize = selectedSize.split(/[\\/]/).pop();
				}

				for(size in photo.sizes) {

					if ( 'undefined' != typeof titles[ size ] ) {
						title = titles[ size ] + ' - ';
					}
					else if ( 'undefined' != typeof BACheetahConfig.customImageSizeTitles[ size ] ) {
						title = BACheetahConfig.customImageSizeTitles[ size ] + ' - ';
					}
					else {
						title = '';
					}

					selected = '';

					if ( ! selectedSize ) {
						selected = size == 'full' ? ' selected="selected"' : '';
					} else if( selectedSize === photo.sizes[ size ].url.split(/[\\/]/).pop() ) {
						selected = ' selected="selected"';
					}

					html += '<option value="' + photo.sizes[size].url + '"' + selected + '>' + title + photo.sizes[size].width + ' x ' + photo.sizes[size].height + '</option>';
				}
			}

			return html;
		},

		/* Multiple Photo Fields
		----------------------------------------------------------*/

		/**
		 * Shows the multiple photo selector.
		 *

		 * @access private
		 * @method _selectMultiplePhotos
		 */
		_selectMultiplePhotos: function()
		{
			var wrap           = $(this).closest('.ba-cheetah-multiple-photos-field'),
				photosField    = wrap.find('input[type=hidden]'),
				photosFieldVal = photosField.val(),
				parsedVal      = photosFieldVal === '' ? '' : BACheetah._jsonParse(photosFieldVal),
				defaultPostId  = wp.media.gallery.defaults.id,
				content        = '[gallery ids="-1"]',
				shortcode      = null,
				attachments    = null,
				selection      = null,
				i              = null,
				ids            = [];

			// Builder the gallery shortcode.
			if ( 'object' == typeof parsedVal ) {
				for ( i in parsedVal ) {
					ids.push( parsedVal[ i ] );
				}
				content = '[gallery ids="'+ ids.join() +'"]';
			}

			shortcode = wp.shortcode.next('gallery', content).shortcode;

			if(_.isUndefined(shortcode.get('id')) && !_.isUndefined(defaultPostId)) {
				shortcode.set('id', defaultPostId);
			}

			// Get the selection object.
			attachments = wp.media.gallery.attachments(shortcode);

			selection = new wp.media.model.Selection(attachments.models, {
				props: attachments.props.toJSON(),
				multiple: true
			});

			selection.gallery = attachments.gallery;

			// Fetch the query's attachments, and then break ties from the
			// query to allow for sorting.
			selection.more().done(function() {

				if ( ! selection.length ) {
					BACheetah._multiplePhotoSelector.setState( 'gallery-library' );
				}

				// Break ties with the query.
				selection.props.set({ query: false });
				selection.unmirror();
				selection.props.unset('orderby');
			});

			// Destroy the previous gallery frame.
			if(BACheetah._multiplePhotoSelector) {
				BACheetah._multiplePhotoSelector.dispose();
			}

			// Store the current gallery frame.
			BACheetah._multiplePhotoSelector = wp.media({
				frame:     'post',
				state:     $(this).hasClass('ba-cheetah-multiple-photos-edit') ? 'gallery-edit' : 'gallery-library',
				title:     wp.media.view.l10n.editGalleryTitle,
				editing:   true,
				multiple:  true,
				selection: selection
			}).open();

			$(BACheetah._multiplePhotoSelector.views.view.el).addClass('ba-cheetah-multiple-photos-lightbox');
			BACheetah._multiplePhotoSelector.once('update', $.proxy(BACheetah._multiplePhotosSelected, this));
		},

		/**
		 * Callback for when multiple photos have been selected.
		 *

		 * @access private
		 * @method _multiplePhotosSelected
		 * @param {Object} data The photo data object.
		 */
		_multiplePhotosSelected: function(data)
		{
			var wrap        = $(this).closest('.ba-cheetah-multiple-photos-field'),
				photosField = wrap.find('input[type=hidden]'),
				count       = wrap.find('.ba-cheetah-multiple-photos-count'),
				photos      = [],
				i           = 0;

			for( ; i < data.models.length; i++) {
				photos.push(data.models[i].id);
			}

			if(photos.length == 1) {
				count.html('1 ' + BACheetahStrings.photoSelected);
			}
			else {
				count.html(photos.length + ' ' + BACheetahStrings.photosSelected);
			}

			wrap.removeClass('ba-cheetah-multiple-photos-empty');
			wrap.find('label.error').remove();
			photosField.val(JSON.stringify(photos)).trigger('change');
		},

		/* Single Video Fields
		----------------------------------------------------------*/

		/**
		 * Initializes the single video selector.
		 *

		 * @access private
		 * @method _initSingleVideoSelector
		 */
		_initSingleVideoSelector: function()
		{
			if(BACheetah._singleVideoSelector === null) {

				BACheetah._singleVideoSelector = wp.media({
					title: BACheetahStrings.selectVideo,
					button: { text: BACheetahStrings.selectVideo },
					library : { type : BACheetahConfig.uploadTypes.video },
					multiple: false
				});

				BACheetah._singleVideoSelector.on( 'open', BACheetah._wpmedia_reset_errors );
				_wpPluploadSettings['defaults']['multipart_params']['ba_cheetah_upload_type']= 'video';
			}
		},

		/**
		 * Shows the single video selector.
		 *

		 * @access private
		 * @method _selectSingleVideo
		 */
		_selectSingleVideo: function()
		{
			BACheetah._initSingleVideoSelector();
			BACheetah._singleVideoSelector.once('select', $.proxy(BACheetah._singleVideoSelected, this));
			BACheetah._singleVideoSelector.open();
		},

		/**
		 * Callback for when a single video is selected.
		 *

		 * @access private
		 * @method _singleVideoSelected
		 */
		_singleVideoSelected: function()
		{
			var video      = BACheetah._singleVideoSelector.state().get('selection').first().toJSON(),
				wrap       = $(this).closest('.ba-cheetah-video-field'),
				image      = wrap.find('.ba-cheetah-video-preview-img'),
				filename   = wrap.find('.ba-cheetah-video-preview-filename'),
				videoField = wrap.find('input[type=hidden]');

			image.html('<i class="fas fa-file-video"></i>');
			filename.html(video.filename);
			wrap.removeClass('ba-cheetah-video-empty');
			wrap.find('label.error').remove();
			videoField.val(video.id).trigger('change');
			BACheetahSettingsConfig.attachments[ video.id ] = video;
		},

		/**
		 * Clears a video that has been selected in a single video field.
		 *

		 * @access private
		 * @method _singleVideoRemoved
		 */
		_singleVideoRemoved: function()
		{
			BACheetah._initSingleVideoSelector();
			var state       = BACheetah._singleVideoSelector.state(),
				selection   = 'undefined' != typeof state ? state.get('selection') : null,
				wrap        = $(this).closest('.ba-cheetah-video-field'),
				image      	= wrap.find('.ba-cheetah-video-preview-img img'),
				filename   	= wrap.find('.ba-cheetah-video-preview-filename'),
				videoField  = wrap.find('input[type=hidden]');

			if ( selection ) {
				selection.reset();
			}

			image.attr('src', '');
			filename.html('');
			wrap.addClass('ba-cheetah-video-empty');
			videoField.val('').trigger('change');
		},

		/* Multiple Audios Field
		----------------------------------------------------------*/

		/**
		 * Shows the multiple audio selector.
		 *

		 * @access private
		 * @method _selectMultipleAudios
		 */
		_selectMultipleAudios: function()
		{
			var wrap           = $(this).closest('.ba-cheetah-multiple-audios-field'),
				audiosField    = wrap.find('input[type=hidden]'),
				audiosFieldVal = audiosField.val(),
				content        = audiosFieldVal == '' ? '[playlist ids="-1"]' : '[playlist ids="'+ BACheetah._jsonParse(audiosFieldVal).join() +'"]',
				shortcode      = wp.shortcode.next('playlist', content).shortcode,
				defaultPostId  = wp.media.playlist.defaults.id,
				attachments    = null,
				selection      = null;

			if(_.isUndefined(shortcode.get('id')) && !_.isUndefined(defaultPostId)) {
				shortcode.set('id', defaultPostId);
			}

			attachments = wp.media.playlist.attachments(shortcode);

			selection = new wp.media.model.Selection(attachments.models, {
				props: attachments.props.toJSON(),
				multiple: true
			});

			selection.playlist = attachments.playlist;

			// Fetch the query's attachments, and then break ties from the
			// query to allow for sorting.
			selection.more().done(function() {
				// Break ties with the query.
				selection.props.set({ query: false });
				selection.unmirror();
				selection.props.unset('orderby');
			});

			// Destroy the previous frame.
			if(BACheetah._multipleAudiosSelector) {
				BACheetah._multipleAudiosSelector.dispose();
			}

			// Store the current frame.
			BACheetah._multipleAudiosSelector = wp.media({
				frame:     'post',
				state:     $(this).hasClass('ba-cheetah-multiple-audios-edit') ? 'playlist-edit' : 'playlist-library',
				title:     wp.media.view.l10n.editPlaylistTitle,
				editing:   true,
				multiple:  true,
				selection: selection
			}).open();

			// Hide the default playlist settings since we have them added in the audio settings
			BACheetah._multipleAudiosSelector.content.get('view').sidebar.unset('playlist');
			BACheetah._multipleAudiosSelector.on( 'content:render:browse', function( browser ) {
			    if ( !browser ) return;
			    // Hide Playlist Settings in sidebar
			    browser.sidebar.on('ready', function(){
			        browser.sidebar.unset('playlist');
			    });
			});


			BACheetah._multipleAudiosSelector.once('update', $.proxy(BACheetah._multipleAudiosSelected, this));

		},

		/**
		 * Callback for when a single/multiple audo is selected.
		 *

		 * @access private
		 * @method _multipleAudiosSelected
		 */
		_multipleAudiosSelected: function(data)
		{
			var wrap       		= $(this).closest('.ba-cheetah-multiple-audios-field'),
				count      		= wrap.find('.ba-cheetah-multiple-audios-count'),
				audioField 		= wrap.find('input[type=hidden]'),
				audios     		= [],
				i          		= 0;

			for( ; i < data.models.length; i++) {
				audios.push(data.models[i].id);
			}

			if(audios.length == 1) {
				count.html('1 ' + BACheetahStrings.audioSelected);
			}
			else {
				count.html(audios.length + ' ' + BACheetahStrings.audiosSelected);
			}

			audioField.val(JSON.stringify(audios)).trigger('change');
			wrap.removeClass('ba-cheetah-multiple-audios-empty');
			wrap.find('label.error').remove();

		},

		/* Icon Fields
		----------------------------------------------------------*/

		/**
		 * Initializes icon fields for a settings form.
		 *

		 * @access private
		 * @method _initIconFields
		 */
		_initIconFields: function()
		{
			var inputs = $( '.ba-cheetah-settings:visible' ).find( '.ba-cheetah-icon-field input' );

			inputs.on( 'change', BACheetah._toggleSettingsOnIconChange );
			inputs.trigger( 'change' );
		},

		/**
		 * Callback for when an icon field changes. If the field
		 * isn't empty the specified elements (if any) will be shown.
		 *

		 * @access private
		 * @method _toggleSettingsOnIconChange
		 */
		_toggleSettingsOnIconChange: function()
		{
			var input  = $( this ),
				val    = input.val(),
				show   = input.attr( 'data-show' ),
				i      = 0;

			if ( typeof show === 'undefined' ) {
				return;
			}

			show = BACheetah._jsonParse( show );

			BACheetah._settingsSelectToggle( show.fields, 'hide', '#ba-cheetah-field-' );
			BACheetah._settingsSelectToggle( show.sections, 'hide', '#ba-cheetah-settings-section-' );
			BACheetah._settingsSelectToggle( show.tabs, 'hide', 'a[href*=ba-cheetah-settings-tab-', ']' );

			if ( val ) {
				BACheetah._settingsSelectToggle( show.fields, 'show', '#ba-cheetah-field-' );
				BACheetah._settingsSelectToggle( show.sections, 'show', '#ba-cheetah-settings-section-' );
				BACheetah._settingsSelectToggle( show.tabs, 'show', 'a[href*=ba-cheetah-settings-tab-', ']' );
				BACheetah._calculateSettingsTabsOverflow();
			}
		},

		/**
		 * Shows the icon selector.
		 *

		 * @access private
		 * @method _selectIcon
		 */
		_selectIcon: function()
		{
			var self = this;

			BACheetahIconSelector.open(function(icon){
				BACheetah._iconSelected.apply(self, [icon]);
			});
		},

		/**
		 * Callback for when an icon is selected.
		 *

		 * @access private
		 * @method _iconSelected
		 * @param {String} icon The selected icon's CSS classname.
		 */
		_iconSelected: function(icon)
		{
			var wrap       = $(this).closest('.ba-cheetah-icon-field'),
				iconField  = wrap.find('input[type=hidden]'),
				iconTag    = wrap.find('i'),
				oldIcon    = iconTag.attr('data-icon');

			iconField.val(icon).trigger('change');
			iconTag.removeClass(oldIcon);
			iconTag.addClass(icon);
			iconTag.attr('data-icon', icon);
			wrap.removeClass('ba-cheetah-icon-empty');
			wrap.find('label.error').remove();
		},

		/**
		 * Callback for when a selected icon is removed.
		 *

		 * @access private
		 * @method _removeIcon
		 */
		_removeIcon: function()
		{
			var wrap       = $(this).closest('.ba-cheetah-icon-field'),
				iconField  = wrap.find('input[type=hidden]'),
				iconTag    = wrap.find('i');

			iconField.val('').trigger('change');
			iconTag.removeClass();
			iconTag.attr('data-icon', '');
			wrap.addClass('ba-cheetah-icon-empty');
		},

		/* Settings Form Fields
		----------------------------------------------------------*/

		/**
		 * Shows the settings for a nested form field when the
		 * edit link is clicked.
		 *

		 * @access private
		 * @method _formFieldClicked
		 */
		_formFieldClicked: function()
		{
			var link      = $( this ),
				form      = link.closest( '.ba-cheetah-settings' ),
				type      = link.attr( 'data-type' ),
				settings  = link.siblings( 'input' ).val(),
				helper    = BACheetah._moduleHelpers[ type ],
				config    = BACheetahSettingsConfig.forms[ type ],
				lightbox  = BACheetah._openNestedSettings( { className: 'ba-cheetah-lightbox ba-cheetah-form-field-settings' } );

			if ( '' === settings ) {
				settings = JSON.stringify( BACheetahSettingsConfig.forms[ type ] );
			}

			BACheetahSettingsForms.render( {
				id        		: type,
				nodeId    		: form.attr( 'data-node' ),
				nodeSettings	: BACheetah._getSettings( form ),
				settings  		: BACheetah._jsonParse( settings.replace( /&#39;/g, "'" ) ),
				lightbox		: lightbox,
				helper			: helper,
				rules 			: helper ? helper.rules : null
			}, function() {
				link.attr( 'id', 'ba-cheetah-' + lightbox._node.attr( 'data-instance-id' ) );
				lightbox._node.find( 'form.ba-cheetah-settings' ).attr( 'data-type', type );
				BACheetahResponsiveEditing._switchAllSettingsToCurrentMode();
			} );
		},

		/**
		 * Saves the settings for a nested form field when the
		 * save button is clicked.
		 *

		 * @access private
		 * @method _saveFormFieldClicked
		 * @return {Boolean} Whether the save was successful or not.
		 */
		_saveFormFieldClicked: function()
		{
			var form          = $(this).closest('.ba-cheetah-settings'),
				lightboxId    = $(this).closest('.ba-cheetah-lightbox-wrap').attr('data-instance-id'),
				type          = form.attr('data-type'),
				settings      = BACheetah._getSettings(form),
				oldSettings   = {},
				helper        = BACheetah._moduleHelpers[type],
				link          = $('.ba-cheetah-settings #ba-cheetah-' + lightboxId),
				preview       = link.parent().attr('data-preview-text'),
				previewField  = form.find( '#ba-cheetah-field-' + preview ),
				previewText   = settings[preview],
				selectPreview = $( 'select[name="' + preview + '"]' ),
				tmp           = document.createElement('div'),
				valid         = true;

			if ( selectPreview.length > 0 ) {
				previewText = selectPreview.find( 'option[value="' + settings[ preview ] + '"]' ).text();
			}
			if(typeof helper !== 'undefined') {

				form.find('label.error').remove();
				form.validate().hideErrors();
				valid = form.validate().form();

				if(valid) {
					valid = helper.submit();
				}
			}
			if(valid) {

				if(typeof preview !== 'undefined' && typeof previewText !== 'undefined') {

					if('icon' === previewField.data('type')) {
						previewText = '<i class="' + previewText + '"></i>';
					}
					else if(previewText.length > 35) {
						tmp.innerHTML = previewText;
						previewText = (tmp.textContent || tmp.innerText || '').replace(/^(.{35}[^\s]*).*/, "$1")  + '...';
					}

					link.siblings('.ba-cheetah-form-field-preview-text').html(previewText);
				}

				oldSettings = link.siblings('input').val().replace(/&#39;/g, "'");

				if ( '' != oldSettings ) {
					settings = $.extend( BACheetah._jsonParse( oldSettings ), settings );
				}

				link.siblings('input').val(JSON.stringify(settings)).trigger('change');

				BACheetah._closeNestedSettings();

				return true;
			}
			else {
				BACheetah._toggleSettingsTabErrors();
				return false;
			}
		},

		/* Layout Fields
		----------------------------------------------------------*/

		/**
		 * Callback for when the item of a layout field is clicked.
		 *

		 * @access private
		 * @method _layoutFieldClicked
		 */
		_layoutFieldClicked: function()
		{
			var option = $(this);

			option.siblings().removeClass('ba-cheetah-layout-field-option-selected');
			option.addClass('ba-cheetah-layout-field-option-selected');
			option.siblings('input').val(option.attr('data-value'));
		},

		/* Link Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all link fields in a settings form.
		 *

		 * @access private
		 * @method _initLinkFields
		 */
		_initLinkFields: function()
		{
			$('.ba-cheetah-settings:visible .ba-cheetah-link-field').each(BACheetah._initLinkField);
		},

		/**
		 * Initializes a single link field in a settings form.
		 *

		 * @access private
		 * @method _initLinkFields
		 */
		_initLinkField: function()
		{
			var wrap        = $(this),
				searchInput = wrap.find('.ba-cheetah-link-field-search-input'),
				checkboxes	= wrap.find( '.ba-cheetah-link-field-options-wrap input[type=checkbox]' );

			searchInput.autoSuggest(BACheetah._ajaxUrl({
				'ba_cheetah_action'         : 'ba_cheetah_autosuggest',
				'ba_cheetah_as_action'      : 'ba_cheetah_as_links',
				'_wpnonce'			: BACheetahConfig.ajaxNonce
			}), {
				asHtmlID                    : searchInput.attr('name'),
				selectedItemProp            : 'name',
				searchObjProps              : 'name',
				minChars                    : 3,
				keyDelay                    : 1000,
				fadeOut                     : false,
				usePlaceholder              : true,
				emptyText                   : BACheetahStrings.noResultsFound,
				showResultListWhenNoMatch   : true,
				queryParam                  : 'ba_cheetah_as_query',
				selectionLimit              : 1,
				afterSelectionAdd           : BACheetah._updateLinkField,
				formatList: function(data, elem){
					var new_elem = elem.html(data.name + '<span class="type">[' + data.type + ']</span>');
					return new_elem;
				}
			});

			checkboxes.on( 'click', BACheetah._linkFieldCheckboxClicked );
		},

		/**
		 * Updates the value of a link field when a link has been
		 * selected from the auto suggest menu.
		 *

		 * @access private
		 * @method _updateLinkField
		 * @param {Object} element The auto suggest field.
		 * @param {Object} item The current selection.
		 * @param {Array} selections An array of selected values.
		 */
		_updateLinkField: function(element, item, selections)
		{
			var wrap        = element.closest('.ba-cheetah-link-field'),
				search      = wrap.find('.ba-cheetah-link-field-search'),
				searchInput = wrap.find('.ba-cheetah-link-field-search-input'),
				field       = wrap.find('.ba-cheetah-link-field-input');

			field.val(item.value).trigger('keyup');
			searchInput.autoSuggest('remove', item.value);
			search.hide();
		},

		/**
		 * Shows the auto suggest input for a link field.
		 *

		 * @access private
		 * @method _linkFieldSelectClicked
		 */
		_linkFieldSelectClicked: function()
		{
			var $el = $(this).closest('.ba-cheetah-link-field').find('.ba-cheetah-link-field-search');
			$el.show();
			$el.find('input').focus();
		},

		/**
		 * Hides the auto suggest input for a link field.
		 *

		 * @access private
		 * @method _linkFieldSelectCancelClicked
		 */
		_linkFieldSelectCancelClicked: function()
		{
			var $button = $(this);
			$button.parent().hide();
			$button.closest('.ba-cheetah-link-field').find('input.ba-cheetah-link-field-input').focus();
		},

		/**
		 * Handles when a link field checkbox option is clicked.
		 *

		 * @access private
		 * @method _linkFieldCheckboxClicked
		 */
		_linkFieldCheckboxClicked: function()
		{
			var checkbox = $( this ),
				checked = checkbox.is( ':checked' ),
				input = checkbox.siblings( 'input[type=hidden]' ),
				value = '';

			if ( checkbox.hasClass( 'ba-cheetah-link-field-target-cb' ) ) {
				value = checked ? '_blank' : '_self';
			} else {
				value = checked ? 'yes' : 'no';
			}

			input.val( value );
		},

		/* Font Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all font fields in a settings form.
		 *

		 * @access private
		 * @method _initFontFields
		 */
		_initFontFields: function(){
			$('.ba-cheetah-settings:visible .ba-cheetah-font-field').each( BACheetah._initFontField );
		},

		/**
		 * Initializes a single font field in a settings form.
		 *

		 * @access private
		 * @method _initFontFields
		 */
		_initFontField: function(){
			var wrap   = $( this ),
				value  = wrap.attr( 'data-value' ),
				font   = wrap.find( '.ba-cheetah-font-field-font' ),
				weight = wrap.find( '.ba-cheetah-font-field-weight' );

			if ( BACheetahConfig.select2Enabled ) {
				font.select2({width:'100%'})
					.on('select2:open', function(e){
						$('.select2-search__field').attr('placeholder', BACheetahStrings.placeholderSelect2);
						document.querySelector('.select2-search__field').focus();
					})
			}

			font.on( 'change', function(){
				BACheetah._getFontWeights( font );
			} );

			if ( value.indexOf( 'family' ) > -1 ) {

				value = BACheetah._jsonParse( value );
				font.val( value.family );
				font.trigger( 'change' );

				if ( weight.find( 'option[value=' + value.weight + ']' ).length ) {
					weight.val( value.weight );
				}
			}
		},

		/**
		 * Renders the correct weights list for a respective font.
		 *

		 * @acces  private
		 * @method _getFontWeights
		 * @param  {Object} currentFont The font field element.
		 */
		_getFontWeights: function( currentFont ){
			var selectWeight = currentFont.closest( '.ba-cheetah-font-field' ).find( '.ba-cheetah-font-field-weight' ),
				font         = currentFont.val(),
				weight 	 	   = selectWeight.val(),
				weightMap    = BACheetahConfig.FontWeights,
				weights      = {},
				recentList   = currentFont.closest( '.ba-cheetah-font-field' ).find( '.recent-fonts option' )

			selectWeight.html( '' );

			if( recentList.length > 0 ) {
				var exists = $(recentList)
					.filter(function (i, o) { return o.value === font; })
					.length > 0;

				if ( false === exists ) {
						currentFont.closest( '.ba-cheetah-font-field' ).find( '.recent-fonts' ).append( $('<option>', {
							value: font,
							text: font
						}));
				}
			}


			if ( 'undefined' != typeof BACheetahFontFamilies.system[ font ] ) {
				weights = BACheetahFontFamilies.system[ font ].weights;
			} else if ( 'undefined' != typeof BACheetahFontFamilies.google[ font ] ) {
				weights = BACheetahFontFamilies.google[ font ];
			} else {
				weights = BACheetahFontFamilies.default[ font ];
			}

			$.each( weights, function( key, value ){
				var selected = weight === value ? ' selected' : '';
				selectWeight.append( '<option value="' + value + '"' + selected + '>' + weightMap[ value ] + '</option>' );
			} );
		},

		/* Editor Fields
		----------------------------------------------------------*/

		/**
		 * InitializeS TinyMCE when the builder is first loaded.
		 *

		 * @access private
		 * @method _initEditorFields
		 */
		_initTinyMCE: function()
		{
			if ( typeof tinymce === 'object' && typeof tinymce.ui.FloatPanel !== 'undefined' ) {
				tinymce.ui.FloatPanel.zIndex = 100100; // Fix zIndex issue in wp 4.8.1
			}

			$( '.ba-cheetah-hidden-editor' ).each( BACheetah._initEditorField );
		},

		/**
		 * Initialize all TinyMCE editor fields.
		 *

		 * @access private
		 * @method _initEditorFields
		 */
		_initEditorFields: function()
		{
			$( '.ba-cheetah-settings:visible .ba-cheetah-editor-field' ).each( BACheetah._initEditorField );
		},

		/**
		 * Initialize a single TinyMCE editor field.
		 *

		 * @method _initEditorField
		 */
		_initEditorField: function()
		{
			var field	 = $( this ),
				textarea = field.find( 'textarea' ),
				name 	 = field.attr( 'data-name' ),
				editorId = 'flrich' + new Date().getTime() + '_' + name,
				html 	 = BACheetahConfig.wp_editor,
				config	 = tinyMCEPreInit,
				buttons  = Number( field.attr( 'data-buttons' ) ),
				rows  	 = field.attr( 'data-rows' ),
				init     = null,
				wrap     = null;

			html = html.replace( /bacheetaheditor/g , editorId );
			config = BACheetah._jsonParse( JSON.stringify( config ).replace( /bacheetaheditor/g , editorId ) );

			config = JSONfn.parse( JSONfn.stringify( config ).replace( /bacheetaheditor/g , editorId ) );

			textarea.after( html ).remove();
			$( 'textarea#' + editorId ).val( textarea.val() )

			if ( undefined !== typeof tinymce && undefined !== config.mceInit[ editorId ] ) {

				init = config.mceInit[ editorId ];

				init.setup = function (editor) {
					editor.on('SaveContent', function (e) {
						e.content = e.content.replace(/<a href="(\.\.\/){1,2}/g, '<a href="' + BACheetahConfig.homeUrl + '/' );
						e.content = e.content.replace(/src="(\.\.\/){1,2}/g, 'src="' + BACheetahConfig.homeUrl + '/' );
					});
				}

				wrap = tinymce.$( '#wp-' + editorId + '-wrap' );
				wrap.find( 'textarea' ).attr( 'rows', rows );

				if ( ! buttons ) {
					wrap.find( '.wp-media-buttons' ).remove();
				}

				if ( ( wrap.hasClass( 'tmce-active' ) || ! config.qtInit.hasOwnProperty( editorId ) ) && ! init.wp_skip_init ) {
					tinymce.init( init );
				}
			}

			if ( undefined !== typeof quicktags ) {
				quicktags( config.qtInit[ editorId ] );
			}

			window.wpActiveEditor = editorId;
		},

		/**
		 * Reinitialize all TinyMCE editor fields.
		 *

		 * @access private
		 * @method _reinitEditorFields
		 */
		_reinitEditorFields: function()
		{
			if ( ! $( '.ba-cheetah-lightbox-resizable:visible' ).length ) {
				return;
			}

			// Do this on a timeout so TinyMCE doesn't hold up other operations.
			setTimeout( function() {

				var i, id;

				if ( 'undefined' === typeof tinymce ) {
					return;
				}

				for ( i = tinymce.editors.length - 1; i > -1 ; i-- ) {
					if ( ! tinymce.editors[ i ].inline ) {
						id = tinymce.editors[ i ].id;
						tinyMCE.execCommand( 'mceRemoveEditor', true, id );
						tinyMCE.execCommand( 'mceAddEditor', true, id );
					}
				}

				if ( BACheetah.preview ) {
					BACheetah.preview._initDefaultFieldPreviews( $( '.ba-cheetah-field[data-type="editor"]' ) );
				}

			}, 1 );
		},

		/**
		 * Destroy all TinyMCE editors.
		 *

		 * @method _destroyEditorFields
		 */
		_destroyEditorFields: function()
		{
			var i, id;

			if ( 'undefined' === typeof tinymce ) {
				return;
			}

			for ( i = tinymce.editors.length - 1; i > -1 ; i-- ) {
				if ( ! tinymce.editors[ i ].inline ) {
					tinyMCE.execCommand( 'mceRemoveEditor', true, tinymce.editors[ i ].id );
				}
			}

			$( '.wplink-autocomplete' ).remove();
			$( '.ui-helper-hidden-accessible' ).remove();
		},

		/**
		 * Updates all editor fields within a settings form.
		 *

		 * @access private
		 * @method _updateEditorFields
		 */
		_updateEditorFields: function()
		{
			var wpEditors = $('.ba-cheetah-settings:visible textarea.wp-editor-area');

			wpEditors.each(BACheetah._updateEditorField);
		},

		/**
		 * Updates a single editor field within a settings form.
		 * Creates a hidden textarea with the editor content so
		 * this field can be saved.
		 *

		 * @access private
		 * @method _updateEditorField
		 */
		_updateEditorField: function()
		{
			var textarea  = $( this ),
				field     = textarea.closest( '.ba-cheetah-editor-field' ),
				form      = textarea.closest( '.ba-cheetah-settings' ),
				wrap      = textarea.closest( '.wp-editor-wrap' ),
				id        = textarea.attr( 'id' ),
				setting   = field.attr( 'data-name' ),
				editor    = typeof tinymce == 'undefined' ? false : tinymce.get( id ),
				hidden    = textarea.siblings( 'textarea[name="' + setting + '"]' ),
				wpautop   = field.data( 'wpautop' );

			// Add a hidden textarea if we don't have one.
			if ( 0 === hidden.length ) {
				hidden = $( '<textarea name="' + setting + '"></textarea>' ).hide();
				textarea.after( hidden );
			}

			// Save editor content.
			if ( wpautop ) {

				if ( editor && wrap.hasClass( 'tmce-active' ) ) {
					hidden.val( editor.getContent() );
				}
				else if ( 'undefined' != typeof switchEditors ) {
					hidden.val( switchEditors.wpautop( textarea.val() ) );
				}
				else {
					hidden.val( textarea.val() );
				}
			}
			else {

				if ( editor && wrap.hasClass( 'tmce-active' ) ) {
					editor.save();
				}

				hidden.val( textarea.val() );
			}
		},

		/* Loop Settings Fields
		----------------------------------------------------------*/

		/**
		 * Callback for the data source of loop settings changes.
		 *

		 * @access private
		 * @method _loopDataSourceChange
		 */
		_loopDataSourceChange: function()
		{
			var val = $( this ).val();

			$('.ba-cheetah-loop-data-source').hide();
			$('.ba-cheetah-loop-data-source[data-source="' + val + '"]').show();
		},

		/**
		 * Callback for when the post type of a custom query changes.
		 *

		 * @access private
		 * @method _customQueryPostTypeChange
		 */
		_customQueryPostTypeChange: function()
		{
			var val = $(this).val();

			$('.ba-cheetah-custom-query-filter').hide();
			$('.ba-cheetah-custom-query-' + val + '-filter').show();
		},

		/* Ordering Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all ordering fields in a settings form.
		 *

		 * @access private
		 * @method _initOrderingFields
		 */
		_initOrderingFields: function()
		{
			$( '.ba-cheetah-settings:visible .ba-cheetah-ordering-field-options' ).each( BACheetah._initOrderingField );
		},

		/**
		 * Initializes a single ordering field in a settings form.
		 *

		 * @access private
		 * @method _initOrderingField
		 */
		_initOrderingField: function()
		{
			$( this ).sortable( {
				items: '.ba-cheetah-ordering-field-option',
				containment: 'parent',
				tolerance: 'pointer',
				stop: BACheetah._updateOrderingField
			} );
		},

		/**
		 * Updates an ordering field when dragging stops.
		 *

		 * @access private
		 * @method _updateOrderingField
		 * @param {Object} e The event object.
		 */
		_updateOrderingField: function( e )
		{
			var options = $( e.target ),
				input   = options.siblings( 'input[type=hidden]' ),
				value   = [];

			options.find( '.ba-cheetah-ordering-field-option' ).each( function() {
				value.push( $( this ).attr( 'data-key' ) );
			} );

			input.val( JSON.stringify( value ) ).trigger( 'change' );
		},

		/* Text Fields - Add Predefined Value Selector
		----------------------------------------------------------*/

		/**
		 * Callback for when "add value" selectors for text fields changes.
		 *

		 * @access private
		 * @method _textFieldAddValueSelectChange
		 */
		_textFieldAddValueSelectChange: function()
		{

			var dropdown     = $( this ),
			    textField    = $( 'input[name="' + dropdown.data( 'target' ) + '"]' ),
			    currentValue = textField.val(),
			    addingValue  = dropdown.val(),
			    newValue     = '';

			// Adding selected value to target text field only once

				if ( -1 == currentValue.indexOf( addingValue ) ) {

					newValue = ( currentValue.trim() + ' ' + addingValue.trim() ).trim();

					textField
						.val( newValue )
						.trigger( 'change' )
						.trigger( 'keyup' );

				}

			// Resetting the selector

				dropdown
					.val( '' );

		},

		/* Number Fields
		----------------------------------------------------------*/

		/**

		 * @access private
		 * @method _onNumberFieldFocus
		 */
		_onNumberFieldFocus: function(e) {
			var $input = $(e.currentTarget);
			$input.addClass('mousetrap');

			Mousetrap.bind('up', function() {
				$input.attr('step', 1);
			});
			Mousetrap.bind('down', function() {
				$input.attr('step', 1);
			});
			Mousetrap.bind('shift+up', function() {
				$input.attr('step', 10);
			});
			Mousetrap.bind('shift+down', function() {
				$input.attr('step', 10);
			});
		},

		/**

		 * @access private
		 * @method _onNumberFieldBlur
		 */
		_onNumberFieldBlur: function(e) {
			var $input = $(e.currentTarget);
			$input.attr('step', 'any').removeClass('mousetrap');
		},

		/* Timezone Fields
		----------------------------------------------------------*/

		/**

		 * @access private
		 * @method _initTimezoneFields
		 */
		_initTimezoneFields: function() {
			$( '.ba-cheetah-settings:visible .ba-cheetah-field[data-type=timezone]' ).each( BACheetah._initTimezoneField );
		},

		/**

		 * @access private
		 * @method _initTimezoneField
		 */
		_initTimezoneField: function() {
			var select = $( this ).find( 'select' ),
				value  = select.attr( 'data-value' );

			select.find( 'option[value="' + value + '"]' ).prop('selected', true);
		},

		/* Dimension Fields
		----------------------------------------------------------*/

		/**
		 * Initializes all dimension fields in a form.
		 *

		 * @access private
		 * @method _initDimensionFields
		 */
		_initDimensionFields: function() {
			var form = $( '.ba-cheetah-settings:visible' );

			form.find( '.ba-cheetah-field[data-type=dimension]' ).each( BACheetah._initDimensionField );
			form.find( '.ba-cheetah-dimension-field-link' ).on( 'click', BACheetah._dimensionFieldLinkClicked );
			BACheetah.addHook( 'responsive-editing-switched', this._initResponsiveDimensionFieldLinking );

			form.find( '.ba-cheetah-compound-field-setting' ).has( '.ba-cheetah-dimension-field-link' ).each( BACheetah._initDimensionFieldLinking );
		},

		/**
		 * Initializes a single dimension field.
		 *

		 * @access private
		 * @method _initDimensionField
		 */
		_initDimensionField: function() {
			var field = $( this ),
				label = field.find( '.ba-cheetah-field-label label' ),
				wrap = field.find( '.ba-cheetah-field-control-wrapper' ),
				icon = '<i class="ba-cheetah-dimension-field-link ba-cheetah-tip dashicons dashicons-admin-links" title="Link Values"></i>';

			label.append( icon );
			wrap.prepend( icon );

			BACheetah._initTipTips();
			BACheetah._initDimensionFieldLinking.apply( this );
		},

		/**
		 * Initializes input linking for a dimension field by
		 * linking inputs if they all have the same value.
		 *

		 * @access private
		 * @method _initDimensionFieldLinking
		 */
		_initDimensionFieldLinking: function() {
			var field = $( this ),
				icon = field.find( '.ba-cheetah-dimension-field-link' ),
				inputs = BACheetah._getDimensionFieldLinkingInputs( field ),
				equal = BACheetah._dimensionFieldInputsAreEqual( inputs );

			if ( equal ) {
				icon.removeClass( 'dashicons-admin-links' );
				icon.addClass( 'dashicons-editor-unlink' );
				inputs.off( 'input', BACheetah._dimensionFieldLinkedValueChange );
				inputs.on( 'input', BACheetah._dimensionFieldLinkedValueChange );
			} else {
				icon.addClass( 'dashicons-admin-links' );
				icon.removeClass( 'dashicons-editor-unlink' );
			}
		},

		/**
		 * Initializes input linking for responsive dimension fields
		 * when the responsive mode is switched.
		 *

		 * @access private
		 * @method _initDimensionFieldLinking
		 */
		_initResponsiveDimensionFieldLinking: function() {
			var form = $( '.ba-cheetah-settings:visible' );
			form.find( '.ba-cheetah-field[data-type=dimension]' ).each( BACheetah._initDimensionFieldLinking );
		},

		/**
		 * Handles logic for when dimension fields are linked
		 * or unlinked from each other.
		 *

		 * @access private
		 * @method _dimensionFieldLinkClicked
		 */
		_dimensionFieldLinkClicked: function() {
			var target = $( this ),
				compound = target.closest( '.ba-cheetah-compound-field-setting' ),
				field = compound.length ? compound : target.closest( '.ba-cheetah-field' ),
				icon = field.find( '.ba-cheetah-dimension-field-link' ),
				linked = icon.hasClass( 'dashicons-editor-unlink' ),
				inputs = BACheetah._getDimensionFieldLinkingInputs( field );

			icon.toggleClass( 'dashicons-admin-links' );
			icon.toggleClass( 'dashicons-editor-unlink' );

			if ( linked ) {
				inputs.off( 'input', BACheetah._dimensionFieldLinkedValueChange );
			} else {
				inputs.val( inputs.eq( 0 ).val() ).trigger( 'input' );
				inputs.on( 'input', BACheetah._dimensionFieldLinkedValueChange );
			}
		},

		/**
		 * Updates dimension inputs when a linked input changes.
		 *

		 * @access private
		 * @method _dimensionFieldLinkedValueChange
		 */
		_dimensionFieldLinkedValueChange: function() {
			var input = $( this ),
				name = input.attr( 'name' ),
				wrap = input.closest( '.ba-cheetah-dimension-field-units' ),
				inputs = wrap.find( 'input:not([name="' + name + '"])' );

			inputs.off( 'input', BACheetah._dimensionFieldLinkedValueChange );
			inputs.val( input.val() ).trigger( 'input' );
			inputs.on( 'input', BACheetah._dimensionFieldLinkedValueChange );
		},

		/**
		 * Returns the inputs for dimension field linking. If this field
		 * is responsive, then only returns inputs for the current mode.
		 *

		 * @access private
		 * @method _getDimensionFieldLinkingInputs
		 * @param {Object} field
		 * @return {Object}
		 */
		_getDimensionFieldLinkingInputs: function( field ) {
			var responsive = field.find( '.ba-cheetah-field-responsive-setting' ).length ? true : false,
				mode = BACheetahResponsiveEditing._mode,
				inputs = null;

			if ( responsive ) {
				inputs = field.find( '.ba-cheetah-field-responsive-setting-' + mode + ' input' );
			} else {
				inputs = field.find( '.ba-cheetah-dimension-field-unit input' );
			}

			return inputs;
		},

		/**
		 * Checks to see if all inputs for a dimension field have
		 * the same value or not.
		 *

		 * @access private
		 * @method _dimensionFieldInputsAreEqual
		 * @param {Object} inputs
		 * @return {Boolean}
		 */
		_dimensionFieldInputsAreEqual: function( inputs ) {
			var first = inputs.eq( 0 ).val();

			if ( '' === first ) {
				return false;
			}

			for ( var i = 1; i < 4; i++ ) {
				if ( inputs.eq( i ).val() !== first ) {
					return false;
				}
			}

			return true;
		},

		/* Field Popup Sliders
		----------------------------------------------------------*/

		/**
		 * Initializes unit and dimension field popup slider controls.
		 *

		 * @access private
		 * @method _initFieldPopupSliders
		 */
		_initFieldPopupSliders: function() {
			var form = $( '.ba-cheetah-settings:visible' ),
				sliders = form.find( '.ba-cheetah-field-popup-slider' );

			sliders.each( BACheetah._initFieldPopupSlider );
		},

		/**
		 * Initializes a single popup slider control.
		 *

		 * @access private
		 * @method _initFieldPopupSlider
		 */
		_initFieldPopupSlider: function() {
			var body = $( 'body' ),
				wrapper = $( this ),
				slider = wrapper.find( '.ba-cheetah-field-popup-slider-input' ),
				arrow = wrapper.find( '.ba-cheetah-field-popup-slider-arrow' ),
				name = wrapper.data( 'input' ),
				input = $( 'input[name="' + name + '"]' );

			input.on( 'click', function() {

				if ( ! slider.hasClass( 'ba-cheetah-field-popup-slider-init' ) ) {
					slider.slider( {
						value: input.val(),
						slide: function( e, ui ) {
							input.val( ui.value ).trigger( 'input' );
						},
					} );

					input.on( 'input', function() {
						slider.slider( 'value', $( this ).val() );
					} );

					slider.addClass( 'ba-cheetah-field-popup-slider-init' );
					slider.find( '.ui-slider-handle' ).removeAttr( 'tabindex' );
				}

				BACheetah._setFieldPopupSliderMinMax( slider );
				BACheetah._hideFieldPopupSliders();
				body.on( 'mousedown', BACheetah._hideFieldPopupSliders );
				input.addClass( 'ba-cheetah-field-popup-slider-focus' );
				wrapper.show();

				var tab = $( '.ba-cheetah-settings:visible .ba-cheetah-settings-tab.ba-cheetah-active' ),
					tabOffset = tab.offset(),
					inputOffset = input.offset(),
					inputWidth = input.width(),
					wrapperOffset = wrapper.offset();

				if ( wrapperOffset.top + wrapper.outerHeight() > tabOffset.top + tab.outerHeight() ) {
					wrapper.addClass( 'ba-cheetah-field-popup-slider-top' );
				}

				arrow.css( 'left', ( 2 + inputOffset.left - wrapperOffset.left + inputWidth / 2 ) + 'px' );
			} );

			input.on( 'focus', function() {
				BACheetah._hideFieldPopupSliders();
			} );
		},

		/**
		 * Hides all single slider controls.
		 *

		 * @access private
		 * @param {Object} e
		 * @method _hideFieldPopupSliders
		 */
		_hideFieldPopupSliders: function( e ) {
			var target = e ? $( e.target ) : null,
				body = $( 'body' ),
				sliders = $( '.ba-cheetah-field-popup-slider:visible' ),
				inputs = $( '.ba-cheetah-field-popup-slider-focus' );

			if ( target ) {
				if ( target.closest( '.ba-cheetah-field-popup-slider' ).length ) {
					return;
				} else if ( target.closest( '.ba-cheetah-field-popup-slider-focus' ).length ) {
					return;
				}
			}

			body.off( 'mousedown', BACheetah._hideFieldPopupSliders );
			inputs.removeClass( 'ba-cheetah-field-popup-slider-focus' );
			sliders.hide();
		},

		/**
		 * Sets the min/max/step config for a popup slider.
		 *

		 * @access private
		 * @method _setFieldPopupSliderMinMax
		 * @param {Object} slider
		 */
		_setFieldPopupSliderMinMax: function( slider ) {
			var wrapper = slider.parent(),
				parent = wrapper.parent().parent(),
				select = parent.find( 'select.ba-cheetah-field-unit-select' ),
				unit = select.val(),
				data = wrapper.data( 'slider' ),
				min = 0,
				max = 100,
				step = 1;

			if ( '' === unit || 'em' === unit || 'rem' === unit ) {
				max = 10;
				step = .1;
			}

			if ( 'object' === typeof data ) {
				min = data.min ? parseFloat( data.min ) : min;
				max = data.max ? parseFloat( data.max ) : max;
				step = data.step ? parseFloat( data.step ) : step;

				if ( select.length && data[ unit ] ) {
					min = data[ unit ].min ? parseFloat( data[ unit ].min ) : min;
					max = data[ unit ].max ? parseFloat( data[ unit ].max ) : max;
					step = data[ unit ].step ? parseFloat( data[ unit ].step ) : step;
				}
			}

			slider.slider( {
				min: min,
				max: max,
				step: step,
			} );
		},


		/* Preset Fields
		---------------------------------------------------- */
		_initPresetFields: function() {
			var form = $( '.ba-cheetah-settings:visible' ),
				fields = form.find( '.ba-cheetah-preset-select-controls' );

			fields.each( BACheetah._initPresetField );
		},

		_initPresetField: function() {
			var field = $( this ),
				select = field.find('select'),
				presetType = field.data('presets'),
				prefix = field.data('prefix');

			select.on( 'change', BACheetah._setFormPreset.bind( this, presetType, prefix ) );
		},

		_setFormPreset: function( type, prefix, e ) {
			var value = $( e.currentTarget ).val();
				presetLists = BACheetahConfig.presets,
				presets = presetLists[type],
				form = $( '.ba-cheetah-settings:visible' );

			if ( 'undefined' !== presets && 'undefined' !== presets[value] ) {
				var settings = presets[value].settings;

				for( var name in settings ) {
					var value = settings[name],
						input;
					if ( 'undefined' !== typeof prefix && '' !== prefix ) {
						// Prefix setting name
						input = form.find('[name="' + prefix + name + '"]');
					} else {
						input = form.find('[name="' + name + '"]');
					}
					input.val(value).trigger('change').trigger('input');
				}
			}
		},

		_initDragDropFields: (function(){
			var field = $( '.ba-cheetah-settings:visible input[name="dragdrop"]' );

			/*
			field.on( 'change', function(){
				if ($(this).val() == 'yes') {
					if (confirm(BACheetahStrings.notificationDradDropActivated)) {
						BACheetah._triggerSettingsSave( false, false, false )
					}
				}
			});
			*/
		}),


		/* AJAX
		----------------------------------------------------------*/

		/**
		 * Frontend AJAX for the builder interface.
		 *

		 * @method ajax
		 * @param {Object} data The data for the AJAX request.
		 * @param {Function} callback A function to call when the request completes.
		 */
		ajax: function(data, callback)
		{
			var prop;

			// Queue this request if one is already in progress.
			if ( BACheetah._ajaxRequest ) {
				BACheetah._ajaxQueue.push( {
					data: data,
					callback: callback,
				} );
				return;
			}

			BACheetah.triggerHook('didBeginAJAX', data );

			// Undefined props don't get sent to the server, so make them null.
			for ( prop in data ) {
				if ( 'undefined' == typeof data[ prop ] ) {
					data[ prop ] = null;
				}
			}

			// Add the ajax nonce to the data.
			data._wpnonce = BACheetahConfig.ajaxNonce;

			// Send the post id to the server.
			data.post_id = BACheetahConfig.postId;

			// Tell the server that the builder is active.
			data.ba_cheetah = 1;

			// Append the builder namespace to the action.
			data.ba_cheetah_action = data.action;

			// Prevent ModSecurity false positives if our fix is enabled.
			if ( 'undefined' != typeof data.settings ) {
				data.settings = BACheetah._ajaxModSecFix( $.extend( true, {}, data.settings ) );
			}
			if ( 'undefined' != typeof data.node_settings ) {
				data.node_settings = BACheetah._ajaxModSecFix( $.extend( true, {}, data.node_settings ) );
			}

			if ( 'undefined' != typeof data.node_preview ) {
				data.node_preview = BACheetah._ajaxModSecFix( $.extend( true, {}, data.node_preview ) );
			}

			data.settings      = BACheetah._inputVarsCheck( data.settings );
			data.node_settings = BACheetah._inputVarsCheck( data.node_settings );

			if ( 'error' === data.settings || 'error' === data.node_settings ) {
				return 0;
			}

			// Store the data in a single variable to avoid conflicts.
			data = { ba_cheetah_data: data };

			// Do the ajax call.
			BACheetah._ajaxRequest = $.post(BACheetah._ajaxUrl(), data, function(response) {
				if(typeof callback !== 'undefined') {
					callback.call(this, response);
				}

				BACheetah.triggerHook('didCompleteAJAX', data );

			})
			.always( BACheetah._ajaxComplete )
			.fail( function( xhr, status, error ){
				msg = false;
				switch(xhr.status) {
					case 403:
					case 409:
						msg  = 'Something you entered has triggered a ' + xhr.status + ' error.<br /><br />This is nearly always due to mod_security settings from your hosting provider.'
					break;
				}
				if ( msg ) {
					console.log(xhr)
					console.log(error)
					BACheetah.alert(msg)
				}
			})


			return BACheetah._ajaxRequest;
		},

		_inputVarsCheck: function( o ) {

			var maxInput = BACheetahConfig.MaxInputVars || 0;

			if ( 'undefined' != typeof o && maxInput > 0 ) {
				count = $.map( o, function(n, i) { return i; }).length;
				if ( count > maxInput ) {
					BACheetah.alert( '<h1 style="font-size:2em;text-align:center">Critical Issue</h1><br />The number of settings being saved (' + count + ') exceeds the PHP Max Input Vars setting (' + maxInput + ').<br />Please contact your host to have this value increased, the default is 1000.' );
					console.log( 'Vars Count: ' + count );
					console.log( 'Max Input: ' + maxInput );
					return 'error';
				}
			}
			return o;
		},

		/**
		 * Callback for when an AJAX request is complete.
		 *

		 * @access private
		 * @method _ajaxComplete
		 */
		_ajaxComplete: function()
		{
			BACheetah._ajaxRequest = null;
			BACheetah.hideAjaxLoader();

			if ( BACheetah._ajaxQueue.length ) {
				var item = BACheetah._ajaxQueue.shift();
				BACheetah.ajax( item.data, item.callback );
			}
		},

		/**
		 * Returns a URL for an AJAX request.
		 *

		 * @access private
		 * @method _ajaxUrl
		 * @param {Object} params An object with key/value pairs for the AJAX query string.
		 * @return {String} The AJAX URL.
		 */
		_ajaxUrl: function(params)
		{
			var config  = BACheetahConfig,
				url     = config.shortlink,
				param   = null;

			if(typeof params !== 'undefined') {

				for(param in params) {
					url += url.indexOf('?') > -1 ? '&' : '?';
					url += param + '=' + params[param];
				}
			}
			return url;
		},

		/**
		 * Shows the AJAX loading overlay.
		 *

		 * @method showAjaxLoader
		 */
		showAjaxLoader: function()
		{
			if( 0 === $( '.ba-cheetah-lightbox-loading' ).length ) {
				$( '.ba-cheetah-loading' ).show();
			}
		},

		/**
		 * Hides the AJAX loading overlay.
		 *

		 * @method hideAjaxLoader
		 */
		hideAjaxLoader: function()
		{
			$( '.ba-cheetah-loading' ).hide();
		},

		/**
		 * Fades a node when it is being loaded.
		 *

		 * @access private
		 * @param {String} nodeId
		 * @method _showNodeLoading
		 */
		_showNodeLoading: function( nodeId )
		{
			var node = $( '.ba-cheetah-node-' + nodeId );

			node.addClass( 'ba-cheetah-node-loading' );

			BACheetah._removeAllOverlays();
			BACheetah.triggerHook( 'didStartNodeLoading', node );
		},

		/**
		 * Brings a node back to 100% opacity when it's done loading.
		 *

		 * @access private
		 * @param {String} nodeId
		 * @method _hideNodeLoading
		 */
		_hideNodeLoading: function( nodeId )
		{
			var node = $( '.ba-cheetah-node-' + nodeId );

			node.removeClass( 'ba-cheetah-node-loading' );
		},

		/**
		 * Inserts a placeholder in place of where a node will be
		 * that is currently loading.
		 *

		 * @access private
		 * @param {Object} parent
		 * @param {Number} position
		 * @method _showNodeLoadingPlaceholder
		 */
		_showNodeLoadingPlaceholder: function( parent, position )
		{
			var placeholder = $( '<div class="ba-cheetah-node-loading-placeholder"></div>' );

			// Make sure we only have one placeholder at a time.
			$( '.ba-cheetah-node-loading-placeholder' ).remove();

			// Get sibling rows.
			if ( parent.hasClass( 'ba-cheetah-content' ) ) {
				siblings = parent.find( ' > .ba-cheetah-row' );
			}
			// Get sibling column groups.
			else if ( parent.hasClass( 'ba-cheetah-row-content' ) ) {
				siblings = parent.find( ' > .ba-cheetah-col-group' );
			}
			// Get sibling columns.
			else if ( parent.hasClass( 'ba-cheetah-col-group' ) ) {
				parent.addClass( 'ba-cheetah-col-group-has-child-loading' );
				siblings = parent.find( ' > .ba-cheetah-col' );
			}
			// Get sibling modules.
			else {
				siblings = parent.find( ' > .ba-cheetah-col-group, > .ba-cheetah-module' );
			}

			// Add the placeholder.
			if ( 0 === siblings.length || siblings.length == position) {
				parent.append( placeholder );
			}
			else {
				siblings.eq( position ).before( placeholder );
			}
		},

		/**
		 * Removes the node loading placeholder for a node.
		 *

		 * @access private
		 * @param {Object} node
		 * @method _removeNodeLoadingPlaceholder
		 */
		_removeNodeLoadingPlaceholder: function( node )
		{
			var prev = node.prev( '.ba-cheetah-node-loading-placeholder' ),
				next = node.next( '.ba-cheetah-node-loading-placeholder' );

			if ( prev.length ) {
				prev.remove();
			} else {
				next.remove();
			}
		},

		/**
		 * Base64 encode settings to prevent ModSecurity false
		 * positives if our fix is enabled.
		 *

		 * @access private
		 * @method _ajaxModSecFix
		 */
		_ajaxModSecFix: function( settings )
		{
			var prop;

			if ( BACheetahConfig.modSecFix && 'undefined' != typeof btoa ) {

				if ( 'string' == typeof settings ) {
					settings = BACheetah._btoa( settings );
				}
				else {

					for ( prop in settings ) {

						if ( 'string' == typeof settings[ prop ] ) {
							settings[ prop ] = BACheetah._btoa( settings[ prop ] );
						}
						else if( 'object' == typeof settings[ prop ] ) {
							settings[ prop ] = BACheetah._ajaxModSecFix( settings[ prop ] );
						}
					}
				}
			}

			return settings;
		},

		/**
		 * Fetch the options of a select via ajax.
		 * Used to select: menu, popup, mailingboss list, booking, video hosting, headers, footers...
		 * 
		 * @param {String} action xhr action (see BACheetahAJAX::add_actions)
		 * @param {String} property html field name
		 * @param {Function} callback 
		 */


		getSelectOptions: function(action, property, callback) {
			const form 			= $( '.ba-cheetah-settings:visible');
			const settings 		= BACheetah._getOriginalSettings( form, true );
			const selectElement	= form.find('select[name='+property+']');
			const currentValue	= settings[property];

			// disable select
			selectElement.empty();
			selectElement.prop('disabled', true)
			selectElement.append($(new Option('Loading', null, true, true)));

			// get options
			BACheetah.ajax({
				action: action,
			}, function(response) {

				// populate fields
				selectElement.empty();
				const options = (JSON.parse(response) || [])

				if (options.length) {
					selectElement.append($(new Option(BACheetahStrings.selectOne, '', !currentValue, !currentValue)).prop('disabled', true));
					options.forEach((option) => {
						selectElement.append(new Option(
							option.label,
							option.value,
							currentValue == option.value,
							currentValue == option.value
						))
					});
				} else {
					selectElement.append($(new Option(BACheetahStrings.noOptionsFound, '', true, true)).prop('disabled', true));
				}

				// enable field
				selectElement.prop('disabled', false)

				// automatically adds a refresh button next to the select
				if (!selectElement.next().is('i')) {
					const refreshButton	= $('<i class="dashicons dashicons-update js-reload-options"></i>')
					refreshButton.on('click', () => BACheetah.getSelectOptions(action, property, callback));
					selectElement.after(refreshButton)
				}

				// handle callback
				if (callback && typeof callback == 'function') {
					callback(options, selectElement)
				}
			});
		},

		/**
		 * Helper function for _ajaxModSecFix
		 * btoa() does not handle utf8/16 characters
		 * See: https://stackoverflow.com/questions/30106476/using-javascripts-atob-to-decode-base64-doesnt-properly-decode-utf-8-strings
		 *

		 * @access private
		 * @method _btoa
		 */
		_btoa: function(str) {
			return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
				return String.fromCharCode('0x' + p1);
			}));
		},

		/**

		 * @access private
		 * @method _wpmedia_reset_errors
		 */
		_wpmedia_reset_errors: function() {
			$('.upload-error').remove()
			$('.media-uploader-status' ).removeClass( 'errors' ).hide()
		},

		/* Lightboxes
		----------------------------------------------------------*/

		/**
		 * Initializes the lightboxes for the builder interface.
		 *

		 * @access private
		 * @method _initLightboxes
		 */
		_initLightboxes: function()
		{
			/* Main builder lightbox */
			BACheetah._lightbox = new BACheetahLightbox({
				className: 'ba-cheetah-lightbox ba-cheetah-settings-lightbox',
				resizable: true
			});

			BACheetah._lightbox.on('resized', BACheetah._calculateSettingsTabsOverflow);
			BACheetah._lightbox.on('close', BACheetah._lightboxClosed);
			BACheetah._lightbox.on('beforeCloseLightbox', BACheetah._destroyEditorFields);

			/* Actions lightbox */
			BACheetah._actionsLightbox = new BACheetahLightbox({
				className: 'ba-cheetah-actions-lightbox'
			});
		},

		/**
		 * Shows the settings lightbox.
		 *

		 * @access private
		 * @method _showLightbox
		 */
		_showLightbox: function( content )
		{
			if ( ! content ) {
				content = '<div class="ba-cheetah-lightbox-loading"></div>';
			}

			BACheetah._lightbox.open( content );
			BACheetah._initLightboxScrollbars();
		},

		/**
		 * Set the content for the settings lightbox.
		 *

		 * @access private
		 * @method _setLightboxContent
		 * @param {String} content The HTML content for the lightbox.
		 */
		_setLightboxContent: function(content)
		{
			BACheetah._lightbox.setContent(content);
		},

		/**
		 * Initializes the scrollbars for the settings lightbox.
		 *

		 * @access private
		 * @method _initLightboxScrollbars
		 */
		_initLightboxScrollbars: function()
		{
			BACheetah._initScrollbars();
			clearTimeout( BACheetah._lightboxScrollbarTimeout );
			BACheetah._lightboxScrollbarTimeout = setTimeout(BACheetah._initLightboxScrollbars, 500);
		},

		/**
		 * Callback to clean things up when the settings lightbox
		 * is closed.
		 *

		 * @access private
		 * @method _lightboxClosed
		 */
		_lightboxClosed: function()
		{
			BACheetah.triggerHook( 'settings-lightbox-closed' );
			BACheetah._lightbox.empty();
			clearTimeout( BACheetah._lightboxScrollbarTimeout );
			BACheetah._lightboxScrollbarTimeout = null;
		},

		/**
		 * Shows the actions lightbox.
		 *

		 * @access private
		 * @method _showActionsLightbox
		 * @param {Object} settings An object with settings for the lightbox buttons.
		 */
		_showActionsLightbox: function(settings)
		{
			var template = wp.template( 'ba-cheetah-actions-lightbox' );

			// Allow extensions to modify the settings object.
			BACheetah.triggerHook( 'actions-lightbox-settings', settings );

			// Open the lightbox.
			BACheetah._actionsLightbox.open( template( settings ) );
		},

		/* Alert Lightboxes
		----------------------------------------------------------*/

		/**
		 * Shows the alert lightbox with a message.
		 *

		 * @method alert
		 * @param {String} message The message to show.
		 */
		alert: function(message, title = '', template = '', icon = '')
		{
			var alert = new BACheetahLightbox({
					className: ( template || 'ba-cheetah-alert-lightbox' ),
					destroyOnClose: true
				}),
				template = wp.template( template || 'ba-cheetah-alert-lightbox' );

			alert.open( template( { 
				message : message,
				title	: title,
				icon	: icon
			} ) );
		},

		crashMessage: function(debug)
		{
			BACheetahLightbox.closeAll();
			var alert = new BACheetahLightbox({
					className: 'ba-cheetah-alert-lightbox ba-cheetah-crash-lightbox',
					destroyOnClose: true
				}),
				template  = wp.template( 'ba-cheetah-crash-lightbox' ),
				product   = window.crash_vars.product,
				labeled   = window.crash_vars.white_label,
				label_txt = window.crash_vars.labeled_txt;



				message  = product + " has detected a plugin conflict that is preventing the page from saving.<p>( In technical terms there’s probably a PHP error in Ajax. )</p>"
				info     = "<p>If you contact Builderall Builder Support, we need to know what the error is in the JavaScript console in your browser.</p>"

				info     +="<div><div style='width:49%;float:left;'>"
				info     +="<p>MacOS Users:<br />Chrome: View > Developer > JavaScript Console<br />Firefox: Tools > Web Developer > Browser Console<br />Safari: Develop > Show JavaScript console</p>"
				info     +="</div>"

				info     +="<div style='width:49%;float:right;'>"
				info     +="<p>Windows Users:<br />Chrome: Settings > More Tools > Developer > Console<br />Firefox: Menu/Settings > Web Developer > Web Console<br />Edge: Settings and More > More Tools > Console</p>"
				info     +="</div></div>"

				info     +="<p style='display:inline-block;'>Copy the errors you find there and submit them with your Support ticket. It saves us having to ask you that as a second step. Then deactivate your plugins one by one while you try to save the page in the Builderall Builder editor. When the page saves normally, you have identified the plugin causing the conflict.</p>"

				if ( BACheetahConfig.MaxInputVars <= 3000 ) {
					info += '<br /><br />The PHP config value max_input_vars is only set to ' + BACheetahConfig.MaxInputVars + '. If you are using 3rd party addons this could very likely be the cause of this error.'
				}

				debug    = false
				if ( labeled ) {
					info = label_txt
				}
				alert.open( template( { message : message, info: info, debug: debug } ) );
		},

		/**
		 * Closes the alert lightbox when a child element is clicked.
		 *

		 * @access private
		 * @method _alertClose
		 */
		_alertClose: function()
		{
			BACheetahLightbox.closeParent(this);
		},

		/**
		 * Shows the confirm lightbox with a message.
		 *

		 * @method confirm
		 * @param {Object} o The config object that overrides the defaults.
		 */
		confirm: function( o )
		{
			var defaults = {
					message : '',
					ok      : function(){},
					cancel  : function(){},
					strings : {
						'ok'     : BACheetahStrings.ok,
						'cancel' : BACheetahStrings.cancel
					}
				},
				config = $.extend( {}, defaults, ( 'undefined' == typeof o ? {} : o ) )
				lightbox = new BACheetahLightbox({
					className: 'ba-cheetah-confirm-lightbox ba-cheetah-alert-lightbox',
					destroyOnClose: true
				}),
				template = wp.template( 'ba-cheetah-confirm-lightbox' );

			lightbox.open( template( config ) );
			lightbox._node.find( '.ba-cheetah-confirm-ok' ).on( 'click', config.ok );
			lightbox._node.find( '.ba-cheetah-confirm-cancel' ).on( 'click', config.cancel );
		},

		/* Simple JS hooks similar to WordPress PHP hooks.
		----------------------------------------------------------*/

		/**
		 * Trigger a hook.
		 *

		 * @method triggerHook
		 * @param {String} hook The hook to trigger.
		 * @param {Array} args An array of args to pass to the hook.
		 */
		triggerHook: function( hook, args )
		{
			$( 'body' ).trigger( 'ba-cheetah.' + hook, args );
		},

		/**
		 * Add a hook.
		 *

		 * @method addHook
		 * @param {String} hook The hook to add.
		 * @param {Function} callback A function to call when the hook is triggered.
		 */
		addHook: function( hook, callback )
		{
			$( 'body' ).on( 'ba-cheetah.' + hook, callback );
		},

		/**
		 * Remove a hook.
		 *

		 * @method removeHook
		 * @param {String} hook The hook to remove.
		 * @param {Function} callback The callback function to remove.
		 */
		removeHook: function( hook, callback )
		{
			$( 'body' ).off( 'ba-cheetah.' + hook, callback );
		},

		/* Console Logging
		----------------------------------------------------------*/

		/**
		 * Logs a message in the console if the console is available.
		 *

		 * @method log
		 * @param {String} message The message to log.
		 */
		log: function( message )
		{
			if ( 'undefined' == typeof window.console || 'undefined' == typeof window.console.log ) {
				return;
			}

			console.log( message );
		},

		/**
		 * Logs an error in the console if the console is available.
		 *

		 * @method logError
		 * @param {String} error The error to log.
		 */
		logError: function( error, data )
		{
			var message = null;

			if ( 'undefined' == typeof error ) {
				return;
			}
			else if ( 'undefined' != typeof error.stack ) {
				message = error.stack;
			}
			else if ( 'undefined' != typeof error.message ) {
				message = error.message;
			}

			if ( message ) {
				BACheetah.log( '************************************************************************' );
				BACheetah.log( BACheetahStrings.errorMessage );
				BACheetah.log( message );
				if ( 'undefined' != typeof data && data ) {
						BACheetah.log( "Debug Info" );
						console.log( data );
				}
				// Show debug data in console.
				$.each( window.crash_vars.vars, function(i,t) {
					console.log(i + ': ' + t)
				})
				BACheetah.log( '************************************************************************' );
				if ( 'undefined' != typeof data && data ) {
					message = data + "\n" + message
				}
				BACheetah.crashMessage(message)
			}
		},

		/**
		 * Logs a global error in the console if the console is available.
		 *

		 * @method logGlobalError
		 * @param {String} message
		 * @param {String} file
		 * @param {String} line
		 * @param {String} col
		 * @param {String} error
		 */
		logGlobalError: function( message, file, line, col, error )
		{
			BACheetah.log( '************************************************************************' );
			BACheetah.log( BACheetahStrings.errorMessage );
			BACheetah.log( BACheetahStrings.globalErrorMessage.replace( '{message}', message ).replace( '{line}', line ).replace( '{file}', file ) );

			if ( 'undefined' != typeof error && 'undefined' != typeof error.stack ) {
				BACheetah.log( error.stack );
			}
			BACheetah.log( '************************************************************************' );
		},

		/**
		 * Parse JSON with try/catch and print useful debug info on error.

		 * @param {string} data JSON data
		 */
		_jsonParse: function( data ) {
			try {
					data = JSON.parse( data );
					} catch (e) {
						BACheetah.logError( e, BACheetah._parseError( data ) );
					}
					return data;
		},

		/**
		 * Parse data for php error on 1st line.

		 * @param {string} data the JSON containing error(s)
		 */
		_parseError: function( data ) {
			if( data.indexOf('</head>') ) {
				return 'AJAX returned HTML page instead of data. (Possible 404 or max_input_vars)';
			}
			php = data.match(/^<.*/gm) || false;
			if ( php && php.length > 0 ) {
				var txt = '';
				$.each( php, function(i,t) {
					txt += t
				})
				return $(txt).text();
			}
			return false;
		},
		/**
		 * Helper taken from lodash

		 */
		isUndefined: function(obj) {
			return obj === void 0;
		},

		/**
		 * Helper taken from lodash

		 */
		isBoolean: function(value) {
			return value === true || value === false
		}
	};

	/* Start the party!!! */
	$(function(){
		BACheetah._init();
	});

})(jQuery);
