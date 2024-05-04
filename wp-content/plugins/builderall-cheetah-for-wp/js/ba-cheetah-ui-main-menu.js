(function($, BACheetah) {

    /**
    * Base prototype for views in the menu
    */
    var PanelView = BACheetahExtendableObject.create({

        templateName: "ba-cheetah-main-menu-panel-view",

        name: "Untitled View",

        isShowing: false,

        isRootView: false,

        items: {},

        /**
        * Initialize the view
        *
        * @return void
        */
        init: function() {
            this.template = wp.template(this.templateName);
        },

        /**
        * Render the view
        *
        * @return String
        */
        render: function() {
            return this.template(this);
        },

        /**
        * Setup Events
        *
        * @return void
        */
        bindEvents: function() {
            this.$items = this.$el.find('.ba-cheetah--menu-item');
        },

        /**
        * Make this the current view
        *
        * @return void
        */
        show: function() {
            this.$el.addClass('is-showing');
			this.isShowing = true;
        },

        /**
        * Resign the active view
        *
        * @return void
        */
        hide: function() {
            this.$el.removeClass('is-showing');
			this.isShowing = false;
        },

        /**
        * Handle transitioning the view in
        *
        * @return void
        */
        transitionIn: function(reverse) {
            requestAnimationFrame( this.show.bind(this) );
        },

        /**
        * Handle transition away from the view
        *
        * @return void
        */
        transitionOut: function(reverse) {
            this.hide();
        },
    });

    /**
    * Menu Panel
    */
    var MainMenuPanel = BACheetahExtendableObject.create({

        templateName: 'ba-cheetah-main-menu-panel',

        template: null,

        menu: null,

        views: {},

        viewNavigationStack: [],

        isShowing: false,

        shouldShowTabs: false,

        /**
        * Setup and render the menu
        *
        * @return void
        */
        init: function() {

            // Render Panel
            this.template = wp.template(this.templateName);
            $('body').prepend( this.template(this) );
            this.$el = $('.ba-cheetah--main-menu-panel');
            this.$el.find('.ba-cheetah--main-menu-panel-views').html('');

            // Render Views
            for (var key in BACheetahConfig.mainMenu) {
                this.renderPanel( key );
            }

            // Event Listeners
            $('body').on('click', '.ba-cheetah--main-menu-panel .pop-view', this.goToPreviousView.bind(this));

            this.$tabs = this.$el.find('.ba-cheetah--tabs > span'); /* on purpose */
            this.$tabs.on('click', this.onItemClick.bind(this));

            $('body').on('click', '.ba-cheetah-bar-title-icon', this.toggle.bind(this));

            var hide = this.hide.bind(this);
			var show = this.show.bind(this);
            BACheetah.addHook('didShowPublishActions', hide);
            BACheetah.addHook('didBeginSearch', hide);
            BACheetah.addHook('didBeginPreview', hide);
            BACheetah.addHook('didShowContentPanel', hide);
            BACheetah.addHook('endEditingSession', hide);
            BACheetah.addHook('didFocusSearchBox', hide);
            BACheetah.addHook('didEnterRevisionPreview', hide);
            BACheetah.addHook('didFailSettingsSave', hide);
            BACheetah.addHook('showKeyboardShortcuts', hide);
			BACheetah.addHook('openMainMenu', show);
			BACheetah.addHook('closeMainMenu', hide)

            this.$mask = $('.ba-cheetah--main-menu-panel-mask');
            this.$mask.on('click', hide);

            Tools.init();
            Help.init();
        },

        /**
        * Render the panel
        *
        * @param String key
        * @return void
        */
        renderPanel: function( key ) {
	        var data, view, $html;
			var current = this.views[ key ];

            data = BACheetahConfig.mainMenu[ key ];
            data.handle = key;
            view = PanelView.create( data );
            view.init();
            $html = $( view.render() );
            view.$el = $html;
            $( '.ba-cheetah--main-menu-panel-views' ).append( $html );
            view.bindEvents();
            view.$el.find( '.ba-cheetah--menu-item' ).on( 'click', this.onItemClick.bind( this ) );

			if ( 'undefined' !== typeof current ) {
				current.$el.remove();
				if ( current.isShowing ) {
					this.currentView = view
					view.show();
				}
			}

            if ( view.isRootView ) {
	            this.rootView = view;
	            this.currentView = view;
	        }

            this.views[ key ] = view;
        },

        /**
        * Show the menu
        *
        * @return void
        */
        show: function() {
            if (this.isShowing) return;
            this.$el.addClass('is-showing');
            $('.ba-cheetah-bar-title-icon').addClass('is-showing-menu');
            this.currentView.transitionIn();
            this.isShowing = true;
            this.$mask.show();
            BACheetah.triggerHook('didOpenMainMenu');
        },

        /**
        * Hide the menu
        *
        * @return void
        */
        hide: function() {
            if (!this.isShowing) return;
            this.$el.removeClass('is-showing');
            $('.ba-cheetah-bar-title-icon').removeClass('is-showing-menu');
            this.isShowing = false;
            this.resetViews();
            this.$mask.hide();
        },

        /**
        * Toggle show/hide the menu
        *
        * @return void
        */
        toggle: function() {
            if (this.isShowing) {
                this.hide();
            } else {
                this.show();
            }
        },

        /**
        * Handle item click
        *
        * @param {Event} e
        * @return void
        */
        onItemClick: function(e) {
            var $item = $(e.currentTarget),
                type = $item.data('type');

            switch (type) {
                case "view":
                    var name = $item.data('view');
                    this.goToView(name);
                    break;
                case "event":
                    var hook = $item.data('event');
                    BACheetah.triggerHook(hook, $item);
                    break;
                case "link":
                    // follow link
                    break;
            }
        },

        /**
        * Display a specific view
        *
        * @param String name
        * @return void
        */
        goToView: function(name) {

            var currentView = this.currentView;
            var newView = this.views[name];

            currentView.transitionOut();
            newView.transitionIn();
            this.currentView = newView;
            this.viewNavigationStack.push(currentView);
        },

        /**
        * Pop a view off the stack
        *
        * @return void
        */
        goToPreviousView: function() {
            var currentView = this.currentView;
            var newView = this.viewNavigationStack.pop();
            currentView.transitionOut(true);
            newView.transitionIn(true);
            this.currentView = newView;
            $('.ba-cheetah-bar-title-caret').focus();
        },

        /**
        * Reset to root view
        *
        * @return void
        */
        resetViews: function() {
            if (this.currentView != this.rootView ) {
                this.currentView.hide();
                this.rootView.show();
                this.currentView = this.rootView;
                this.viewNavigationStack = [];
            }
        },
    });

    BACheetah.MainMenu = MainMenuPanel;

    /**
    * Handle tools menu actions
    */
    var Tools = {

        /**
        * Setup listeners for tools actions
        * @return void
        */
        init: function() {
            BACheetah.addHook('saveTemplate', this.saveTemplate.bind(this));
            BACheetah.addHook('saveCoreTemplate', this.saveCoreTemplate.bind(this));
            BACheetah.addHook('duplicateLayout', this.duplicateLayout.bind(this));
            BACheetah.addHook('showLayoutSettings', this.showLayoutSettings.bind(this));
            BACheetah.addHook('showGlobalSettings', this.showGlobalSettings.bind(this));
            BACheetah.addHook('showPageSettings', this.showPageSettings.bind(this));
			BACheetah.addHook('startTour', this.startTour.bind(this));
            BACheetah.addHook('toggleUISkin', this.toggleUISkin.bind(this));
            BACheetah.addHook('clearLayoutCache', this.clearLayoutCache.bind(this));

            $('input[name=cheetah-mode-checkbox]').change(this.toogleCheetahMode.bind(this));

            // Show Keyboard Shortcuts
            if ( 'BA' in window && 'Builder' in BA ) {
                var actions = BA.Builder.data.getSystemActions();

                BACheetah.addHook( 'showKeyboardShortcuts', function() {
                    actions.setShouldShowShortcuts( true );
                });
            }
        },

        /**
        * Show the save template lightbox
        * @return void
        */
        saveTemplate: function() {
            BACheetah._saveUserTemplateClicked();
            MainMenuPanel.hide();
        },

        /**
        * Show save core template lightbox
        * @return void
        */
        saveCoreTemplate: function() {
            BACheetahCoreTemplatesAdmin._saveClicked();
            MainMenuPanel.hide();
        },

        /**
        * Trigger duplicate layout
        * @return void
        */
        duplicateLayout: function() {
            BACheetah._duplicateLayoutClicked();
            MainMenuPanel.hide();
        },

        /**
        * Show the global settings lightbox
        * @return void
        */
        showGlobalSettings: function() {
            BACheetah._globalSettingsClicked();
            MainMenuPanel.hide();
        },

        /**
        * Show the page layout options
        * @return void
        */
        showPageSettings: function () {
            BACheetah._pageSettingsClicked();
            MainMenuPanel.hide();
        },

		startTour: function() {
			BACheetah._showTourLightbox()
			MainMenuPanel.hide();
		},

        /**
        * Show the layout js/css lightbox
        * @return void
        */
        showLayoutSettings: function() {
            BACheetah._layoutSettingsClicked();
            MainMenuPanel.hide();
        },

        /**
        * Clear cache for this layout
        * @return void
        */
        clearLayoutCache: function() {
            BACheetah.ajax({
                action: 'clear_cache'
            }, function() {
                location.href = BACheetahConfig.editUrl;
            });
            BACheetah.showAjaxLoader();
            MainMenuPanel.hide();
        },

        toogleCheetahMode: function(e) {
            const value = e.target.value;

            if(confirm(BACheetahStrings.changeLayoutModeMessage)) {
                BACheetah.ajax({
                    action: 'set_cheetah_mode',
                    canvas_enabled: value == 'canvas'
                }, function () {
                    location.href = BACheetahConfig.editUrl;
                });
                BACheetah.showAjaxLoader();
                MainMenuPanel.hide();
            } else {
				$('input[name=cheetah-mode-checkbox]').val([value == 'canvas' ? 'default' : 'canvas']);
            }
        },

        /**
        * Toggle between the UI Skins
        * @var Event
        * @return void
        */
        toggleUISkin: function(e) {
            var $item = $('a[data-event="toggleUISkin"]');
            if ($('body').hasClass('ba-cheetah-ui-skin--light')) {
                var fromSkin = 'light';
                var toSkin = 'dark';
            }
            if ($('body').hasClass('ba-cheetah-ui-skin--dark')) {
                var fromSkin = 'dark';
                var toSkin = 'light';
            }
            $('body').removeClass('ba-cheetah-ui-skin--' + fromSkin ).addClass('ba-cheetah-ui-skin--' + toSkin);

            if ( 'Builder' in BA && 'data' in BA.Builder ) {
                var actions = BA.Builder.data.getSystemActions()
                actions.setColorScheme( toSkin )
            }

            // ajax save
            BACheetah.ajax({
                action: 'save_ui_skin',
                skin_name: toSkin,
            });
        },

    }

    var Help = {

        /**
        * Init the help controller
        * @return void
        */
        init: function() {
            BACheetah.addHook('beginTour', this.onStartTourClicked.bind(this));
        },

        /**
        * Handle tour item click
        *
        * @return void
        */
        onStartTourClicked: function() {
            BACheetahTour.start();
            MainMenuPanel.hide();
        },
    }

})(jQuery, BACheetah);
