<?php
/**
 * グループ-利用者情報アクセスオブジェクト
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class GroupUserDao extends MY_DataMapper {
	
	/**
	 * テーブル名定義。
	 */
	public $table = "group_users";
	var $db_params = SLAVE;

	/**
	 * グループ-利用者情報の初期化メソッド。
	 */
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * グループ-利用者情報を挿入する。
	 * @param $group_id グループID
	 * @param $owner_id グループオーナーID
	 * @param $lstUserID ユーザ一覧
	 * @param $notice お知らせ
	 * @param $type
	 * @return boolean
	 */
	public function insert_user_group($group_id, $owner_id, $lstUserID, $notice = array(), $type = 0) {
		$this->trans_begin();
		$favoritedao = new FavoriteUserDao(MASTER);
		$configdao = new ConfigDao();
		$noticedao = new NoticeDao(MASTER);
		$target_user = $favoritedao->get_target_id_by_user_id($owner_id);
		$userdao = new UserDao();
		$maildao = new MailDao(MASTER);
		$arr_target_user = array();
		if ($target_user->result_count() > 0) {
			foreach($target_user as $user) {
				array_push($arr_target_user, $user->target_user_id);
			}
		}

 		foreach ($lstUserID as $user_id) {
 			if ($user_id != '') {
 				$currentDate = date("Y-m-d H:i:s",time());
 				$count = $this->get_userid_in_group($group_id, $user_id);
 				if ($count > 0) {
 					$this->where('group_id',$group_id);
 					$this->where('user_id',$user_id);
 					$target_recordset = array();
 					if ($type == STATUS_GROUP_USER_OWNER_APPROVE) {
 						$target_recordset['status']	= $type;
 					} else {
 						$target_recordset['status']	= STATUS_GROUP_USER_PENDING_INVITATION;
 					}
 					$target_recordset['updated_at'] = $currentDate;
 					$target_recordset['leaved_at'] = NULL;
 					$result = $this->update($target_recordset);
 				} else {
					$this->group_id 	= $group_id;
					$this->user_id 		= $user_id;
					$this->joined_at 	= $currentDate;
					$this->updated_at 	= $currentDate;
					if ($type == STATUS_GROUP_USER_OWNER_APPROVE) {
						$this->status	= $type;
					} else {
						$this->status	= STATUS_GROUP_USER_PENDING_INVITATION;
					}
					$result = $this->save();
 				}
				if (!$result) {
					$this->trans_rollback();
					return FALSE;
				}
				
				if (in_array($user_id, $arr_target_user) == FALSE) {
					$favoriteResult = $favoritedao->insert_favorite_user($owner_id, $user_id);
					if (!$favoriteResult) {
						$this->trans_rollback();
						return FALSE;
					}
				}
				
				if (count($notice) != 0) {
					$link = $notice['link'].$group_id;
					if (isset($notice['insert'])) {
						$receive_invite = $configdao->check_config_setting_notice($user_id, CONFIG_TYPE_RECEIVE_INVITE);
						if ($receive_invite == TRUE) {
							$mail_to_user = $userdao->get_by_id($user_id);
							$this->lang->load('application', $mail_to_user->language);
							$this->lang->load('msg_error', $mail_to_user->language);
							
							$mail = array();
							$mail['mail_from'] = MAIL_FROM;
							$mail['subject'] = $this->lang->line('label_notice_subject_email');
							$mail['content'] = $noticedao->parse_message($notice['insert']);
							$mail['language'] = $mail_to_user->language;
							$mail['mail_to'] = $mail_to_user->email;
							$mail['link'] = $link;
							$maildao->insert($mail);
						}
						$message = $notice['insert'];
						$noticeResult = $noticedao->insert_notice($user_id, $link, $message);
					} else {
						$message = $notice['request'];
						$noticeResult = $noticedao->insert_notice($owner_id, $link, $message);
					}

					if (!$noticeResult) {
						$this->trans_rollback();
						return FALSE;
					}
				}
			}
		}
		$this->lang->load('application', $this->data['language']);
		$this->lang->load('msg_error', $this->data['language']);
		$this->trans_commit();
		return TRUE;
	}
	
	/**
	 * ユーザーIDを取得する。
	 * @param $group_id グループＩＤ
	 * @param $user_id ユーザーＩＤ
	 */
	private function get_userid_in_group($group_id, $user_id) {
		$conditionWhere = array();
		$conditionWhere["group_users.group_id"] = $group_id;
		$conditionWhere["group_users.user_id"] = $user_id;
		$result = $this->where($conditionWhere)->get()->result_count();
		return $result;
	}
	
	/**
	 * 有効というステータスでのメンバーを取得する。
	 * @param $group_id グループＩＤ
	 * @param $user_id　ユーザーＩＤ
	 */
	public function get_member_in_group($group_id, $type, $user_id = '') {
		$this->select('user_id, last_name_ja, first_name_ja, last_name, first_name, department, users.position,
				organization, group_users.status, qualifications.category_id');
		$this->db->join('users', 'group_users.user_id = users.id', 'inner');
		$this->db->join('qualifications', 'users.qualification_id = qualifications.id', 'left');
		
		$conditionWhere = array();
		$conditionWhere["leaved_at"] = null;
		$conditionWhere["group_users.group_id"] = $group_id;
		if ($user_id != '') {
			$conditionWhere["group_users.user_id"] = $user_id;
		}
		$conditionWhere["users.deleted_at"] = null;
		if ($type == 1) {
			$conditionWhere["group_users.status = "] = STATUS_GROUP_USER_ENABLE;
		} else if ($type == 2) {
			$this->where_in("group_users.status", array(STATUS_GROUP_USER_ENABLE, STATUS_GROUP_USER_OWNER_APPROVE));
		} else if ($type == 3) {
			$conditionWhere["group_users.status != "] = STATUS_GROUP_USER_DISABLE;
		}

		$result = $this->where($conditionWhere)->get();
		return $result;
	}
	
	public function chk_unq_user_group($group_id, $user_id) {
		$this->select('count(*) AS totalRecords');
		$conditionWhere = array();
		$conditionWhere['leaved_at'] = null;
		$conditionWhere['group_id'] = $group_id;
		$conditionWhere['user_id'] = $user_id;
		$result = $this->where($conditionWhere)->get();
		return $result->totalRecords;
	}
	
	public function search_list_group($user_id) {
		$conditionWhere = array();
		$conditionWhere["leaved_at"] = null;
		$conditionWhere['group_users.user_id'] = $user_id;
		$conditionWhere['groups.deleted_at'] = null;
		$conditionWhere['group_users.status'] = STATUS_GROUP_USER_ENABLE;
		$this->select('groups.id,groups.name,groups.summary');
		$this->db->join('groups','groups.id = group_users.group_id');
		$this->db->join('users','groups.user_id = users.id','join');
		$result = $this->where($conditionWhere)->get();
		return $result;
	}
	
	public function update_user_in_group($arr_user = array(), $group_id, $notice = array(), $owner_id='', $arr_user_insert = array()) {
		$this->trans_begin();
		$aldao = new ActivityLogDao(MASTER);
		$noticedao = new NoticeDao(MASTER);
		$configdao = new ConfigDao();
		$userdao = new UserDao();
		$maildao = new MailDao(MASTER);
		foreach($arr_user as $user) {
			$this->where('group_id',$group_id);
			$this->where('user_id',$user['id']);
			if ($user['flag'] == STATUS_GROUP_USER_ENABLE) {
				$currentDate = date("Y-m-d H:i:s",time());
				$target_recordset = array();
				$target_recordset['status'] = STATUS_GROUP_USER_ENABLE;
				$target_recordset['updated_at'] = $currentDate;
				$result = $this->update($target_recordset);
				
				$aldao->on_group_join($user['id'], $group_id);
				
				$link = $notice['link'].$group_id;
				if (isset($notice['approve'])) {
					$message_str = $notice['approve'];
					$user_id = $user['id'];
					$type = CONFIG_TYPE_GROUP_APPROVE;
				} else {
					$message_str = $notice['accept'];
					$user_id = $owner_id;
					$type = CONFIG_TYPE_USER_ACCEPT;
				}
				$noticeResult = $noticedao->insert_notice($user_id, $link, $message_str);
				if (!$noticeResult) {
					$this->trans_rollback();
					return FALSE;
				}
				
				$flag_send_mail = $configdao->check_config_setting_notice($user_id, $type);
				if ($flag_send_mail == TRUE) {
					$mail_to_user = $userdao->get_by_id($user_id);
					$this->lang->load('application', $mail_to_user->language);
					$this->lang->load('msg_error', $mail_to_user->language);
					if ($type == CONFIG_TYPE_USER_ACCEPT) {
						$ = $notice[''];
						$to = array($mail_to_user->email);
						$subject = $this->lang->line('label_notice_subject_email');
						$message = $noticedao->parse_message($message_str) . '<br/>';
						$message = $message . '<a href="'. $ . $link .'">' . $ . $link .'</a>';
						$from = MAIL_FROM;
						$this->send_mail($to, $subject, $message ,$from);
					} else {
						$mail = array();
						$mail['mail_from'] = MAIL_FROM;
						$mail['mail_to'] = $mail_to_user->email;
						$mail['subject'] = $this->lang->line('label_notice_subject_email');
						$mail['content'] = $noticedao->parse_message($message_str);
						$mail['language'] = $mail_to_user->language;
						$mail['link'] = $link;
						$maildao->insert($mail);
					}
				}
			} else {
				$result = $this->update('leaved_at',date("Y-m-d H:i:s",time()));
				$aldao->on_group_leave($user['id'], $group_id);
			}
			
			if(!$result){
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->lang->load('application', $this->data['language']);
		$this->lang->load('msg_error', $this->data['language']);
		$count_member = $this->get_member_in_group($group_id, 1)->result_count();
		if ($count_member > MAX_USER_IN_GROUP || 
				($count_member == MAX_USER_IN_GROUP) && count($arr_user_insert) > 0) {
			$this->trans_rollback();
			return FALSE;
		}
		
		if (count($arr_user_insert) > 0) {
			$result = $this->insert_user_group($group_id, $owner_id, $arr_user_insert, $notice, STATUS_GROUP_USER_PENDING_INVITATION);
		}
		
		$this->trans_commit();
		return TRUE;
	}
	
	public function remove_group_user($user_id,$list_group_id) {
		$this->trans_begin();
		foreach ($list_group_id as $group_id) {
			$this->where('group_id',$group_id['id']);
			$this->where('user_id',$user_id);
			$result = $this->update('leaved_at',date("Y-m-d H:i:s",time()));
			if(!$result){
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		return TRUE;
	}
	
	private function send_mail($target = array(), $subject, $message, $from) {
		//mb_language("ja");
		//mb_internal_encoding("utf8");
	
		if (is_array($from)) {
			$header = "From: ".implode(",", $from)."\n";
		} else {
			$header = "From: ".$from."\n";
		}
		$header .= 'Content-type: text/html; charset=utf-8';
		// 送信対象にメール
		$debug = array_shift(debug_backtrace());
		$debuginfo = $debug['file'].":".$debug['line'];
		foreach ($target as $mail_address) {
			mb_send_mail($mail_address, $subject, $message, $header);
			log_message('mail', sprintf("%s\t%s\t%s", $mail_address, $subject, $debuginfo));
		}
	}
}
?>