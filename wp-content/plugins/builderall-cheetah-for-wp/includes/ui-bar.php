<?php
/**
 * Button texts
 */
$discard     = apply_filters( 'ba_cheetah_ui_bar_discard', __( 'Discard', 'ba-cheetah' ) );
$discard_alt = apply_filters( 'ba_cheetah_ui_bar_discard_alt', __( 'Discard changes and exit', 'ba-cheetah' ) );
$draft       = apply_filters( 'ba_cheetah_ui_bar_draft', __( 'Save Draft', 'ba-cheetah' ) );
$draft_alt   = apply_filters( 'ba_cheetah_ui_bar_draft_alt', __( 'Keep changes drafted and exit', 'ba-cheetah' ) );
$review      = apply_filters( 'ba_cheetah_ui_bar_review', __( 'Submit for Review', 'ba-cheetah' ) );
$review_alt  = apply_filters( 'ba_cheetah_ui_bar_review_alt', __( 'Submit changes for review and exit', 'ba-cheetah' ) );
$publish     = apply_filters( 'ba_cheetah_ui_bar_publish', __( 'Publish', 'ba-cheetah' ) );
$publish_alt = apply_filters( 'ba_cheetah_ui_bar_publish_alt', __( 'Publish changes and exit', 'ba-cheetah' ) );
$cancel      = apply_filters( 'ba_cheetah_ui_bar_cancel', __( 'Cancel', 'ba-cheetah' ) );
?>
<div class="ba-cheetah-bar">
	<div class="ba-cheetah-bar-content">
		<?php BACheetah::render_ui_bar_title(); ?>
		<div class="ba-cheetah-bar-actions ba-navbar-actions">

			<span class="ba-cheetah-bar-spacer"></span>
			
			<!-- devices -->
			<div class="ba-cheetah-button-group ba-navbar-devices">
				<button class="ba-cheetah-button ba-cheetah-button-white ba-device-active" data-action="change-device" data-mode="default" title="<?php echo esc_attr(__('Default', 'ba-cheetah')) ?>">
					<svg width="25.916" height="25.916"><use xlink:href="#ba-cheetah-icon--desktop"></use></svg>
				</button>
				<span class="ba-cheetah-bar-divider"></span>
				<button class="ba-cheetah-button ba-cheetah-button-white" data-action="change-device" data-mode="medium" title="<?php echo esc_attr(__('Medium', 'ba-cheetah')) ?>">
					<svg width="18.511" height="25.916"><use xlink:href="#ba-cheetah-icon--tablet"></use></svg>
				</button>
				<span class="ba-cheetah-bar-divider"></span>
				<button class="ba-cheetah-button ba-cheetah-button-white" data-action="change-device" data-mode="responsive" title="<?php echo esc_attr(__('Responsive', 'ba-cheetah')) ?>">
					<svg width="14.809" height="25.916"><use xlink:href="#ba-cheetah-icon--smartphone"></use></svg>
				</button>
			</div>

			<span class="ba-cheetah-bar-spacer"></span>

			<?php if (!BA_CHEETAH_PRO): ?>
			<a class="ba-cheetah-bar-go-pro" target="_blank" href="<?php echo esc_url(BA_CHEETAH_LANDINGPAGE_URL) ?>">
				<svg width="26.54" height="26.12"><use xlink:href="#ba-cheetah-icon--pro-diamond"></use></svg>
				<button class="ba-cheetah-button">
					<?php echo __( 'Go PRO', 'ba-cheetah' ) ?>
				</button>
			</a>
			<?php endif; ?>

			<span class="ba-cheetah--saving-indicator"></span>
			<span class="ba-cheetah-bar-divider"></span>

			<!-- history -->
			<div class="ba-cheetah-button-group ba-navbar-history">
				<button class="ba-cheetah-button ba-cheetah-button-white" data-action="undo" title="<?php echo esc_attr(__('Undo', 'ba-cheetah')) ?>">
					<svg width="19.021" height="18.998"><use xlink:href="#ba-cheetah-icon--undo"></use></svg>
				</button>
				<button class="ba-cheetah-button ba-cheetah-button-white" data-action="redo" title="<?php echo esc_attr(__('Redo', 'ba-cheetah')) ?>">
					<svg width="19.022" height="18.999"><use xlink:href="#ba-cheetah-icon--redo"></use></svg>
				</button>
			</div>

			<span class="ba-cheetah-bar-divider"></span>

			<!-- open panel -->
			<button class="ba-cheetah-content-panel-button ba-cheetah-button ba-cheetah-button-silent">
				<svg xmlns="http://www.w3.org/2000/svg" width="17.601" height="17.601" viewBox="0 0 17.601 17.601">
					<g transform="translate(-165.484 -8.961) rotate(90)" opacity="0.705">
						<path id="path-content-panel-button-filled" d="M25.594,43.441a8.827,8.827,0,1,1,7.377-2.506,8.8,8.8,0,0,1-7.377,2.506Z" transform="translate(-9 -209)"/>
						<path d="M32.965,35.751H30.252V33.038A1.038,1.038,0,0,0,29.214,32h-.425a1.038,1.038,0,0,0-1.038,1.038v2.713H25.038A1.038,1.038,0,0,0,24,36.789v.425a1.038,1.038,0,0,0,1.038,1.038h2.713v2.713A1.038,1.038,0,0,0,28.789,42h.425a1.038,1.038,0,0,0,1.038-1.038V38.252h2.713A1.038,1.038,0,0,0,34,37.214v-.425A1.038,1.038,0,0,0,32.965,35.751Z" transform="translate(-11.264 -211.281)" fill="#fff"/>
					</g>
				</svg>
			</button>

			<!-- done -->
			<div class="ba-cheetah-bar-actions ba-navbar-done">
				<button class="ba-cheetah-button ba-cheetah-button-primary" data-action="preview">
					<svg width="16.64" height="11.346" fill="#fff"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>
					<?php echo __('Preview', 'ba-cheetah') ?>
				</button>
				<button class="ba-cheetah-done-button ba-cheetah-button ba-cheetah-button-success">
					<svg width="14.166" height="14.166" fill="#fff"><use xlink:href="#ba-cheetah-icon--save"></use></svg>
					<?php echo __('Done', 'ba-cheetah') ?>
				</button>
			</div>
		</div>
		
		<div class="ba-cheetah-clear"></div>
		<div class="ba-cheetah-publish-actions-click-away-mask"></div>
		<div class="ba-cheetah-publish-actions is-hidden">
			<button class="ba-cheetah-button ba-cheetah-button-danger" data-action="discard" title="<?php echo esc_attr( $discard_alt ); ?>">
				<svg width="15.429" height="18" fill="#fff" filter="brightness(0) invert(1)"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>
				<?php echo esc_attr( $discard ); ?>
			</button>
			<button class="ba-cheetah-button ba-cheetah-button-primary" data-action="draft" title="<?php echo esc_attr( $draft_alt ); ?>">
				<svg width="14.238" height="14.238" fill="#fff"><use xlink:href="#ba-cheetah-icon--pencil"></use></svg>
				<?php echo esc_attr( $draft ); ?>
			</button>
			<# if( 'publish' !== BACheetahConfig.postStatus && ! BACheetahConfig.userCanPublish ) { #>
			<button class="ba-cheetah-button ba-cheetah-button-primary" data-action="success" title="<?php echo esc_attr( $review_alt ); ?>"><?php echo esc_attr( $review ); ?></button>
			<# } else { #>
			<button class="ba-cheetah-button ba-cheetah-button-success" data-action="publish" title="<?php echo esc_attr( $publish_alt ); ?>">
				<svg width="14.166" height="14.166" fill="#fff"><use xlink:href="#ba-cheetah-icon--publish"></use></svg>
				<?php echo esc_attr( $publish ); ?>
			</button>
			<# } #>
			<button class="ba-cheetah-button has-background" data-action="dismiss"><?php echo esc_attr( $cancel ); ?></button>
		</div>
	</div>
</div>
<div class="ba-cheetah--preview-actions">

	<!-- fake space for the logo -->
	<span style="width:353px"></span>

	<div class="ba-preview-devices">
		<button class="ba-cheetah-button ba-cheetah-button-white ba-device-active" data-mode="default" title="<?php echo esc_attr(__('Default', 'ba-cheetah')) ?>">
			<svg width="25.916" height="25.916"><use xlink:href="#ba-cheetah-icon--desktop"></use></svg>
		</button>
		<span class="ba-cheetah-bar-divider"></span>
		<button class="ba-cheetah-button ba-cheetah-button-white" data-mode="medium" title="<?php echo esc_attr(__('Medium', 'ba-cheetah')) ?>">
			<svg width="18.511" height="25.916"><use xlink:href="#ba-cheetah-icon--tablet"></use></svg>
		</button>
		<span class="ba-cheetah-bar-divider"></span>
		<button class="ba-cheetah-button ba-cheetah-button-white" data-mode="responsive" title="<?php echo esc_attr(__('Responsive', 'ba-cheetah')) ?>">
			<svg width="14.809" height="25.916"><use xlink:href="#ba-cheetah-icon--smartphone"></use></svg>
		</button>
	</div>

	<div class="ba-cheetah-button-group buttons-preview">
		<button class="ba-cheetah-button end-preview-btn"><?php _e( 'Go back to editor', 'ba-cheetah' ); ?></button>
		<span class="ba-cheetah-bar-divider"></span>
		<button class="ba-cheetah-button ba-cheetah-button-success publish-preview-btn">
			<svg width="14.166" height="14.166" fill="#fff"><use xlink:href="#ba-cheetah-icon--publish"></use></svg>
			<?php echo __('Publish', 'ba-cheetah') ?>
		</button>
	</div>
</div>

<div class="ba-cheetah--revision-actions">
	<select></select>
	<button class="ba-cheetah-button ba-cheetah-cancel-revision-preview"><?php _e( 'Cancel', 'ba-cheetah' ); ?></button>
	<button class="ba-cheetah-button ba-cheetah-button-primary ba-cheetah-apply-revision-preview"><?php _e( 'Apply', 'ba-cheetah' ); ?></button>
</div>
