<?php
/**
 * ユーザーデータアクセスオブジェクト
 * @name UserDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class UserDao extends MY_DataMapper {

	var $table = 'users';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	function login($id, $password) {
		$binds = array($id, $password, STATUS_REGIST_USER_ACTIVE, STATUS_ENABLE);
		$sql = <<<SQL
select * from $this->table where login_id = ? and password = ? and registered_status = ? and status = ?
SQL;
		$result = $this->query($sql, $binds)->all;
		if (count($result) === 1) {
			return array_shift($result);
		} else {
			return null;
		}
	}

	function verify_session($token) {
		$binds = array($token);
		$sql = <<<SQL
select * from sessions left join $this->table on sessions.user_id = {$this->table}.id and sessions.status = 1 and sessions.expired_at >= now() where sessions.token = ?
SQL;
		$result = $this->query($sql, $binds)->all;
		if (count($result) === 1) {
			return array_shift($result);
		} else {
			return null;
		}
	}
	/**
	 * Get all
	 * @return Object
	 */
	
	public function get_all() {
		$result = $this->where('deleted_at',NULL)->get();
		return $result;
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
	 * Get user by array id
	 * @param string $arr_id
	 * @return Object
	 */
	public function get_by_id_in($arr_id) {
		$this->select('email');
		$results = $this->where_in('id',$arr_id)->get();
		$arr_email = array();
		foreach($results as $result) {
			array_push($arr_email, $result->email);
		}
		return $arr_email;
	}
	
	public function get_user_approve($id) {
		$this->where('id',$id);
		$this->where('registered_type',3);
		$this->where_in('registered_status',array(0,2,3));
		$result = $this->get();
		return $result;
	}
	
	/**
	 * Get user by id
	 * @param string $id
	 * @return Object
	 */
	public function get_by_id($id) {
		
		$result = $this->where('id',$id)->get();
		return $result;
	}
	
	public function get_user($id) {
		$this->select('users.*,qualifications.category_id');
		$this->db->join('qualifications', 'qualifications.id = users.qualification_id');
		$this->where('deleted_at', null);
		$this->where('id', $id);
		return $this->get();
	}
	
	/**
	 * 条件を満たした総件数を取得する。
	 * @param $condition 
	 * @return int
	 */
	public function count_by_condition($condition) {
		$sqlWhere = $this->createWhere($condition);
		$result = $this->select("count(id) as totalRecords")->like($sqlWhere[0])->where($sqlWhere[1])->get();
		return $result->totalRecords;
	}

	/**
	 * Search with condition
	 * @param array $condition
	 * @param array $limit
	 * @param string $sort
	 * @param string $order
	 * @return object
	 */
	public function search($condition, $limit = NULL, $sort ='updated_at', $order='DESC') {
		$sqlWhere = $this->createWhere($condition);
		if (NULL === $limit) {
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])->get();
		} else {
			$result = $this->like($sqlWhere[0])->where($sqlWhere[1])
			->order_by($sort,$order)->limit($limit[0],$limit[1])->get();
		}
		return $result;
	}
	
	/**
	 * Create SQL 
	 * @param array $condition
	 * @return array
	 */
	private function createWhere($condition,$table_name='') {
		$sqlWhere = array();
		$conditionWhere = array();
		$conditionLike = array();
		$conditionWhere=array();
		$conditionWhere['deleted_at'] = NULL;

		if(!isset($condition))
		{
			$sqlWhere[0]=$conditionLike;
			$sqlWhere[1]=$conditionWhere;
			return $sqlWhere;
		}
		$likeTarget = array('login_id', 'email', 'last_name_ja', 'first_name_ja', 'organization', 'phone_number'
							, 'company_code' , 'registered_type' );
		foreach ($likeTarget as $target) {
			if (isset($condition[$target]) && trim($condition[$target]) !== '') {
				$conditionLike[$target] = trim($condition[$target]);
			}
		}
		$equalTarget = array('id', 'gender', 'qualification_id','registered_type', 'status');
		foreach ($equalTarget as $target) {
			if (isset($condition[$target]) && trim($condition[$target]) !== '') {
				$conditionWhere[$target] = trim($condition[$target]);
			}
		}
		
		if (isset($condition['case']) && $condition['case'] == "approve") {
			$conditionWhere['registered_status']= trim($condition['registered_status']);
		}
		// 月次絞り込みのロジックを追加
		if (isset($condition['year_month']) && preg_match('/^(\d\d\d\d[\/\-]\d\d)$/', $condition['year_month'])) {
			list($year, $month) = preg_split('/[\/\-]/', $condition['year_month']);
			$condition['start_date'] = sprintf("%04d-%02d-%02d", $year, $month, 1);
			$condition['end_date'] = end_of_month($condition['start_date']);
		}
		if (isset($condition['start_date']) && trim($condition['start_date']) !== '') {
			if ($table_name == '') {
				$conditionWhere['date(created_at) >= '] = $condition['start_date'];
			} else {
				$conditionWhere['date('.$table_name.'.created_at) >= '] = $condition['start_date'];
			}
			
		}
		if (isset($condition['end_date']) && trim($condition['end_date']) !== '') {
			if ($table_name == '') {
					$conditionWhere['date(created_at) <= '] = $condition['end_date'];
			} else {
					$conditionWhere['date('.$table_name.'.created_at) <= '] = $condition['end_date'];
			}
		}
		$sqlWhere[0] = $conditionLike;
		$sqlWhere[1] = $conditionWhere;
		return $sqlWhere;
	}

	/**
	 * insert
	 * @param  $data
	 * @return int
	 */
	public function insert($recordset = array()) {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at= $current_date;
		
		$target = array('login_id', 'password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name','gender','birthday',
						'qualification_id','qualification','organization','department','position','phone_number','domain','history',
						'university','scholar','author','society','hobby','message','company_code','registered_admin_id',
						'approvaled_admin_id','registered_type','registered_status','approved_at','status','joined_at',
						'auth_method','confirm_image_url','confirm_organization','confirm_phone_number','recommend_user_id','language'
						);
		
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$this->{$key} = trim($recordset[$key]);
			}
		}
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
	 * update
	 * @param  $data
	 * @return string
	 */
	public function update_user($id, $recordset = array()) {
		
		$this->id = $id;
		$target = array('login_id','password', 'email', 'first_name_ja', 'last_name_ja','first_name','last_name','gender','birthday',
						'qualification_id','qualification','organization','department','position','phone_number','domain','history',
						'university','scholar','author','society','hobby','message','company_code','auth_method','status',
						'specialist','confirm_image_url','confirm_organization','confirm_phone_number','language',
						'domain_flag','history_flag','university_flag','scholar_flag','author_flag','society_flag','hobby_flag','message_flag','specialist_flag',
						'file_size', 'max_file_size'
						);
		
		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;
		
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$target_recordset[$key] = (trim($recordset[$key]) !== "") ? trim($recordset[$key]) : NULL;
			}
		}
		log_message('debug', var_export($target_recordset, true));
		$this->trans_begin();
		
		$login_id = $this->get_by_id($id)->login_id;
		if (isset($target_recordset['login_id']) && $target_recordset['login_id'] != $login_id) {
			$record['login_id'] = $target_recordset['login_id'];
			$tmp_users = new Tmp_UserDao(MASTER);
			$tmp_users->where('login_id', $login_id);
			$tmp_users->update($record);
		}
		
		$result =  $this->where('id', $id)->update($target_recordset);
		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	public function update_by_login_id ($login_id, $recordset) {
		$target = array('email','language');
		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;
		
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$target_recordset[$key] = (trim($recordset[$key]) !== "") ? trim($recordset[$key]) : NULL;
			}
		}
		$result = $this->where('login_id', $login_id)->update($target_recordset);
		$id = 0;
		if($result) {
			$id = $this->select('id')->where('login_id',$login_id)->get()->id;
		}
		return $id;
	}
	
	/**
	 * 一括登録
	 * @param array $data
	 * @param integer $row
	 * @return boolean
	 */
	public function insert_batch($recordset = array(), $list_config_setting) {
		$current_date = date("Y-m-d H:i:s");
		$target = array('id','email','login_id','password','last_name_ja','first_name_ja','last_name','first_name'
				, 'gender','birthday','qualification_id','qualification','organization','department','position'
				, 'phone_number','domain','domain_flag','specialist','specialist_flag','history','history_flag'
				, 'university','university_flag', 'scholar','scholar_flag','author','author_flag','society','society_flag'
				,'hobby','hobby_flag','message','message_flag', 'auth_method','confirm_image_url','confirm_organization'
				, 'confirm_phone_number', 'dcf_code1','qlid1','qlid2','domain1','domain2','domain3','domain4','domain5','company_code'
				, 'remark1','remark2','remark3','remark4','remark5','remark6','remark7','remark8','remark9','remark10'
				, 'recommend_user_id','registered_type','registered_status','registered_admin_id','approvaled_admin_id','language'
				,'approved_at','status','max_file_size'
		);
		$aldao = new ActivityLogDao(MASTER);
		$configdao = new ConfigDao(MASTER);

		$this->trans_begin();
		$row = count($recordset);
		for ($i = 1; $i <= $row; $i++ ){
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

			if ($this->id !== NULL) {
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
				$result = $this->save();
				
				$recordset[$i]['id'] = $this->id;
				$recordset[$i]['registered_type'] = TYPE_REGIST_USER_ADMIN;
				
				$aldao->on_user_join($recordset[$i]);
				$configdao->insert_config($this->id,CONFIG_CATEGORY_NOTICE, $list_config_setting);
				$this->clear();
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
		return $result->id;
	}
	
	/**
	 * 更新
	 * @param array $info
	 * @param integer $status
	 * @return boolean
	 */
	public function update_approve($info,$status) {
		$this->id = $info['id'];
		$this->registered_status = $status;
		if ($status == 1) {
			$this->joined_at = date("Y-m-d H:i:s",time());
			$this->approvaled_admin_id = $info['admin_id'];
			$this->approved_at = date("Y-m-d H:i:s",time());
		}
		
		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	/**
	 * 更新
	 * @param array $info
	 * @param integer $status
	 * @return boolean
	 */
	public function update_file_size($id, $file_size) {
		$recordset = array('file_size' => $file_size);
		$this->update_user($id, $recordset);
		return TRUE;
	}
	
	public function get_user_by_id_in($arr_id) {
		$this->select('last_name_ja, first_name_ja, last_name, first_name, organization, department, users.id, users.position, category_id');
		$this->db->join('qualifications','users.qualification_id = qualifications.id','inner');
		$this->where_in('users.id', $arr_id);
		return $this->get();
	}
	
	public function get_user_can_be_send($user_id) {
		$binds = array($user_id, $user_id, $user_id, STATUS_ENABLE, STATUS_REGIST_USER_ACTIVE);
		$sql = <<<SQL
		select users.*,qualifications.category_id
		from users
		left join qualifications on users.qualification_id = qualifications.id
		where
		users.id not in (select target_user_id from blacklist_users where user_id = ?)
		and users.id not in (select user_id from blacklist_users where target_user_id = ?)
		and users.id != ?
		and users.status = ?
		and users.registered_status = ?
		and qualifications.id is not null
SQL;
		$result = $this->query($sql, $binds)->all;
		return $result;
	}
	
	public function get_user_can_add_group($user_id, $group_id = '') {
		$binds = array($user_id, $user_id, $user_id, STATUS_ENABLE, STATUS_REGIST_USER_ACTIVE, $group_id);
		$sql = <<<SQL
		select users.*,  qualifications.category_id
		from users
		inner join qualifications on users.qualification_id = qualifications.id
		where users.deleted_at is null
		and users.id not in (select target_user_id from blacklist_users where user_id = ?)
		and users.id not in (select user_id from blacklist_users where target_user_id = ?)
		and users.id != ?
		and users.status = ?
		and users.registered_status = ?
SQL;
		if ($group_id != '') {
			$sql .= <<<SQL
			and users.id not in (
				select user_id 
				from group_users 
				where leaved_at is NULL 
					and group_id = ? 
				)
SQL;
		}
		$result = $this->query($sql, $binds)->all;
		return $result;
	}
	
	/**
	 * Get list log entry
	 * @param array $condition
	 * @param array $limit
	 * @return object
	 */
	public function get_user_log_entry($condition,$limit = null) {
	
		$sqlWhere = $this->createWhere($condition,'activity_logs');
		$this->select('activity_logs.summary_id as summary_id ,activity_logs.created_at as created_at, 
				users.login_id,users.id,users.email,users.last_name_ja,users.first_name_ja,users.organization,
				users.qualification,users.company_code,users.status');
		$this->db->from('activity_logs');
		$this->db->join('users','activity_logs.target_id = users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_JOIN,ActivityLogDao::USER_LEAVE));
		$this->db->like($sqlWhere[0])->where($sqlWhere[1]);
		$this->db->get();
		$query1 = $this->check_last_query(false, true);
		$this->select('activity_logs.summary_id as summary_id ,activity_logs.created_at as created_at, 
				tmp_users.login_id,tmp_users.id,tmp_users.email,tmp_users.last_name_ja,tmp_users.first_name_ja,
				tmp_users.organization,tmp_users.qualification,tmp_users.company_code,tmp_users.status');
		$this->db->from('activity_logs');
		$this->db->join('tmp_users','activity_logs.target_id = tmp_users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_TEMPORARY_JOIN));
		$this->db->like($sqlWhere[0])->where($sqlWhere[1]);
		$this->db->get();
		$query2 = $this->check_last_query(false, true);
		$query_limit='';
		if ($limit != null) {
			$query_limit =  " LIMIT ".$limit[1].",". $limit[0];
		}
		$query = $this->db->query($query1." UNION ALL ".$query2.' ORDER BY created_at DESC'.$query_limit);
		return $query->result();
	}
	
	/**
	 * Get list log entry aggregation
	 * @param array $condition
	 * @param array $limit => 一覧取得の範囲を限定、all => 全体取得、指定なし => 日別で全取得
	 * @return object
	 */
	function get_user_log_entry_detail($condition, $limit = ''){
		$sqlWhere = $this->createWhere($condition,'activity_logs');
		$this->select('activity_logs.summary_id as summary_id ,date(activity_logs.created_at) as created_at, users.login_id');
		$this->db->from('activity_logs');
		$this->db->join('users','activity_logs.target_id = users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_JOIN,ActivityLogDao::USER_LEAVE));
		$this->db->like($sqlWhere[0])->where($sqlWhere[1]);
		$this->db->get();
		$query1 = $this->db->last_query();
		
		$this->select('activity_logs.summary_id as summary_id ,date(activity_logs.created_at) as created_at, tmp_users.login_id');
		$this->db->from('activity_logs');
		$this->db->join('tmp_users','activity_logs.target_id = tmp_users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_TEMPORARY_JOIN));
		$this->db->like($sqlWhere[0])->where($sqlWhere[1]);
		$this->db->get();
		$query2 = $this->db->last_query();
	
		$query_select = "SELECT count(case when summary_id = ".ActivityLogDao::USER_JOIN." then summary_id end) as num_joined,
			count(case when summary_id = ".ActivityLogDao::USER_LEAVE." then summary_id end) as num_leaved,
			count(case when summary_id = ".ActivityLogDao::USER_TEMPORARY_JOIN." then summary_id end) as num_temp,
			created_at ";
		$query_limit = "";
		// limit が指定されている場合
		if (is_array($limit))  {
			$query_limit = " LIMIT ".$limit[1].",". $limit[0];
			$res = $this->db->query($query_select." FROM (".$query1." UNION ALL ".$query2.")as TMP GROUP BY TMP.created_at ORDER BY TMP.created_at DESC ".$query_limit);
		// 日別の全体取得
		} else if ($limit == "") {
			$res = $this->db->query($query_select." FROM (".$query1." UNION ALL ".$query2.")as TMP GROUP BY TMP.created_at ORDER BY TMP.created_at DESC ");
		// 全体取得
		} else {
			$res = $this->db->query($query_select." FROM (".$query1." UNION ALL ".$query2.")as TMP");
		}

		return $res->result();
	}

	/**
	 * Get list log entry aggregation
	 * @param array $condition
	 * @return object
	 */
	function get_user_log_year_month_list(){

		$this->select('activity_logs.summary_id as summary_id, left(activity_logs.created_at, 7) as created_at, users.login_id', false);
		$this->db->from('activity_logs');
		$this->db->join('users','activity_logs.target_id = users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_JOIN,ActivityLogDao::USER_LEAVE));
		$this->db->group_by("created_at");
		$this->db->get();
		$query1 = $this->db->last_query();
		
		$this->select('activity_logs.summary_id as summary_id, left(activity_logs.created_at, 7) as created_at, tmp_users.login_id', false);
		$this->db->from('activity_logs');
		$this->db->join('tmp_users','activity_logs.target_id = tmp_users.id','right');
		$this->db->where('category_id',ActivityLogDao::CATEGORY_USER);
		$this->db->where_in('activity_logs.summary_id',array(ActivityLogDao::USER_TEMPORARY_JOIN));
		$this->db->group_by("created_at");
		$this->db->get();
		$query2 = $this->db->last_query();
	
		$query_select = "SELECT min(distinct created_at) as min ";
		$res = $this->db->query($query_select." FROM (".$query1." UNION ALL ".$query2.")as TMP ORDER BY TMP.created_at DESC ");
		$result = array();
		foreach ($res->result() as $recordset) {
			$min = $recordset->min;
			$max_ym = date2yearmonth(time2date(time()));
			for ($xi = $min; $xi <= $max_ym; $xi = next_month($xi)) {
				$result[] = str_replace('-', '/', $xi);
			}
		}
		return array_reverse($result);
	}
	
	/**
	 * Get user by email
	 * @param string $email
	 * @return Object
	 */
	public function get_by_email($email) {
		$this->select('id, login_id, birthday, language');
		$this->where('email',$email);
		return $this->get();
	}
	
	/**
	 * 退会の処理
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete_user($user_id) {
		$this->trans_begin();
		
		//delete posts
		$postdao = new PostDao(MASTER);
		$result = $postdao->delete_post_by_user_id($user_id);
		if (!$result){
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete configs
		$configdao = new ConfigDao(MASTER);
		$result = $configdao->delete_by_user_id($user_id);
		if (!$result){
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete groups
		$groupdao = new GroupDao(MASTER);
		$result = $groupdao->delete_by_user_id($user_id);
		if (!$result){
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete inqueries
		$inquirydao = new InquiryDao(MASTER);
		$result = $inquirydao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete notices
		$noticedao = new NoticeDao(MASTER);
		$result = $noticedao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete tasks
		$taskdao = new TaskDao(MASTER);
		$result = $taskdao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete tmp_uploads
		$tmp_uploaddao = new TmpUploadDao(MASTER);
		$result = $tmp_uploaddao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete uploads
		$uploaddao = new UploadDao(MASTER);
		$result = $uploaddao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		// delete tmp_users
		$tmp_userdao = new Tmp_UserDao(MASTER);
		$result = $tmp_userdao->delete_by_user_id($user_id);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		//delete users
		$result = $this->where('id',$user_id)->get()->delete();
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		
		$this->trans_commit();
		return TRUE;
	}
	
}

