<?php
/**
 * @name ユーザーログ管理のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class UserLog extends MY_Controller{
	
	private $post_types;
	private $flag_deletes;
	private $columns_table_activity_user =  array('created_at','id','user_id','type', 
			'parent_id', 'body', 'deleted_at','thread_download_view','thread_view');

	public function __construct(){
		parent::__construct();
		
		if(!isset($_SESSION)){
			session_start();
		}
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->helper('download');
		$this->load->library('excel');
		
		$listgendertypes = config_item('forminfo')['common']['profile']['gender_types'];
		$this->data['gender_types'] = $listgendertypes;
		
		$list_status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $list_status_types;
		
		$this->post_types = config_item('forminfo')['common']['activity']['post_types'];
		$this->data['post_types'] = $this->post_types;
		
		$this->flag_deletes = config_item('forminfo')['common']['activity']['flag_delete'];
		$this->data['flag_deletes'] = $this->flag_deletes;
		
		$this->data['controller'] = 'userlog';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}
	
	/**
	 * 検索画面の表示処理
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
				
			if ($case === 'search') {
				$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender',
						'qualification_id','organization','phone_number','registered_type','status','start_date','end_date');
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				
				if (trim($condition['end_date']) !== '' || trim($condition['start_date']) !== ''
					|| trim($condition['id']) !== ''){
					$this->setup_validation_rules('user/search');
					$validation = $this->form_validation->run($this);
					if(!$validation){
						$this->setValueItem($case);
						$this->parse('user_log.tpl', 'userlog/index');
						return;
					}
				}
				$condition['per_page'] = $this->input->post('per_page');
				// セッションに検索条件を保存するを実行
				$this->session->set_userdata('condition', $condition);
			} else {
				$conditionInfo = $this->session->userdata('condition');
				$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender','qualification_id',
						'organization','phone_number','registered_type','status','start_date','end_date','per_page');
				foreach ($targets as $target) {
					$condition[$target] = $conditionInfo[$target];
				}
			}
			$userdao = new UserDao();
				
			$this->data['is_has_data'] = $userdao->is_has_data();
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/userlog/paginate";
			$uri_segment = 4;
			$total_records = $userdao->count_by_condition($condition);
			$this->data['total_records'] = $total_records;
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $userdao->search($condition,$limit);
				
			// 項目値設定
			$this->setValueItem($case);
			$this->data['list_users'] = $result;
			
			header_remove("Cache-Control");
			$this->parse('user_log.tpl', 'userlog/index');
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
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function setValueItem($case) {
		$targets = array('id','email','login_id','first_name_ja','last_name_ja','gender','qualification_id',
				'organization','phone_number','registered_type','status','start_date','end_date');
		
		if ($case === 'search') {
			foreach ($targets as $target) {
				$this->data[$target] = $this->input->post($target);
			}
		} else {
			$conditionInfo = $this->session->userdata('condition');
			foreach ($targets as $target) {
				$this->data[$target] = $conditionInfo[$target];
			}
		}
	}
	
	/**
	 * ユーザー別ログ／集計画面の表示処理
	 * @param $user_id
	 * @param $case
	 */
	public function user_log($user_id , $case="search") {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			// 機能種別を指定する。
			if ($this->uri->segment(5) === 'paginate') {
				$case = 'paginate';
			}
			$condition = array();
			if($case === 'search') {
				$condition['from_date'] = $this->input->post('from_date');
				$condition['to_date'] = $this->input->post('to_date');
				$condition['user_id'] = $user_id;
				if (trim($condition['to_date']) !== '' || trim($condition['from_date']) !== ''){
					$this->setup_validation_rules('userlog/search');
					$validation = $this->form_validation->run($this);
					if(!$validation){
						$this->set_value_user_log($case,$user_id);
						$this->data['user_id'] = $user_id;
						$this->parse('user_log_detail.tpl','userlog/user_log');
						return;
					}
				}
				$condition['per_page']	= $this->input->post('per_page');
				$this->session->set_userdata('user_detail', $condition);
			} elseif ($case ==='paginate') {
				$conditionInfo = $this->session->userdata['user_detail'];
				$condition['from_date']	= $conditionInfo['from_date'];
				$condition['to_date']	= $conditionInfo['to_date'];
				$condition['user_id']	= $conditionInfo['user_id'];
				$condition['per_page']	= $conditionInfo['per_page'];
			}
			$condition['post_deleted_at'] = 'all';
			$condition['post_type'] = 'all';
			
			$post = new PostDao();
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/userlog/{$condition['user_id']}/detail/paginate";
			$uri_segment = 6;
			$total_records = $post->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $post->search($condition,$limit);
			$this->set_value_user_log($case,$user_id);
			
			if ($result->result_count() > 0) {
				$this->data['posts'] = $this->get_list_user_log($result);
			}
			$this->data['user_id'] = $condition['user_id'];
			
			header_remove("Cache-Control");
			$this->parse('user_log_detail.tpl','userlog/user_log');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 集計データの作成処理
	 * @param  $posts
	 * @return $user_logs
	 */
	private function get_list_user_log($posts) {
		$list_post = $this->parse_data_from_query($posts);
		$post_ids = $this->get_post_ids($posts);
		$thread_view = $this->get_thread_view_matrix($post_ids);
		$thread_download_view = $this->get_thread_download_matrix($post_ids);
		
		$data = array();
		$user_logs = array();		
		
		foreach ($list_post as $post) {
			if (array_key_exists($post['id'], $thread_view)) {
				$data['thread_view'] = $thread_view[$post['id']];
			} else {
				$data['thread_view'] = '';
			}

			if (array_key_exists($post['id'],$thread_download_view)) {
				$data['thread_download_view'] = $thread_download_view[$post['id']];
			} else {
				$data['thread_download_view'] = '';
			}
			$data['created_at'] = $post['created_at'];
			$data['id'] = $post['id'];
			$data['user_id'] = $post['user_id'];
			$data['type'] = $post['type'];
			$data['parent_id'] = $post['parent_id'];
			$data['body'] = $post['body'];
			$data['deleted_at'] = $post['deleted_at'];
			
			array_push($user_logs,$data);
		}
		return $user_logs;
	}
	
	/**
	 * queryからarrayにデータをパースする。
	 * @param object $result_query
	 */
	private function parse_data_from_query($result_query) {
		$posts = array();
		foreach ($result_query as $row) {
			$data['created_at'] = $row->created_at;
			$data['id'] = $row->id;
			$data['user_id'] = $row->user_id;
			$data['type'] = $row->type;
			$data['parent_id'] = $row->parent_id;
			$data['body'] = $row->body;
			$data['deleted_at'] = $row->deleted_at;
			array_push($posts,$data); 
		}
		return $posts;
	}
	
	private function get_post_ids($posts) {
		$post_ids = array();
		foreach ($posts as $post) {
			array_push($post_ids,$post->id);
		}
		return $post_ids;
	}
	
	private function get_thread_view_matrix($post_ids) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_thread_view_matrix($post_ids);
		return $result;	
	}
	
	private function get_thread_download_matrix($post_ids) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_thread_download_matrix($post_ids);
		return $result;
	}
		
	private function set_value_user_log($case,$user_id) {
		if($case==='search') {
			$this->data['from_date']	=	$this->input->post('from_date');
			$this->data['to_date']		=	$this->input->post('to_date');
			$this->data['user_id']		=	$user_id;
			$this->data['per_page']		=	$this->input->post('per_page');
		}elseif( $case ==='paginate') {
			$condition					=	$this->session->userdata('user_detail');
			$this->data['from_date']	=	$condition['from_date'];
			$this->data['to_date']		=	$condition['to_date'];
			$this->data['user_id']		=	$condition['user_id'];
			$this->data['per_page']		=	$condition['per_page'];
		}
	}
	
	/**
	 * 検索結果のエクスポート処理
	 */
	public function export_search_result() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if(FALSE === $this->session->userdata('user_detail')) {
				return;
			}	
			$conditionSearch = $this->session->userdata('user_detail');
			$output_file_name = $this->lang->line('label_user_log_export_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			$list_column_table = $this->columns_table_activity_user;
			$postdao = new PostDao();
			$encoding = $this->input->get('encoding');
			$conditionSearch['post_deleted_at'] = 'all';
			$conditionSearch['post_type'] = 'all';
			$result_search = $postdao->search($conditionSearch);
			$result = $this->get_list_user_log($result_search);
			$result = $this->display_detail_result($result);
			$this->process_export($result,$output_file_name,$list_column_table,'label_activity_', $encoding);
				
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function display_detail_result($result){
		for ($i = 0 ; $i < count($result); $i++) {
			$result[$i]['type'] = $this->lang->line($this->post_types[$result[$i]['type']]['label']);
			if (isset($result_query[$i]['deleted_at'])) {
				$result[$i]['deleted_at'] = $this->lang->line($this->flag_deletes['2']['label']);
			} else {
				$result[$i]['deleted_at'] = $this->lang->line($this->flag_deletes['1']['label']);
			}
		}
		return $result;
	}
	
}
