<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter DateUtil Helpers
 *
 * date util helpers.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author Takeo Noda Sevenmedia Inc.
 * @link		
 */
// --------------------------------------------------------------------

include_once APPPATH.'third_party/spyc/spyc.php';
require_once APPPATH."third_party/MX/Modules.php";

/**
*/
if ( ! function_exists('load_yaml'))
{	
	function load_yaml( $filename ) {
		$yaml = "";

		$result = array();
		if (file_exists($filename)) {
			$fp = fopen($filename,'r');
			while(!feof($fp)){
				$line = fgets($fp,1024);
				$yaml .= $line;
			}
			fclose($fp);

			$result = Spyc::YAMLLoad($yaml);
		}
		return $result;
	}
}


if ( ! function_exists('load_config_yaml'))
{	
	function load_config_yaml($target_filename) {
		$common_filename = APPPATH."config/".$target_filename;
		$_module = CI::$APP->router->fetch_module();
		list($path, $file) = Modules::find($target_filename, $_module, 'config/');
		list($env_path, $env_file) = Modules::find($target_filename, $_module, 'config/'.ENVIRONMENT.'/');
		return array_merge(load_yaml($common_filename), load_yaml($path.$file), load_yaml($env_path.$env_file));
	}
}
