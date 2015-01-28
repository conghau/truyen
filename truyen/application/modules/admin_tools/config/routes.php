<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//COMMON
$route['admin_tools'] 								= 'index';
$route['admin_tools/get_post/(:num)'] 				= 'index/get_post/$1';
$route['admin_tools/(:num)/comment_list']			= 'index/get_comment_list/$1';
$route['admin_tools/login'] 						= 'authadmin/login';
$route['admin_tools/logout'] 						= 'authadmin/logout';

// ADMIN AREA CONFIGURATION ROUTE STA
$route['admin_tools/admin'] 						= 'admin/index';
$route['admin_tools/admin/paginate'] 				= "admin/index";
$route['admin_tools/admin/paginate/(:any)'] 		= 'admin/index';
$route['admin_tools/admin/create'] 					= 'admin/create';
$route['admin_tools/admin/store']					= 'admin/store';
$route['admin_tools/admin/confirm_create']			= 'admin/confirm_create';
$route['admin_tools/admin/(:num)/edit'] 			= 'admin/edit/$1';
$route['admin_tools/admin/(:num)/update'] 			= 'admin/update/$1';
$route['admin_tools/admin/(:num)/confirm_edit']		= 'admin/confirm_edit/$1';
$route['admin_tools/admin/(:num)/delete'] 			= 'admin/delete/$1';
$route['admin_tools/admin/export_all']				= 'admin/export_all';
$route['admin_tools/admin/export_search']			= 'admin/export_search_result';
$route['admin_tools/admin/import_confirm']			= 'admin/import_confirm';
$route['admin_tools/admin/import_done']				= 'admin/import_done';
$route['admin_tools/admin/download_file_error']		= 'admin/download_file_error';
// ADMIN AREA CONFIGURATION ROUTE END

// POST AREA CONFIGURATION ROUTE STA
$route['admin_tools/post'] 							= 'postadmin/index';
$route['admin_tools/post/paginate'] 				= 'postadmin/index';
$route['admin_tools/post/(:num)/edit'] 				= 'postadmin/edit/$1';
$route['admin_tools/post/(:num)/confirm_edit'] 		= 'postadmin/confirm_edit';
$route['admin_tools/post/(:num)/update']			= 'postadmin/update';
$route['admin_tools/post/paginate/(:any)']			= 'postadmin/index';
$route['admin_tools/post/(:num)/delete']			= 'postadmin/delete';
$route['admin_tools/post/export_all']				= 'postadmin/export_all';
$route['admin_tools/post/export_search']			= 'postadmin/export_search_result';
$route['admin_tools/post/(:num)/user']				= 'postadmin/index';
$route['admin_tools/post/(:num)/group']				= 'postadmin/index';
$route['admin_tools/post/(:num)/file']				= 'postadmin/file/$1';
$route['admin_tools/comment/(:num)/edit']			= 'postadmin/comment_edit/$1';
$route['admin_tools/comment/(:num)/confirm_edit']	= 'postadmin/comment_confirm_edit';
$route['admin_tools/comment/(:num)/update']			= 'postadmin/comment_update';
$route['admin_tools/comment/(:num)/delete']			= 'postadmin/comment_delete';
// POST AREA CONFIGURATION ROUTE END

// USER AREA CONFIGURATION ROUTE STA
$route['admin_tools/user']							= 'useradmin/index';
$route['admin_tools/user/create'] 					= 'useradmin/create';
$route['admin_tools/user/confirm_create']			= 'useradmin/confirm_create';
$route['admin_tools/user/store'] 					= 'useradmin/store';
$route['admin_tools/user/paginate'] 				= 'useradmin/index';
$route['admin_tools/user/paginate/(:any)'] 			= 'useradmin/index';
$route['admin_tools/user/(:num)/edit'] 				= 'useradmin/edit/$1';
$route['admin_tools/user/(:num)/update'] 			= 'useradmin/update/$1';
$route['admin_tools/user/(:num)/confirm_edit']		= 'useradmin/confirm_edit/$1';
$route['admin_tools/user/(:num)/delete'] 			= 'useradmin/delete/$1';
$route['admin_tools/user/import_confirm']			= 'useradmin/import_confirm';
$route['admin_tools/user/import_done']				= 'useradmin/import_done';
$route['admin_tools/user/export_all']				= 'useradmin/export_all';
$route['admin_tools/user/export_search']			= 'useradmin/export_search_result';
$route['admin_tools/user/download_file_error']		= 'useradmin/download_file_error';
// USER AREA CONFIGURATION ROUTE END

// GROUP AREA CONFIGURATION ROUTE STA
$route['admin_tools/group']							= 'groupadmin/index';
$route['admin_tools/group/(:num)/user']				= 'groupadmin/index';
$route['admin_tools/group/paginate'] 				= 'groupadmin/index';
$route['admin_tools/group/paginate/(:any)'] 		= 'groupadmin/index';
$route['admin_tools/group/create'] 					= 'groupadmin/create';
$route['admin_tools/group/confirm_create'] 			= 'groupadmin/confirm_create';
$route['admin_tools/group/store'] 					= 'groupadmin/store';
$route['admin_tools/group/(:num)/edit']				= 'groupadmin/edit/$1';
$route['admin_tools/group/(:num)/confirm_edit']		= 'groupadmin/confirm_edit/$1';
$route['admin_tools/group/(:num)/update']			= 'groupadmin/update';
$route['admin_tools/group/(:num)/delete']			= 'groupadmin/delete/$1';
$route['admin_tools/group/create_done'] 			= 'groupadmin/create_done';
$route['admin_tools/group/edit_done'] 				= 'groupadmin/edit_done';
$route['admin_tools/group/export_all']				= 'groupadmin/export_all';
$route['admin_tools/group/export_search']			= 'groupadmin/export_search_result';
// GROUP AREA CONFIGURATION ROUTE END

// USERLOG AREA CONFIGURATION ROUTE STA
$route['admin_tools/userlog']									= 'userlog/index';
$route['admin_tools/userlog/paginate']							= 'userlog/index';
$route['admin_tools/userlog/paginate/(:any)']					= 'userlog/index';
$route['admin_tools/userlog/(:num)/detail']						= 'userlog/user_log/$1';
$route['admin_tools/userlog/(:num)/detail/paginate']			= 'userlog/user_log/$1';
$route['admin_tools/userlog/(:num)/detail/paginate/(:any)']		= 'userlog/user_log/$1';
$route['admin_tools/userlog/export_search']						= 'userlog/export_search_result';
// USERLOG AREA CONFIGURATION ROUTE END

// FILELOG AREA CONFIGURATION ROUTE STA
$route['admin_tools/filelog']									= 'filelog/index';
$route['admin_tools/filelog/paginate']							= 'filelog/index';
$route['admin_tools/filelog/paginate/(:any)']					= 'filelog/index';
$route['admin_tools/filelog/(:num)/detail']						= 'filelog/file_log/$1';
$route['admin_tools/filelog/(:num)/detail/paginate']			= 'filelog/file_log/$1';
$route['admin_tools/filelog/(:num)/detail/paginate/(:any)']		= 'filelog/file_log/$1';
$route['admin_tools/filelog/export_search']						= 'filelog/export_search_file_log';
// FILELOG AREA CONFIGURATION ROUTE END

// THREADLOG AREA CONFIGURATION ROUTE STA
$route['admin_tools/threadlog']									= 'threadlog/index';
$route['admin_tools/threadlog/paginate']						= 'threadlog/index';
$route['admin_tools/threadlog/paginate/(:any)']					= 'threadlog/index';
$route['admin_tools/threadlog/(:num)/detail']					= 'threadlog/thread_log/$1';
$route['admin_tools/threadlog/(:num)/detail/paginate']			= 'threadlog/thread_log/$1';
$route['admin_tools/threadlog/(:num)/detail/paginate/(:any)']	= 'threadlog/thread_log/$1';
$route['admin_tools/threadlog/export_search']					= 'threadlog/export_search_thread_log';
// THREADLOG AREA CONFIGURATION ROUTE END

// GROUPLOG AREA CONFIGURATION ROUTE STA
$route['admin_tools/grouplog']									= 'grouplog/index';
$route['admin_tools/grouplog/paginate'] 						= 'grouplog/index/$1';
$route['admin_tools/grouplog/paginate/(:any)'] 					= 'grouplog/index/$1';
$route['admin_tools/grouplog/(:num)/detail']					= "grouplog/group_log/$1";
$route['admin_tools/grouplog/(:num)/detail/paginate'] 			= 'grouplog/group_log/$1';
$route['admin_tools/grouplog/(:num)/detail/paginate/(:any)'] 	= 'grouplog/group_log/$1';
$route['admin_tools/grouplog/export_search']					= 'grouplog/export_search_result';
// GROUPLOG AREA CONFIGURATION ROUTE END

// ENTRYLOG AREA CONFIGURATION ROUTE STA
$route['admin_tools/entrylog']										= 'entrylog/index';
$route['admin_tools/entrylog/paginate']								= 'entrylog/index';
$route['admin_tools/entrylog/paginate/(:num)']						= 'entrylog/index';
$route['admin_tools/entrylog/detail_all']							= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail_all/export']					= 'entrylog/export_detail';
$route['admin_tools/entrylog/detail_search']						= 'entrylog/entry_log';
$route['admin_tools/entrylog/export_all']							= 'entrylog/export_all';
$route['admin_tools/entrylog/export_search']						= 'entrylog/export_search';
$route['admin_tools/entrylog/detail/paginate']						= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail/paginate/(:num)']				= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail_all/paginate']					= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail_all/paginate/(:num)']			= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail_search/paginate']				= 'entrylog/entry_log';
$route['admin_tools/entrylog/detail_search/paginate/(:num)']		= 'entrylog/entry_log';
// ENTRYLOG AREA CONFIGURATION ROUTE END

// APPROVE AREA CONFIGURATION ROUTE STA
$route['admin_tools/approve']						= 'approve/index';
$route['admin_tools/approve/paginate']				= 'approve/index';
$route['admin_tools/approve/paginate/(:any)']		= 'approve/index';
$route['admin_tools/approve/index']					= 'approve/load_index';
$route['admin_tools/approve/(:num)/edit'] 			= 'approve/edit/$1';
$route['admin_tools/approve/update']				= 'approve/update';
$route['admin_tools/approve/(:num)/delete']			= 'approve/delete/$1';
// APPROVE AREA CONFIGURATION ROUTE END

// FILE AREA CONFIGURATION ROUTE STA
$route['admin_tools/file']							= 'fileadmin/index';
$route['admin_tools/file/paginate']					= 'fileadmin/index';
$route['admin_tools/file/paginate/(:any)']			= 'fileadmin/index';
$route['admin_tools/file/(:num)/edit']				= 'fileadmin/edit/$1';
$route['admin_tools/file/(:num)/confirm_edit']		= 'fileadmin/confirm_edit/$1';
$route['admin_tools/file/edit_done']				= 'fileadmin/edit_done';
$route['admin_tools/file/(:num)/delete']			= 'fileadmin/delete/$1';
$route['admin_tools/file/(:num)/update']			= 'fileadmin/update/$1';
$route['admin_tools/file/export_search']			= 'fileadmin/export_search_result';
$route['admin_tools/file/export_all']				= 'fileadmin/export_all';
$route['admin_tools/file/(:num)/post']				= 'fileadmin/index';
$route['admin_tools/file/(:num)/get_and_zip_file'] 	= 'fileadmin/get_and_zip_file/$1';
$route['admin_tools/file/(:num)/batch_download'] 	= 'fileadmin/batch_download/$1';
$route['admin_tools/file_list/(:any)']				= 'fileadmin/file_list/$1';
$route['admin_tools/download_file_list/(:any)']		= 'fileadmin/download_file_list/$1';
$route['admin_tools/download/(:any)']				= 'fileadmin/download/$1';
// FILE AREA CONFIGURATION ROUTE END

// QUALIFICATION AREA CONFIGURATION ROUTE STA
$route['admin_tools/qualification']							= 'qualification/edit';
$route['admin_tools/qualification/import_confirm']			= 'qualification/import_confirm';
$route['admin_tools/qualification/import_done']				= "qualification/import_done";
$route['admin_tools/qualification/export_all']				= 'qualification/export_all';
$route['admin_tools/qualification/update']					= 'qualification/update';
$route['admin_tools/qualification/download_file_error']		= 'qualification/download_file_error';
// QUALIFICATION AREA CONFIGURATION ROUTE END

$route['admin_tools/approve/(:num)/delete']			= 'approve/delete/$1';
