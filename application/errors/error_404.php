<?php
$config =& get_config();
$a = (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? $config['ssl_base_url'] : $config['base_url'];
header(sprintf("Location: %s%s", $a, 'error/not_found'));
