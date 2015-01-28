<?php
/**
 * アクティビティ集計アクセスオブジェクト
 * @name ActivityStatsDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php'; 
class ActivityStatDao extends MY_DataMapper {

	/** カテゴリー：スレッド */
	const CATEGORY_THREAD = 3;
	/** ターゲット：閲覧数（全体） */
	const THREAD_VIEW_ALL = 1;
	/** ターゲット：閲覧数（日次） */
	const THREAD_VIEW_DAILY = 2;
	/** ターゲット：DL数（全体） */
	const THREAD_DL_ALL = 3;
	/** ターゲット：DL数（日次） */
	const THREAD_DL_DAILY = 4;
	
	/** カテゴリー：ファイル */
	const CATEGORY_FILE = 4;
	/** ターゲット：DL数（全体） */
	const FILE_DL_ALL = 1;
	/** ターゲット：DL数（日次） */
	const FILE_DL_DAILY = 2;

	var $table = 'activity_stats';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * 集計値を取得する
	 * @param $category_id カテゴリーID
	 * @param $summary_id サマリーID
	 * @param $target_id 集計ID
	 * @param $date 日付 (※必須でない）
	 * @return 参照レコードを取得
	 */
	 function find_by_key($category_id, $summary_id, $target_id, $date = "") {
		$result = $this->where('target_id', $target_id)
			->where('category_id', $category_id)
			->where('summary_id', $summary_id)
			->where('date', $date)
			->get()->all;
		if (count($result) == 0) {
			return NULL;
		} else {
			return array_shift($result);
		}
	 }

	/**
	 * 集計値を加算保存する（レコードがない場合は作成）
	 * @param $category_id カテゴリーID
	 * @param $summary_id サマリーID
	 * @param $target_id 集計ID
	 * @param $value 増減値
	 * @param $date 日付 (※必須でない）
	 * @return 参照レコードを取得
	 */
	function increment_save($category_id, $summary_id, $target_id, $value, $date = "") {
		$result = $this->find_by_key($category_id, $summary_id, $target_id, $date);
		$this->clear();
		$recordset = array();
		$recordset['category_id'] = $category_id;
		$recordset['summary_id'] = $summary_id;
		$recordset['target_id'] = $target_id;
		$recordset['date'] = $date;
		
		if (empty($result)) {
			$recordset['total'] = sprintf('%d', $value);
			$result = $this->insert_recordset($recordset);
		} else {
			$recordset['total'] = sprintf('total + %d', $value);
			$result = $this->update_recordset($result->id, $recordset, false);
		}
		$this->id = null;
		$this->clear();
		return $result;
	}

	/**
	 * 集計値を追加する
	 * @param $recordset データ値
	 * @return クエリ実行結果
	 */
	function insert_recordset($recordset = array()) {
		$target = array('category_id', 'summary_id', 'target_id', 'date', 'total', 'update_at');

		$current_date = date("Y-m-d H:i:s");
		$this->updated_at= $current_date;
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				$this->{$key} = $recordset[$key];
			}
		}
		log_message('debug', 'Insert activity stats:'.var_export($recordset, true));
		return $this->save();		
	}

	/**
	 * 集計値を更新する
	 * @param $id ID
	 * @param $recordset データ値
	 * @return クエリ実行結果
	 */
	function update_recordset($id, $recordset = array(), $escape = true) {
		$this->id = $id;
		$target = array('category_id', 'summary_id', 'target_id', 'date', 'total', 'update_at');
		$current_date = date("Y-m-d H:i:s");

		$target_recordset = array();
		$target_recordset['updated_at'] = sprintf("'%s'", $current_date);
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset)) {
				if ($key == 'total') {
					$target_recordset[$key] = $recordset[$key];
				} else {
					$target_recordset[$key] = sprintf("'%s'", $recordset[$key]);
				}
			}
		}
		return $this->where('id', $id)->update($target_recordset, $escape);
	}

	/**
	 * スレッド参照数を取得する
	 * @param $post_id 投稿ID
	 * @param $date 日付 (※必須でない）
	 * @return 参照レコードを取得
	 */
	 function get_thread_view_count($post_id, $date = "") {
		return $this->find_by_key($this::CATEGORY_THREAD, $this::THREAD_VIEW_ALL, $post_id, $date);	 	
	 }

	/**
	 * ファイルDL数を取得する
	 * @param $upload_id 投稿ID
	 * @param $date 日付 (※必須でない）
	 * @return 参照レコードを取得
	 */
	 function get_file_download_count($upload_id, $date = "") {
		return $this->find_by_key($this::CATEGORY_FILE, $this::FILE_DL_ALL, $upload_id, $date);	 	
	 }

	/**
	 * スレッド参照数を増やす
	 * @param $post_id 投稿ID
	 * @param $value 増減分
	 * 
	 */
	function increment_thread_view($post_id, $value = 1) {
		$this->db->trans_begin();
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_VIEW_ALL, $post_id, $value);
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_VIEW_DAILY, $post_id, $value, date("Ymd"));
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		log_message('thread_view', sprintf("%s\t%s", $post_id, $value));
		$this->trans_commit();
	}

	/**
	 * ファイルDL数を増やす
	 * @param $upload_id ファイルID
	 * @param $post_id 投稿ID
	 * @param $value 増減分
	 * 
	 */
	function increment_file_download($upload_id, $post_id, $value = 1) {
		$this->db->trans_begin();
		$result = $this->increment_save($this::CATEGORY_FILE, $this::FILE_DL_ALL, $upload_id, $value);
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		$result = $this->increment_save($this::CATEGORY_FILE, $this::FILE_DL_DAILY, $upload_id, $value, date("Ymd"));
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_DL_ALL, $post_id, $value);
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_DL_DAILY, $post_id, $value, date("Ymd"));
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		log_message('file_dl', sprintf("%s\t%s", $upload_id, $value));
		$this->trans_commit();
	}
	
	/**
	 * ファイルDL数を増やす
	 * @param $files_id listファイルID
	 * @param $post_id 投稿ID
	 * @param $value 増減分
	 *
	 */
function increment_file_bulkdownload($files_id, $post_id, $value = 1) {
		$this->db->trans_begin();
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_DL_ALL, $post_id, $value);
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		$result = $this->increment_save($this::CATEGORY_THREAD, $this::THREAD_DL_DAILY, $post_id, $value, date("Ymd"));
		if (!$result) {
			$this->trans_rollback();
			return false;
		}
		foreach ($files_id as $upload_id) {
			$result = $this->increment_save($this::CATEGORY_FILE, $this::FILE_DL_ALL, $upload_id, $value);
			if (!$result) {
				$this->trans_rollback();
				return false;
			}
			$result = $this->increment_save($this::CATEGORY_FILE, $this::FILE_DL_DAILY, $upload_id, $value, date("Ymd"));
			if (!$result) {
				$this->trans_rollback();
				return false;
			}
			log_message('file_bulk_dl', sprintf("%s\t%s", $upload_id, $value));
		}
		$this->trans_commit();
	}
	
	/**
	 * 集計値のマトリックステーブルを返す
	 * @param $category_id カテゴリーID
	 * @param $summary_id　集計種別ID
	 * @param $target_ids 集計対象ID
	 * @return $target_id をキーにした集計結果のマトリックス
	 */
	 function get_all_matrix($category_id, $summary_id, $target_ids) {
		$result = array();
		$list = $this->where('category_id', $category_id)
			->where('summary_id', $summary_id)
			->where('date', '')
			->where_in('target_id', $target_ids)->get()->all;
		foreach ($list as $key => $value) {
			$result[$value->target_id] = $value->total;
		}
		return $result;
	 	
	 }

	/**
	 * スレッドに対する参照数の変換テーブル情報を返します
	 * @param $thread_ids 投稿ID一覧
	 * @return スレッドIDをキーにした変換テーブル
	 */
	function get_thread_view_matrix($post_ids) {
		return $this->get_all_matrix($this::CATEGORY_THREAD, $this::THREAD_VIEW_ALL, $post_ids);
	}

	/**
	 * スレッドに対するDL数の変換テーブル情報を返します
	 * @param $thread_ids 投稿ID一覧
	 * @return スレッドIDをキーにした変換テーブル
	 */
	function get_thread_download_matrix($post_ids) {
		return $this->get_all_matrix($this::CATEGORY_THREAD, $this::THREAD_DL_ALL, $post_ids);
	}

	/**
	 * ファイルに対するダウンロード数の変換テーブル情報を返します
	 * @param $thread_ids ファイルID一覧
	 * @return ファイルIDをキーにした変換テーブル
	 */
	function get_file_download_matrix($upload_ids) {
		return $this->get_all_matrix($this::CATEGORY_FILE, $this::FILE_DL_ALL, $upload_ids);
	}
}

