<?php
/**
 * DataMapperの拡張。キャッシュ保持に対応。
 * @name MY_DataMapper
 * @copyright (C)2013 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
class MY_DataMapper extends DataMapper {

	/**
	 * ファイルの初期化メソッド。
	 */
	public function __construct($db_params = SLAVE) {
		parent::__construct();
		$this->init_log($db_params);
	}

	/*
	 * $timespan 秒
	 */
	public function setup_cache($timespan = CACHE_DB_TIME) {
		if ($this->db->cachedir == '') {
			return $this->db->cache_off();
		}
		$segment_one = ($this->uri->segment(1) == FALSE) ? 'default' : $this->uri->segment(1);
		$segment_two = ($this->uri->segment(2) == FALSE) ? 'index' : $this->uri->segment(2);
		$dir_path = $this->db->cachedir.$segment_one.'+'.$segment_two.'/';
		if (is_dir($dir_path) && filemtime($dir_path) + $timespan < time()) {
			$this->db->cache_delete($segment_one, $segment_two);
			log_message('debug', 'Delete DB Cache: '.$dir_path);
		}
		$this->db->cache_on();
		log_message('debug', 'Begin DB Cache: '.time2datetime(filemtime($dir_path))."(".filemtime($dir_path).":".$dir_path.")");
	}
	
	/**
	 * 
	 */
	 public function init_log($db_params) {
		$debug_backtrace = debug_backtrace();
		// data mapper から dto のモデルとして dao を呼び出している個所があるので、そこはログを出さないよう調整
		$caller = array_selector(3, array_keys(array_indexing_by_keys(array('file', 'line'), $debug_backtrace)));
//		log_message('debug', $caller);
		if (strpos($caller, 'datamapper')=== false) {
			log_message('debug', get_class($this) . ' connect to ' . $db_params. ' ('. $caller.')');
		}	 	
	 }
}
	