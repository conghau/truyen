<?php
if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

	function EncodingFilter() {
		if(isset($_POST) && HTML_ENCODING !== 'UTF-8') {
			$_POST =_mbConvertEncodingEx($_POST, "UTF-8", HTML_ENCODING);
		}
		if(isset($_GET) && HTML_ENCODING !== 'UTF-8') {
			$_GET = _mbConvertEncodingEx($_GET, "UTF-8", HTML_ENCODING);
		}
	}

	/**
	 * mb_convert_encoding()の拡張
	 *
	 * @param  mixed  $target       arrayかstring
	 * @param  string $toEncoding   エンコード先
	 * @param  string $fromEncoding エンコード元(default:null)
	 * @return mixed  arrayが来たらarrayを、stringが来たらstringを
	 */
	function _mbConvertEncodingEx($target, $toEncoding, $fromEncoding = null){
		if (is_array($target)) {
			foreach ($target as $key => $val) {
				if (is_null($fromEncoding)) {
					$fromEncoding = mb_detect_encoding($val);
				}
				$target[$key] = _mbConvertEncodingEx($val, $toEncoding, $fromEncoding);
			}
		} else {
			if  (is_null($fromEncoding)) {
				$fromEncoding = mb_detect_encoding($target);
			}
			$target = mb_convert_encoding($target, $toEncoding, $fromEncoding);
		}
		return $target;
	}
