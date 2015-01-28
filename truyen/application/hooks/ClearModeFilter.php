<?php 
if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
class ClearModeFilter {
    public function __construct()
    {
        $this->CI = get_instance();
//		$this->CI->load->helper('input');
    }

	function process() {
		if (array_key_exists('mode', $_COOKIE) && $this->CI->input->cookie('mode') !== 'smartphone') {
			$this->CI->input->set_cookie(array('name' => 'mode', 'value' => '', 'expire' => '-1', 'path' => '/'));
		}
	}
}
