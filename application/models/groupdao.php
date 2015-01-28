<?php
/**
 * グループ情報アクセスオブジェクト
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class GroupDao extends MY_DataMapper {
	
	/**
	 * テーブル名定義。
	 */
	public $table = "groups";
	var $db_params = SLAVE;
	const TYPE_STATUS_PUBLIC = 1;//公募
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * グループを削除する。
	 * @param $groupId グループID
	 * @return boolean
	 */
	public function delete_group($groupId) {
		$this->trans_begin();
		$date = date("Y-m-d H:i:s",time());
		$result = $this->where('id', $groupId)->update(array('deleted_at' => $date));
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	/**
	 * グループを挿入する。
	 * @param $recordset グループ情報
	 * @param $user_add グループ利用者情報
	 * @param $notice お知らせ
	 * @return boolean
	 */
	public function insert_group($recordset = array(), $user_add, $notice) {
		$insert_date = date("Y-m-d H:i:s");
		$this->created_at = $insert_date;
		$this->updated_at = $insert_date;
		$target = array('name', 'summary', 'user_id', 'public_status','status');

		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && isset($recordset[$key])) {
				$this->{$key} = $recordset[$key];
			}
		}
		$this->trans_begin();
		$result = $this->save();
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		if (count($user_add) > 0) {
			$group_user = new GroupUserDao(MASTER);
			$resultUser = $group_user->insert_user_group($this->id, $recordset['user_id'], $user_add, $notice);
			if (!$resultUser) {
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		
		return $this->id;
	}

	/**
	 * グループを更新する。
	 * @param $id グループID
	 * @param $recordset グループ情報
	 * @param $user_add グループ利用者情報
	 * @param $notice お知らせ
	 * @return boolean
	 */
	public function update_group($id, $recordset, $user_add, $notice = array()) {
		$this->id = $id;

		$target = array('name', 'summary', 'public_status', 'status');
		
		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$target_recordset[$key] = $recordset[$key];
			}
		}

		$this->trans_begin();
		$result = $this->where('id', $id)->update($target_recordset);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		if (count($user_add) > 0) {
			$group_user = new GroupUserDao(MASTER);
			$resultUser = $group_user->insert_user_group($id, $recordset['user_id'], $user_add, $notice);
			if (!$resultUser) {
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		return TRUE;
	}
	
	/**
	 * 検索条件でグループ数を取得する。
	 * @param array $condition 検索条件一覧
	 */
	public function count_by_condition($condition) {
		if (isset($condition['user_id']) && $condition['user_id'] != '') {
			$user_id = $condition['user_id'];
			$query = <<<SQL
			select count(*) as totalRecords from (
				SELECT DISTINCT groups.id
				FROM (groups)
				JOIN group_users ON groups.id = group_users.group_id
				JOIN users ON group_users.user_id = users.id
				WHERE groups.deleted_at IS NULL
				AND group_users.leaved_at IS NULL
				AND group_users.status = ?
				AND (
				group_users.user_id = ?
				OR groups.user_id = ?
				  )
				ORDER BY groups.updated_at DESC) as groups
SQL;
			$bind = array(STATUS_GROUP_USER_ENABLE, $user_id, $user_id);
			$result = $this->query($query, $bind);
			return $result->totalRecords;
		} else {
			$this->select('count(groups.id) as totalRecords');
			$this->db->join('users', 'groups.user_id = users.id', 'join');
			$this->where('users.deleted_at', null);
			$sqlWhere = $this->create_where($condition);
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])->get();
			return $result->totalRecords;
		}
	}

	/**
	 * グループを検索する。
	 * @param $condition 検索条件一覧
	 * @param $limit
	 */
	public function search($condition, $limit) {
		// 条件作成を実行
		$sort = "updated_at";
		$order = "DESC";
		
		if (isset($condition['user_id']) && $condition['user_id'] != '') {
			$this->distinct();
			$this->select('groups.id, name, summary, groups.user_id, groups.status, groups.public_status,
				groups.created_at, groups.updated_at');
			
			$this->db->join('group_users', 'groups.id = group_users.group_id', 'join');
			$this->db->join('users', 'group_users.user_id = users.id', 'join');
			$this->where('groups.deleted_at', null);

			$this->where('group_users.leaved_at', null);
			$this->where('group_users.status', STATUS_GROUP_USER_ENABLE);
			$user_id = $condition['user_id'];
			$this->group_start();
			$this->where('group_users.user_id', $user_id);
			$this->or_where('groups.user_id', $user_id);
			$this->group_end();
			$result = $this->order_by($sort, $order)->limit($limit[0], $limit[1])->get();
		} else {
			$this->select('groups.id, name, summary, groups.user_id, groups.status, groups.public_status,
				groups.created_at, groups.updated_at, last_name_ja, first_name_ja, last_name, first_name');
			$this->db->join('users', 'groups.user_id = users.id', 'join');
			$this->where('users.deleted_at', null);
			$sqlWhere = $this->create_where($condition);
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])
			->order_by($sort, $order)->limit($limit[0], $limit[1])->get();
		}
		return $result;
	}
	
	/**
	 * 検索条件句を作成します。
	 * @param $condition 検索条件一覧
	 */
	private function create_where($condition) {
		$sqlWhere = array();
		$conditionWhere = array();
		$conditionLike = array();
		$conditionWhere['deleted_at'] = null;

		$likeTarget = array('name', 'last_name_ja', 'first_name_ja');
		foreach ($likeTarget as $target) {
			if (isset($condition[$target]) && $condition[$target] != '') {
				$conditionLike[$target] = trim($condition[$target]);
			}
		}

		$equalTarget = array('id', 'public_status');
		foreach ($equalTarget as $target) {
			if (isset($condition[$target]) && $condition[$target] != '') {
				$conditionWhere[$target] = trim($condition[$target]);
			}
		}

		if (isset($condition['date_from']) && $condition['date_from'] != '') {
			$conditionWhere['date(groups.created_at)>='] = $condition['date_from'];
		}
		if (isset($condition['date_to']) && $condition['date_to'] != '') {
			$conditionWhere['date(groups.created_at)<='] = $condition['date_to'];
		}

		// 検索条件を存在しない場合
		$sqlWhere[0] = $conditionLike;
		$sqlWhere[1] = $conditionWhere;
		return $sqlWhere;
	}

	/**
	 * グループのIDでグループを取得する。
	 * @param $group_id グループID
	 */
	public function get_group_by_id($group_id) {
		// 条件作成を実行
		$this->select('groups.*, users.email, users.language');
		$this->db->join('users', 'groups.user_id = users.id', 'join');
		$this->where('groups.id', $group_id);
		$this->where('groups.deleted_at', null);
		$result = $this->get();
		return $result;
	}

	/**
	 * 全件を取得する。
	 */
	public function get_all_group() {
		$result = $this->where('deleted_at',NULL)->get();
		return $result;
	}
	
	/**
	 * ユーザーのIDでグループ一覧を取得する
	 * @param $user_id　ユーザーＩＤ
	 */
	public function get_group_by_user_id($user_id) {
		$this->select('groups.id, groups.name');
		$this->db->join('group_users', 'groups.id = group_users.group_id', 'left');
		$this->db->join('users', 'groups.user_id = users.id', 'join');
		$this->group_start();
		$this->group_start();
		$this->where('group_users.user_id', $user_id);
		$this->where('group_users.status', STATUS_GROUP_USER_ENABLE);
		$this->where('group_users.leaved_at', null);
		$this->group_end();
		$this->or_where('groups.user_id', $user_id);
		$this->group_end();
		$this->where('groups.deleted_at', null);
		$this->where('groups.status', STATUS_ENABLE);
		$this->group_by('groups.id');
		return $this->get();
	}
	
	/**
	 * ユーザーが参加していないグループを取得する。
	 * @param int $user_id
	 */
	public function get_group_can_join($user_id) {
		$query = <<<SQL
		SELECT groups.id, groups.name,
		count(
			case when group_users.leaved_at is null 
				and group_users.user_id is not null
				and group_users.status = ? and exists (select id from users where users.id = group_users.user_id)
			then 1 end
		) as total_member
		FROM (groups)
		LEFT JOIN group_users ON groups.id = group_users.group_id
		JOIN users ON groups.user_id = users.id
		WHERE groups.id not in (select group_id from group_users where group_users.user_id= ? and leaved_at is null
				and (group_users.status = ? or group_users.status = ?))
		and groups.user_id != ?
		and groups.deleted_at is null
		and groups.status = ?
		and groups.public_status = ?
		and groups.user_id not in (select target_user_id from blacklist_users where user_id = ?)
		and groups.user_id not in (select user_id from blacklist_users where target_user_id = ?)
		GROUP BY groups.id;
SQL;
		$bind = array(STATUS_GROUP_USER_ENABLE, $user_id, STATUS_GROUP_USER_ENABLE, STATUS_GROUP_USER_DISABLE, $user_id, STATUS_ENABLE, STATUS_GROUP_PUBLIC, $user_id, $user_id);
		$result = $this->query($query, $bind);
		return $result;
	}
	
	/**
	 * グループテーブルにデータの存在をチェックする。
	 * @return boolean
	 */
	public function is_has_data() {
		$result = $this->select('id')->where('deleted_at',NULL)->get()->result_count();
		if ($result > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * グループオーナーをチェックする
	 * @param int $user_id
	 * @param int $group_id
	 * @return boolean
	 */
	public function is_group_owner($user_id, $group_id) {
		$this->where('id', $group_id);
		$this->where('user_id', $user_id);
		$result = $this->get()->result_count();
		if ($result > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * グループIDでグループオーナーを取得する
	 * @param int $group_id
	 * @return object
	 */
	public function get_group_owner_by_group_id($group_id) {
		$this->select('users.id, users.last_name_ja, users.first_name_ja, users.last_name, users.first_name');
		$this->db->join('users', 'groups.user_id = users.id', 'join');
		$this->where('groups.id', $group_id);
		$result = $this->get();
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
?>