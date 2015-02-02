<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['story'] 							= 'story/index';
$route['story/paginate'] 					= "story/index";
$route['story/paginate/(:any)'] 			= 'story/index';

$route['story/detail/(:num)_(:any).html'] 				= 'story/view_story/$1';
$route['story/(:any)/chapter/(:any)-(:num).html']				= 'story/view_chapter/$3';
