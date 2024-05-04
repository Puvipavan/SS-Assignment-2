( function( $ ) {

	/**
	 * Helper for rendering builder settings forms.
	 *

	 * @class BACheetahSettingsForms
	 */
	BACheetahSettingsForms = {

		/**
		 * Config for the current form that is rendering.
		 *

		 * @property {Object} config
		 */
		config : null,

		/**
		 * Settings cache for the current form so we can compare
		 * later and see if settings have changed before saving.
		 *

		 * @property {Object} settings
		 */
		settings : null,

		/**
		 * A reference to the AJAX object for rendering legacy settings.
		 *

		 * @property {Object} legacyXhr
		 */
		legacyXhr : null,

		/**

		 * @method init
		 */
		init: function() {
			this.bind();
		},

		/**

		 * @method bind
		 */
		bind: function() {
			BACheetah.addHook( 'didDeleteRow', this.closeOnDeleteNode );
			BACheetah.addHook( 'didDeleteColumn', this.closeOnDeleteNode );
			BACheetah.addHook( 'didDeleteModule', this.closeOnDeleteNode );
		},

		/**
		 * Renders a settings form.
		 *

		 * @method render
		 * @param {Object} config
		 * @param {Function} callback
		 */
		render: function( config, callback ) {
			var forms    = BACheetahSettingsConfig.forms,
				modules  = BACheetahSettingsConfig.modules,
				defaults = {
					type      : 'general',
					id        : null,
					nodeId    : null,
					className : '',
					attrs     : '',
					title     : '',
					badges	  : [],
					tabs      : [],
					activeTab : null,
					buttons	  : [],
					settings  : {},
					legacy    : null,
					rules	    : null,
					preview   : null,
					helper 	  : null,
					messages  : null
				};

			// Load settings from the server if we have a node but no settings.
			if ( config.nodeId && ! config.settings ) {
				this.loadNodeSettings( config, callback );
				return;
			}

			// Merge the config into the defaults and make sure we have a callback.
			config   = $.extend( defaults, config );
			callback = undefined === callback ? function(){} : callback;

			// Add the form data to the config.
			if ( ! config.id ) {
				return;
			} else if ( 'general' === config.type && undefined !== forms[ config.id ] ) {
				config = $.extend( true, config, forms[ config.id ] );
			} else if ( 'module' === config.type && undefined !== modules[ config.id ] ) {
				config = $.extend( true, config, modules[ config.id ] );
			} else {
				return;
			}

			// Store the config so it can be accessed by forms.
			this.config = config;

			// Render the lightbox and form.
			if ( this.renderLightbox( config ) ) {

				// Finish rendering.
				if ( config.legacy || ! this.renderLegacySettings( config, callback ) ) {
					this.renderComplete( config, callback );
				} else {
					this.showLightboxLoader();
				}
			}

			// Clear any visible registered panels
            if ( 'Builder' in BA && 'data' in BA.Builder ) {
                const actions = BA.Builder.data.getSystemActions()
                actions.hideCurrentPanel()
            }
		},

		/**
		 * Loads node settings for a form if they do not exist in
		 * the settings config cache.
		 *

		 * @method loadNodeSettings
		 * @param {Object} config
		 * @param {Function} callback
		 * @return {Boolean}
		 */
		loadNodeSettings: function( config, callback ) {
			BACheetah.showAjaxLoader();
			BACheetah.ajax( {
				action 	 : 'get_node_settings',
				node_id  : config.nodeId,
			}, function( response ) {
				config.settings = BACheetah._jsonParse( response );
				BACheetahSettingsConfig.nodes[ config.nodeId ] = config.settings;
				BACheetahSettingsForms.render( config, callback );
				BACheetah.hideAjaxLoader();
			} );
		},

		/**
		 * Renders the lightbox for a settings form.
		 *

		 * @method renderLightbox
		 * @param {Object} config
		 * @return {Boolean}
		 */
		renderLightbox: function( config ) {
			var template 	= wp.template( 'ba-cheetah-settings' ),
				form	 	= BACheetah._lightbox._node.find( 'form.ba-cheetah-settings' ),
				nested   	= $( '.ba-cheetah-lightbox-wrap[data-parent]' ),
				cachedTabId = localStorage.getItem( 'ba-cheetah-settings-tab' );

			// Don't render a node form if it's already open.
			if ( config.nodeId && config.nodeId === form.data( 'node' ) && ! config.lightbox ) {
				BACheetah._focusFirstSettingsControl();
				return false;
			}

			if ( config.hide ) {
				return true;
			}

			// Set the active tab from local storage.
			if ( cachedTabId ) {
				for ( var tabId in config.tabs ) {
					if ( tabId === cachedTabId.replace( 'ba-cheetah-settings-tab-', '' ) ) {
						config.activeTab = tabId;
					}
				}
			}

			// Make sure we have an active tab.
			if ( ! config.activeTab ) {
				config.activeTab = Object.keys( config.tabs ).shift();
			}

			// Render the lightbox and form.
			if ( ! config.lightbox ) {

				// Save existing settings first if any exist. Don't proceed if it fails.
				if ( ! BACheetah._triggerSettingsSave( true, true ) ) {
					return false;
				}

				// Cancel any preview refreshes.
				if ( BACheetah.preview ) {
					BACheetah.preview.cancel();
				}

				BACheetah._closePanel();
				BACheetah._showLightbox( template( config ) );
			} else {
				config.lightbox.setContent( template( config ) );
			}

			return true;
		},

		/**
		 * Initializes a form when rendering is complete.
		 *

		 * @method renderComplete
		 * @param {Object} config
		 * @param {Function} callback
		 */
		renderComplete: function( config, callback ) {
			var form = $( '.ba-cheetah-settings:visible' );

			// This is done on a timeout to keep it from delaying painting
			// of the settings form in the DOM by a fraction of a second.
			setTimeout( function() {
				if ( config.legacy ) {
					this.renderLegacySettingsComplete( config.legacy );
				}

				callback();

				BACheetah._initSettingsForms();

				if ( config.rules ) {
					BACheetah._initSettingsValidation( config.rules, config.messages );
				}
				if ( config.preview ) {
					BACheetah.preview = new BACheetahPreview( config.preview );
				}
				if ( config.helper ) {
					config.helper.init();
				}

				// Cache the original settings.
				if ( ! form.closest( '.ba-cheetah-lightbox-wrap[data-parent]' ).length ) {
					this.settings = BACheetah._getSettingsForChangedCheck( this.config.nodeId, form );
				}

			}.bind( this ), 1 );
		},

		/**
		 * Renders the fields for a section in a settings form.
		 *

		 * @method renderFields
		 * @param {Object} fields
		 * @param {Object} settings
		 * @return {String}
		 */
		renderFields: function( fields, settings ) {
			var template         = wp.template( 'ba-cheetah-settings-row' ),
				html             = '',
				field            = null,
				name             = null,
				value 			 = null,
				isMultiple       = false,
				responsive		 = null,
				responsiveFields = [ 'align', 'border', 'dimension', 'unit', 'photo', 'select', 'typography' ],
				settings		 = ! settings ? this.config.settings : settings,
				globalSettings   = BACheetahConfig.global;

			for ( name in fields ) {

				field 				= fields[ name ];
				isMultiple 		 	= field.multiple ? true : false;
				supportsResponsive 	= $.inArray( field['type'], responsiveFields ) > -1,
				value 			 	= ! _.isUndefined( settings[ name ] ) ? settings[ name ] : '';

				// Make sure this field has a type, if not the sky falls.
				if ( ! field.type ) {
					continue;
				}

				// Use a default value if not set in the settings.
				if ( _.isUndefined( settings[ name ] ) && field['default'] ) {
					value = field['default'];
				}

				// Check to see if responsive is enabled for this field.
				if ( field['responsive'] && globalSettings.responsive_enabled && ! isMultiple && supportsResponsive ) {
					responsive = field['responsive'];
				} else {
					responsive = null;
				}

				html += template( {
					field    		 : field,
					name 			 : name,
					rootName		 : name,
					value 			 : value,
					preview			 : JSON.stringify( field['preview'] ? field['preview'] : { type: 'refresh' } ),
					responsive		 : responsive,
					rowClass		 : field['row_class'] ? ' ' + field['row_class'] : '',
					isMultiple     	 : isMultiple,
					supportsMultiple : 'editor' !== field.type && 'service' !== field.type,
					settings 		 : settings,
					globalSettings   : globalSettings,
					template		 : $( '#tmpl-ba-cheetah-field-' + field.type )
				} );
			}

			return html;
		},

		/**
		 * Renders a single field for a settings form.
		 *

		 * @method renderField
		 * @param {Object} config
		 * @return {String}
		 */
		renderField: function( config ) {
			var template = wp.template( 'ba-cheetah-field' );
			return template( config );
		},

		/**
		 * Renders a custom template for a section.
		 *

		 * @method renderSectionTemplate
		 * @param {Object} section
		 * @param {Object} settings
		 * @return {String}
		 */
		renderSectionTemplate: function( section, settings ) {
			var template = wp.template( section.template.id );

			return template( {
				section  : section,
				settings : settings
			} );
		},

		/**
		 * Renders a custom template for a tab.
		 *

		 * @method renderTabTemplate
		 * @param {Object} tab
		 * @param {Object} settings
		 * @return {String}
		 */
		renderTabTemplate: function( tab, settings ) {
			var template = wp.template( tab.template.id );

			return template( {
				tab  	 : tab,
				settings : settings
			} );
		},

		/**
		 * Renders any legacy custom fields that need to be
		 * rendered on the server with PHP.
		 *

		 * @method renderLegacyField
		 * @param {Object} config
		 * @param {Function} callback
		 * @return {Boolean}
		 */
		renderLegacySettings: function( config, callback ) {
			var form     = $( '.ba-cheetah-settings:visible' ),
				name     = null,
				ele      = null,
				render   = false,
				data 	 = {
					'tabs' 		: [],
					'sections' 	: [],
					'fields' 	: [],
					'settings'	: null,
					'node_id'	: null
				};

			// Fields
			form.find( '.ba-cheetah-legacy-field' ).each( function() {
				ele = $( this );
				data.fields.push( ele.attr( 'data-field' ) );
				BACheetahSettingsForms.showFieldLoader( ele );
				render = true;
			} );

			// Sections
			form.find( '.ba-cheetah-legacy-settings-section' ).each( function() {
				ele = $( this );
				data.sections.push( { tab: ele.attr( 'data-tab' ), section: ele.attr( 'data-section' ) } );
				render = true;
			} );

			// Tabs
			form.find( '.ba-cheetah-legacy-settings-tab' ).each( function() {
				ele = $( this );
				data.tabs.push( ele.attr( 'data-tab' ) );
				render = true;
			} );

			// Send a node ID if we have it, otherwise, send the settings.
			if ( form.attr( 'data-node' ) ) {
				data.node_id = form.attr( 'data-node' );
			} else {
				data.settings = BACheetah._getOriginalSettings( form, true );
			}

			// Cancel an existing legacy AJAX request if we have one.
			if ( this.legacyXhr ) {
				this.legacyXhr.abort();
				this.legacyXhr = null;
			}

			// We still fire the AJAX request even if we don't need to render new
			// tabs, sections or fields just in case any field extras need to render.
			this.legacyXhr = BACheetah.ajax( $.extend( this.getLegacyVars(), {
				action 	 : 'render_legacy_settings',
				data   	 : data,
				form     : form.attr( 'data-form-id' ),
				group    : form.attr( 'data-form-group' ),
				lightbox : form.closest( '.ba-cheetah-lightbox' ).attr( 'data-instance-id' )
			} ), function( response ) {
				BACheetahSettingsForms.renderLegacySettingsComplete( response );
				if ( render ) {
					BACheetahSettingsForms.renderComplete( config, callback );
				}
				BACheetahSettingsForms.hideLightboxLoader();
			} );

			return render;
		},

		/**
		 * Callback for when legacy settings are done rendering.
		 *

		 * @method renderLegacySettingsComplete
		 * @param {String} response
		 */
		renderLegacySettingsComplete: function( response ) {
			var data 	 = 'object' === typeof response ? response : BACheetah._jsonParse( response ),
				lightbox = null,
				form  	 = null,
				name 	 = '',
				field    = null,
				section  = null,
				tab      = null,
				settings = null;

			// Get the form object.
			if ( data.lightbox ) {
				lightbox = $( '.ba-cheetah-lightbox[data-instance-id=' + data.lightbox + ']' );
				form = lightbox.length ? lightbox.find( '.ba-cheetah-settings' ) : null;
			} else {
				form = $( '.ba-cheetah-settings:visible' );
				lightbox = form.closest( '.ba-cheetah-lightbox' );
			}

			// Bail if the form no longer exists.
			if ( ! form || ! form.length ) {
				return;
			}

			// Fields
			for ( name in data.fields ) {
				field = $( '#ba-cheetah-field-' + name ).attr( 'id', '' );
				field.after( data.fields[ name ] ).remove();
			}

			// Field extras
			for ( name in data.extras ) {
				field = $( '#ba-cheetah-field-' + name ).find( '.ba-cheetah-field-control-wrapper' );
				if ( data.extras[ name ].multiple ) {
					field.each( function( i, field_item ) {
						if ( ( i in data.extras[ name ].before ) && ( data.extras[ name ].before[ i ] != "" ) ) {
							$( this ).prepend(
								'<div class="ba-cheetah-form-field-before">' +
								data.extras[ name ].before[ i ] +
								'</div>'
							);
						}
						if ( ( i in data.extras[ name ].after ) && ( data.extras[ name ].after[ i ] != "" ) ) {
							$( this ).append(
								'<div class="ba-cheetah-form-field-after">' +
								data.extras[name].after[ i ] +
								'</div>'
							);
						}
					});
				} else {
					if ( data.extras[ name ].before != "" ) {
						field.prepend(
							'<div class="ba-cheetah-form-field-before">' +
							data.extras[name].before +
							'</div>'
						);
					}
					if ( data.extras[ name ].after != "" ) {
						field.append(
							'<div class="ba-cheetah-form-field-after">' +
							data.extras[name].after +
							'</div>'
						);
					}
				}
			}

			// Sections
			for ( tab in data.sections ) {
				for ( name in data.sections[ tab ] ) {
					section = $( '#ba-cheetah-settings-section-' + name );
					section.html( data.sections[ tab ][ name ] );
				}
			}

			// Tabs
			for ( name in data.tabs ) {
				tab = $( '#ba-cheetah-settings-tab-' + name );
				tab.html( data.tabs[ name ] );
			}

			// Refresh cached settings only if it's the main form.
			if ( ! lightbox.data( 'parent' ) ) {
				this.settings = BACheetah._getSettingsForChangedCheck( this.config.nodeId, form );

				if ( BACheetah.preview ) {
					this.settings = $.extend( this.settings, BACheetah.preview._savedSettings );
					BACheetah.preview._savedSettings = this.settings;
				}
			}

			// Clear the legacy AJAX object.
			this.legacyXhr = null;
		},

		/**
		 * Returns legacy variables that were sent in AJAX requests
		 * when a nested settings form was rendered.
		 *

		 * @method getLegacyVars
		 * @return {Object}
		 */
		getLegacyVars: function() {
			var form     = $( '.ba-cheetah-settings:visible' ),
				lightbox = form.closest( '.ba-cheetah-lightbox' ),
				parent   = lightbox.attr( 'data-parent' ),
				settings = null,
				nodeId   = null,
				vars     = {};

			if ( parent ) {
				parent   = $( '.ba-cheetah-lightbox[data-instance-id=' + parent + ']' );
				form     = parent.find( 'form.ba-cheetah-settings' );
				settings = BACheetah._getSettings( form );
				nodeId   = form.attr( 'data-node' );

				if ( nodeId ) {
					vars.node_id       = nodeId;
					vars.node_settings = settings;
				}
			}

			return vars;
		},

		/**
		 * Checks to see if the main form settings has changed.
		 *

		 * @method settingsHaveChanged
		 * @return {Boolean}
		 */
		settingsHaveChanged: function()
		{
			var form 	 = BACheetah._lightbox._node.find( 'form.ba-cheetah-settings' ),
				settings = BACheetah._getSettings( form ),
				result   = ! this.settings ? false :  JSON.stringify( this.settings ) != JSON.stringify( settings );

			// force save if editing a module with drag and drop inside enabled
			if ( BACheetahConfig.modulesRowInsideEnabled.includes(form.attr('data-type'))) {
				result = true
			}

			return result;
		},

		/**
		 * Closes the settings lightbox when an associated node is deleted.
		 *

		 * @method closeOnDeleteNode
		 * @param {Object} e
		 */
		closeOnDeleteNode: function( e )
		{
			var settings = $( '.ba-cheetah-settings[data-node]' ),
				selector = BACheetah._contentClass + ' .ba-cheetah-node-' + settings.data( 'node' );

			if ( settings.length && ! $( selector ).length ) {
				BACheetahLightbox.closeAll();
			}
		},

		/**
		 * Shows the loader for the current lightbox that is visible.
		 *

		 * @method showLightboxLoader
		 */
		showLightboxLoader: function() {
			$( '.ba-cheetah-settings:visible' ).append( '<div class="ba-cheetah-loading"></div>' );
		},

		/**
		 * Hides the loader for the current lightbox that is visible.
		 *

		 * @method hideLightboxLoader
		 */
		hideLightboxLoader: function( ele ) {
			$( '.ba-cheetah-settings:visible .ba-cheetah-loading' ).remove();
		},

		/**
		 * Shows the loader for a field that is loading.
		 *

		 * @method showFieldLoader
		 * @param {Object} ele
		 */
		showFieldLoader: function( ele ) {
			var wrapper = ele.closest( '.ba-cheetah-field-control' ).find( '.ba-cheetah-field-control-wrapper' );
			wrapper.hide().after( '<div class="ba-cheetah-field-loader">' + BACheetahStrings.fieldLoading + '</div>' );
		},

		/**
		 * Hides the loader for a field that is loading.
		 *

		 * @method hideFieldLoader
		 * @param {Object} ele
		 */
		hideFieldLoader: function( ele ) {
			var field   = ele.closest( '.ba-cheetah-field' ),
				wrapper = ele.closest( '.ba-cheetah-field-control' ).find( '.ba-cheetah-field-control-wrapper' );

			wrapper.show();
			field.find( '.ba-cheetah-field-loader' ).remove();
		}
	};

	/**
	 * Helper for working with settings forms config.
	 *

	 * @class BACheetahSettingsConfig
	 */
	BACheetahSettingsConfig = 'undefined' === typeof BACheetahSettingsConfig ? {} : BACheetahSettingsConfig;

	$.extend( BACheetahSettingsConfig, {

		/**

		 * @method init
		 */
		init: function() {

			// Save settings
			BACheetah.addHook( 'didSaveNodeSettings', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didSaveNodeSettingsComplete', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didSaveLayoutSettingsComplete', this.updateOnSaveLayoutSettings.bind( this ) );
			BACheetah.addHook( 'didSaveGlobalSettingsComplete', this.updateOnSaveGlobalSettings.bind( this ) );
			BACheetah.addHook( 'didSaveGlobalSettingsComplete', this.reload );

			// Add nodes
			BACheetah.addHook( 'didAddRow', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didAddColumnGroup', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didAddColumn', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didAddModule', this.updateOnNodeEvent.bind( this ) );

			// Delete nodes
			BACheetah.addHook( 'didDeleteRow', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didDeleteColumn', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didDeleteModule', this.updateOnNodeEvent.bind( this ) );

			// Duplicate nodes
			BACheetah.addHook( 'didDuplicateRow', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didDuplicateColumn', this.updateOnNodeEvent.bind( this ) );
			BACheetah.addHook( 'didDuplicateModule', this.updateOnNodeEvent.bind( this ) );

			// Resize nodes
			BACheetah.addHook( 'didResizeRow', this.updateOnRowResize.bind( this ) );
			BACheetah.addHook( 'didResizeColumn', this.updateOnColumnResize.bind( this ) );

			// Reset node widths
			BACheetah.addHook( 'didResetRowWidth', this.updateOnResetRowWidth.bind( this ) );
			BACheetah.addHook( 'didResetColumnWidths', this.updateOnResetColumnWidths.bind( this ) );

			// Apply templates
			BACheetah.addHook( 'didApplyTemplateComplete', this.updateOnApplyTemplate.bind( this ) );
			BACheetah.addHook( 'didApplyRowTemplateComplete', this.updateOnApplyTemplate.bind( this ) );
			BACheetah.addHook( 'didApplyColTemplateComplete', this.updateOnApplyTemplate.bind( this ) );
			BACheetah.addHook( 'didSaveGlobalNodeTemplate', this.updateOnApplyTemplate.bind( this ) );

			// Revisions and history
			BACheetah.addHook( 'didRestoreRevisionComplete', this.updateOnApplyTemplate.bind( this ) );
			BACheetah.addHook( 'didRestoreHistoryComplete', this.updateOnHistoryRestored.bind( this ) );
		},

		/**
		 * Reloads the core settings config from the server.
		 *

		 * @method reload
		 */
		reload: function() {
			var url = BACheetahConfig.editUrl + '&ba_cheetah_load_settings_config=core';

			$( 'script[src*="ba_cheetah_load_settings_config=core"]' ).remove();
			$( 'head' ).append( '<script src="' + url + '"></script>' );
		},

		/**
		 * Updates the global settings when they are saved.
		 *

		 * @method updateOnSaveGlobalSettings
		 * @param {Object} e
		 * @param {Object} settings
		 */
		updateOnSaveGlobalSettings: function( e, settings ) {
			this.settings.global = settings;
			BACheetahConfig.global = settings;
		},

		/**
		 * Updates the layout settings when they are saved.
		 *

		 * @method updateOnSaveLayoutSettings
		 * @param {Object} e
		 * @param {Object} settings
		 */
		updateOnSaveLayoutSettings: function( e, settings ) {
			this.settings.layout = settings;
		},

		/**
		 * Updates the node config when an event is triggered.
		 *

		 * @method updateOnNodeEvent
		 */
		updateOnNodeEvent: function() {

			var event = arguments[0];

			if ( event.namespace.indexOf( 'didAdd' ) > -1 ) {
				this.addNode( 'object' === typeof arguments[1] ? arguments[1].nodeId : arguments[1] );
			} else if ( event.namespace.indexOf( 'didSaveNodeSettings' ) > -1 ) {
				this.updateNode( arguments[1].nodeId, arguments[1].settings );
			} else if ( event.namespace.indexOf( 'didDelete' ) > -1 ) {
				this.deleteNodes( 'object' === typeof arguments[1] ? arguments[1].nodeId : arguments[1] );
			} else if ( event.namespace.indexOf( 'didDuplicate' ) > -1 ) {
				this.duplicateNode( arguments[1].oldNodeId, arguments[1].newNodeId );
			}
		},

		/**
		 * Updates the node config when a row is resized.
		 *

		 * @method updateOnRowResize
		 * @param {Object} e
		 * @param {Object} data
		 */
		updateOnRowResize: function( e, data ) {
			this.nodes[ data.rowId ].max_content_width = data.rowWidth;
		},

		/**
		 * Updates the node config when a row width is reset.
		 *

		 * @method updateOnResetRowWidth
		 * @param {Object} e
		 * @param {String} nodeId
		 */
		updateOnResetRowWidth: function( e, nodeId ) {
			this.nodes[ nodeId ].max_content_width = '';
		},

		/**
		 * Updates the node config when a column is resized.
		 *

		 * @method updateOnColumnResize
		 * @param {Object} e
		 * @param {Object} data
		 */
		updateOnColumnResize: function( e, data ) {
			this.nodes[ data.colId ].size = data.colWidth;
			this.nodes[ data.siblingId ].size = data.siblingWidth;
		},

		/**
		 * Updates the node config when column widths are reset.
		 *

		 * @method updateOnResetColumnWidths
		 * @param {Object} e
		 * @param {Object} data
		 */
		updateOnResetColumnWidths: function( e, data ) {
			var self = this;

			data.cols.each( function() {
				var col   = $( this ),
					colId = col.attr( 'data-node' );

				if ( self.nodes[ colId ] ) {
					self.nodes[ colId ].size = parseFloat( col[0].style.width );
				}
			} );
		},

		/**
		 * Updates the node config when a template is applied.
		 *

		 * @method updateOnApplyTemplate
		 * @param {Object} e
		 * @param {Object} config
		 */
		updateOnApplyTemplate: function( e, config ) {
			this.nodes = config.nodes;
			this.attachments = config.attachments;
		},

		/**
		 * Updates the node config when a history state is rendered.
		 *

		 * @method updateOnHistoryRestored
		 * @param {Object} e
		 * @param {Object} data
		 */
		updateOnHistoryRestored: function( e, data ) {
			this.nodes = data.config.nodes
			this.attachments = data.config.attachments
			this.settings.layout = data.settings.layout
			this.settings.global = data.settings.global
			BACheetahConfig.global = data.settings.global
		},

		/**
		 * Adds the settings config for a new node.
		 *

		 * @method addNode
		 * @param {String} nodeId
		 * @param {Object} settings
		 */
		addNode: function( nodeId, settings ) {

			var node 		= $( '.ba-cheetah-node-' + nodeId ),
				isRow 		= node.hasClass( 'ba-cheetah-row' ),
				isCol 		= node.hasClass( 'ba-cheetah-col' ),
				isColGroup 	= node.hasClass( 'ba-cheetah-col-group' ),
				isModule 	= node.hasClass( 'ba-cheetah-module' ),
				self 		= this;

			if ( this.nodes[ nodeId ] ) {
				return;
			}

			if ( ! settings ) {

				if ( isRow ) {
					settings = $.extend( {}, this.defaults.row );
				} else if ( isCol ) {
					settings = $.extend( {}, this.defaults.column );
				} else if ( isModule ) {
					settings = $.extend( {}, this.defaults.modules[ node.attr( 'data-type' ) ] );
				}

				if ( isRow || isColGroup ) {
					node.find( '.ba-cheetah-col' ).each( function() {
						var col = $( this ), defaults = $.extend( {}, self.defaults.column );
						defaults.size = parseFloat( col[0].style.width );
						self.addNode( col.attr( 'data-node' ), defaults );
					} );
				} else if ( isModule ) {
					self.addNode( node.closest( '.ba-cheetah-row' ).attr( 'data-node' ) );
					self.addNode( node.closest( '.ba-cheetah-col' ).attr( 'data-node' ) );
					self.updateOnResetColumnWidths( null, {
						cols: node.closest( '.ba-cheetah-col-group' ).find( '> .ba-cheetah-col' )
					} );
				}
			}

			if ( settings ) {
				this.nodes[ nodeId ] = settings;
			}
		},

		/**
		 * Update the settings config for a node.
		 *

		 * @method updateNode
		 * @param {String} nodeId
		 * @param {Object} settings
		 */
		updateNode: function( nodeId, settings ) {
			var node  = $( '.ba-cheetah-node-' + nodeId ),
				self  = this;

			if ( node.hasClass( 'ba-cheetah-col' ) ) {
				node.closest( '.ba-cheetah-col-group' ).find( '> .ba-cheetah-col' ).each( function() {
					var col = $( this ), colId = col.attr( 'data-node' );
					self.nodes[ colId ].size 				= parseFloat( col[0].style.width );
					self.nodes[ colId ].equal_height 		= settings.equal_height;
					self.nodes[ colId ].content_alignment 	= settings.content_alignment;
					self.nodes[ colId ].responsive_order 	= settings.responsive_order;
				} );
			}

			this.nodes[ nodeId ] = settings;
		},

		/**
		 * Duplicates settings config for a node.
		 *

		 * @method duplicateNode
		 * @param {String} oldNode
		 * @param {String} newNode
		 */
		duplicateNode: function( oldNodeId, newNodeId ) {

			var newNode  = $( '.ba-cheetah-node-' + newNodeId ),
				newNodes = newNode.find( '[data-node]' ),
				oldNode  = $( '.ba-cheetah-node-' + oldNodeId ),
				oldNodes = oldNode.find( '[data-node]' ),
				self     = this;

			this.nodes[ newNodeId ] = this.nodes[ oldNodeId ];

			newNodes.each( function( i ) {

				oldNodeId = oldNodes.eq( i ).attr( 'data-node' );
				newNodeId = $( this ).attr( 'data-node' );

				if ( self.nodes[ oldNodeId ] ) {
					self.nodes[ newNodeId ] = self.nodes[ oldNodeId ];
				}
			} );
		},

		/**
		 * Deletes any nodes that are no longer in the DOM.
		 *

		 * @method deleteNodes
		 */
		deleteNodes: function() {

			var nodeId  = '',
				content = $( BACheetah._contentClass ).html();

			for ( nodeId in this.nodes ) {
				if ( content.indexOf( nodeId ) === -1 ) {
					this.nodes[ nodeId ] = null;
					delete this.nodes[ nodeId ];
				}
			}
		}
	} );

	$( function() {
		BACheetahSettingsConfig.init();
		BACheetahSettingsForms.init();
	} );

} )( jQuery );
