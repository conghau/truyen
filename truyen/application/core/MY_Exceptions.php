<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require_once BASEPATH.'core/Exceptions.php';
$LANG =& load_class('Lang', 'core');

class MY_Exceptions extends CI_Exceptions{
    public function __construct() {
        parent::__construct();
	}

/*
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
		// send every other error to the original handler
        parent::show_error($heading, $message, $template, $status_code);
    }
*/
    public function show_404($page='', $log_error = TRUE){    
        $this->config =& get_config();
		// ベースURL
		$this->data['base_url'] = $this->config['base_url'];
		// ベースURL
		$this->data['ssl_base_url'] = str_replace("http:", "https:", $this->config['base_url']);
		// 現在の接続状態に合わせたベースURL
		$base_url =  (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? $this->data['ssl_base_url'] : $this->data['base_url'];

		// By default we log this, but allow a dev to skip it
		if ($log_error) {
			log_message('error', '404 Page Not Found --> '.$page);
		}

        header("Location: ".$base_url);
        exit;
    }
}
