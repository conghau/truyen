<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter SQL Helpers
 *
 * SQL Useful Helper Libraries.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author Takeo Noda Sevenmedia Inc.
 * @link		
 */

// --------------------------------------------------------------------

/**
 * 主に like 検索対策
 * @param string $text 文字列
 * @return 文字列
 */
if ( ! function_exists('like_escape')) {
	function like_escape($text) {
		$text = str_replace("\\", "\\\\", $text);
		$text = str_replace('%', '\%', $text);
		$text = str_replace('_', '\_', $text);
		return $text;
	}
}

/* End of file sqlutil_helper.php */
/* Location: ./application/helpers/sqlutil_helper.php */