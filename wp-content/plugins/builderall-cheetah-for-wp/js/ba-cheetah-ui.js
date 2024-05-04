(function($, BACheetah) {

    /**
    * Polyfill for String.startsWith()
    */
    if (!String.prototype.startsWith) {
        String.prototype.startsWith = function(searchString, position){
          position = position || 0;
          return this.substr(position, searchString.length) === searchString;
      };
    }

		/**
		 * Polyfill for String.endsWidth()
		 */
		if (!String.prototype.endsWith) {
			String.prototype.endsWith = function(searchString, position) {
				var subjectString = this.toString();
				if (typeof position !== 'number' || !isFinite(position) || Math.floor(position) !== position || position > subjectString.length) {
					position = subjectString.length;
				}
				position -= searchString.length;
				var lastIndex = subjectString.indexOf(searchString, position);
				return lastIndex !== -1 && lastIndex === position;
			};
		}

    // Calculate width of text from DOM element or string. By Phil Freo <http://philfreo.com>
    $.fn.textWidth = function(text, font) {
        if (!$.fn.textWidth.fakeEl) $.fn.textWidth.fakeEl = $('<span>').hide().appendTo(document.body);
        $.fn.textWidth.fakeEl.text(text || this.val() || this.text()).css('font', font || this.css('font'));
        return $.fn.textWidth.fakeEl.width();
    };

    /**
    * Base object that all view objects can delegate to.
    * Has the ability to create new objects with itself as the new object's prototype.
    */
    BACheetahExtendableObject = {
        /**
        * Create new object with the current object set as its prototype.
        * @var mixin - Object with properties to be mixed into the final object.
        * @return object
        */
        create: function(mixin) {
            // create a new object with this object as it's prototype.
            var obj = Object.create(this);
            // mix any given properties into it
            obj = $.extend(obj, mixin);

            $(this).trigger('onCreate');

            return obj;
        },
    };

    /**
    * jQuery function to set a class while removing all other classes from
    * the same element that start with the prefix.
    * This is to allow for class states where only one state from the group of classes
    * can be present at any time.
    */
    $.fn.switchClass = function (prefix, ending) {
        return this.each(function() {

            $(this).removeClass(function(i, classesString) {
                var classesToRemove = [];
                var classes = classesString.split(' ');
                for(var i in classes) {
                    if (classes[i].startsWith(prefix)) {
                        classesToRemove.push(classes[i]);
                    }
                }
                return classesToRemove.join(' ');
            });
            return $(this).addClass(prefix + ending);
        });
    };


    var KeyShortcuts = {

        /**
        * Initialize the keyboard shortcut manager.
        * @return void
        */
        init: function() {

            BACheetah.addHook('cancelTask', this.onCancelTask.bind(this));
            BACheetah.addHook('showSavedMessage', this.onSaveShortcut.bind(this));
            BACheetah.addHook('goToNextTab', this.onNextPrevTabShortcut.bind(this, 'next'));
            BACheetah.addHook('goToPrevTab', this.onNextPrevTabShortcut.bind(this, 'prev'));

            BACheetah.addHook('endEditingSession', this.onEndEditingSession.bind(this));
            BACheetah.addHook('restartEditingSession', this.onRestartEditingSession.bind(this));

            this.setDefaultKeyboardShortcuts();
        },

        /**
        * Add single keyboard shortcut
        * @var string A hook to be triggered by `BACheetah.triggerhook(hook)`
        * @var string The key combination to trigger the command.
        * @var bool isGlobal - If the shortcut should work even inside inputs.
        * @return void
        */
        addShortcut: function( hook, key, isGlobal ) {
            var fn = $.proxy(this, 'onTriggerKey', hook);
            if ( isGlobal ) {
                Mousetrap.bindGlobal(key, fn);
            } else {
                Mousetrap.bind(key, fn);
            }
        },

        /**
        * Clear all registered key commands.
        * @return void
        */
        reset: function() {
            Mousetrap.reset();
        },

        /**
        * Set the default shortcuts
        * @return void
        */
        setDefaultKeyboardShortcuts: function() {
            this.reset();

            for( var action in BACheetahConfig.keyboardShortcuts ) {
	            var code = BACheetahConfig.keyboardShortcuts[action].keyCode,
                    isGlobal = BACheetahConfig.keyboardShortcuts[action].isGlobal;

                this.addShortcut( action, code, isGlobal);
            }
        },

        /**
        * Handle a key command by triggering the associated hook.
        * @var string the hook to be fired.
        * @return void
        */
        onTriggerKey: function(hook, e) {
			console.log('onTriggerKey', hook)
            BACheetah.triggerHook(hook);

            if (e.preventDefault) {
                e.preventDefault();
            } else {
                // internet explorer
                e.returnValue = false;
            }
        },

        /**
        * Cancel out of the current task - triggered by pressing ESC
        * @return void
        */
        onCancelTask: function() {

            // Is the editor in preview mode?
            if (EditingUI.isPreviewing) {
                EditingUI.endPreview();
                return;
            }

            // Are the publish actions showing?
            if (PublishActions.isShowing) {
                PublishActions.hide();
                return;
            }

            // Is the content panel showing?
            if (BACheetah.ContentPanel.isShowing) {
                BACheetah.ContentPanel.hide();
                return;
            }
        },

        /**
        * Pause the active keyboard shortcut listeners.
        * @return void
        */
        pause: function() {
            Mousetrap.pause();
        },

        /**
        * Unpause the active keyboard shortcut listeners.
        * @return void
        */
        unpause: function() {
            Mousetrap.unpause();
        },

        /**
        * Handle ending the editing session
        * @return void
        */
        onEndEditingSession: function() {

            if ( 'Builder' in BA && 'data' in BA.Builder ) {
                const actions = BA.Builder.data.getSystemActions()
                actions.setIsEditing(false)
            }

			document.documentElement.classList.remove( 'ba-cheetah-assistant-visible' )

            this.reset();
            this.addShortcut('restartEditingSession', 'mod+e');
        },

        /**
        * Handle restarting the editing session
        * @return void
        */
        onRestartEditingSession: function() {

            if ( 'Builder' in BA && 'data' in BA.Builder ) {
                const actions = BA.Builder.data.getSystemActions()
                actions.setIsEditing(true)
            }

			const currentPanel = BA.Builder.data.getSystemState().currentPanel
			if ( 'assistant' === currentPanel ) {
				document.documentElement.classList.add( 'ba-cheetah-assistant-visible' )
			}

            this.reset();
            this.setDefaultKeyboardShortcuts();
        },

        /**
         * Handle CMD+S Save Shortcut
         *
         * @return void
         */
        onSaveShortcut: function() {
            if (BACheetah.SaveManager.layoutNeedsPublish()) {

                var message = BACheetahStrings.savedStatus.hasAlreadySaved;
                BACheetah.SaveManager.showStatusMessage(message);

                setTimeout(function() {
                    BACheetah.SaveManager.resetStatusMessage();
                }, 2000);

            } else {
                var message = BACheetahStrings.savedStatus.nothingToSave;
                BACheetah.SaveManager.showStatusMessage(message);

                setTimeout(function() {
                    BACheetah.SaveManager.resetStatusMessage();
                }, 2000);
            }
        },

        onNextPrevTabShortcut: function( direction, e ) {

            var $lightbox = $('.bal-cheetah-lightbox:visible'),
                $tabs = $lightbox.find('.ba-cheetah-settings-tabs a'),
                $activeTab,
                $nextTab;

            if ( $lightbox.length > 0 ) {
                $activeTab = $tabs.filter('a.ba-cheetah-active');

                if ( 'next' == direction ) {

                    if ( $activeTab.is( $tabs.last() ) ) {

                        $nextTab = $tabs.first();

                    } else {

                        $nextTab = $activeTab.next('a');
                    }

                } else {

                    if ( $activeTab.is( $tabs.first() ) ) {
                        $nextTab = $tabs.last();
                    } else {
                        $nextTab = $activeTab.prev('a');
                    }
                }
                $nextTab.trigger('click');
            }

            BACheetah._calculateSettingsTabsOverflow();
            e.preventDefault();
        },
    };


    /**
    * Publish actions button bar UI
    */
    var PublishActions = BACheetahExtendableObject.create({

        /**
        * Is the button bar showing?
        * @var bool
        */
        isShowing: false,

        /**
        * Setup the bar
        * @return void
        */
        init: function() {

            this.$el = $('.ba-cheetah-publish-actions');
            this.$defaultBarButtons = $('.ba-cheetah-bar-actions');
            this.$clickAwayMask = $('.ba-cheetah-publish-actions-click-away-mask');

            this.$doneBtn = this.$defaultBarButtons.find('.ba-cheetah-done-button');
            this.$doneBtn.on('click', this.onDoneTriggered.bind(this));

            this.$actions = this.$el.find('.ba-cheetah-button');
            this.$actions.on('click touchend', this.onActionClicked.bind(this));

            BACheetah.addHook('triggerDone', this.onDoneTriggered.bind(this));

            var hide = this.hide.bind(this);
            BACheetah.addHook('cancelPublishActions', hide);
            BACheetah.addHook('endEditingSession', hide);
            this.$clickAwayMask.on('click', hide );

			const cheetahNavbarActions = $('.ba-navbar-actions')
			const cbActions = cheetahNavbarActions.find('.ba-cheetah-button');
			cbActions.on('click touchend', this.onCheetahActionClicked.bind(this));
        },

        /**
        * Fired when the done button is clicked or hook is triggered.
        * @return void
        */
        onDoneTriggered: function() {
            if (BACheetah.SaveManager.layoutNeedsPublish()) {
                this.show();
            } else {
                if ( BACheetahConfig.shouldRefreshOnPublish ) {
					BACheetah._exit();
				} else {
					BACheetah._exitWithoutRefresh();
				}
            }
        },

        /**
        * Display the publish actions.
        * @return void
        */
        show: function() {
            if (this.isShowing) return;

            // Save existing settings first if any exist. Don't proceed if it fails.
			if ( ! BACheetah._triggerSettingsSave( false, true ) ) {
				return;
			}

            this.$el.removeClass('is-hidden');
            this.$defaultBarButtons.css('opacity', '0');
            this.$clickAwayMask.show();
            this.isShowing = true;
            BACheetah.triggerHook('didShowPublishActions');
        },

        /**
        * Hide the publish actions.
        * @return void
        */
        hide: function() {
            if (!this.isShowing) return;
            this.$el.addClass('is-hidden');
            this.$defaultBarButtons.css('opacity', '1');
            this.$clickAwayMask.hide();
            this.isShowing = false;
        },

		/**
        * Fired when a cheetah navbar action is clicked.
        * @return void
        */
		onCheetahActionClicked: function(e) {
			const action =  $(e.currentTarget).data('action')
			switch(action) {
				case 'change-device':
					const mode = $(e.currentTarget).data('mode') || 'default'
					BACheetahResponsiveEditing._switchTo(mode);
					break;
				case 'undo':
					BACheetah.triggerHook('undo');
					break;
				case 'preview':
					BACheetah.triggerHook('previewLayout');
					break;
				case 'redo':
					BACheetah.triggerHook('redo');
					break;
			}
		},

        /**
        * Fired when a publish action (or cancel) is clicked.
        * @return void
        */
        onActionClicked: function(e) {
            var action = $(e.currentTarget).data('action');
            switch(action) {
                case "dismiss":
                    this.hide();
                    break;
                case "discard":
                    this.hide();
                    EditingUI.muteToolbar();
                    BACheetah._discardButtonClicked();
                    break;
                case "publish":
                    this.hide();
                    EditingUI.muteToolbar();
                    BACheetah._publishButtonClicked();
                    BACheetah._destroyOverlayEvents();
                    break;
                case "draft":
                    this.hide();
                    EditingUI.muteToolbar();
                    BACheetah._draftButtonClicked();
                    break;
                default:
                    // draft
                    this.hide();
                    EditingUI.muteToolbar();
                    BACheetah._draftButtonClicked();
            }
            BACheetah.triggerHook( action + 'ButtonClicked' );
        },
    });


    /**
    * Editing UI State Controller
    */
    var EditingUI = {

        /**
        * @var bool - whether or not the editor is in preview mode.
        */
        isPreviewing: false,

        /**
        * Setup the controller.
        * @return void
        */
        init: function() {
            this.$el = $('body');
            this.$mainToolbar = $('.ba-cheetah-bar');
            this.$mainToolbarContent = this.$mainToolbar.find('.ba-cheetah-bar-content');
            this.$wpAdminBar = $('#wpadminbar');
            this.$endPreviewBtn = $('.ba-cheetah--preview-actions .end-preview-btn');
			this.$publishPreviewBtn = $('.ba-cheetah--preview-actions .publish-preview-btn');

            BACheetah.addHook('endEditingSession', this.endEditingSession.bind(this) );
            BACheetah.addHook('previewLayout', this.togglePreview.bind(this) );

            // End preview btn
            this.$endPreviewBtn.on('click', this.endPreview.bind(this));

			// Publish preview btn
            this.$publishPreviewBtn.on('click', this.publishOnPreview.bind(this));

            // Preview mode device size icons
            this.$deviceIcons = $('.ba-cheetah--preview-actions .ba-preview-devices .ba-cheetah-button');
            this.$deviceIcons.on('click', this.onDeviceIconClick.bind(this));

            // Admin bar link to re-enable editor
            var $link = this.$wpAdminBar.find('#wp-admin-bar-ba-cheetah-frontend-edit-link > a, #wp-admin-bar-ba-cheetah-theme-builder-frontend-edit-link > a');
            $link.on('click', this.onClickPageBuilderToolbarLink.bind(this));

            // Take admin bar links out of the tab order
            $('#wpadminbar a').attr('tabindex', '-1');

            var restart = this.restartEditingSession.bind(this);
            BACheetah.addHook('restartEditingSession', restart);

            BACheetah.addHook('didHideAllLightboxes', this.unmuteToolbar.bind(this));
            BACheetah.addHook('didCancelDiscard', this.unmuteToolbar.bind(this));
            BACheetah.addHook('didEnterRevisionPreview', this.hide.bind(this));
            BACheetah.addHook('didExitRevisionPreview', this.show.bind(this));
            BACheetah.addHook('didPublishLayout', this.onPublish.bind(this));
        },

        /**
        * Handle exit w/o preview
        * @return void
        */
        endEditingSession: function() {
            BACheetah._destroyOverlayEvents();
            BACheetah._removeAllOverlays();
            BACheetah._removeEmptyRowAndColHighlights();
            BACheetah._removeColHighlightGuides();
            BACheetah._unbindEvents();

            $('html').removeClass('ba-cheetah-edit').addClass('ba-cheetah-show-admin-bar');
            $('body').removeClass('ba-cheetah-edit');
            $('#wpadminbar a').attr('tabindex', null );
			$( BACheetah._contentClass ).removeClass( 'ba-cheetah-content-editing' );
            this.hideMainToolbar();
            BACheetah.ContentPanel.hide();
            BACheetahLayout.init();
        },

        /**
        * Re-enter the editor without refresh after having left without refresh.
        * @return void
        */
        restartEditingSession: function(e) {

            BACheetah._initTemplateSelector();
            BACheetah._bindOverlayEvents();
            BACheetah._highlightEmptyCols();
			BACheetah._rebindEvents();

            $('html').addClass('ba-cheetah-edit').removeClass('ba-cheetah-show-admin-bar');
            $('body').addClass('ba-cheetah-edit');
            $('#wpadminbar a').attr('tabindex', '-1');
			$( BACheetah._contentClass ).addClass( 'ba-cheetah-content-editing' );
            this.showMainToolbar();

            e.preventDefault();
        },

        /**
        * Handle re-entering the editor when you click the toolbar button.
        * @return void
        */
        onClickPageBuilderToolbarLink: function(e) {
			BACheetah.triggerHook('restartEditingSession');
            e.preventDefault();
        },

        /**
         * Make admin bar dot green
         *
         * @return void
         */
        onPublish: function() {
            var $dot = this.$wpAdminBar.find('#wp-admin-bar-ba-cheetah-frontend-edit-link > a span');
            $dot.css('color', '#6bc373');
        },

        /**
        * Hides the entire UI.
        * @return void
        */
        hide: function() {
	        if ( $( 'html' ).hasClass( 'ba-cheetah-edit' ) ) {
	            BACheetah._unbindEvents();
	            BACheetah._destroyOverlayEvents();
	            BACheetah._removeAllOverlays();
	            $('html').removeClass('ba-cheetah-edit')
	            $('body').removeClass('admin-bar');
	            this.hideMainToolbar();
	            BACheetah.ContentPanel.hide();
	            BACheetahLayout.init();
	            BACheetah.triggerHook('didHideEditingUI');
	        }
        },

        /**
        * Shows the UI when it's hidden.
        * @return void
        */
        show: function() {
	        if ( ! $( 'html' ).hasClass( 'ba-cheetah-edit' ) ) {
				BACheetah._rebindEvents();
	            BACheetah._bindOverlayEvents();
	            this.showMainToolbar();
	            BACheetahResponsiveEditing._switchTo('default');
	            $('html').addClass('ba-cheetah-edit');
	            $('body').addClass('admin-bar');
	            BACheetah.triggerHook('didShowEditingUI');
	        }
        },

        /**
        * Enter Preview Mode
        * @return void
        */
        beginPreview: function() {

	        // Save existing settings first if any exist. Don't proceed if it fails.
			if ( ! BACheetah._triggerSettingsSave( false, true ) ) {
				return;
			}

            this.isPreviewing = true;
            this.hide();
            $('html').addClass('ba-cheetah-preview');
            $('html, body').removeClass('ba-cheetah-edit');
            BACheetah._removeEmptyRowAndColHighlights();
            BACheetah._removeColHighlightGuides();
            BACheetah.triggerHook('didBeginPreview');

			const mode = $('.ba-navbar-devices .ba-device-active').data('mode')
			BACheetahResponsivePreview.enter(mode);
        },

        /**
        * Leave preview module
        * @return void
        */
        endPreview: function() {
            this.isPreviewing = false;
            this.show();
            BACheetah._highlightEmptyCols();
            BACheetahResponsivePreview.exit();
            $('html').removeClass('ba-cheetah-preview');
            $('html, body').addClass('ba-cheetah-edit');
        },

		publishOnPreview: function() {
			this.endPreview();
			PublishActions.onDoneTriggered()
		},

        /**
        * Toggle in and out of preview mode
        * @return void
        */
        togglePreview: function() {
            if (this.isPreviewing) {
                this.endPreview();
            } else {
                this.beginPreview();
            }
        },

        /**
        * Hide the editor toolbar
        * @return void
        */
        hideMainToolbar: function() {
            this.$mainToolbar.addClass('is-hidden');
            $('html').removeClass('ba-cheetah-is-showing-toolbar');
        },

        /**
        * Show the editor toolbar
        * @return void
        */
        showMainToolbar: function() {
            this.unmuteToolbar();
            this.$mainToolbar.removeClass('is-hidden');
            $('html').addClass('ba-cheetah-is-showing-toolbar');
        },

        /**
        * Handle clicking a responsive device icon while in preview
        * @return void
        */
        onDeviceIconClick: function(e) {
            var mode = $(e.target).data('mode');
            BACheetahResponsivePreview.switchTo(mode);
            BACheetahResponsivePreview._showSize(mode);
			BACheetahResponsivePreview.activatePreviewMenuBarDeviceIcon(mode)
        },

        /**
        * Make toolbar innert
        * @return void
        */
        muteToolbar: function() {
            this.$mainToolbarContent.addClass('is-muted');
            BACheetah._hideTipTips();
        },

        /**
        * Re-activate the toolbar
        * @return void
        */
        unmuteToolbar: function() {
            this.$mainToolbarContent.removeClass('is-muted');
        },
    };

	/**
    * Browser history logic.
    */
	var BrowserState = {

        isEditing: true,

        /**
         * Init the browser state controller
         *
         * @return void
         */
        init: function() {

            if ( history.pushState ) {
                BACheetah.addHook('endEditingSession', this.onLeaveBuilder.bind(this) );
                BACheetah.addHook('restartEditingSession', this.onEnterBuilder.bind(this) );
            }
        },

        /**
         * Handle restarting the edit session.
         *
         * @return void
         */
        onEnterBuilder: function() {
            history.replaceState( {}, document.title, BACheetahConfig.editUrl );
            this.isEditing = true;
        },

        /**
         * Handle exiting the builder.
         *
         * @return void
         */
        onLeaveBuilder: function() {
            history.replaceState( {}, document.title, BACheetahConfig.url );
            this.isEditing = false;
        },
    };

    /**
    * Content Library Search
    */
    var SearchUI = {

        /**
        * Setup the search controller
        * @return void
        */
        init: function() {
            this.$searchBox = $('.ba-cheetah--search');
            this.$searchBoxInput = this.$searchBox.find('input#ba-cheetah-search-input');
            this.$searchBoxClear = this.$searchBox.find('.search-clear');

            this.$searchBoxInput.on('focus', this.onSearchInputFocus.bind(this));
            this.$searchBoxInput.on('blur', this.onSearchInputBlur.bind(this));
            this.$searchBoxInput.on('keyup', this.onSearchTermChange.bind(this));
            this.$searchBoxClear.on('click', this.onSearchTermClearClicked.bind(this));

            this.renderSearchResults = wp.template('ba-cheetah-search-results-panel');
            this.renderNoResults = wp.template('ba-cheetah-search-no-results');

            BACheetah.addHook('didStartDrag', this.hideSearchResults.bind(this));
            BACheetah.addHook('focusSearch', this.focusSearchBox.bind(this));
        },

        focusSearchBox: function() {
            this.$searchBoxInput.trigger('focus');
        },

        /**
        * Fires when focusing on the search field.
        * @return void
        */
        onSearchInputFocus: function() {
            this.$searchBox.addClass('is-expanded');
            BACheetah.triggerHook('didFocusSearchBox');
        },

        /**
        * Fires when blurring out of the search field.
        * @return void
        */
        onSearchInputBlur: function(e) {
            this.$searchBox.removeClass('is-expanded has-text');
            this.$searchBoxInput.val('');
            this.hideSearchResults();
        },

        /**
        * Fires when a key is pressed inside the search field.
        * @return void
        */
        onSearchTermChange: function(e) {
        	if (e.key == 'Escape') {
        		this.$searchBoxInput.blur();
        		return;
        	}
        	BACheetah.triggerHook('didBeginSearch');

            var value = this.$searchBoxInput.val();
            if (value != '') {
                this.$searchBox.addClass('has-text');
            } else {
                this.$searchBox.removeClass('has-text');
            }

            var results = BACheetah.Search.byTerm(value);
            if (results.term != "") {
            	this.showSearchResults(results);
            } else {
            	this.hideSearchResults();
            }
        },

		/**
		* Fires when the clear button is clicked.
        * @return void
		*/
        onSearchTermClearClicked: function() {
            this.$searchBox.removeClass('has-text').addClass('is-expanded');
            this.$searchBoxInput.val('').focus();

            this.hideSearchResults();
        },

        /**
        * Display the found results in the results panel.
        * @var Object - the found results
        * @return void
        */
        showSearchResults: function(data) {

            if (data.total > 0) {
                var $html = $(this.renderSearchResults(data)),
                    $panel = $('.ba-cheetah--search-results-panel');
    			$panel.html($html);

    			BACheetah._initSortables();
            } else {
                var $html = $(this.renderNoResults(data)),
                    $panel = $('.ba-cheetah--search-results-panel');
    			$panel.html($html);
            }
			$('body').addClass('ba-cheetah-search-results-panel-is-showing');
        },

        /**
        * Hide the search results panel
        * @return void
        */
        hideSearchResults: function() {
        	$('body').removeClass('ba-cheetah-search-results-panel-is-showing');
        },
    };

    var RowResize = {

        /**
        * @var {jQuery}
        */
        $row: null,

        /**
        * @var {jQuery}
        */
        $rowContent: null,

        /**
        * @var {Object}
        */
        row: null,

        /**
        * @var {Object}
        */
        drag: {},

        /**
        * Setup basic events for row content overlays
        * @return void
        */
        init: function() {

            if ( this.userCanResize() ) {
                var $layoutContent = $( BACheetah._contentClass );

				$layoutContent.delegate('.ba-cheetah-row', 'mouseenter touchstart', this.onDragHandleHover.bind(this) );
                $layoutContent.delegate('.ba-cheetah-block-row-resize', 'mousedown touchstart', this.onDragHandleDown.bind(this) );
            }
        },

        /**
        * Check if the user is able to resize rows
        *
        * @return bool
        */
        userCanResize: function() {
            return BACheetahConfig.rowResize.userCanResizeRows;
        },

        /**
        * Hover over a row resize drag handle.
        * @return void
        */
        onDragHandleHover: function(e) {

			if (this.drag.isDragging) {
				return
			};

            var $this = this,
			    originalWidth,
            	$handle = $(e.target),
				row = $handle.closest('.ba-cheetah-row'),
				node = row.data('node'),
				form = $( '.ba-cheetah-row-settings[data-node=' + node + ']' ),
				unitField = form.find( '[name=max_content_width_unit]' ),
				unit = 'px';

			$this.onSettingsReady(node, function(settings){

				// Get unit.
				if (unitField.length) {
					unit =  unitField.length;
				} else if ('undefined' !== typeof settings) {
					unit = settings.max_content_width_unit;
				}

				$this.$row = row;
				$this.$rowContent = $this.$row.find('.ba-cheetah-row-content');

	            $this.row = {
	                node: node,
	                form: form,
					unit: unit,
	                isFixedWidth: $this.$row.hasClass('ba-cheetah-row-fixed-width'),
					parentWidth: 'vw' === unit ? $( window ).width() : $this.$row.parent().width(),
	            };

	            $this.drag = {
	                edge: null,
	                isDragging: false,
	                originalPosition: null,
	                originalWidth: null,
	                calculatedWidth: null,
	                operation: null,
	            };

	            if ($this.row.isFixedWidth) {
	                $this.drag.originalWidth = $this.$row.width();
	            } else {
	                $this.drag.originalWidth = $this.$rowContent.width();
	            }

	            $this.dragInit();
			});
        },

		/**
        * Check if BACheetahSettingsConfig.node is available.
        * @return void
        */
		onSettingsReady: function(nodeId, callback) {
			var nodes = 'undefined' !== typeof BACheetahSettingsConfig.nodes ? BACheetahSettingsConfig.nodes : null;

			if (null !== nodes && 'undefined' !== typeof nodes[ nodeId ] ) {
				callback( nodes[ nodeId ] );

				if (null != RowResize._mouseEnterTimeout) {
					clearTimeout( RowResize._mouseEnterTimeout );
					RowResize._mouseEnterTimeout = null;
				}
			} else {
				// If settings is not yet available, check again by timeout.
				clearTimeout( RowResize._mouseEnterTimeout );
				RowResize._mouseEnterTimeout = setTimeout(this.onSettingsReady.bind(this), 350, nodeId, callback);
			}
		},

        /**
        * Handle mouse down on the drag handle
        * @return void
        */
        onDragHandleDown: function() {
            $('body').addClass( 'ba-cheetah-row-resizing' );

			if (null != RowResize._mouseEnterTimeout) {
				clearTimeout( RowResize._mouseEnterTimeout );
				RowResize._mouseEnterTimeout = null;
			}
        },

        /**
        * Setup the draggable handler
        * @return void
        */
        dragInit: function(e) {
            this.$row.find('.ba-cheetah-block-row-resize').draggable( {
				axis 	: 'x',
				start 	: this.dragStart.bind(this),
				drag	: this.dragging.bind(this),
				stop 	: this.dragStop.bind(this)
			});
        },

        /**
        * Handle drag started
        * @var {Event}
        * @var {Object}
        * @return void
        */
        dragStart: function(e, ui) {

	        var body    = $( 'body' ),
	        	$handle = $(ui.helper);

            this.drag.isDragging = true;

            if (this.row.isFixedWidth) {
                this.drag.originalWidth = this.$row.width();
            } else {
                this.drag.originalWidth = this.$rowContent.width();
            }

            if ($handle.hasClass( 'ba-cheetah-block-col-resize-e' )) {
				this.drag.edge = 'e';
                this.$feedback = $handle.find('.ba-cheetah-block-col-resize-feedback-left');
			}
            if ($handle.hasClass( 'ba-cheetah-block-col-resize-w' )) {
				this.drag.edge = 'w';
                this.$feedback = $handle.find('.ba-cheetah-block-col-resize-feedback-right');
			}

	        body.addClass( 'ba-cheetah-row-resizing' );
			BACheetah._colResizing = true;
			BACheetah._destroyOverlayEvents();
			BACheetah._closePanel();
        },

        /**
        * Handle drag
        * @var {Event}
        * @var {Object}
        * @return void
        */
        dragging: function(e, ui) {

            var currentPosition = ui.position.left,
                originalPosition = ui.originalPosition.left,
                originalWidth = this.drag.originalWidth,
                distance = 0,
                edge = this.drag.edge,
                minAllowedWidth = BACheetahConfig.rowResize.minAllowedWidth,
                maxAllowedWidth = BACheetahConfig.rowResize.maxAllowedWidth;

            if (originalPosition !== currentPosition) {

                if ( BACheetahConfig.isRtl ) {
                    edge = ( 'w' == edge ) ? 'e' : 'w'; // Flip the direction
                }

                if (originalPosition > currentPosition) {
                    if (edge === 'w') {
                        this.drag.operation = '+';
                    } else {
                        this.drag.operation = '-';
                    }
                } else {
                    if (edge === 'e') {
                        this.drag.operation = '+';
                    } else {
                        this.drag.operation = '-';
                    }
                }

                distance = Math.abs(originalPosition - currentPosition);

                if (this.drag.operation === '+') {
                    this.drag.calculatedWidth = originalWidth + (distance * 2);
                } else {
                    this.drag.calculatedWidth = originalWidth - (distance * 2);
                }

                if ( false !== minAllowedWidth && this.drag.calculatedWidth < minAllowedWidth ) {
	                this.drag.calculatedWidth = minAllowedWidth;
                }

                if ( false !== maxAllowedWidth && this.drag.calculatedWidth > maxAllowedWidth ) {
	                this.drag.calculatedWidth = maxAllowedWidth;
                }

                if (this.row.isFixedWidth) {
                    this.$row.css('max-width', this.drag.calculatedWidth + 'px');
                }

                this.$rowContent.css('max-width', this.drag.calculatedWidth + 'px');

				if ( 'px' !== this.row.unit ) {
					this.drag.calculatedWidth = Math.round( this.drag.calculatedWidth / this.row.parentWidth * 100 );
				}

                if (!_.isUndefined(this.$feedback)) {
                    this.$feedback.html(this.drag.calculatedWidth + this.row.unit).show();
                }

                if ( this.row.form.length ) {
	                this.row.form.find( '[name=max_content_width]' ).val( this.drag.calculatedWidth );
                }
            }
        },

        /**
        * Handle drag ended
        * @var {Event}
        * @var {Object}
        * @return void
        */
        dragStop: function(e, ui) {
            this.drag.isDragging = false;

            if (!_.isUndefined(this.$feedback)) {
                this.$feedback.hide();
            }

            var data = {
	                action: 'resize_row_content',
	                node: this.row.node,
	                width: this.drag.calculatedWidth
	            },
            	body = $( 'body' );

            BACheetah.ajax(data);
            BACheetah._bindOverlayEvents();
            body.removeClass( 'ba-cheetah-row-resizing' );

            $( '.ba-cheetah-block-overlay' ).each( function() {
	            BACheetah._buildOverlayOverflowMenu( $( this ) );
            } );

            // Set the resizing flag to false with a timeout so other events get the right value.
			setTimeout( function() { BACheetah._colResizing = false; }, 50 );

			BACheetah.triggerHook( 'didResizeRow', {
				rowId	 : this.row.node,
				rowWidth : this.drag.calculatedWidth
			} );
        },
    };


    var Toolbar = {

        /**
        * wp.template id suffix
        */
        templateName: 'ba-cheetah-toolbar',

        /**
        * Initialize the toolbar controller
        *
        * @return void
        */
        init: function() {
            this.template = wp.template(this.templateName);
            this.render();
            this.initTipTips();

            /* "Add Content" Button */
            var $addContentBtn = this.$el.find('.ba-cheetah-content-panel-button');
			$addContentBtn.on('click', BACheetah._togglePanel );

			/*
			this.$el.find('.ba-cheetah-buy-button').on('click', BACheetah._upgradeClicked);
			this.$el.find('.ba-cheetah-upgrade-button').on('click', BACheetah._upgradeClicked);

            this.$el.find('#ba-cheetah-toggle-notifications').on('click', this.onNotificationsButtonClicked.bind(this) );

            BACheetah.addHook('notificationsLoaded', this.onNotificationsLoaded.bind(this));
			*/
        },

        /**
        * Render the toolbar html
        * @param object
        * @return void
        */
        render: function(data) {
            var $html = $(this.template(data));
            this.$el = $html;
            this.el = $html.get(0);
            EditingUI.$mainToolbar = this.$el;
            $('body').prepend($html);
            $('html').addClass('ba-cheetah-is-showing-toolbar');
        },

        /**
        * Add tooltips
        *
        * @return void
        */
        initTipTips: function() {

            // Saving indicator tooltip
			$('.ba-cheetah--saving-indicator').tipTip({
				defaultPosition: 'bottom',
				edgeOffset: 14
			});

			// Publish actions tooltip
			$('.ba-navbar-devices .ba-cheetah-button, .ba-navbar-history .ba-cheetah-button').tipTip({
				defaultPosition: 'bottom',
				edgeOffset: 6
			});

			// Modes tooltips
			$('.ba-cheetah-mode-toggle .ba-cheetah-mode-toggle-label img').tipTip({
				defaultPosition: 'right',
				edgeOffset: 6
			});

			// Wordpress admin
			$('.ba-cheetah--main-menu-panel-view-title a').tipTip({
				defaultPosition: 'right',
				edgeOffset: 6
			});
        },

        onNotificationsButtonClicked: function() {
            BACheetah.triggerHook('toggleNotifications');
        },

        onNotificationsLoaded: function() {
            $('body').removeClass('ba-cheetah-has-new-notifications');

            var data = {
	                action: 'ba_cheetah_notifications',
	                read: true,
	            }
            BACheetah.ajax(data);
        }
    };

    /**
    * Kick off initializers when BACheetah inits.
    */
    $(function() {

        // Render Order matters here
        BACheetah.ContentPanel.init();

        if ( !BACheetahConfig.simpleUi ) {
            BACheetah.MainMenu.init();
        }

        if ( BACheetahConfig.showToolbar ) {
            Toolbar.init();
            BACheetah.ContentPanel.alignPanelArrow();
        } else {
            $('html').addClass('ba-cheetah-no-toolbar');
        }
        // End Render Order

        KeyShortcuts.init();
        EditingUI.init();
        BrowserState.init();
        RowResize.init();
        PublishActions.init();

        BACheetah.triggerHook( 'didInitUI' );
    });

})(jQuery, BACheetah);
