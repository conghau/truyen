<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter HtmlUtil Helpers
 *
 * html util helpers.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		
 * @link		
 */

// --------------------------------------------------------------------

/**
*/
if ( ! function_exists('h'))
{
	function h( $text ) {
		return htmlspecialchars($text, ENT_QUOTES);
	}
}



if ( ! function_exists('user_name'))
{
	function user_name( $dto, $language = 'japanese') {
		if ($language === 'japanese') {
			$user_name = array_selector('last_name_ja', $dto).' '.array_selector('first_name_ja', $dto);
		} else {
			$user_name = array_selector('first_name', $dto).' '.array_selector('last_name', $dto);
		}
		return $user_name;
	}
}

if (!function_exists('get_file_ext_type')) {
	function get_file_ext_type($ext) {
		switch ($ext) {
		case 'jpeg':
		case 'jpg':
		case 'png':
		case 'gif':
		case 'wmv':
		case 'mp4':
		case 'm4v':
		case 'm4a':
			return 'img';
		case 'xls':
		case 'xlsx':
			return 'xls';
		case 'ppt':
		case 'pptx':
			return 'ppt';
		case 'doc':
		case 'docx':
			return 'doc';
		case 'txt':
			return 'txt';
		case 'zip':
			return 'zip';
		default:
			return 'other';
		}
	}
	
}

if ( ! function_exists('truncate'))
{

	/**
	 * Smarty truncate modifier plugin
	 * 
	 * Type:     modifier<br>
	 * Name:     truncate<br>
	 * Purpose:  Truncate a string to a certain length if necessary,
	 *               optionally splitting in the middle of a word, and
	 *               appending the $etc string or inserting $etc into the middle.
	 * 
	 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
	 * @author Monte Ohrt <monte at ohrt dot com> 
	 * @param string  $string      input string
	 * @param integer $length      length of truncated text
	 * @param string  $etc         end string
	 * @param boolean $break_words truncate at word boundary
	 * @param boolean $middle      truncate in the middle of text
	 * @return string truncated string
	 */
	function truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
	    if ($length == 0)
	        return '';
	
	    if (Smarty::$_MBSTRING) {
	        if (mb_strlen($string, Smarty::$_CHARSET) > $length) {
	            $length -= min($length, mb_strlen($etc, Smarty::$_CHARSET));
	            if (!$break_words && !$middle) {
	                $string = preg_replace('/\s+?(\S+)?$/' . Smarty::$_UTF8_MODIFIER, '', mb_substr($string, 0, $length + 1, Smarty::$_CHARSET));
	            } 
	            if (!$middle) {
	                return mb_substr($string, 0, $length, Smarty::$_CHARSET) . $etc;
	            }
	            return mb_substr($string, 0, $length / 2, Smarty::$_CHARSET) . $etc . mb_substr($string, - $length / 2, $length, Smarty::$_CHARSET);
	        }
	        return $string;
	    }
	    
	    // no MBString fallback
	    if (isset($string[$length])) {
	        $length -= min($length, strlen($etc));
	        if (!$break_words && !$middle) {
	            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
	        } 
	        if (!$middle) {
	            return substr($string, 0, $length) . $etc;
	        }
	        return substr($string, 0, $length / 2) . $etc . substr($string, - $length / 2);
	    }
	    return $string;
	} 
}


if ( ! function_exists('isAjax'))
{

/**
 * Ajaxによるリクエストかどうか判定
 *
 * @return boolean True or False
 */
function isAjax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }
    return false;
}
}

/**
 * URL にリンクを付与する
 */
if ( ! function_exists('addTagA')) {
	function addTagA($text){
		$text = preg_replace('/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u', '<a href="$1" target="_blank">$1</a>', $text);
		return $text;
	}
}