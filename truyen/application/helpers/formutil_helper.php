<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter HtmlUtil Helpers
 *
 * form util helpers.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		
 * @link		
 */

// --------------------------------------------------------------------

/**
 * Form Close Tag
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('form_close'))
{
	function form_close($csrf_off = '')
	{
		$CI =& get_instance();
		$token_name = $CI->security->get_csrf_token_name();
		if (array_key_exists($token_name, $_POST)) {
			$token_value = $_POST[$token_name];			
		} else {
			$token_value = $CI->security->get_csrf_hash();	
			log_message('debug', 'making CSRF hash: '.$token_value);		
		}
		$form = "";
		$form .= sprintf('<input type="hidden" name="%s" value="%s" />', $token_name, $token_value);
		$form .= "\n</form><!-- overwrite -->";
		return $form;
	}
}


/* End of file formutil_helper.php */
/* Location: ./application/helpers/formutil_helper.php */