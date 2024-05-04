<script type="text/html" id="tmpl-ba-cheetah-row-overlay">
	<div class="ba-cheetah-row-overlay ba-cheetah-block-overlay<# if ( data.global ) { #> ba-cheetah-block-overlay-global<# } #>">
		<div class="ba-cheetah-block-overlay-header">
			<div class="ba-cheetah-block-overlay-actions">
				<# if ( data.global && ! BACheetahConfig.userCanEditGlobalTemplates ) { #>
				<i class="fas fa-lock ba-cheetah-tip" title="<?php _e( 'Locked', 'ba-cheetah' ); ?>"></i>
				<# } else { #>

				<?php if ( ! BACheetahModel::is_post_user_template( 'row' ) && ! $simple_ui ) : ?>
					<# if (!data.inside) { #>
					<span class="ba-over-action ba-cheetah-block-move ba-cheetah-tip" title="<?php _e( 'Move', 'ba-cheetah' ); ?>">
						<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
					</span>
					<# } #>
				<?php endif; ?>

				<span class="ba-over-action ba-cheetah-block-settings ba-cheetah-tip" title="<?php _e( 'Row Settings', 'ba-cheetah' ); ?>">
					<svg width="18.505" height="18"><use xlink:href="#ba-cheetah-icon--gear"></use></svg>
				</span>

				<?php if ( ! BACheetahModel::is_post_user_template( 'row' ) && ! $simple_ui ) : ?>
					<# if (!data.inside) { #>
						<span class="ba-over-action ba-cheetah-block-copy ba-cheetah-tip" title="<?php _e( 'Duplicate', 'ba-cheetah' ); ?>">
							<svg width="14.205" height="18"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>
						</span>
					<# } #>
				<?php endif; ?>

				<!-- Doing this is easy, you don't need a menu
				<?php if ( ! $simple_ui ) : ?>
				<# if ( ! data.global || ( data.global && 'row' == BACheetahConfig.userTemplateType ) ) { #>
				<span class="ba-cheetah-has-submenu">
					<span class="ba-over-action ba-cheetah-block-col-settings ba-cheetah-tip" title="<?php _e( 'Row Actions', 'ba-cheetah' ); ?>">
						<svg width="5.005" height="18.795"><use xlink:href="#ba-cheetah-icon--three-dots-vertical"></use></svg>
					</span>
					<ul class="ba-cheetah-submenu ba-cheetah-block-col-submenu">
						<li><a class="ba-cheetah-block-col-reset" href="javascript:void(0);"><?php _e( 'Reset Column Widths', 'ba-cheetah' ); ?></a></li>
						<li><a class="ba-cheetah-block-row-reset" href="javascript:void(0);"><?php _e( 'Reset Row Width', 'ba-cheetah' ); ?></a></li>
					</ul>
				</span>
				<# } #>
				<?php endif; ?>
				-->
				<?php if ( ! BACheetahModel::is_post_user_template( 'row' ) && ! $simple_ui ) : ?>
					<# if (!data.inside) { #>
						<span class="ba-over-action ba-cheetah-block-remove ba-cheetah-tip" title="<?php _e( 'Remove', 'ba-cheetah' ); ?>">
							<svg width="15.429" height="18" fill="#ed6666"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
						</span>
					<# } #>
				<?php endif; ?>
				<# } #>
			</div>
			<div class="ba-cheetah-clear"></div>
		</div>
		<# if ( data.hasRules ) { #>
		<i class="fas fa-eye ba-cheetah-tip ba-cheetah-block-has-rules {{data.rulesTypeRow}}" title="<?php _e( 'This row has visibility rules', 'ba-cheetah' ); ?>: {{data.rulesTextRow}}"></i>
		<# } #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-row-overlay -->

<script type="text/html" id="tmpl-ba-cheetah-col-overlay">

	<div class="ba-cheetah-col-overlay ba-cheetah-block-overlay<# if ( data.global ) { #> ba-cheetah-block-overlay-global<# } #>">
		<div class="ba-cheetah-block-overlay-header">
			<div class="ba-cheetah-block-overlay-actions">
				<# if ( data.global && ! BACheetahConfig.userCanEditGlobalTemplates ) { #>
				<i class="fas fa-lock ba-cheetah-tip" title="<?php _e( 'Locked', 'ba-cheetah' ); ?>"></i>
				<# } else { #>
				<?php if ( ! $simple_ui ) : ?>
				<# if ( ! data.isRootCol ) { #>
					<span class="ba-over-action ba-cheetah-block-move ba-cheetah-tip" title="<?php _e( 'Move', 'ba-cheetah' ); ?>"></i>
						<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
					</span>
					<# if ( ( ! data.hasParentCol && data.numCols < 12 ) || ( data.hasParentCol && data.numCols < 4 ) ) { #>
					<span class="ba-over-action ba-cheetah-block-copy ba-cheetah-block-col-copy ba-cheetah-tip" title="<?php _e( 'Duplicate', 'ba-cheetah' ); ?>">
						<svg width="14.205" height="18"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>
					</span>
					<# } #>
				<# } #>
				<?php endif; ?>
				<span class="ba-cheetah-has-submenu">
					<span class="ba-over-action ba-cheetah-block-settings ba-cheetah-tip" title="<?php _e( 'Edit Column', 'ba-cheetah' ); ?>">
						<svg width="5.005" height="18.795"><use xlink:href="#ba-cheetah-icon--three-dots-vertical"></use></svg>
					</span>
					<?php if ( ! $simple_ui ) : ?>
					<# if ( ! data.global || ( data.global && BACheetahConfig.userTemplateType ) ) { #>
					<ul class="ba-cheetah-submenu ba-cheetah-block-col-submenu">
						<li>
							<a class="ba-cheetah-block-col-edit" href="javascript:void(0);">
								<svg width="20" height="21.798"><use xlink:href="#ba-cheetah-icon--site-settings"></use></svg>
								<?php _e( 'Column Settings', 'ba-cheetah' ); ?>
							</a>
						</li>
						<!-- Doing this is easy, you don't need a menu
						<# if ( data.numCols > 1 || ( data.hasParentCol && data.numParentCols > 1 ) ) { #>
						<li><a class="ba-cheetah-block-col-reset" href="javascript:void(0);"><?php _e( 'Reset Column Widths', 'ba-cheetah' ); ?></a></li>
						<# } #>
						<# if ( data.rowIsFixedWidth ) { #>
						<li><a class="ba-cheetah-block-row-reset" href="javascript:void(0);"><?php _e( 'Reset Row Width', 'ba-cheetah' ); ?></a></li>
						<# } #>
						-->
						<# if ( data.hasParentCol ) { #>
						<li class="ba-cheetah-submenu-sep"><div></div></li>
						<# if ( 'column' != BACheetahConfig.userTemplateType ) { #>
						<li>
							<a class="ba-cheetah-block-col-move-parent" href="javascript:void(0);">
								<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
								<?php _e( 'Move Parent', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# } #>
						<li>
							<a class="ba-cheetah-block-col-edit-parent" href="javascript:void(0);">
								<svg width="18.505" height="18"><use xlink:href="#ba-cheetah-icon--gear"></use></svg>
								<?php _e( 'Parent Settings', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# } #>
					</ul>
					<# } #>
					<?php endif; ?>
				</span>
				<?php if ( ! $simple_ui ) : ?>
				<# if ( ! data.isRootCol ) { #>
					<span class="ba-over-action ba-cheetah-block-remove ba-cheetah-tip" title="<?php _e( 'Remove', 'ba-cheetah' ); ?>">
						<svg width="15.429" height="18" fill="#ed6666"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
					</span>
				<# } #>
				<?php endif; ?>
				<# } #>
			</div>
			<div class="ba-cheetah-clear"></div>
		</div>
		<# if ( data.hasRules ) { #>
		<i class="fas fa-eye ba-cheetah-tip ba-cheetah-block-has-rules" title="<?php _e( 'This column has visibility rules.', 'ba-cheetah' ); ?>"></i>
		<# } #>
		<?php if ( ! $simple_ui ) : ?>
		<# if ( ! data.groupLoading ) { #>
			<# if ( ! data.first || ( data.hasParentCol && data.first && ! data.parentFirst ) ) { #>
			<div class="ba-cheetah-block-col-resize ba-cheetah-block-col-resize-w<# if ( data.hasParentCol && data.first && ! data.parentFirst ) { #> ba-cheetah-block-col-resize-parent<# } #>">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
			<# if ( ! data.last || ( data.hasParentCol && data.last && ! data.parentLast ) ) { #>
			<div class="ba-cheetah-block-col-resize ba-cheetah-block-col-resize-e<# if ( data.hasParentCol && data.last && ! data.parentLast ) { #> ba-cheetah-block-col-resize-parent<# } #>">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
		<# } #>
		<# if ( data.userCanResizeRows ) { #>
			<# if ( ( ( data.first && ! data.hasParentCol ) || ( data.first && data.parentFirst ) ) && data.rowIsFixedWidth ) { #>
			<div class="ba-cheetah-block-row-resize ba-cheetah-block-col-resize ba-cheetah-block-col-resize-w">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
			<# if ( ( ( data.last && ! data.hasParentCol ) || ( data.last && data.parentLast ) ) && data.rowIsFixedWidth ) { #>
			<div class=" ba-cheetah-block-row-resize ba-cheetah-block-col-resize ba-cheetah-block-col-resize-e">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
		<# } #>
		<?php endif; ?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-col-overlay -->

<script type="text/html" id="tmpl-ba-cheetah-module-overlay">
	<div class=" ba-cheetah-module-overlay <# if (data.hasRowInside) { #> ba-cheetah-module-inside-overlay <# } #> ba-cheetah-block-overlay<# if ( data.global ) { #> ba-cheetah-block-overlay-global<# } #>">
		<div class="ba-cheetah-block-overlay-header">
			<div class="ba-cheetah-block-overlay-actions">
				<# if ( data.global && ! BACheetahConfig.userCanEditGlobalTemplates ) { #>
				<i class="fas fa-lock ba-cheetah-tip" title="<?php _e( 'Locked', 'ba-cheetah' ); ?>"></i>
				<# } else { #>
				<?php if ( ! BACheetahModel::is_post_user_template( 'module' ) && ! $simple_ui ) : ?>
					<span class="ba-over-action ba-cheetah-block-move ba-cheetah-tip" title="<?php _e( 'Move', 'ba-cheetah' ); ?>"></i>
						<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
					</span>
				<?php endif; ?>
				<?php /* translators: %s: module name */ ?>
				<span class="ba-over-action ba-cheetah-block-settings ba-cheetah-tip" title="<?php printf( __( '%s Settings', 'ba-cheetah' ), '{{data.moduleName}}' ); ?>">
					<svg width="18.505" height="18"><use xlink:href="#ba-cheetah-icon--gear"></use></svg>
				</span>
				<?php if ( ! BACheetahModel::is_post_user_template( 'module' ) && ! $simple_ui ) : ?>
				<span class="ba-over-action ba-cheetah-block-copy ba-cheetah-tip" title="<?php _e( 'Duplicate', 'ba-cheetah' ); ?>">
					<svg width="14.205" height="18"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>
				</span>
				<span class="ba-cheetah-has-submenu">
					<span class="ba-over-action ba-cheetah-block-col-settings ba-cheetah-tip"  title="<?php _e( 'Edit Column', 'ba-cheetah' ); ?>">
						<svg width="19" height="17"><use xlink:href="#ba-cheetah-icon--cols"></use></svg>
					</span>
					<# if ( ! data.isRootCol ) { #>
					<ul class="ba-cheetah-submenu ba-cheetah-block-col-submenu">
						<li>
							<a class="ba-cheetah-block-col-edit" href="javascript:void(0);">
								<svg width="20" height="21.798"><use xlink:href="#ba-cheetah-icon--site-settings"></use></svg>
								<?php _e( 'Column Settings', 'ba-cheetah' ); ?>
							</a>
						</li>
						<li class="ba-cheetah-submenu-sep"><div></div></li>
						<li>
							<a class="ba-cheetah-block-col-move" href="javascript:void(0);">
								<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
								<?php _e( 'Move Column', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# if ( ( ! data.hasParentCol && data.numCols < 12 ) || ( data.hasParentCol && data.numCols < 4 ) ) { #>
						<li>
							<a class="ba-cheetah-block-col-copy" href="javascript:void(0);">
								<svg width="14.205" height="18"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>
								<?php _e( 'Duplicate Column', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# } #>
						<li class="ba-cheetah-submenu-sep"><div></div></li>
						<li>
							<a class="ba-cheetah-block-col-delete" href="javascript:void(0);">
								<svg width="15.429" height="18" fill="#ed6666" class="submenu-trash"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
								<?php _e( 'Delete Column', 'ba-cheetah' ); ?>
							</a>
						</li>
						<!-- Doing this is easy, you don't need a menu
						<# if ( data.numCols > 1 || ( data.hasParentCol && data.numParentCols > 1 ) ) { #>
						<li><a class="ba-cheetah-block-col-reset" href="javascript:void(0);"><?php _e( 'Reset Column Widths', 'ba-cheetah' ); ?></a></li>
						<# } #>
						<# if ( data.rowIsFixedWidth ) { #>
						<li><a class="ba-cheetah-block-row-reset" href="javascript:void(0);"><?php _e( 'Reset Row Width', 'ba-cheetah' ); ?></a></li>
						<# } #>
						-->
						<# if ( data.hasParentCol ) { #>
						<li class="ba-cheetah-submenu-sep"><div></div></li>
						<# if ( 'column' != BACheetahConfig.userTemplateType ) { #>
						<li>
							<a class="ba-cheetah-block-col-move-parent" href="javascript:void(0);">
								<svg width="17" height="17"><use xlink:href="#ba-cheetah-icon--move"></use></svg>
								<?php _e( 'Move Parent', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# } #>
						<li>
							<a class="ba-cheetah-block-col-edit-parent" href="javascript:void(0);">
								<svg width="18.505" height="18"><use xlink:href="#ba-cheetah-icon--gear"></use></svg>
								<?php _e( 'Parent Settings', 'ba-cheetah' ); ?>
							</a>
						</li>
						<# } #>
					</ul>
					<# } #>
				</span>
				<span class="ba-over-action ba-cheetah-block-remove ba-cheetah-tip" title="<?php _e( 'Remove', 'ba-cheetah' ); ?>">
					<svg width="15.429" height="18" fill="#ed6666"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
				</span>
				<?php endif; ?>
				<# } #>
			</div>
			<div class="ba-cheetah-clear"></div>
		</div>
		<# if ( data.colHasRules ) { #>
		<i class="fas fa-eye ba-cheetah-tip ba-cheetah-block-has-rules {{data.rulesTypeCol}}" title="<?php _e( 'This column has visibility rules', 'ba-cheetah' ); ?>: {{data.rulesTextCol}}"></i>
		<# } else if ( data.hasRules ) { #>
		<i class="fas fa-eye ba-cheetah-tip ba-cheetah-block-has-rules {{data.rulesTypeModule}}" title="<?php _e( 'This element has visibility rules', 'ba-cheetah' ); ?>: {{data.rulesTextModule}}"></i>
		<# } #>
		<?php if ( ! BACheetahModel::is_post_user_template( 'module' ) && ! $simple_ui ) : ?>
		<# if ( ! data.groupLoading && ! data.isRootCol ) { #>
			<# if ( ! data.colFirst || ( data.hasParentCol && data.colFirst && ! data.parentFirst ) ) { #>
			<div class="ba-cheetah-block-col-resize ba-cheetah-block-col-resize-w<# if ( data.hasParentCol && data.colFirst && ! data.parentFirst ) { #> ba-cheetah-block-col-resize-parent<# } #>">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
			<# if ( ! data.colLast || ( data.hasParentCol && data.colLast && ! data.parentLast ) ) { #>
			<div class="ba-cheetah-block-col-resize ba-cheetah-block-col-resize-e<# if ( data.hasParentCol && data.colLast && ! data.parentLast ) { #> ba-cheetah-block-col-resize-parent<# } #>">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
		<# } #>
		<# if ( data.userCanResizeRows ) { #>
			<# if ( ( ( data.colFirst && ! data.hasParentCol ) || ( data.colFirst && data.parentFirst ) ) && data.rowIsFixedWidth ) { #>
			<div class="ba-cheetah-block-row-resize ba-cheetah-block-col-resize ba-cheetah-block-col-resize-w">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
			<# if ( ( ( data.colLast && ! data.hasParentCol ) || ( data.colLast && data.parentLast ) ) && data.rowIsFixedWidth ) { #>
			<div class="ba-cheetah-block-row-resize ba-cheetah-block-col-resize ba-cheetah-block-col-resize-e">
				<div class="ba-cheetah-block-col-resize-handle-wrap">
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-left"></div>
					<div class="ba-cheetah-block-col-resize-handle"></div>
					<div class="ba-cheetah-block-col-resize-feedback ba-cheetah-block-col-resize-feedback-right"></div>
				</div>
			</div>
			<# } #>
		<# } #>
		<?php endif; ?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-module-overlay -->

<script type="text/html" id="tmpl-ba-cheetah-overlay-overflow-menu">
	<span class="ba-cheetah-has-submenu">
		<i class="ba-cheetah-block-overflow-menu fas fa-bars ba-cheetah-tip" title="<?php _e( 'More', 'ba-cheetah' ); ?>"></i>
		<ul class="ba-cheetah-submenu">
			<# for( var i = 0; i < data.length; i++ ) { #>
				<# if ( 'submenu' == data[ i ].type ) { #>
				<li class="ba-cheetah-has-submenu"><a href="javascript:void(0);">{{data[ i ].label}}<i class="fas fa-caret-right"></i></a>
					{{{data[ i ].submenu}}}
				</li>
				<# } else { #>
				<li><a class="{{data[ i ].className}}" href="javascript:void(0);">{{data[ i ].label}}<# if ( data[ i ].className.indexOf( 'ba-cheetah-block-move' ) > -1 ) { #><i class="fas fa-arrows-alt"></i><# } #></a>
				<# } #>
			<# } #>
		</ul>
	</span>
</script>
<!-- #tmpl-ba-cheetah-overlay-overflow-menu -->

<script type="text/html" id="tmpl-ba-cheetah-actions-lightbox">
	<span class="dashicons dashicons-no lightbox-close-icon" onclick="BACheetahLightbox.closeParent( this )"></span>
	<div class="ba-cheetah-actions {{data.className}}">
		<span class="ba-cheetah-actions-title">{{data.title}}</span>
		<div class="ba-cheetah-actions-options">
		<# for( var i in data.buttons ) { #>
			<div class="ba-cheetah-actions-option">
				<img src="<?php echo BA_CHEETAH_URL . 'img/svg/alert/layout-{{data.buttons[ i ].key}}.svg'; ?>">
				<span class="ba-cheetah-{{data.buttons[ i ].key}}-button ba-cheetah-button ba-cheetah-button--medium">{{data.buttons[ i ].label}}</span>
			</div>
		<# 
			if (i < data.buttons.length - 1) #>
				<span class="ba-cheetah-actions-divider"></span>
			<# 
		} #>
		</div>
		<!-- <span class="ba-cheetah-cancel-button ba-cheetah-button ba-cheetah-button-primary ba-cheetah-button-large"><?php _e( 'Cancel', 'ba-cheetah' ); ?></span>-->
	</div>
</script>
<!-- #tmpl-ba-cheetah-actions-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-alert-lightbox">
	<span class="dashicons dashicons-no lightbox-close-icon ba-cheetah-alert-close"></span>
	<div class="ba-cheetah-lightbox-message">{{{data.message}}}</div>
	<div class="ba-cheetah-lightbox-footer">
		<span class="ba-cheetah-alert-close ba-cheetah-button ba-cheetah-button-large ba-cheetah-button-primary" href="javascript:void(0);"><?php _e( 'OK', 'ba-cheetah' ); ?></span>
	</div>
</script>

<script type="text/html" id="tmpl-ba-cheetah-feedback-alert">
	<h3 class="ba-cheetah-lightbox-title"><?php echo _e('Quick Search Question', 'ba-cheetah') ?></h3>
	<span class="dashicons dashicons-no lightbox-close-icon ba-cheetah-alert-close ba-cheetah-feedback-close"></span>
	<div class="ba-cheetah-lightbox-body">
		<p><?php echo _e('Are you enjoying Builderall Builder? Tell us about your experience.', 'ba-cheetah') ?></p>
		<a href="https://forms.gle/hzPbZksNtkUPaLzKA" target="_blank" class="ba-cheetah-feedback-stars ba-cheetah-feedback-close">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</a>
	</div>
	<div class="ba-cheetah-lightbox-footer">
		<label for="ba-cheetah-feedback"><input type="checkbox" id="ba-cheetah-feedback" name="ba-cheetah-feedback">Don't show again</label>
		<a href="https://forms.gle/hzPbZksNtkUPaLzKA" target="_blank" class="ba-cheetah-feedback-close ba-cheetah-alert-close ba-cheetah-alert-confirm ba-cheetah-button ba-cheetah-button--medium ba-cheetah-button-primary">
			<?php echo _e('Answer', 'ba-cheetah') ?>
		</a>
	</div>
</script>

<!-- #tmpl-ba-cheetah-alert-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-lightbox-confirm-icon">
	<span class="dashicons dashicons-no lightbox-close-icon ba-cheetah-alert-close"></span>
	<div class="ba-cheetah-lightbox-body">
		<img src="<?php echo BA_CHEETAH_URL . 'img/svg/alert/{{{data.icon}}}'; ?>">
		<h3>{{{data.title}}}</h3>
		<p>{{{data.message}}}</p>
	</div>
	<div class="ba-cheetah-lightbox-footer">
		<# if (data.cancelable) { #>
			<span class="ba-cheetah-alert-close ba-cheetah-button ba-cheetah-button--medium" href="javascript:void(0);">
				<# if (data.cancelText) { #>
					{{{data.cancelText}}}
				<# } else { #>
					<?php _e( 'Cancel', 'ba-cheetah' ); ?>
				<# } #>
			</span>
		<# } #>
		<span class=" ba-cheetah-alert-close ba-cheetah-alert-confirm ba-cheetah-button ba-cheetah-button--medium ba-cheetah-button-primary" href="javascript:void(0);">
			<# if (data.confirmText) { #>
				{{{data.confirmText}}}
			<# } else { #>
				<?php _e( 'OK', 'ba-cheetah' ); ?>
			<# } #>
		</span>
	</div>
</script>
<!-- #tmpl-ba-cheetah-lightbox-confirm-icon -->

<script type="text/html" id="tmpl-ba-cheetah-pro-lightbox">
	<span class="dashicons dashicons-no" onclick="BACheetahLightbox.closeParent( this )"></span>
	<div class="ba-cheetah-pro-message-title">{{data.feature}} <?php _e( 'has restricted access', 'ba-cheetah' );?></div>
	<div class="ba-cheetah-pro-message-content">
		<?php _e( 'With the Builderall Builder for WordPress you can make a website load faster and beatiful. Meet all features of Pro version and take the experience to create websites to the next level.', 'ba-cheetah' ); ?>
	</div>
	<div class="ba-cheetah-pro-message-button">
		<button class="ba-cheetah-upgrade-button ba-cheetah-button ba-cheetah-button-primary" onclick="BACheetah._proClicked()">
			<?php _e( 'GO PRO', 'Link to learn more about Builderall Builder Pro', 'ba-cheetah' ); ?>
		</button>
	</div><br>
	<div class="ba-cheetah-pro-message-content">
		<?php _e( 'Are you a Builderall User?', 'ba-cheetah' ); ?> 
		<a href="javascript:BACheetah._upgradeClicked()"><?php _e( 'Click here', 'ba-cheetah' );?></a> 
		<?php _e( 'and unlock all Builderall Elements right now!', 'ba-cheetah' );?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-pro-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-crash-lightbox">
	<div class="ba-cheetah-lightbox-message">{{{data.message}}}</div>
	<# if ( data.debug ) { #>
		<div class="ba-cheetah-lightbox-message-info">Here is the message reported in your browser’s JavaScript console.<pre>{{{data.debug}}}</pre></div>
	<# } #>
	<div class="ba-cheetah-lightbox-message-info">{{{data.info}}}</div>
	<div class="ba-cheetah-lightbox-footer">
		<span class="ba-cheetah-alert-close ba-cheetah-button ba-cheetah-button-large ba-cheetah-button-primary" href=2"javascript:void(0);"><?php _e( 'OK', 'ba-cheetah' ); ?></span>
	</div>
</script>
<!-- #tmpl-ba-cheetah-crash-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-confirm-lightbox">
	<div class="ba-cheetah-lightbox-message">{{{data.message}}}</div>
	<div class="ba-cheetah-lightbox-footer">
		<span class="ba-cheetah-confirm-cancel ba-cheetah-alert-close ba-cheetah-button ba-cheetah-button-large" href="javascript:void(0);">{{data.strings.cancel}}</span>
		<span class="ba-cheetah-confirm-ok ba-cheetah-alert-close ba-cheetah-button ba-cheetah-button-large ba-cheetah-button-primary" href="javascript:void(0);">{{data.strings.ok}}</span>
	</div>
</script>
<!-- #tmpl-ba-cheetah-confirm-lightbox 

<script type="text/html" id="tmpl-ba-cheetah-tour-lightbox">
	<div class="ba-cheetah-actions ba-cheetah-tour-actions">
		<span class="ba-cheetah-actions-title"><?php _e( 'Welcome! It looks like this might be your first time using the builder. Would you like to take a tour?', 'ba-cheetah' ); ?></span>
		<span class="ba-cheetah-no-tour-button ba-cheetah-button ba-cheetah-button-large"><?php _e( 'No Thanks', 'ba-cheetah' ); ?></span>
		<span class="ba-cheetah-yes-tour-button ba-cheetah-button ba-cheetah-button-primary ba-cheetah-button-large"><?php _e( 'Yes Please!', 'ba-cheetah' ); ?></span>
	</div>
</script>
-->
<!-- #tmpl-ba-cheetah-tour-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-video-lightbox">
	<div class="ba-cheetah-lightbox-header">
		<h1><?php _e( 'Getting Started Video', 'ba-cheetah' ); ?></h1>
	</div>
	<div class="ba-cheetah-getting-started-video">{{{data.video}}}</div>
	<div class="ba-cheetah-lightbox-footer">
		<span class="ba-cheetah-settings-cancel ba-cheetah-button ba-cheetah-button-large ba-cheetah-button-primary" href="javascript:void(0);"><?php _e( 'Done', 'ba-cheetah' ); ?></span>
	</div>
</script>
<!-- #tmpl-ba-cheetah-video-lightbox -->

<script type="text/html" id="tmpl-ba-cheetah-responsive-preview">
	<div class="ba-cheetah-responsive-preview-mask"></div>
	<div class="ba-cheetah-responsive-preview">
		
		<div class="ba-cheetah-responsive-preview-message">
			<!--
			<span>
				<?php _e( 'Responsive Editing', 'ba-cheetah' ); ?>
			</span>
			<button class="ba-cheetah-button ba-cheetah-button-large" data-mode="responsive">
				<i class="dashicons dashicons-smartphone"></i>
			</button>
			<button class="ba-cheetah-button ba-cheetah-button-large" data-mode="medium">
				<i class="dashicons dashicons-tablet"></i>
			</button>
			<button class="ba-cheetah-button ba-cheetah-button-large" data-mode="default">
				<?php _e( 'Exit', 'ba-cheetah' ); ?>
			</button>
			-->
			<span class="size"></span>
		</div>
		
		<div class="ba-cheetah-responsive-preview-content"></div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-responsive-preview -->

<script type="text/html" id="tmpl-ba-cheetah-search-results-panel">
	<div class="ba-cheetah--search-results">
		<#
		var grouped = data.grouped;
		var orderedGroups = Array.from(Object.keys(grouped)).sort()

		orderedGroups.forEach(function(groupSlug) {
			var cats = grouped[groupSlug];
			#>

			<div class="ba-cheetah-blocks-group">

			<#
			var orderedCats = Array.from(Object.keys(cats)).sort()

			orderedCats.forEach(function(catSlug) {
				var modules = cats[catSlug];
				#>
				<div class="ba-cheetah-blocks-section">
					<span class="ba-cheetah-blocks-section-title">{{catSlug}}</span>
					<div class="ba-cheetah-blocks-section-content ba-cheetah-modules">
					<#
					for( var i in modules ) {
						var module 	= modules[i],
							type 	= module.isWidget ? 'widget' : module.slug,
							alias 	= module.isAlias ? ' data-alias="' + module.alias + '"' : '',
							widget 	= module.isWidget ? ' data-widget="' + module.class + '"' : '',
							name 	= module.name,
							allowed = true,
							showElement = true,
							showMsg		  = "",
							blockDisabled = "",
							indexInArray  = jQuery.inArray( module.slug, BACheetah._disabledModules );

								
							if ( -1 !=  indexInArray) {
								showElement = BACheetah._disabledModulesFull[ BACheetah._disabledModules[indexInArray] ]['show'];
								blockDisabled = 'ba-cheetah-block-disabled';
								showMsg = ' onclick="BACheetah._showProMessage(\''+module.name+'\')"';
								allowed = false;
							}

							if(showElement) {
							#>
							<span class="ba-cheetah-block ba-cheetah-block-module {{blockDisabled}}" title="{{name}}" data-type="{{type}}"{{{alias}}}{{{widget}}}{{{showMsg}}}>
								<span class="ba-cheetah-block-content">
									<# if ( !allowed ) { #>
										<div class="ba-cheetah--element-denied" title="<?php _e( 'This is a PRO Element', 'ba-cheetah' ); ?>"></div>
									<# } #>
									<span class="ba-cheetah-block-icon">{{{module.icon}}}</span>
								</span>
								<span class="ba-cheetah-block-title">{{name}}</span>
							</span>
					<# 		}
						} #>
					</div>
				</div>
			<# }) #>
			</div>
		<# }) #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-search-results-panel -->

<script type="text/html" id="tmpl-ba-cheetah-search-no-results">
	<div class="ba-cheetah--no-results"><?php _ex( 'No Results Found', 'No content panel search results found', 'ba-cheetah' ); ?></div>
</script>
<!-- #tmpl-ba-cheetah-search-no-results -->

<script type="text/html" id="tmpl-ba-cheetah-main-menu-panel">
	<div class="ba-cheetah--main-menu-panel-mask"></div>
	<div class="ba-cheetah--main-menu-panel">
		<div class="ba-cheetah--main-menu-panel-views"></div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-main-menu-panel -->

<script type="text/html" id="tmpl-ba-cheetah-main-menu-panel-view">
	<#
	var viewClasses = [],
		backItem;

	if (data.isShowing) {
		viewClasses.push('is-showing');
	}
	if (data.isRootView) {
		viewClasses.push('is-root-view');
	}

	viewClasses = viewClasses.join(' ');

	if (!data.isRootView) {
		backItem = '<button class="pop-view">&larr;</button>';
	}
	#>
	<div class="ba-cheetah--main-menu-panel-view {{viewClasses}}" data-name="{{data.handle}}">
		<div class="ba-cheetah--main-menu-panel-view-title">{{{backItem}}}{{{data.name}}}</div>
		
		
		<div class="ba-cheetah--menu">
			<?php if(!BACheetahUserTemplatesLayout::is_layout_content_type(get_post_type(get_the_ID()))): 
				$postmeta_mode = get_post_meta(BACheetahModel::get_post_id(), 'ba_cheetah_mode', true);
			?>
				<# if (data.isRootView) { #>
				<hr>
				<div class="ba-cheetah-mode-toggle">
					<label class="ba-cheetah-mode-toggle-label">
						<?php _e( 'Layout mode', 'ba-cheetah' ); ?>
						<img 
							title="<?php _e( 'In Canvas mode you edit the entire page, while in Default mode, just content area', 'ba-cheetah' ); ?>"
							src="<?php echo BA_CHEETAH_URL . 'img/svg/help.svg'; ?>"
						>
					</label>
					<div class="ba-cheetah-mode-toggle-options">
						<div class="ba-cheetah-mode-toggle-option <?php echo $postmeta_mode == 'default' ? 'is-active' : '' ?>">
							<img src="<?php echo BA_CHEETAH_URL . 'img/svg/mode-default.svg'; ?>">
							<div>
								<input 
									type="radio"
									name="cheetah-mode-checkbox"
									id="cheetah-mode-checkbox-default"
									value="default"
									
									<?php checked($postmeta_mode, 'default'); ?> 
								/>
								<label for="cheetah-mode-checkbox-default"><?php _e( 'Default', 'ba-cheetah' ); ?></label>
							</div>
						</div>
						<div class="ba-cheetah-mode-toggle-option <?php echo $postmeta_mode == 'canvas' ? 'is-active' : '' ?>">
							<img src="<?php echo BA_CHEETAH_URL . 'img/svg/mode-canvas.svg'; ?>">
							<div>
								<input 
									type="radio"
									name="cheetah-mode-checkbox"
									id="cheetah-mode-checkbox-canvas"
									value="canvas"
									<?php checked($postmeta_mode, 'canvas'); ?>
								/>
								<label for="cheetah-mode-checkbox-canvas"><?php _e( 'Canvas', 'ba-cheetah' ); ?></label>
							</div>
						</div>
					</div>
				</div>
				<# } #>
			<?php endif;?>

			<# for (var key in data.items) {
				var item  = data.items[key];
				var extra = '';
				if ( 'revisions' === item.view && BACheetahConfig.revisions_count > 0 ) {
					extra = '[' + BACheetahConfig.revisions_count + ']';
				}
				if ( 'event' === item.type && 'showPageSettings' === item.eventName ) {
					if( BACheetahConfig.layout_css_js ) {
						extra = '&bull;';
					}
				}
				if ( 'event' === item.type && 'showGlobalSettings' === item.eventName ) {
					if( '' !== BACheetahConfig.global.css || '' !== BACheetahConfig.global.js ) {
						extra = '&bull;';
					}
				}
				switch(item.type) {
					case "separator":
						#><hr><#
						break;
					case "event":
						#>
						<button class="ba-cheetah--menu-item" data-type="event" data-event="{{item.eventName}}">
							<# if (item.icon) { #>
								<span class="ba-cheetah--menu-item-icon">
									<svg width="24" height="24" fill="#a5b9d5"><use xlink:href="#{{item.icon}}"></use></svg>
								</span>
							<# } #>
							<span class="ba-cheetah--menu-item-label">{{{item.label}}}</span>
							<span class="menu-event event-{{item.eventName}}">{{{extra}}}</span>
							<!-- <span class="ba-cheetah--menu-item-accessory">{{{item.accessory}}}</span> -->
						</button>
						<#
						break;
					case "link":
						#>
						<a class="ba-cheetah--menu-item" href="{{{item.url}}}" data-type="link" target="_blank">
							<# if (item.icon) { #>
								<span class="ba-cheetah--menu-item-icon">
									<svg width="24" height="24" fill="#a5b9d5"><use xlink:href="#{{item.icon}}"></use></svg>
								</span>
							<# } #>
							<span class="ba-cheetah--menu-item-label">{{{item.label}}}</span>
							<!-- <span class="ba-cheetah--menu-item-accessory"><i class="fas fa-external-link-alt"></i></span> -->
						</a>
						<#
						break;
					case "view":
						#>
						<button class="ba-cheetah--menu-item" data-type="view" data-view="{{item.view}}">
							<# if (item.icon) { #>
								<span class="ba-cheetah--menu-item-icon">
									<svg width="24" height="24" fill="#a5b9d5"><use xlink:href="#{{item.icon}}"></use></svg>
								</span>
							<# } #>
							<span class="ba-cheetah--menu-item-label">{{{item.label}}}</span>
							<span class="menu-view view-{{item.view}}">{{extra}}</span>
							<!-- <span class="ba-cheetah--menu-item-accessory">&rarr;</span> -->
						</button>
						<#
						break;
					case "video":
						#>
						<div class="ba-cheetah-video-wrap">
							{{{item.embed}}}
						</div>
						<#
						break;
					default:
				}
			}
			#>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-main-menu-panel-view -->

<script type="text/html" id="tmpl-ba-cheetah-toolbar">
<?php include BA_CHEETAH_DIR . 'includes/ui-bar.php'; ?>
</script>
<!-- #tmpl-ba-cheetah-toolbar -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-base">
	<div class="ba-cheetah--content-library-panel ba-cheetah-panel">
		<div class="ba-cheetah--panel-arrow">
			<svg width="29px" height="15px" viewBox="0 0 29 15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g transform="translate(-260.000000, -14.000000)">
					<polygon transform="translate(274.142136, 28.142136) rotate(-315.000000) translate(-274.142136, -28.142136) " points="264.142136 18.1421356 284.142136 18.1421356 264.142136 38.1421356"></polygon>
				</g>
			</svg>
		</div>
		<div class="ba-cheetah--panel-header">
			<!--
			<div class="ba-cheetah-panel-drag-handle">
				<svg xmlns="http://www.w3.org/2000/svg" width="10" height="16" viewBox="0 0 10 16">
					<path d="M11,18a2,2,0,1,1-2-2A2.006,2.006,0,0,1,11,18ZM9,10a2,2,0,1,0,2,2A2.006,2.006,0,0,0,9,10ZM9,4a2,2,0,1,0,2,2A2.006,2.006,0,0,0,9,4Zm6,4a2,2,0,1,0-2-2A2.006,2.006,0,0,0,15,8Zm0,2a2,2,0,1,0,2,2A2.006,2.006,0,0,0,15,10Zm0,6a2,2,0,1,0,2,2A2.006,2.006,0,0,0,15,16Z" transform="translate(-7 -4)" fill="#0080fc"/>
				</svg>
			</div>
			-->
			<div class="ba-cheetah--tabs">
				<div class="ba-cheetah--tab-wrap">
				<# for (var handle in data.tabs) {
					var tab = data.tabs[handle];
					if (!tab.shouldShowTabItem || "" == tab.name ) {
						continue;
					}
					var isShowingClass = (tab.isShowing) ? 'is-showing' : '' ;
					#>
					<button data-tab="{{tab.handle}}" class="ba-cheetah--tab-button {{isShowingClass}}">{{tab.name}}</button>
					<#
				}
				#>
				</div>
			</div>
			<div class="ba-cheetah--panel-controls">
				<div class="ba-cheetah-content-group-select"></div>
				<div class="ba-cheetah-panel-search">
					<button class="ba-cheetah-toggle-panel-search">
						<svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g class="filled-shape" transform="translate(-528.000000, -17.000000)">
									<path d="M539.435106,27.0628931 L538.707833,27.0628931 L538.456261,26.8113208 C539.352773,25.7730132 539.89251,24.4236707 539.89251,22.946255 C539.89251,19.6620926 537.230417,17 533.946255,17 C530.662093,17 528,19.6620926 528,22.946255 C528,26.2304174 530.662093,28.89251 533.946255,28.89251 C535.423671,28.89251 536.773013,28.352773 537.811321,27.4608348 L538.062893,27.7124071 L538.062893,28.4351058 L542.636935,33 L544,31.6369354 L539.435106,27.0628931 Z M534,27 C531.791111,27 530,25.2088889 530,23 C530,20.7911111 531.791111,19 534,19 C536.208889,19 538,20.7911111 538,23 C538,25.2088889 536.208889,27 534,27 Z"></path>
								</g>
							</g>
						</svg>
					</button>
					<div class="ba-cheetah-panel-search-input">
						<input name="search-term" placeholder="<?php _e( 'Search Elements', 'ba-cheetah' ); ?>" autocomplete="off" />
						<!--
						<button class="ba-cheetah-dismiss-panel-search">
							<svg viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
								<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<polygon class="filled-shape" points="20 2.02142857 17.9785714 0 10 7.97857143 2.02142857 0 0 2.02142857 7.97857143 10 0 17.9785714 2.02142857 20 10 12.0214286 17.9785714 20 20 17.9785714 12.0214286 10"></polygon>
								</g>
							</svg>
						</button>
						-->
					</div>
				</div>
			</div>
		</div>
		<div class="ba-cheetah--panel-content">
			<# for (var handle in data.tabs) {
				var tab = data.tabs[handle];
				if (!tab.shouldShowTabItem) {
					continue;
				}
				var isShowingClass = (tab.isShowing) ? 'is-showing' : '' ;
				#>
			<div data-tab="{{tab.handle}}" class="ba-cheetah--panel-view ba-cheetah-nanoscroller {{isShowingClass}}">
				<div class="ba-cheetah-nanoscroller-content"></div>
			</div>
			<# } #>
		</div>
		<div class="ba-cheetah--search-results-panel"></div>
		<button class="ba-cheetah-ui-pinned-collapse ba-cheetah-ui-pinned-left-collapse">
			<i data-toggle="show" data-position="left">
				<img src="<?php echo BA_CHEETAH_URL . 'img/svg/left-show.svg'; ?>">
			</i>
			<i data-toggle="hide" data-position="left">
				<img src="<?php echo BA_CHEETAH_URL . 'img/svg/left-hide.svg'; ?>">
			</i>
		</button>
		
		<button class="ba-cheetah-ui-pinned-collapse ba-cheetah-ui-pinned-right-collapse">
			<i data-toggle="hide" data-position="right">
				<img src="<?php echo BA_CHEETAH_URL . 'img/svg/right-hide.svg'; ?>">
			</i>
			<i data-toggle="show" data-position="right">
				<img src="<?php echo BA_CHEETAH_URL . 'img/svg/right-show.svg'; ?>">
			</i>
		</button>
		<div class="ba-cheetah--panel-no-settings">
			<div><?php _e( 'No settings selected.', 'ba-cheetah' ); ?></div>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-base -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-category-selector">
	<#
	var activeViewName = data.tab.activeView.name,
		views = data.items;
	#>
	<div class="ba-cheetah--category-select">
		<div class="ba-cheetah--selector-display">
			<button class="ba-cheetah--selector-display-label">
				<span class="ba-cheetah--current-view-name">{{{activeViewName}}}</span>
			</button>
		</div>
		<div class="ba-cheetah--selector-menu">
			<div class="ba-cheetah--menu">
				<# for(var i in views) {
					var view = views[i];
					if (view.type === 'separator') {
						#><hr><#
					} else {
					var parent = view.parent ? 'data-parent="' + view.parent + '"' : '';
					var hasChildrenClass = view.hasChildren ? ' ba-cheetah-has-children' : '';
					var hasChildrenOpenClass = view.hasChildrenOpen ? ' ba-cheetah-has-children-showing' : '';
					var insetClass = view.isSubItem ? ' ba-cheetah-inset' : '';
					var display = '';

					if ( view.parent && views[ view.parent ] && views[ view.parent ].hasChildrenOpen ) {
						display = ' style="display:block;"';
					}
					#>
					<button data-view="{{view.handle}}" {{{parent}}} {{{display}}} class="ba-cheetah--menu-item{{insetClass}}{{hasChildrenClass}}{{hasChildrenOpenClass}}">
						{{{view.name}}}
						<# if ( view.hasChildren ) { #>
						<svg class="ba-cheetah-symbol" height="14px" width="14px">
							<use xlink:href="#ba-cheetah-down-caret" />
						</svg>
						<# } #>
					</button>
				<# } } #>
			</div>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-category-selector -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-modules-view">
	<#
	if (!_.isUndefined(data.queryResults)) {
		var groupedModules = data.queryResults.library.module,
			groupedTemplates = data.queryResults.library.template;
	}

	if (!_.isUndefined(groupedModules) && groupedModules.hasOwnProperty('categorized')) {

		// Check if there are any ordered sections before looping over everything
		if (!_.isUndefined(data.orderedSectionNames)) {

			for( var i = 0; i < data.orderedSectionNames.length; i++ ) {
				var title = data.orderedSectionNames[i],
					modules = groupedModules.categorized[title],
					slug = title.replace(/\s+/g, '-').toLowerCase();

					if ( _.isUndefined(modules) ) { continue; }
				#>
				<div id="ba-cheetah-blocks-{{slug}}" class="ba-cheetah-blocks-section">
					<div class="ba-cheetah-blocks-section-header">
						<span class="ba-cheetah-blocks-section-title">{{title}}</span>
						<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
					</div>
					<div class="ba-cheetah-blocks-section-content ba-cheetah-modules">
						<# for( var k in modules) {
							var module 		  = modules[ k ],
								type 		  = module.isWidget ? 'widget' : module.slug,
								alias 		  = module.isAlias ? ' data-alias="' + module.alias + '"' : '',
								widget 		  = module.isWidget ? ' data-widget="' + module.class + '"' : '',
								allowed		  = true,
								showElement	  = true,
								showMsg		  = "",
								blockDisabled = "",
								indexInArray  = jQuery.inArray( module.slug, BACheetah._disabledModules );

								
							if ( -1 !=  indexInArray) {
								showElement = BACheetah._disabledModulesFull[ BACheetah._disabledModules[indexInArray] ]['show'];
								blockDisabled = 'ba-cheetah-block-disabled';
								showMsg = ' onclick="BACheetah._showProMessage(\''+module.name+'\')"';
								allowed = false;
							}

							if(showElement) {
							#>
							<span class="ba-cheetah-block ba-cheetah-block-module {{blockDisabled}}" title="{{module.name}}" data-type="{{type}}"{{{alias}}}{{{widget}}}{{{showMsg}}}>
								<span class="ba-cheetah-block-content">
									<# if ( !allowed ) { #>
										<div class="ba-cheetah--element-denied" title="<?php _e( 'This is a PRO Element', 'ba-cheetah' ); ?>"></div>
									<# } #>
									<span class="ba-cheetah-block-icon">{{{module.icon}}}</span>
								</span>
								<span class="ba-cheetah-block-title">{{module.name}}</span>
							</span>
						<# }
						} #>
					</div>
				</div>
				<#
				delete groupedModules.categorized[title];
			}
		}

		// Sort categorized modules in alphabetical order before render.
		Object.keys(groupedModules.categorized).sort().forEach(function(key) {
			var value = groupedModules.categorized[key];
			delete groupedModules.categorized[key];
			groupedModules.categorized[key] = value;
		});

		// Render any sections that were not already rendered in the ordered set
		for( var title in groupedModules.categorized) {
			var modules = groupedModules.categorized[title],
				slug = title.replace(/\s+/g, '-').toLowerCase();
				
				modules.sort(function(a, b) {
					if (a.name < b.name)
						return -1;
					if (a.name > b.name)
						return 1;
					return 0;
				});
			#>
			
			<div id="ba-cheetah-blocks-{{slug}}" class="ba-cheetah-blocks-section">
				<div class="ba-cheetah-blocks-section-header">
					<span class="ba-cheetah-blocks-section-title">{{title}}</span>
					<svg width="12.588" height="7.494"><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
				</div>
				<div class="ba-cheetah-blocks-section-content ba-cheetah-modules">
					<# for( var i in modules) {
						var module		  = modules[i],
							type		  = module.isWidget ? 'widget' : module.slug,
							alias		  = module.isAlias ? ' data-alias="' + module.alias + '"' : '',
							widget		  = module.isWidget ? ' data-widget="' + module.class + '"' : '',
							allowed 	  = true,
							showMsg 	  = "",
							blockDisabled = "";
							
						if ( -1 != jQuery.inArray( module.slug, BACheetah._disabledModules ) ) {
							blockDisabled = 'ba-cheetah-block-disabled';
							showMsg = ' onclick="BACheetah._showProMessage(\''+module.name+'\')"';
							allowed = false;
						}

					#>
					<span class="ba-cheetah-block ba-cheetah-block-module {{blockDisabled}}" title="{{module.name}}" data-type="{{type}}"{{{alias}}}{{{widget}}}{{{showMsg}}}>
						<span class="ba-cheetah-block-content">
							<# if ( !allowed ) { #>
								<div class="ba-cheetah--element-denied" title="<?php _e( 'This is a PRO Element', 'ba-cheetah' ); ?>"></div>
							<# } #>		
							<span class="ba-cheetah-block-icon">{{{module.icon}}}</span>
						</span>
						<span class="ba-cheetah-block-title">{{module.name}}</span>
					</span>
					<# } #>
				</div>
			</div>
			<#
		}
	}

	if (!_.isUndefined(groupedTemplates) && groupedTemplates.hasOwnProperty('categorized')) {

		var uncategorizedKey = BACheetahStrings.uncategorized;
		if (!_.isUndefined(groupedTemplates.categorized[uncategorizedKey])) {
			var uncategorized = groupedTemplates.categorized[uncategorizedKey];
		}
		for( var title in groupedTemplates.categorized) {
			var templates = groupedTemplates.categorized[title];
			#>
			<div class="ba-cheetah-blocks-section">
				<# if (title !== '') { #>
				<div class="ba-cheetah-blocks-section-header">
					<span class="ba-cheetah-blocks-section-title">{{title}}</span>
				</div>
				<# } #>
				<div class="ba-cheetah-blocks-section-content ba-cheetah-module-templates">
					<#
					for( var i in templates) {
						var template = templates[i],
							image = template.image,
							id = _.isNumber( template.postId ) ? template.postId : template.id,
							hasImage = image && !image.endsWith('blank.jpg'),
							hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '' ;
					#>
					<span class="ba-cheetah-block ba-cheetah-block-template ba-cheetah-block-module-template {{hasImageClass}}" data-id="{{id}}" data-type="{{template.type}}">
						<span class="ba-cheetah-block-content">
							<# if ( hasImage ) { #>
							<div class="ba-cheetah-block-thumbnail" style="background-image:url({{image}})"></div>
							<# } #>
						</span>
						<span class="ba-cheetah-block-title">{{template.name}}</span>
					</span>
					<# } #>
				</div>
			</div>
			<#
		}
	}
	#>
	
</script>
<!-- #tmpl-ba-cheetah-content-panel-modules-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-col-groups-view">
	<#
	if (_.isUndefined(data.queryResults)) return;
	var colGroups = data.queryResults.library.colGroup.items;
	#>
	<div id="ba-cheetah-blocks-rows" class="ba-cheetah-blocks-section">
		<# if (typeof colGroups !== 'undefined') { #>
		<div class="ba-cheetah-blocks-section-content ba-cheetah-rows">
			<# for( var i in colGroups) {
				var group = colGroups[i],
					id = group.id,
					name = group.name;
			#>
			<span class="ba-cheetah-block ba-cheetah-block-row ba-cheetah-block-col-group" data-cols="{{id}}" title="{{name}}">
				<span class="ba-cheetah-block-content">
					<span class="ba-cheetah-block-visual ba-cheetah-cols-visual {{id}}">
						<# for ( i = 0; i < group.count; i++ ) { #>
						<span class="ba-cheetah-cols-visual-col"></span>
						<# } #>
					</span>
				</span>
				<span class="ba-cheetah-block-title">{{name}}</span>
			</span>
			<# } #>
		</div>
		<# } #>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-col-groups-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-templates-view">
	<#
	var categories;
	if (!_.isUndefined(data.queryResults)) {
		categories = data.queryResults.library.template.categorized;
	}
	#>
	<div class="ba-cheetah--template-collection">
		<#
		if (categories !== undefined) {
			// treat as collection
			for( var catHandle in categories) {
				var templates = categories[catHandle];
				var categoryName;
				if (!_.isUndefined(BACheetahStrings.categoryMeta[catHandle])) {
					categoryName = BACheetahStrings.categoryMeta[catHandle].name;
				} else {
					categoryName = catHandle;
				}
				#>
				<div class="ba-cheetah--template-collection-section">
					<# if (catHandle !== 'uncategorized' && catHandle !== BACheetahStrings.undefined && Object.keys(categories).length > 1) { #>
					<div class="ba-cheetah--template-collection-section-header">
						<div class="ba-cheetah--template-collection-section-name">{{categoryName}}</div>
					</div>
					<# } #>
					<div class="ba-cheetah--template-collection-section-content">
						<#
						for( var i in templates) {
							var template = templates[i];
							var background = template.image;
							var id = _.isNumber( template.postId ) ? template.postId : template.id;
							var allowed = BACheetahConfig.isProUser || !template.pro;
						#>
						<div 
							class="ba-cheetah--template-collection-item"
							data-id="{{id}}"
							data-type="{{template.type}}"
							data-pro="{{template.pro}}"
							data-content-central-id="{{template.content_central_id}}"
							data-compatible="{{template.compatible}}"
							data-allowed="{{allowed}}"
							>
							<div class="ba-cheetah--template-thumbnail" style="background-image:url({{background}})">

								<div class="ba-cheetah--template-actions">

								<!-- template is pro but user isnt -->
								<# if ( !allowed ) { #>
								<div class="ba-cheetah--template-denied" title="<?php _e( 'This is a PRO template', 'ba-cheetah' ); ?>">
								</div>

								<!-- incompatible -->
								<# } else if ( !template.compatible ) { #>
								<div class="ba-cheetah--template-incompatible" title="<?php _e( 'This template was created with a newer version of Builderall Builder for Wordpress and therefore you must update your plugin in order to use it.', 'ba-cheetah' ); ?>">
								</div>

								<!-- compatible  -->
								<# } else { #>
								<button class="ba-cheetah-button ba-cheetah-button-primary ba-cheetah--template-apply"><?php _e( 'Apply', 'ba-cheetah' ); ?></button>

								<!-- preview  -->
								<# } if ( template.preview_url ) { #>
								<a class="ba-cheetah-button ba-cheetah-button-white" target="_blank" href="{{template.preview_url}}"><?php _e( 'Preview', 'ba-cheetah' ); ?></a>
								<# } #>

								</div>
							</div>
							<div class="ba-cheetah--template-name">{{template.name}}</div>
						</div>
						<# } #>
					</div>
				</div>
				<#
			}
		} else {
			// treat as category
			for( var i in data.templates) {
				var template = data.templates[i];
				var background = template.image;
			#>
			<div class="ba-cheetah--template-collection-item" data-id="{{template.id}}">
				<div class="ba-cheetah--template-thumbnail" style="background-image:url({{background}})"></div>
				<div class="ba-cheetah--template-name">{{template.name}}</div>
			</div>
			<#
			}
		}
		#>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-templates-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-row-templates-view">
	<#
	var categories;
	if (!_.isUndefined(data.queryResults)) {
		categories = data.queryResults.library.template.categorized;
	}
	#>
	<div>
		<#
		if (!_.isUndefined(categories)) {
			for( var catHandle in categories) {
				var templates = categories[catHandle];
				var categoryName;
				if (!_.isUndefined(BACheetahStrings.categoryMeta[catHandle])) {
					categoryName = BACheetahStrings.categoryMeta[catHandle].name;
				} else {
					categoryName = catHandle;
				}
				#>
				<div class="ba-cheetah-blocks-section">
					<# if (catHandle !== 'uncategorized' && catHandle !== BACheetahStrings.undefined && Object.keys(categories).length > 1) { #>
					<div class="ba-cheetah-blocks-section-header">
						<span class="ba-cheetah-blocks-section-title">{{categoryName}}</span>
					</div>
					<# } #>
					<div class="ba-cheetah-blocks-section-content ba-cheetah-row-templates">
						<#
						for( var i in templates) {
							var template = templates[i],
								image = template.image,
								id = _.isNumber( template.postId ) ? template.postId : template.id,
								hasImage = image && !image.endsWith('blank.jpg'),
								hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '',
								allowed = BACheetahConfig.isProUser || !template.pro,
								disabledClass = !allowed ? 'ba-cheetah-block-disabled' : '';
						#>
						<span 
							class="ba-cheetah-block ba-cheetah-block-template ba-cheetah-block-row-template {{hasImageClass}} {{disabledClass}}"
							data-allowed="{{allowed}}"
							data-id="{{id}}"
							data-type="{{template.type}}"
							data-pro="{{template.pro}}"
							data-compatible="{{template.compatible}}"
							data-content-central-id="{{template.content_central_id}}"
							>
							<span class="ba-cheetah-block-content">
								<# if (hasImage) { #>
								<div class="ba-cheetah-block-thumbnail" style="background-image:url({{image}})">
								
									<div class="ba-cheetah--template-actions">

											<!-- template is pro but user isnt -->
										<# if ( !allowed ) { #>
										<div class="ba-cheetah--template-denied" title="<?php _e( 'This is a PRO row template', 'ba-cheetah' ); ?>">
										</div>
										
										<!-- incompatible -->
										<# } else if ( !template.compatible ) { #>
										<div class="ba-cheetah--template-incompatible" title="<?php _e( 'This row was created with a newer version of Builderall Builder for Wordpress and therefore you must update your plugin in order to use it.', 'ba-cheetah' ); ?>">
										</div>

										<!-- preview  -->
										<# } if ( template.preview_url ) { #>
										<a 
											class="ba-cheetah-button ba-cheetah-button-primary ba-preview-row"
											target="_blank"
											href="{{template.preview_url}}"
											onclick="event.stopPropagation();"
											title="<?php _e( 'Preview', 'ba-cheetah' ); ?>"
											>
											<svg width="17.007" height="11.071" fill="#fff"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>
										</a>
										<# } #>

									</div>									
								</div>
								<# } #>
							</span>
							<span class="ba-cheetah-block-title">{{template.name}}</span>
						</span>
						<# } #>
					</div>
				</div>
				<#
			}
		}
		#>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-row-templates-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-module-templates-view">
	<#
	var categories;
	if (!_.isUndefined(data.queryResults)) {
		categories = data.queryResults.library.template.categorized;
	}
	#>
	<div class="ba-cheetah-module-templates-view">
		<#
		if (!_.isUndefined(categories)) {
			for( var catHandle in categories) {
				var templates = categories[catHandle],
					categoryName;
				if (!_.isUndefined(BACheetahStrings.categoryMeta[catHandle])) {
					categoryName = BACheetahStrings.categoryMeta[catHandle].name;
				} else {
					categoryName = catHandle;
				}
				#>
				<div class="ba-cheetah-blocks-section">
					<# if (catHandle !== 'uncategorized' && catHandle !== BACheetahStrings.undefined && Object.keys(categories).length > 1) { #>
					<div class="ba-cheetah-blocks-section-header">
						<span class="ba-cheetah-blocks-section-title">{{categoryName}}</span>
					</div>
					<# } #>
					<div class="ba-cheetah-blocks-section-content ba-cheetah-module-templates">
						<#
						for( var i in templates) {
							var template = templates[i],
								image = template.image,
								id = _.isNumber( template.postId ) ? template.postId : template.id,
								hasImage = image && !image.endsWith('blank.jpg'),
								hasImageClass = hasImage ? 'ba-cheetah-block-has-thumbnail' : '';
						#>
						<span class="ba-cheetah-block ba-cheetah-block-template ba-cheetah-block-module-template {{hasImageClass}}" data-id="{{id}}" data-type="{{template.type}}">
							<span class="ba-cheetah-block-content">
								<# if ( hasImage ) { #>
								<img class="ba-cheetah-block-template-image" src="{{image}}" />
								<# } #>
							</span>
							<span class="ba-cheetah-block-title">{{template.name}}</span>
						</span>
						<# } #>
					</div>
				</div><#
			}
		}
		#>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-module-templates-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-no-view">
	<div class="ba-cheetah--panel-message">
		<?php _ex( 'Sorry, no content was found!', 'Message that displays when a panel tab has no view to display', 'ba-cheetah' ); ?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-no-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-panel-no-templates-view">
	<div class="ba-cheetah--panel-message">
		<?php _ex( 'Sorry, no templates were found!', 'Message that displays when there are no templates to display', 'ba-cheetah' ); ?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-panel-no-templates-view -->

<script type="text/html" id="tmpl-ba-cheetah-content-lite-templates-upgrade-view">
	<div class="ba-cheetah--panel-message">
		<p><?php _ex( 'Save and reuse your layouts or kick-start your creativity with dozens of professionally designed templates.', 'Upgrade message that displays in the templates tab in lite installs.', 'ba-cheetah' ); ?></p>
		<a class="ba-cheetah-upgrade-button ba-cheetah-button" href="{{BACheetahConfig.upgradeUrl}}" target="_blank"><?php _ex( 'Learn More', 'Link to learn more about Builderall Builder PRO', 'ba-cheetah' ); ?> <i class="fas fa-external-link-alt"></i></a>
	</div>
</script>
<!-- #tmpl-ba-cheetah-content-lite-templates-upgrade-view -->

<script type="text/html" id="tmpl-ba-cheetah-revision-list-item">
	<div class="ba-cheetah-revision-list-item" data-revision-id="{{data.id}}">
		<div class="ba-cheetah-revision-list-item-avatar">
			{{{data.avatar}}}
		</div>
		<div class="ba-cheetah-revision-list-item-text">
			<div class="ba-cheetah-revision-list-item-date">
			{{data.date}}
			</div>
			<div class="ba-cheetah-revision-list-item-author">
			{{data.author}}
			</div>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-revision-list-item -->

<script type="text/html" id="tmpl-ba-cheetah-no-revisions-message">
	<div class="ba-cheetah-no-revisions-message">
		<div class="ba-cheetah-no-revisions-message-title">
			<?php _e( 'No Revisions Found', 'ba-cheetah' ); ?>
		</div>
		<?php if ( defined( 'WP_POST_REVISIONS' ) && ! WP_POST_REVISIONS ) : ?>
			<div class="ba-cheetah-no-revisions-message-text">
				<?php _e( "Revisions are disabled for this site. Please contact your host if you aren't sure how to enable revisions.", 'ba-cheetah' ); ?>
			</div>
		<?php else : ?>
			<div class="ba-cheetah-no-revisions-message-text">
				<?php _e( "You haven't saved any revisions yet. Each time you publish a new revision will be saved here.", 'ba-cheetah' ); ?>
			</div>
		<?php endif; ?>
	</div>
</script>
<!-- #tmpl-ba-cheetah-no-revisions-message -->

<script type="text/html" id="tmpl-ba-cheetah-history-list-item">
	<div class="ba-cheetah-history-list-item" data-position="{{data.position}}" data-current="{{data.current}}">
		<div class="ba-cheetah-history-list-item-label">
			{{{data.label}}}
		</div>
		<i class="fas fa-check-circle"></i>
	</div>
</script>
<!-- #tmpl-ba-cheetah-history-list-item -->

<script type="text/html" id="tmpl-ba-cheetah-keyboard-shortcuts">
	<div class="ba-cheetah-ui-keyboard-shortcuts">
		<div class="ba-cheetah-ui-keyboard-shortcuts-content">
			<# for( var i in data ) {
				var item = data[i];
			#>
			<div class="ba-cheetah-ui-keyboard-shortcut-item">{{ item.label }} <span class="ba-cheetah-ui-shortcut-keycode">{{{ item.keyLabel }}}</span></div>
			<# } #>

			<div class="ba-cheetah-ui-keyboard-shortcust-footer">
				<button class="dismiss-shortcut-ui"><?php _e( 'Close', 'ba-cheetah' ); ?></button>
			</div>
		</div>
	</div>
</script>
<!-- #tmpl-ba-cheetah-keyboard-shortcuts -->
