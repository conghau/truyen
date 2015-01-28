<?php
/**
 * @name グループ管理のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class GroupAdmin extends MY_Controller {

	private $user_id;
	var $column_table = array('id','name','user_id','summary','public_status','created_at','updated_at','status');

	public function __construct() {
		parent::__construct();
		$this->load->config('forminfo');
		$this->load->library('excel');

		$lstPublicStatus = config_item('forminfo')['common']['group']['group_public_status'];
		$this->data['lst_public_status'] = $lstPublicStatus;
		
		$lstStatus = config_item('forminfo')['common']['group']['group_status_types'];
		$this->data['status_types'] = $lstStatus;

		if(!isset($_SESSION)) {
			session_start();
		}
		$this->data['controller'] = 'group';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}
	
	/**
	 * グループ一覧画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case = 'search') {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			} else if ($this->uri->segment(4) === 'user') {
				$case = "user";
			}

			$groups = new GroupDao();
			$condition = array();

			// 検索条件情報を設定
			if ($case === 'search') {
				if (!$this->input->post('btn_search') && $this->input->post('id')!= '') {
					$conditionInfo = $_SESSION['condition'];
					$condition['user_id'] = isset($conditionInfo['user_id']) ? $conditionInfo['user_id']: null;
					$this->user_id = $condition['user_id'];
				}
				// 検索機能の場合、画面からの情報を取得する。
				$condition['id']	= $this->input->post('id');
				$condition['name']	= $this->input->post('name');
				$condition['last_name_ja']	= $this->input->post('last_name_ja');
				$condition['first_name_ja']	= $this->input->post('first_name_ja');
				$condition['public_status'] = $this->input->post('status');
				$condition['date_from'] = $this->input->post('date_from');
				$condition['date_to'] = $this->input->post('date_to');
				$condition['per_page'] = $this->input->post('per_page');
				
				if ($condition['date_from'] != '' || $condition['date_to'] != '' || $condition['id'] != '') {
					$this->setup_validation_rules('group/search');
					$validation = $this->form_validation->run($this);
					if (!$validation){
						$this->data['is_has_data'] = FALSE;
						$this->set_value_item($case);
						header_remove("Cache-Control");
						$this->parse('group_list.tpl', 'groupadmin/index');
						return;
					}
				}
				
				$_SESSION['condition'] = $condition;
			} else if ($case === 'paginate') {
				$conditionInfo = $_SESSION['condition'];
				$condition['id'] = isset($conditionInfo['id']) ? $conditionInfo['id']: null;
				$condition['name'] = isset($conditionInfo['name']) ? $conditionInfo['name']: null;
				$condition['last_name_ja'] = isset($conditionInfo['last_name_ja']) ? $conditionInfo['last_name_ja']: null;
				$condition['first_name_ja'] = isset($conditionInfo['first_name_ja']) ? $conditionInfo['first_name_ja']: null;
				$condition['public_status'] = isset($conditionInfo['public_status']) ? $conditionInfo['public_status']: null;
				$condition['date_from'] = isset($conditionInfo['date_from']) ? $conditionInfo['date_from']: null;
				$condition['date_to'] = isset($conditionInfo['date_to']) ? $conditionInfo['date_to']: null;
				$condition['per_page'] = isset($conditionInfo['per_page']) ? $conditionInfo['per_page']: null;
				$condition['user_id'] = isset($conditionInfo['user_id']) ? $conditionInfo['user_id']: null;
			} else if ($case === 'user') {
				$condition['user_id'] = $this->uri->segment(3);
				$condition['per_page'] = null;
				
				$dataSession = array('user_id' => $this->uri->segment(3));
				$_SESSION['condition'] = $dataSession;
			}
			
			$this->data['is_has_data'] = $groups->is_has_data();
			$url = $this->data[''] . "admin_tools/group/paginate";
			$uri_segment = 4;
			// 改ページ作成を実行
			$total_records = $groups->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition,$url,$uri_segment);
			// 項目値設定
			$this->set_value_item($case);
			$result = $groups->search($condition, $limit);
			// 一覧表示フォーマットを実行
			$this->display_list($result);

			// 検索結果を保存
			$this->data['list_groups'] = $result;
			header_remove("Cache-Control");
			$this->parse('group_list.tpl', 'groupadmin/index');

		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * グループ新規登録画面の表示処理。
	 */
	public function create() {
		try{
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				$this->load_groups_info(0);
			} else {
				$group_info = $_SESSION['group_info'];
				$this->data['name'] 		= $group_info['name'];
				$this->data['summary'] 		= $group_info['summary'];
				$this->data['group_users_add'] 	= $group_info['group_users_add'];
				$this->data['public_status']= $group_info['public_status'];
				$this->data['group_owner']	= $group_info['group_owner'];
				$this->data['status_type']	= $group_info['status_type'];
			}
			$this->parse('group_create.tpl', 'groupadmin/create');
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
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				redirect($this->data[''].'admin_tools/group/create');
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
			$lstAddUser = $this->input->post('group_users_add');
			$users_add = array();
			if (strlen($lstAddUser) > 0) {
				$lstUserInfo = array();
				$users_add = array_unique(preg_split('/[\s,]+/', $lstAddUser));
				$owner = array($this->input->post('group_owner'));
				$users_add = array_diff($users_add, $owner);
				
				foreach ($users_add as $user_add) {
					if ($user_add != '') {
						$user = new UserDao();
						$user_info = $user->get_by_id($user_add);
						if ($user_info->id === null or $this->form_validation->numeric($user_add) == FALSE) {
							$this->data['error'] = $this->lang->line('L-A-0018-E');
							$flag_error = true;
							break;
						} else {
							array_push($lstUserInfo, $user_info);
						}
					}
				}
				$this->data['lst_user_add_info'] = $lstUserInfo;
			}
			if($flag_error) {
				$this->load_groups_info(1);
				$this->parse('group_create.tpl','groupadmin/create');
			} else {
				// groupsの配列の初期化
				$groups = array();
				$groups['name'] 			= $this->input->post('name');
				$groups['summary'] 			= $this->input->post('summary');

				$lstPublicStatus = $this->data['lst_public_status'];
				$public_status = $this->input->post('public_status');
				foreach ($lstPublicStatus as $item) {
					if ($item['id'] == $public_status) {
						$groups['lbl_public_status'] = $item['label'];
					}
				}
				$groups['public_status'] 	= $public_status;
				$groups['group_owner'] 		= $this->input->post('group_owner');

				$lstStatus = $this->data['status_types'];
				$status_type	= $this->input->post('status_type');
				foreach ($lstStatus as $item) {
					if ($item['id'] == $status_type) {
						$groups['lbl_status_type'] = $item['label'];
					}
				}
				$groups['group_users_add'] 	= $this->input->post('group_users_add');
				$groups['status_type'] 	= $status_type;

				$_SESSION['group_info'] = $groups;
				$this->data['group_info']= $groups;
				$this->parse('group_confirm_create.tpl','groupadmin/confirm_create');
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
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				redirect($this->data[''].'admin_tools/group/create');
				return;
			}
			
			if (!isset($_SESSION['group_info'])) {
				set_status_header(417);
				exit;
			}
		
			$sessionGroupInfo = $_SESSION['group_info'];
			$group_info = array();
			$group_info['name'] 			= $sessionGroupInfo['name'];
			$group_info['summary'] 			= $sessionGroupInfo['summary'];
			$group_info['public_status']	= $sessionGroupInfo['public_status'];
			$group_info['group_users_add']	= $sessionGroupInfo['group_users_add'];
			$group_info['user_id']			= $sessionGroupInfo['group_owner'];

			$group_info['status']			= $sessionGroupInfo['status_type'];
			$updateError = false;

			$users_add = array();
			if (count($sessionGroupInfo['group_users_add']) > 0) {
				$users_add = array_unique(preg_split('/[\s,]+/', $sessionGroupInfo['group_users_add']));
			}
			$users_add = array_diff($users_add, array($group_info['user_id']));
			$groups = new GroupDao(MASTER);
			$userdao = new UserDao();
			$owner_info = $userdao->get_by_id($group_info['user_id']);
			$name = array();
			$name['ja'] = user_name($owner_info, 'japanese');
			$name['en'] = user_name($owner_info, 'english');;
			$notice['link'] = 'group/';
			$notice[''] = $this->data[''];
			$notice['insert'] = json_encode(array('L-A-0044-I', array($group_info['name'], $name)));
			$result = $groups->insert_group($group_info, $users_add, $notice);
			
			if (!$result) {
				$info['status'] = FALSE;
				$msgID = 'L-A-0004-E';
			} else {
				$info['status'] = TRUE;
				$msgID = 'L-A-0003-I';
				unset($_SESSION['group_info']);
			}
			$info['message'] = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode(array($info));
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * グループ編集画面の表示処理
	 *  @param $id グループID
	 */
	public function edit($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				$_SESSION['group_id'] = $id;
				$group = new GroupDao();
				if ($group->get_group_by_id($id)->id === null) {
					redirect($this->data[''].'admin_tools/group');
				}
				$this->load_groups_info(0);
				$this->parse('group_edit.tpl', 'groupadmin/edit');
				return;
			} else {
				$group_info = $_SESSION['group_info'];
				$this->data['group_id'] 	= $group_info['group_id'];
				$this->data['name'] 		= $group_info['name'];
				$this->data['summary'] 		= $group_info['summary'];
				$this->data['public_status']= $group_info['public_status'];
				$this->data['group_users_add']= $group_info['group_users_add'];
				$this->data['group_owner']= $group_info['group_owner'];
				$this->data['created_at']= $group_info['created_at'];
				$this->data['status_type']	= $group_info['status_type'];
				if (isset($_SESSION['lst_user_info'])) {
					$this->data['lst_user_info'] = unserialize($_SESSION['lst_user_info']);
				}
				$this->parse('group_edit.tpl', 'groupadmin/edit');
				return;
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ編集確認画面の表示処理
	 *  @param $id グループID
	 */
	public function confirm_edit() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				redirect($this->data[''].'admin_tools/group');
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
			$group_id = $this->input->post('group_id');
			$lstAddUser = $this->input->post('group_users_add');
			
			if (isset($_SESSION['lst_user_info'])) {
				$this->data['lst_user_info'] = unserialize($_SESSION['lst_user_info']);
			}

			$users_add = array();
			if (strlen($lstAddUser) > 0) {
				$groupuserdao = new GroupUserDao();
				$member = $groupuserdao->get_member_in_group($group_id, 1);
				if ($member->result_count() >= MAX_USER_IN_GROUP) {
					$this->data['error'] = sprintf($this->lang->line('L-A-0047-E'), MAX_USER_IN_GROUP);
					$flag_error = true;
				} else {
					$users_add = array_unique(preg_split('/[\s,]+/', $lstAddUser));
					$lstUserInfo = array();
					$owner = array($this->input->post('group_owner'));
					$users_add = array_diff($users_add, $owner);

					foreach ($users_add as $user_add) {
						if ($user_add != '') {
							$user = new UserDao();
							if ($user->get_by_id($user_add)->id === null or $this->form_validation->numeric($user_add) == FALSE) {
								$this->data['error'] = $this->lang->line('L-A-0018-E');
								$flag_error = true;
								break;
							} else if ($groupuserdao->chk_unq_user_group($group_id, $user_add) > 0) {
								$this->data['error'] = $this->lang->line('L-A-0019-E');
								$flag_error = true;
								break;
							} else {
								array_push($lstUserInfo, $user->get_by_id($user_add));
							}
						}
					}
					$this->data['lst_user_add_info'] = $lstUserInfo;
				}
			}
			
			if($flag_error) {
				$this->load_groups_info(1);
				$this->parse('group_edit.tpl','groupadmin/edit');
			} else {
				// groupsの配列の初期化
				$groups = array();
				$groups['group_id'] 		= $group_id;
				$groups['name'] 			= $this->input->post('name');
				$groups['summary'] 			= $this->input->post('summary');
				$groups['group_owner'] 		= $this->input->post('group_owner');
				$groups['created_at'] 		= $this->input->post('created_at');
				$groups['group_users_add'] 	= $this->input->post('group_users_add');

				$lstPublicStatus = $this->data['lst_public_status'];
				$public_status = $this->input->post('public_status');
				foreach ($lstPublicStatus as $item) {
					if ($item['id'] == $public_status) {
						$groups['lbl_public_status'] = $item['label'];
					}
				}
				$groups['public_status'] 	= $public_status;
					
				$lstStatus = $this->data['status_types'];
				$status_type	= $this->input->post('status_type');
				foreach ($lstStatus as $item) {
					if ($item['id'] == $status_type) {
						$groups['lbl_status_type'] = $item['label'];
					}
				}
				$groups['status_type'] 	= $status_type;
		
				$_SESSION['group_info'] = $groups;
				
				$this->data['group_info']= $groups;
				$this->parse('group_confirm_edit.tpl','groupadmin/confirm_edit');
			}
		} catch(Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * グループ編集完了画面の表示処理
	 * @param $id グループID
	 */
	public function update() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				redirect($this->data[''].'admin_tools/group');
				return;
			}
			
			if (FALSE === $_SESSION['group_info']) {
				set_status_header(417);
				exit;
			}
		
			$sessionGroupInfo = $_SESSION['group_info'];
			$group_info = array();
			$group_id = $sessionGroupInfo['group_id'];
			$group_info['name'] 			= $sessionGroupInfo['name'];
			$group_info['summary'] 			= $sessionGroupInfo['summary'];
			$group_info['public_status']	= $sessionGroupInfo['public_status'];
			$group_info['status']			= $sessionGroupInfo['status_type'];
			$group_info['user_id']			= $sessionGroupInfo['group_owner'];

			$groups = new GroupDao(MASTER);
			$notice = array();
			$users_add = array();
			if (count($sessionGroupInfo['group_users_add']) > 0) {
				$users_add = array_unique(preg_split('/[\s,]+/', $sessionGroupInfo['group_users_add']));
				$users_add = array_diff($users_add, array($group_info['user_id']));
				$userdao = new UserDao();
				$owner_info = $userdao->get_by_id($group_info['user_id']);
				$name = array();
				$name['ja'] = user_name($owner_info, 'japanese');
				$name['en'] = user_name($owner_info, 'english');
				$notice['insert'] = json_encode(array('L-A-0044-I', array($group_info['name'], $name)));
				$notice['link'] = 'group/';
				$notice[''] = $this->data[''];
			}
			$result = $groups->update_group($group_id, $group_info, $users_add, $notice);

			if (!$result) {
				$info['status'] = FALSE;
				$msgID = 'L-A-0006-E';
			} else {
				$info['status'] = TRUE;
				$msgID = 'L-A-0005-I';
				unset($_SESSION['group_info']);
			}
			$info['message'] = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode(array($info));
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * ユーザ削除完了画面の表示処理
	 * @param $id グループID
	 */
	public function delete() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['group_info'])) {
					unset($_SESSION['group_info']);
				}
				redirect($this->data[''].'admin_tools/group');
				return;
			}
			
			$storeError = false;
			$msgID = "";
			
			$group = new GroupDao(MASTER);
			$result = $group->delete_group($this->uri->segment(3));
			
			if (!$result) {
				$info['status'] = FALSE;
				$msgID = 'L-A-0002-E';
			} else {
				$info['status'] = TRUE;
				$msgID = 'L-A-0001-I';
				unset($_SESSION['group_info']);
			}
			$info['message'] = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode(array($info));
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ全体のエクスポート処理
	 */
	public function export_all() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$encoding = $this->input->get('encoding');
			$output_file_name = $this->lang->line('label_group_export_all_file_name');
			$output_file_name = $output_file_name.'.'.OUTPUT_FILE_TYPE_TSV;
	
			$group = new GroupDao();
			$result = $group->get_all_group();
	
			$this->process_export($result, $output_file_name, $this->column_table, 'label_group_', $encoding);
	
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 検索結果のエクスポート処理
	 */
	public function export_search_result() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
				
			if(!isset($_SESSION['condition'])) {
				return;
			}
			$encoding = $this->input->get('encoding');
			$output_file_name = $this->lang->line('label_group_export_search_file_name');
			$output_file_name = $output_file_name . '.' . OUTPUT_FILE_TYPE_TSV;
			$conditionSearch = $_SESSION['condition'];
	
			$group = new GroupDao();
			$result_search = $group->search($conditionSearch, array($group->count_by_condition($conditionSearch), NULL));
	
			$this->process_export($result_search, $output_file_name, $this->column_table, 'label_group_', $encoding);
	
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 *グループ情報をロードする。
	 * @param $mode 処理を分類するパラメータ
	 */
	private function load_groups_info($mode) {
		// 初期表示の場合
		if ($mode == 0) {
			if(isset($_SESSION['group_id'])) {
				// データベースからのページ情報取得
				$group_id = $_SESSION['group_id'];
				$groupdao = new GroupDao();
				$groups = $groupdao->get_group_by_id($group_id);
	
				$this->data['group_id']		= $group_id;
				$this->data['name']			= $groups->name;
				$this->data['summary']		= $groups->summary;
				$this->data['public_status']= $groups->public_status;
				$this->data['created_at']	= date("Y-m-d", strtotime(str_replace('-','/', $groups->created_at)));
				$this->data['group_owner']	= $groups->user_id;
				$this->data['status_type']	= $groups->status;
				$this->data['group_users_add']= "";

				$groupuserdao = new GroupUserDao();
				$lstGroupUser = $groupuserdao->get_member_in_group($group_id, 3);
				$this->data['lst_user_info'] = $lstGroupUser;
				if (count($lstGroupUser) > 0) {
					$_SESSION['lst_user_info'] = serialize($lstGroupUser);
				}

				unset($_SESSION['group_id']);
			} else {
				$this->data['name'] 			= "";
				$this->data['summary'] 			= "";
				$this->data['group_users_add']	= "";
				$this->data['group_owner']		= "";
			}
		} else {
			// リクエストからのページ情報取得
			$this->data['group_id'] 	= $this->input->post('group_id');
			$this->data['name'] 		= $this->input->post('name');
			$this->data['summary'] 		= $this->input->post('summary');
			$this->data['public_status']= $this->input->post('public_status');
			$this->data['group_users_add']= $this->input->post('group_users_add');
			$this->data['group_owner']	= $this->input->post('group_owner');
			$this->data['created_at']	= $this->input->post('created_at');
			$this->data['status_type']	= $this->input->post('status_type');
			if (isset($_SESSION['lst_user_info'])) {
				$this->data['lst_user_info'] = unserialize($_SESSION['lst_user_info']);
			}
		}
	}

	/**
	 * 表示フォーマットメソッド
	 * @param $listGroups 検索結果一覧
	 */
	private function display_list($listGroups) {
		foreach ($listGroups as $groupDetail) {
			// テンプレートフォル種別
			$statusLabel = "";
			$lstStatus = $this->data['status_types'];
			foreach ($lstStatus as $status) {
				if ($groupDetail->status == $status['id']) {
					$statusLabel = $status['label'];
				}
			}
			$groupDetail->status = $statusLabel;
		}
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item($case) {
		if ($case === 'search') {
			// 検索の場合
			$this->data['id'] 			= $this->input->post('id');
			$this->data['name'] 		= $this->input->post('name');
			$this->data['last_name_ja'] = $this->input->post('last_name_ja');
			$this->data['first_name_ja']= $this->input->post('first_name_ja');
			$this->data['status']		= $this->input->post('status');
			$this->data['date_from'] 	= $this->input->post('date_from');
			$this->data['date_to']		= $this->input->post('date_to');
			$this->data['per_page']		= $this->input->post('per_page');
		} else if ($case === 'paginate') {
			$condition = $_SESSION['condition'];
			$this->data['id'] 			= isset($condition['id']) ? $condition['id']: null;
			$this->data['name'] 		= isset($condition['name']) ? $condition['name']: null;
			$this->data['last_name_ja'] = isset($condition['last_name_ja']) ? $condition['last_name_ja']: null;
			$this->data['first_name_ja']= isset($condition['first_name_ja']) ? $condition['first_name_ja']: null;
			$this->data['status']		= isset($condition['public_status']) ? $condition['public_status']: null;
			$this->data['date_from'] 	= isset($condition['date_from']) ? $condition['date_from']: null;
			$this->data['date_to']		= isset($condition['date_to']) ? $condition['date_to']: null;
			$this->data['per_page']		= isset($condition['per_page']) ? $condition['per_page']: null;
		}
	}

	/**
	 * ユーザーIDでユーザー情報を取得し、ビューで表示する。
	 * @param array $listUser
	 */
	private function get_user_info ($listUser) {
		$users_add = preg_split('#\s+#', $listUser);
		$lstUserInfo = array();
		foreach ($users_add as $user_add) {
			$user = new UserDao();
			array_push($lstUserInfo, $user->get_by_id($user_add));
		}
		$this->data['lst_user_add_info'] = $lstUserInfo;
	}
}
