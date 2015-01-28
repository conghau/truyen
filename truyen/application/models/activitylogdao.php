<?php
/**
 * アクティビティログアクセスオブジェクト
 * @name ActivityLogsDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author nodat
 * @version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php'; 
class ActivityLogDao extends MY_DataMapper {

	/** カテゴリー：ユーザー */
	const CATEGORY_USER = 1;
	/** ターゲット：入会 */
	const USER_JOIN = 11;
	/** ターゲット：退会 */
	const USER_LEAVE = 12;
	/** ターゲット：仮入会 */
	const USER_TEMPORARY_JOIN = 13;
	/** ターゲット：グループ入会 */
	const USER_GROUP_JOIN = 21;
	/** ターゲット：グループ退会 */
	const USER_GROUP_LEAVE = 22;
	/** ターゲット：スレッド投稿 */
	const USER_THREAD_POST = 31;
	/** ターゲット：スレッド削除 */
	const USER_THREAD_DELETE = 32;
	/** ターゲット：スレッド削除 */
	const USER_THREAD_EDIT = 33;
	/** ターゲット：スレッド投稿 */
	const USER_COMMENT_POST = 34;
	/** ターゲット：スレッド削除 */
	const USER_COMMENT_DELETE = 35;
	/** ターゲット：スレッド削除 */
	const USER_COMMENT_EDIT = 36;
	/** ターゲット：ダウンロード */
	const USER_FILE_DOWNLOAD = 41;
	/** ターゲット：アップロード */
	const USER_FILE_UPLOAD = 42;
	
	/** カテゴリー：グループ */
	const CATEGORY_GROUP = 2;
	/** ターゲット：グループ入会 */
	const GROUP_JOIN = 21;
	/** ターゲット：グループ退会 */
	const GROUP_LEAVE = 22;
	/** ターゲット：スレッド投稿 */
	const GROUP_THREAD_POST = 31;
	/** ターゲット：スレッド削除 */
	const GROUP_THREAD_DELETE = 32;
	/** ターゲット：スレッド削除 */
	const GROUP_THREAD_EDIT = 33;
	/** ターゲット：スレッド投稿 */
	const GROUP_COMMENT_POST = 34;
	/** ターゲット：スレッド削除 */
	const GROUP_COMMENT_DELETE = 35;
	/** ターゲット：スレッド削除 */
	const GROUP_COMMENT_EDIT = 36;

	/** カテゴリー：投稿*/
	const CATEGORY_THREAD = 3;
	/** ターゲット：スレッド投稿 */
	const THREAD_POST = 31;
	/** ターゲット：スレッド削除 */
	const THREAD_DELETE = 32;
	/** ターゲット：スレッド削除 */
	const THREAD_EDIT = 33;
	/** ターゲット：スレッド投稿 */
	const COMMENT_POST = 34;
	/** ターゲット：スレッド削除 */
	const COMMENT_DELETE = 35;
	/** ターゲット：スレッド削除 */
	const COMMENT_EDIT = 36;
	/** ターゲット：ダウンロード */
	const THREAD_FILE_DOWNLOAD = 41;
	/** ターゲット：ダウンロード */
	const THREAD_FILE_UPLOAD = 42;

	/** カテゴリー：ファイル*/
	const CATEGORY_FILE = 4;
	/** ターゲット：ダウンロード */
	const FILE_DOWNLOAD = 41;

	var $table = 'activity_logs';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * アクティビティログを取得する
	 * @param $category_id カテゴリーID
	 * @param $conditions 配列。検索項目をキーとした値を指定します。例： array('summary_id' => ActivityLog::USER_JOIN) ※入会のみのログ
	 * @param $offset 開始位置
	 * @param $limit 一覧数
	 * @return 参照レコードを取得
	 */
	 function find_list_by_key($category_id, $conditions = array(), $offset, $limit = LIST_COUNT) {
		$this->where('category_id', $category_id);
		if (!empty($conditions)) {
			foreach ($conditions as $type => $rules) {
				if ($type === 'in' && is_array($rules)) {
					foreach ($rules as $key => $value) {
						$this->where_in($key, $value);
					} 
				} else if ($type === 'like' && is_array($rules)) {
					foreach ($rules as $key => $value) {
						$this->like($key, $value);
					} 
				} else if ($type === 'and' && is_array($rules)) {
					foreach ($rules as $key => $value) {
						$this->where($key, $value);
					} 
				} else if ($type === 'or' && is_array($value)) {
					foreach ($rules as $key => $value) {
						$this->or_where($key, $value);
					} 
				}
			}
		}
		$result = $this->limit($limit, $offset)
			->order_by('created_at', 'desc')
			->get()->all;
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	 }

	/**
	 * アクティビティログを追加する
	 * @param $recordset データ値
	 * @return クエリ実行結果
	 */
	function insert_recordset($recordset = array()) {
		$target = array('category_id', 'summary_id', 'target_id', 'content_data', 'created_at');
		$this->clear();
		$current_date = date("Y-m-d H:i:s");
		$this->created_at= $current_date;
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$this->{$key} = $recordset[$key];
			}
		}
		return $this->save();
	}
	
	/**
	 * ユーザー入会時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $user_dto ユーザー情報（$this->data['user']を指定する）
	 */
	function on_user_join($user_dto) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_JOIN;
		$recordset['target_id'] = array_selector('id', $user_dto);
		$recordset['content_data'] = json_encode($this->convert_user_to_content_data($user_dto));
		$this->insert_recordset($recordset);
	}

	/**
	 * 仮ユーザー入会時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $user_dto ユーザー情報（$this->data['user']を指定する）
	 */
	function on_user_temporary_join($user_dto) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_TEMPORARY_JOIN;
		$recordset['target_id'] = array_selector('id', $user_dto);
		$recordset['content_data'] = json_encode($this->convert_user_to_content_data($user_dto));
		$this->insert_recordset($recordset);
	}
	
	/**
	 * ユーザー退会時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $user_dto ユーザー情報（$this->data['user']を指定する）
	 */
	function on_user_leave($user_dto) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_LEAVE;
		$recordset['target_id'] = array_selector('id', $user_dto);
		$recordset['content_data'] = json_encode($this->convert_user_to_content_data($user_dto));
		$this->insert_recordset($recordset);
	}
	
	/**
	 * ユーザー情報をアクティビティログの content_data 情報に変換する
	 * @param $user_dto ユーザー情報（$this->data['user']を指定する）
	 */
	private function convert_user_to_content_data($user_dto) {
		$content_data = array(
				'company_code' => array_selector('company_code', $user_dto), 
				'registered_type' => (integer) array_selector('registered_type', $user_dto),
				'gender' => (integer) array_selector('gender', $user_dto),
				'qualification_id' => (integer) array_selector('qualification_id', $user_dto, -1),
			);
		return $content_data;
	}
	
	
	/**
	 * グループ入会時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 */
	function on_group_join($user_id, $group_id) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_GROUP_JOIN;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('group_id' => $group_id));
		$this->insert_recordset($recordset);

		$recordset['category_id'] = $this::CATEGORY_GROUP;
		$recordset['summary_id'] = $this::GROUP_JOIN;
		$recordset['target_id'] = $group_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id));
		$this->insert_recordset($recordset);
	}

	/**
	 * グループ退会時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 */
	function on_group_leave($user_id, $group_id) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_GROUP_LEAVE;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('group_id' => $group_id));
		$this->insert_recordset($recordset);

		$recordset['category_id'] = $this::CATEGORY_GROUP;
		$recordset['summary_id'] = $this::GROUP_LEAVE;
		$recordset['target_id'] = $group_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id));
		$this->insert_recordset($recordset);
	}

	/**
	 * 投稿投稿時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 * @param $body 本文
	 */
	function on_thread_post($user_id, $group_id, $post_id, $body) {
		$body = truncate($body, 50, '...');
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_THREAD_POST;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'body' => $body));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_THREAD_POST;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id, 'body' => $body));
			$this->insert_recordset($recordset);
		}
		
		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::THREAD_POST;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'body' => $body));
		$this->insert_recordset($recordset);
	}

	/**
	 * 投稿削除時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 */
	function on_thread_delete($user_id, $group_id, $post_id) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_THREAD_DELETE;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_THREAD_DELETE;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id));
			$this->insert_recordset($recordset);
		}

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::THREAD_DELETE;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id));
		$this->insert_recordset($recordset);
	}

	/**
	 * 投稿編集時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 * @param $body 本文
	 */
	function on_thread_edit($user_id, $group_id, $post_id, $body) {
		$body = truncate($body, 50, '...');
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_THREAD_EDIT;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'body' => $body));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_THREAD_EDIT;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id, 'body' => $body));
			$this->insert_recordset($recordset);
		}

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::THREAD_EDIT;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'body' => $body));
		$this->insert_recordset($recordset);
	}

	/**
	 * コメント投稿時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 * @param $parent_id コメント親投稿ID
	 * @param $body 本文
	 */
	function on_comment_post($user_id, $group_id, $post_id, $parent_id, $body) {
		$body = truncate($body, 50, '...');
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_COMMENT_POST;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'parent_id' => $parent_id, 'body' => $body));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_COMMENT_POST;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id, 'body' => $body));
			$this->insert_recordset($recordset);
		}

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::COMMENT_POST;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'parent_id' => $parent_id, 'body' => $body));
		$this->insert_recordset($recordset);
	}

	/**
	 * コメント削除時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 * @param $parent_id コメント親投稿ID
	 */
	function on_comment_delete($user_id, $group_id, $post_id, $parent_id) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_COMMENT_DELETE;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'parent_id' => $parent_id));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_COMMENT_DELETE;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id));
			$this->insert_recordset($recordset);
		}

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::COMMENT_DELETE;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'parent_id' => $parent_id));
		$this->insert_recordset($recordset);
	}

	/**
	 * コメント編集時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $group_id グループID
	 * @param $post_id コメント投稿ID
	 * @param $parent_id コメント親投稿ID
	 * @param $body 本文
	 */
	function on_comment_edit($user_id, $group_id, $post_id, $parent_id, $body) {
		$body = truncate($body, 50, '...');
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_COMMENT_EDIT;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'parent_id' => $parent_id, 'body' => $body));
		$this->insert_recordset($recordset);

		if (!empty($group_id)) {
			$recordset['category_id'] = $this::CATEGORY_GROUP;
			$recordset['summary_id'] = $this::GROUP_COMMENT_EDIT;
			$recordset['target_id'] = $group_id;
			$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id, 'body' => $body));
			$this->insert_recordset($recordset);
		}

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::COMMENT_EDIT;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'parent_id' => $parent_id, 'body' => $body));
		$this->insert_recordset($recordset);
	}
	
	/**
	 * ファイルをダウンロード時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $post_id 投稿ID
	 * @param $upload_id アップロードID
	 */
	function on_file_download($user_id, $post_id, $upload_id) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_FILE_DOWNLOAD;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('upload_id' => $upload_id, 'post_id' => $post_id));
		$this->insert_recordset($recordset);

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::THREAD_FILE_DOWNLOAD;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'upload_id' => $upload_id));
		$this->insert_recordset($recordset);

		$recordset['category_id'] = $this::CATEGORY_FILE;
		$recordset['summary_id'] = $this::FILE_DOWNLOAD;
		$recordset['target_id'] = $upload_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'post_id' => $post_id));
		$this->insert_recordset($recordset);
	}

	/**
	 * ファイルをアップロード時にアクティビティログを出力する
	 * @param $user_id ユーザーID
	 * @param $post_id 投稿ID
	 * @param $upload_id アップロードID
	 */
	function on_file_upload($user_id, $post_id, $file_count, $file_size) {
		$recordset = array();
		$recordset['category_id'] = $this::CATEGORY_USER;
		$recordset['summary_id'] = $this::USER_FILE_UPLOAD;
		$recordset['target_id'] = $user_id;
		$recordset['content_data'] = json_encode(array('post_id' => $post_id, 'file_count' => $file_count, 'file_size' => $file_size));
		$this->insert_recordset($recordset);

		$recordset['category_id'] = $this::CATEGORY_THREAD;
		$recordset['summary_id'] = $this::THREAD_FILE_UPLOAD;
		$recordset['target_id'] = $post_id;
		$recordset['content_data'] = json_encode(array('user_id' => $user_id, 'file_count' => $file_count, 'file_size' => $file_size));
		$this->insert_recordset($recordset);
	}
}

