<?php
/**
 * 利用者-拒否データアクセスオブジェクト
 * @name Blacklist_UserDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php'; 
class Blacklist_UserDao extends MY_DataMapper {
	
	var $table = 'blacklist_users';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	public function get_by_target_user_id($login_id,$user_id) {
		$this->where('target_user_id',$login_id);
		$this->where('user_id',$user_id);
		return $this->get();
	}
	
	public function get_user_id_by_target($target_user_id) {
		$this->select("user_id");
		$this->where('target_user_id',$target_user_id);
		return $this->get();
	}
	
	public function get_black_list_by_user_id($user_id) {
		$this->select("users.id,users.last_name_ja,users.first_name_ja,users.last_name,users.first_name,users.organization,users.department,users.position,blacklist_users.target_user_id");
		$this->db->join('users','blacklist_users.target_user_id = users.id');
		$this->where('blacklist_users.user_id =',$user_id);
		$result = $this->get();
		return $result;
	}
	
	public function add_user($recordset = array()) {
		$current_date = date("Y-m-d H:i:s");
		$this->user_id = $recordset['user_id_login'] ;
		$this->target_user_id = $recordset['target_user_id'];
		$this->update_at = $current_date;
		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return 0;
		}
		$this->trans_commit();
		return $this->id;
	}
	
	public function delete_user($recordset = array()) {
		$this->trans_begin();
		$condition = array(
				'user_id' => $recordset['user_id_login'],
				'target_user_id' => $recordset['target_user_id']);
		$result = $this->db->delete('blacklist_users',$condition);
		if(!$result){
			$this->trans_rollback();
			return false;
		}
		$this->trans_commit();
		return true;
	}
	
	public function update_blacklist_user($user_id,$users_add,$users_delete) {
		$this->trans_begin();
		$result = false;
		$current_date = date("Y-m-d H:i:s");
		foreach ($users_delete as $user) {
			$condition = array(
					'user_id' => $user_id,
					'target_user_id' => $user['id']);
			$result = $this->db->delete('blacklist_users',$condition);
			if(!$result){
				$this->trans_rollback();
				return false;
			}
		}
		
		foreach ($users_add as $user) {
			$this->where('user_id',$user_id);
			$this->where('target_user_id',$user['id']);
			if ($this->get()->result_count()==0) {
				$this->user_id = $user_id;
				$this->target_user_id = $user['id'];
				$this->update_at = $current_date;
				$result = $this->save();
				if(!$result){
					$this->trans_rollback();
					return false;
				}
			}
		}
		$this->trans_commit();
		return true;
	}
	
	function get_black_list_user($user_id){
		$this->select('target_user_id as user_id');
		$this->db->join('users','blacklist_users.target_user_id = users.id');
		$this->where('user_id',$user_id);
		$this->get();
		$query1 = $this->check_last_query(false, true);
		
		$this->select('user_id');
		$this->db->join('users','blacklist_users.user_id = users.id');
		$this->where('target_user_id',$user_id);
		$this->get();
		$query2 = $this->check_last_query(false, true);
		$query = $this->db->query($query1." UNION ALL ".$query2);
		return $query->result();
	}
	
}