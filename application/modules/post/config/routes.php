<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['post/dest_list/(:num)'] 			= 'post/get_dest_list/$1';
$route['post/get_dest_name/(:any)/(:any)']	= 'post/get_dest_name';
$route['post/create'] 						= 'post/create';
$route['post/create/(:num)/group']			= 'post/create_for_group/$1';
$route['post/store/(:num)']					= 'post/store/$1';
$route['post/(:num)/edit']					= 'post/edit/$1';
$route['post/update']						= 'post/update';
$route['post/(:num)/delete']				= 'post/delete/$1';
$route['post/(:num)/copy']					= 'post/copy/$1';
$route['post/(:num)/comment_list']			= 'post/get_comment_list/$1';
$route['post/(:num)/store_comment']			= 'post/store_comment/$1';
$route['post/(:num)/update_comment']		= 'post/update_comment/$1';
$route['post/(:num)/(:num)/delete_comment']	= 'post/delete_comment/$1/$2';
$route['post/create_send_to/(:num)']		= 'post/create_send_to/$1';
$route['post/insert_view/(:num)']			= 'post/insert_view/$1';
