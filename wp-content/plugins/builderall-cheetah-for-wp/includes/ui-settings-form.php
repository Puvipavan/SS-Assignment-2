<script type="text/html" id="tmpl-ba-cheetah-settings">
	<form class="ba-cheetah-settings {{data.className}}" {{{data.attrs}}} data-form-id="{{data.id}}" data-form-group="{{data.type}}" onsubmit="return false;">
		<div class="ba-cheetah-lightbox-header-wrap">
			<!--
			<div class="ba-cheetah-panel-drag-handle">
				<svg xmlns="http://www.w3.org/2000/svg" width="10" height="16" viewBox="0 0 10 16">
					<path d="M11,18a2,2,0,1,1-2-2A2.006,2.006,0,0,1,11,18ZM9,10a2,2,0,1,0,2,2A2.006,2.006,0,0,0,9,10ZM9,4a2,2,0,1,0,2,2A2.006,2.006,0,0,0,9,4Zm6,4a2,2,0,1,0-2-2A2.006,2.006,0,0,0,15,8Zm0,2a2,2,0,1,0,2,2A2.006,2.006,0,0,0,15,10Zm0,6a2,2,0,1,0,2,2A2.006,2.006,0,0,0,15,16Z" transform="translate(-7 -4)" fill="#0080fc"/>
				</svg>
			</div>
			-->
			<div class="ba-cheetah-lightbox-header">
				<h1>
					{{{data.title}}}
					<# for ( var i = 0; i < data.badges.length; i++ ) { #>
					<span class="ba-cheetah-badge ba-cheetah-badge-{{data.badges[ i ]}}">{{data.badges[ i ]}}</span>
					<# } #>
				</h1>
				<!--
				<div class="ba-cheetah-lightbox-controls">
					<i class="ba-cheetah-lightbox-resize-toggle <# var className = BACheetahLightbox.getResizableControlClass(); #>{{className}}"></i>
				</div>
				-->
			</div>
			<# if ( data.tabs && Object.keys( data.tabs ).length > 1 ) { #>
			<div class="ba-cheetah-settings-tabs">
				<# var i = 0; for ( var tabId in data.tabs ) { #>
				<# var tab = data.tabs[ tabId ]; #>
				<a href="#ba-cheetah-settings-tab-{{tabId}}"<# if ( tabId === data.activeTab ) { #> class="ba-cheetah-active"<# } #>>{{{tab.title}}}</a>
				<# i++; } #>
				<button class="ba-cheetah-settings-tabs-more">
					<svg width="5.005" height="18.795"><use xlink:href="#ba-cheetah-icon--three-dots-vertical"></use></svg>
				</button>
			</div>
			<div class="ba-cheetah-settings-tabs-overflow-click-mask"></div>
			<div class="ba-cheetah-settings-tabs-overflow-menu"></div>
			<# } #>
		</div>

		<div class="ba-cheetah-lightbox-content-wrap">
			<div class="ba-cheetah-settings-fields ba-cheetah-nanoscroller">
				<div class="ba-cheetah-nanoscroller-content">
					<# if ( data.tabs && Object.keys( data.tabs ).length > 0 ) { #>
						<# var i = 0; for ( var tabId in data.tabs ) { #>
						<# var tab = data.tabs[ tabId ]; #>
						<div id="ba-cheetah-settings-tab-{{tabId}}" class="ba-cheetah-settings-tab<# if ( tabId === data.activeTab ) { #> ba-cheetah-active<# } #>">

							<# if ( tab.file ) { #>
								<div class="ba-cheetah-legacy-settings-tab" data-tab="{{tabId}}"></div>
							<# } else if ( tab.template ) { #>
								<# tab = BACheetahSettingsForms.renderTabTemplate( tab, data.settings ); #>
								{{{tab}}}
							<# } else { #>

								<# if ( tab.description ) { #>
								<p class="ba-cheetah-settings-tab-description">{{{tab.description}}}</p>
								<# } #>

								<# for ( var sectionId in tab.sections ) { #>
								<# var section = tab.sections[ sectionId ]; #>
								<#
									var isCollapsed = false;
									if ( typeof section.collapsed !== 'undefined' ) {
										isCollapsed = section.collapsed
									}
									var collapsedClass = isCollapsed ? 'ba-cheetah-settings-section-collapsed' : '';
									var arrowOrbadgePro = "";

									var isEnable = ( !_.isUndefined(section.enabled) && section.enabled === false) ? false : true;
								#>
								<div id="ba-cheetah-settings-section-{{sectionId}}" class="ba-cheetah-settings-section {{collapsedClass}}">

									<# if ( section.file ) { #>
										<div class="ba-cheetah-legacy-settings-section" data-section="{{sectionId}}" data-tab="{{tabId}}"></div>
									<# } else if ( section.template ) { #>
										<# section = BACheetahSettingsForms.renderSectionTemplate( section, data.settings ); #>
										{{{section}}}
									<# } else {

										if ( section.title ) {
											var msgRestrict = '';
											if( !isEnable) {
												msgRestrict = ' onclick="BACheetah._showProMessage(\''+section.title+'\');"';
												arrowOrbadgePro = '<span class="ba-cheetah-pro-badge">PRO</span>';
											} else {
												arrowOrbadgePro = '<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>';
											} #>
										
											<div class="ba-cheetah-settings-section-header"{{{msgRestrict}}}>
												<button class="ba-cheetah-settings-title">
													{{{section.title}}}
													{{{arrowOrbadgePro}}}
												</button>
											</div>
									<# }

										if( isEnable) { #>

											<div class="ba-cheetah-settings-section-content">
												<# if ( section.description ) { #>
												<p class="ba-cheetah-settings-description">{{{section.description}}}</p>
												<# } #>

												<table class="ba-cheetah-form-table">
												<# var fields = BACheetahSettingsForms.renderFields( section.fields, data.settings ); #>
												{{{fields}}}
												</table>
											</div>

								<# 		}
											
										} #>

								</div>
								<# } #>

							<# } #>

						</div>
						<# i++; } #>
					<# } #>
				</div>
			</div>
			<div class="ba-cheetah-lightbox-footer">
				<button class="ba-cheetah-settings-save ba-cheetah-button ba-cheetah-button-success ba-cheetah-button-large" href="javascript:void(0);" onclick="return false;" title="{{BACheetahStrings.save}}">{{BACheetahStrings.save}}</button>
				<# if ( jQuery.inArray( 'save-as', data.buttons ) > -1 ) { #>
				<button class="ba-cheetah-settings-save-as ba-cheetah-button ba-cheetah-button-primary ba-cheetah-button-large" href="javascript:void(0);" onclick="return false;" title="{{BACheetahStrings.saveAs}}">{{BACheetahStrings.saveAs}}</button>
				<# } #>
				<button class="ba-cheetah-settings-cancel ba-cheetah-button ba-cheetah-button-large has-background" href="javascript:void(0);" onclick="return false;"  title="{{BACheetahStrings.cancel}}">{{BACheetahStrings.cancel}}</button>
			</div>
		</div>
		<# var settings = BACheetah._getSettingsJSONForHTML( data.settings ); #>
		<input class="ba-cheetah-settings-json" type="hidden" value='{{settings}}' />
	</form>
</script>
