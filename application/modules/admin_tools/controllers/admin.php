<?php
/**
 * @name 管理者管理のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Admin extends MY_Controller {
	private $column_table_admin =  array('id','email','login_id','password','last_name_ja','first_name_ja','last_name','first_name'
								,'gender','birthday','organization','department','position','phone_number','info','role','language','status'
								,'joined_at','leaved_at','expired_at','created_at','updated_at','deleted_at'
								);
	private $status_delete = "-1";
	private $first_row = 2;

	public function __construct() {
		parent::__construct();
		if(!isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');
		$listgendertypes = config_item('forminfo')['common']['profile']['gender_types'];
		$liststatustypes = config_item('forminfo')['common']['status_types'];
		$listlanguages	 = config_item('forminfo')['common']['language'];
		$this->data['gender_types'] = $listgendertypes ;
		$this->data['status_types'] = $liststatustypes ;
		$this->data['languages'] 	= $listlanguages;
		
		$this->data['controller'] = "admin";
		$this->setup_form_info($this->data['controller'].'/'.$this->data['language']);
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * 管理者一覧画面の表示処理
	 * @param string $case
	 */
	public function index($case = "search") {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() === FALSE) {
				$msgID = 'L-A-0027-E';
				$this->data['error_access_denied'] = $this->lang->line($msgID);
				$this->parse('admin_edit.tpl','admin/index');
				return;
			}
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			
			$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender','per_page');
			$condition = array();
			if ($case === 'search') {
				foreach ($targets as $key) {
					$condition[$key] = $this->input->post($key);
				}
				if(trim($condition['id']) != '') {
					$this->setup_validation_rules('user/search_code');
					$validation = $this->form_validation->run($this);
					if (!$validation){
						$this->setValueItem($case);
						header_remove("Cache-Control");
						$this->parse('admin_list.tpl', 'admin/index');
						return;
					}
				}
				// セッションに検索条件を保存するを実行
				$_SESSION['condition'] = $condition;
			} else {
				$condition = $_SESSION['condition'];
			}
			
			$admindao = new AdminDao();
			$this->data['is_has_data'] = $admindao->is_has_data();
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/admin/paginate";
			$uri_segment = 4;
			$total_records = $admindao->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $admindao->search($condition,$limit);
			
			// 項目値設定
			$this->setValueItem($case);
			$this->displayList($result);
			$this->data['list_admins'] = $result;
			header_remove("Cache-Control");
			$this->parse('admin_list.tpl', 'admin/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		} 	
	}
	
	private function displayList($listAdmin) {
		$listgendertypes = $this->data['gender_types'];
		$liststatustypes = $this->data['status_types'];
		foreach ($listAdmin as $AdminDetail) {
			$genderLabel = "";
			$statusLabel = "";
			foreach ($listgendertypes as $gender_types) {
				if ($AdminDetail->gender == $gender_types['id']) {
				$genderLabel = $gender_types['label'];
			}
			foreach ($liststatustypes as $status_types) {
				if ($AdminDetail->status == $status_types['id']) {
					$statusLabel = $status_types['label'];
				}
			}
		}
		$AdminDetail->gender = $genderLabel;
		$AdminDetail->status = $statusLabel;
		}
	}
	
	/**
	 * 管理者新規登録画面の表示処理。
	 */
	public function create(){
		try {
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				$msgID = 'L-A-0027-E';
				$this->data['error_access_denied'] = $this->lang->line($msgID);
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_info'])) {
					unset($_SESSION['admin_info']);
				}
				$this->data['admin_language'] 	= $this->data['language'];
				$this->parse('admin_create.tpl', 'admin/create');
				return;
			} else {// edit
				if (!isset($_SESSION['admin_info'])) {
					redirect($this->data[''].'admin_tools/admin/create');
				}
				$admin_info = $_SESSION['admin_info'];
				$this->data['login_id'] 		= $admin_info['login_id'];
				$this->data['email'] 			= $admin_info['email'];
				$this->data['first_name_ja'] 	= $admin_info['first_name_ja'];
				$this->data['last_name_ja'] 	= $admin_info['last_name_ja'];
				$this->data['first_name'] 		= $admin_info['first_name'];
				$this->data['last_name'] 		= $admin_info['last_name'];
				$this->data['gender'] 			= $admin_info['gender'];
				$this->data['birthday'] 		= $admin_info['birthday'];
				$this->data['phone_number'] 	= $admin_info['phone_number'];
				$this->data['organization'] 	= $admin_info['organization'];
				$this->data['department'] 		= $admin_info['department'];
				$this->data['position'] 		= $admin_info['position'];
				$this->data['info'] 			= $admin_info['info'];
				$this->data['admin_language'] 	= $admin_info['language'];
				$this->data['status'] 			= $admin_info['status'];
				$this->parse('admin_create.tpl', 'admin/create');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	} 
	
	/**
	 * 管理者新規登録確認画面の表示処理。
	 */
	public function confirm_create() {
		try {	
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				$msgID = 'L-A-0027-E';
				$this->data['error_access_denied'] = $this->lang->line($msgID);
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_info'])) {
					unset($_SESSION['admin_info']);
				}
				redirect($this->data[''].'admin_tools/admin/create');
			}

			$this->setup_validation_rules('admin/create');
			$validation = $this->form_validation->run($this);
			if ( $validation === TRUE) {
				$admin_info = array();
				$admin_info['login_id'] = $this->input->post('login_id');
				$admin_info['email'] = $this->input->post('email');
				$admin_info['password'] = $this->input->post('password');
				$admin_info['first_name_ja'] = $this->input->post('first_name_ja');
				$admin_info['last_name_ja'] = $this->input->post('last_name_ja');
				$admin_info['first_name'] = $this->input->post('first_name');
				$admin_info['last_name'] = $this->input->post('last_name');
				$admin_info['gender'] = $this->input->post('gender');
				$admin_info['birthday'] = $this->input->post('birthday');
				$admin_info['phone_number'] = $this->input->post('phone_number');
				$admin_info['organization'] = $this->input->post('organization');
				$admin_info['department'] = $this->input->post('department');
				$admin_info['position'] = $this->input->post('position');
				$admin_info['info'] = $this->input->post('info');
				$admin_info['language'] = $this->input->post('language');
				$admin_info['status'] = $this->input->post('status');
				
				$_SESSION['admin_info'] = $admin_info;
				$this->data['admin_info']= $admin_info;
				$this->parse('admin_create_confirm.tpl','admin/confirm_create');
			} else {
				$this->load_info_admin();
				$this->parse('admin_create.tpl', 'admin/create');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 管理者新規登録完了画面の表示処理
	 */
	public function store() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_info'])) {
					unset($_SESSION['admin_info']);
				}
				redirect($this->data[''].'admin_tools/admin/create');
			}
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if ($this->form_validation->check_role() == FALSE) {
				set_status_header(417);
				exit;
			}
			if ( !isset($_SESSION['admin_info'])) {
				set_status_header(417);
				exit;
			}
			
			$admin_info = $_SESSION['admin_info'];
			$admin_info['password'] = hash('sha256',$admin_info['password']);
			$admin_info['role'] = ROLE_ADMIN;

			$msgID = "";
			$info = array();
			$admindao = new AdminDao(MASTER);
			$result = $admindao->insert($admin_info);
				
			if (!$result) {
				$msgID = 'L-A-0004-E';
			} else {
				$msgID = 'L-A-0003-I';
			}
			unset($_SESSION['admin_info']);
			$info['message'] = $this->lang->line($msgID);
			$this->clear_csrf();
			echo json_encode($info);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 管理者編集画面の表示処理
	 *  @param integer $id
	 */
	public function edit($id) {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			$this->data['current_id_login'] = $this->data['admin']->id;
			if ($this->data['admin']->role == ROLE_ADMIN) {
				if ($id != $this->data['current_id_login']) {
					$msgID = 'L-A-0027-E';
					$this->data['error_access_denied'] = $this->lang->line($msgID);
					$this->parse('admin_edit.tpl','admin/edit');
					return;
				}
			}
			
			$this->data['current_id_login'] = $this->adminauth->getAdmin()->id;
			$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0023-Q');
			
			$admindao = new AdminDao();
			$result = $admindao->get_by_id($id);
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if ( $result->id === NULL ) {
					redirect($this->data[''].'admin_tools/admin');
				}
				$this->data['admin_info_edit'] = $this->select_data($result);
				$_SESSION['admin_id'] = $id;
				$_SESSION['login_id'] = $result->login_id;
				$this->parse('admin_edit.tpl','admin/edit');
				return;
			} else {
				$admin_info_edit = $_SESSION['admin_info_edit'];
				$this->data['admin_info_edit'] = $admin_info_edit;
				$this->parse('admin_edit.tpl','admin/edit');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 管理者編集確認画面の表示処理
	 *  @param integer $id
	 */
	public function confirm_edit($id) {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			$this->data['current_id_login'] = $this->data['admin']->id;
			if ($this->data['admin']->role == ROLE_ADMIN) {
				if ($id != $this->data['current_id_login']) {
					$msgID = 'L-A-0027-E';
					$this->data['error_access_denied'] = $this->lang->line($msgID);
					$this->parse('admin_edit.tpl','admin/edit');
					return;
				}
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_info_edit'])) {
					unset($_SESSION['admin_info_edit']);
				}
				redirect($this->data[''].'admin_tools/admin');
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0023-Q');
				$data_edit = array();
				$data_edit['id'] = $_SESSION['admin_id'];
				$data_edit['login_id'] = $this->input->post('login_id');
				$data_edit['email'] = $this->input->post('email');
				$data_edit['first_name_ja'] = $this->input->post('first_name_ja');
				$data_edit['last_name_ja'] = $this->input->post('last_name_ja');
				$data_edit['first_name'] = $this->input->post('first_name');
				$data_edit['last_name'] = $this->input->post('last_name');
				$data_edit['gender'] = $this->input->post('gender');
				$data_edit['birthday'] = $this->input->post('birthday');
				$data_edit['phone_number'] = $this->input->post('phone_number');
				$data_edit['organization'] = $this->input->post('organization');
				$data_edit['department'] = $this->input->post('department');
				$data_edit['position'] = $this->input->post('position');
				$data_edit['info'] = $this->input->post('info');
				$data_edit['language'] = $this->input->post('language');
				$data_edit['status'] = $this->input->post('status');
				
				if ('' != trim($this->input->post('password'))) {
					$data_edit['password'] = $this->input->post('password');
					$this->setup_validation_rules('admin/change_password');
				}
				$this->setup_validation_rules('admin/update');
				$validation =$this->form_validation->run($this);
				if ( $validation === TRUE ) {
					$_SESSION['admin_info_edit'] = $data_edit;
					$this->data['admin_info_edit']=$data_edit;	
					$this->parse('admin_edit_confirm.tpl', 'admin/confirm_edit');
				} else {
					$this->data['admin_info_edit'] = $data_edit;
					$this->parse('admin_edit.tpl', 'admin/update');
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 管理者編集完了画面の表示処理
	 * @param integer $id
	 */
	public function update($id){
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_info_edit'])) {
					unset($_SESSION['admin_info_edit']);
				}
				redirect($this->data[''].'admin_tools/admin');
				return;
			}
			// Check login
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if ($this->data['admin']->role == ROLE_ADMIN) {
				if ($id != $this->data['admin']->id) {
					set_status_header(417);
					exit;
				}
			}
			if (!isset($_SESSION['admin_info_edit'])) {
				set_status_header(417);
				exit;
			}
			$msgID = "";
			$admindao = new AdminDao(MASTER);
			$data = $_SESSION['admin_info_edit'];
			if (isset($data['password'])) {
				$data['password'] = hash('sha256', $data['password']);
			}
			$result = $admindao->updateAdmin($data['id'],$data);
			
			unset($_SESSION['admin_info_edit']);
			unset($_SESSION['admin_id']);
			unset($_SESSION['login_id']);
		
			if (!$result) {
				$msgID = 'L-A-0006-E';
			} else {
				$msgID = 'L-A-0005-I';
			}
			$message = $this->lang->line($msgID);
			$this->clear_csrf();
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	
	/**
	 * 管理者削除完了画面の表示処理
	 * @param integer $id
	 */
	public function delete($id){
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['admin_id'])) {
					unset($_SESSION['admin_id']);
				}
				redirect($this->data[''].'admin_tools/admin');
			}
			
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if ($this->form_validation->check_role() == FALSE) {
				set_status_header(410);
				exit;
			}
			if (!isset($_SESSION['admin_id'])) {
				set_status_header(417);
				exit;
			}
			$id = $_SESSION['admin_id'];
			unset($_SESSION['admin_id']);
			
			if ($id == $this->adminauth->getAdmin()->id) {
				set_status_header(417);
				exit;
			}
			$info = array();
			$admindao = new AdminDao(MASTER);
			$admindao->id = $id;
			$result = $admindao->delete();
			if (!$result) {
				$msgID = 'L-A-0002-E';
			} else {
				$msgID = 'L-A-0001-I';
			}
			$info['message'] = $this->lang->line($msgID);
			$this->clear_csrf();
			echo json_encode($info);

		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 全て管理者ののエクスポート処理管
	 */
	public function export_all() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				redirect($this->data[''] .'admin_tools/admin');
			}

			$encoding = $this->input->get('encoding');
			$list_columns_table_admin = $this->column_table_admin;
			$output_file_name = $this->lang->line('label_admin_export_all_file_name').'.'.OUTPUT_FILE_TYPE_TSV;

			$admindao = new AdminDao();
			$result = $admindao->get_all();

			$this->process_export($result, $output_file_name, $list_columns_table_admin,'label_admin_', $encoding);

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
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				redirect($this->data[''] .'admin_tools/admin');
			}
			if(!isset($_SESSION['condition'])) {
				redirect($this->data[''] .'admin_tools/admin');
			}
			
			$encoding = $this->input->get('encoding');
			$list_columns_table_admin =  $this->column_table_admin;
			$output_file_name = $this->lang->line('label_admin_export_search_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			$conditionSearch = $_SESSION['condition'];

			$admindao = new AdminDao();
			$result_search = $admindao->search($conditionSearch);

			$this->process_export($result_search, $output_file_name, $list_columns_table_admin,'label_admin_', $encoding);

		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * リクエストからデータを取得する。
	 */
	private function load_info_admin(){
		$this->data['id'] = $this->input->post('id');
		$this->data['login_id'] = $this->input->post('login_id');
		$this->data['email'] = $this->input->post('email');
		$this->data['first_name_ja'] = $this->input->post('first_name_ja');
		$this->data['last_name_ja'] = $this->input->post('last_name_ja');
		$this->data['first_name'] = $this->input->post('first_name');
		$this->data['last_name'] = $this->input->post('last_name');
		$this->data['gender'] = $this->input->post('gender');
		$this->data['birthday'] = $this->input->post('birthday');
		$this->data['organization'] = $this->input->post('organization');
		$this->data['department'] = $this->input->post('department');
		$this->data['position'] = $this->input->post('position');
		$this->data['phone_number'] = $this->input->post('phone_number');
		$this->data['info'] = $this->input->post('info');
		$this->data['admin_language'] = $this->input->post('language');
		$this->data['status'] = $this->input->post('status');
	}
	
	/**
	 * クエリからデータを取得する。
	 */
	private function select_data($result_query) {
		$data['id'] = $result_query->id;
		$data['email'] = $result_query->email;
		$data['login_id'] = $result_query->login_id;
		$data['birthday'] = $result_query->birthday;
		$data['first_name_ja'] =$result_query->first_name_ja;
		$data['last_name_ja'] = $result_query->last_name_ja;
		$data['first_name'] = $result_query->first_name;
		$data['last_name'] = $result_query->last_name;
		$data['gender'] = $result_query->gender;
		$data['phone_number'] = $result_query->phone_number;
		$data['organization'] = $result_query->organization;
		$data['department'] = $result_query->department;
		$data['position'] = $result_query->position;
		$data['info'] = $result_query->info;
		$data['language'] = $result_query->language;
		$data['status'] = $result_query->status;
		return $data;
	}
	
	/**
	 * ログインIDのチェック処理
	 * @param string $login_id
	 * @return boolean
	 */
	public function check_login_id($login_id){
		$login_id_old = $_SESSION['login_id'];
		if ($login_id_old == $login_id) {
			return TRUE;
		}
		return $this->form_validation->is_unique($login_id,'admins.login_id');
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function setValueItem($case) {
		if ($case === 'search') {
			$this->data['id'] 				= $this->input->post('id');
			$this->data['login_id'] 		= $this->input->post('login_id');
			$this->data['email'] 	 		= $this->input->post('email');
			$this->data['last_name_ja'] 	= $this->input->post('last_name_ja');
			$this->data['first_name_ja'] 	= $this->input->post('first_name_ja');
			$this->data['gender']			= $this->input->post('gender');
				
		} else {
			//　ソート、改ページ、削除の場合
			// セッションの値を項目に設定
			$condition = $_SESSION['condition'];
			$this->data['id'] 				= $condition['id'];
			$this->data['login_id'] 		= $condition['login_id'];
			$this->data['email'] 	 		= $condition['email'];
			$this->data['last_name_ja'] 	= $condition['last_name_ja'];
			$this->data['first_name_ja'] 	= $condition['first_name_ja'];
			$this->data['gender']			= $condition['gender'];
		}
	}
	
	/**
	 * 一括登録確認画面の表示処理。
	 */
	public function import_confirm() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				$msgID = 'L-A-0027-E';
				$this->data['error_access_denied'] = $this->lang->line($msgID);
			}
			$this->load->library('excel');
			$this->load->library('upload');
			if($_SERVER['REQUEST_METHOD'] !== 'POST'){
				redirect($this->data[''].'admin_tools/admin');
			}
			$this->data['case'] = "upload_confirm";
			$this->setValueItem($this->data['case']);

			$admindao = new AdminDao();
			$this->data['format_tsv'] = config_item('forminfo')['common']['format_export_tsv'];
			$this->data['is_has_data'] = $admindao->is_has_data();
			
			if (!is_dir(UPLOAD_PATH_TSV)) {
				mkdir(UPLOAD_PATH_TSV);
			}

			$config['upload_path'] = UPLOAD_PATH_TSV;
			$config['allowed_types'] = OUTPUT_FILE_TYPE_TSV;
			$config['max_size']	= '0';

			$ext = end(explode('.', $_FILES['upload']['name']));
			$this->data['file_name_uniqid'] = md5(uniqid()).'.'.$ext;
			$config['file_name'] = $this->data['file_name_uniqid'];

			$this->data['row'] = 0;
			$this->data['flag_error'] = TRUE;
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('upload')) {
				$this->data['msg_error'] = $this->upload->display_errors();
				$this->parse("admin_list.tpl","admin/index");
				return;
			}
			$file = UPLOAD_PATH_TSV.$this->data['file_name_uniqid'];
			$reader = new PHPExcel_Reader_CSV();
			$reader->setDelimiter("\t");
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_MyValueBinder());
			$objXLS = $reader->load($file);

			//check data row title
			$row_title = $objXLS->getSheet(0)->getRowIterator(1)->current();
			$cellIterator = $row_title->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(FALSE);
			list($check_title, $has_id) = $this->check_title($cellIterator);
			if ($check_title === FALSE) {
				$this->data['msg_error'] = $this->lang->line('L-A-0012-E');
				if(file_exists($file)) {
					@unlink($file);
				}
				$this->parse("admin_list.tpl","admin/index");
				return;
			} else {
				$data = $this->check_validation($objXLS, $has_id);
			}

			$objXLS->disconnectWorksheets();
			unset($objXLS);

			$admin_info['file_name_uniqid'] = $this->data['file_name_uniqid'];
			$admin_info['row'] = $data['row'];
			$admin_info['has_id'] = $has_id;
			$this->session->set_userdata('upload_admin_info', $admin_info);

			$this->data['row'] = $data['row'];
			$this->data['type_row'] = $data['type_row'];
			if (isset($data['error_details'])) {
				$this->data['error_details'] = $data['error_details'];
			}
			if (isset($data['msg_error'])) {
				$this->data['msg_result'] = $data['msg_error'];
				if(file_exists($file)) {
					@unlink($file);
				}
			} else {
				$this->data['flag_error'] = FALSE;
				$this->data['msg_result'] = $this->lang->line('L-A-0024-I');
			} 

			$this->parse("admin_list.tpl","admin/import_confirm");
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 一括登録完了画面の表示処理。
	 */
	public function import_done() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			if ($this->form_validation->check_role() == FALSE) {
				$msgID = 'L-A-0027-E';
				$this->data['error_access_denied'] = $this->lang->line($msgID);
			}
			$this->load->library('excel');
			if($_SERVER['REQUEST_METHOD'] != 'POST'){
				if ($this->session->userdata('upload_admin_info') != FALSE) {
					$this->session->unset_userdata('upload_admin_info');
				}
				redirect($this->data[''].'admin_tools/admin');
			} else {
				if ($this->session->userdata('upload_admin_info') == FALSE) {
					redirect($this->data[''].'admin_tools/admin');
				}
				
				$this->data['case'] = "upload_done";
				$this->setValueItem($this->data['case']);
				
				$admin_info = $this->session->userdata('upload_admin_info');
				$file_name = $admin_info['file_name_uniqid'];
				$row = $admin_info['row'];
				$file = UPLOAD_PATH_TSV.$file_name;

				$reader = new PHPExcel_Reader_CSV();
				$reader->setDelimiter("\t");
				PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_MyValueBinder());
				$objXLS = $reader->load($file);

				$data = $this->get_data_insert($objXLS, $row, $admin_info['has_id']);

				$objXLS->disconnectWorksheets();
				unset($objXLS);

				//delete file tsv
				if(file_exists($file)) {
					@unlink($file);
				}

				$admin = new AdminDao(MASTER);
				$result = $admin->insert_batch($data);
				if (!$result) {
					$this->data['message'] = $this->lang->line('L-A-0004-E');
				} else {
					$this->data['message'] = $this->lang->line('L-A-0026-I');
				}

				$this->session->unset_userdata('upload_admin_info');
				$this->parse("admin_list.tpl","admin/import_done");
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 取込ファイルからデータを取得してデータベースに挿入する。
	 * @param object $objXLS
	 * @param int $row
	 * @return array
	 */
	public function get_data_insert($objXLS, $row, $has_id) {
		$default_language = 'japanese';
		$current_date = date("Y-m-d H:i:s");
		$first_col = 0;
		if ($has_id == TRUE) {
			$first_col = 1;
		}
		
		$admin = new AdminDao();
		$tmp_objXLS = $objXLS->getSheet(0);
		for ($i = 1, $j = $this->first_row; $j < $row + $this->first_row; $i++, $j++) {

			$col = $first_col;
			foreach ($this->column_table_admin as $column) {
				if ($column == 'joined_at') {
					break;
				}
				
				if ($column == 'id') {
					continue;
				}
				
				if ($column == 'password') {
					$password = trim($tmp_objXLS->getCellByColumnAndRow($col, $j)->getValue());
					if ($password != '') {
						$data[$i][$column]	=	hash('sha256',$password);
					}
				} else {
					$data[$i][$column] = trim($tmp_objXLS->getCellByColumnAndRow($col, $j)->getValue());
				}
				$col += 1;
			}
			$data[$i]['id']			=	$admin->get_id_by_login_id($data[$i]['login_id']);
			$data[$i]['language']	=	($data[$i]['language'] == "") ? $default_language : $data[$i]['language'];
		}
		return $data;
	}
	
	/**
	 * アップロードファイルのデータが妥当かチェックする。
	 * @param object $objXLS
	 * @return array
	 */
	public function check_validation($objXLS, $has_id) {
		$data_file['row'] = 0;
		$msg_error = array(); 
		$data_file['type_row'] = array('new' => 0, 'update' => 0, 'delete' => 0);
		$tmp_objXLS = $objXLS->getSheet(0);
		$admindao = new AdminDao();
		$admin = $this->adminauth->getAdmin();
		$this->lang->load('form_validation');
		//check first row
		$data_first_row = $tmp_objXLS->getRowIterator($this->first_row)->current();
		if ($this->check_empty_row($data_first_row) === FALSE) {
			$data_file['msg_error'] = $this->lang->line('L-A-0013-E');
			return $data_file;
		}

		$arr_table_admin =  array('email' => 0,'login_id'=> 1,'password'=> 2,'last_name_ja'=> 3,'first_name_ja'=> 4
				,'last_name'=> 5,'first_name'=> 6,'gender'=> 7,'birthday' => 8,'organization' => 9,'department' => 10
				,'position' => 11,'phone_number' => 12,'info' => 13,'role' => 14,'language' => 15,'status' => 16
				,'joined_at' => 17,'leaved_at' => 18,'expired_at' => 19,'created_at' => 20,'updated_at' => 21,'deleted_at' => 22
		);
		$j = 0;
		if ($has_id == TRUE) {
			$j = 1;
		}

		//get row of file import
		$row=$tmp_objXLS->getHighestRow();
		$arr_login_id = array();
		$count_error = 0;
		for ($i = $this->first_row; $i <= $row; $i++ ) {
			$row_data = $tmp_objXLS->getRowIterator($i)->current();
			if ($this->check_empty_row($row_data) === FALSE) {
				break;
			}
			$data_file['row'] += 1;
			$error = '';
			$email = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['email'] + $j, $i)->getValue());
			if ($this->form_validation->valid_email($email) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('valid_email'), $this->lang->line('label_admin_'.'email'))."\r\n";
			} else if($this->form_validation->max_length($email, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'email'), 128)."\r\n";
			}

			$login_id = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['login_id'] + $j, $i)->getValue());
			//check unique login_id in file
			$check_login_id = in_array($login_id, $arr_login_id);
			if ($this->form_validation->min_length($login_id, 6) === FALSE ) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('min_length'), $this->lang->line('label_admin_'.'login_id'), 6)."\r\n";
			} elseif ($check_login_id === TRUE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_unique_in_file'), $this->lang->line('label_admin_'.'login_id'))."\r\n";
			} elseif ($this->form_validation->max_length($login_id, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'login_id'), 64)."\r\n";
			} elseif ($this->form_validation->alpha_numeric($login_id) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_admin_'.'login_id'))."\r\n";
			} else {
				$arr_login_id[$i] = $login_id;
			}

			$last_name_ja =trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['last_name_ja'] + $j, $i)->getValue());
			if ($last_name_ja == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_admin_'.'last_name_ja'))."\r\n";
			} elseif ($this->form_validation->max_length($last_name_ja, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'last_name_ja'), 64)."\r\n";
			}

			$first_name_ja =trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['first_name_ja'] + $j, $i)->getValue());
			if ($first_name_ja == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_admin_'.'first_name_ja'))."\r\n";
			} elseif ($this->form_validation->max_length($first_name_ja, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'first_name_ja'), 64)."\r\n";
			}

			$last_name = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['last_name'] + $j, $i)->getValue());
			if ($last_name != "") {
				if ($this->form_validation->max_length($last_name, 64) === FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'last_name'), 64)."\r\n";
				}elseif ($this->form_validation->alpha_numeric($last_name) === FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_admin_'.'last_name'))."\r\n";
				}
			}

			$first_name = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['first_name'] + $j, $i)->getValue());
			if ($first_name != "") {
				if ($this->form_validation->max_length($first_name, 64) === FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'first_name'), 64)."\r\n";
				} elseif ($this->form_validation->alpha_numeric($first_name) === FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_admin_'.'first_name'))."\r\n";
				}
			}
			
			$gender = $tmp_objXLS->getCellByColumnAndRow($arr_table_admin['gender'] + $j, $i)->getValue();
			if ($gender != "") {
				if ($gender != "1" && $gender != "2") {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_admin_'.'gender'))."\r\n";
				}
			}
			
			$birthday = $tmp_objXLS->getCellByColumnAndRow($arr_table_admin['birthday'] + $j, $i)->getValue();
			if ($birthday != ""){
				if ($this->form_validation->check_birthday($birthday) === FALSE) {
					$count_error++;
					$error = $error."\t".$this->lang->line('check_birthday')."\r\n";
				}
			}

			$organization = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['organization'] + $j, $i)->getValue());
			if ($this->form_validation->max_length($organization, 255) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'organization'), 255)."\r\n";
			}

			$department = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['department'] + $j, $i)->getValue());
			if ($this->form_validation->max_length($department, 255) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'department'), 255)."\r\n";
			}

			$position = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['position'] + $j, $i)->getValue());
			if ($this->form_validation->max_length($position, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_admin_'.'position'), 128)."\r\n";
			}

			$phone_number = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['phone_number'] + $j, $i)->getValue());
			if ($phone_number != "") {
				if ($this->form_validation->is_valid_phone_number($phone_number) === FALSE) {
					$count_error++;
					$error = $error."\t".$this->lang->line('is_valid_phone_number')."\r\n";
				}
			}

			$role = $tmp_objXLS->getCellByColumnAndRow($arr_table_admin['role'] + $j, $i)->getValue();
			if ($role != "") {
				if ($role != strval(ROLE_ADMIN) && $role != strval(ROLE_SUPER_ADMIN)) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_admin_'.'role'))."\r\n";
				}
			}
			
			$listlanguage = config_item('forminfo')['common']['language'];
			$language = trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['language'] + $j, $i)->getValue());
			if ($language != '') {
				if ($language != $listlanguage['english']['id'] && $language != $listlanguage['japanese']['id']) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_admin_'.'language'))."\r\n";
				}
			}

			$password =trim($tmp_objXLS->getCellByColumnAndRow($arr_table_admin['password'] + $j, $i)->getValue());
			$status = $tmp_objXLS->getCellByColumnAndRow($arr_table_admin['status'] + $j, $i)->getValue();
			if ($status != strval(STATUS_DISABLE) && $status != strval(STATUS_ENABLE) && $status != $this->status_delete) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_admin_'.'status'))."\r\n";
			} else {
				//check unique login_id in database
				$admin_id = $admindao->get_id_by_login_id($login_id);
				$msg_err = $this->form_validation->check_password($password);
				if ($admin_id > 0) {
					if ($admin->id == $admin_id) {
						if ($status == $this->status_delete) {
							$count_error++;
							$error = $error."\t".$this->lang->line('L-A-0040-E')."\r\n";
						}
						else {
							if ($password != '') {
								if ($msg_err !== TRUE) {
									$count_error++;
									$error = $error."\t".$msg_err."\r\n";
								}
							}
							$data_file['type_row']['update'] += 1;
						}
					} else {
						if ($status != $this->status_delete) {
							if ($password != '') {
								if ($msg_err !== TRUE) {
									$count_error++;
									$error = $error."\t".$msg_err."\r\n";
								}
							}
							$data_file['type_row']['update'] += 1;
						} else {
							$data_file['type_row']['delete'] += 1;
						}
					}
				} else {
					if ($status == $this->status_delete) {
						$count_error++;
						$error = $error."\t".$this->lang->line('delete_id_not_in_database')."\r\n";
					} else {
						if ($msg_err !== TRUE) {
							$count_error++;
							$error = $error."\t".$msg_err."\r\n";
						}
						$data_file['type_row']['new'] += 1;
					}
				}
			}
			if ($error != '') {
				$error = $i.$this->lang->line("label_line")." :\r\n".$error;
				array_push($msg_error, $error);
			}
		}
		
		if ($count_error > 0) {
			$data_file['msg_error'] = $this->lang->line('L-A-0025-E');
		
			if ($count_error <= MAX_ERROR_SHOW) {
				$data_file['error_details'] = implode('', $msg_error);
			} else {
				$this->load->helper(array('fileutil'));
				save_file(APPPATH.'tmp_files/error_upload_admin_'.session_id().'.txt', $msg_error);
			}
		}
		return $data_file;
	}

	/**
	 * アップロードファイルにおけるいずれの行が空白であるかをチェックする。　
	 * @param object $row
	 * @return boolean
	 */
	public function check_empty_row($row) {
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(FALSE);
		foreach ($cellIterator as $cell) {
			if (trim($cell->getValue()) != "") {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * アップロードのテンプレートが正しいかをチェックする。
	 * @param object $cellIterator
	 * @return boolean
	 */
	public function check_title($cellIterator) {
		$title_template = $this->column_table_admin;

		foreach ($cellIterator as $cell) {
			$title[] = $cell->getValue();
		}

		if ($title[0] == $this->lang->line('label_admin_'.$title_template[0])) {
			$j = 0;
			$has_id = TRUE;
		} else if($title[0] == $this->lang->line('label_admin_'.$title_template[1])) {
			$j = 1;
			$has_id = FALSE;
		} else {
			log_message('debug', sprintf("TITLE CHECK: %s = %s", $title[0], $this->lang->line('label_admin_'.$title_template[0])));
			return array(FALSE, NULL);
		}
	
		for($i = 0; $j < count($title_template) - 6; $i++, $j++) {
			if (trim($title[$i]) != $this->lang->line('label_admin_'.$title_template[$j])) {
				log_message('debug', sprintf("TITLE CHECK: %s = %s", $title[$i], $this->lang->line('label_admin_'.$title_template[$j])));
				return array(FALSE, NULL);
			}
		}
		return array(TRUE,$has_id);
	}
	
	public function download_file_error() {
		$file_path = APPPATH.'tmp_files/error_upload_admin_'.session_id().'.txt';
		if (!file_exists($file_path)) {
			redirect($this->data[''].'admin_tools/admin');
		}
		$this->load->helper('download');
		$data = file_get_contents($file_path); // Read the file's contents
		$file_name = 'error_upload_admin-'.date('Y-m-d').'.txt';
		$output_file_name = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0) ? $file_name : urlencode($file_name);
		force_download($output_file_name, $data);
	}
}
