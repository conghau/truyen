<?php
/**
 * 通知情報アクセスオブジェクト
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class NoticeDao extends MY_DataMapper {

	/**
	 * テーブル名定義。
	 */
	public $table = "notices";
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * 通知情報を挿入する。
	 * @param $user_id ユーザーＩＤ
	 * @param $link リンク
	 * @param $message メッセージ
	 * @return boolean
	 */
	public function insert_notice($user_id, $link, $message) {
		if ($user_id != '') {
			$this->clear();
			$currentDate = date("Y-m-d H:i:s",time());
			$this->user_id		= $user_id;
			$this->message		= $message;
			$this->link 		= $link;
			$this->created_at 	= $currentDate;
			$this->updated_at 	= $currentDate;
			$this->status 		= STATUS_ENABLE;
			$result = $this->save();
			if (!$result) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * ユーザーＩＤで未読の通知を取得する。
	 * @param $user_id ユーザーＩＤ
	 */
	public function count_unread_notice_by_user($user_id) {
		$this->select('count(*) as total_notice');
		$this->where('status', STATUS_NOTICE_UNREAD);
		$this->where('deleted_at', null);
		$this->where('user_id',$user_id);
		return $this->get()->total_notice;
	}

	/**
	 * ユーザーＩＤで未読の通知を取得する。
	 * @param $user_id ユーザーＩＤ
	 * @param $offset
	 * @param $limit
	 */
	public function get_notice_by_user($user_id, $offset, $limit) {
		$this->select('*');
		$this->where('status != ', STATUS_NOTICE_HIDDEN);
		$this->where('deleted_at', null);
		$this->where('user_id', $user_id);
		$this->order_by('created_at', 'DESC');
		$this->limit($limit, $offset);
		return $this->get();
	}
	
	/**
	 * ユーザーＩＤで未読の通知を取得する。
	 * @param $user_id ユーザーＩＤ
	 * @param $offset
	 * @param $limit
	 */
	public function set_notice_read($user_id, $notice_id) {

		$now = date("Y-m-d H:i:s",time());
		
		$target_recordset = array();
		$target_recordset['updated_at'] = $now;
		$target_recordset['status'] = STATUS_NOTICE_READ;
		
		$this->trans_begin();
		
		$this->where('user_id', $user_id);
		$this->where('status', STATUS_NOTICE_UNREAD);
		$this->where('id', $notice_id);
		
		$result = $this->update($target_recordset);

		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
	
	public function parse_message($message) {
		$message_arr = json_decode($message, true);
		if ($message_arr != null && is_array($message_arr)) {
			$lang_code = $this->lang->line('language_code');
			$lang_line = $message_arr[0];
			$param = $message_arr[1];
			for ($i = 0; $i < count($param); $i++) {
				if (is_array($param[$i])){
					$param[$i] = $param[$i][$lang_code];
				}
			}
			return vsprintf($this->lang->line($lang_line), $param);
		} else {
			return $message;
		}
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