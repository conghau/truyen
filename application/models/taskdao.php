<?php
/**
 * 
 * @copyright (C)2014 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class TaskDao extends MY_DataMapper {
	var $table = 'tasks';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * Get List 
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	function count_list() {
		$this->select(" count(tasks.id) as totalRecord");
		$result = $this->where("status", '1')->get();
		return $result->totalRecord;
	}

	/**
	 * Get List 
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	function get_list($offset = 0, $limit = 100) {
		$results = $this->where('status', '1')->order_by("created_at", "ASC")->get($limit,$offset)->all;
		return $results;
	}
	
	function delete_mail($arr_id) {
		$binds = implode(',', $arr_id);
		$this->db->query("DELETE FROM mails WHERE mails.id IN (".$binds.")");
	}

	public function insert_recordset($user_id, $type, $upload_dir, $post_id, $expire_type, $encryption_key = "") {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at = $current_date;
		$this->user_id = trim($user_id);
		$this->data = sprintf("%s,%s,%s,%s", $upload_dir, $post_id, $expire_type, $encryption_key);
		$this->status = 1;
		$this->type = $type;

		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	/**
	 * ステータスを更新する
	 * @param $id ID
	 * @param $status ステータス
	 * @return boolean 実行結果
	 */
	public function update_status($id, $status) {
		$cur_date_time = date("Y-m-d H:i:s");
		$task = array();
		$task['status'] = $status;
		$task['updated_at'] = $cur_date_time;
		$this->where('id =', $id);
		
		$result = $this->update($task);
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}

	/**
	 * ユーザIDで削除する
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete_by_user_id($user_id) {
		$this->where('user_id', $user_id);
		$this->where("deleted_at", null);
		$result = $this->update(array('deleted_at' => date("Y-m-d H:i:s")));
		return $result;
	}
}