<?php
$config =& get_config();
$a = (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? $config['ssl_base_url'] : $config['base_url'];

$debug_backtrace = debug_backtrace();
$error_log = array_keys(array_indexing_by_keys(array('file', 'line'), $debug_backtrace));
log_message('error', var_export($error_log, true));

header(sprintf("Location: %s%s", $a, 'error/db'));
