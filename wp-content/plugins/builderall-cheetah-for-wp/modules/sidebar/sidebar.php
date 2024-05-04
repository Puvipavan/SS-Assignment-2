<?php

/**
 * @class BACheetahSidebarModule
 */
class BACheetahSidebarModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Sidebar', 'ba-cheetah' ),
			'description'     => __( 'Display a WordPress sidebar that has been registered by the current theme.', 'ba-cheetah' ),
			'category'        => __( 'Layout', 'ba-cheetah' ),
			'editor_export'   => false,
			'partial_refresh' => true,
			'enabled'		  => false,
			'icon'            => 'layout.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahSidebarModule', array(
	'general' => array( // Tab
		'title' => __( 'General', 'ba-cheetah' ), // Tab title
		'file'  => BA_CHEETAH_DIR . 'modules/sidebar/includes/settings-general.php',
	),
));
