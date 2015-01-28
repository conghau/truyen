<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['story'] 							= 'story/index';
$route['story/detail/(:num)'] 				= 'story/view_story/$1';
$route['story/chapter/(:num)']				= 'story/get_chapter/$1';
