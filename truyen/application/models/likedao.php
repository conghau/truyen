<?php

/**
 * アクティビティデータアクセスオブジェクト
 * @name LikeDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';

class LikeDao extends MY_DataMapper{
	
	public $table ="likes";
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	/**
	 * ＩＤでＬｉｋｅ数を取得する
	 * @param int $post_id
	 * @author dao-lta
	 */
	public function count_by_post_id($post_id) {
		$this->select('count(*) as like_number');
		$this->where('post_id', $post_id);
		$result = $this->get();
		return $result->like_number;
	}
}
