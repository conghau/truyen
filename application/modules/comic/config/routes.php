<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['comic'] 							= 'comic/index';
$route['comic/paginate'] 					= "comic/index";
$route['comic/paginate/(:any)'] 			= 'comic/index';

$route['comic/detail/(:num)_(:any).html'] 				= 'comic/view_story/$1';
$route['comic/(:num)_(:num)/(:any)_(:any).html']				= 'comic/view_chapter/$1/$2';
