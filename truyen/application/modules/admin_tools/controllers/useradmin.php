<?php
/**
 * @name ユーザー管理
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class UserAdmin extends MY_Controller {
	
	private $column_table_user = array('id','email','login_id','password','last_name_ja','first_name_ja','last_name','first_name'
					, 'gender','birthday','qualification_id','qualification','organization','department','position'
					, 'phone_number','domain','domain_flag','specialist','specialist_flag','history','history_flag'
					, 'university','university_flag', 'scholar','scholar_flag','author','author_flag','society','society_flag'
					,'hobby','hobby_flag','message','message_flag', 'auth_method','confirm_image_url','confirm_organization'
					, 'confirm_phone_number', 'dcf_code1','qlid1','qlid2','domain1','domain2','domain3','domain4','domain5','company_code'
					, 'remark1','remark2','remark3','remark4','remark5','remark6','remark7','remark8','remark9','remark10'
					, 'recommend_user_id','registered_type','registered_status','registered_admin_id','approvaled_admin_id','language','approved_at','status'
					, 'max_file_size', 'file_size', 'joined_at','leaved_at','expired_at','created_at','updated_at','deleted_at'
				);
	private $status_delete = "-1";
	private $first_row = 2;
	const FLAG_PUBLIC 		=	"1";
	const FLAG_NON_PUBLIC	=	"2";
	
	public function __construct() {
		parent::__construct();
	
		if(!isset($_SESSION)){
			session_start();
		}
		$this->load->config('forminfo');
		
		$listgendertypes = config_item('forminfo')['common']['profile']['gender_types'];
		$this->data['gender_types'] = $listgendertypes;
		
		$list_status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $list_status_types;
		
		$listlanguages = config_item('forminfo')['common']['language'];
		$this->data['languages'] = $listlanguages;
		
		$this->data['controller'] = 'user';
		$this->setup_form_info($this->data['controller'].'/'.$this->data['language']);
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * ユーザ一覧画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case ='search') {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			$this->data['list_registered_type'] = config_item('forminfo')['common']['registered_type'];
			$condition = array();
			$target = array('id','email','login_id','first_name_ja','last_name_ja','gender','qualification_id','organization',
							'phone_number','registered_type','status','start_date','end_date','per_page'
							);
			if ($case === 'search') {
				foreach ($target as $key) {
					$condition[$key] = $this->input->post($key);
				}
				
				if(trim($condition['start_date']) != '' || trim($condition['end_date']) != '' || trim($condition['id']) != '') {
					$this->setup_validation_rules('user/search');
					$validation = $this->form_validation->run($this);
					if (!$validation){
						$this->data['is_has_data'] = FALSE;
						$this->setValueItem($case);
						header_remove("Cache-Control");
						$this->parse('user_list.tpl', 'useradmin/index');
						return;
					}
				}
				// セッションに検索条件を保存するを実行
				$_SESSION['condition'] = $condition;
			} else {
				$condition = $_SESSION['condition'];
			}
			$userdao = new UserDao();
			$this->data['is_has_data'] = $userdao->is_has_data();
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/user/paginate";
			$uri_segment = 4;
			$total_records = $userdao->count_by_condition($condition);
			$this->data['total_records'] = $total_records;
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $userdao->search($condition,$limit);
			// 項目値設定
			$this->setValueItem($case);
			$this->data['list_users'] = $result;
			
			header_remove("Cache-Control");
			$this->parse('user_list.tpl', 'useradmin/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ新規登録画面の表示処理。
	 */
	public function create() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['user_info'])) {
					unset($_SESSION['user_info']);
				}
				$this->data['status'] = $this->data['status_types'][1]['id'];
				$this->data['gender'] = $this->data['gender_types'][1]['id'];
				$this->data['user_language'] = $this->data['language'];
				$this->parse('user_create.tpl', 'useradmin/create');
				return;
			} else {// edit
				if (!isset($_SESSION['user_info'])) {
					redirect($this->data[''].'admin_tools/user/create');
				}
				$user_info = unserialize($_SESSION['user_info']);
				
				$targets = array('email','login_id','first_name_ja','last_name_ja','first_name','last_name','gender','birthday','qualification_id','organization',
						'department','position','phone_number','domain','history','university','scholar','author','society','hobby','message','company_code','status'
				);
				foreach ($targets as $key) {
					$this->data[$key] = $user_info[$key];
				}
				$this->data['user_language'] = $user_info['language'];
				
				$this->parse('user_create.tpl', 'useradmin/create');
			}			
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ新規登録確認画面の表示処理。
	 */
	public function confirm_create() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$this->data['flag_error']= FALSE;
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['user_info'])) {
					unset($_SESSION['user_info']);
				}
				redirect($this->data[''].'admin_tools/user/create');
				return;
			}
			
			$this->setup_validation_rules('user/create');
			$validation = $this->form_validation->run($this);
			if (TRUE === $validation) {
				$user_info = array();
				$user_info = $this->get_value_users();
				$user_info['password'] = $this->input->post('password');
				
				$_SESSION['user_info'] = serialize($user_info);
				$this->data['user_info']= $user_info;
				
				$this->parse('user_confirm_create.tpl','useradmin/confirm_create');
			} else {
				$qualificationdao = new QualificationDao();
				$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
				$this->data['user_language'] = $this->input->post('language');
				
				$targets = array('email','login_id','first_name_ja','last_name_ja','first_name','last_name','gender','birthday','qualification_id','qualification','organization',
						'department','position','phone_number','domain','history','university','scholar','author','society','hobby','message','company_code','status'
				);
				foreach ($targets as $key) {
					$this->data[$key] = $this->input->post($key);
				}
				$this->parse('user_create.tpl', 'useradmin/create');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ新規登録完了画面の表示処理
	 */
	public function store() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['user_info'])) {
					unset($_SESSION['user_info']);
				}
				redirect($this->data[''].'admin_tools/user/create');
				return;
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if (!isset($_SESSION['user_info'])) {
				set_status_header(417);
				exit;
			}
			//get info of admin
			$info_admin = $this->adminauth->getAdmin();
			$user_info = unserialize($_SESSION['user_info']);
			
			$list_registered_type = config_item('forminfo')['common']['registered_type'];
			$list_registered_status = config_item('forminfo')['common']['registered_status'];
			$list_config_setting  = config_item('forminfo')['common']['config_setting'];
			
			$user_info['registered_admin_id'] = $info_admin->user_id;
			$user_info['approvaled_admin_id'] = $info_admin->user_id;
			$user_info['registered_type'] = $list_registered_type[1]['id'];
			$user_info['registered_status'] = $list_registered_status[2]['id'];
			$user_info['password'] = hash('sha256',$user_info['password']);
			$user_info['approved_at'] = date("Y-m-d H:i:s");
			$user_info['joined_at'] = date("Y-m-d H:i:s");
			$msgID = "";
			
			$userdao = new UserDao(MASTER);
			$result = $userdao->insert($user_info);
			
			if (!$result) {
				$msgID = 'L-A-0004-E';
			} else {
				$msgID = 'L-A-0003-I';
				
				//write log
				$user_info['id'] = $result;
				$aldao = new ActivityLogDao(MASTER);
				$aldao->on_user_join($user_info);
				
				$configdao = new ConfigDao(MASTER);
				$configdao->insert_config($result,CONFIG_CATEGORY_NOTICE, $list_config_setting);
			}
			unset($_SESSION['user_info']);
			$message = $this->lang->line($msgID);
			$this->clear_csrf();
			echo json_encode($message);
			
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
		
	}
	
	/**
	 * ユーザ編集画面の表示処理
	 *  @param $id ユーザーＩＤ
	 */
	public function edit($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			
			$this->data['list_auth_method'] = config_item('forminfo')['common']['auth_method'];
			$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0022-Q');
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$userdao = new UserDao();
				$result = $userdao->get_by_id($id);
				if (NULL === $result->id) {
					redirect($this->data[''] . 'admin_tools/user');
				}
				
				$data_user_const = array ('id' => $id,
										'email' => $result->email,
										'login_id' => $result->login_id,
										'auth_method' => $result->auth_method,
										'recommend_user_id' => $result->recommend_user_id,
										'joined_at' => $result->joined_at
										);
				$_SESSION['data_user_const'] = $data_user_const;
				
				$this->data['user_info_edit'] = $this->parse_data_from_query($result);
				
				$this->parse('user_edit.tpl','useradmin/edit');
				return;
			} else {// edit
				$user_info_edit = $_SESSION['user_info_edit'];
				$this->data['user_info_edit'] = $user_info_edit;
				$this->parse('user_edit.tpl', 'useradmin/edit');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ編集確認画面の表示処理
	 *  @param $id ユーザーＩＤ
	 */
	public function confirm_edit($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['user_info_edit'])) {
					unset($_SESSION['user_info_edit']);
				}
				redirect($this->data[''].'admin_tools/user');
			}			
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0022-Q');
				$this->data['list_auth_method'] = config_item('forminfo')['common']['auth_method'];
				
				$data_user_edit = array();
				$data_user_edit = $this->get_value_users('edit');
				
				if ('' != trim($this->input->post('password'))) {
					$data_user_edit['password'] = $this->input->post('password');
					$this->setup_validation_rules('user/change_password');
				}
				$this->setup_validation_rules('user/edit');
				if ($data_user_edit['email'] !== $_SESSION['data_user_const']['email']) {
						$this->setup_validation_rules('user/edit_email');
				}
				$validation =$this->form_validation->run($this);
				if (TRUE === $validation) {
					$_SESSION['user_info_edit'] = $data_user_edit;
					$this->data['data_user_edit']= $data_user_edit;
					$this->parse('user_confirm_edit.tpl','useradmin/confirm_edit');
				} else {
					$qualificationdao = new QualificationDao();
					$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
					$this->data['user_info_edit'] = $data_user_edit;
					$this->parse('user_edit.tpl', 'useradmin/edit');
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ編集完了画面の表示処理
	 * @param $id ユーザーＩＤ
	 */
	public function update($id) {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['user_info_edit'])) {
					unset($_SESSION['user_info_edit']);
				}
				redirect($this->data[''].'admin_tools/user');
				return;
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if (!isset($_SESSION['user_info_edit'])) {
				set_status_header(417);
				exit;
			}
	
			$msgID = "";
	
			$userdao = new UserDao(MASTER);
			$data = $_SESSION['user_info_edit'];
			if (isset($data['password'])) {
				$data['password'] = hash('sha256', $data['password']);
			}
			$result = $userdao->update_user($data['id'],$data);
				
			unset($_SESSION['user_info_edit']);
			unset($_SESSION['data_user_const']);
				
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
	 * ユーザ削除完了画面の表示処理
	 * @param $id ユーザーＩＤ
	 */
	public function delete() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['data_user_const'])) {
					unset($_SESSION['data_user_const']);
				}
				redirect($this->data[''].'admin_tools/user');
				return;
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if (!isset($_SESSION['data_user_const'])) {
				set_status_header(417);
				exit;
			}
			$msgID = "";
			$id = $_SESSION['data_user_const']['id'];

			unset($_SESSION['data_user_const']);
			$userdao = new UserDao(MASTER);
			$userdao->id = $id;
			$result = $userdao->delete();
			if (!$result) {
				$msgID = 'L-A-0002-E';
			} else {
				$msgID = 'L-A-0001-I';
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
	 * 代表資格・職種リストの作成処理
	 * @param object $qualificationdao
	 * @return array $qualifications
	 */
	private function set_value_option_qualification($qualificationdao) {
		$result = $qualificationdao->get_list();
		$qualifications = array();
		$count =0;
		foreach ($result as $item) {
			$qualifications[$count]['id'] = $item->id;
			$qualifications[$count]['name'] = $item->name;
			$count ++;
		}
		return $qualifications;
	}
	
	/**
	 * リクエストからデータを取得する。
	 * @return array
	 */
	private function get_value_users($mode='') {
		$user_info = array();
		$targets = array('email','login_id','first_name_ja','last_name_ja','first_name','last_name','gender','birthday','qualification_id','qualification','organization',
				'department','position','phone_number','domain','history','university','scholar','author','society','hobby','message','company_code','language','status'
				);
		
		foreach ($targets as $key) {
			$user_info[$key] = $this->input->post($key);
		}
		if($mode == 'edit') {
			$user_info['recommend_user_id'] = $_SESSION['data_user_const']['recommend_user_id'];
			$user_info['auth_method'] =$_SESSION['data_user_const']['auth_method'];
			$user_info['joined_at'] = $_SESSION['data_user_const']['joined_at'];
			$user_info['id'] = $_SESSION['data_user_const']['id'];
		} 
		return $user_info;
	}
	
	/**
	 * Parse data from query to array
	 * @param object $result_query
	 * @return array
	 */
	private function parse_data_from_query($result_query) {
		$data = array();
		$targets = array('id','email','login_id','first_name_ja','last_name_ja','first_name','last_name','gender','birthday','qualification_id','qualification','organization',
				'department','position','phone_number','domain','history','university','scholar','author','society','hobby','message','company_code','language','status',
				'recommend_user_id','auth_method','joined_at'
		);
		
		foreach ($targets as $key) {
			$data[$key] = $result_query->$key;
		}
		return $data;
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function setValueItem($case) {
		$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender','qualification_id',
							'organization','phone_number','registered_type','status','start_date','end_date'
						);
		if ($case === 'search') {
			// 検索の場合
			foreach ($targets as $key) {
				$this->data[$key] = $this->input->post($key);
			}
		} else {
			$conditionInfo = $_SESSION['condition'];
			foreach ($targets as $key) {
				$this->data[$key] = $conditionInfo[$key];
			}
		}
	}
	
	/**
	 * ログインIDのチェック処理
	 * @param string $login_id
	 * @return boolean
	 */
	public function check_login_id($login_id) {
		$login_id_old = $_SESSION['data_user_const']['login_id'];
		if ($login_id_old == $login_id) {
			return TRUE;
		}
		if ($this->form_validation->is_unique($login_id,'users.login_id')) {
			return $this->form_validation->is_unique($login_id,'tmp_users.login_id');
		}
		return FALSE;
	}
	
	/**
	 * 一括登録確認画面の表示処理。
	 */
	public function import_confirm() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$this->load->library('excel');
			$this->load->library('upload');
			
			if($_SERVER['REQUEST_METHOD'] !== 'POST'){
				redirect($this->data[''].'admin_tools/user');
			}
			$this->data['case'] = "upload_confirm";
			$this->setValueItem($this->data['case']);
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			$this->data['list_registered_type'] = config_item('forminfo')['common']['registered_type'];
			$this->data['format_tsv'] = config_item('forminfo')['common']['format_export_tsv'];
			$userdao = new UserDao();
			$this->data['is_has_data'] = $userdao->is_has_data();
			
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
				$this->parse("user_list.tpl","useradmin/index");
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
				$this->parse("user_list.tpl","useradmin/index");
				return;
			} else {
				$data = $this->check_validation($objXLS, $has_id);
			}

			$objXLS->disconnectWorksheets();
			unset($objXLS);

			$user_info['file_name_uniqid'] = $this->data['file_name_uniqid'];
			$user_info['row'] = $data['row'];
			$user_info['has_id'] = $has_id;
			$this->session->set_userdata('upload_user_info',$user_info);

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

			$this->parse("user_list.tpl","useradmin/import_confirm");
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
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$list_config_setting  = config_item('forminfo')['common']['config_setting'];
			$this->load->library('excel');
			if($_SERVER['REQUEST_METHOD'] != 'POST'){
				if ($this->session->userdata('upload_user_info') != FALSE) {
					$this->session->unset_userdata('upload_user_info');
				}
				redirect($this->data[''].'admin_tools/user');
			} else {
				if ($this->session->userdata('upload_user_info') == FALSE) {
					redirect($this->data[''].'admin_tools/user');
				}
				
				$this->data['case'] = "upload_done";
				$this->setValueItem($this->data['case']);
				$qualificationdao = new QualificationDao();
				$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
				$this->data['list_registered_type'] = config_item('forminfo')['common']['registered_type'];
				
				$user_info = $this->session->userdata('upload_user_info');
				$file_name = $user_info['file_name_uniqid'];
				$row = $user_info['row'];
				$file = UPLOAD_PATH_TSV.$file_name;

				$reader = new PHPExcel_Reader_CSV();
				$reader->setDelimiter("\t");
				PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_MyValueBinder());
				$objXLS = $reader->load($file);

				$data = $this->get_data_insert($objXLS, $row, $user_info['has_id']);

				$objXLS->disconnectWorksheets();
				unset($objXLS);

				//delete file tsv
				if(file_exists($file)) {
					@unlink($file);
				}

				$user = new UserDao(MASTER);
				$result = $user->insert_batch($data, $list_config_setting);
				if (!$result) {
					$this->data['message'] = $this->lang->line('L-A-0004-E');
				} else {
					$this->data['message'] = $this->lang->line('L-A-0026-I');
				}
				
				$this->session->unset_userdata('upload_user_info');
				$this->parse("user_list.tpl","useradmin/import_done");
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
	private function get_data_insert($objXLS, $row, $has_id) {
		$default_language = 'japanese';
		$current_date = date("Y-m-d H:i:s");
		$first_col = 0;
		if ($has_id == TRUE) {
			$first_col = 1;
		}
		
		//get info of admin
		$info_admin = $this->adminauth->getAdmin();
		$user = new UserDao();
		$tmp_objXLS = $objXLS->getSheet(0);
		for ($i = 1, $j = $this->first_row; $j < $row + $this->first_row; $i++, $j++) {
			$col = $first_col;
			foreach ($this->column_table_user as $column) {
				if ($column == 'file_size') {
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

			$data[$i]['id']		=	$user->get_id_by_login_id($data[$i]['login_id']);
			$data[$i]['language']		=	($data[$i]['language'] == "") ? $default_language : $data[$i]['language'];
			$data[$i]['approved_at']	=	($data[$i]['approved_at'] == "") ? $current_date : $data[$i]['approved_at'];
			$registered_admin_id = $data[$i]['registered_admin_id'];
			$data[$i]['registered_admin_id']	=	($registered_admin_id == "") ? $info_admin->user_id : $registered_admin_id;
			$approvaled_admin_id = $data[$i]['approvaled_admin_id'];
			$data[$i]['approvaled_admin_id']	=	($approvaled_admin_id == "") ? $info_admin->user_id : $approvaled_admin_id;
			$registered_type = $data[$i]['registered_type'];
			$data[$i]['registered_type']		= ($registered_type == "") ? TYPE_REGIST_USER_ADMIN : $registered_type;
			$registered_status = $data[$i]['registered_status'];
			$data[$i]['registered_status']	= ($registered_status == "") ? STATUS_REGIST_USER_ACTIVE : $registered_status;
		}
		return $data;
	}
	
	/**
	 * 全てユーザーののエクスポート処理
	 */
	public function export_all() {
		try {
			$this->form_validation->check_login_admin();
			$encoding = $this->input->get('encoding');
			$list_columns_table_user =  $this->column_table_user;
			$output_file_name = $this->lang->line('label_user_export_all_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			
			$userdao = new UserDao();
			$result = $userdao->get_all();
			
			$this->process_export($result, $output_file_name, $list_columns_table_user, 'label_user_', $encoding);
			
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
			$this->form_validation->check_login_admin();
			if(!isset($_SESSION['condition'])) {
				redirect($this->data[''] .'admin_tools/user');
			}
			$encoding = $this->input->get('encoding');
			$list_columns_table_user =  $this->column_table_user;
			$output_file_name = $this->lang->line('label_user_export_search_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			$conditionSearch = $_SESSION['condition'];
			
			$userdao = new UserDao();
			$result_search = $userdao->search($conditionSearch);
			
			$this->process_export($result_search, $output_file_name, $list_columns_table_user,'label_user_', $encoding);
			
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * アップロードファイルのデータが妥当かチェックする。
	 * @param object $objXLS
	 * @return array
	 */
	private function check_validation($objXLS, $has_id) {
		$qualification = new QualificationDao();
		$userdao = new UserDao();
		$tmp_userdao = new Tmp_UserDao();
		
		$data_file['row'] = 0;
		$msg_error = array();
		$data_file['type_row'] = array('new' => 0, 'update' => 0, 'delete' => 0);
		$tmp_objXLS = $objXLS->getSheet(0);
		$this->lang->load('form_validation');
		//check first row
		$data_first_row = $tmp_objXLS->getRowIterator($this->first_row)->current();
		if ($this->check_empty_row($data_first_row) === FALSE) {
			$data_file['msg_error'] = $this->lang->line('L-A-0013-E');
			return $data_file;
		}
		
		$arr_max_length = array(
				'qualification' => 10,'organization' => 11,'department' => 12,'domain' => 15,'specialist' => 17, 'university' => 21
				,'confirm_image_url' => 34,'confirm_organization' => 35,'qlid2' => 39,'domain1' => 40,'domain2' => 41,'domain3' => 42
				,'domain4' => 43,'domain5' => 44,'company_code' => 45, 'remark1' => 46,'remark2' => 47,'remark3' => 48,'remark4' => 49
				,'remark5' => 50,'remark6' => 51,'remark7' => 52,'remark8' => 53,'remark9' => 54,'remark10' => 55
		);
		
		$arr_flag = array(
						'domain_flag' => 16, 'specialist_flag' => 18, 'history_flag' => 20, 'university_flag' => 22
					,'scholar_flag' => 24, 'author_flag' => 26, 'society_flag' => 28, 'hobby_flag' => 30, 'message_flag' => 32
					);
		$j = 0;
		if ($has_id == TRUE) {
			$j = 1;
		}

		//get row of file import
		$row=$tmp_objXLS->getHighestRow();
		$arr_email = array();
		$arr_login_id = array();
		$count_error = 0;
		for ($i = $this->first_row; $i <= $row; $i++ ) {
			$row_data = $tmp_objXLS->getRowIterator($i)->current();
			if ($this->check_empty_row($row_data) === FALSE) {
				break;
			}
			$data_file['row'] += 1;
			$error = '';
			$email =trim($tmp_objXLS->getCellByColumnAndRow(0 + $j, $i)->getValue());
			$login_id = trim($tmp_objXLS->getCellByColumnAndRow(1+ $j, $i)->getValue());
			
			//check unique email in file
			$check_email = in_array($email, $arr_email);
			
			//すでに登録完了しているユーザーの場合
			$result = $userdao->get_by_email($email);
			$check_registered = TRUE;
			if ($result->result_count() > 0 && $result->login_id != $login_id) {
				$check_registered = FALSE;
			}
			
			//すでに登録申請しているユーザーの場合
			$check_request = TRUE;
			$result = $tmp_userdao->get_by_email($email);
			if ($result->result_count() > 0 && $result->recommend_user_id == NULL) {
				$check_request = FALSE;
			}
			
			//招待しているユーザーの場合
			$check_invite = TRUE;
			$result = $tmp_userdao->get_by_email($email);
			if ($result->result_count() > 0 && $result->recommend_user_id !== NULL) {
				$check_invite = FALSE;
			}
			
			if ($this->form_validation->valid_email($email) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('valid_email'), $this->lang->line('label_user_'.'email'))."\r\n";
			} elseif ($this->form_validation->max_length($email, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'email'), 128)."\r\n";
			} elseif ($check_email === TRUE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_unique_in_file'), $this->lang->line('label_user_'.'email'))."\r\n";
			} elseif ($check_registered === FALSE) {
				$count_error++;
				$error = $error."\t".$this->lang->line('is_unique_email_registered')."\r\n";
			} elseif ($check_request === FALSE) {
				$count_error++;
				$error = $error."\t".$this->lang->line('is_unique_email_request')."\r\n";
			} elseif ($check_invite === FALSE) {
				$count_error++;
				$error = $error."\t".$this->lang->line('is_unique_email_invite')."\r\n";
			} else {
				$arr_email[$i] = $email;
			}
			
			//check unique login_id in file
			$check_login_id = in_array($login_id, $arr_login_id);
			//check unique login_id in table tmp_users
			$check_unique_tmp_login_id = $this->form_validation->is_unique($login_id,'tmp_users.login_id');
			//check unique login_id in table users
			$check_unique_login_id = $this->form_validation->is_unique($login_id,'users.login_id');
			if ($this->form_validation->min_length($login_id, 6) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('min_length'), $this->lang->line('label_user_'.'login_id'), 6)."\r\n";
			} elseif ($this->form_validation->max_length($login_id, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'login_id'), 64)."\r\n";
			} elseif ($check_login_id === TRUE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_unique_in_file'), $this->lang->line('label_user_'.'login_id'))."\r\n";
			} elseif ($check_unique_tmp_login_id === FALSE && $check_unique_login_id == TRUE) {
				$count_error++;
				$error = $error."\t".$this->lang->line('is_unique_in_database')."\r\n";
			} elseif ($this->form_validation->alpha_numeric($login_id) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_user_'.'login_id'))."\r\n";
			} else {
				$arr_login_id[$i] = $login_id;
			}

			$last_name_ja = trim($tmp_objXLS->getCellByColumnAndRow(3 + $j, $i)->getValue());
			if ($last_name_ja == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_user_'.'last_name_ja'))."\r\n";
			} elseif ($this->form_validation->max_length($last_name_ja, 64) === FALSE)  {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'last_name_ja'), 64)."\r\n";
			}
			
			$first_name_ja = trim($tmp_objXLS->getCellByColumnAndRow(4 + $j, $i)->getValue());
			if ($first_name_ja == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_user_'.'first_name_ja'))."\r\n";
			} elseif ($this->form_validation->max_length($first_name_ja, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'first_name_ja'), 64)."\r\n";
			}
			
			
			$last_name = trim($tmp_objXLS->getCellByColumnAndRow(5 + $j, $i)->getValue());
			if ($last_name == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_user_'.'last_name'))."\r\n";
			} elseif ($this->form_validation->max_length($last_name, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'last_name'), 64)."\r\n";
			} elseif ($this->form_validation->alpha_numeric($last_name) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_user_'.'last_name'))."\r\n";
			}

			$first_name = trim($tmp_objXLS->getCellByColumnAndRow(6 + $j, $i)->getValue());
			if ($first_name == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_user_'.'first_name'))."\r\n";
			} elseif ($this->form_validation->max_length($first_name, 64) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'first_name'), 64)."\r\n";
			} elseif ($this->form_validation->alpha_numeric($first_name) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('alpha_numeric'), $this->lang->line('label_user_'.'first_name'))."\r\n";
			}

			$gender = $tmp_objXLS->getCellByColumnAndRow(7 + $j, $i)->getValue();
			if ($gender != "1" && $gender != "2") {
				$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'gender'))."\r\n";
			}

			$birthday = $tmp_objXLS->getCellByColumnAndRow(8 + $j, $i)->getValue();
			if ($this->form_validation->check_birthday($birthday) === FALSE) {
				$count_error++;
				$error = $error."\t".$this->lang->line('check_birthday')."\r\n";
			}

			$qualification_id = $tmp_objXLS->getCellByColumnAndRow(9 + $j, $i)->getValue();
			if ($qualification_id != "") {
				$check_quanlification = $qualification->find_by_id($qualification_id);
				if ($check_quanlification == 0 or is_numeric($qualification_id) == FALSE) {
					$count_error++;
					$error = $error."\t".$this->lang->line('qualification_id_is_exist')."\r\n";
				}
			}

			$phone_number = trim($tmp_objXLS->getCellByColumnAndRow(14 + $j, $i)->getValue());
			if ($phone_number != "") {
				if ($this->form_validation->is_valid_phone_number($phone_number) === FALSE) {
					$count_error++;
					$error = $error."\t".$this->lang->line('is_valid_phone_number')."\r\n";
				}
			}

			$auth_method = $tmp_objXLS->getCellByColumnAndRow(33 + $j, $i)->getValue();
			if ($auth_method != "") {
				if ($auth_method != strval(TYPE_AUTH_METHOD_IMAGE) && $auth_method != strval(TYPE_AUTH_METHOD_PHONE)) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'auth_method'))."\r\n";
				}
			}

			$confirm_phone_number = trim($tmp_objXLS->getCellByColumnAndRow(36 + $j, $i)->getValue());
			if ($confirm_phone_number != "") {
				if ($this->form_validation->is_valid_phone_number($confirm_phone_number) === FALSE) {
					$count_error++;
					$error = $error."\t".$this->lang->line('is_valid_phone_number')."\r\n";
				}
			}

			foreach($arr_max_length as $key=>$col) {
				$value = trim($tmp_objXLS->getCellByColumnAndRow($col + $j, $i)->getValue());
				if ($this->form_validation->max_length($value, 255) === FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.$key), 255)."\r\n";
				}
			}
			
			foreach($arr_flag as $key=>$col) {
				$value = trim($tmp_objXLS->getCellByColumnAndRow($col + $j, $i)->getValue());
				if ($value != "") {
					if ($value != $this::FLAG_PUBLIC && $value != $this::FLAG_NON_PUBLIC) {
						$count_error++;
						$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.$key))."\r\n";
					}
				}
			}

			$position = trim($tmp_objXLS->getCellByColumnAndRow(13 + $j, $i)->getValue());
			if ($this->form_validation->max_length($position, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'position'))."\r\n";
			}

			$dcf_code1 = trim($tmp_objXLS->getCellByColumnAndRow(37 + $j, $i)->getValue());
			if ($this->form_validation->max_length($dcf_code1, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'dcf_code1'))."\r\n";
			}

			$qlid1 = trim($tmp_objXLS->getCellByColumnAndRow(38 + $j, $i)->getValue());
			if ($this->form_validation->max_length($qlid1, 128) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_user_'.'qlid1'))."\r\n";
			}

			$recommend_user_id = trim($tmp_objXLS->getCellByColumnAndRow(56 + $j, $i)->getValue());
			if ($recommend_user_id != "") {
				if (is_numeric($recommend_user_id) == FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_user_'.'qlid1'))."\r\n";
				}
			}

			$registered_type = trim($tmp_objXLS->getCellByColumnAndRow(57 + $j, $i)->getValue());
			if ($registered_type != "") {
				if ($registered_type != strval(TYPE_REGIST_USER_ADMIN) && 
						$registered_type != strval(TYPE_REGIST_USER_RECOM) && $registered_type != strval(TYPE_REGIST_USER_GENERAL)) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'registered_type'))."\r\n";
				}
			}
			
			$registered_status = trim($tmp_objXLS->getCellByColumnAndRow(58 + $j, $i)->getValue());
			if ($registered_status != "") {
				if ($registered_status != strval(STATUS_REGIST_USER_PEND) && $registered_status != strval(STATUS_REGIST_USER_ACTIVE)
					&& $registered_status != strval(STATUS_REGIST_USER_DENIAL) && $registered_status != strval(STATUS_REGIST_USER_HOLD)) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'registered_status'))."\r\n";
				}
			}
			
			$registered_admin_id = trim($tmp_objXLS->getCellByColumnAndRow(59 + $j, $i)->getValue());
			if ($registered_admin_id != "") {
				if (is_numeric($registered_admin_id) == FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_user_'.'registered_admin_id'))."\r\n";
				}
			}
			
			$approvaled_admin_id = trim($tmp_objXLS->getCellByColumnAndRow(60 + $j, $i)->getValue());
			if ($approvaled_admin_id != "") {
				if (is_numeric($approvaled_admin_id) == FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_user_'.'approvaled_admin_id'))."\r\n";
				}
			}
			
			$listlanguage = config_item('forminfo')['common']['language'];
			$language = trim($tmp_objXLS->getCellByColumnAndRow(61 + $j, $i)->getValue());
			if ($language != '') {
				if ($language != $listlanguage['english']['id'] && $language != $listlanguage['japanese']['id']) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'language'))."\r\n";
				}
			}
		
			$approved_at = $tmp_objXLS->getCellByColumnAndRow(62 + $j, $i)->getValue();
			if ($approved_at != "") {
				if (strtotime($approved_at) == false) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'approved_at'))."\r\n";
				}
			}
			
			$password =trim($tmp_objXLS->getCellByColumnAndRow(2 + $j, $i)->getValue());
			$status = $tmp_objXLS->getCellByColumnAndRow(63 + $j, $i)->getValue();
			if ($status != strval(STATUS_DISABLE) && $status != strval(STATUS_ENABLE) && $status != $this->status_delete) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_user_'.'status'))."\r\n";
			} else {
				$msg_err = $this->form_validation->check_password($password);
				if ($check_unique_login_id === FALSE ) {
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
			
			$max_file_size = trim($tmp_objXLS->getCellByColumnAndRow(64 + $j, $i)->getValue());
			if ($max_file_size != '') {
				if (is_numeric($max_file_size) == FALSE) {
					$count_error++;
					$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_user_'.'max_file_size'))."\r\n";
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
				save_file(APPPATH.'tmp_files/error_upload_user'.session_id().'.txt', $msg_error);
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
		$title_template = $this->column_table_user;
		
		foreach ($cellIterator as $cell) {
			$title[] = $cell->getValue();
		}
		
		if ($title[0] == $this->lang->line('label_user_'.$title_template[0])) {
			$j = 0;
			$has_id = TRUE;
		} else if($title[0] == $this->lang->line('label_user_'.$title_template[1])) {
			$j = 1;
			$has_id = FALSE;
		} else {
			log_message('debug', sprintf("TITLE CHECK: %s = %s", $title[0], $this->lang->line('label_user_'.$title_template[0])));
			return array(FALSE, NULL);
		}

		for( $i = 0; $j < count($title_template) - 7; $i++, $j++) {
			if (trim($title[$i]) != $this->lang->line('label_user_'.$title_template[$j])) {
				log_message('debug', sprintf("TITLE CHECK: %s = %s", $title[$i], $this->lang->line('label_user_'.$title_template[$j])));
				return array(FALSE, NULL);
			}
 		}
		return array(TRUE,$has_id);
	}
	
	public function download_file_error() {
		$file_path = APPPATH.'tmp_files/error_upload_user'.session_id().'.txt';
		if (!file_exists($file_path)) {
			redirect($this->data[''].'admin_tools/user');
		}
		$this->load->helper('download');
		$data = file_get_contents($file_path); // Read the file's contents
		$file_name = 'error_upload_user-'.date('Y-m-d').'.txt';
		$output_file_name = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0) ? $file_name : urlencode($file_name);
		force_download($output_file_name, $data);
	}
}
