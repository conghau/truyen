<?php
/**
 * 通知データアクセスオブジェクト
 * @name ConfigDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php'; 
class ConfigDao extends MY_DataMapper {
	
	var $table = 'configs';
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	public function get_by_user_id($id,$type) {
		$this->select("id,target_id,status");
		$this->where('user_id',$id);
		$this->where('category_id',$type);
		$result = $this->where('deleted_at',NULL)->get();
		return $result;
	}
	
	public function check_exist_config($user_id, $target_id) {
			$this->where('user_id',$user_id);
			$this->where('target_id',$target_id);
			$this->where('category_id',CONFIG_CATEGORY_NOTICE);
			return $this->get()->result_count();
	}
	
	public function update_config($configs_setting) {
		$this->trans_begin();
		foreach ($configs_setting as $config) {
			$user_id = $config['user_id'];
			$target_id = $config['id'];
			$category_id = $config['category_id'];
			if ($this->check_exist_config($user_id,$target_id) > 0) {
				$this->where('user_id',$user_id);
				$this->where('target_id',$target_id);
				$this->where('category_id',$category_id);
				$result = $this->update(array('status'=>$config['check'],'deleted_at'=>NULL));
				if(!$result){
					$this->trans_rollback();
					return FALSE;
				}
			} else {
				$current_date = date("Y-m-d H:i:s");
				$this->created_at = $current_date;
				$this->updated_at = $current_date;
				$this->user_id = $user_id;
				$this->category_id = $category_id;
				$this->status = $config['check'];
				$this->target_id =$target_id;
				$result = $this->save_as_new();
				if(!$result){
					$this->trans_rollback();
					return FALSE;
				}
			}
		}
		$this->trans_commit();
		return TRUE;
	}
	
	public function insert_config($user_id, $category_id, $list_setting) {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at = $current_date;
		$this->trans_begin();
		foreach ($list_setting as $config) {
			$this->user_id = $user_id;
			$this->category_id = $category_id;
			$this->status = STATUS_ENABLE;
			$this->target_id = $config['id'];
			$result = $this->save_as_new();
			if(!$result){
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		return TRUE;
	}
	
	public function check_config_setting_notice($user_id, $type) {
		$this->where('user_id', $user_id);
		$this->where('category_id', CONFIG_CATEGORY_NOTICE);
		$this->where('target_id', $type);
		$this->where('status', STATUS_ENABLE);
		$this->where('deleted_at', null);
		$result = $this->get()->result_count();
		if ($result > 0) {
			return TRUE;
		}
		return FALSE;
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
