<?php

/**
 * アクティビティデータアクセスオブジェクト
 * @name ForwardDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class ForwardDao extends MY_DataMapper{
	
	public $table ="forwards";
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	/**
	 * スレッドIDで宛先情報を取得する
	 * @param int $post_id
	 */
	public function get_by_post_id($post_id) {
		$this->where("post_id", $post_id);
		return $this->get();
	}
	
	/**
	 * 送信した履歴を取得する。
	 * @param $user_id　ユーザーID
	 */
	public function get_history_forward($user_id) {
		$binds = array($user_id, TYPE_USER, $user_id, $user_id, $user_id, TYPE_GROUP, STATUS_ENABLE, $user_id, $user_id, STATUS_ENABLE, 100);
		$sql = <<<SQL
			select distinct user_type, send_id, first_name_ja, last_name_ja, first_name, last_name, organization, department, group_name, category_id from (
				select forwards.user_type, send_id, send.first_name_ja, send.last_name_ja, send.first_name, send.last_name, send.organization,
					send.department, null as group_name, posts.created_at, qualifications.category_id as category_id
				from forwards
				join posts on forwards.post_id = posts.id
				join users on posts.user_id = users.id
				join users as send on forwards.send_id = send.id
				join qualifications on send.qualification_id = qualifications.id
				where users.id = ? and forwards.user_type = ?
				and send.id not in(select target_user_id from blacklist_users where user_id = ?)
				and users.id not in (select user_id from blacklist_users where target_user_id = ?)
				union
				select forwards.user_type, send_id,null as first_name_ja,null as last_name_ja,null as first_name,null as last_name,null as organization,
					null as department, send.name as group_name, posts.created_at, null as category_id
				from forwards
				join posts on forwards.post_id = posts.id
				join users on posts.user_id = users.id and users.id = ?
				join groups as send on forwards.send_id = send.id and send.deleted_at is null 
					and forwards.user_type = ? and send.status = ?
				left join group_users on group_users.group_id = send.id
				where
				(send.user_id = ? or (group_users.user_id = ? and group_users.leaved_at is null and group_users.status = ?))
				) as tmp
			order by created_at desc, last_name_ja, group_name
			limit ?;
SQL;
		$result = $this->query($sql, $binds)->all;
		return $result;
	}
	
	/**
	 * 宛先情報一覧を投稿IDによって取得する。
	 * @param $post_id 投稿ID
	 */
	public function get_forward($post_id) {
		$binds = array($post_id, TYPE_USER, $post_id, TYPE_GROUP);
		$sql = <<<SQL
			select user_type, send_id, first_name_ja, last_name_ja, first_name, last_name, organization, department, group_name from (
				select forwards.user_type, send_id, send.first_name_ja, send.last_name_ja, send.first_name, send.last_name, send.organization, send.department, null as group_name, posts.created_at
				from forwards
				join posts on forwards.post_id = posts.id
				join users on posts.user_id = users.id
				join users as send on forwards.send_id = send.id
				where posts.id = ? and forwards.user_type = ?
				union
				select forwards.user_type, send_id,null as first_name_ja,null as last_name_ja,null as first_name,null as last_name,null as organization,null as department, send.name as group_name, posts.created_at
				from forwards
				join posts on forwards.post_id = posts.id
				join users on posts.user_id = users.id
				join groups as send on forwards.send_id = send.id
				where posts.id = ? and forwards.user_type = ?) as tmp;
SQL;
		$result = $this->query($sql, $binds)->all;
		return $result;
	}
	
	/**
	 * 新規登録
	 * @param $forward 宛先一覧
	 */
	public function insert($post_id, $forward = array()) {
		$target = array('user_type', 'send_id');
		foreach ($target as $key) {
			if(isset($forward[$key])) {
				$this->{$key} = $forward[$key];
			}
		}
		$this->post_id = $post_id;
		$cur_date_time = date("Y-m-d H:i:s");
		$this->update_at = $cur_date_time;
		return $this->save();
	}
	
	public function get_group_forward_by_post_id($post_id) {
		$this->select('send_id');;
		$this->where('post_id', $post_id);
		$this->where('user_type', TYPE_GROUP);
		$result = $this->get();
		return $result->send_id;
	}
}
