<?php
/**
 * @name ファイルログ管理のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class FileLog extends  MY_Controller {
	private $expired_types;
	private $file_types;
	private $columns_table_activity_file = array('created_at', 'user_id', 'id', 'post_id',
										'expired_type', 'file_info', 'file_type', 'file_size', 'totalDL');
	
	public function __construct() {
		parent::__construct();
		
		if(!isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->library('excel');
		
		$this->expired_types = config_item('forminfo')['common']['activity']['expired_type'];
		$this->data['expired_types'] = $this->expired_types;
		
		$this->file_types = config_item('forminfo')['common']['activity']['file_types'];
		$this->data['file_types'] = $this->file_types;
		
		$gender_types = config_item('forminfo')['common']['profile']['gender_types'];
		$this->data['gender_types'] = $gender_types;
		
		$status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $status_types;
		$this->data['controller'] = 'filelog';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}
	
	/**
	 *  検索画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case ='search') {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
	
			$userdao = new UserDao();
			$qualificationdao = new QualificationDao();
				
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			$this->data['list_registered_type'] = config_item('forminfo')['common']['registered_type'];
			
			$condition = array();
			$validation = TRUE;
			
			$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender',
					'qualification_id','organization','phone_number','registered_type','status','start_date','end_date','per_page');
			if ($case === 'search') {
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				if ($condition['start_date'] 
					|| $condition['end_date'] || $condition['id'] ){
					$this->setup_validation_rules('user/search');
					$validation = $this->form_validation->run($this);
				}
				$this->session->set_userdata('condition', $condition);
			} elseif ($case === 'paginate') {
				$condition = $this->session->userdata('condition');
			}
			if ($validation === TRUE) {
				// 改ページ作成を実行
				$url = $this->data[''] . "admin_tools/filelog/paginate";
				$uri_segment = 4;
				$total_records = $userdao->count_by_condition($condition);
				$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
				$result = $userdao->search($condition,$limit);
				$this->data['list_users'] = $result;
			}
			
			$this->set_value_item($case);
			header_remove("Cache-Control");
			$this->parse('file_log.tpl', 'filelog/index');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ファイル別ログ／集計画面の表示処理
	 * @param $user_id ユーザーＩＤ
	 * @param $case 機能種別
	 */
	public function file_log($user_id, $case="search") {
		try {
			// Check login
			$this->form_validation->check_login_admin();
			
			$path_paginate = "admin_tools/filelog/{$user_id}/detail/paginate";
			$paginate_offset = substr_count($path_paginate, "/") + 1;
			// Identify case
			if ($this->uri->segment($paginate_offset) === 'paginate') {
				$case = 'paginate';
			}
	
			$validation = TRUE;
			$condition = array();
			if ($case === 'search') {
				$condition['user_id']	= $user_id;
				$condition['created_date_start'] = $this->input->post('created_date_start');
				$condition['created_date_end'] = $this->input->post('created_date_end');
				$condition['per_page']	= $this->input->post('per_page');
				$condition['deleted_at'] = TRUE;
				$condition['file_type'] = 1;
				$this->setup_validation_rules('filelog/search');
				if ($condition['created_date_start'] || $condition['created_date_end']) {
					$validation = $this->form_validation->run($this);
				}
				$this->session->set_userdata('file_log_detail', $condition);
			} elseif ($case === 'paginate') {
				$condition = $this->session->userdata['file_log_detail'];
			}
						
			if ($validation === TRUE) {
				$uploaddao = new UploadDao();
				
				// Pagination
				$url = $this->data[''] . $path_paginate;
				$uri_segment = $paginate_offset + 1;
				$total_records = $uploaddao->count_by_condition($condition);
				$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
				$result = $uploaddao->search($condition, $limit);
				
				if ($result->result_count() > 0) {
					$this->data['uploads'] = $this->get_list_file_log($result);
				}
			}
			
			// Store value for view
			$this->data['created_date_start'] = $condition['created_date_start'];
			$this->data['created_date_end'] = $condition['created_date_end'];
			$this->data['user_id'] = $condition['user_id'];
			$this->data['per_page'] = $condition['per_page'];
			
			header_remove("Cache-Control");
			$this->parse('file_log_detail.tpl','filelog/file_log_detail');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 検索結果のエクスポート処理
	 */
	public function export_search_file_log() {
		try {
			// Check login
			$this->form_validation->check_login_admin();
				
			if (FALSE === $this->session->userdata('file_log_detail')) {
				redirect($this->data[''] . "admin_tools/filelog");
			}
			$conditionSearch = $this->session->userdata('file_log_detail');
			$output_file_name = $this->lang->line('label_file_log_export_file_name') . '.' . OUTPUT_FILE_TYPE_TSV;
			$list_column_table = $this->columns_table_activity_file;
				
			$uploaddao = new UploadDao();
				
			$result = $uploaddao->search($conditionSearch);
				
			if ($result->result_count() > 0) {
				$result_search = $this->get_list_file_log($result);
			}
				
			$this->display_detail_file_log($result_search);
			
			$encoding = $this->input->get('encoding');
			$this->process_export($result_search, $output_file_name, $list_column_table, 'label_activity_upload_', $encoding);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 集計データの作成処理
	 * @param  $files ファイル一覧
	 */
	private function get_list_file_log($files) {
		$files = $this->parse_data_from_query($files);
		$file_ids = $this->get_file_ids($files);
		$file_download = $this->get_file_download_matrix($file_ids);
	
		$data = array();
		$file_logs = array();
	
		foreach ($files as $file) {
			if (array_key_exists($file['id'], $file_download)) {
				$data['totalDL'] = $file_download[$file['id']];
			} else {
				$data['totalDL'] = '';
			}
			$data['created_at'] = $file['created_at'];
			$data['user_id'] = $file['user_id'];
			$data['id'] = $file['id'];
			$data['post_id'] = $file['post_id'];
			$data['file_name'] = $file['original_file_name'];
			$data['file_extension'] = $file['file_extension'];
			$data['expired_type'] = $file['expired_type'];
			$data['file_size'] = $file['file_size'];
				
			array_push($file_logs, $data);
		}
		return $file_logs;
	}
	
	/**
	 * queryからarrayにデータをパースする。
	 * @param $result_query
	 * @return array
	 */
	private function parse_data_from_query($result_query) {
		$files = array();
		foreach ($result_query as $row) {
			$data['created_at'] = $row->created_at;
			$data['user_id'] = $row->user_id;
			$data['id'] = $row->id;
			$data['post_id'] = $row->post_id;
			$data['original_file_name'] = $row->original_file_name;
			$data['file_extension'] = $row->file_extension;
			$data['expired_type'] = $row->expired_type;
			$data['file_size'] = $row->file_size;
			array_push($files, $data);
		}
		return $files;
	}
	
	/**
	 * ファイルID一覧を作成する処理
	 * @param $files ファイル一覧
	 * @return $file_ids  ファイルID一覧
	 */
	private function get_file_ids($files) {
		$file_ids = array();
		foreach ($files as $file) {
			array_push($file_ids,$file['id']);
		}
		return $file_ids;
	}
	
	/**
	 * DBから統計情報データを取得する処理
	 * @param $file_ids ファイルID一覧
	 */
	private function get_file_download_matrix($file_ids) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_file_download_matrix($file_ids);
		return $result;
	}
	
	private function set_value_option_qualification($qualificationdao) {
		$result = $qualificationdao->get_list();
		$data = array();
		$count =0;
		foreach ($result as $item) {
			$data[$count]['id'] = $item->id;
			$data[$count]['name'] = $item->name;
			$count ++;
		}
		return $data;
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item($case) {
		$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender',
				'qualification_id','organization','phone_number','registered_type','status','start_date','end_date','per_page');
		if ($case === 'search') {
			// 検索の場合
			foreach ($targets as $target) {
				$this->data[$target] = $this->input->post($target);
			}
		} else if ($case === 'paginate') {
			$conditionInfo = $this->session->userdata('condition');
			foreach ($targets as $target) {
				$this->data[$target] = $conditionInfo[$target];
			}
		}
	}
	
	private function display_detail_file_log(&$result_search) {
		if (!is_callable('smarty_modifier_file_size_format')) {
			include APPPATH . 'third_party/Smarty/plugins/modifier.file_size_format.php';
		}
		
		foreach ($result_search as &$row){
			$row['expired_type'] = $this->lang->line($this->get_expired_type($row['expired_type']));
			$row['file_type'] = strtoupper($row['file_extension']);
			$row['file_info'] = $row['file_name'];
			$row['file_size'] =  smarty_modifier_file_size_format($row['file_size'], 0);
		}
	}
	
	private function get_expired_type($type_id) {
		foreach ($this->expired_types as $type) {
			if ($type_id == $type['id']) {
				return $type['label'];
			}
		}
	}
	
	private function get_file_type($type_id) {
		foreach ($this->file_types as $type) {
			if ($type_id == $type['id']) {
				return $type['label'];
			}
		}
	}

}
