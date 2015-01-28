<?php
/**
 * アップロード情報アクセスオブジェクト
 * @name UploadDao
 * @copyright (C)2013 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class UploadDao extends MY_DataMapper {

	/** 
	 * ファイル種別 1:アップロードファイル
	*/
	const FILE_UPLOAD = 1;
	/**
	 * テーブル名定義。
	 */
	public $table = "uploads";
	var $db_params = SLAVE;
	var $transfer_count = 0;

	/**
	 * ファイルの初期化メソッド。
	 */
	public function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * データベースからデータを全て取得する。
	 * @return $result データベースからデータを全
	 */
	public function get_all() {
		$this->select("uploads.*, post_uploads.post_id");
		$this->db->join("post_uploads", "post_uploads.upload_id = uploads.id", "left");
		$this->where('deleted_at', null)->where('file_type', $this::FILE_UPLOAD);
		$result = $this->get();
		return $result;
	}

	/**
	 * ファイルの各レコードを検索するメソッド
	 * @param array $condition 検索条件情報
	 * @param array $limit 制限条件情報
	 * @return 条件に満たしたページのレコード
	 */
	public function search($condition, $limit = "") {
		$columnGet = "uploads.*";
		$columnGet .= ", post_uploads.post_id";
		$sort = "updated_at";
		$order = "DESC";
		$this->select($columnGet);
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'inner');
		$this->db->join('posts', 'post_uploads.post_id = posts.id', 'inner');
		$this->db->join("users","uploads.user_id = users.id","inner");
		$this->where("posts.deleted_at",NULL);
		$sqlWhere = $this->create_where($condition);
		$this->like($sqlWhere[0])->where($sqlWhere[1])->order_by($sort, $order);
		if ($limit != "") {
			$this->limit($limit[0], $limit[1]);
		}
		$result = $this->get();
		return $result;
	}

	/**
	 * 条件作成メソッド
	 * @param $condition 検索条件情報
	 */
	private function create_where($condition) {
		$sqlWhere = array();
		$conditionWhere = array();
		$conditionLike = array();
		
		if (!isset($condition['deleted_at'])) {
			$conditionWhere[$this->table.'.deleted_at'] = null;
		}

		// 検索条件を存在しない場合
		if (!isset($condition)) {
			$sqlWhere[0] = $conditionLike;
			$sqlWhere[1] = $conditionWhere;
			return $sqlWhere;
		}

		$equalTarget = array('id', 'user_id', 'expired_type', 'status', 'file_type');
		foreach ($equalTarget as $target) {
			if (isset($condition[$target]) && trim($condition[$target]) !== '') {
				$conditionWhere[$this->table.'.'.$target] = trim($condition[$target]);
			}
		}

		if (isset($condition['post_id']) && trim($condition['post_id']) !== '') {
			$conditionWhere['post_uploads.post_id ='] = trim($condition['post_id']);
		}
		
		if (isset($condition['created_date_start']) && trim($condition['created_date_start']) !== '') {
			$conditionWhere['date(uploads.created_at) >='] = $condition['created_date_start'];
		}

		if (isset($condition['created_date_end']) && trim($condition['created_date_end']) !== '') {
			$conditionWhere['date(uploads.created_at) <='] = $condition['created_date_end'];
		}

		if (isset($condition['expired_date_start']) && trim($condition['expired_date_start']) !== '') {
			$conditionWhere['date(uploads.expired_at) >='] = $condition['expired_date_start'];
		}

		if (isset($condition['expired_date_end']) && trim($condition['expired_date_end']) !== '') {
			$conditionWhere['date(uploads.expired_at) <='] = $condition['expired_date_end'];
		}

		if (isset($condition['expired_datetime_start']) && trim($condition['expired_datetime_start']) !== '') {
			$conditionWhere['uploads.expired_at >='] = $condition['expired_datetime_start'];
		}

		if (isset($condition['expired_datetime_end']) && trim($condition['expired_datetime_end']) !== '') {
			$conditionWhere['uploads.expired_at <'] = $condition['expired_datetime_end'];
		}

		$sqlWhere[0] = $conditionLike;
		$sqlWhere[1] = $conditionWhere;
		return $sqlWhere;
	}

	/**
	 * ファイル有効期限が切れた数を取得する
	 * @param $datetime 有効期限削除対象日
	 */
	public function count_file_expiration($datetime = null) {
		$this->select("count(uploads.id) as totalRecord");
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'left');
		$this->db->where("deleted_at", null);
		$result = $this->where($this->expired_condition($datetime))->get();
		return $result->totalRecord;
	}
	
	/**
	 * ファイル有効期限が切れた一覧を取得する
	 * @param $datetime 有効期限削除対象日
	 */
	public function find_expired_list($datetime = null, $offset, $limit  = 100) {
		$this->select("uploads.*, post_uploads.post_id");
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'left');
		$this->db->where("deleted_at", null);
		$result = $this->where($this->expired_condition($datetime))->limit($limit, $offset)->get()->all;
		return $result;
	}

	/**
	 * 削除対象のファイルを削除済みとして更新
	 */
	public function delete_expired_list($datetime = null) {
		$target_recordset = array(
			'deleted_at' => $datetime,
		);
		$this->trans_begin();
		$result = $this->where("deleted_at", NULL)->where($this->expired_condition($datetime))->update($target_recordset);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;		
	}
	
	/**
	 * 削除条件を作成する。
	 */
	private function expired_condition($datetime = null) {
		if (empty($datetime)) {
			$datetime = date('Y-m-d H:i:s');	
		}
		$condition = array(
			'expired_at <=' => $datetime,
		);
		return $condition;
	}

	/**
	 * 条件を満たした総件数を取得する。
	 * @param $condition 検索条件情報
	 */
	public function count_by_condition($condition) {
		$this->select(" count(uploads.id) as totalRecord");
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'inner');
		$this->db->join('posts', 'post_uploads.post_id = posts.id', 'inner');
		$this->db->join("users","uploads.user_id = users.id","inner");
		$this->where("posts.deleted_at", null);
		$sqlWhere = $this->create_where($condition);
		$result = $this->like($sqlWhere[0])->where($sqlWhere[1])->get();
		return $result->totalRecord;
	}

	/**
	 * 指定したIDからデータを取得する。
	 * @param $result 指定したIDからデータ
	 */
	public function get_by_id($id) {
		$columnGet = "uploads.*";
		$columnGet .= ", post_uploads.post_id";

		$this->select($columnGet);
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'left');
		$this->where('uploads.id', $id);
		$result = $this->get();
		return $result;
	}

	/**
	 * Get uploads by post id
	 * @param int $id The post ID
	 * @return array of upload
	 */
	public function get_by_post_id($id) {
		$cur_date = date("Y-m-d H:i:s");
		$this->select("uploads.*");
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'left');
		$this->where('post_uploads.post_id', $id);
		$this->where('uploads.deleted_at is null', null, false);
		$this->where('uploads.status', STATUS_ENABLE);
		$this->where('uploads.expired_at >= now()', null, false);
		// 以下はなくて良い
//		$this->where('uploads.file_type', $type);
		$result = $this->get();
		return $result;
	}

	/**
	 * Get uploads by post id
	 * @param int $id The post ID
	 * @return array of upload
	 */
	public function get_by_parent_id($id) {
		$cur_date = date("Y-m-d H:i:s");
		$this->select("uploads.*");
		$this->where('uploads.parent_id', $id);
		$this->where('uploads.deleted_at is null', null, false);
		$this->where('uploads.status', STATUS_ENABLE);
		$this->where('uploads.expired_at >= now()', null, false);
// 以下はなくて良い
//		$this->where('uploads.file_type', $type);
		$result = $this->get();
		return $result;
	}

	/**
	 * ファイルテーブルにレコードを更新するメソッド
	 * @param aray $file_info
	 * @return boolean: true - 更新成功の場合
	 * 					false - 更新に誤りがある場合
	 */
	public function update_file($id, $recordset = array()) {
		$this->id = $id;
		$target = array('file_id' , 'file_size' , 'file_extension' , 'parent_file_id' , 'file_info'
						, 'encryption_key' , 'file_type' , 'hash_value' , 'file_path' , 'small_thumbnail_path'
						, 'large_thumbnail_path' , 'user_id' , 'expired_type' , 'expired_at' , 'created_at'
						, 'updated_at' , 'deleted_at' , 'status');

		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;

		if ($recordset['expired_type'] == 0) {
			$expired_date = $recordset['year_expired_date'];
			$expired_date .= "-" . $recordset['month_expired_date'];
			$expired_date .= "-" . $recordset['day_expired_date'];
			$expired_date .= " " . $recordset['hour_expired_date'];
			$expired_date .= ":" . $recordset['min_expired_date'];
			$expired_date .= ":00";

			$target_recordset['expired_at'] = $expired_date;
		} else if ($recordset['expired_type'] == -1) {
			$target_recordset['expired_at'] = null;
		} else if ($recordset['expired_type'] == 3) {
			$target_recordset['expired_at'] = date('Y-m-d H:i:s', strtotime(ADD_THREE_DAYS));
		} else {
			$target_recordset['expired_at'] = date('Y-m-d H:i:s', strtotime(ADD_A_YEAR));
		}
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && isset($recordset[$key])) {
				$target_recordset[$key] = $recordset[$key];
			}
		}
		$this->trans_begin();
		$result = $this->where('id', $id)->update($target_recordset);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
	
	protected function get_expired_date($expired_type, $recordset = false) {
		if ($expired_type == 0 && is_array($recordset)) {
			$expired_date = $recordset['year_expired_date'];
			$expired_date .= "-" . $recordset['month_expired_date'];
			$expired_date .= "-" . $recordset['day_expired_date'];
			$expired_date .= " " . $recordset['hour_expired_date'];
			$expired_date .= ":" . $recordset['min_expired_date'];
			$expired_date .= ":00";
	
			$expired_at = $expired_date;
		} else if ($expired_type == -1) {
			$expired_at = null;
		} else if ($expired_type == 3) {
			$expired_at = date('Y-m-d H:i:s', strtotime(ADD_THREE_DAYS));
		} else {
			$expired_at = date('Y-m-d H:i:s', strtotime(ADD_A_YEAR));
		}
		return $expired_at;
	}

	/**
	 * ページのレコードを削除するメソッド
	 * @param interger ID: 削除が必要なファイルのＩＤ。
	 * @return boolean: true - 削除成功の場合
	 * 					false - 削除失敗
	 */
	public function delete_recordset($id) {
		$cur_date_time = date('Y-m-d H:i:s');
		$this->trans_begin();
		$this->remove_files($this->get_by_id($id));
		$result = $this->where('id = ', $id)->update(array('deleted_at' => $cur_date_time));
		if ($result === false) {
			$this->trans_rollback();
			return false;
		}
		$this->trans_commit();
		return true;
	}
	
	/**
	 * スレッドidで削除する
	 * @param int $post_id
	 * @return boolean
	 */
	public function delete_by_post_id($post_id) {
		$postuploaddao = new PostUploadDao();
		$postuploaddao->where('post_id', $post_id);
		$uploads = $postuploaddao->get();
		foreach ($uploads as $upload) {
			$this->remove_files($upload);
			$cur_date_time = date('Y-m-d H:i:s');
			$this->where('id', $upload->upload_id);
			$result = $this->update(array('deleted_at' => $cur_date_time));
			if (!$result) {
				return FALSE;
			}
			$this->clear();
		}
		
		return TRUE;
	}

	/**
	 * ダウンロードリンクを取得する。
	 * @param int $id
	 * @return string
	 */
	public function get_download_link($id) {
		$this->where('id', $id);
		$this->where('uploads.deleted_at', null);
		$this->where('uploads.status', STATUS_ENABLE);
		$result = $this->get();
		
		return $result;
	}
	
	/**
	 * プレビュー数を取得する。
	 * @param int $id 投稿ID
	 * @return string
	 */
	public function count_preview_list($post_id) {
		$binds = array($post_id, $post_id, STATUS_ENABLE);
		$sql = <<<SQL
select count(*) as total from uploads
where
	(id in (SELECT upload_id from post_uploads where post_id = ?)
	or parent_id in (SELECT upload_id from post_uploads where post_id = ?))
	and file_extension != 'zip'
	and small_thumbnail_path is not null
	and large_thumbnail_path is not null
	and deleted_at is null
	and status = ?
order by created_at asc
SQL;
		$result = $this->query($sql, $binds);
		log_message('debug', $this->check_last_query(false, true));
		return $result->total;
	}
	
	/**
	 * プレビュー一覧を取得する。
	 * @param int $id 投稿ID
	 * @return string
	 */
	public function get_preview_list($post_id) {
		$binds = array($post_id, $post_id, STATUS_ENABLE);
		$sql = <<<SQL
select * from uploads
where
	(id in (SELECT upload_id from post_uploads where post_id = ?)
	or parent_id in (SELECT upload_id from post_uploads where post_id = ?))
	and file_extension != 'zip'
	and small_thumbnail_path is not null
	and large_thumbnail_path is not null
	and deleted_at is null
	and status = ?
order by created_at asc
SQL;
		$result = $this->query($sql, $binds)->all;
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}

	/**
	 * Sum size of all files belong to an user
	 * @param int $user_id
	 * @return int
	 */
	public function sum_size_by_user($user_id) {
		$this->select("sum(file_size) as sum_size");
		$this->where("user_id", $user_id);
		$this->where("deleted_at", null);
		$this->where("file_type", 1);
		$result = $this->get();
		return $result->sum_size;
	}

	/**
	 * Check exists data in table
	 * @return boolean
	 */
	public function has_data() {
		$result = $this->select('id')->where('deleted_at',NULL)->where('file_type',1)->get()->result_count();
		if ($result > 0) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function can_download($user_id, $post_id = NULL, $upload_id = NULL) {
		$query = <<<SQL
		SELECT sum( 
			CASE
SQL;
		if ($post_id != NULL) {
			$query .= <<<SQL
				WHEN ( EXISTS (SELECT posts.user_id FROM posts WHERE posts.id = ? AND posts.user_id = ? )) THEN 1 
SQL;
		}
		if ($upload_id != NULL) {
			$query .= <<<SQL
				WHEN ( EXISTS (SELECT uploads.user_id FROM uploads WHERE uploads.file_id = ? AND uploads.user_id = ? )) THEN 1
SQL;
		}
			$query .= <<<SQL
				WHEN (forwards.user_type = 1 AND forwards.send_id = ?) THEN 1
				WHEN (forwards.user_type = 2 AND 
				(
					EXISTS (SELECT groups.user_id FROM groups where groups.id = forwards.send_id and groups.user_id = ?)
					OR 
					EXISTS (SELECT group_users.user_id FROM group_users where group_users.group_id = forwards.send_id and group_users.user_id = ?)
				)) THEN 1 END) as result
		FROM forwards 
SQL;
		if ($post_id != NULL) {
			$query .= <<<SQL
			WHERE forwards.post_id = ?
SQL;
			$binds = array($post_id,$user_id, $user_id, $user_id, $user_id, $post_id);
		}
		if ($upload_id != NULL) {
			$query .= <<<SQL
		WHERE forwards.post_id IN 
			(SELECT post_uploads.post_id FROM post_uploads INNER JOIN uploads ON uploads.id = post_uploads.upload_id WHERE uploads.file_id = ?);
SQL;
			$binds = array($upload_id,$user_id, $user_id, $user_id, $user_id, $upload_id);
		}
		$result = $this->query($query,$binds)->all;
		log_message('debug', $this->check_last_query(false, true));
		if ($result != NULL) {
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * ファイルテーブルにレコードを追加するメソッド
	 * @param aray $file_info
	 * @return boolean: true - 更新成功の場合
	 * 					false - 更新に誤りがある場合
	 */
	public function insert_recordset($recordset = array(), $transaction = true) {
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;
		$this->updated_at= $current_date;
	
		$target = array('file_id' , 'file_size' , 'original_file_name', 'file_extension' , 'parent_id' , 'file_info'
				, 'encryption_key' , 'file_type' , 'hash_value' , 'file_path' , 'small_thumbnail_path'
				, 'large_thumbnail_path' , 'user_id' , 'expired_type' , 'expired_at' , 'created_at'
				, 'updated_at' , 'deleted_at' , 'status');
	
		$this->file_extension = '';
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$this->{$key} = trim($recordset[$key]);
			}
		}
		if ($transaction) {
			$this->trans_begin();
		}
		$result = $this->save();
	
		if(!$result){
			if ($transaction) {
				$this->trans_rollback();
			}
			return 0;
		}
		if ($transaction) {
			$this->trans_commit();
		}
		return $this->id;
	}
	
	/**
	 * ファイルテーブルにレコードを更新するメソッド
	 * @param aray $file_info
	 * @return boolean: true - 更新成功の場合
	 * 					false - 更新に誤りがある場合
	 */
	public function update_recordset($id, $recordset = array(), $transaction = true) {
		$this->id = $id;
		$target = array('file_id' , 'file_size' , 'original_file_name', 'file_extension' , 'parent_id' , 'file_info'
				, 'encryption_key' , 'file_type' , 'hash_value' , 'file_path' , 'small_thumbnail_path'
				, 'large_thumbnail_path' , 'user_id' , 'expired_type' , 'expired_at' , 'created_at'
				, 'updated_at' , 'deleted_at' , 'status');
	
		$target_recordset = array();
		$update_date = date("Y-m-d H:i:s");
		$target_recordset['updated_at'] = $update_date;
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$target_recordset[$key] = trim($recordset[$key]);
			}
		}
	
		if ($transaction) {
			$this->trans_begin();
		}
		$result = $this->where('id', $id)->update($target_recordset);
		if (!$result) {
			if ($transaction) {
				$this->trans_rollback();
			}
			return FALSE;
		}
		if ($transaction) {
			$this->trans_commit();
		}
		return TRUE;
	}
	
	/**
	 * サムネイル情報を更新する。
	 */
	public function update_file_info($id, $file) {
		$recordset = array(
			'file_info' => json_encode(array_selector('file_info', $file)),
			'small_thumbnail_path' => array_selector('thumbnail_s_path', $file, ''),
			'large_thumbnail_path' => array_selector('thumbnail_l_path', $file, ''),
		);
		log_message('debug', var_export($recordset, true));
		$this->update_recordset($id, $recordset);
	}
	
	/**
	 * 指定したファイルIDからデータを取得する。
	 */
	public function find_by_file_id($file_id) {
		$result = $this->where('file_id', $file_id)
		->where('deleted_at IS NULL', null, false)
		->get()->all;
		if (count($result) == 0) {
			return NULL;
		} else {
			return array_shift($result);
		}
	}
	
	/**
	 * 指定したファイルIDからデータリストを取得する。
	 */
	public function find_list_in_file_id($file_ids) {
		$result = $this->where_in('file_id', $file_ids)
		->get()->all;
		return $result;
	}
	
	/**
	 * 指定したフ親IDからデータリストを取得する。
	 */
	public function find_list_by_parent_id($parent_id) {
		$result = $this->where('parent_id', $parent_id)
		->where('deleted_at IS NULL', null, false)
		->get()->all;
		return $result;
	}
	
	public function transfer_upload_file($upload_dir, $post_id, $expired_type, $dirmode = 0777) {
		log_message('debug', sprintf("[TRANSFER UPLOAD FILE] %s; %s; %s", $upload_dir, $post_id, $expired_type));
		$this->transfer_count = 0; // reset count
		$file_target_list = array('file_path', 'small_thumbnail_path', 'large_thumbnail_path');
		
		// 対象ディレクトリを検査
		if (!is_dir($upload_dir)) {
			log_message("debug", "no upload files.");
			return false;
		}
		
		// 対象ディレクトリをスキャン
		$file_list = scandir($upload_dir);
		$checker = array_indexing_by_key('file_id', $this->find_list_in_file_id($file_list));
		log_message('debug', var_export(array_keys($checker), true));
	
		$reldao = new PostUploadDao(MASTER);
		$utmpDao = new TmpUploadDao(MASTER);
		$tmpDao = new TmpUploadDao();
		
		$CI =& get_instance();
		$CI->load->library('UploadHandler', array('initialize' => false));

		// 同時アップロード数20件までなので、それでカバー
		$sorted_file_list = array_indexing_by_key('file_id', $tmpDao->find_list_in_file_id($file_list));
	
		foreach ($sorted_file_list as $file_id => $targetDto) {
			// フォルダや階層はスキップ
			if (preg_match('/^(\.+|unzipped|thumbnail)$/', $file_id)) {
				continue;
			}
			log_message('debug', 'Processing file id: '.$file_id);
			// 対象 FILE_ID に対してデータ移行開始
			// $targetDto = $tmpDao->find_by_file_id($file_id);
	
			// 対象ファイル情報がなければ、終了
			if ($targetDto === NULL) {
				log_message('debug', 'NO TARGET FILE!!');
				return false;
			}

			// 
			$destDto = $this->convertDto($post_id, $targetDto, $expired_type);
			log_message('debug', var_export($destDto, true));
			$parent_id = 0; 

			// 対象フォルダにファイルがあれば、移動
			foreach ($file_target_list as $file_target) {
				if (file_exists($targetDto->{$file_target})) {
					$dest_dir = dirname($destDto[$file_target]);
					if (!is_dir($dest_dir)) {
						mkdir($dest_dir, $dirmode, true);
						log_message('debug', 'Making dir ... '.$dest_dir);
					}
					if (!empty($destDto['encryption_key'])) {
						$CI->uploadhandler->encrypt_file($targetDto->{$file_target}, $destDto['encryption_key']);
					}
					rename($targetDto->{$file_target}, $destDto[$file_target]);
					log_message('debug', 'rename '.$targetDto->{$file_target}.' to '.$destDto[$file_target]);
				}
			}

			// すでにあれば、更新。なければ追加。
			if (array_key_exists($file_id, $checker)) {
				$parent_id = $checker[$file_id]->id;
				$this->update_recordset($checker[$file_id]->id, $destDto);
			} else {
				$parent_id = $this->insert_recordset($destDto);
			}
			log_message('debug', $this->check_last_query(false, true)."[parent_id]:".$parent_id);
			$this->id = null;
			$this->clear();
	
			// アップロードファイルと投稿のリレーションを張る(基本は、投稿IDでアップデートするのみなので、インサートのみのはず）。
			$reldao->insert_post_upload($post_id, $parent_id);
			$reldao->clear();
			log_message('debug', $reldao->check_last_query(false, true));
				
			// Transfer したのちに TMP から削除
			$utmpDao->delete_by_file_id($file_id);
			$utmpDao->clear();
			$this->transfer_count++;
			log_message('debug', 'Delete from temporary upload file: '.$file_id);
		}
		return true;
	}

	private function convertDto($post_id, $recordset, $expired_type, $parent_id = 0) {
		$dto = array();
		$target = array('file_id'
				, 'file_size'
				, 'file_extension'
				, 'file_info'
				, 'original_file_name'
				, 'encryption_key'
				, 'file_type'
				, 'hash_value'
				, 'user_id' );
	
		foreach ($target as $key) {
			if (is_object($recordset) && isset($recordset->{$key}) && trim($recordset->{$key}) != '') {
				$dto[$key] = trim($recordset->{$key});
			}
		}
		$dto['parent_id'] = $parent_id;
		$dto['expired_type'] = $expired_type;
		$dto['expired_at'] = $this->get_expired_date($expired_type);
		$dto['file_path'] = preg_replace('/\/tmp_files\//', '/files/'.$post_id.'/', $recordset->file_path);
		$dto['small_thumbnail_path'] = preg_replace('/\/tmp_files\//', '/files/'.$post_id.'/', $recordset->small_thumbnail_path);
		$dto['large_thumbnail_path'] = preg_replace('/\/tmp_files\//', '/files/'.$post_id.'/', $recordset->large_thumbnail_path);
		$dto['status'] = 1;
		return $dto;
	}

	public function get_upload_by_post_id($post_id) {
		$this->select('posts.id, sum(uploads.file_size) as total_size, count(uploads.id) as total_number');
		$this->db->join('post_uploads', 'post_uploads.upload_id = uploads.id', 'join');
		$this->db->join('posts', 'posts.id = post_uploads.post_id', 'join');
		$this->where('uploads.parent_id', 0);
		$this->where('posts.id', $post_id);
		return $this->get();
	}
	
	public function remove_files($recordset) {
		$id = array_selector('id', $recordset);
		$file_path_list = array(
			array_selector('file_path', $recordset),
			array_selector('small_thumbnail_path', $recordset),
			array_selector('large_thumbnail_path', $recordset)
		);
		foreach ($file_path_list as $file_path) {
			if (!empty($file_path) && file_exists($file_path)) {
				log_message('debug', 'unlink file at uploads.id '.$id.':'.$file_path);
				unlink($file_path);
			}
		}
	}
	

	public function remove_recordset($recordset, $force = false) {
		$this->remove_files($recordset);

		$id = array_selector('id', $recordset);
		if (empty($id)) {
			return;
		}
		$this->trans_begin();
		if ($force) {
			$result = $this->db->delete($this->table, array('id' => $id));
		} else {
			$cur_date_time = date('Y-m-d H:i:s');
			$result = $this->where('id', $id)->update(array('deleted_at' => $cur_date_time));
		}
		if ($result === false) {
			$this->trans_rollback();
			return false;
		}
		$this->trans_commit();
		log_message('debug', 'remove uploads.id '.$id.';'.$this->check_last_query(false, true));
	}

	public function update_expired_date($id, $expired_at) {
		$this->id = $id;
		$result = $this->where('id', $id)->or_where('parent_id', $id)->update(array('expired_at' => $expired_at));
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