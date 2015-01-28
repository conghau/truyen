<?php
/**
*ファイル管理のコントローラ
*@name FileName
*@copyright (C)2014 Sevenmedia Inc
*@author FJN
*@version 1.0
*/
class FileAdmin extends MY_Controller{
	
	private $column_table_file = array('id' , 'file_id' ,'file_size' ,'original_file_name', 'file_extension' , 'parent_id' ,'file_info' , 'encryption_key'
							,'file_type', 'hash_value' , 'file_path' , 'small_thumbnail_path' , 'large_thumbnail_path'
							,'user_id' , 'expired_type' , 'expired_at' , 'created_at' , 'updated_at' , 'deleted_at' , 'status');
	CONST FILE_UPLOAD = 1;
	public function __construct(){
		parent::__construct();
		
		if(!isset($_SESSION)){
			session_start();
		}
		
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->library('excel');
		
		$list_expired_types = config_item('forminfo')['common']['files']['expired_types'];
		$this->data['expired_types'] = $list_expired_types;
		
		$list_status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $list_status_types;
		
		$list_file_types = config_item('forminfo')['common']['files']['file_types'];
		$this->data['file_types'] = $list_file_types;
		
		$this->data['cur_year'] = date('Y',time());
		
		$this->data['controller'] = 'file';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}
	
	/**
	 * ファイル覧画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case = 'search'){
		try{
			//Check user login
			$this->form_validation->check_login_admin();
			
			if (isset($_SESSION['file_info'])) {
				unset($_SESSION['file_info']);
			}
			if (isset($_SESSION['file_id'])) {
				unset($_SESSION['file_id']);
			}
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			if ($this->uri->segment(4) === 'post' ){
				$case = 'post';
			}
		
			$condition = array();
			if ($case === 'search') {
				$condition['id'] 				= $this->input->post('upload_id');
				$condition['user_id']			= $this->input->post('user_id');
				$condition['post_id']			= $this->input->post('post_id');
				$condition['expired_type']		= $this->input->post('expired_type');
				$condition['status']			= $this->input->post('status');
				$condition['created_date_start']= $this->input->post('created_date_start');
				$condition['created_date_end']	= $this->input->post('created_date_end');
				$condition['expired_date_start']= $this->input->post('expired_date_start');
				$condition['expired_date_end']	= $this->input->post('expired_date_end');
				$condition['per_page']			= $this->input->post('per_page');
				
				if ( trim($condition['created_date_start']) || trim($condition['created_date_end']) 
					|| trim($condition['expired_date_start']) || trim($condition['expired_date_end'])
					|| trim($condition['id']) || trim($condition['user_id']) || trim($condition['post_id'])) {
					$this->setup_validation_rules('file/search');
					$validation = $this->form_validation->run($this);
					if (!$validation) {
						$this->setValueItem($case);
						$this->data['has_data'] = FALSE;
						header_remove("Cache-Control");
						$this->parse('file_list.tpl','fileadmin/index');
						return;
					}
				}
			} else if ($case === 'post') {
				$condition['id'] 				= $this->input->post('upload_id');
				$condition['user_id']			= $this->input->post('user_id');
				$condition['post_id']			= $this->uri->segment(3);
				$condition['expired_type']		= $this->input->post('expired_type');
				$condition['status']			= $this->input->post('status');
				$condition['created_date_start']= $this->input->post('created_date_start');
				$condition['created_date_end']	= $this->input->post('created_date_end');
				$condition['expired_date_start']= $this->input->post('expired_date_start');
				$condition['expired_date_end']	= $this->input->post('expired_date_end');
				$condition['per_page']			= $this->input->post('per_page');
			} else {
				$conditionInfo 					= $_SESSION['condition'];
				$condition['id'] 				= $conditionInfo['upload_id'];
				$condition['user_id']			= $conditionInfo['user_id'];
				$condition['post_id']			= $conditionInfo['post_id'];
				$condition['expired_type']		= $conditionInfo['expired_type'];
				$condition['status']			= $conditionInfo['status'];
				$condition['created_date_start']= $conditionInfo['created_date_start'];
				$condition['created_date_end']	= $conditionInfo['created_date_end'];
				$condition['expired_date_start']= $conditionInfo['expired_date_start'];
				$condition['expired_date_end']	= $conditionInfo['expired_date_end'];
				$condition['per_page']			= $conditionInfo['per_page'];
			}
			$condition['file_type'] = $this::FILE_UPLOAD;
			
			$uploaddao = new UploadDao();
			
			$this->data['has_data'] = $uploaddao->has_data();
			
			$url = $this->data[''].'admin_tools/file/paginate';
			$uri_segment = 4;
			
			$total_record = $uploaddao->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_record,$condition,$url,$uri_segment);
			$result = $uploaddao->search($condition,$limit);
			$this->storeInSession($case);
			$this->setValueItem($case);
			$this->data['list_files'] = $result;
			
			header_remove("Cache-Control");
			$this->parse('file_list.tpl','fileadmin/index');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別 
	 */
	private function setValueItem($case) {
		if ($case === 'search') {
			// 検索の場合
			$this->data['upload_id'] 			= $this->input->post('upload_id');
			$this->data['user_id']				= $this->input->post('user_id');
			$this->data['post_id']				= $this->input->post('post_id');
			$this->data['expired_type']			= $this->input->post('expired_type');
			$this->data['status']				= $this->input->post('status');
			$this->data['created_date_start']	= $this->input->post('created_date_start');
			$this->data['created_date_end']		= $this->input->post('created_date_end');
			$this->data['expired_date_start']	= $this->input->post('expired_date_start');
			$this->data['expired_date_end']		= $this->input->post('expired_date_end');
			$this->data['per_page']				= $this->input->post('per_page');
		} else if ($case === 'post') {
			$this->data['upload_id'] 			= $this->input->post('upload_id');
			$this->data['user_id']				= $this->input->post('user_id');
			$this->data['post_id']				= $this->uri->segment(3);;
			$this->data['expired_type']			= $this->input->post('expired_type');
			$this->data['status']				= $this->input->post('status');
			$this->data['created_date_start']	= $this->input->post('created_date_start');
			$this->data['created_date_end']		= $this->input->post('created_date_end');
			$this->data['expired_date_start']	= $this->input->post('expired_date_start');
			$this->data['expired_date_end']		= $this->input->post('expired_date_end');
			$this->data['per_page']				= $this->input->post('per_page');
		} else {
			$conditionInfo = $_SESSION['condition'];
			$this->data['upload_id'] 			= $conditionInfo['upload_id'];
			$this->data['user_id']				= $conditionInfo['user_id'];
			$this->data['post_id']				= $conditionInfo['post_id'] ;
			$this->data['expired_type']			= $conditionInfo['expired_type'];
			$this->data['status']				= $conditionInfo['status'];
			$this->data['created_date_start']	= $conditionInfo['created_date_start'];
			$this->data['created_date_end']		= $conditionInfo['created_date_end'];
			$this->data['expired_date_start']	= $conditionInfo['expired_date_start'];
			$this->data['expired_date_end']		= $conditionInfo['expired_date_end'];
			$this->data['per_page']				= $conditionInfo['per_page'];
		} 
		
	}
	
	/**
	 * リクエストから検索条件を取得し、セッションにセットする。
	 * @param $case 機能種別
	 */
	private function storeInSession($case) {
		if ($case === 'search' or $case === 'post') {
			$post_id = ($case === 'post') ? $this->uri->segment(3) : $this->input->post('post_id');
			// セッションに検索条件を保存する
			$dataSession = array(
					'upload_id' 		=> $this->input->post('upload_id'),
					'user_id'			=> $this->input->post('user_id'),
					'post_id'			=> $post_id,
					'expired_type'		=> $this->input->post('expired_type'),
					'status'			=> $this->input->post('status'),
					'created_date_start'=> $this->input->post('created_date_start'),
					'created_date_end'	=> $this->input->post('created_date_end'),
					'expired_date_start'=> $this->input->post('expired_date_start'),
					'expired_date_end'	=> $this->input->post('expired_date_end'),
					'per_page'			=> $this->input->post('per_page'),
					'file_type'			=> $this::FILE_UPLOAD,
			);
			$_SESSION['condition'] = $dataSession;
		}
	}
	
	/**
	 * ファイル編集画面の表示処理
	 *  @param $id アップロード情報ID
	 */
	public function edit($id = null) {
		try {
			//Check user login
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$uploaddao = new UploadDao();
				$result = $uploaddao->get_by_id($id);
				
				if (NULL === $result->id) {
					redirect($this->data['']."admin_tools/file");
				}
				$_SESSION['file_id']  = $result->id;
				$_SESSION['expired']  = $result->expired_type;
				$this->data['file'] = $this->parse_data_from_query($result);
				$this->parse('file_edit.tpl','fileadmin/edit');
			} else {
				$result = $_SESSION['file_info'];
				$this->data['file'] = $result;
				$this->parse('file_edit.tpl','fileadmin/edit');
			}
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * ファイル編集確認画面の表示処理
	 * @param $id アップロード情報ID
	 */
	public function confirm_edit($id = null) {
		try {
			//Check user login
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET'){
				if (isset($_SESSION['file_info'])) {
					unset($_SESSION['file_info']);
				}
				redirect($this->data[''].'admin_tools/file');
			}
			$file_id = $_SESSION['file_id'];
			if ($file_id != $id) {
				redirect($this->data[''].'admin_tools/file');
			}
			$expired_type = $this->input->post('expired_type');
			if ($expired_type == EXPIRED_TYPE_INDEFINED || $expired_type == EXPIRED_TYPE_72_HOURS || $expired_type == EXPIRED_TYPE_1_YEAR ){
				$validation = TRUE;	
			} else {
				$this->setup_validation_rules('file/edit');
				$validation =$this->form_validation->run($this);
			}
			$file_info = $this->set_values_file_edit();
			
			if (TRUE === $validation) {
				$_SESSION['file_info'] = $file_info;
				$this->data['file'] = $file_info;
				$this->parse('file_confirm_edit.tpl','fileadmin/confirm_edit');
			} else {
				$this->data['file'] = $file_info;
				$this->parse('file_edit.tpl','fileadmin/edit');
			}
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * ファイル編集完了画面の表示処理
	 * @param $id アップロード情報ID
	 */
	public function update($id = null) {
		try {
			//Check user login
			$this->form_validation->check_login_admin(TYPE_AJAX);
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['file_info'])) {
					unset($_SESSION['file_info']);
				}
				redirect($this->data['']."admin_tools/file");
			}
			
			if (!isset($_SESSION['file_info'])) {
				redirect($this->data['']."admin_tools/file");
			}
			
			$expired_type = $_SESSION['expired'];
			$file_info = $_SESSION['file_info'];
			$new_expired_type = $file_info['expired_type'];
			
			$msgID = "";
				
			$uploaddao = new UploadDao(MASTER);
			
			if ($expired_type == EXPIRED_TYPE_1_YEAR && $new_expired_type == EXPIRED_TYPE_72_HOURS) {
				$result = $uploaddao->delete_recordset($id);
			} else {
				$data = $this->set_values_file_update($file_info);
				$result = $uploaddao->update_file($id,$data);
			}
			
			unset($_SESSION['file_info']);
			unset($_SESSION['expired']);
			unset($_SESSION['file_id']);
			
			if (!$result) {
				$msgID = 'L-A-0006-E';
			} else {
				$msgID = 'L-A-0005-I';
			}
			$message = $this->lang->line($msgID);
			$this->clear_csrf();
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * リクエストからデータを取得する。
	 */
	private function set_values_file_edit(){
		$data['upload_id']				= $this->input->post('upload_id');
		$data['user_id']				= $this->input->post('user_id');
		$data['post_id']				= $this->input->post('post_id');
		$data['file_name']				= $this->input->post('file_name');
		$data['file_type']				= $this->input->post('file_type');
		$data['file_size']				= $this->input->post('file_size');
		$data['year_created_date']		= $this->input->post('year_created_date');
		$data['month_created_date']		= $this->input->post('month_created_date');
		$data['day_created_date']		= $this->input->post('day_created_date');
		$data['hour_created_date']		= $this->input->post('hour_created_date');
		$data['min_created_date']		= $this->input->post('min_created_date');;
		$data['expired_type']			= $this->input->post('expired_type');
		$data['year_expired_date']		= $this->input->post('year_expired_date');
		$data['month_expired_date']		= $this->input->post('month_expired_date');
		$data['day_expired_date']		= $this->input->post('day_expired_date');
		$data['hour_expired_date']		= $this->input->post('hour_expired_date');
		$data['min_expired_date']		= $this->input->post('min_expired_date');
		$data['expired_date']			= $this->input->post('expired_date');
		$data['status']					= $this->input->post('status');
		return $data;
	}
	
	/**
	 * セッション情報からデータを取得する。
	 * @param $file_info セッションに保存されるファイル情報
	 */
	private function set_values_file_update($file_info) {
		$data['expired_type']			= $file_info['expired_type'];
		if ($data['expired_type'] == 0) {
			$data['year_expired_date']		= $file_info['year_expired_date'];
			$data['month_expired_date']		= $file_info['month_expired_date'];
			$data['day_expired_date']		= $file_info['day_expired_date'];
			$data['hour_expired_date']		= $file_info['hour_expired_date'];
			$data['min_expired_date']		= $file_info['min_expired_date'];
		}
		$data['status']					= $file_info['status'];
		return $data;
	}
	
	/**
	 * クエリからデータを取得する。
	 * @param $result_query　結果照会
	 */
	private function parse_data_from_query($result_query) {
		$data['upload_id'] 			= $result_query->id;
		$data['file_id'] 			= $result_query->file_id;
		$data['user_id']			= $result_query->user_id;
		$data['post_id']			= $result_query->post_id;
		$data['expired_type']		= $result_query->expired_type;
		$data['file_name']			= $result_query->original_file_name;
		$data['file_type']			= $result_query->file_type;
		$data['file_size']			= $result_query->file_size;
		$data['year_created_date']	= date('Y',strtotime($result_query->created_at));
		$data['month_created_date']	= date('m',strtotime($result_query->created_at));
		$data['day_created_date']	= date('d',strtotime($result_query->created_at));
		$data['hour_created_date']	= date('H',strtotime($result_query->created_at));
		$data['min_created_date']	= date('i',strtotime($result_query->created_at));
		$data['year_expired_date']	= date('Y',strtotime($result_query->expired_at));
		$data['month_expired_date'] = date('m',strtotime($result_query->expired_at));
		$data['day_expired_date']	= date('d',strtotime($result_query->expired_at));
		$data['hour_expired_date']	= date('H',strtotime($result_query->expired_at));
		$data['min_expired_date']	= date('i',strtotime($result_query->expired_at));
		$data['status']				= $result_query->status;
		return $data;
	}
	
	/**
	 * 全体のエクスポート処理
	 */
	public function export_all() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			$encoding = $this->input->get('encoding');
			$output_file_name = $this->lang->line('label_upload_export_all_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			
			$condition = array();
			$condition['file_type'] = $this::FILE_UPLOAD;
			$uploaddao = new UploadDao();
			$result = $uploaddao->search($condition);
			
			$list_column_table = $this->column_table_file;
			
			$this->process_export($result,$output_file_name, $list_column_table, 'label_upload_', $encoding);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * 検索結果のエクスポート処理
	 */
	public function export_search_result() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			$encoding = $this->input->get('encoding');
			$condition = $_SESSION['condition'];
			$output_file_name = $this->lang->line('label_upload_export_search_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			
			$uploaddao = new UploadDao();
			$result = $uploaddao->search($condition);
			
			$list_column_table = $this->column_table_file;
			$this->process_export($result,$output_file_name, $list_column_table, 'label_upload_', $encoding);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * ファイル一括ダウンロード
	 * @param $id ファイルID
	 */
	public function get_and_zip_file($post_id) {
		try {
			//ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				set_status_header(401);
			}
			ignore_user_abort(true);
			set_time_limit(600);
			$uploaddao = new UploadDao();
			list($files, $uploads_id) = $this->get_file_by_post_id($uploaddao,$post_id);
			if(empty($files)){
				set_status_header(500);
				exit;
			}
			$tmp_folder_name = session_id().'/';
			$result = $this->process_zip($files, $tmp_folder_name);
			if($result) {
				$_SESSION['dl_admin_post_id'] = $post_id;
				$_SESSION['dl_admin_uploads_id'] = $uploads_id;
				set_status_header(200);
				exit;
			} else {
				set_status_header(500);
				exit;
			}
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	public function batch_download($post_id){
		try {
			$this->form_validation->check_login_admin();
			$tmp_folder_name = session_id().'/';
			
			if(!isset($_SESSION['dl_admin_post_id'])){
				redirect($this->data[''].'admin_tools');
				exit;
			}
			if($_SESSION['dl_admin_post_id'] != $post_id) {
				redirect($this->data[''].'admin_tools');
				exit;
			}
			$file_path = PATH_TMP_FILE_ZIP.$tmp_folder_name.session_id().OUTPUT_FILE_TYPE_ZIP;
			$uploads_id = $_SESSION['dl_admin_uploads_id'] ;
			
			$postdao = new PostDao();
			$user = $postdao->get_user_by_post_id($post_id);
			$date_create = date("YmdHis", strtotime($user->created_at));
			$file_download_name = $user->last_name.$user->first_name.'_('. $post_id . ')'.$date_create;

			unset($_SESSION['tmp_file_zip_dl']);
			unset($_SESSION['dl_admin_uploads_id']);
			
			header('X-Content-Type-Options: nosniff');
			header('Content-type: application/zip');
			header('Content-disposition: attachment; filename='.$file_download_name.OUTPUT_FILE_TYPE_ZIP);
			header("Pragma: no-cache");
			header("Expires: 0");
			ob_clean();
			flush();
			readfile($file_path);
			$this->delete_file_in_folder(PATH_TMP_FILE_ZIP.$tmp_folder_name,TRUE);
			exit;
		} catch (Exception $e) {
			log_message('error_download',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * zip圧縮とファイルダウンロー
	 * @param $files ファイル一覧
	 * @param $file_download_name ファイルアップロード名
	 */
	private  function process_zip($files, $tmp_folder_name) {
		$result = FALSE;
		try{
			$this->delete_file_in_folder(PATH_TMP_FILE_ZIP.$tmp_folder_name,FALSE);
				
			if (!is_dir(PATH_TMP_FILE_ZIP.$tmp_folder_name)) {
				mkdir(PATH_TMP_FILE_ZIP.$tmp_folder_name,0777,TRUE);
				chmod(PATH_TMP_FILE_ZIP.$tmp_folder_name, 0777);
			}

			$arr_name = array();
			foreach($files as $file) {
				$file_path =$file['file_path'];
				$pass = $file['pass'];
				$temp_decrypt_file = PATH_TMP_FILE_ZIP.$tmp_folder_name.'tmp_file'.$file['file_extension'];
				$output_file = PATH_TMP_FILE_ZIP.$tmp_folder_name.$file['file_name'];
				
				if (in_array($output_file, $arr_name)) {
					$file_extension = '.'.$file['file_extension'];
					$index = strrpos($output_file, $file_extension);
					$i = 1;
					$name = substr($output_file, 0, $index);
					while(true) {
						$output_file = $name.'('.$i.')'.$file_extension;
						if (in_array($output_file, $arr_name)){
							$i++;
						} else {
							break;
						}
					}
				}
				array_push($arr_name, $output_file);
				
				$cmd = sprintf(FILE_DECODER, $pass, $file_path, $temp_decrypt_file);
				system($cmd);
				rename($temp_decrypt_file,$output_file);
			}
			
			$path_folder = PATH_TMP_FILE_ZIP.session_id();
			$zip_name = session_id().OUTPUT_FILE_TYPE_ZIP;
			$cmd = sprintf(CMD_ZIP_CODE,$path_folder,$zip_name,'*');
			system($cmd);
			
			$result = TRUE;
		} catch (Exception $e) {
			log_message('error_zip', $e->getMessage());
			show_error($e->getMessage());
			return $result;
		}
		return $result;
	}
	
	/**
	 * ファイル一覧を投稿IDによって取得する。
	 * @param $id 投稿ID
	 */
	private function get_file_by_post_id($uploaddao,$post_id = null) {
		$uploads = $uploaddao->get_by_post_id($post_id);
		$files = array();
		$uploads_id = array();
		$now = new DateTime();
		foreach ($uploads as $upload) {
			$expired_at = $this->get_expired_at($upload);
			if ($expired_at == null || $expired_at > $now) {
				$file['file_path'] = $upload->file_path;
				$file['file_name'] = $upload->original_file_name;
				$file['file_extension'] = $upload->file_extension;
				$file['pass'] = $upload->encryption_key;
				if(file_exists($upload->file_path)) {
					array_push($files, $file);
					array_push($uploads_id, $upload->id);
				}
			}
		}
		return array($files, $uploads_id);
	}
	/**
	 * アップロード情報IDで有効期限を取得する。
	 * @param $upload アップロード情報ID
	 */
	private function get_expired_at($upload) {
		# Calculate expired time and chose the smallest one
		if ($upload->expired_type != EXPIRED_TYPE_INDEFINED && $upload->expired_at) {
			$expired_at = new DateTime($upload->expired_at);
		} else { # Unlimited time
			$expired_at = null;
		}
		return $expired_at;
	}
	
	/**
	 * Process delete file in folder
	 * @param string $path_folder
	 * @param boolean $is_delete_folder
	 */
	private function delete_file_in_folder($path_folder, $is_delete_folder = FALSE){
		if (is_dir($path_folder)) {
			array_map('unlink', glob($path_folder.'*'));
		}
		if($is_delete_folder) {
			rmdir($path_folder);
		}
	}
	
	/**
	 * ファイル一覧を表示する。
	 */
	public function file_list($id ='') {
		try {
			//ログインチェック処理
			if (!isset($this->data['admin'])){
				$this->data['auth'] = false;
			} else {
				$this->data['auth'] = true;
				$uploaddao = new UploadDao();
				$files_tmp = $uploaddao->get_by_post_id($id);

				$files = array();
				$now = new DateTime();
				foreach ($files_tmp as $file_tmp) {
					$expired_at = $this->get_expired_at($file_tmp);
					if ($expired_at == null || $expired_at > $now) {
						$file = array();
						$file['file_size'] = $file_tmp->file_size;
						$file['file_name'] = $file_tmp->original_file_name;
						if(file_exists($file_tmp->file_path)) {
							$file['file_id'] = $file_tmp->file_id;
						} else {
							$file['file_id'] = 0;
						}
						array_push($files, $file);
					}
				}
				$this->data['files'] = $files;
			}
			$this->parse('filemodal.tpl','fileadmin/filelist');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ダウンロードを行う。
	 * @param integer $file_id
	 */
	public function download($file_id = null) {
		$this->form_validation->check_login_admin();
		$this->process_download($file_id);
	}
	
	/**
	 * ファイル個別 ダウンロード
	 * @param integer $file_id
	 */
	public function download_file_list($file_id = null) {
		try {
			//ログインチェック処理
			if (isset($this->data['admin'])){
				$this->process_download($file_id);
			}
			$this->data['auth'] = false;
			$this->parse('filemodal.tpl','fileadmin/filelist');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function process_download($file_id) {
		$upload = new UploadDao();
		$dto = $upload->find_by_file_id($file_id);
		// log_message('debug', $dao->check_last_query(false, true));
		if ($dto === NULL) {
			set_status_header(404);
			return;
		}
		// ファイル情報を調整
		$file_extension = $dto->file_extension;
		$pass = $dto->encryption_key;
		$file_name = $dto->original_file_name;
		$file_path = $dto->file_path;
		$file_size = $dto->file_size;
	
		if (empty($file_name)) {
			$file_name = $file_id . '.' . $file_extension;
		}
		if (!file_exists($file_path)) {
			log_message('debug', 'File not found: ' . $file_path);
			set_status_header(404);
			return;
		}
		// Prevent browsers from MIME-sniffing the content-type:
		//Download
		$output_file_name = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0) ? $file_name : urlencode($file_name);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $output_file_name . '"');
		
		if ($file_size > 0) {
			header('Content-Length: ' . $file_size);
		}
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($file_path)));
		log_message('debug', 'Download header out: ' . $file_size . 'byte');
		$cmd = sprintf(FILE_DECODER_DIRECT, $pass, $file_path);
		log_message('debug', 'FILE DECODER: ' . $cmd);
		system($cmd);
	}
}
