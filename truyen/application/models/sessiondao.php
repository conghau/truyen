<?php
/**
 * セッションデータアクセスオブジェクト
 * @name SessionDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php'; 
class SessionDao extends MY_DataMapper {
	
	/**
	 * テーブル名定義。
	 */
	public $table = "sessions";
	
	/**
	 * SessionDaoの初期化メソッド。
	 */
	public function __construct($db_params = DEFAULT_DB, $cache_time = 0) {
		$CI =& get_instance();
        $CI->load->helper('dateutil');
		$this->db_params = $db_params;
		parent::__construct($db_params);
		
		if ($cache_time > 0) {
			$this->setup_cache($cache_time);
		}
	}
	
	function find_by_token($token) {
		$result = $this->where('token', $token)->get()->all;
		if (count($result) > 0) {
			return array_shift($result);
		} else {
			return null;
		}
	}
	
	function update_session($user_id, $token, $user_type, $expired_at) {
		$result = $this->find_by_token($token);
		$recordset = array('user_id' => $user_id, 'token' => $token, 'expired_at' => $expired_at, 'user_type' => $user_type, 'status' => 1);
		if (empty($result)) {
			$this->clear();
			$this->insert_recordset($recordset);
		} else {
			$this->clear();
			$this->update_recordset($result->id, $recordset);
		}
	}

	function insert_recordset($recordset = array()) {
		$insert_date = date("Y-m-d H:i:s");
		$this->created_at = $insert_date;
		$this->updated_at = $insert_date;
		$target = array('user_id', 'token', 'user_type', 'expired_at', 'status');

		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$this->{$key} = $recordset[$key];
			}
		}
		$this->save();		
	}

	function update_recordset($id, $recordset = array()) {
		$this->id = $id;
		$target = array('user_id', 'token', 'user_type', 'expired_at', 'status');

		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;

		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$target_recordset[$key] = $recordset[$key];
			}
		}

		$this->where('id', $id)
			->update($target_recordset);		
	}
	
	function logout($user_id, $token) {
		return $this->db->delete($this->table, array('token' => $token, 'user_id' => $user_id));
	}
}