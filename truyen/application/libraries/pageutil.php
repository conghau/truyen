<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* @name CI DeviceInfo
* @copyright Takeo Noda, 2012
* @author Takeo Noda
*/

require_once APPPATH."third_party/Utils/Pager.class.php";

//class CI_DeviceInfo {
class PageUtil extends Pager {

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
    }
}
