<?php
/**
 * お気に入り情報りアクセスオブジェクト
 * @name FavoriteUserDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class FavoriteUserDao extends MY_DataMapper {

	var $table = 'favorite_users';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	/**
	 * 「お気に入り情報」テーブルにデータを挿入する。
	 * @param $owner_id オーナーID
	 * @param $user_id ユーザーID
	 * @return boolean
	 */
	public function insert_favorite_user($owner_id, $user_id) {
		$currentDate = date("Y-m-d H:i:s",time());
		$this->user_id			= $owner_id;
		$this->target_user_id	= $user_id;
		$this->updated_at		= $currentDate;
		$result = $this->save();
		if (!$result) {
			return FALSE;
		}
		return TRUE;
	} 
	
	/**
	 * お気に入り利用者情報をオーナーIDによって取得する。
	 * @param $owner_id オーナーID
	 */
	public function get_target_id_by_user_id($owner_id) {
		$this->select('target_user_id');
		$this->where('user_id', $owner_id);
		$result = $this->get();
		return $result;
	}
	
	/**
	 * ユーザー情報をオーナーIDによって取得する。
	 * @param $owner_id オーナーID
	 */
	public function get_user_by_user_id($group_id, $owner_id) {
		$binds = array($owner_id, $owner_id, $owner_id, $group_id);
		$sql = <<<SQL
		select users.id, last_name_ja, first_name_ja, last_name, first_name, organization, users.position, qualifications.category_id
		from favorite_users inner join users on favorite_users.target_user_id = users.id
		inner join qualifications on users.qualification_id = qualifications.id
		where user_id = ?
		and users.deleted_at is null
		and users.id not in (select target_user_id from blacklist_users where user_id = ?)
		and users.id not in (select user_id from blacklist_users where target_user_id = ?)
		and users.id not in(
		select user_id from group_users where group_id = ?
		)
SQL;
		$result = $this->query($sql, $binds)->all;
		return $result;
	}
}