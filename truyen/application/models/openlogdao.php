<?php
/**
 * 既読情報アクセスオブジェクト
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class OpenLogDao extends MY_DataMapper {
	
	var $table = 'open_logs';
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	public function count_by_post_id($post_id) {
		$this->select('count(*) as view_number');
		$this->where('post_id', $post_id);
		$result = $this->get();
		return $result->view_number;
	}

	public function get_by_post_id($post_id) {
		$this->select('open_logs.*, users.first_name_ja, users.last_name_ja, users.first_name, users.last_name');
		$this->db->join('users', 'open_logs.user_id = users.id', 'join');
		$this->where('post_id', $post_id);
		$result = $this->get();
		return $result;
	}
	
	public function check_has_record($post_id, $user_id) {
		$this->where('post_id', $post_id);
		$this->where('user_id', $user_id);
		$result = $this->get();
		if (count($result->all) > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * 未読一覧を取得する
	 * @param $post_id 投稿ID
	 * @param $forward_type 投稿先種別(1:ユーザー/2:グループ)
	 * @param $exclude_user_id 除外ユーザー
	 */
	public function get_unread_by_post_id($post_id, $forward_type, $exclude_user_id = null) {
		$binds = array($post_id);
		if ($forward_type == TYPE_USER) {
			$sql = <<<SQL
select users.*, forwards.user_type as forward_type from forwards 
left join users on send_id = users.id 
left join open_logs on open_logs.post_id = forwards.post_id and open_logs.user_id = users.id
where forwards.user_type = 1 and forwards.post_id = ? 
and users.status = 1 and users.deleted_at is null and users.leaved_at is null 
and open_logs.user_id is null
SQL;
		} else if ($forward_type == TYPE_GROUP) {
			$sql = <<<SQL
select distinct users.*, forwards.user_type as forward_type, forwards.send_id as forward_id from forwards 
left join groups on send_id = groups.id 
left join group_users on groups.id = group_users.group_id 
left join users on users.id = group_users.user_id 
left join open_logs on open_logs.post_id = forwards.post_id and open_logs.user_id = users.id
where forwards.user_type = 2 and  forwards.post_id = ?
and groups.status = 1 and groups.deleted_at is null  
and group_users.status = 1 and group_users.leaved_at is null
and users.status = 1 and users.deleted_at is null and users.leaved_at is null 
and open_logs.user_id is null
SQL;
		} else {
			log_message('error', 'SQL Error.  No unread list selected.');
			return array();
		}
		if (!is_null($exclude_user_id)) {
			$sql .= " and users.id != ?";
			array_push($binds, $exclude_user_id);
		} 
		$result = $this->query($sql, $binds)->all;
		return $result;
	}

	
	public function insert($open_log) {
		$cur_date_time = date("Y-m-d H:i:s");
		$target = array('post_id', 'user_id');
		foreach ($target as $key) {
			if (isset($open_log[$key])) {
				$this->{$key} = $open_log[$key];
			}
		}
		$this->update_at = $cur_date_time;
		$this->trans_begin();
		$result = $this->save();
		if (!$result) {
			$this->trans_rollback();
			return $result;
		}
		$this->trans_commit();
		return $result;
	}
}