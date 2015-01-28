<?php
/**
 *入退会ログ管理のコントローラ
 *@name FileName
 *@copyright (C)2014 Sevenmedia Inc
 *@author FJN
 *@version 1.0
 */
class EntryLog extends MY_Controller {
	
	private $column_table_entry_detail = array('date','num_user','num_change','num_joined','num_leaved','num_temp');
	private $column_table_entry = array('created_date','id','email','last_name_ja',
										'first_name_ja','organization','qualification','company_code','status','summary_id');
	public function __construct() {
		parent::__construct();
		
		if (! isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->library('excel');
		
		$listgendertypes = config_item('forminfo')['common']['profile']['gender_types'];
		$this->data['gender_types'] = $listgendertypes;
		
		$list_status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $list_status_types;
		
		$this->data['controller'] = 'entrylog';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}
	
	/**
	 * 入退会ログ一覧画面の表示処理
	 * @param $case 機能種別
	 */
	public function index( $case = 'search') {
		try {
			$this->form_validation->check_login_admin();
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			$condition = array();
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			
			$userdao = new UserDao();
			$aldao = new ActivityLogDao();
			$has_data = $aldao->find_list_by_key(ActivityLogDao::CATEGORY_USER,array(),0) ? true : false;
			if ($case === 'search') {
				$condition['qualification_id'] = $this->input->post('qualification');
				$targets = array('gender','company_code','registered_type','status','start_date','end_date','per_page');
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				if (trim($condition['start_date']) !== '' || trim($condition['end_date']) !== '') {
					$this->setup_validation_rules('entrylog/search');
					$validation = $this->form_validation->run($this);
					if ( ! $validation) {
						$this->setValueItem($case);
						$this->data['total_records'] = 0;
						$this->data['has_data']	= $has_data;
						$this->data['format_tsv'] = config_item('forminfo')['common']['format_export_tsv'];
						$this->parse('entry_log.tpl','entrylog/index');
						return;
					}
				}
				$this->session->set_userdata('condition', $condition);
			} else {
				$conditionInfo = $this->session->userdata('condition');
				$targets = array('qualification_id','gender','company_code','registered_type','status','start_date','end_date','per_page');
				foreach ($targets as $target) {
					$condition[$target] = isset($conditionInfo[$target]) ? $conditionInfo[$target] : null ;
				}
			}
			
			$url = $this->data[''].'admin_tools/entrylog/paginate';
			$uri_segment = 4;
		
			$total_records = count($userdao->get_user_log_entry($condition));
			$limit = $this->create_pagination($total_records, $condition, $url, $uri_segment);
			$result = $userdao->get_user_log_entry($condition,$limit);
			
			$this->setValueItem($case);
			
			$this->session->set_userdata('flag', TRUE);
			$this->data['has_data']	= $has_data;
			$this->data['list_users'] = $this->displayList($result);
			$this->parse('entry_log.tpl','entrylog/index');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 *  入隊会ログの詳細画面の表示処理
	 */
	public function entry_log() {
		try {
			$this->form_validation->check_login_admin();
			
			// 機能種別を指定する。
			if ($this->uri->segment(3) === 'detail_all') {
				$case = 'detail_all';
			} else if ($this->uri->segment(3) === 'detail_search') {
				$case = 'detail_search';
			}
			
			if ($this->uri->segment(4) === 'paginate') {
				$case = 'paginate';
			}
			
			$condition = array();
			$qualificationdao = new QualificationDao();
			$this->data['list_qualification'] = $this->set_value_option_qualification($qualificationdao);
			
			if ($case == 'detail_all') {
				$targets = array('company_code', 'year_month', 'per_page');
				$condition['qualification_id'] = $this->input->post('qualification');
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				$this->session->set_userdata('condition_detail', $condition);
			} elseif ($case == 'detail_search') {
				if ($this->session->userdata('condition') == false ) {
					redirect($this->data[''].'admin_tools/entrylog/');
				}
				$condition = $this->session->userdata('condition');
				$per_page	= $this->input->post('per_page');
				$company_code = $this->input->post('company_code');
				$qualification = $this->input->post('qualification');
				$year_month = $this->input->post('year_month');
				$flag = $this->session->userdata('flag');
				if ($company_code || !$flag) {
					$condition['company_code'] = $company_code;
				}
				if ($qualification || !$flag) {
					$condition['qualification_id'] = $qualification;
				}
				if ($year_month || !$flag) {
					$condition['year_month'] = $year_month;
				}
				if ($per_page || !$flag) {
					$condition['per_page'] = $per_page;
				}
				$this->session->set_userdata('condition_detail', $condition);
			}
			elseif ($case == 'paginate') {
				$targets = array('qualification_id','gender','company_code','year_month','registered_type','status','start_date','end_date','per_page');
				$conditionInfo = $this->session->userdata('condition_detail');
				foreach ($targets as $target) {
					$condition[$target] = isset($conditionInfo[$target]) ? $conditionInfo[$target] : null ;
				}
			}
			
			if ($this->uri->segment(3) === 'detail_all') {
				$this->data['case'] = 'detail_all';
				$url = $this->data[''].'admin_tools/entrylog/detail_all/paginate';
			} elseif ($this->uri->segment(3) === 'detail_search') {
				$this->data['case'] = 'detail_search';
				$url = $this->data[''].'admin_tools/entrylog/detail_search/paginate';
			}
			$uri_segment = 5;
			$userdao = new UserDao();

			// 年月リストの作成
			$ym_list = $userdao->get_user_log_year_month_list();
			$this->data['list_yearmonth'] = $ym_list;

			// 対象の集計値を取得
			$stats = $userdao->get_user_log_entry_detail($this->convert_condition($condition), 'all');
			$this->data['list_stats'] = $stats;
			
			// 全取得
			$result = $userdao->get_user_log_entry_detail($condition);
			$count = count($result);

			// 配列に対するページング処理
			$limit = $this->create_pagination_admin($count, $condition, $url, $uri_segment);
			$this->data['list_log'] = $this->displayDetail($result, $count, $limit);
			
			// リクエストに関する調整
			$this->set_value_entry_log($case);			// この中で月次に関する処理を実施
			
			header_remove("Cache-Control");
			$this->parse('entry_log_detail.tpl','entrylog/detail');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	private function set_value_entry_log($case) {
 		if ($case === 'detail_all' || $case === 'detail_search') {
 				$flag = $this->session->userdata('flag');
 				$targets = array('qualification', 'company_code', 'year_month', 'per_page');
 				foreach ($targets as $target) {
 					$this->data[$target] = $this->input->post($target);
 				}
 				if ($flag == TRUE) {
 					$conditionInfo = $this->session->userdata('condition_detail');
 					$this->data['qualification']		= isset($conditionInfo['qualification_id']) ? $conditionInfo['qualification_id'] : null ;
 					$this->data['company_code']			= isset($conditionInfo['company_code']) ? $conditionInfo['company_code'] : null;
 					$this->data['year_month']			= isset($conditionInfo['year_month']) ? $conditionInfo['year_month'] : null;
 					$this->data['per_page']				= isset($conditionInfo['per_page']) ? $conditionInfo['per_page'] : null;
 					$this->session->set_userdata('flag', FALSE);
 				}
 		} else if ($case === 'paginate'){
			$conditionInfo = $this->session->userdata('condition_detail');
			$this->data['qualification']		= isset($conditionInfo['qualification_id']) ? $conditionInfo['qualification_id'] : null ;
			$this->data['company_code']			= isset($conditionInfo['company_code']) ? $conditionInfo['company_code'] : null;
			$this->data['year_month']			= isset($conditionInfo['year_month']) ? $conditionInfo['year_month'] : null;
			$this->data['per_page']				= isset($conditionInfo['per_page']) ? $conditionInfo['per_page'] : null;
 		}
		$this->setup_year_month_list();
	}
	
	private function setup_year_month_list() {
		if (!isset($this->data['year_month']) || empty($this->data['year_month'])) {
			return;
		}
		$this->data['list_log'] = $this->convert_year_month_list($this->data['year_month'], $this->data['list_log'], $this->data['list_stats']);
		$this->data['total_records'] = count($this->data['list_log']);
	}
	
	private function convert_year_month_list($year_month, $result, $stats) {
		if (!empty($result) && is_array($result)) {
			$temp = array_indexing_by_key('created_at', $result);
		} else {
			$temp = array();
		}
		$new_list = array();
		$start_date = $year_month."-01";
		$end_date = end_of_month($start_date);
		$prev_date = prev_date($start_date);
//		log_message('debug', var_export($temp, true));
		$prev_num_user = 0;
		if ($stats) {
			$tgt_stats = array_shift($stats);
			$prev_num_user = array_selector('num_joined', $tgt_stats, 0) + array_selector('num_temp', $tgt_stats, 0) - array_selector('num_leaved', $tgt_stats, 0);
		}
		for ($xi = date2time($start_date) ; $xi <= date2time($end_date); $xi += 86400) {
			$tgt_date = time2date($xi);
			$tgt_date_ja = date('Y'.$this->lang->line('label_year').'m'.$this->lang->line('label_month').'d'.$this->lang->line('label_day'), $xi);
			if (array_key_exists($tgt_date, $temp)) {
				$target_recordset = $temp[$tgt_date];
				$target_recordset['num_user'] = $prev_num_user + $target_recordset['num_change'];
				$new_list[] = $target_recordset;
				$prev_num_user = $target_recordset['num_user'];
			} else {
				$new_list[] = array(
				    'num_joined' => '0',
				    'num_leaved' => '0',
				    'num_temp' => '0',
				    'created_at' => $tgt_date,
				    'date' => $tgt_date_ja,
				    'num_user' => $prev_num_user,
				    'num_change' => 0,
				);
			}
		}
		return $new_list;
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function setValueItem($case) {
		$targets = array('gender','company_code','registered_type','status','start_date','end_date','per_page');
		if ($case === 'search') {
			$this->data['qualification'] = $this->input->post('qualification');
			// 検索の場合
			foreach ($targets as $target) {
				$this->data[$target] = $this->input->post($target);
			}
		} else {
			$conditionInfo = $this->session->userdata('condition');
			$this->data['qualification'] = isset($conditionInfo['qualification_id']) ? $conditionInfo['qualification_id'] : null ;
			foreach ($targets as $target) {
				$this->data[$target] = isset($conditionInfo[$target]) ? $conditionInfo[$target] : null ;
			}
		}
	}
	
	/**
	 * 表示フォーマットメソッド
	 * @param $result 検索結果一覧
	 */
	private function displayList($result) {
		$count = count($result);
		$result_array = array();
		if ($count > 0) {
			for($index = 0 ; $index < $count ; $index ++) {
				$tmp_array = (array)$result[$index];
				$tmp_array['created_date'] = $tmp_array['created_at'];
				
				if ($tmp_array['summary_id'] == ActivityLogDao::USER_JOIN) {
					$tmp_array['summary_id'] = $this->lang->line('label_entry_joined');
				} elseif ($tmp_array['summary_id'] == ActivityLogDao::USER_JOIN) {
					$tmp_array['summary_id'] = $this->lang->line('label_entry_left');
				} else {
					$tmp_array['summary_id'] = $this->lang->line('label_entry_temp');
				}
				array_push($result_array, $tmp_array);
			}
		};
		return $result_array;
	}
	
	/**
	 * 代表資格・職種リストの作成処理
	 * @param $qualificationdao 職種情報
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
	 * 入退会ログ全体のエクスポート処理
	 */
	public function export_all() {
		try {
			$this->form_validation->check_login_admin();
			$list_column_table = $this->column_table_entry;
			$output_file_name = $this->lang->line('label_export_entry_all').'.'.OUTPUT_FILE_TYPE_TSV;
			
			$condition = array();
			$userdao = new UserDao();
			
			$result = $userdao->get_user_log_entry($condition);
			$result1 = $this->displayList($result);
			$encoding = $this->input->get('encoding');
			$this->process_export($result1, $output_file_name, $list_column_table, 'label_entry_', $encoding);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * 入退会ログのエクスポート処理
	 */
	public function export_search() {
		try {
			$this->form_validation->check_login_admin();
			if ($this->session->userdata('condition') == false) {
				return;
			}
			$condition = $this->session->userdata('condition');
			$list_column_table = $this->column_table_entry;
			
			$output_file_name = $this->lang->line('label_export_entry_search').'.'.OUTPUT_FILE_TYPE_TSV;
			
			$userdao = new UserDao();
			$result = $userdao->get_user_log_entry($condition);
			$result = $this->displayList($result);
			$encoding = $this->input->get('encoding');
			$this->process_export($result, $output_file_name, $list_column_table, 'label_entry_', $encoding);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage);
		}
	}
	
	/**
	 * 入退会ログのエクスポート処理
	 */
	public function export_detail() {
		try {
			$this->form_validation->check_login_admin();
 			if ($this->session->userdata('condition_detail') == false){
 				return;
 			}
			$list_column_table = $this->column_table_entry_detail;
			$output_file_name = $this->lang->line('label_export_entry_detail').'.'.OUTPUT_FILE_TYPE_TSV;
			$condition = $this->session->userdata('condition_detail');
			$userdao = new Userdao();
			$stats = $userdao->get_user_log_entry_detail($this->convert_condition($condition), 'all');
			$result = $userdao->get_user_log_entry_detail($condition);
			$result = $this->display_export_detail($result);
			if (isset($condition['year_month'])) {
				$result = $this->convert_year_month_list($condition['year_month'], $result, $stats);
			}
			$encoding = $this->input->get('encoding');
			$this->process_export($result,$output_file_name, $list_column_table, 'label_entry_',$encoding);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}

	private function convert_condition($condition) {
		$new_condition = $condition;
		if (isset($new_condition['year_month'])) {
			$new_condition['end_date'] = prev_date($new_condition['year_month']."/01");
			unset($new_condition['year_month']);
		}
		return $new_condition;
	}
	
	/**
	 * データのエクスポート処理
	 * @param $result_query
	 * @param $list_column_table カラム名一覧
	 * @param $output_file_name ファイル名
	 */
	private function display_export_detail($result) {
		$count = count($result);
		$result_array = array();
		if ($count > 0) {
			$countUser = 0;
			for($index = 0 ; $index < $count ; $index ++) {
				$tmp_array = (array)$result[$index];
				$countUser = $countUser + $tmp_array['num_joined'] - $tmp_array['num_leaved'] + $result[$index]->num_temp;
			}
			for($index = 0 ; $index < $count ; $index ++) {
				$tmp_array = (array)$result[$index];
				$tmp_array['date'] = date('Y'.$this->lang->line('label_year').'m'.$this->lang->line('label_month').'d'.$this->lang->line('label_day')
						, strtotime($tmp_array['created_at']));
				$tmp_array['num_user'] = $countUser;
				$countUser = $countUser - $tmp_array['num_joined'] + $tmp_array['num_leaved'] - $tmp_array['num_temp'];
				$tmp_array['num_change'] = $tmp_array['num_joined'] - $tmp_array['num_leaved'] + $tmp_array['num_temp'];
				array_push($result_array, $tmp_array);
			}
			return $result_array;
		}
	}
	
	/**
	 * データのエクスポート処理
	 * @param $result_query
	 * @param $result
	 * @param $count 
	 * @param $limit
	 * @param $result_array array
	 */
	private function displayDetail($result,$count, $limit) {
		$array_parse = array();
		if ($count > 0) {
			$countUser = 0;
			for($index = 0 ; $index < $count; $index ++) {
				$countUser = $countUser + $result[$index]->num_joined - $result[$index]->num_leaved + $result[$index]->num_temp;
			}
			for($index = 0 ; $index < $count && $index < $limit[0] + $limit[1]; $index ++) {
				$tmp_array = (array)$result[$index];
				$tmp_array['date'] = date('Y'.$this->lang->line('label_year').'m'.$this->lang->line('label_month').'d'.$this->lang->line('label_day')
						, strtotime($tmp_array['created_at']));
				$tmp_array['num_user'] = $countUser;
				$countUser = $countUser - $tmp_array['num_joined'] + $tmp_array['num_leaved'] - $tmp_array['num_temp'];
				$tmp_array['num_change'] = $tmp_array['num_joined'] - $tmp_array['num_leaved'] + $tmp_array['num_temp'];
				array_push($array_parse, $tmp_array);
			}
			$result_array = array();
			for($i = $limit[1] ; $i <$limit[1] + $limit[0] && $i <$count; $i++ ) {
				array_push($result_array, $array_parse[$i]);
			}
			return $result_array;
		}
	}
}