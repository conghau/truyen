<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter DICOMヘルパー関数
 *
 * @package		CodeIgniter
 * @category	Helpers
 * @copyright   (c) 2014 Sevenmedia Inc.
 * @author		Takeo Noda
 */

// --------------------------------------------------------------------
if (!defined('TOOLKIT_DIR')) {
	define('TOOLKIT_DIR', '/usr/local/bin');
}
define('BIN_DCMDUMP', 	TOOLKIT_DIR . '/dcmdump');
define('BIN_STORESCU', 	TOOLKIT_DIR . '/storescu');
define('BIN_STORESCP', 	TOOLKIT_DIR . '/storescp');
define('BIN_ECHOSCU', 	TOOLKIT_DIR . '/echoscu');
define('BIN_DCMJ2PNM', 	TOOLKIT_DIR . '/dcmj2pnm');
define('BIN_DCMODIFY', 	TOOLKIT_DIR . '/dcmodify');
define('BIN_DCMCJPEG', 	TOOLKIT_DIR . '/dcmdjpeg');
define('BIN_DCMDJPEG', 	TOOLKIT_DIR . '/dcmcjpeg');
define('BIN_XML2DCM', 	TOOLKIT_DIR . '/xml2dcm');
define('BIN_IMG2DCM', 	TOOLKIT_DIR . '/img2dcm');
define('BIN_IMAGEMAGICK_FILE', '/usr/bin/file');

/**
 * コマンド実行結果を記録する処理
 */
if ( ! function_exists('execute_command')) {
	function execute_command($command) {
		$command .= ' 2>&1';
		
		log_message('debug', 'CMD: '.$command);
		$fh = popen($command, 'r');
		$result = array();
		while (!feof($fh)) {
			$line = fread($fh, 1024);
			$result[] = $line;
		}
		pclose($fh);
		log_message('debug', 'RESULT: '.join('', $result));
		return $result;
	}
}

/**
 * 画像かどうか判定
 */
if ( ! function_exists('is_image')) {
	function is_image($file_path, $ext = "") {
		$dump_cmd = BIN_IMAGEMAGICK_FILE . " $file_path";
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}

		if(strstr($dump, 'error')) {
			return 0;
		} else if(strstr($dump, 'image data')) {
			return 1; // 画像
		}
		return 0;
	}
}

/**
 * DICOM かどうか判定
 */
if ( ! function_exists('is_dicom')) {
	function is_dicom($file) {
		$dump_cmd = BIN_IMAGEMAGICK_FILE . " $file";
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}

		if(strstr($dump, 'error')) {
			return 0;
		} else if(strstr($dump, 'DICOM medical imaging data')) {
			return 1; // 画像
		}
		return 0;
	}
}

/**
 * DICOM ファイルから拡張子を取得
 */
if ( ! function_exists('get_dicom_file_extension')) {
	function get_dicom_file_extension($file, $splitter = '.') {
		$dump_cmd = BIN_IMAGEMAGICK_FILE . " $file";
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}

		if(strstr($dump, 'JPEG')) {
			return $splitter.'jpg';
		} else if(strstr($dump, 'GIF')) {
			return $splitter.'gif'; 
		} else if(strstr($dump, 'PNG')) {
			return $splitter.'png'; 
		} else if(strstr($dump, 'data')) {
			return $splitter.'data'; 
		}
		return '';
	}
}

/**
 * DICOM Sequence かどうか判定
 */
if ( ! function_exists('count_dicom_pixel_sequence')) {
	function count_dicom_pixel_sequence($file) {
	    $dump_cmd = BIN_DCMDUMP . " -M +L +Qn $file 2>&1 | grep PixelSequence";
	    $dump = execute_command($dump_cmd);
	    if(empty($dump)) {
	    	return false;
	    }
		if (is_array($dump)) {
			$dump = join('', $dump);
		}
		if (!preg_match('/PixelSequence #=(\d+)/', $dump, $matches)) {
			return 0;
		}
		return $matches[1];
	}
}

/**
 * DICOM 画像の解凍処理
 */
/**
 * DICOM かどうか判定
 */
if ( ! function_exists('unzip_dicom_pixel_sequence')) {
	function unzip_dicom_pixel_sequence($file, $dir) {
	    $dump_cmd = BIN_DCMDUMP . " +W $dir $file";
	    $dump = execute_command($dump_cmd);
	    if(empty($dump)) {
	    	return 0;
	    }
		if (is_array($dump)) {
			$dump = join('', $dump);
		}
		return $dump;
	}
}

/**
 * dicomタグを読込
 */
if ( ! function_exists('load_dicom_tags')) {
  function load_dicom_tags($file) {
    $dump_cmd = BIN_DCMDUMP . " -M +L +Qn $file";
    $dump = execute_command($dump_cmd);

    if(empty($dump)) {
      return(0);
    }
    $tags = array();

    foreach($dump as $line) {

      $ge = '';

      $t = preg_match_all("/\((.*)\) [A-Z][A-Z]/", $line, $matches);
      if(isset($matches[1][0])) {
        $ge = $matches[1][0];
        if(!isset($tags["$ge"])) {
          $tags["$ge"] = '';
        }
      }

      if(!$ge) {
        continue;
      }

      $val = '';
      $found = 0;
      $t = preg_match_all("/\[(.*)\]/", $line, $matches);
      if(isset($matches[1][0])) {
        $found = 1;
        $val = $matches[1][0];

        if(is_array($tags["$ge"])) { // Already an array
          $tags["$ge"][] = $val;
        }
        else { // Create new array
          $old_val = $tags["$ge"];
          if($old_val) {
            $tags["$ge"] = array();
            $tags["$ge"][] = $old_val;
            $tags["$ge"][] = $val;
          }
          else {
            $tags["$ge"] = $val;
          }
        }
      }

      if(is_array($tags["$ge"])) {
        $found = 1;
      }

      if(!$found) { // a couple of tags are not in [] preceded by =
        $t = preg_match_all("/\=(.*)\#/", $line, $matches);
        if(isset($matches[1][0])) {
          $found = 1;
          $val = $matches[1][0];
          $tags["$ge"] = rtrim($val);
        }
      }

      if(!$found) { // a couple of tags are not in []
        $t = preg_match_all("/[A-Z][A-Z] (.*)\#/", $line, $matches);
        if(isset($matches[1][0])) {
          $found = 1;
          $val = $matches[1][0];
          if(strstr($val, '(no value available)')) {
            $val = '';
          }
          $tags["$ge"] = rtrim($val);
        }
      }
    }
	return $tags;
  }
}