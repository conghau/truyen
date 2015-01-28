<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter ファイル関連ヘルパー関数
 *
 * @package		CodeIgniter
 * @category	Helpers
 * @copyright   (c) 2012 Sevenmedia Inc.
 * @author		Takeo Noda
 */

// --------------------------------------------------------------------

/**
 * ファイルがバイナリかチェックします。
 * @param $filename ファイル名
 * @return バイナリなら true
 */
if (!function_exists('is_binary')) {
	function is_binary($fiename) {
		if (file_exists($filename)) {
			$fp = fopen($filename,'r');
			while(!feof($fp)){
				$line = rtrim(fgets($fp,4096));
				if (preg_match('/\0/', $line)) {
					return true;
				}
			}
		}
		return false;
	}
}

/**
 * ディレクトリを作成します。
 * @param $dir ディレクトリ名
 * @return なし
 */
if (!function_exists('make_dir')) {
	function make_dir($dir) { 
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
		}
	}
}

/**
 * コマンドを実行する
 * @param 	String		$filename		ファイル名
 * @return 	Array		$ret			取得したデータ一覧
 */
if (!function_exists('execute_command')) {
	function execute_command( $command ) {
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
 * ファイルを読み込む
 * @param 	String		$filename		ファイル名
 * @return 	Array		$ret			取得したデータ一覧
 */
if (!function_exists('load_file')) {
	 function load_file( $filename ) {
		$ret = array();
	
		// ファイル名がない場合には空を返す
		if (!file_exists($filename)) {
			return $ret;
		}
	
		$fp = fopen($filename,'r');
		while(!feof($fp)){
			$line = fgets($fp,1024);
			array_push($ret, $line);
		}
		fclose($fp);
		return $ret;
	}
}
/**
 * ファイルを保存する
 * @param 	String		$filename		ファイル名
 * @param 	Array		$data			保存するデータ一覧
 */
if (!function_exists('save_file')) {
	function save_file( $filename, $data ) {
		$ret = array();
	
		// output の一覧整形
		if (is_array($data)) {
			$_data = $data;
		} else {
			$_data = array($data);
		}
	
		$fp = fopen($filename,'w');
		foreach ($_data as $line) {
			fputs($fp, $line);
		}
		fclose($fp);
		$prev = error_reporting(0);
		chmod( $filename, 0777 );
		error_reporting($prev);
	}
}

// 関数
if (!function_exists('remove_dir')) {
	function remove_dir($path, $dir_remove = true){
		$list = scandir($path);
		$length = count($list);
		for($i = 0; $i < $length; $i++){
			if($list[$i] === '.' || $list[$i] === '..'){
				continue;
			}
			if(is_dir($path.'/'.$list[$i])){
				remove_dir($path.'/'.$list[$i], $dir_remove);
			}else{
				unlink($path.'/'.$list[$i]);
			}
		}
		if ($dir_remove) {
			rmdir($path);
		}
	}
}
	