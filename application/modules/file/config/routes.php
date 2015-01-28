<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['file']							= 'index';
$route['file/preview/(:num)']			= 'preview/$1';
$route['file/processing/(:num)/(:any)']	= 'preview_processing/$1/$2';
$route['file/processing/(:num)']		= 'preview_processing/$1';
$route['file/image/(:any)']				= 'image/$1';
$route['file/thumbnail/(:any)']			= 'thumbnail/$1';
$route['file/download/(:any)']			= 'download/$1';
$route['file/movie/(:any)']				= 'movie/$1';
$route['file/batch_download/(:num)']	= 'batch_download/$1';
$route['file/get_and_zip_file/(:num)']	= 'get_and_zip_file/$1';
$route['file/file_list/(:any)']			= 'file_list/$1';
$route['file/download_file_list/(:any)']= 'download_file_list/$1';