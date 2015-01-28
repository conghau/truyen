<?php

/**
 * 一時利用者情報アクセスオブジェクト
 * @name PostDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class Tmp_UserDao extends MY_DataMapper {
	
	var $table = 'tmp_users';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	/**insert
	 * @param  $data
	* @return string
	*/
	public function insert($recordset) {
		
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at= $current_date;
		
		$target = array('login_id', 'password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name','gender','birthday',
				'qualification_id','qualification','organization','confirm_image_url','confirm_organization','confirm_phone_number',
				'auth_method','registered_type','registered_status','expired_at','status', 'token','recommend_user_id','language'
		);
		
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$this->{$key} = trim($recordset[$key]);
			}
		}
		$this->trans_begin();
		$result = $this->save();
		if (!$result) {
			$this->trans_rollback();
			return 0;
		}
		$this->trans_commit();
		return $this->id;
	}
	
	public function get_active_user_by_token($token) {
		$result = $this->where('token',$token)
				->where('expired_at >=',date('Y-m-d H:m:s'))->get();
		return $result;
	}
	
	public function find_by_token($token) {
		$this->where('token', $token);
		$result = $this->get();
		if (count($result) > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * update
	 * @param  $data
	 * @return string
	 */
	public function update_user($id, $recordset = array()) {
		$this->id = $id;
		$target = array('login_id','password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name','gender','birthday',
				'qualification_id','qualification','organization','department','phone_number','domain','history',
				'university','scholar','author','society','hobby','message','company_code','auth_method','status',
				'specialist','confirm_image_url','confirm_organization','confirm_phone_number','expired_at','language',
				'registered_status','token'
		);
	
		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$target_recordset[$key] = (trim($recordset[$key]) !== "") ? trim($recordset[$key]) : NULL;
			}
		}
		$this->trans_begin();
		$result =  $this->where('id', $id)->update($target_recordset);
		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
	
	/**
	 * Get record by token
	 * @param string $token
	 * @return Object
	 */
	public function get_by_token($token) {
		$result = $this->where('token', $token)
		->where('expired_at', NULL)->get();
		return $result;
	}
	
	/**
	 * Get tmp_user by email
	 * @param  $email
	 * @return object
	 */
	public function get_by_email($email) {
		$this->select('recommend_user_id');
		$this->where('email',$email);
		return $this->get();
	}

	/**
	 * ユーザIDで削除する
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete_by_user_id($user_id) {
		$userdao = new UserDao();
		$user = $userdao->get_user($user_id);
		if ($user->result_count() > 0) {
			$login_id = $user->login_id;
			$this->where('login_id',$login_id)->get()->delete();
			return TRUE;
		}
		return FALSE;
	}
}