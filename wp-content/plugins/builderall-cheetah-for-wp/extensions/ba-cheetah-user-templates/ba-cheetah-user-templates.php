<?php

// Defines
define( 'BA_CHEETAH_USER_TEMPLATES_DIR', BA_CHEETAH_DIR . 'extensions/ba-cheetah-user-templates/' );
define( 'BA_CHEETAH_USER_TEMPLATES_URL', BA_CHEETAH_URL . 'extensions/ba-cheetah-user-templates/' );



// PRO Classes
require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates.php';
require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-post-type.php';

// Classes
require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-layout.php';

// Admin Classes
if ( is_admin() ) {
	require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-admin-edit.php';

	require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-admin-add.php';
	require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-admin-list.php';
	require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-admin-menu.php';
	require_once BA_CHEETAH_USER_TEMPLATES_DIR . 'classes/class-ba-cheetah-user-templates-admin-settings.php';
}
