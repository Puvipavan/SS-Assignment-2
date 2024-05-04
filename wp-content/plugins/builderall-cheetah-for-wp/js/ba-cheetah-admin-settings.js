(function($){

	/**
	 * Helper class for dealing with the builder's admin
	 * settings page.
	 *
	 * @class BACheetahAdminSettings

	 */
	BACheetahAdminSettings = {

		/**
		 * An instance of wp.media used for uploading icons.
		 *

		 * @access private
		 * @property {Object} _iconUploader
		 */
		_iconUploader: null,

		/**
		 * Initializes the builder's admin settings page.
		 *

		 * @method init
		 */
		init: function()
		{
			this._bind();
			this._maybeShowWelcome();
			this._initNav();
			this._initNetworkOverrides();
			this._initLicenseSettings();
			this._initMultiSelects();
			this._initUserAccessSelects();
			this._initUserAccessNetworkOverrides();
			this._templatesOverrideChange();
			this._iconPro();
			this._alphaSettings();
		},

		/**
		 * Binds events for the builder's admin settings page.
		 *

		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$('.ba-cheetah-settings-nav a').on('click', BACheetahAdminSettings._navClicked);
			$('.ba-cheetah-override-ms-cb').on('click', BACheetahAdminSettings._overrideCheckboxClicked);
			$('.ba-cheetah-ua-override-ms-cb').on('click', BACheetahAdminSettings._overrideUserAccessCheckboxClicked);
			$('.ba-cheetah-module-all-cb').on('click', BACheetahAdminSettings._moduleAllCheckboxClicked);
			$('.ba-cheetah-module-cb').on('click', BACheetahAdminSettings._moduleCheckboxClicked);
			$('input[name=ba-cheetah-templates-override]').on('keyup click', BACheetahAdminSettings._templatesOverrideChange);
			$('input[name=ba-cheetah-upload-icon]').on('click', BACheetahAdminSettings._showIconUploader);
			$('.ba-cheetah-delete-icon-set').on('click', BACheetahAdminSettings._deleteCustomIconSet);
			$('#uninstall-form').on('submit', BACheetahAdminSettings._uninstallFormSubmit);
			$( '.ba-cheetah-settings-form .dashicons-editor-help' ).tipTip();
			$( '.subscription-form .subscribe-button' ).on( 'click', BACheetahAdminSettings._welcomeSubscribe);
		},

		_welcomeSubscribe: function()
		{
			form  = $('.subscription-form')
			var error = form.find('.error')
			var spinner = form.find('.dashicons')

			if( error.css('display') != 'none' ) {
				error.hide()
			}

			name   = form.find( '.input-group-field.name').val()
			email  = form.find( '.input-group-field.email').val()
			nonce  = form.find( '#_wpnonce' ).val()

			if ( ! email || ! name ) {
				error.html('Please enter required fields').fadeIn()
				return false;
			}
			spinner.css('color', '#fff')
			spinner.addClass('spin')

			data = {
				'action'  : 'ba_cheetah_welcome_submit',
				'name'    : name,
				'email'   : email,
				'_wpnonce': nonce
			}

			console.log(data)

			$.post(ajaxurl, data, function(response) {
				spinner.css( 'color', '#0a3c4b' );
				spinner.removeClass( 'spin' );
				if( response.success ) {
					$('.subscribe-button').hide()
					$('.input-group').remove()
					spinner.remove()
					$( error ).html( '<h2>' + response.data.message + '</h2>' ).fadeIn()
				} else {
					$( error ).html(response.data.message).fadeIn()
				}
			});
		},

		/**
		 * Show the welcome page after the license has been saved.
		 *

		 * @access private
		 * @method _maybeShowWelcome
		 */
		_maybeShowWelcome: function()
		{
			var onLicense    = 'integrations' == window.location.hash.replace( '#', '' ),
				isUpdated    = $( '.wrap .updated' ).length,
				watermark    = $( '.wrap .watermark' ).length,
				licenseError = $( '.ba-cheetah-license-error' ).length;
				
			if ( onLicense && isUpdated && ! licenseError && ! watermark) {
				window.location.hash = 'welcome';
			}
		},

		/**
		 * Initializes the nav for the builder's admin settings page.
		 *

		 * @access private
		 * @method _initNav
		 */
		_initNav: function()
		{
			var links  = $('.ba-cheetah-settings-nav a'),
				hash   = window.location.hash,
				active = hash === '' ? [] : links.filter('[href~="'+ hash +'"]');

			$('a.ba-cheetah-active').removeClass('ba-cheetah-active');
			$('.ba-cheetah-settings-form').hide();

			if(hash === '' || active.length === 0) {
				active = links.eq(0);
			}

			active.addClass('ba-cheetah-active');
			$('#ba-cheetah-'+ active.attr('href').split('#').pop() +'-form').fadeIn();
		},

		/**
		 * Fires when a nav item is clicked.
		 *

		 * @access private
		 * @method _navClicked
		 */
		_navClicked: function()
		{
			if($(this).attr('href').indexOf('#') > -1) {
				$('a.ba-cheetah-active').removeClass('ba-cheetah-active');
				$('.ba-cheetah-settings-form').hide();
				$(this).addClass('ba-cheetah-active');
				$('#ba-cheetah-'+ $(this).attr('href').split('#').pop() +'-form').fadeIn();
			}
		},

		/**
		 * Initializes the checkboxes for overriding network settings.
		 *

		 * @access private
		 * @method _initNetworkOverrides
		 */
		_initNetworkOverrides: function()
		{
			$('.ba-cheetah-override-ms-cb').each(BACheetahAdminSettings._initNetworkOverride);
		},

		/**
		 * Initializes a checkbox for overriding network settings.
		 *

		 * @access private
		 * @method _initNetworkOverride
		 */
		_initNetworkOverride: function()
		{
			var cb      = $(this),
				content = cb.closest('.ba-cheetah-settings-form').find('.ba-cheetah-settings-form-content');

			if(this.checked) {
				content.show();
			}
			else {
				content.hide();
			}
		},

		/**
		 * Fired when a network override checkbox is clicked.
		 *

		 * @access private
		 * @method _overrideCheckboxClicked
		 */
		_overrideCheckboxClicked: function()
		{
			var cb      = $(this),
				content = cb.closest('.ba-cheetah-settings-form').find('.ba-cheetah-settings-form-content');

			if(this.checked) {
				content.show();
			}
			else {
				content.hide();
			}
		},

		/**
		 * Initializes custom multi-selects.
		 *

		 * @access private
		 * @method _initMultiSelects
		 */
		_initMultiSelects: function()
		{
			$( 'select[multiple]' ).multiselect( {
				selectAll: true,
				texts: {
					deselectAll     : BACheetahAdminSettingsStrings.deselectAll,
					noneSelected    : BACheetahAdminSettingsStrings.noneSelected,
					placeholder     : BACheetahAdminSettingsStrings.select,
					selectAll       : BACheetahAdminSettingsStrings.selectAll,
					selectedOptions : BACheetahAdminSettingsStrings.selected
				}
			} );
		},

		/**
		 * Initializes user access select options.
		 *

		 * @access private
		 * @method _initUserAccessSelects
		 */
		_initUserAccessSelects: function()
		{
			var config  = BACheetahAdminSettingsConfig,
				options = null,
				role    = null,
				select  = null,
				key     = null,
				hidden  = null;

			$( '.ba-cheetah-user-access-select' ).each( function() {

				options = [];
				select  = $( this );
				key     = select.attr( 'name' ).replace( 'ba_cheetah_user_access[', '' ).replace( '][]', '' );

				for( role in config.roles ) {
					options.push( {
						name    : config.roles[ role ],
						value   : role,
						checked : 'undefined' == typeof config.userAccess[ key ] ? false : config.userAccess[ key ][ role ]
					} );
				}

				select.multiselect( 'loadOptions', options );
			} );
		},

		/**
		 * Initializes the checkboxes for overriding user access
		 * network settings.
		 *

		 * @access private
		 * @method _initUserAccessNetworkOverrides
		 */
		_initUserAccessNetworkOverrides: function()
		{
			$('.ba-cheetah-ua-override-ms-cb').each(BACheetahAdminSettings._initUserAccessNetworkOverride);
		},

		/**
		 * Initializes a checkbox for overriding user access
		 * network settings.
		 *

		 * @access private
		 * @method _initUserAccessNetworkOverride
		 */
		_initUserAccessNetworkOverride: function()
		{
			var cb     = $(this),
				select = cb.closest('.ba-cheetah-user-access-setting').find('.ms-options-wrap');

			if(this.checked) {
				select.show();
			}
			else {
				select.hide();
			}
		},

		/**
		 * Fired when a network override checkbox is clicked.
		 *

		 * @access private
		 * @method _overrideCheckboxClicked
		 */
		_overrideUserAccessCheckboxClicked: function()
		{
			var cb     = $(this),
				select = cb.closest('.ba-cheetah-user-access-setting').find('.ms-options-wrap');

			if(this.checked) {
				select.show();
			}
			else {
				select.hide();
			}
		},

		/**
		 * Fires when the "all" checkbox in the list of enabled
		 * modules is clicked.
		 *

		 * @access private
		 * @method _moduleAllCheckboxClicked
		 */
		_moduleAllCheckboxClicked: function()
		{
			if($(this).is(':checked')) {
				$('.ba-cheetah-module-cb').prop('checked', true);
			} else {
				$('.ba-cheetah-module-cb').prop('checked', false);
			}
		},

		/**
		 * Fires when a checkbox in the list of enabled
		 * modules is clicked.
		 *

		 * @access private
		 * @method _moduleCheckboxClicked
		 */
		_moduleCheckboxClicked: function()
		{
			var allChecked = true;

			$('.ba-cheetah-module-cb').each(function() {

				if(!$(this).is(':checked')) {
					allChecked = false;
				}
			});

			if(allChecked) {
				$('.ba-cheetah-module-all-cb').prop('checked', true);
			}
			else {
				$('.ba-cheetah-module-all-cb').prop('checked', false);
			}
		},

		/**

		 * @access private
		 * @method _initLicenseSettings
		 */
		_initLicenseSettings: function()
		{
			$( '.ba-cheetah-new-license-form .button' ).on( 'click', BACheetahAdminSettings._newLicenseButtonClick );
		},

		/**

		 * @access private
		 * @method _newLicenseButtonClick
		 */
		_newLicenseButtonClick: function()
		{
			$( '.ba-cheetah-new-license-form' ).hide();
			$( '.ba-cheetah-license-form' ).show();
		},

		/**
		 * Fires when the templates override setting is changed.
		 *

		 * @access private
		 * @method _templatesOverrideChange
		 */
		_templatesOverrideChange: function()
		{
			var input 			= $('input[name=ba-cheetah-templates-override]'),
				val 			= input.val(),
				overrideNodes 	= $( '.ba-cheetah-templates-override-nodes' ),
				toggle 			= false;

			if ( 'checkbox' == input.attr( 'type' ) ) {
				toggle = input.is( ':checked' );
			}
			else {
				toggle = '' !== val;
			}

			overrideNodes.toggle( toggle );
		},

		/**
		 * Shows the media library lightbox for uploading icons.
		 *

		 * @access private
		 * @method _showIconUploader
		 */
		_showIconUploader: function()
		{
			if(BACheetahAdminSettings._iconUploader === null) {
				BACheetahAdminSettings._iconUploader = wp.media({
					title: BACheetahAdminSettingsStrings.selectFile,
					button: { text: BACheetahAdminSettingsStrings.selectFile },
					library : { type : 'application/zip' },
					multiple: false
				});
			}

			BACheetahAdminSettings._iconUploader.once('select', $.proxy(BACheetahAdminSettings._iconFileSelected, this));
			BACheetahAdminSettings._iconUploader.open();
		},

		/**
		 * Callback for when an icon set file is selected.
		 *

		 * @access private
		 * @method _iconFileSelected
		 */
		_iconFileSelected: function()
		{
			var file = BACheetahAdminSettings._iconUploader.state().get('selection').first().toJSON();

			$( 'input[name=ba-cheetah-new-icon-set]' ).val( file.id );
			$( '#icons-form' ).submit();
		},

		/**
		 * Fires when the delete link for an icon set is clicked.
		 *

		 * @access private
		 * @method _deleteCustomIconSet
		 */
		_deleteCustomIconSet: function()
		{
			var set = $( this ).data( 'set' );

			$( 'input[name=ba-cheetah-delete-icon-set]' ).val( set );
			$( '#icons-form' ).submit();
		},

		/**
		 * Fires when the uninstall button is clicked.
		 *

		 * @access private
		 * @method _uninstallFormSubmit
		 * @return {Boolean}
		 */
		_uninstallFormSubmit: function()
		{
			var result = prompt(BACheetahAdminSettingsStrings.uninstall.replace(/&quot;/g, '"'), '');

			if(result == 'uninstall') {
				return true;
			}

			return false;
		},
		_iconPro: function() {
			form = $('#icons-form')
			checkbox = form.find('input[name=ba-cheetah-enable-fa-pro]').prop('checked')
			light = form.find('input[value=font-awesome-5-light]').parent()
			duo   = form.find('input[value=font-awesome-5-duotone]').parent()

			if ( true === checkbox ) {
				light.css('font-weight', '800')
			//	light.css('color', '#0E5A71')
				duo.css('font-weight', '800')
			//	duo.css('color', '#0E5A71')
			}

		},
		_alphaSettings: function() {
			form = $('#beta-form');

			form.find('.alpha-checkbox').on('click', function(){
				console.log('checked?')
				if ( true === $(this).prop('checked') ) {
					if ( confirm( 'Are you sure you want to enable Alpha releases?') ) {
						// do nothing
					} else {
						$(this).prop('checked',false);
					}

				}
			})
		}

	};

	/* Initializes the builder's admin settings. */
	$(function(){
		BACheetahAdminSettings.init();
	});

})(jQuery);
