<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * hex2bin
 */
if ( ! function_exists('hex2bin')) { 
	function hex2bin($data){
		$len = "";
		$data_array = str_split($data,2);
		foreach($data_array as $key){
	       $len .= pack('sX',hexdec($key));
		}
		return $len;
	}
}

/**
 * hex2bin
 */
if ( ! function_exists('hexbin')) { 
	function hexbin($data){
		$temp = str_split($data);
		$bin = "";
		foreach($temp as $key => $val){
			$bin.= str_pad(decbin(hexdec($val)), 4, '0', STR_PAD_LEFT);
		}
		return $bin;
	}
}

/**
 * binhex
 */
if ( ! function_exists('binhex')) { 
	function binhex($data){
		$temp = str_split($data, 4);
		$hex = "";
		foreach($temp as $key => $val){
			$hex.= dechex(bindec($val));
		}
		return $hex;
	}
}

/**
 * bin_and
 */
if ( ! function_exists('bin_and')) { 
	function bin_and($a_bin, $b_bin) {
		$a_arr = str_split($a_bin);
		$b_arr = str_split($b_bin);
		return join("", arr_and($a_arr, $b_arr));
	}
}

/**
 * arr_and
 * ※同じ桁数の配列でないとエラーがでます。
 */
if ( ! function_exists('arr_and')) { 
	function arr_and($arr1, $arr2){
		$result = array();
		
		foreach($arr1 as $key => $val){
			$result[$key] = $arr1[$key] & $arr2[$key];
		}
		return $result;
	}
}


/**
 * bin_or
 */
if ( ! function_exists('bin_or')) { 
	function bin_or($a_bin, $b_bin) {
		$a_arr = str_split($a_bin);
		$b_arr = str_split($b_bin);
		return join("", arr_or($a_arr, $b_arr));
	}
}

/**
 * arr_or
 * ※同じ桁数の配列でないとエラーがでます。
 */
if ( ! function_exists('arr_or')) { 
	function arr_or($arr1, $arr2){
		$result = array();
		
		foreach($arr1 as $key => $val){
			$result[$key] = $arr1[$key] | $arr2[$key];
		}
		return $result;
	}
}


/**
 * bin_xor
 */
if ( ! function_exists('bin_xor')) { 
	function bin_xor($a_bin, $b_bin) {
		$a_arr = str_split($a_bin);
		$b_arr = str_split($b_bin);
		return join("", arr_xor($a_arr, $b_arr));
	}
}

/**
 * arr_xor
 * ※同じ桁数の配列でないとエラーがでます。
 */
if ( ! function_exists('arr_xor')) { 
	function arr_xor($arr1, $arr2){
		$result = array();
		
		foreach($arr1 as $key => $val){
			if($arr1[$key]=="" || $arr2[$key]==""){
				if($arr1[$key]==""){
					$result[$key] = $arr2[$key];
				}else{
					$result[$key] = $arr1[$key];
				}
			}else{
				if($arr1[$key]==$arr2[$key]){
					$result[$key] = "0";
				}else{
					$result[$key] = "1";
				}
			}
		}
		return $result;
	}
}


if ( ! function_exists('dec2hex')) { 
function dec2hex($str) {
    
    // 数字以外は弾く
    if (!ctype_digit($str))
        return false;
        
    // 初期化
    $result = array();
    $a = $str;
    
    // 16進数変換計算ループ
    while (true) {
        
        // 10進数除算ループ
        for (
            $i = 0, $s = 0, $next = '', $len = strlen($a);
            $i < $len;
            $i++
        ) {
            
            // 被除数
            $n = intval($s . $a[$i]);
            
            // 商(＝次の被除数のケタの1つ)
            $next .= $q = (int)($n / 16);
            
            // 余り
            $s = $n - $q * 16;
            
        }
        
        // 余りを16進数変換して記録
        $result[] = dechex($s);
        
        // (※PHPの型の自動変換に依存する比較)
        if ($next < 16) {
            
            // 次の被除数が16未満ならそれを余りとして16進数変換して記録
            $result[] = dechex($next);
            
            // ループ終了
            break;
            
        } else {
            
            // 次の被除数が16以上なら続行
            $a = $next;
            
        }
        
    }
    
    // 余りを逆から連結
    $result = implode('',array_reverse($result));
    
    // 頭の連続する0を取り除いて返す
    if (preg_match('/[1-9a-f].*/',$result,$matches))
        return $matches[0];
    else
        return '0';

}
}
