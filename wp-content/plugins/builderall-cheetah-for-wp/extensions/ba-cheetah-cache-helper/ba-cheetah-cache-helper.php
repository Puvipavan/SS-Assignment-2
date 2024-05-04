<?php
// Defines
define( 'BA_CHEETAH_CACHE_HELPER_DIR', BA_CHEETAH_DIR . 'extensions/ba-cheetah-cache-helper/' );
define( 'BA_CHEETAH_CACHE_HELPER_URL', BA_CHEETAH_URL . 'extensions/ba-cheetah-cache-helper/' );

// Classes
if ( version_compare( PHP_VERSION, '5.3.0', '>' ) ) {
	require_once BA_CHEETAH_CACHE_HELPER_DIR . 'classes/class-ba-cheetah-cache-helper.php';
}
