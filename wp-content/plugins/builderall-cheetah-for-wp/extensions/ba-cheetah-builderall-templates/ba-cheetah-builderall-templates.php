<?php


	// Defines
	define( 'BA_CHEETAH_BUILDERALL_TEMPLATES_DIR', BA_CHEETAH_DIR . 'extensions/ba-cheetah-builderall-templates/' );
	define( 'BA_CHEETAH_BUILDERALL_TEMPLATES_URL', BA_CHEETAH_URL . 'extensions/ba-cheetah-builderall-templates/' );

	if(!defined('BA_CHEETAH_TEMPLATE_API_URL')) {
		define('BA_CHEETAH_TEMPLATE_API_URL', 'https://builderallbuilderwp.builderall.com/wp-json/wp/v2/');
	}

	// Classes
	require_once BA_CHEETAH_BUILDERALL_TEMPLATES_DIR . 'classes/class-ba-cheetah-templates.php';

	if(BA_CHEETAH_TEMPLATE_API_ENABLED) {
		require_once BA_CHEETAH_BUILDERALL_TEMPLATES_DIR . 'classes/class-ba-cheetah-api.php';
		require_once BA_CHEETAH_BUILDERALL_TEMPLATES_DIR . 'classes/class-ba-cheetah-pro.php';
	}
	
