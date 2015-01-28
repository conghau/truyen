<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['notice'] 					= 'notice/index';
$route['notice/get'] 				= 'notice/get';
$route['notice/set_read/(:num)'] 	= 'notice/set_read/$1';