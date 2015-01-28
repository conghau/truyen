<?php
/**
 * 一時アップロード情報アクセスオブジェクト
 * @name UploadDao
 * @copyright (C)2013 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class TmpUploadDao extends MY_DataMapper {

	/**
	 * テーブル名定義。
	 */
	public $table = "tmp_uploads";
	var $db_params = SLAVE;

	/**
	 * ファイルの初期化メソッド。
	 */
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
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
	 * ファイル有効期限が切れた数を取得する
	 * @param $datetime 有効期限削除対象日
	 */
	public function count_file_expiration($datetime = null) {
		$this->select("count(*) as totalRecord");
		$this->where("deleted_at", null);
		$result = $this->where(array('expired_at <=' => $datetime))->get();
		return $result->totalRecord;
	}
	
	/**
	 * ファイル有効期限が切れた一覧を取得する
	 * @param $datetime 有効期限削除対象日
	 */
	public function find_expired_list($datetime = null, $offset, $limit  = 100) {
		$this->select("*");
		$this->where("deleted_at", null);
		$result = $this->where(array('expired_at <=' => $datetime))->limit($limit, $offset)->get()->all;
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
		$result = $this->where("deleted_at", NULL)->where(array('expired_at <=' => $datetime))->update($target_recordset);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;		
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
		if (!empty($file_ids) && !is_array($file_ids)) {
			$file_ids = array($file_ids);
		}
		if (count($file_ids) > 0) {
			$result = $this->where_in('file_id', $file_ids)->order_by("id asc")->get()->all;
		} else {
			$result = array();
		}
		return $result;
	}

	/**
	 * 指定したファイルIDからデータリストを取得する。
	 */
	public function find_list_by_parent_id($parent_id) {
		$result = $this->where('parent_id', $parent_id)
			->where('deleted_at IS NULL', null, false)
			->get()->all;
		return $result;
	}

	/**
	 * ファイルテーブルにレコードを追加するメソッド
	 * @param aray $file_info
	 * @return boolean: true - 更新成功の場合
	 * 					false - 更新に誤りがある場合
	 */
	public function insert_recordset($recordset = array()) {
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
	 * ファイルテーブルにレコードを更新するメソッド
	 * @param aray $file_info
	 * @return boolean: true - 更新成功の場合
	 * 					false - 更新に誤りがある場合
	 */
	public function update_recordset($id, $recordset = array()) {
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
		
		$this->trans_begin();
		$result = $this->where('id', $id)->update($target_recordset);
		if (!$result) {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	/**
	 * アップロード情報を削除するメソッド
	 * @param interger ID: 削除が必要なファイルのＩＤ。
	 * @return boolean: true - 削除成功の場合
	 * 					false - 削除失敗
	 */
	public function delete_recordset($id) {
		$cur_date_time = date('Y-m-d H:i:s');
		$this->trans_begin();
		$result = $this->where('id', $id)->update(array('deleted_at' => $cur_date_time));
		if ($result === false) {
			$this->trans_rollback();
			return false;
		}
		$this->trans_commit();
		return true;
	}

	/**
	 * アップロード情報を削除するメソッド
	 * @param interger ID: 削除が必要なファイルのＩＤ。
	 * @return boolean: true - 削除成功の場合
	 * 					false - 削除失敗
	 */
	public function delete_by_file_id($file_id, $transaction = true) {
		$cur_date_time = date('Y-m-d H:i:s');
		if ($transaction) {
			$this->trans_begin();
		}
		$target = $this->find_by_file_id($file_id);
		if ($target === NULL) {
			if ($transaction) {
				$this->trans_rollback();
			}
			return false;
		}
		$result = $this->where('id', $target->id)->update(array('deleted_at' => $cur_date_time));
		if ($result === false) {
			if ($transaction) {
				$this->trans_rollback();
			}
			return false;
		}
		$subresult = $this->where('parent_id', $target->id)->update(array('deleted_at' => $cur_date_time));
		$this->trans_commit();
		return true;
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

	public function remove_files($recordset) {
		$id = array_selector('id', $recordset);
		$file_path_list = array(
			array_selector('file_path', $recordset),
			array_selector('small_thumbnail_path', $recordset),
			array_selector('large_thumbnail_path', $recordset)
		);
		foreach ($file_path_list as $file_path) {
			if (!empty($file_path) && file_exists($file_path)) {
				log_message('debug', 'unlink file at tmp_uploads.id '.$id.':'.$file_path);
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

	/**
	 * ユーザIDで削除する
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete_by_user_id($user_id) {
		$this->where('user_id', $user_id);
		$this->where('deleted_at', null);
		$result = $this->update(array('deleted_at' => date("Y-m-d H:i:s")));
		return $result;
	}
}
