<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* @name CI Smarty
* @copyright Dwayne Charrington, 2011.
* @author Dwayne Charrington and other Github contributors
* @license (DWYWALAYAM) 
           Do What You Want As Long As You Attribute Me Licence
* @version 1.2
* @link http://ilikekillnerds.com
*/

class Errors extends MY_Controller {


    public function __construct() {
        parent::__construct();
        
        // Ideally you would autoload the parser
        $this->load->library('parser');
        $this->load->library('device');
		$this->load->helper('url');
    }

    public function index($code = 'system_error') {
    	try {
			$this->data['message_code'] = $code;
	        $this->parse("general.tpl", "errors/not_supported");
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	}

    public function not_supported() {
    	try {
			$this->data['message_code'] = 'not_supported';
	        $this->parse("general.tpl", "errors/not_supported");
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	}

	public function not_found() {
    	try {
			$this->data['message_code'] = 'not_found';
	        $this->parse("general.tpl", "errors/not_supported");
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	}
	
	public function invalid_session() {
		try {
			$this->data['message_code'] = 'invalid_session';
			$this->parse("general.tpl", "errors/invalid_session");
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
		}
	}
}
