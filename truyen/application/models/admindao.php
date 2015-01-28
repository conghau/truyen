<?php
/**
 * ユーザーデータアクセスオブジェクト
 * @name AdminDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';   
class AdminDao extends MY_DataMapper {

	var $table = 'admins';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	function login($id, $password) {
		$sqlWhere['login_id'] = $id;
		$sqlWhere['password'] = $password;
		$sqlWhere['deleted_at'] = NULL;
		$sqlWhere['status'] = 1;
		$this->where($sqlWhere);
		$result = $this->get();
		if ($result->result_count() === 1) {
			return $result;
		} else {
			return null;
		}
	}
	
	function verify_session($token) {
		$this->select('admins.id, admins.id as user_id ,role, last_name_ja, first_name_ja, last_name, first_name, language');
		$this->db->join('sessions','sessions.user_id = admins.id and sessions.status = 1 and sessions.expired_at >= now()','right');
		$sqlWhere['token'] = $token;
		$sqlWhere['user_type'] = 2;
		$this->where($sqlWhere);
		$result = $this->get();
		if ($result->result_count() === 1) {
			return $result;
		} else {
			return null;
		}
	}
	
	public function countMember() {
		return $this->get()->result_count();
	}
	
	public function get_all() {
		return $this->where('deleted_at',NULL)->get();
	}
	
	/**
	 * Check exists data in table
	 * @return boolean
	 */
	public function is_has_data() {
		$result = $this->select('id')->get()->result_count();
		if ($result > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * 会員の各レコードを検索するメソッド
	 * @param $condition 検索条件情報
	 * @param $order　ソート種別
	 * @return 条件に満たしたページのレコード
	 */
	public function search($condition,$limit = NULL) {
		// 条件作成を実行
		
		$sqlWhere = $this->createWhere($condition);
		if (NULL === $limit) {
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])->get();
		} else {
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])
			->order_by('updated_at','DESC')
			->limit($limit[0], $limit[1])->get();
		}
		return $result;
	}
	
	/**
	 * 条件を満たした総件数を取得する。
	 * @param $condition 検索条件情報
	 * @return number
	 */
	public function count_by_condition($condition) {
		$this->select(" count(*) As totalRecords ");
		$sqlWhere = $this->createWhere($condition);
		$this->like($sqlWhere[0]);
		$this->where($sqlWhere[1]);
		$result = $this->get();
		return $result->totalRecords;
	}
	
	/**
	 * Search Data
	 * @param : $condition
	 */
	private function createWhere($condition) {
		$sqlWhere = array();
		$conditionWhere = array();
		$conditionLike = array();
		$conditionWhere['deleted_at'] = null;
		if (!isset($condition)) {
			$sqlWhere[0] = $conditionLike;
			$sqlWhere[1] = $conditionWhere;
			return $sqlWhere;
		}
		
		$likeTarget = array('login_id', 'email', 'last_name_ja', 'first_name_ja');
		foreach ($likeTarget as $target) {
			if (isset($condition[$target]) && trim($condition[$target]) !== '') {
				$conditionLike[$target] = trim($condition[$target]);
			}
		}
		
		$equalTarget = array('id', 'gender');
		foreach ($equalTarget as $target) {
			if (isset($condition[$target]) && trim($condition[$target]) !== '') {
				$conditionWhere[$target] = trim($condition[$target]);
			}
		}

		$sqlWhere[0] = $conditionLike;
		$sqlWhere[1] = $conditionWhere;

		return $sqlWhere;
	}
	
	/**
	 * Check exist login ID
	 */
	public function check_exist($fieldvalue, $fieldname) {
		$this->where($fieldname, $fieldvalue);
		$this->where('deleted_at',null);
		$result = $this->get()->result_count();
		// エイリアスIDが存在しました
		if($result > 0) {
			return true;
		}
		return false;
	}
	
	/**
	 * Insert Data
	 * @param array $data
	 */
	public function insert($recordset = array()) {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at= $current_date;
		
		$target = array('login_id', 'password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name',
				'gender','birthday','organization','department','phone_number','position','info','status','role','language'
		);
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) !== '') {
				$this->{$key} = $recordset[$key];
			}
		}
		
		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return false;
		}
		$this->trans_commit();
		return true;
	}
	
	/**
	 * Select data id selected
	 * @param string $id
	 */
	public function get_by_id($id) {
		$result = $this->where('id',$id)->where('deleted_at',NULL)->get();
		return $result;
	}
	
	/**
	 * Update Data
	 * @param : $data
	 */
	 public function updateAdmin($id, $recordset = array()) {
	 	$this->id = $id;
	 	$target = array('login_id', 'password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name',
				'gender','birthday','organization','department','phone_number','position','info','status','role','language'
		);
	 	
	 	$target_recordset = array();
	 	$update_date = date("Y-m-d H:i:s");
	 	$target_recordset['updated_at'] = $update_date;
	 	foreach ($target as $key) {
	 		if (array_key_exists($key, $recordset)) {
	 			$target_recordset[$key] = (trim($recordset[$key]) != '') ? trim($recordset[$key]) : NULL ;
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
	 * 一括登録
	 * @param array $data
	 * @param integer $row
	 * @return boolean
	 */
	public function insert_batch($recordset = array()) {
		$current_date = date("Y-m-d H:i:s");
		$target = array('id','email','login_id','password','last_name_ja','first_name_ja','last_name','first_name'
						,'gender','birthday','organization','department','position','phone_number','info','role','language'
						,'status'
						);

		$this->trans_begin();
		$row = count($recordset);
		for ($i = 1; $i <= $row; $i++ ) {
			foreach ($target as $key) {
				if (array_key_exists($key, $recordset[$i])) {
					if ($recordset[$i][$key] !== "") {
						$this->{$key} = $recordset[$i][$key];
					} else {
						if ($key == 'password') {
							continue;
						}
						$this->{$key} = NULL;
						$recordset[$i][$key] = NULL;
					}
				}
			}

			if ($this->id > 0) {
				if ($this->status == -1) {
					$result = $this->where('id', $this->id)->delete();
				} else {
					$recordset[$i]['updated_at'] = $current_date;
					$result = $this->where('id', $this->id)->update($recordset[$i]);
				}
			} else {
				$this->joined_at = $current_date;
				$this->created_at = $current_date;
				$this->updated_at = $current_date;
				$result = $this->save_as_new();
			}
			if (!$result){
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		return TRUE;
	}
	
	public function get_id_by_login_id($login_id) {
		$result = $this->where('login_id',$login_id)->where('deleted_at',NULL)->get();
		if ($result->result_count() > 0) {
			return $result->id;
		}
		return 0;
	}
}

