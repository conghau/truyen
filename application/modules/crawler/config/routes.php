<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['crawler/getstorymanga24h']				= 'crawler/GetStoryManga24h';

$route['crawler/getchaptermanga24h']			= 'crawler/GetChapterManga24h';
$route['crawler/getchaptermanga24h/(:any)']			= 'crawler/GetChapterManga24h/$1';

$route['crawler/updatedetailmanga24h']			= 'crawler/UpdateDetailManga24h';
$route['crawler/updatedetailmanga24h/(:any)']			= 'crawler/UpdateDetailManga24h/$1';

$route['crawler/getimagemanga24h']			    = 'crawler/GetImageManga24h';