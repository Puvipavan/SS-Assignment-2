<script type="text/html" id="tmpl-ba-cheetah-node-template-block">
	<span class="ba-cheetah-block ba-cheetah-block-saved-{{data.type}}<# if ( data.global ) { #> ba-cheetah-block-global<# } #>" data-id="{{data.id}}">
		<span class="ba-cheetah-block-content">
			<div class="ba-cheetah-block-title">{{data.name}}</div>
			<# if ( data.global ) { #>
			<div class="ba-cheetah-badge ba-cheetah-badge-global">
				<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
			</div>
			<# } #>
			<# if ( data.global && BACheetahConfig.userCanEditGlobalTemplates ) { #>
			<span class="ba-cheetah-node-template-actions">
				<a class="ba-cheetah-node-template-edit" href="{{data.link}}" target="_blank">
					<i class="fas fa-wrench"></i>
				</a>
				<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
					<i class="fas fa-times"></i>
				</a>
			</span>
			<# } #>
		</span>
	</span>
</script>
<!-- #tmpl-ba-cheetah-node-template-block -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-saved-view">
	<div class="ba-cheetah-content-panel-saved-view">
		<#
		var templates = data.queryResults.library.template.items;
		var rows = _.filter(templates, function(item) {
			return item.content === 'row';
		});
		var columns = _.filter(templates, function(item) {
			return item.content === 'column';
		});
		var modules = _.filter(templates, function(item) {
			return item.content === 'module';
		});
		#>
		<?php if ( ! BACheetahModel::is_post_user_template( 'row' ) && ! BACheetahModel::is_post_user_template( 'column' ) ) : ?>
		<div id="ba-cheetah-blocks-saved-rows" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">
			<div class="ba-cheetah-settings-section">
				<div class="ba-cheetah-settings-section-header">
					<button class="ba-cheetah-settings-title">
						<span class="ba-cheetah-blocks-section-title"><?php _e( 'Saved Rows', 'ba-cheetah' ); ?></span>
						<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
					</button>
				</div>
				<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-rows">
					<# if (rows.length === 0) { #>
						<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved rows found.', 'ba-cheetah' ); ?></span>
					<# } else { #>
						<# for( var i in rows) {
							var row = rows[i];
							image = row.image,
							hasImage = image && !image.endsWith('blank.jpg'),
							hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
							var globalClass = row.isGlobal ? ' ba-cheetah-block-global' : '';
						#>
						<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-row{{globalClass}}" data-id="{{row.id}}">
							<span class="ba-cheetah-block-content">
								<div class="ba-cheetah-block-title" title="{{row.name}}">{{row.name}}</div>
								<# if (row.isGlobal) { #>
								<div class="ba-cheetah-badge ba-cheetah-badge-global">
									<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
								</div>
								<# } #>
								<span class="ba-cheetah-node-template-actions">
									<a class="ba-cheetah-node-template-edit" href="{{row.link}}" target="_blank">
									<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
									</a>
									<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
										<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
									</a>
								</span>
							</span>
						</span>
						<# } #>
					<# } #>
				</div>
			</div>
		</div>
		<?php endif; ?>

		
		<?php if ( ! BACheetahModel::is_post_user_template( 'column' ) ) : ?>
		<div id="ba-cheetah-blocks-saved-columns" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">
			<div class="ba-cheetah-settings-section">
				<div class="ba-cheetah-settings-section-header">
					<button class="ba-cheetah-settings-title">
					<span class="ba-cheetah-blocks-section-title"><?php _e( 'Saved Columns', 'ba-cheetah' ); ?></span>
						<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
					</button>
				</div>
				<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-columns">
					<# if (columns.length === 0) { #>
						<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved columns found.', 'ba-cheetah' ); ?></span>
					<# } else { #>
						<# for( var i in columns) {
							var column = columns[i];
							image = column.image,
							hasImage = image && !image.endsWith('blank.jpg'),
							hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
							var globalClass = column.isGlobal ? ' ba-cheetah-block-global' : '';
						#>
						<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-column{{globalClass}} {{hasImageClass}}" data-id="{{column.id}}">
							<span class="ba-cheetah-block-content">
								<div class="ba-cheetah-block-title" title="{{column.name}}">{{column.name}}</div>
								<# if (column.isGlobal) { #>
								<div class="ba-cheetah-badge ba-cheetah-badge-global">
									<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
								</div>
								<# } #>
								<span class="ba-cheetah-node-template-actions">
									<a class="ba-cheetah-node-template-edit" href="{{column.link}}" target="_blank">
										<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
									</a>
									<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
										<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
									</a>
								</span>
							</span>
						</span>
						<# } #>
					<# } #>
				</div>
			</div>			
		</div>
		<?php endif; ?>


		<div id="ba-cheetah-blocks-saved-modules" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">

			<div class="ba-cheetah-settings-section">
				<div class="ba-cheetah-settings-section-header">
					<button class="ba-cheetah-settings-title">
					<span class="ba-cheetah-blocks-section-title"><?php _e( 'Saved Elements', 'ba-cheetah' ); ?></span>
						<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
					</button>
				</div>
				<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-modules">
					<# if (modules.length === 0) { #>
					<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved elements found.', 'ba-cheetah' ); ?></span>
					<# } else { #>
						<# for( var i in modules) {
							var module = modules[i];
							image = module.image,
							hasImage = image && !image.endsWith('blank.jpg'),
							hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
							var globalClass = module.isGlobal ? ' ba-cheetah-block-global' : '';
						#>
						<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-module{{globalClass}} {{hasImageClass}}" data-id="{{module.id}}">
							<span class="ba-cheetah-block-content">
								<div class="ba-cheetah-block-title" title="{{module.name}}">{{module.name}}</div>
								<# if (module.isGlobal) { #>
								<div class="ba-cheetah-badge ba-cheetah-badge-global">
									<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
								</div>
								<# } #>
								<span class="ba-cheetah-node-template-actions">
									<a class="ba-cheetah-node-template-edit" href="{{module.link}}" target="_blank">
										<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
									</a>
									<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
										<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
									</a>
								</span>
							</span>
						</span>
						<# } #>
					<# } #>
				</div>
			</div>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-saved-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-saved-modules">
	<#
	var modules = data.queryResults.library.template.items;
	#>
	<div id="ba-cheetah-blocks-saved-modules" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">
		<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-modules">
		<# if (modules.length === 0) { #>
		<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved elements found.', 'ba-cheetah' ); ?></span>
		<# } else { #>
			<# for( var i in modules) {
				var module = modules[i],
					image = module.image,
					globalClass = module.isGlobal ? ' ba-cheetah-block-global' : '',
					image = module.image,
					hasImage = image && !image.endsWith( 'blank.jpg' ),
					hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
			#>
			<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-module {{globalClass}} {{hasImageClass}}" data-id="{{module.id}}">
				<span class="ba-cheetah-block-content">
					<div class="ba-cheetah-block-title" title="{{module.name}}">{{module.name}}</div>
					<# if (module.isGlobal) { #>
					<div class="ba-cheetah-badge ba-cheetah-badge-global">
						<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
					</div>
					<# } #>
					<span class="ba-cheetah-node-template-actions">
						<a class="ba-cheetah-node-template-edit" href="{{module.link}}" target="_blank">
							<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
						</a>
						<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
							<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
						</a>
					</span>
				</span>
			</span>
			<# } #>
		<# } #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-saved-modules -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-saved-columns">
	<#
	var columns = data.queryResults.library.template.items;
	#>
	<div id="ba-cheetah-blocks-saved-columns" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">
		<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-columns">
		<# if (columns.length === 0) { #>
		<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved columns found.', 'ba-cheetah' ); ?></span>
		<# } else { #>
			<# for( var i in columns) {
				var column = columns[i],
					image = column.image,
					globalClass = column.isGlobal ? ' ba-cheetah-block-global' : '',
					image = column.image,
					hasImage = image && !image.endsWith( 'blank.jpg' ),
					hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
			#>
			<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-column {{globalClass}} {{hasImageClass}}" data-id="{{column.id}}">
				<span class="ba-cheetah-block-content">
					<div class="ba-cheetah-block-title" title="{{column.name}}">{{column.name}}</div>
					<# if (column.isGlobal) { #>
					<div class="ba-cheetah-badge ba-cheetah-badge-global">
						<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
					</div>
					<# } #>
					<span class="ba-cheetah-node-template-actions">
						<a class="ba-cheetah-node-template-edit" href="{{column.link}}" target="_blank">
							<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
						</a>
						<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
							<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
						</a>
					</span>
				</span>
			</span>
			<# } #>
		<# } #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-saved-columns -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-saved-rows">
	<#
	var rows = data.queryResults.library.template.items;
	#>
	<div id="ba-cheetah-blocks-saved-rows" class="ba-cheetah-blocks-section ba-cheetah-blocks-node-template">
		<div class="ba-cheetah-blocks-section-content ba-cheetah-saved-rows">
		<# if (rows.length === 0) { #>
			<span class="ba-cheetah-block-no-node-templates"><?php _e( 'No saved rows found.', 'ba-cheetah' ); ?></span>
		<# } else { #>
			<# for( var i in rows) {
				var row = rows[i],
					globalClass = row.isGlobal ? 'ba-cheetah-block-global' : '',
					image = row.image,
					hasImage = image && !image.endsWith( 'blank.jpg' ),
					hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
			#>
			<span class="ba-cheetah-block ba-cheetah-block-saved ba-cheetah-block-saved-row {{globalClass}} {{hasImageClass}}" data-id="{{row.id}}">
				<span class="ba-cheetah-block-content">
					<!--
					<# if (image && !image.endsWith('blank.jpg')) { #>
					<div class="ba-cheetah-block-thumbnail" style="background-image:url({{image}})"></div>
					<# } #>
					-->
					<div class="ba-cheetah-block-title" title="{{row.name}}">{{row.name}}</div>
					<# if (row.isGlobal) { #>
					<div class="ba-cheetah-badge ba-cheetah-badge-global">
						<?php _ex( 'Global', 'Indicator for global node templates.', 'ba-cheetah' ); ?>
					</div>
					<# } #>
					<span class="ba-cheetah-node-template-actions">
						<a class="ba-cheetah-node-template-edit" href="{{row.link}}" target="_blank">
							<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
						</a>
						<a class="ba-cheetah-node-template-delete" href="javascript:void(0);">
							<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
						</a>
					</span>
				</span>
			</span>
			<# } #>
		<# } #>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-saved-rows -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-saved-templates">
	<div class="ba-cheetah-user-templates">
		<div class="ba-cheetah--user-templates-section-content">
			<div class="ba-cheetah-user-template" data-id="blank">
				<div class="ba-cheetah-user-template-thumbnail">
					<div class="ba-cheetah--template-thumbnail"></div>
				</div>
				<span class="ba-cheetah-user-template-name"><?php _ex( 'Blank', 'Template name.', 'ba-cheetah' ); ?></span>
			</div>
		</div>
		<#

		var queryResults = data.queryResults.library.template,
			templates 			= null,
			categories			= {},
			showCategoryName	= false,
			categoryName 		= '';

		if ( _.isUndefined( queryResults.categorized ) ) {
			for ( var slug in queryResults.items[0].category ) {
				categoryName = queryResults.items[0].category[ slug ];
				break;
			}
			categories[ categoryName ] = queryResults.items;
		} else {
			categories 			= data.queryResults.library.template.categorized,
			showCategoryName 	= true !== ( Object.keys( categories ).length <= 1 );
		}

		for ( var categoryHandle in categories ) {

			templates = categories[ categoryHandle ]

			if ( showCategoryName ) { #>
			<div class="ba-cheetah--user-templates-section-name">{{categoryHandle}}</div>
			<# } #>
			<div class="ba-cheetah--user-templates-section-content">
				<# for( var i in templates ) {
					var template = templates[ i ];
				#>
				<div class="ba-cheetah-user-template" data-id="{{template.postId}}">
					<div class="ba-cheetah-user-template-thumbnail">
						<div class="ba-cheetah--template-thumbnail" style="background-image:url({{template.image}})"></div>
					</div>
					<span class="ba-cheetah-user-template-name">{{template.name}}</span>
					<div class="ba-cheetah-user-template-actions">
						<a class="ba-cheetah-user-template-edit" href="{{template.link}}">
							<svg width="14.238" height="14.238" fill="var(--ba-cheetah-primary)"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
						</a>
						<a class="ba-cheetah-user-template-delete" href="javascript:void(0);" onclick="return false;">
							<svg width="15.429" height="18"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
						</a>
					</div>
				</div>
				<# } /* #>
				<div class="ba-cheetah--save-new-user-template">
					<div class="ba-cheetah-user-template-thumbnail">
						<div class="ba-cheetah--template-thumbnail"></div>
					</div>
					<div class="ba-cheetah-save-control">
						<input name="template-name" placeholder="<?php _e( 'Save New Template', 'ba-cheetah' ); ?>" type="text">
						<button class="ba-cheetah-button"><?php _e( 'Save', 'ba-cheetah' ); ?></button>
						<input type="hidden" name="template-category" value="{{categoryHandle}}" >
					</div>
				</div>
				<div class="ba-cheetah-save-control-mask"></div>
				<# */ #>
			</div>

		<# } #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-saved-templates -->
