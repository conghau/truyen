<?php 
if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
class AuthFilter {

    public function __construct()
    {
        $this->CI =& get_instance();
//         $this->CI->load->library('userauth');
//         $this->CI->load->library('adminauth');
//         $this->CI->load->helper('cookie', 'arrayutil');
//		$this->CI->lang->load('error');
    }

	function process()
	{
// 		$session_id = $this->CI->input->cookie(COOKIE_SESSION);
// 		log_message('debug', sprintf("Cookie => session id: %s", $session_id));
// 		$status = $this->CI->userauth->verify_session($session_id);
// 		if ($status) {
// 			$this->CI->data['user'] = $this->CI->userauth->getUser();
// 			log_message('auth', sprintf("%s\t%s\t%s", 'verify', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $session_id, 'user_id' => $this->CI->data['user']->user_id))));
// 		}
		
// 		$session_admin_id = $this->CI->input->cookie(COOKIE_ADMIN_SESSION);
// 		log_message('debug', sprintf("Cookie => session id: %s", $session_admin_id));
// 		$status_admin = $this->CI->adminauth->verify_session($session_admin_id);
// 		if ($status_admin) {
// 			$this->CI->data['admin'] = $this->CI->adminauth->getAdmin();
// 			log_message('authadmin', sprintf("%s\t%s\t%s", 'verify', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $session_id, 'admin_id' => $this->CI->data['admin']->admin_id))));
// 		}
	}
}
