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
define('BIN_FFMPEG', 	TOOLKIT_DIR . '/ffmpeg');
define('BIN_FFPROBE', 	TOOLKIT_DIR . '/ffprobe');

// 1: %s 元ファイル
// 2: %d 開始秒
// 3: %d フレーム数
// 4: %s 画面サイズ
// 5: %s 出力ファイル
define('CMD_MAKE_VIDEO_THUMBNAIL', BIN_FFMPEG . ' -y -i %s -ss 00:00:%02d -vframes %d -f image2 -s %s %s');
define('CMD_MAKE_MJPEG_THUMBNAIL', BIN_FFMPEG . ' -y -i %s -f image2 -s %s %s');
define('CMD_VIDEO_STATS', BIN_FFPROBE . ' %s 2>&1 | grep Video');
define('CMD_VIDEO_DURATION', BIN_FFPROBE . ' %s 2>&1 | grep Duration');


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
 * 動画かどうか判定
 */
if ( ! function_exists('is_video')) {
	function is_video($file_path, $ext = "") {
		if (!empty($ext) && preg_match('/(jpg|jpeg|png|gif)/i', $ext)) {
			return false;			
		}
		$dump_cmd = sprintf(CMD_VIDEO_STATS, $file_path);
		log_message('debug', $dump_cmd);
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}
		log_message('debug', $dump);

		// データタイプを適宜調整
		if(!empty($dump) && preg_match('/Video:\s(wmv|h26\d|mpeg2video|mjpeg)/', $dump)) {
			return 1;
		}
		return 0;
	}
}


/**
 * 動画ファイルからサムネイルを作るコマンドを生成
 * @param $org_file_path 元ファイル
 * @param dest_file_path 出力ファイル
 * @param $sec 開始秒
 * @param $frame 開始フレーム数
 * @param $size 画面サイズ
 * @return 生成コマンド
 * 
 * 使用例：　ffmpeg -y -i intuitrak.wmv -ss 00:00:00 -vframes 1 -f image2 -s 520x520 int.jpg
 */
if ( ! function_exists('make_video_thumbnail_cmd')) {
	function make_video_thumbnail_cmd($org_file_path, $dest_file_path, $sec, $frame, $size) {
		if (empty($sec) || empty($frame)) {
			return sprintf(CMD_MAKE_MJPEG_THUMBNAIL, $org_file_path, $size, $dest_file_path);
		} else {
			return sprintf(CMD_MAKE_VIDEO_THUMBNAIL, $org_file_path, $sec, $frame, $size, $dest_file_path);
		}
	}
}

/**
 * 動画ファイルから画角情報を取得する
 * @param $file_path 対象ファイル
 * @return 画角情報 array(x, y, type ファイル種別) + 
 */
if ( ! function_exists('video_imagexy')) {
	function video_imagexy($file_path) {
		$dump_cmd = sprintf(CMD_VIDEO_STATS, $file_path);
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}

		// 画角を取得
		if(!empty($dump) && preg_match('/\s([1-9]\d+x[1-9]\d+)[,\s]/', $dump, $matches)) {
			if (preg_match('/Video:\s(wmv|h26\d|mpeg2video|mjpeg)/', $dump, $type_matches)) {
				$type = $type_matches[1];
			} else {
				$type = "";
			}
			log_message('debug', 'Video size: '.$matches[1]."|".$type);
			return explode('x', $matches[1]."x".$type);
		}
		return array(false, false);
	}
}

/**
 * 動画ファイルから画角情報を取得する
 * @param $file_path 対象ファイル
 * @return 画角情報 array(x, y, type ファイル種別) + 
 */
if ( ! function_exists('video_duration')) {
	function video_duration($file_path) {
		$dump_cmd = sprintf(CMD_VIDEO_DURATION, $file_path);
		$dump = execute_command($dump_cmd);
		if (is_array($dump)) {
			$dump = join('', $dump);
		}

		// 画角を取得
		if(!empty($dump) && preg_match('/Duration:\s(\d\d:\d\d:\d\d)[\.\s](\d\d)/', $dump, $matches)) {
			log_message('debug', 'Video duration: '.$matches[1]);
			return $matches[1];
		}
		return false;
	}
}