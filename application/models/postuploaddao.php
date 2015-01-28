<?php

/**
 * 投稿-アップロード情報アクセスオブジェクト
 * @name PostUploadDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class PostUploadDao extends MY_DataMapper{
	
	public $table ="post_uploads";
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	public function count_by_post_id($post_id) {
		$this->select("count(*) As totalRecords ");
		$this->where("post_id", $post_id);
		$result = $this->get();
		return $result->totalRecords;
	}
	
	public function get_by_upload_id($id) {
		$this->where("upload_id", $id);
		$result = $this->get();
		return $result;
	}
	
	public function get_by_file_id($file_id) {
		$this->select("post_uploads.post_id, uploads.id");
		$this->db->join("uploads", "uploads.id = post_uploads.upload_id", "inner");
		$this->where("file_id", $file_id);
		$result = $this->get()->all;
		return $result;
	}
	
	/**
	 * 「投稿-アップロード」テーブルにデータを挿入する。
	 * @param $owner_id オーナーID
	 * @param $user_id ユーザーID
	 * @return boolean
	 */
	public function insert_post_upload($post_id, $upload_id) {
		$currentDate = date("Y-m-d H:i:s",time());
		$this->post_id			= $post_id;
		$this->upload_id		= $upload_id;
		$this->update_at		= $currentDate;
		$result = $this->save_as_new();
		if (!$result) {
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * 有効期限種別をチェックする
	 * @param unknown_type $post_id
	 * @param unknown_type $type
	 * @return boolean
	 */
	public function check_expired_type($post_id, $type) {
		$this->db->join('uploads', ('uploads.id = post_uploads.upload_id'));
		$this->where('post_uploads.post_id', $post_id);
		$this->where('uploads.expired_type', $type);
		$this->where('uploads.deleted_at', null);
		$result = $this->get()->result_count();
		if (!$result) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function get_file_expired_by_type($post_id, $type) {
		$this->select ('uploads.id, uploads.expired_at');
		$this->db->join('uploads', 'uploads.id = post_uploads.upload_id', 'join');
		$this->where('post_uploads.post_id', $post_id);
		$this->where('uploads.expired_type', $type);
		$this->where('uploads.deleted_at', null);
		return $this->get();
	}

}
