<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter 配列ヘルパー関数
 *
 * @package		CodeIgniter
 * @category	Helpers
 * @copyright   (c) 2012 Sevenmedia Inc.
 * @author		Takeo Noda
 */

// --------------------------------------------------------------------

/**
 * 配列を指定したキーでグルーピングします。
 * @param $key キー名（プロパティ名）
 * @param $array 配列
 * @return $key でグルーピング。グループ化できないときはそのまま返す。
 */
if ( ! function_exists('array_grouping_by_key')) {
	function array_grouping_by_key($key, $array, $keysort = TRUE) {
		$new_array = array();
		foreach ($array as $recordset) {
			if (is_object($recordset) && isset($recordset->{$key})) {
				$_key = $recordset->{$key};
			} else if (is_array($recordset) && array_key_exists($key, $recordset)) {
				$_key = $recordset[$key];
			} else {
				$_key = '-';
			}
			if (!array_key_exists($_key, $new_array) || !isset($new_array[$_key])) {
				$tmp_array = array();
			} else {
				$tmp_array = $new_array[$_key];
			}
			array_push($tmp_array, $recordset);
			$new_array[$_key] = $tmp_array;
		}
		if ($keysort) {
			ksort($new_array);			
		}
		return $new_array;
	}
}

/**
 * 配列を指定したキーでインデックス化します。
 * @param $key キー名（プロパティ名）
 * @param $array 配列
 * @return $key でインデックス化。
 */
if ( ! function_exists('array_indexing_by_key')) {
	function array_indexing_by_key($key, $array, $keysort = TRUE) {
		$new_array = array();
		foreach ($array as $recordset) {
			if (is_object($recordset) && isset($recordset->{$key})) {
				$_key = $recordset->{$key};
			} else if (is_array($recordset) && array_key_exists($key, $recordset)) {
				$_key = $recordset[$key];
			} else if (!empty($recordset) && empty($key)) {
				$_key = $recordset;
			} else {
				$_key = '-';
			}
			$new_array[$_key] = $recordset;
		}
		return $new_array;
	}
}


/**
 * 配列を指定したキーでインデックス化します。
 * @param $key キー名（プロパティ名）
 * @param $array 配列
 * @return $key でインデックス化。
 */
if ( ! function_exists('array_indexing_by_keys')) {
	function array_indexing_by_keys($key, $array, $keysort = TRUE, $splitter=":") {
		$new_array = array();
		if (!is_array($key)) {
			$keys = array($key);
		} else {
			$keys = $key;
		}
		foreach ($array as $recordset) {
			$target_key = array();
			foreach ($keys as $ckey) {
				if (is_object($recordset) && isset($recordset->{$ckey})) {
					$_key = $recordset->{$ckey};
				} else if (is_array($recordset) && array_key_exists($ckey, $recordset)) {
					$_key = $recordset[$ckey];
				} else if (!empty($recordset) && empty($ckey)) {
					$_key = '_';
				} else {
					$_key = '-';
				}
				array_push($target_key, $_key);
			}
			$new_key = implode($splitter, $target_key);
			$new_array[$new_key] = $recordset;
		}
		return $new_array;
	}
}

/**
 * 配列をキーによってフィルターします。
 * @param $key キー名（プロパティ名）
 * @param $array 配列
 * @return $key でインデックス化。
 */
if ( ! function_exists('array_filter_by_keys')) {
	function array_filter_by_keys($array, $keys) {
		$new_array = array();
		foreach ($array as $key => $recordset) {
			if (array_search($key, $keys) !== FALSE) {
				$new_array[$key] = $recordset;				
			}
		}
		return $new_array;
	}
}

/**
 * 配列を値によってフィルターします。
 * @param $key キー名（プロパティ名）
 * @param $array 配列
 * @return $key でインデックス化。
 */
if ( ! function_exists('array_filter_by_values')) {
	function array_filter_by_values($array, $values) {
		$new_array = array();
		foreach ($array as $key => $recordset) {
			if (array_search($recordset, $values) !== FALSE) {
				$new_array[$key] = $recordset;				
			}
		}
		return $new_array;
	}
}

/**
 * キーと配列から値を取得します。対応値がない場合はデフォルト値を表示します。
 * @param $key キー値
 * @param $array 配列
 * @param $default デフォルト値
 * @return 選択された値
 */
if ( ! function_exists('array_selector')) {
	function array_selector($key, $array, $default = "", $subkey = "") {
		if (is_object($array) && isset($array->{$key})) {
			$result = $array->{$key};
			if (!empty($subkey)) {
				$result = array_selector($subkey, $result, $default);
			}
		} else if (is_array($array) && isset($array[$key])) {
			$result = $array[$key];
			if (!empty($subkey)) {
				$result = array_selector($subkey, $result, $default);
			}
		} else {
			$result = $default;
		}
		return $result;
	}
}

/**
 * 連想配列かチェックします。
 * @param $array 配列
 * @return true: 連想配列 / false: 連想配列でない
 */
if ( ! function_exists('is_hash')) {
	function is_hash(&$array) {
	    reset($array);
	    list($k) = each($array);
	    return $k !== 0;
	}
}

/**
 * 指定した区間の配列を取得する。
 * @param 	Integer		$start_idx		開始値
 * @param 	Integer		$end_idx		終了値
 * @param 	Integer		$interval		間隔
 * @return 取得したデータ
 */
if ( ! function_exists('make_serial_array')) {
	function make_serial_array( $start_idx, $end_idx, $interval = 1 ) {
		$list = array();
		for ($xi = $start_idx; $xi <= $end_idx; $xi = $xi+$interval) {
			array_push($list, $xi);
		}
		return $list;
	}
}

/**
 * 連想配列をマージします。
 */
if (! function_exists('array_assoc_merge')) {
	function array_assoc_merge($array1, $array2) {
		$array2 = array_reverse($array2, true);
		$array1 = array_reverse($array1, true);
		foreach ($array1 as $key => $value) {
			$array2[$key] = $value;
		}
		return array_reverse($array2, true);
	}
}

if (! function_exists('obj2arr')) {
	function obj2arr($obj)  {  
	    if ( !is_object($obj) ) return $obj;  
	  
	    $arr = (array) $obj;  
	    foreach ( $arr as &$a ) {  
	        $a = obj2arr($a);  
	    }  
	  
	    return $arr;  
	}
}  

if (! function_exists('is_not_array')) {
	function is_not_array($var)  {  
	    return !is_array($var);  
	}
}  
/* End of file inflector_helper.php */
/* Location: ./application/helpers/inflector_helper.php */