<?php
/**
 * @name スレッドとコメントのコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Post extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->lang->load('application');
		$this->load->config('forminfo');
	}

	/**
	 * スレッドの登録画面を表示する。
	 */
	public function create() {
		try {
			log_message('debug', 'Regenerated session:'.session_regenerate_id());
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$list_expired_types = config_item('forminfo')['expired_types'];
			$this->data['expired_types'] = $list_expired_types;
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('create.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * データベースにスレッド情報を挿入する。
	 */
	public function store($post_from) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$message = array();
			
			// 本文のバリデーションチェック
			$this->setup_validation_rules('post/create');
			$validation_body = $this->form_validation->run($this);
			if(!$validation_body) {
				$message['body'] = $this->form_validation->error_string('<div>', '</div>');
			}

			// 宛先のバリデーションチェック
			$user_id = $this->input->post('hdn_dest_user');
			$group_id = $this->input->post('hdn_dest_group');
			$group_id_upload = $this->input->post('hdn_dest_group_upload');
			$validation_dest = $this->form_validation->check_select_dest($user_id, $group_id);
			if(!$validation_dest) {
				$message['dest'] = $this->lang->line('L-F-0054-E');
			}
			
			// ファイルのバリデーションチェック
			// アップロードディレクトリ情報を設定
			$options = array(
					'user_dirs' => $this->data['user']->user_id.'/'.(!empty($group_id_upload) ? $group_id_upload : 0).'/',
					'user' => $this->data['user'],
	// 				'dicom_parser' => file_exists(DICOM_PARSER) ? DICOM_PARSER : false,
					'initialize' => false
			);
			$this->load->library('UploadHandler', $options);
			$upload_dir = $this->uploadhandler->get_upload_path();

			// 対象ディレクトリを検査
			$dir_list = is_dir($upload_dir) ? array_diff(scandir($upload_dir), array('..', '.', 'thumbnail_l', 'thumbnail_s', 'unzipped')) : array();
			$dir_count = count($dir_list);
			log_message("debug", "upload dir count:".$dir_count);
			$uploaded_file_size = $this->uploadhandler->get_file_objects_file_size();
			$free_size = $this->data['user']->max_file_size - $this->data['user']->file_size - $uploaded_file_size;
			$validation_file = $free_size > 0;
			log_message('debug', sprintf('UPLOAD FREE SIZE: %s = %s - %s - %s', $free_size, $this->data['user']->max_file_size, $this->data['user']->file_size, $uploaded_file_size));
			if (!$validation_file) {
				$message['file'] = $this->lang->line('label_upload_error_max_upload_size');
			}

			if (!$validation_body || !$validation_dest || !$validation_file) {
				echo json_encode($message);
				return;
			} 

			$post['type'] = TYPE_THREAD;
			$post['user_type'] = TYPE_POST_USER;

			$user_login_id = $this->data['user']->id;
			$user_post = array();
			$user_post['ja'] = user_name($this->data['user'], 'japanese');
			$user_post['en'] = user_name($this->data['user'], 'english');
			$post['user_id'] = $user_login_id;
			
			$post['body'] = htmlspecialchars(trim($this->input->post('post_body')));

			//宛先情報
			$forwards = array();
			if ($user_id) {
				$dest_users = array_unique(array_map('intval',explode(',', $user_id)));
				foreach ($dest_users as $dest_user) {
					$forwards[] = array('user_type'=>TYPE_USER, 'send_id'=>$dest_user);
				}
			}
			
			$notice = array();
			$notice['link'] = "user/post/";
			$notice['message'] = json_encode(array('L-F-0062-I', array($user_post)));
			if ($group_id) {
				$dest_group = intval($group_id);
				$forwards[] = array('user_type'=>TYPE_GROUP, 'send_id'=>$dest_group);
				$notice['link_group'] = "group/" . $group_id . "/post/";
			}

			$postdao = new PostDao(MASTER);
			$result = $postdao->insert_post($post, $forwards, $notice);
			if ($result) {
				if ($dir_count > 0) {
					// 公開時にアップロードファイルを暗号化して合わせて公開
//					$this->do_upload_process($postdao, $group_id_upload, $upload_dir, $dir_count);				
					// 公開時にアップロードファイルを暗号化せずバックグラウンド処理を行う形で公開
					$this->do_background_upload_process($postdao, $group_id_upload, $upload_dir, $dir_count);
				}

				// スレッド登録のログ
				$aldao = new ActivityLogDao(MASTER);
				$aldao->on_thread_post($post['user_id'], $group_id, $postdao->id, $post['body']);

				// アップロードのログ
				$uploaddao = new UploadDao();
				$upload = $uploaddao->get_upload_by_post_id($postdao->id);
				if ($upload->total_number > 0) {
					$aldao = new ActivityLogDao(MASTER);
					$aldao->on_file_upload($post['user_id'], $postdao->id, $upload->total_number, $upload->total_size);
				}
				$message['success']="success";
			}
			log_message('debug', 'Post done: '.$postdao->id);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			log_message('debug', 'Regenerated session:'.session_regenerate_id());
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	private function do_background_upload_process($postdao, $group_id_upload, $upload_dir, $dir_count) {
		log_message('debug', 'Background Transfer: '.$postdao->id);
		//　タスク管理に追加
		$taskdao = new TaskDao(MASTER);
		$taskdao->insert_recordset($this->data['user']->id, TYPE_BACKGROUND_UPLOAD_TASK, $upload_dir, $postdao->id, $this->input->post('expire_type'), md5(uniqid(mt_rand(), true)));
		log_message('debug', $taskdao->check_last_query(false, true));
		$postdao->update_upload_status($postdao->id, STATUS_UPLOAD_PROCESSING, $dir_count);
		
		// バッチをバックグラウンドで実行
		execute_command(sprintf(POST_UPLOAD_FILE_TRANSTER, $this->data['user']->id, $postdao->id));
	}
	
	private function do_upload_process($postdao, $group_id_upload, $upload_dir, $dir_count) {
		log_message('debug', 'Transfer: '.$postdao->id);

		// 一時ファイルより転送処理(ZIP解凍＋サムネイル生成は、別処理で行う）
		$updao = new UploadDao(MASTER);
		$updao->transfer_upload_file($upload_dir, $postdao->id, $this->input->post('expire_type'));
				
		$postdao->update_upload_status($postdao->id, STATUS_UPLOAD_PREPROCESS, $dir_count);
		log_message('debug', $postdao->check_last_query(false, true));
	}

	private function count_zip_file($list) {
		$tmpDao = new TmpUploadDao();
		$sorted_file_list = array_indexing_by_key('file_id', $tmpDao->find_list_in_file_id($list));
		$count = 0;
		foreach ($sorted_file_list as $file_id => $targetDto) {
			// フォルダや階層はスキップ
			if (preg_match('/^(\.+|unzipped|thumbnail)$/', $file_id)) {
				continue;
			}
			if ($targetDto->file_extension !== 'zip') {
				continue;
			}
			log_message('debug', 'ZIP FILE: '.$file_id);
			$zip = zip_open($targetDto->file_path);
			if (!is_resource($zip)) {
				continue;					
			}
			while( $entry = zip_read($zip) ) {
				$count++;
			}
			log_message('debug', 'ZIP FILE COUNT: '.$count.'('.$file_id.')');
		}
		return $count;
	}

	private function has_zip_file($dir, $list) {
		$tmpDao = new TmpUploadDao();
		$sorted_file_list = array_indexing_by_key('file_id', $tmpDao->find_list_in_file_id($list));
		foreach ($sorted_file_list as $file_id => $targetDto) {
			// フォルダや階層はスキップ
			if (preg_match('/^(\.+|unzipped|thumbnail)$/', $file_id)) {
				continue;
			}
			if ($targetDto->file_extension === 'zip') {
				return true;	
			}
		}
		return false;
	}
	
	/**
	 * スレッドの編集画面を表示する。
	 * @param $id スレッドID
	 */
	public function edit($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$postdao = new PostDao();
			$post = $postdao->get_post($id);
			$this->data['post_edit_body'] = $post->body;

			$forwarddao = new ForwardDao();
			$forwards = $forwarddao->get_forward($id);
			$forward_name = array();
			$language = $this->data["language"];
			foreach ($forwards as $forward) {
				if($forward->user_type == TYPE_USER) {
					$forward_name[] = user_name($forward, $language);
				} else {
					$forward_name[] = $forward->group_name;
				}
			}

			$postuploaddao = new PostUploadDao();
			$is_expired_72 = $postuploaddao->check_expired_type($id, EXPIRED_TYPE_72_HOURS);
			$comma = $this->lang->line("label_comma");
			$this->data['post_edit_to'] = implode($comma, $forward_name);
			$this->data['post_id'] = $id;
			$this->data['is_expired_72'] = $is_expired_72;
			$this->session->set_userdata('post_id', $id);
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('edit.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * データベースにスレッド情報を更新する。
	 */
	public function update() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				redirect($this->data[''].'user');
			}
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_login_id = $this->data['user']->id;

			$postdao = new PostDao();
			$result = $postdao->check_post_owner($this->session->userdata('post_id'), $user_login_id);
			if (!$result) {
				$message = 'permission_denied';
				echo json_encode($message);
				return;
			}

			$this->setup_validation_rules('post/create');
			$validation = $this->form_validation->run($this);
			if(!$validation) {
				$message = $this->form_validation->error_string('<div>', '</div>');
				echo json_encode($message);
				return;
			}

			$post['id'] = $this->session->userdata('post_id');
			$post['body'] = htmlspecialchars(trim($this->input->post('post_body')));
			$post['extend_72'] = $this->input->post('chk_extend_72');
			$postdao = new PostDao(MASTER);
			$result = $postdao->update_post($post);
			if($result) {
				$forwarddao = new ForwardDao();
				$group_forward_id = $forwarddao->get_group_forward_by_post_id($post['id']);
				
				$asdao = new ActivityLogDao(MASTER);
				$asdao->on_thread_edit($user_login_id, $group_forward_id, $post['id'], $post['body']);
				$message="success";
			}
			$this->session->unset_userdata('post_id');
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * データベースからスレッド情報を削除する。
	 * @param $id スレッドID
	 */
	public function delete($id) {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				redirect($this->data[''].'user');
			}

			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_login_id = $this->data['user']->id;

			$postdao = new PostDao();
			$result = $postdao->check_post_owner($id, $user_login_id);
			if (!$result) {
				$message = 'permission_denied';
				echo json_encode($message);
				return;
			}

			$postdao = new PostDao(MASTER);
			$result = $postdao->delete_post($id);
			if ($result) {
				// スレッド削除のログ
				$forwarddao = new ForwardDao();
				$group_forward_id = $forwarddao->get_group_forward_by_post_id($id);
				$asdao = new ActivityLogDao(MASTER);
				$asdao->on_thread_delete($user_login_id, $group_forward_id, $id);
				
				// ユーザー容量の更新
				$this->update_user_file_size();

				$message = "success";
				$this->clear_csrf(); // 使った CSRFトークン をクリア
				echo json_encode($message);
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * スレッド情報をコピーする。
	 * @param $id スレッドＩＤ
	 */
	public function copy($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$dest = $this->get_dest_by_post_id($id);
			$comma = $this->lang->line('label_comma');
			$this->data['post_edit_to'] = implode($comma, $dest['name']);
			$this->data['dest_user_id'] = implode(',', $dest['user_id']);
			$this->data['dest_group_id'] = implode(',', $dest['group_id']);
			
//			$postdao = new PostDao();
//			$post = $postdao->get_post($id);
//			$this->data['body'] = array_selector('body', $post);

			$list_expired_types = config_item('forminfo')['expired_types'];
			$this->data['expired_types'] = $list_expired_types;
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('create.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * プロファイルを閲覧する時、スレッドを新規作成する。　
	 * @param $id スレッドID
	 */
	public function create_send_to($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$userdao = new UserDao();
			$userdao->where('status', STATUS_ENABLE);
			$userdao->where('registered_status', STATUS_REGIST_USER_ACTIVE);
			$user = $userdao->get_by_id($id);
			$this->data['post_edit_to'] = user_name($user, $this->data['language']);
			$this->data['dest_user_id'] = $user->id;
			
			$list_expired_types = config_item('forminfo')['expired_types'];
			$this->data['expired_types'] = $list_expired_types;
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('create.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ ダッシュボード画面にスレッド情報を新規作成する。
	 * @param $id グループID
	 */
	public function create_for_group($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$groupdao = new GroupDao();
			$group = $groupdao->get_group_by_id($id);
			$this->data['post_edit_to'] = $group->name;
			$this->data['dest_group_id'] = $group->id;

			$list_expired_types = config_item('forminfo')['expired_types'];
			$this->data['expired_types'] = $list_expired_types;
			$this->data['group_id'] = $id;
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('create.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * コメントを新規作成する。
	 * @param $post_id スレッドID
	 */
	public function store_comment($post_id) {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				redirect($this->data[''].'user');
			}

			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$this->setup_validation_rules('comment/create');
			$validation = $this->form_validation->run($this);
			if(!$validation) {
				$message = 'error';
				echo $message;
				return;
			}
			$comment['type'] = TYPE_COMMENT;
			$comment['user_type'] = TYPE_POST_USER;//利用者
			$comment['parent_id'] = $post_id;

			$user_login_id = $this->data['user']->id;
			$user_post = array();
			$user_post['ja'] = user_name($this->data['user'], 'japanese');
			$user_post['en'] = user_name($this->data['user'], 'english');
			$comment['user_id'] = $user_login_id;
			$comment['body'] = htmlspecialchars(trim($this->input->post('comment_body')[0]));
			
			$postdao = new PostDao();
			$postdao->get_user_by_post_id($post_id);
			$owner_thread['ja'] = user_name($postdao, 'japanese');
			$owner_thread['en'] = user_name($postdao, 'english');

			$notice['message'] = json_encode(array('L-F-0064-I', array($user_post, $owner_thread)));
			$notice['message_thread_owner'] = json_encode(array('L-F-0063-I', array($user_post)));
			$notice['user_post'] = $user_post;
			$notice['link'] = 'user/post/' . $comment['parent_id'];
			
			$postdao = new PostDao(MASTER);
			$result = $postdao->insert_post($comment, array(), $notice);
			$new_id = $postdao->id;
			if($result) {
				// コメント登録のログ
				$forwarddao = new ForwardDao();
				$group_forward_id = $forwarddao->get_group_forward_by_post_id($comment['parent_id']);
				$asdao = new ActivityLogDao(MASTER);
				$asdao->on_comment_post($comment['user_id'], $group_forward_id, $new_id, $comment['parent_id'], $comment['body']);

				$postdao = new PostDao();
				$comments = $postdao->get_comment_by_id($new_id);
				$this->data['comments'] = $this->get_array_comment($comments, $user_login_id);
				$this->data['post_id'] = $post_id;
				$postdao = new PostDao();
				$number_comment =  $postdao->count_comment($post_id, $user_login_id);
				$this->data['number_comment'] = $number_comment;
				$this->data['add_new'] = TRUE;
				$this->data['number_comment_show'] = 
						sprintf($this->lang->line('label_hide_comments'), $number_comment);
				$this->data['number_comment_hide'] =
						sprintf($this->lang->line('label_show_comments'), $number_comment);
				$this->parse('comment_list.tpl');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * データベースにコメント情報を更新する。
	 * @param $id コメントID
	 */
	public function update_comment($id) {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				redirect($this->data[''].'user');
			}
			$user_login_id = $this->data['user']->id;

			$postdao = new PostDao();
			$result = $postdao->check_post_owner($id, $user_login_id);
			if (!$result) {
				$arr_post['message'] = 'permission_denied';
				echo json_encode($arr_post);
				return;
			}

			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$arr_post = array();
			$this->setup_validation_rules('comment/update');
			$validation = $this->form_validation->run($this);
			if(!$validation) {
				$arr_post['message'] = 'error';
				echo json_encode($arr_post);
				return;
			}

			$comment['body'] = htmlspecialchars(trim($this->input->post('hdn_body')[0]));
			$comment['id'] = $id;
			$postdao = new PostDao(MASTER);
			$result = $postdao->update_post($comment);
			if ($result) {
				$postdao = new PostDao();
				$post = $postdao->get_comment_by_id($id);
				$user_login_id = $this->data['user']->id;

				//コメント更新のログ
				$forwarddao = new ForwardDao();
				$group_forward_id = $forwarddao->get_group_forward_by_post_id($post->parent_id);
				$asdao = new ActivityLogDao(MASTER);
				$asdao->on_comment_edit($user_login_id, $group_forward_id, $id, $post->parent_id, $comment['body']);

				$label_month = $this->lang->line('label_month');
				$label_day = $this->lang->line('label_day');
				$date = new DateTime($post->updated_at);

				$arr_post['updated_at'] = $date->format(sprintf('m%sd%s H:i', $label_month, $label_day));
				$arr_post['message'] = 'success';
				$arr_post['body'] = $post->body;
			}
			echo json_encode($arr_post);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * データベースからコメント情報を削除する。
	 * @param $post_id スレッドID
	 * @param $comment_id コメントID
	 */
	public function delete_comment($post_id, $comment_id) {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				redirect($this->data[''].'user');
			}
			$user_login_id = $this->data['user']->id;
			$arr_comment = array();
			$arr_comment['message'] = '';

			$postdao = new PostDao();
			$result = $postdao->check_post_owner($comment_id, $user_login_id);
			if (!$result) {
				$arr_comment['message'] = 'permission_denied';
				echo json_encode($arr_comment);
				return;
			}

			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);

			$postdao = new PostDao(MASTER);
			$result = $postdao->delete_post($comment_id);

			$arr_comment = array();
			$arr_comment['message'] = '';

			if ($result) {
				// コメント削除ログ
				$forwarddao = new ForwardDao();
				$group_forward_id = $forwarddao->get_group_forward_by_post_id($post_id);
				$user_login_id = $this->data['user']->id;
				$asdao = new ActivityLogDao(MASTER);
				$asdao->on_comment_delete($user_login_id, $group_forward_id, $comment_id, $post_id);

				$arr_comment['message'] = 'success';
				$postdao = new PostDao();
				$count_comment = $postdao->count_comment($post_id, $user_login_id);
				$arr_comment['count'] = $count_comment;
				if ($count_comment > 0) {
					$arr_comment['show_comment'] = 
						sprintf($this->lang->line('label_show_comments'), $count_comment);
					$arr_comment['hide_comment'] =
						sprintf($this->lang->line('label_hide_comments'), $count_comment);
				}
			}
			echo json_encode($arr_comment);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * スレッドの宛先一覧を取得する
	 * @param $hide_item
	 */
	public function get_dest_list($hide_item) {
		try {
			// ログインチェック処理
			if (!isset($this->data['user'])){
				$this->data['auth'] = false;
			} else {
				$user_login_id = $this->data['user']->id;;
				
				// alphabet
				$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
						'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
						'X', 'Y', 'Z');
						
				$this->benchmark->mark('user_list_begin');						
				$userdao = new UserDao();
				$users = $userdao->get_user_can_be_send($user_login_id);
				log_message('debug', $userdao->check_last_query(false, true));
				$this->benchmark->mark('user_list_end');						
	
				$result = array();
				$language = $this->data["language"];
				foreach ($users as $user) {
					$user->user_name = user_name($user, $language);
					$result[strtoupper((substr($user->last_name, 0, 1)))][] = $user;
		
				}
				$this->data['users'] = $result;
				$this->data['alpha'] = $alpha;
	
				// ログインユーザーのグループ一覧を取得する。
				$this->benchmark->mark('group_list_begin');						
				$groupdao = new GroupDao();
				$groups = $groupdao->get_group_by_user_id($user_login_id);
				log_message('debug', $groupdao->check_last_query(false, true));
				$this->data['groups'] = $groups;
				$this->benchmark->mark('group_list_end');						
	
				// ログインユーザーの履歴を取得する。
				$this->benchmark->mark('history_list_begin');				
				$forwarddao = new ForwardDao();
				$forwards = $forwarddao->get_history_forward($user_login_id);
				foreach ($forwards as $forward) {
					if ($forward->user_type == TYPE_USER) {
						$forward->user_name = user_name($forward, $language);
					}
				}
				log_message('debug', $forwarddao->check_last_query(false, true));
				$this->benchmark->mark('history_list_end');						
				
				$this->data['forwards'] = $forwards;
				$this->data['type_user'] = TYPE_USER;
				$this->data['type_group'] = TYPE_GROUP;
				$this->data['msg_group_error'] = $this->lang->line('L-F-0035-E');
				$this->data['msg_user_error'] = sprintf($this->lang->line('L-F-0043-E'), MAX_USER_SEND);
				$this->data['hide_item'] = $hide_item;
				
				log_message('debug', $this->benchmark->elapsed_time('user_list_begin', 'user_list_end'));
				log_message('debug', $this->benchmark->elapsed_time('group_list_begin', 'group_list_end'));
				log_message('debug', $this->benchmark->elapsed_time('history_list_begin', 'history_list_end'));
				
			}
			$this->parse('select.tpl', 'post/get_dest_list');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 宛先の名前を取得する。
	 */
	public function get_dest_name() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user();

			$user_id = $this->uri->segment(3);
			$group_id = $this->uri->segment(4);
			$arr_user_id = array_map('intval',explode("_", $user_id));
			$list = array();
			if ($user_id) {
				$userdao = new UserDao();
				$users = $userdao->get_user_by_id_in($arr_user_id);
				$language = $this->data["language"];
				foreach($users as $user) {
					$list[] = user_name($user, $language);
				}
			}
			if ($group_id) {
				$groupdao = new GroupDao();
				$group = $groupdao->get_group_by_id($group_id);
				$list[] = $group->name;
			}
			$comma = $this->lang->line('label_comma');
			$result = implode($comma, $list);
			
			echo json_encode($result);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * スレッドのコメント一覧を取得する。
	 * @param $id スレッドＩＤ
	 */
	public function get_comment_list($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_login_id = $this->data['user']->id;;

			$postdao = new PostDao();
			$comments = $postdao->get_comment_detail($id, $user_login_id);

			$this->data['comments'] = $this->get_array_comment($comments, $user_login_id);
			$this->data['post_id'] = $id;
			$this->data['number_comment_show'] = sprintf($this->lang->line('label_hide_comments'), count($comments->all));
			$this->data['number_comment_hide'] = sprintf($this->lang->line('label_show_comments'), count($comments->all));
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('comment_list.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 既読情報を登録する。
	 * @param $id スレッドID
	 */
	public function insert_view($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_user();
			$user_login_id = $this->data['user']->id;

			$open_log_dao = new OpenLogDao();
			$result = $open_log_dao->check_has_record($id, $user_login_id);
			if (!$result) {
				$open_log = array();
				$open_log['post_id'] = $id;
				$open_log['user_id'] = $user_login_id;
				$open_log_dao = new OpenLogDao(MASTER);
				$result = $open_log_dao->insert($open_log);
				if ($result) {
					$asdao = new ActivityStatDao(MASTER);
					$asdao->increment_thread_view($id);
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * コメント配列を作成する。
	 * @param $comments コメント一覧
	 * @param $user_login_id ログインユーザーID
	 */
	private function get_array_comment($comments, $user_login_id) {
		$arr_comments = array();
		$language = $this->data["language"];
		foreach ($comments as $comment) {
			$arr_comment['id'] = $comment->id;
			$arr_comment['user_name'] = user_name($comment, $language);
//			$arr_comment['body'] = addTagA($comment->body);// オリジナルはそのまま使う。テンプレート側で調整すること
			$arr_comment['body'] = $comment->body; 
			$arr_comment['updated_at'] = $comment->updated_at;
			if($comment->user_id == $user_login_id) {
				$arr_comment['is_owned'] = TRUE;
			} else {
				$arr_comment['is_owned'] = FALSE;
			}
			$arr_comments[] = $arr_comment;
		}
		return $arr_comments;
	}
	
	/**
	 * スレッドIDで宛先の名前を取得する
	 * @param $id スレッドID
	 */
	private function get_dest_by_post_id($id) {
		$dest = array();
		$forwarddao = new ForwardDao();
		$forwards = $forwarddao->get_forward($id);

		$forward_name = array();
		$forward_user_id = array();
		$forward_group_id = array();
		$language = $this->data["language"];
		foreach ($forwards as $forward) {
			if($forward->user_type == TYPE_USER) {
				$forward_name[] = user_name($forward, $language);
				$forward_user_id[] = $forward->send_id;
			} else {
				$forward_name[] = $forward->group_name;
				$forward_group_id[] = $forward->send_id;
			}
		}
		$dest['name'] = $forward_name;
		$dest['user_id'] = $forward_user_id;
		$dest['group_id'] = $forward_group_id;
		return $dest;
	}

}
