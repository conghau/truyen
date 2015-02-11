<?php
require_once(APPPATH.'helpers/arrayutil_helper.php');
require_once(APPPATH.'helpers/htmlutil_helper.php');

$config =& get_config();
$a = (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? $config['ssl_base_url'] : $config['base_url'];
$debug_backtrace = debug_backtrace();
// data mapper から dto のモデルとして dao を呼び出している個所があるので、そこはログを出さないよう調整
$error_log = array_keys(array_indexing_by_keys(array('file', 'line'), $debug_backtrace));
log_message('error', var_export($error_log, true));
$callback_tree = array_keys(array_indexing_by_keys(array('function'), $debug_backtrace));

$ajax_uri = array('/file/upload/', '/post/store');
// ajax 用フォルダか判定
if (isAjax()) {
	$ajax_path = '/ajax';
} else {
	$ajax_path = '';
}
log_message('error', "HTTP_X_REQUESTED_WITH:".isAjax());

// CSRF 対策によるエラー
if (isset($callback_tree[2]) && $callback_tree[2] === 'csrf_show_error') {
	$uri = $_SERVER['REQUEST_URI'];
	log_message('error', 'Invalid session at '.$uri);
	$header = sprintf("Location: %s%s", $a, 'error/invalid_session'.$ajax_path);
// 汎用エラー	
} else {
	log_message('error', 'System error at '.$uri);
	$header = sprintf("Location: %s%s", $a, 'error/system'.$ajax_path);
}
if (ENVIRONMENT !== 'batch') {
	log_message('debug', $header);
	header($header);
}
