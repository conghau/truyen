<?php
/**
* お問い合わせ情報アクセスオブジェクト
* @copyright (C)2014 Sevenmedia Inc.
* @author FJN
* @version 1.0
*/
require_once APPPATH.'models/MY_DataMapper.php';
class InquiryDao extends MY_DataMapper {
	
	var $table = 'inqueries';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	/**
	 * お問い合わせ情報を挿入する。
	 * @param  $recordset お問い合わせ情報
	 * @return boolean
	 */
	public function insert($recordset = array()) {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = date("Y-m-d H:i:s",time());
		$this->updated_at = date("Y-m-d H:i:s",time());
		
		$target = array('email','user_id','subject','category_id','body');
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$this->{$key} = trim($recordset[$key]);
			}
		}
		$this->status = 1;
		
		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return 0;
		}
		$this->trans_commit();
		return $this->id;
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