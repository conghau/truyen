<?php

/**
 * 投稿情報アクセスオブジェクト
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class PostDao extends MY_DataMapper {

	var $table = 'posts';
	var $db_params = SLAVE;

	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * 全件を取得する。
	 */
	public function get_all() {
		$this->select('posts.*');
		$this->where('deleted_at',NULL);
		$this->where('type', TYPE_THREAD);
		$this->db->join('users', 'posts.user_id = users.id', 'join');
		return $this->get();
	}

	/**
	 * スレッドを取得する
	 * @param int $id
	 * @return post object
	 */
	public function get_post($id) {
		$this->select("id, title, body, status, user_id, created_at");
		$this->where('deleted_at', null);
		$this->where('posts.id =', $id);
		return $this->get();
	}


	/**
	 * スレッドを取得する
	 * @param int $id
	 * @return post object
	 */
	public function find_by_id($id) {
		$this->select("*");
		$this->where('deleted_at', null);
		$this->where('posts.id =', $id);
		return $this->get();
	}
	
	/**
	 * ユーザーが投稿閲覧権限を持つかチェックする。
	 * @param int $user_id
	 * @param int $post_id
	 * @return boolean
	 */
	public function can_see_post($user_id, $post_id) {
		$posts = $this->get_post_detail_by_user($user_id, 0, 0, $post_id);
		return (count($posts) > 0);
	}

	/**
	 * Get posts detail which are sent by or send to an user
	 * @param int $user_id
	 * @param int $offset
	 * @param int $limit
	 * @param int $post_id
	 * @return object
	 */
	public function get_post_detail_by_user($user_id, $offset, $limit, $post_id = null) {
		// todo: SQL プレイスホルダー
		$binds = array();
		$query = <<<SQL
			SELECT posts.*, users.first_name_ja, users.last_name_ja, users.first_name, users.last_name
			FROM posts join users on posts.user_id = users.id
			WHERE
					posts.deleted_at IS NULL
				and posts.type = ?
				and posts.status = ?
SQL;
		array_push($binds, TYPE_THREAD, STATUS_ENABLE);
		
		if ($post_id != null) {
			$query .= <<<SQL
				and posts.id = ?
SQL;
			array_push($binds, $post_id);
		}
		
		if ($user_id != null) {
			$query .= <<<SQL
				and (
					posts.user_id = ? or
					exists (
						select * from forwards
						where
							forwards.post_id = posts.id
							and not exists (
									select * from blacklist_users bu
									where 
										(bu.target_user_id=posts.user_id and bu.user_id=?)
												or  (bu.target_user_id=? and bu.user_id=posts.user_id)
								)
							and (
								(forwards.user_type = ? and forwards.send_id = ?)
								or
								(forwards.user_type = ? and forwards.send_id in (
									select id from groups g where g.user_id = ?
									union distinct
									select group_id from group_users gu where gu.leaved_at is null and gu.user_id = ? and gu.status = ?
								))
							)
						limit 1
					)
				)
SQL;
			array_push($binds, $user_id, $user_id, $user_id, TYPE_USER, $user_id, TYPE_GROUP, $user_id, $user_id, STATUS_GROUP_USER_ENABLE);
		}
		
		$query .= <<<SQL
		order by posts.updated_at DESC
SQL;
		
		if ($post_id == null) {
			$query .= <<<SQL
				limit ? , ?
SQL;
			array_push($binds, $offset, $limit);
		}
		
		$result = $this->query($query,$binds)->all;
		return $result;
	}
	
	/**
	 * Get post detail which are sent to an group
	 * @param int $user_id
	 * @param int $group_id
	 * @param int $offset
	 * @param int $limit
	 * @return post object
	 */
	public function get_post_detail_by_group($user_id, $group_id, $offset, $limit, $post_id = null) {
		$binds = array();
		$query = <<<SQL
		SELECT posts.*, users.first_name_ja, users.last_name_ja, users.first_name, users.last_name
		FROM posts join users on posts.user_id = users.id
		WHERE
SQL;
		
		if ($post_id != null) {
			$query .= <<<SQL
			posts.id = ? and
SQL;
			array_push($binds, $post_id);
		}
		
		$query .= <<<SQL
			posts.deleted_at is null 
			and type=?
			and exists (
				select * from forwards
				where 
						forwards.post_id = posts.id 
					and forwards.user_type = ? 
					and forwards.send_id = ?
					and not exists (
							select * from blacklist_users bu
							where 
									(bu.target_user_id=posts.user_id and bu.user_id=?)
								or  (bu.target_user_id=? and bu.user_id=posts.user_id)
							)
			 	limit 1
			)
		order by posts.updated_at DESC
SQL;
		array_push($binds, TYPE_THREAD, TYPE_GROUP, $group_id, $user_id, $user_id);
		
		if ($post_id == null) {
			$query .= <<<SQL
			limit ? , ?
SQL;
			array_push($binds, $offset, $limit);
		}
		
		$result = $this->query($query,$binds)->all;
		return $result;
	}

	/**
	 * スレッドとコメントから検索する
	 * Get post id which are sent by or send to an user
	 * @param int $user_id
	 * @return object
	 */
	public function search_post_id_by_user_and_keyword($user_id = '', $keyword='', $offset, $limit) {
		$keyword = '%'.$keyword.'%';

		$query = <<<SQL
SELECT distinct p.target_id as id 
FROM posts_search p join users u on p.user_id = u.id 
WHERE p.deleted_at IS NULL 
and p.status=? 
			and (
				p.title like ? 
				or p.body like ?			
				or u.first_name like ? 
				or u.last_name like ? 
				or u.first_name_ja like ? 
				or u.last_name_ja like ?
			)
and u.deleted_at is null 
and u.status = ? 
SQL;
		if ($user_id != '') {
			$query .= <<<SQL
and ( 
p.user_id = ? or 
exists ( 
select * from forwards f where f.post_id = p.target_id and ( 
(f.user_type = ? and f.send_id = ? and not exists ( 
select * from blacklist_users bu where 
(bu.target_user_id=p.user_id and bu.user_id=f.send_id) 
or 
(bu.target_user_id=f.send_id and bu.user_id=p.user_id)) 
)
or 
(f.user_type = ? and f.send_id in ( 
select id from groups g where g.user_id = ? 
union distinct 
select group_id from group_users gu where gu.leaved_at is null and gu.user_id = ? and gu.status = ? 
)
)
) limit 1)
)
SQL;
		}
		$query .= <<<SQL
		order by p.updated_at DESC, p.id desc
		limit ?, ?
SQL;

		if ($user_id != '') {
			$binds = array(STATUS_ENABLE, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, STATUS_ENABLE, 
						$user_id, TYPE_USER, $user_id, TYPE_GROUP, $user_id, $user_id, STATUS_GROUP_USER_ENABLE, 
						$offset, $limit);
		} else {
			$binds = array(STATUS_ENABLE, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, STATUS_ENABLE,
						$offset, $limit);
		}

		$result = $this->query($query,$binds)->all;
		log_message('debug', $this->check_last_query(false, true));		
		return $result;
	}
	
	/**
	 * Get post id which are sent to an group
	 * @param int $group_id
	 * @return object
	 */
	public function search_post_id_by_group_and_keyword($user_id, $group_id, $keyword, $offset, $limit) {
		$keyword = '%'.$keyword.'%';
		$query = <<<SQL
		SELECT distinct posts.target_id as id
		FROM posts_search posts join users on posts.user_id = users.id
		WHERE
			posts.deleted_at IS NULL
			and posts.status = ?
			and (
				posts.title like ? 
				or posts.body like ?			
				or users.first_name like ? 
				or users.last_name like ? 
				or users.first_name_ja like ? 
				or users.last_name_ja like ?
			)
			and users.deleted_at is null
			and users.status = ?
			and exists (select * from forwards
				where 
						forwards.post_id = posts.id 
					and forwards.user_type = ? 
					and forwards.send_id = ?
					and not exists (
							select * from blacklist_users bu
							where 
									(bu.target_user_id=posts.user_id and bu.user_id=?)
								or  (bu.target_user_id=? and bu.user_id=posts.user_id)
							)
			 	limit 1
			)
		order by posts.updated_at DESC, posts.id desc
		limit ?, ?
SQL;
		$binds = array(STATUS_ENABLE, $keyword, $keyword, $keyword, $keyword, $keyword, $keyword, 
			STATUS_ENABLE, TYPE_GROUP, $group_id, $user_id, $user_id, $offset, $limit);
		$result = $this->query($query,$binds)->all;
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}


	/**
	 * スレッドとコメントから検索する
	 * Get post detail which are sent by or send to an user
	 * @param int $user_id
	 * @return object
	 */
	public function search_post_detail_by_user_and_keyword($user_id = '', $keyword='', $offset, $limit) {
		$result = $this->search_post_id_by_user_and_keyword($user_id, $keyword, $offset, $limit);
		$ids = array();
		foreach ($result as $recordset) {
			$ids[] = $recordset->id;	
		}
		return $this->get_post_detail_by_ids($ids);
	}
	
	/**
	 * Get post detail which are sent to an group
	 * @param int $group_id
	 * @return object
	 */
	public function search_post_detail_by_group_and_keyword($user_id, $group_id, $keyword, $offset, $limit) {
		$result = $this->search_post_id_by_group_and_keyword($user_id, $group_id, $keyword, $offset, $limit);
		$ids = array();
		foreach ($result as $recordset) {
			$ids[] = $recordset->id;	
		}
		return $this->get_post_detail_by_ids($ids);
	}
	
	public function get_post_detail_by_ids($ids) {
		if (count($ids) == 0) {
			$ids = array(-1);
		}

		$p_holder = preg_replace("/,$/", "", str_repeat("?,", count($ids)));
		$binds = array(STATUS_ENABLE, STATUS_ENABLE);
		$binds = array_merge($ids, $binds);

		$query = <<<SQL
		SELECT posts.*, users.first_name_ja, users.last_name_ja, users.first_name, users.last_name
		FROM posts join users on posts.user_id = users.id
		WHERE
		posts.id in ($p_holder)
		and posts.deleted_at IS NULL
		and posts.status = ?
		and users.deleted_at is null
		and users.status = ?
		order by posts.updated_at DESC, posts.id desc
SQL;
		$result = $this->query($query,$binds)->all;
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}
	
	
	/**
	 * Get number of comment of a post
	 * @param int $id
	 * @return count
	 */
	public function count_comment($post_id, $user_id = null) {
		if ($user_id == null) {
			$this->select(' COUNT(posts.id) AS totalRecords ');
			$this->db->join('users', 'posts.user_id = users.id', 'join');
			$this->where('posts.type', TYPE_COMMENT);
			$this->where('posts.deleted_at', null);
			$this->where('posts.parent_id = ', $post_id);
			$this->where("posts.status", STATUS_ENABLE);
			$result = $this->get();
		} else {
			$query = <<<SQL
			SELECT COUNT(posts.id) AS totalRecords
			FROM posts join users on posts.user_id = users.id
			WHERE
					posts.deleted_at IS NULL
				and posts.type = ?
				and posts.status = ?
				and posts.parent_id = ?
				and not exists (
						select * from blacklist_users bu
						where
								(bu.target_user_id=posts.user_id and bu.user_id=?)
							or  (bu.target_user_id=? and bu.user_id=posts.user_id)
					)
SQL;
			$binds = array(TYPE_COMMENT, STATUS_ENABLE, $post_id, $user_id, $user_id);
			$result = $this->query($query,$binds);
		}
		return $result->totalRecords;
	}

	public function get_comment_detail ($post_id, $user_id = null) {
		if ($user_id == null) {
			$sort = 'posts.created_at';
			$order = 'ASC';
			$this->select('posts.*, users.last_name_ja, users.first_name_ja, users.last_name, users.first_name');
			$this->db->join('users', 'posts.user_id = users.id', 'join');
			$this->where('posts.deleted_at', null);
			$this->where('posts.status', STATUS_ENABLE);
			$this->where('posts.type', TYPE_COMMENT);
			$this->where('posts.parent_id', $post_id);
			
			return $this->order_by($sort, $order)->get();
		} else {
			$query = <<<SQL
			SELECT posts.*, users.first_name_ja, users.last_name_ja, users.first_name, users.last_name
			FROM posts join users on posts.user_id = users.id
			WHERE
					posts.deleted_at IS NULL
				and posts.type = ?
				and posts.status = ?
				and posts.parent_id = ?
				and not exists (
						select * from blacklist_users bu
						where 
								(bu.target_user_id=posts.user_id and bu.user_id=?)
							or  (bu.target_user_id=? and bu.user_id=posts.user_id)
					)
			ORDER BY posts.created_at ASC
SQL;
			$binds = array(TYPE_COMMENT, STATUS_ENABLE, $post_id, $user_id, $user_id);
			$result = $this->query($query,$binds);
			return $result;
		}
	}
	
	/**
	 * コメントのIDでコメントを取得する
	 * @param int $comment_id
	 * @return object
	 */
	public function get_comment_by_id($comment_id) {
		$this->select('posts.id, posts.created_at, posts.body, posts.type, posts.status,
				posts.user_id, posts.parent_id, posts.updated_at, users.last_name_ja, users.first_name_ja, users.last_name, users.first_name');
		$this->db->join('users', 'users.id = posts.user_id');
		$this->where('deleted_at', null);
		$this->where('id', $comment_id);
		return $this->get();
	}

	/**
	 * スレッドとコメントを削除する。
	 * @param int $id
	 * @return boolean
	 */
	public function delete_post($id) {
		$this->trans_begin();
		$this->where('id =', $id);
		$this->or_where(sprintf('type = %d and parent_id = %d', TYPE_COMMENT, $id));
		$result = $this->update(array('deleted_at' => date("Y-m-d H:i:s")));

		if ($result) {
			$uploaddao = new UploadDao(MASTER);
			$result = $uploaddao->delete_by_post_id($id);
			if (!$result) {
				$this->trans_rollback();
				return FALSE;
			}
		} else {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}

	/**
	 * 更新
	 * @param array $post_info
	 * @return boolean
	 */
	public function update_post($post_info) {
		$cur_date_time = date("Y-m-d H:i:s");
		$target = array('status', 'body');
		$post = array();
		foreach ($target as $key) {
			if(isset($post_info[$key])) {
				$post[$key] = $post_info[$key];
			}
		}

		$post['updated_at'] = $cur_date_time;
		$this->where('id =', $post_info['id']);
		
		$this->trans_begin();
		$result = $this->update($post);
		if ($result) {
			if (isset($post_info['extend_72']) && $post_info['extend_72']) {
				$uploaddao = new UploadDao();
				$updateuploaddao = new UploadDao(MASTER);
				$uploads = $uploaddao->get_by_post_id($post_info['id'], EXPIRED_TYPE_72_HOURS);
				foreach ($uploads as $upload) {
					$expired_at = date("Y-m-d H:i:s", strtotime($upload->expired_at . " +72 hours"));
					$result = $updateuploaddao->update_expired_date($upload->id, $expired_at);
					log_message('debug', $this->check_last_query(false, true));
					$updateuploaddao->id = null;
					if (!$result) {
						$this->trans_rollback();
						return FALSE;
					}

				}
			}
		} else {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
	
	/**
	 * 新規登録
	 * @param array $post_info
	 */
	public function insert_post($post, $forwards = array(), $notice) {
		$error = FALSE;
		$cur_date_time = date("Y-m-d H:i:s");
		$target = array('type', 'parent_id', 'root_id', 'user_type', 'user_id', 'category_id',
				'title', 'body', 'open_at', 'expired_at');
		foreach ($target as $key) {
			if (isset($post[$key])) {
				$this->{$key} = $post[$key];
			}
		}
		$this->status = STATUS_ENABLE;
		$this->created_at = $cur_date_time;
		$this->updated_at = $cur_date_time;
		$this->trans_begin();
		$result = $this->save();
		// スレッド
		if($result) {
			$users_id = array();
			$post_id = $this->id;
			$notice_link_thread = $notice['link'] . $post_id;
			$notice_link_thread_group = "";
			if (isset($notice['link_group'])) {
				$notice_link_thread_group = $notice['link_group'] . $post_id;
			}
			
			$forwarddao = new ForwardDao(MASTER);
			$groupuserdao = new GroupUserDao();
			$configdao = new ConfigDao();
			$noticedao = new NoticeDao(MASTER);
			$groupdao = new GroupDao();
			$postdao = new PostDao();
			$userdao = new UserDao();
			$maildao = new MailDao(MASTER);
			$blacklist_user = new Blacklist_UserDao();
			
			$mail = array();
			$mail["mail_from"] = MAIL_FROM;

			foreach ($forwards as $forward) {
				$result = $forwarddao->insert($this->id, $forward);
				if($result) {
					// send to user
					if ($forward['user_type'] === TYPE_USER) {
						$receive_notice = $configdao->check_config_setting_notice($forward['send_id'], CONFIG_TYPE_THREAD_USER);
						if ($receive_notice) {
							// send mail
							$user_mail = $userdao->get_by_id($forward['send_id']);
							
							$this->lang->load('application', $user_mail->language);
							$this->lang->load('msg_error', $user_mail->language);
							
							$mail["subject"] = $this->lang->line('label_notice_subject_email');
							$mail["content"] = $noticedao->parse_message($notice["message"]);
							$mail["language"] = $user_mail->language;
							$mail["mail_to"] = $user_mail->email;
							$mail["link"] = $notice_link_thread;
							$result = $maildao->insert($mail);
							if (!$result) {
								$this->trans_rollback();
								return FALSE;
							}
						}
						$result = $noticedao->insert_notice($forward['send_id'], $notice_link_thread, $notice['message']);
						if(!$result) {
							$this->trans_rollback();
							return FALSE;
						} else {
							$users_id[] = $forward['send_id'];
						}
					} else {
						// send to all member in group
						$users = $groupuserdao->get_member_in_group($forward['send_id'], 1);
						$blacklists = $blacklist_user->get_black_list_user($post['user_id']);
						$blacklist_user_id = $this->parse_arr_blacklist_user($blacklists);
						foreach($users as $user) {
							if ($user->user_id != $post['user_id'] && !in_array($user->user_id, $users_id)) {
								$receive_notice = $configdao->check_config_setting_notice($user->user_id, CONFIG_TYPE_THREAD_GROUP);
								$in_blacklist = in_array($user->user_id, $blacklist_user_id);
								if($in_blacklist == FALSE) {
									if ($receive_notice) {
										// send mail
										$user_mail = $userdao->get_by_id($user->user_id);
										$this->lang->load('application', $user_mail->language);
										$this->lang->load('msg_error', $user_mail->language);
										
										$mail["subject"] = $this->lang->line('label_notice_subject_email');
										$mail["content"] = $noticedao->parse_message($notice["message"]);
										$mail["language"] = $user_mail->language;
										$mail["mail_to"] = $user_mail->email;
										$mail["link"] = $notice_link_thread_group;
										$result = $maildao->insert($mail);
										if (!$result) {
											$this->trans_rollback();
											return FALSE;
										}
									}
									$result = $noticedao->insert_notice($user->user_id, $notice_link_thread_group, $notice['message']);
									if (!$result) {
										$this->trans_rollback();
										return FALSE;
									}
								}
							}
						}
						// send to group owner
						$owner = $groupdao->get_group_owner_by_group_id($forward['send_id']);
						$in_blacklist = in_array($owner->id, $blacklist_user_id);
						if($in_blacklist == FALSE) {
							if ($post['user_id'] != $owner->id && !in_array($owner->id, $users_id)) {
								// send mail
								$user_mail = $userdao->get_by_id($owner->id);
								$this->lang->load('application', $user_mail->language);
								$this->lang->load('msg_error', $user_mail->language);
								
								$mail["subject"] = $this->lang->line('label_notice_subject_email');
								$mail["content"] = $noticedao->parse_message($notice["message"]);
								$mail["language"] = $user_mail->language;
								$mail["mail_to"] = $user_mail->email;
								$mail["link"] = $notice_link_thread_group;
								$result = $maildao->insert($mail);
								if (!$result) {
									$this->trans_rollback();
									return FALSE;
								}
								$result = $noticedao->insert_notice($owner->id, $notice_link_thread_group, $notice['message']);
								if (!$result) {
									$this->trans_rollback();
									return FALSE;
								}
							}
						}
					}
				} else {
					$this->trans_rollback();
					return FALSE;
				}
			}

			// コメント
			$has_group = FALSE;
			if ($post['type'] === TYPE_COMMENT) {
				$notice_link_comment = $notice['link'];
				$forwards = $forwarddao->get_forward($post['parent_id']);
				$blacklists = $blacklist_user->get_black_list_user($post['user_id']);
				$blacklist_user_id = $this->parse_arr_blacklist_user($blacklists);

				$thread_owner = $postdao->get_post($post['parent_id']);
				$blacklists = $blacklist_user->get_black_list_user($thread_owner->user_id);
				$blacklist_thread_owner = $this->parse_arr_blacklist_user($blacklists);

				foreach ($forwards as $forward) {
					// send to user
					if ($forward->user_type == TYPE_USER && $post['user_id'] != $forward->send_id) {
						$in_blacklist = in_array($forward->send_id, $blacklist_user_id);
						$in_blacklist_thread_owner = in_array($forward->send_id, $blacklist_thread_owner);

						if($in_blacklist == FALSE && $in_blacklist_thread_owner == FALSE) {
							$receive_notice = $configdao->check_config_setting_notice($forward->send_id, CONFIG_TYPE_COMMENT_USER);
							if ($receive_notice) {
								// send mail
								$user_mail = $userdao->get_by_id($forward->send_id);
								$this->lang->load('application', $user_mail->language);
								$this->lang->load('msg_error', $user_mail->language);
								
								$mail["subject"] = $this->lang->line('label_notice_subject_email');
								$mail["content"] = $noticedao->parse_message($notice["message"]);
								$mail["language"] = $user_mail->language;
								
								$mail["mail_to"] = $user_mail->email;
								$mail["link"] = $notice_link_comment;
								$result = $maildao->insert($mail);
								if (!$result) {
									$this->trans_rollback();
									return FALSE;
								}
							}
							$result = $noticedao->insert_notice($forward->send_id, $notice_link_comment, $notice['message']);
							if(!$result) {
								$this->trans_rollback();
								return FALSE;
							} else {
								$users_id[] = $forward->send_id;
							}
						}
					} else if($forward->user_type == TYPE_GROUP) {
						// send to member in group
						$owner = $groupdao->get_group_owner_by_group_id($forward->send_id);
						$users = $groupuserdao->get_member_in_group($forward->send_id, 1);
						
						$notice_link_thread_group = "group/" . $forward->send_id . "/post/" . $post['parent_id'];
						foreach($users as $user) {
							if ($user->user_id == $thread_owner->user_id) {
								continue;
							}
							if (!in_array($user->user_id, $users_id) && $post['user_id'] != $user->user_id) {
								$receive_notice = $configdao->check_config_setting_notice($user->user_id, CONFIG_TYPE_COMMENT_GROUP_NOT_GROUP_OWNER);
								$in_blacklist = in_array($user->user_id, $blacklist_user_id);
								$in_blacklist_thread_owner = in_array($user->user_id, $blacklist_thread_owner);

								if($in_blacklist == FALSE && $in_blacklist_thread_owner == FALSE) {
									if ($receive_notice) {
										// send mail
										$user_mail = $userdao->get_by_id($user->user_id);
										$this->lang->load('application', $user_mail->language);
										$this->lang->load('msg_error', $user_mail->language);
											
										$mail["subject"] = $this->lang->line('label_notice_subject_email');
										$mail["content"] = $noticedao->parse_message($notice['message']);
										
										$mail["language"] = $user_mail->language;
										$mail["mail_to"] = $user_mail->email;
										$mail["link"] = $notice_link_thread_group;
										$result = $maildao->insert($mail);
										if (!$result) {
											$this->trans_rollback();
											return FALSE;
										}
									}
									$result = $noticedao->insert_notice($user->user_id, $notice_link_thread_group, $notice['message']);
									if(!$result) {
										$this->trans_rollback();
										return FALSE;
									}
								}
							}
						}

						// send to group owner
						if ($post['user_id'] != $owner->id && !in_array($owner->id, $users_id) && ($owner->id != $thread_owner->user_id)) {
							$receive_notice = $configdao->check_config_setting_notice($owner->id, CONFIG_TYPE_COMMENT_GROUP_GROUP_OWNER);
							$in_blacklist = in_array($owner->id, $blacklist_user_id);
							if($in_blacklist == FALSE) {
								if ($receive_notice) {
									// send mail
									$user_mail = $userdao->get_by_id($owner->id);
									$this->lang->load('application', $user_mail->language);
									$this->lang->load('msg_error', $user_mail->language);
									
									$mail["subject"] = $this->lang->line('label_notice_subject_email');
									$mail["content"] = $noticedao->parse_message($notice["message"]);
									$mail["language"] = $user_mail->language;
									$mail["mail_to"] = $user_mail->email;
									$mail["link"] = $notice_link_thread_group;
									$result = $maildao->insert($mail);
									if (!$result) {
										$this->trans_rollback();
										return FALSE;
									}
								}
								$result = $noticedao->insert_notice($owner->id, $notice_link_thread_group, $notice['message']);
								if(!$result) {
									$this->trans_rollback();
									return FALSE;
								}
							}
						}
					}
				}

				// send to thread owner
				if ($post['user_id'] != $thread_owner->user_id) {
					$receive_notice = $configdao->check_config_setting_notice($thread_owner->user_id, CONFIG_TYPE_COMMENT_USER);
					if ($receive_notice) {
						// send mail
						$user_mail = $userdao->get_by_id($thread_owner->user_id);
						$this->lang->load('application', $user_mail->language);
						$this->lang->load('msg_error', $user_mail->language);
						
						$mail["subject"] = $this->lang->line('label_notice_subject_email');
						$mail["content"] = $noticedao->parse_message($notice["message_thread_owner"]);
						$mail["language"] = $user_mail->language;
						$mail["mail_to"] = $user_mail->email;
						$mail["link"] = $notice_link_comment;
						$result = $maildao->insert($mail);
						if (!$result) {
							$this->trans_rollback();
							return FALSE;
						}
					}
					$result = $noticedao->insert_notice($thread_owner->user_id, $notice_link_comment, $notice['message_thread_owner']);
					if(!$result) {
						$this->trans_rollback();
						return FALSE;
					}
				}
			}
		} else {
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		
		$this->lang->load('application', $this->data['language']);
		$this->lang->load('msg_error', $this->data['language']);
		return TRUE;
	}

	/**
	 * 更新
	 * @param array $post_info
	 * @return boolean
	 */
	public function update_upload_status($post_id, $upload_status, $upload_count = false) {
		$cur_date_time = date("Y-m-d H:i:s");
		$post = array();
		$post['upload_status'] = $upload_status;
		if ($upload_count !== false) {
			$post['upload_count'] = $upload_count;
		}
//		$post['updated_at'] = $cur_date_time;
		$this->where('id =', $post_id);
		
		$result = $this->update($post);
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}

		/**
	 * 更新
	 * @param array $post_info
	 * @return boolean
	 */
	public function update_process_status($post_id, $upload_status, $process_count = false) {
		$cur_date_time = date("Y-m-d H:i:s");
		$post = array();
		$post['upload_status'] = $upload_status;
		if ($process_count !== false) {
			$post['process_count'] = $process_count;
		}
//		$post['updated_at'] = $cur_date_time;
		$this->where('id =', $post_id);
		
		$result = $this->update($post);
		log_message('debug', $this->check_last_query(false, true));
		return $result;
	}
	
	/**
	 * 検索条件でスレッド数を取得する。
	 * @param array $condition
	 */
	public function count_by_condition($condition) {
		$this->select(" count(*) As totalRecords ");
		$sqlWhere = $this->create_where($condition);
		$this->db->join('users', 'posts.user_id = users.id', 'join');

		if (isset($condition['group_id']) && $condition['group_id'] != '') {
			$this->db->join('forwards', 'posts.id = forwards.post_id', 'left');
			$this->db->join('groups', 'groups.id = forwards.send_id', 'join');
		}
		
		$this->like($sqlWhere[0]);
		$this->where($sqlWhere[1]);
		$result = $this->get();
		return $result->totalRecords;
	}

	/**
	 * スレッドを検索する
	 * @param array $condition
	 * @param array $limit
	 */
	public function search($condition, $limit = false, $sort = null) {
		if (is_null($sort)) {
			$sort = array(
				'posts.updated_at' => 'desc', 
				'posts.id' => 'desc'
			);
		}
		// 条件作成を実行
		$columns = <<<COLUMN
posts.*
,users.first_name_ja,users.last_name_ja,users.first_name,users.last_name
,parent_posts.user_id as parent_user_id
COLUMN;
		
		if (isset($condition['group_id']) && $condition['group_id'] != '') {
			$columns .= ",forwards.user_type as forward_type";
		}
		
		$this->distinct(true);
		$this->select($columns);
		$sqlWhere = $this->create_where($condition);
		$this->db->join('users', 'posts.user_id = users.id', 'join');
		$this->db->join('posts as parent_posts', 'parent_posts.id = posts.parent_id', 'left');

		if (isset($condition['group_id']) && $condition['group_id'] != '') {
			$this->db->join('forwards', 'posts.id = forwards.post_id', 'left');
			$this->db->join('groups', 'groups.id = forwards.send_id', 'join');
		}

		if ($limit !== false && is_array($limit)) {
			$this->limit($limit[0], $limit[1]);
		}
		$this->like($sqlWhere[0])->where($sqlWhere[1]);
		foreach ($sort as $key => $order) {
			$this->order_by($key, $order);
		}
		$result = $this->get();
		return $result;
	}

	/**
	 * create condition
	 * @param $condition
	 */
	private function create_where($condition) {
		$sqlWhere = array();
		$conditionWhere = array();
		$conditionLike = array();
		
		// デフォルト設定のある条件
		if (!isset($condition['post_deleted_at'])) {
			$conditionWhere['posts.deleted_at'] = null;
		} else if ($condition['post_deleted_at'] !== 'all') { // all という文字でなく、数値が指定されていれば以下
			$conditionWhere['posts.deleted_at'] = $condition['post_deleted_at'];
		}

		if (!isset($condition['post_type'])) {
			$conditionWhere['posts.type'] = TYPE_THREAD;
		} else if ($condition['post_type'] !== 'all') { // all という文字でなく、数値が指定されていれば以下
			$conditionWhere['posts.type'] = $condition['post_type'];
		}

		// 条件指定がなければ戻す
		if (!isset($condition)) {
			$sqlWhere[0] = $conditionLike;
			$sqlWhere[1] = $conditionWhere;
			return $sqlWhere;
		}

		// その他指定があった条件
		$like_target = array('last_name_ja', 'first_name_ja');
		foreach ($like_target as $key) {
			if (isset($condition[$key]) && $condition[$key] != '')
			$conditionLike[$key] = $condition[$key];
		}

		$equal_target = array('id', 'user_id', 'status', 'parent_id', 'upload_status');
		foreach ($equal_target as $key) {
			if (isset($condition[$key]) && $condition[$key] != '') {
				$conditionWhere[$key] = intval($condition[$key]);
			}
		}

		if (isset($condition['group_id']) && $condition['group_id'] != '') {
			$conditionWhere['send_id'] = intval($condition['group_id']);
			$conditionWhere['forwards.user_type'] = TYPE_GROUP;
			$conditionWhere['groups.deleted_at'] = null;
		}

		if (isset($condition['group_post_id']) && $condition['group_post_id'] != '') {
			$conditionWhere['groups.deleted_at'] = null;
			$conditionWhere['groups.id'] = intval($condition['group_post_id']);
		}

		if (isset($condition['from_date']) && $condition['from_date'] != '') {
			$conditionWhere['date(posts.created_at) >='] = $condition['from_date'];
		}

		if (isset($condition['to_date']) && $condition['to_date'] != '') {
			$conditionWhere['date(posts.created_at) <='] = $condition['to_date'];
		}

		if (isset($condition['created_datetime_start']) && trim($condition['created_datetime_start']) !== '') {
			$conditionWhere['posts.created_at >='] = $condition['created_datetime_start'];
		}

		if (isset($condition['created_datetime_end']) && trim($condition['created_datetime_end']) !== '') {
			$conditionWhere['posts.created_at <'] = $condition['created_datetime_end'];
		}
    
		$sqlWhere[0] = $conditionLike;
		$sqlWhere[1] = $conditionWhere;
		return $sqlWhere;
	}

	public function get_user_read_thread ($post_id) {
		$this->select('last_name_ja, first_name_ja, last_name, first_name');
		$this->db->join('open_logs', 'open_logs.post_id = posts.id', 'inner');
		$this->db->join('users', 'open_logs.user_id = users.id', 'left');
		$this->where('posts.deleted_at', null);
		$this->where('users.deleted_at', null);
		$this->where('posts.id', $post_id);
		return $this->get();
	}

	public function get_sender_user ($post_id) {
		$sort = "posts.updated_at";
		$order = "DESC";
		$this->select('last_name_ja, first_name_ja, last_name, first_name, groups.name as send_group, forwards.user_type');
		$this->db->join('forwards', 'forwards.post_id = posts.id', 'left');
		$this->db->join('users', 'forwards.send_id = users.id', 'left');
		$this->db->join('groups', 'forwards.send_id = groups.id', 'left');
		$this->where('posts.id', $post_id);
		$this->where('posts.deleted_at', null);
		$this->where('groups.deleted_at', null);
		$this->where('users.deleted_at', null);
		$result = $this->order_by($sort, $order)->get();
		return $result;
	}
	
	public function parse_post_detail($user, $posts_obj){
		$posts = array();
		
		$postdao = $this;
		$userdao = new UserDao();
		$forwarddao = new ForwardDao();
		$groupdao = new GroupDao();
		$openlogdao = new OpenLogDao();
		$uploaddao = new UploadDao();
		
		$now = new DateTime();
		foreach ($posts_obj as $post_tmp) {
			$post = array();
			$forwards = array();
			$openlogs = array();
			$uploads = array();
		
			$post['id'] = $post_tmp->id;
			$post['user_id'] = $post_tmp->user_id;
			$post['updated_at'] = $post_tmp->updated_at;
			$post['upload_status'] = $post_tmp->upload_status;
//			$post['body'] = addTagA($post_tmp->body); // オリジナルに対しては触れない　テンプレート側で調整すること
			$post['body'] = $post_tmp->body; 
		
			if ($user != NULL) {
				$post['is_owned'] = ($post_tmp->user_id == $user->id);
				$language = $user->language;
			} else {
				$post['is_owned'] = false;
				$language = $this->data['language'];
			}
			$post['owner_name'] = user_name($post_tmp, $language);
		
			$forwards_tmp = $forwarddao->get_by_post_id($post_tmp->id);
			foreach ($forwards_tmp as $forward_tmp) {
				$forward = array();
		
				if ($forward_tmp->user_type == TYPE_USER) {
					if ($user != NULL && $forward_tmp->send_id == $user->id) {
						$send_user = $user;
					} else {
						$send_user = $userdao->get_by_id($forward_tmp->send_id);
					}
					if ($send_user->id != null){
						$forward['name'] = user_name($send_user, $language);
					}
				} else { //forward to a group
					$send_group = $groupdao->get_by_id($forward_tmp->send_id);
					if ($send_group->id != null) {
						$forward['name'] = $send_group->name;
					}
				}
				
				if (isset($forward['name'])){
					array_push($forwards, $forward);
				}
			}
			$post['forwards'] = $forwards;
		
			$openlogs_tmp = $openlogdao->get_by_post_id($post_tmp->id);
			foreach ($openlogs_tmp as $openlog_tmp) {
				$name = user_name($openlog_tmp, $language);
				array_push($openlogs, $name);
			}
			$post['openlogs'] = $openlogs;
		
			$uploads_tmp = $uploaddao->get_by_post_id($post_tmp->id);
		
			$post_files_expired_at = null;
			$item_count = 0;
			foreach ($uploads_tmp as $upload_tmp) {
				
				// Calculate expired time and chose the smallest one
				if ($upload_tmp->expired_type != EXPIRED_TYPE_INDEFINED && $upload_tmp->expired_at) {
					$expired_at = new DateTime($upload_tmp->expired_at);
					if ($expired_at > $now) {
						if ($post_files_expired_at == null || $expired_at < $post_files_expired_at) {
							$post_files_expired_at = $expired_at;
						}
					}
				} else { // Unlimited time
					$expired_at = null;
				}
					
				// 期限切れの場合次へ
				if ($expired_at != null && $expired_at <= $now) {
					continue;
				}
				$item_count ++;
				// 表示個数超えたときも次へ
				if($item_count > DISPLAY_FILE_UPLOAD) {
					continue;
				}
				$upload = array();
				$upload['file_name'] = $upload_tmp->original_file_name;
				$upload['file_size'] = $upload_tmp->file_size;
				$upload['file_extension'] = $upload_tmp->file_extension;
				$upload['file_id'] = $upload_tmp->file_id;
		
				array_push($uploads, $upload);
			}
			$post['file_count'] = $item_count;
		
			if ($post_files_expired_at != null) {
				$post['uploads_expired_at'] = gmdate('Y-m-d\\TH:i:s\\Z', $post_files_expired_at->getTimestamp());
			} else {
				$post['uploads_expired_at'] = '';
			}
			$post['uploads'] = $uploads;
		
			if ($user != null) {
				$user_id = $user->id;
			} else {
				$user_id = null;
			}
			$post['count_comment'] = $postdao->count_comment($post_tmp->id, $user_id);
		
			array_push($posts, $post);
		}
		
		return $posts;
	}
	
	/**
	 * スレッドのオーナーをチェックする
	 * @param int $post_id
	 * @param int $user_id
	 * @return boolean
	 */
	public function check_post_owner($post_id, $user_id) {
		$this->where('id', $post_id);
		$this->where('user_id', $user_id);
		$result = $this->get()->result_count();
		if ($result === 0) {
			return FALSE;
		}
		return TRUE;
	}

	public function get_user_by_post_id($post_id) {
		$this->select('users.last_name, users.first_name, posts.created_at, users.first_name_ja, users.last_name_ja');
		$this->where('posts.id', $post_id);
		$this->db->join('users', 'posts.user_id = users.id', 'join');
		return $this->get();
	}
	

	/**
	 * ユーザIDで削除する
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete_post_by_user_id($user_id) {
		$this->where('user_id', $user_id);
		$result = $this->update(array('deleted_at' => date("Y-m-d H:i:s")));
		return $result;
	}

	public function parse_arr_blacklist_user($object) {
		$black_user_id = array();
		foreach ($object as $obj) {
			array_push($black_user_id,$obj->user_id);
		} 
		return $black_user_id;
	}

	
}