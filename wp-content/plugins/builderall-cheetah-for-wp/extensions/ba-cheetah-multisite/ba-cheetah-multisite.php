<?php

// Only load for multisite installs.
if ( ! is_multisite() ) {
	return;
}

// Defines
define( 'BA_CHEETAH_MULTISITE_DIR', BA_CHEETAH_DIR . 'extensions/ba-cheetah-multisite/' );
define( 'BA_CHEETAH_MULTISITE_URL', BA_CHEETAH_URL . 'extensions/ba-cheetah-multisite/' );

// Classes
require_once BA_CHEETAH_MULTISITE_DIR . 'classes/class-ba-cheetah-multisite.php';
