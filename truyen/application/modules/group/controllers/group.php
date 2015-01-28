<?php
/**
 * @name グループのコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Group extends MY_Controller {
	const STATUS_USER_JOIN_GROUP = 1;
	
	public function __construct() {
		parent::__construct();
		$this->load->config('forminfo');

		$lstPublicStatus = config_item('forminfo')['front']['group']['group_public_status'];
		$this->data['lst_public_status'] = $lstPublicStatus;

		if (!isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');
		$this->load->helper('form', 'url');
		$this->lang->load('application');
		$this->load->library('upload');
	}

	/**
	 * グループダッシュボード画面の表示処理
	 * @param $group_id グループID
	 */
	public function index($group_id = '') {
 		try {
 			$this->form_validation->check_login_user();
 			
			if ($group_id === ''){
				redirect($this->data[''].'user');
			}
			$this->lang->load('form_validation');
			$this->data['message_error'] = sprintf($this->lang->line('required'),
			$this->lang->line('label_post_body'));
			$this->data['msg_confirm_del_thread'] = $this->lang->line('L-F-0042-Q');
			$this->data['msg_confirm_del_comment'] = $this->lang->line('L-F-0030-Q');
			
			$user = $this->userauth->getUser();
			$groupdao = new GroupDao();
			$group_info = $groupdao->get_group_by_id($group_id);
			if ($group_info->result_count() == 0 or $group_info->status != STATUS_GROUP_ENABLE) {
				$this->get_info_user();
				$this->parse('group_no_exist.tpl', 'group/group_no_exist');
				return;
			}
			if ($this->is_valid_user($group_info, $user)) {
				$postdao = new PostDao();

				$this->load->config('pagination');
				$limit = $this->config->config['pagination']['index_page'];
				$offset = 0;

				// 検索による絞り込みに対応
				$this->data['keyword'] = substr($this->input->post('keyword'), 0, MAX_SEARCH_WORD_LENGTH);
				if (!empty($this->data['keyword'])) {
					$posts_tmp = $postdao->search_post_detail_by_group_and_keyword($user->id, $group_id, $this->data['keyword'], $offset, $limit);
				} else {
					$posts_tmp = $postdao->get_post_detail_by_group($user->id, $group_id, $offset, $limit);
				}				

				$posts = $postdao->parse_post_detail($user, $posts_tmp);
				
				$offset += count($posts);
				
				$this->data['posts'] = $posts;	
				$this->data['post_offset'] = $offset;
				$this->data['post_query_url'] = $this->data[''] . 'group/' .  $group_id .'/get_post';
			}
			$this->data['group_id'] = $group_id;
			$this->parse('index.tpl', 'group/index');
 		} catch (Exception $e) {
 			log_message('error', $e->getMessage());
 			show_error($e->getMessage());
 		}
	}

	protected function is_valid_user($group_info, $user) {
		$member = FALSE;
		$group_id = $group_info->id;
		if ($group_info->user_id == $user->id) {
			$member = TRUE;
		} else {
			$groupuserdao = new GroupUserDao();
			$group_user_info = $groupuserdao->get_member_in_group($group_id, 3, $user->id);
			if ($group_user_info->result_count() == 0) {
				if ($group_info->public_status == STATUS_GROUP_PUBLIC) {
					$blacklistdao = new Blacklist_UserDao();
					$blacklist_owner = $blacklistdao->get_by_target_user_id($user->id, $group_info->user_id);
					$blacklist_member = $blacklistdao->get_by_target_user_id($group_info->user_id, $user->id);
					if (!isset($blacklist_owner->user_id) && !isset($blacklist_member->user_id)) {
						$this->data['group_user_status'] = $this::STATUS_USER_JOIN_GROUP;
					} else {
						redirect($this->data[''].'user');
					}
				} else {
					redirect($this->data[''].'user');
				}
			} else {
				if ($group_user_info->status == STATUS_GROUP_USER_PENDING_INVITATION) {
					$this->data['group_user_status'] = STATUS_GROUP_USER_PENDING_INVITATION;
				} else if ($group_user_info->status == STATUS_GROUP_USER_OWNER_APPROVE) {
					$this->data['group_user_status'] = STATUS_GROUP_USER_OWNER_APPROVE;
				} else {
					$member = TRUE;
				}
			}
		}
		return $member;
	}
	
	/**
	 * グループIDによって現在のグループの各投稿の内容を取得する。
	 * @param $group_id グループID
	 */
	public function get_post($group_id = '', $offset = 0) {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			$offset = intval($offset);
			
			$user = $this->userauth->getUser();
			
			$groupdao = new GroupDao();
			$group_info = $groupdao->get_group_by_id($group_id);
			if ($group_info->result_count() == 0 or $group_info->status != STATUS_GROUP_ENABLE) {
				return;
			}
			
			$member = FALSE;
			if ($group_info->user_id == $user->id) {
				$member = TRUE;
			} else {
				$groupuserdao = new GroupUserDao();
				$group_user_info = $groupuserdao->get_member_in_group($group_id, STATUS_GROUP_USER_ENABLE, $user->id);
				if ($group_user_info->result_count() > 0) {
					$member = TRUE;
				}
			}
			
			if (!$member)
				return;

			$postdao = new PostDao();
			
			$this->load->config('pagination');
			$limit = $this->config->config['pagination']['per_page'];

			// 検索による絞り込みに対応
			$this->data['keyword'] = substr($this->input->post('keyword'), 0, MAX_SEARCH_WORD_LENGTH);
			if (!empty($this->data['keyword'])) {
				$posts_tmp = $postdao->search_post_detail_by_group_and_keyword($user->id, $group_id, $this->data['keyword'], $offset, $limit);
			} else {
				$posts_tmp = $postdao->get_post_detail_by_group($user->id, $group_id, $offset, $limit);
			}
			$posts = $postdao->parse_post_detail($user, $posts_tmp);

			$this->data['posts'] = $posts;
			$this->parse('post_layout.tpl', 'group/get_post');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}


	/**
	 * 投稿IDからグループIDを含めたURLに変換するリダイレクトページ
	 * @param $post_id 投稿ID一覧
	 */
	public function post($post_id) {
		try {
			if (empty($post_id)) {
				redirect();	
			}
			$forwarddao = new ForwardDao();
			$group_id = $forwarddao->get_group_forward_by_post_id($post_id);
			
			$user = $this->userauth->getUser();
			$destinations = $forwarddao->get_by_post_id($post_id);
			$user_id = null;
			foreach ($destinations as $destination) {
				if ($destination->user_type === '1' && $destination->send_id = $user->id) {
					$user_id = $user->id;
					break;
				}
			}

			if (!empty($user_id)) {			
				redirect(sprintf("/user/post/%s", $post_id));
			} else if (!empty($group_id)) {
				redirect(sprintf("/group/%s/post/%s", $group_id, $post_id));
			} else {
				redirect();	
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}


	public function view_post($group_id = '', $post_id = null) {
		try {
			$result = $this->setup_view_post($group_id, $post_id);
			if ($result) {
				$this->get_info_user();
				$this->parse('group_no_exist.tpl', 'group/group_no_exist');
				return;
			}
			$this->parse('index.tpl', 'group/view_post');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}


	public function view_file($group_id = '', $post_id = null) {
		try {
			$this->setup_view_post($group_id, $post_id);
			$this->data['post'] = array_shift($this->data['posts']);
			$this->parse('file.tpl', 'group/view_file');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function get_info_user() {
		$user = $this->userauth->getUser();
		if (isset($user)) {
			$this->data['master_user_name'] = user_name($user, $user->language);
			$this->data['master_user_department'] = $user->organization.'　'.$user->position;
			$this->data['master_user_id'] = $user->id;

			$qualification = new QualificationDao();
			$category_id = $qualification->get_by_id($user->qualification_id)->category_id;
			$this->data['master_category_id'] = $category_id;
			$groupdao = new GroupDao();
			$group_joined = $groupdao->get_group_by_user_id($user->id)->all;
			$groupdao = new GroupDao();
			$group_can_join = $groupdao->get_group_can_join($user->id)->all;
			$this->data['master_group_joined']	= $group_joined;
			$this->data['master_group_can_join'] = $group_can_join;

			$this->data['msg_confirm_logout'] = $this->lang->line('L-F-0058-Q');
		}
	}
	
	private function setup_view_post($group_id = '', $post_id = null) {
 		$this->form_validation->check_login_user();
 		
		if ($group_id === ''){
			redirect($this->data[''].'user');
		}
		$this->lang->load('form_validation');
		$this->data['message_error'] = sprintf($this->lang->line('required'),
				$this->lang->line('label_post_body'));
		$this->data['msg_confirm_del_thread'] = $this->lang->line('L-F-0042-Q');
		$this->data['msg_confirm_del_comment'] = $this->lang->line('L-F-0030-Q');
		
		$user = $this->userauth->getUser();
		$groupdao = new GroupDao();
		$group_info = $groupdao->get_group_by_id($group_id);
		if ($group_info->result_count() == 0 or $group_info->status != STATUS_GROUP_ENABLE) {
			return TRUE;
		}
		if ($this->is_valid_user($group_info, $user)) {
			$postdao = new PostDao();
			$posts_tmp = $postdao->get_post_detail_by_group($user->id, $group_id, 0, 0, $post_id);
			if (count($posts_tmp) == 0) {
				$this->data['msg_no_exist'] = $this->lang->line("label_post_no_exist");
				log_message("dao-lta", $this->data['msg_no_exist']);
			} else {
				log_message("dao-lta", "fail");
				$posts = $postdao->parse_post_detail($user, $posts_tmp);
				$this->data['posts'] = $posts;
				$this->data['post_offset'] = -1;
			}
		}
		$this->data['post_query_url'] = $this->data[''] . 'group/' .  $group_id .'/get_post_detail';
		$this->data['group_id'] = $group_id;
	}
	
	/**
	 * グループ新規登録画面の表示処理。
	 */
	public function create() {
		try{
			$this->form_validation->check_login_user();

			if (isset($_SESSION['group_info_create'])) {
				unset($_SESSION['group_info_create']);
			}
			
			$this->data['name'] 			= "";
			$this->data['summary'] 			= "";
			$this->data['hdn_invite_user'] 	= "";
	
			$this->parse('group_create.tpl', 'group/create');
		} catch(Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ新規登録確認画面の表示処理。
	 */
	public function confirm_create() {
		try {
			// Check login
			$this->form_validation->check_login_user();

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info_create'])) {
					unset($_SESSION['group_info_create']);
				}
				redirect('group/create');
				return;
			}

			$flag_error = false;
			// 設定ファイルロードの処理
			$this->setup_validation_rules("group/regist");
			// validateのチェック処理
			$validate = $this->form_validation->run($this);
			// エラーメッセージが出ない場合
			if ($validate === FALSE) {
				$this->data['errors'] = $this->form_validation->error_array();
				$flag_error = true;
			}
			$users_add = array();
			$lstAddUser = $this->input->post('hdn_invite_user');
			$language = $this->data['language'];
			if (strlen($lstAddUser) > 0) {
				$lstUserInfo = array();
				$users_add = preg_split('/[\s,]+/', $lstAddUser);
				$users_add = array_unique($users_add);
				foreach ($users_add as $user_add) {
					if ($user_add != '') {
						$user = new UserDao();
						$user_info = $user->get_by_id($user_add);
						if ($user_info->result_count() > 0) {
							$user_info->name = user_name($user_info, $language);
							array_push($lstUserInfo, $user_info);
						}
					}
				}
				$this->data['lst_user_add_info'] = $lstUserInfo;
			}
			if($flag_error) {
				// リクエストからのページ情報取得
				$this->data['name'] 		= $this->input->post('name');
				$this->data['summary'] 		= $this->input->post('summary');
				$this->data['public_status']= $this->input->post('public_status');
				$this->data['hdn_invite_user']= $this->input->post('hdn_invite_user');
				$this->parse('group_create.tpl','group/create');
			} else {
				// groupsの配列の初期化
				$groups = array();
				$groups['name'] 			= $this->input->post('name');
				$groups['summary'] 			= $this->input->post('summary');
				$groups['arr_invite_user'] 	= $users_add;
				$groups['public_status'] 	= $this->input->post('public_status');

				$_SESSION['group_info_create'] =  $groups;
				$this->data['group_info']= $groups;
				$this->parse('group_confirm_create.tpl','group/confirm_create');
			}
		} catch(Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ新規登録完了画面の表示処理。
	 */
	public function store() {
		try {
			// Check login
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_login_id = $this->userauth->getUser()->id;

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info_create'])) {
					unset($_SESSION['group_info_create']);
				}
				redirect('group/create');
				return;
			}

			if (!isset($_SESSION['group_info_create'])) {
				redirect('group/create');
				return;
			}

			$sessionGroupInfo = $_SESSION['group_info_create'];
			$group_info = array();
			$group_info['name'] 			= htmlspecialchars(trim($sessionGroupInfo['name']));
			$group_info['summary'] 			= htmlspecialchars(trim($sessionGroupInfo['summary']));
			$group_info['public_status']	= $sessionGroupInfo['public_status'];
			$group_info['user_id'] 			= $user_login_id;
			$group_info['status']			= STATUS_GROUP_ENABLE;

			$user_add = array();
			$user_add =  $sessionGroupInfo['arr_invite_user'];
			
			$groups = new GroupDao(MASTER);
			$user = $this->userauth->getUser();
			$name = array();
			$name['ja'] = user_name($user, 'japanese');
			$name['en'] = user_name($user, 'english');
			$notice['link'] = 'group/';
			$notice[''] = $this->data[''];
			$notice['insert'] = json_encode(array('L-F-0044-I', array($group_info['name'], $name)));

			$result = $groups->insert_group($group_info, $user_add, $notice);
			if (!$result) {
				$msgID = 'L-F-0004-E';
				$data['group_id'] = 0;
			} else {
				$msgID = 'L-F-0057-I';
				$data['group_id'] = $result;
			}
			unset($_SESSION['group_info_create']);
			$data['message'] = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($data);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ編集画面の表示処理
	 *  @param integer $id
	 */
	public function edit() {
		try {
			// Check login
			$this->form_validation->check_login_user();
			$user_login_id = $this->userauth->getUser()->id;
			$group_id = $this->uri->segment(2);

			$groupdao = new GroupDao();
			if ($groupdao->get_by_id($group_id)->user_id != $user_login_id) {
				redirect('user');
			}

			if (isset($_SESSION['group_info_edit'])) {
				unset($_SESSION['group_info_edit']);
			}
			$_SESSION['group_id'] = $group_id;
			if ($groupdao->get_group_by_id($group_id)->id === null) {
				redirect('user');
			}
			$this->load_groups_info(0);
			$this->data['process_edit'] = TRUE;
			$this->parse('group_edit.tpl', 'group/edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ編集確認画面の表示処理
	 *  @param integer $id
	 */
	public function confirm_edit() {
		try {
			// Check login
			$this->form_validation->check_login_user();
			
			$group_id = $this->uri->segment(2);

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info_edit'])) {
					unset($_SESSION['group_info_edit']);
				}
				redirect('group/' . $group_id);
			}

			$flag_error = false;
			// 設定ファイルロードの処理
			$this->setup_validation_rules("group/regist");
			// validateのチェック処理
			$validate = $this->form_validation->run($this);
			// エラーメッセージが出ない場合
			if ($validate === FALSE) {
				$this->data['errors'] = $this->form_validation->error_array();
				$flag_error = true;
			}
			$lstAddUser = $this->input->post('hdn_invite_user');
			$users_add = array();
			$language = $this->data['language'];
			if (strlen($lstAddUser) > 0) {
				$groupuserdao = new GroupUserDao();
				$member = $groupuserdao->get_member_in_group($group_id, 1);
				if ($member->result_count() >= MAX_USER_IN_GROUP) {
					$this->data['error'] = sprintf($this->lang->line('L-F-0047-E'), MAX_USER_IN_GROUP);
					$flag_error = true;
				} else {
					$lstUserInfo = array();
					$users_add = preg_split('/[\s,]+/', $lstAddUser);
					$users_add = array_unique($users_add);
					foreach ($users_add as $user_add) {
						if ($user_add != '') {
							$user = new UserDao();
							$user_info = $user->get_by_id($user_add);
							if ($user_info->result_count() > 0) {
								$user_info->name = user_name($user_info, $language);
								array_push($lstUserInfo, $user_info);
							}
						}
					}
					$this->data['lst_user_add_info'] = $lstUserInfo;
				}
			}
			$this->data['process_edit'] = TRUE;
			if($flag_error) {
				$this->load_groups_info(1);
				$this->parse('group_edit.tpl','group/edit');
			} else {
				// groupsの配列の初期化
				$groups = array();
				$groups['group_id'] 		= $group_id;
				$groups['name'] 			= $this->input->post('name');
				$groups['summary'] 			= $this->input->post('summary');
				$groups['arr_invite_user'] 	= $users_add;
				$groups['public_status'] 	= $this->input->post('public_status');

				$_SESSION['group_info_edit'] = $groups;
				if (isset($_SESSION['lst_user_info'])) {
					$this->data['lst_user_info'] = unserialize($_SESSION['lst_user_info']);
				}
				$this->data['group_info']= $groups;
				$this->parse('group_confirm_edit.tpl','group/confirm_edit');
			}
		} catch(Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ編集完了画面の表示処理
	 * @param integer $id
	 */
	public function update() {
		try {
			// Check login
			$this->form_validation->check_login_user(TYPE_AJAX);
			$group_id = $this->uri->segment(2);

			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				if (isset($_SESSION['group_info_edit'])) {
					unset($_SESSION['group_info_edit']);
				}
				redirect('group/'.$group_id);
				return;
			}

			if (!isset($_SESSION['group_info_edit'])) {
				redirect('group/'.$group_id);
				return;
			}

			$sessionGroupInfo = $_SESSION['group_info_edit'];
			$group_info = array();
			$group_id = $sessionGroupInfo['group_id'];
			$group_info['name'] 			= htmlspecialchars(trim($sessionGroupInfo['name']));
			$group_info['summary'] 			= htmlspecialchars(trim($sessionGroupInfo['summary']));
			$group_info['public_status']	= $sessionGroupInfo['public_status'];
			$group_info['user_id']			= $this->userauth->getUser()->id;

			$groups = new GroupDao(MASTER);
			$user_add = array();
			$user_add = $sessionGroupInfo['arr_invite_user'];

			$user = $this->userauth->getUser();
			$name = array();
			$name['ja'] = user_name($user, 'japanese');
			$name['en'] = user_name($user, 'english');
			$notice['link'] = 'group/';
			$notice[''] = $this->data[''];
			$notice['insert'] = json_encode(array('L-F-0044-I', array($group_info['name'], $name)));
			$result = $groups->update_group($group_id, $group_info, $user_add, $notice);
			
			if (!$result) {
				$msgID = 'L-F-0006-E';
			} else {
				$msgID = 'L-F-0005-I';
			}
			unset($_SESSION['group_info_edit']);
			unset($_SESSION['group_id']);
			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ削除完了画面の表示処理
	 */
	public function delete() {
		try {
			// Check login
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_login_id = $this->userauth->getUser()->id;
			$group_id = $this->uri->segment(2);

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info_edit'])) {
					unset($_SESSION['group_info_edit']);
				}
				redirect('group/create');
			}

			$groupdao = new GroupDao();
			if ($groupdao->get_by_id($group_id)->user_id != $user_login_id) {
				redirect('group/create');
			}

			$msgID = "";
			$group = new GroupDao(MASTER);
			$result = $group->delete_group($group_id);

			if (!$result) {
				$msgID = 'L-F-0002-E';
			} else {
				$msgID = 'L-F-0001-I';
			}
			unset($_SESSION['group_info_edit']);
			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザーがグループを退会した時の処理
	 * @param $group_id グループID
	 */
	public function leave() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->form_validation->check_login_user(TYPE_AJAX);
				$user_login_id = $this->userauth->getUser()->id;
				$group_id = $this->input->post('group_id');
				$group_ids = array('id'=>$group_id);
				$group_members = array($group_ids);
				$groupuserdao = new GroupUserDao(MASTER);
				$result = $groupuserdao->remove_group_user($user_login_id, $group_members);
				if ($result){
					$logdao = new ActivityLogDao(MASTER);
					$logdao->on_group_leave($user_login_id, $group_id);
				}
				header('Content-Type: application/json');
				$this->clear_csrf(); // 使った CSRFトークン をクリア
				echo json_encode($result);
			} else {
				redirect($this->data['']);
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ情報のロード処理
	 * @param $mode モード
	 */
	private function load_groups_info($mode) {
		// 初期表示の場合
		$group_id = $_SESSION['group_id'];
		if ($mode == 0) {
			if($group_id !== FALSE) {
				// データベースからのページ情報取得
				$groupdao = new GroupDao();
				$groups = $groupdao->get_group_by_id($group_id);

				$this->data['groupinfo']  	= $groups;
				$this->data['group_id'] 	= $group_id;
				$this->data['name'] 		= htmlspecialchars_decode($groups->name);
				$this->data['summary'] 		= htmlspecialchars_decode($groups->summary);
				$this->data['public_status']= $groups->public_status;
				$this->data['hdn_invite_user']= "";
				$language = $this->data['language'];
				$groupuserdao = new GroupUserDao();
				$lstGroupUser = $groupuserdao->get_member_in_group($group_id, 3);
				foreach ($lstGroupUser as $user) {
					$user->name = user_name($user, $language);
				}
				$this->data['lst_user_info'] = $lstGroupUser;
				if (count($lstGroupUser) > 0) {
					$_SESSION['lst_user_info'] = serialize($lstGroupUser);
				}
			}
		} else {
			// リクエストからのページ情報取得
			$this->data['group_id'] 	= $group_id;
			$this->data['name'] 		= $this->input->post('name');
			$this->data['summary'] 		= $this->input->post('summary');
			$this->data['public_status']= $this->input->post('public_status');
			$this->data['hdn_invite_user']= $this->input->post('hdn_invite_user');
			if (isset($_SESSION['lst_user_info'])) {
				$this->data['lst_user_info'] = unserialize($_SESSION['lst_user_info']);
			}
		}
	}
		
	/**
	 * グループへ追加可能なユーザーの一覧を取得する。
	 */
	public function get_invite_list() {
		try {
			// Check login
			if (!isset($this->data['user'])){
				$this->data['auth'] = false;
			} else {
				$user = $this->userauth->getUser();
				$user_login_id = $user->id;
	
				$group_id = $this->uri->segment(3);
	
				// user
				$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
						'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
						'X', 'Y', 'Z');
				$userdao = new UserDao();
				$users = $userdao->get_user_can_add_group($user_login_id, $group_id);
	
				$result = array();
				$language = $this->data['language'];
				foreach ($users as $user) {
					foreach ($alpha as $a) {
						if (strtolower(substr($user->last_name, 0, 1)) === strtolower($a)) {
							$user->name = user_name($user, $language);
							$result[$a][] = $user;
						}
					}
				}
				$this->data['users'] = $result;
				$this->data['alpha'] = $alpha;
	
				// history
				$favoriteuserdao = new FavoriteUserDao();
				$favorite = $favoriteuserdao->get_user_by_user_id($group_id, $user_login_id);
				foreach ($favorite as $user) {
					$user->name = user_name($user, $language);
				}
				$this->data['history'] = $favorite;
			}
			$this->parse('select.tpl', 'post/get_invite_list');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループへ追加可能なユーザー名の一覧を取得する。
	 */
	public function get_invite_name() {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user_id = $this->uri->segment(3);
			$arr_user_id = array_map('intval',explode("_", $user_id));
			$list = array();
			if ($user_id) {
				$userdao = new UserDao();
				$users = $userdao->get_user_by_id_in($arr_user_id);
				$language = $this->data['language'];
				foreach($users as $user) {
					$user_info = array();
					$user_info['id'] = $user->id;
					$user_info['name'] = user_name($user, $language);
					$user_info['organization'] = $user->organization==NULL?'':$user->organization;
					$user_info['position'] = $user->position== NULL?'':$user->position;
					$user_info['category_id'] = $user->category_id;
					array_push($list, $user_info);
				}
			}

			echo json_encode($list);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ-利用者情報を取得する。
	 */
	public function get_user_group() {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			$group_id = $this->input->get('group_id');
			$results = array();
			$groupuserdao = new GroupUserDao();
			$groups = $groupuserdao->get_member_in_group($group_id, 2);
			$language = $this->data['language'];
			foreach ($groups as $group) {
				$result = array();
				$result['user_id']		= $group->user_id;
				$result['name'] = user_name($group, $language);
				$result['position']	= isset($group->position)?$group->position : '';
				$result['organization']	= isset($group->organization)?$group->organization: '';
				$result['status']		= $group->status;
				$result['category_id']	= $group->category_id;
				$results[] = $result;
			}

			echo json_encode($results);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザーのグループ入会を承認し、ユーザーをグループ入会に招待し、グループからユーザーを削除する。
	 */
	public function update_user() {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			
			$group_id = $this->input->post('group_id');
			$user_approve = $this->input->post('user_approve');
			$user_delete = $this->input->post('user_delete');
			$user_insert = $this->input->post('user_insert');
			$arr_user = array();
			$arr_user_approve = array();
			$arr_user_delete = array();
			$arr_user_insert = array();
			if ($user_approve != '') {
				$arr_user_approve = explode(',', $user_approve);
				foreach($arr_user_approve as $user_id) {
					$user_info['id'] = $user_id;
					$user_info['flag'] = STATUS_GROUP_USER_ENABLE;
					array_push($arr_user, $user_info);
				}
			}
			if ($user_delete != '') {
				$arr_user_delete = explode(',', $user_delete);
				foreach($arr_user_delete as $user_id) {
					$user_info['id'] = $user_id;
					$user_info['flag'] = STATUS_GROUP_USER_DISABLE;
					array_push($arr_user, $user_info);
				}
			}
			
			$groupdao = new GroupDao();
			$groupname = $groupdao->get_group_by_id($group_id)->name;
			$user = $this->userauth->getUser();
			$name = array();
 			$name['ja'] = user_name($user, 'japanese');
 			$name['en'] = user_name($user, 'english');
			
			$notice['link'] = 'group/';
			$notice[''] = $this->data[''];
			$notice['approve'] = json_encode(array('L-F-0060-I', array($groupname)));
			
			$groupuserdao = new GroupUserDao(MASTER);
			if ($user_insert != '') {
				$arr_user_insert = array_unique(explode(',', $user_insert));
				$owner_id = $user->id;
				$notice['insert'] = json_encode(array('L-F-0044-I', array($groupname, $name)));
				$result = $groupuserdao->update_user_in_group($arr_user, $group_id, $notice, $owner_id, $arr_user_insert);
			} else {
				$result = $groupuserdao->update_user_in_group($arr_user, $group_id, $notice);
			}
			
			$results = array();
			$results['flag'] = FALSE;
			if ($result == TRUE) {
				$userdao = new UserDao();
				$name_delete = "";
				$name_approve = "";
				$name_insert = "";
				$language = $this->data['language'];
				if (isset($arr_user_approve) && count($arr_user_approve) != 0) {
					$user_approve_info = $userdao->get_user_by_id_in($arr_user_approve);
					foreach ($user_approve_info as $user) {
						if ($name_approve == "") {
							$name_approve = $name_approve.user_name($user, $language).$this->lang->line('label_mr');
						} else {
							$name_approve = $name_approve.$this->lang->line('label_comma').user_name($user, $language).$this->lang->line('label_mr');
						}
					}
					$name_approve = sprintf($this->lang->line('L-F-0053-I'), $name_approve);
				}
				$results['name_approve'] = $name_approve;

				if (isset($arr_user_insert) && count($arr_user_insert) != 0) {
					$user_insert_info = $userdao->get_user_by_id_in($arr_user_insert);
					foreach ($user_insert_info as $user) {
						if ($name_insert == "") {
							$name_insert = $name_insert.user_name($user, $language).$this->lang->line('label_mr');
						} else {
							$name_insert = $name_insert.$this->lang->line('label_comma').user_name($user, $language).$this->lang->line('label_mr');
						}
					}
					$name_insert = sprintf($this->lang->line('L-F-0051-I'), $name_insert);
				}
				$results['name_insert'] = $name_insert;
				
				if (isset($arr_user_delete) && count($arr_user_delete) != 0) {
					$user_delete_info = $userdao->get_user_by_id_in($arr_user_delete);
					foreach ($user_delete_info as $user) {
						if ($name_delete == "") {
							$name_delete = $name_delete.user_name($user, $language).$this->lang->line('label_mr');
						} else {
							$name_delete = $name_delete.$this->lang->line('label_comma').user_name($user, $language).$this->lang->line('label_mr');
						}
					}
					$name_delete = sprintf($this->lang->line('L-F-0052-I'), $name_delete);
				}
				$results['name_delete'] = $name_delete;
				$results['flag'] = TRUE;
			} else {
				$count_member = $groupuserdao->get_member_in_group($group_id, 1);
				if (($count_member->result_count() + count($arr_user_approve) - count($arr_user_delete)) >= MAX_USER_IN_GROUP) {
					$results['message'] = sprintf($this->lang->line('L-F-0047-E'), MAX_USER_IN_GROUP);
				} else {
					$results['message'] = $this->lang->line("L-F-0006-E");
				}
			}
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($results);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザーがグループ参加を受諾する又は、グループオーナーがユーザーのグループ入会を受諾する時の処理。
	 */
	public function join_group() {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			
			$user_login_id = $this->userauth->getUser()->id;
			$group_id = $this->input->post('group_id');
			$type = $this->input->post('type');
			
			$groupuserdao = new GroupUserDao(MASTER);
			$count_member = $groupuserdao->get_member_in_group($group_id, 1);
			if ($count_member->result_count() >= MAX_USER_IN_GROUP) {
				$message = $this->lang->line("L-F-0055-E");
			} else {
				$arr_user = array();
				$groupdao = new GroupDao();
				$group_info = $groupdao->get_group_by_id($group_id);
				$owner_id = $group_info->user_id;
				
				$user = $this->userauth->getUser();
				$name = array();
				$name['ja'] = user_name($user, 'japanese');
				$name['en'] = user_name($user, 'english');
				$notice['link'] = 'group/';
				$notice[''] = $this->data[''];
				$groupname = $group_info->name;
				
				if ($type == $this::STATUS_USER_JOIN_GROUP) {
					array_push($arr_user, $user_login_id);
					$notice['request'] = json_encode(array('L-F-0069-I', array($name, $groupname)));
					$result = $groupuserdao->insert_user_group($group_id, $owner_id, $arr_user, $notice, STATUS_GROUP_USER_OWNER_APPROVE);
					if ($result == TRUE) {
						$message = $this->lang->line("L-F-0049-I");
						
						$from = MAIL_FROM;
						$to =array($group_info->email);
						$lang = $group_info->language;
						$this->data['link'] = $this->data[''].'group/'.$group_id;
						$this->data['user_name'] = user_name($user, $lang);
						$this->data['group_name'] = $groupname;
						list($subject, $message_mail)	= $this->get_mail($lang.'/email_request_to_group_owner.tpl');
						$this->send_mail($to, $subject, $message_mail ,$from);
					} else {
						$message = $this->lang->line("L-F-0004-E");
					}
				} else if ($type == STATUS_GROUP_USER_PENDING_INVITATION) {
					$user_info['id'] = $user_login_id;
					$user_info['flag'] = STATUS_GROUP_USER_ENABLE;
					array_push($arr_user, $user_info);
					$notice['accept'] = json_encode(array('L-F-0059-I', array($name, $groupname)));
					
					$result = $groupuserdao->update_user_in_group($arr_user , $group_id, $notice, $owner_id);
					if ($result == TRUE) {
						$message = $this->lang->line("L-F-0048-I");
					} else {
						$message = $this->lang->line("L-F-0006-E");
					}
				}
				$this->clear_csrf(); // 使った CSRFトークン をクリア
			}
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	function get_member($group_id){
		$group_members = array();
		$group_members_invite = array();
		$groupuserdao = new GroupUserDao();
		$group_users = $groupuserdao->get_member_in_group($group_id, 3);
		$language = $this->data['language'];
		foreach($group_users as $group_user){
			$member = array();
			$member['id'] = $group_user->user_id;
			$member['name'] = user_name($group_user, $language);
			$member['organization'] = $group_user->organization == NULL? "" : $group_user->organization;
			$member['position'] = $group_user->position == NULL? "" : $group_user->position;
			$member['category_id'] = $group_user->category_id;
			$member['status'] = $group_user->status ;
			if ($group_user->status == STATUS_GROUP_USER_ENABLE ||
					$group_user->status == STATUS_GROUP_USER_OWNER_APPROVE) {
				$group_members[] = $member;
			} else if($group_user->status == STATUS_GROUP_USER_PENDING_INVITATION) {
				$group_members_invite[] = $member;
			}
		}
		if(!isset($this->data['master_group_joined'])){
			$this->data['master_group_joined'] = false;
		}
		$data['flag_edit'] = TRUE;
		$data['master_group_member'] = $group_members;
		$data['master_group_member_invite'] = $group_members_invite;
		echo json_encode($data);
	}
}
