<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['errors/invalid_session'] 		= 'errors/invalid_session';
$route['errors/not_supported'] 			= 'errors/not_supported';
$route['errors/not_found'] 				= 'errors/not_found';
$route['errors/(:any)'] 				= 'errors/index/$1';
