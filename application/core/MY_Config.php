<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

require APPPATH."third_party/MX/Config.php";
 
// ------------------------------------------------------------------------

/**
 * CodeIgniter Config Class
 *
 * This class contains functions that enable config files to be managed
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class MY_Config extends MX_Config {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Site URL
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @access	public
	 * @param	string	the URI string
	 * @return	string
	 */
	function site_url($uri = '')
	{
		$target = parent::site_url($uri);
		if (FALSE === empty($_SERVER['HTTPS']) && ('off' !== $_SERVER['HTTPS'])) {
			$target = str_replace("http:", "https:", $target);
			log_message('debug', 'SITE_URL:'.$target);
		} else {
			log_message('debug', 'SITE_URL:'.$target);
		}
		return $target;
	}
}