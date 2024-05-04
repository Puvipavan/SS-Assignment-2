<?php

function ba_cheetah_is_rest_api_request()
{
	if (empty($_SERVER['REQUEST_URI'])) {
		// Probably a CLI request
		return false;
	}

	$rest_prefix         = trailingslashit(rest_get_url_prefix());
	$is_rest_api_request = strpos($_SERVER['REQUEST_URI'], $rest_prefix) !== false;

	return apply_filters('is_rest_api_request', $is_rest_api_request);
}


if(!function_exists('dump')) {

	function dump()
	{
		$args = func_get_args();
		echo "<pre>";
		foreach ($args as $arg) {
			echo "<pre>";
			var_dump($arg);
			echo "</pre>";
		}
		echo "</pre>";
	}

}

if (!function_exists('dd')) {

	function dd()
	{
		ob_clean();

		$args = func_get_args();

		dump(...$args);

		die();
	}

}