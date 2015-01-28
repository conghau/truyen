<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// In PHP 5.2 or higher we don't need to bring this in
if (!function_exists('json_encode')) {
	require_once 'JSON/JSON.php';
	
	function json_encode($arg)
	{
		global $services_json;
		if (!isset($services_json)) {
			$services_json = new Services_JSON();
		}
		return $services_json->encode($arg);
	}
	
	function json_decode($arg)
	{
		global $services_json;
		if (!isset($services_json)) {
			$services_json = new Services_JSON();
		}
		return $services_json->decode($arg);
	}
} 
?>
