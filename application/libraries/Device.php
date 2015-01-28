<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* @name CI DeviceInfo
* @copyright Takeo Noda, 2012
* @author Takeo Noda
*/

require_once APPPATH."third_party/DeviceInfo/DeviceInfo.class.php";

//class CI_DeviceInfo {
class Device extends DeviceInfo {

    protected $CI;
    private $_module = '';

    public function __construct() {
        parent::__construct();

        // Store the Codeigniter super global instance... whatever
        $this->CI =& get_instance();

        // Modular Separation / Modular Extensions has been detected
        if ( method_exists( $this->CI->router, 'fetch_module' ) ) {
            $this->_module = $this->CI->router->fetch_module();
        }

		// initialize 
        $this->CI->load->config('deviceinfo');
        $this->device_type  = config_item('device_type');
        $this->rules      	= config_item('rules');
        $this->robots      	= config_item('robots');
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$this->set_user_agent($_SERVER['HTTP_USER_AGENT']);
			$this->initialize();
		}
    }
    
    /**
    */
	public function get_template_name($filename) {
		if ($this->is_robot() && array_key_exists('smartphone', $this->device_type)) {
			$suffix = $this->device_type['smartphone'];
			$new_filename = preg_replace("/(\.[\w]+)$/i", $suffix.'$1', $filename);
			echo $new_filename;
			$template = APPPATH . 'modules/' . $this->_module . '/views/' . $new_filename;
			if (file_exists($template)) {
				return $new_filename;
			} else if (file_exists($new_filename)) {
				return $new_filename;
			} else if (file_exists($filename)) {
				return $filename;
			}
		}
		foreach ($this->device_type as $type => $suffix) {
			if (!$this->is_matches($type) && $type !== 'default') {
				continue;
			}
			$new_filename = preg_replace("/(\.[\w]+)$/i", $suffix.'$1', $filename);
			$template = APPPATH . 'modules/' . $this->_module . '/views/' . $new_filename;
			if (file_exists($template)) {
				return $new_filename;
			} else if (file_exists($new_filename)) {
				return $new_filename;
			} else if (file_exists($filename)) {
				return $filename;
			}
		}
		return "";
	}

}
