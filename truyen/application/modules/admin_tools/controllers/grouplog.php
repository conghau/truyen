<?php
/**
 * @name グループ別ログ／集計管理のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Grouplog extends MY_Controller {
	
	private $list_post_types;
	private $list_post_status;
	private $list_status_group;
	
	private $columns_table = array('created_at','id','user_id','type','parent_id',
									'body','deleted_at','thread_view','thread_download_view');
	
	public function __construct() {
		parent::__construct();
		
		if(!isset($_SESSION)) {
			session_start();
		}
		
		$this->load->library('session');
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->library('excel');

		$this->list_post_types = config_item('forminfo')['common']['activity']['post_types'];
		$this->data['post_types'] = $this->list_post_types;

		$this->list_post_status = config_item('forminfo')['common']['activity']['flag_delete'];
		$this->data['flag_delete'] = $this->list_post_status;
		
		$this->list_status_group = config_item('forminfo')['common']['group']['group_status_types'];
		$this->data['status_group'] = $this->list_status_group;
		
		$lstPublicStatus = config_item('forminfo')['common']['group']['group_public_status'];
		$this->data['lst_public_status'] = $lstPublicStatus;
		
		$this->data['controller'] = 'grouplog';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * 検索画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case="search") {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			$groups = new GroupDao();
			$condition = array();

			$targets = array('id','name','last_name_ja','first_name_ja','public_status','date_from','date_to','per_page');
			// 検索条件情報を設定
			if ($case==='search') {
				// 検索機能の場合、画面からの情報を取得する。
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				if (trim($condition['date_from']) !== '' 
					|| trim($condition['date_to']) !== ''
					|| trim($condition['id']) !== '') {
					$this->setup_validation_rules('grouplog/search');
					$validation = $this->form_validation->run($this);
					if(!$validation){
						$this->set_value_item_group($case);
						$this->parse('group_log.tpl', 'grouplog/index');
						return;
					}
				}
				
				// セッションに検索条件を保存するを実行
				$this->session->set_userdata('condition', $condition);
			} else if ($case === 'paginate') {
				// 機能がソート、ページング、削除、再表示の場合、セッションから情報を取得する。
				$conditionInfo = $this->session->userdata('condition');
				foreach ($targets as $target) {
					$condition[$target] = $conditionInfo[$target];
				}
			}
	
			$url = $this->data[''] . "admin_tools/grouplog/paginate";
			$uri_segment = 4;
			$total_records = $groups->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $groups->search($condition,$limit);
			// 項目値設定
			$this->set_value_item_group($case);
			// 検索結果を保存
			$this->data['list_groups'] = $result;
			
			header_remove("Cache-Control");
			$this->parse('group_log.tpl', 'grouplog/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ別ログ／集計画面の表示処理
	 * @param $group_id グループID
	 * @param $case 機能種別
	 */
	public function group_log($group_id,$case ='search') {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			if ($this->uri->segment(5) === 'paginate') {
				$case = 'paginate';
			}
			$postDao = new PostDao();
			$condition = array();
			$targets = array('group_id','from_date','to_date','post_deleted_at','post_type','per_page');
			if ($case === 'search') {
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				$condition['group_id'] = $group_id;
				$condition['post_deleted_at'] = 'all';
				$condition['post_type'] = 'all';
				if (trim($condition['to_date']) !== '' || trim($condition['from_date']) !== '') {
					$this->setup_validation_rules('grouplogdetail/search');
					$validation = $this->form_validation->run($this);
					if(!$validation){
						$this->set_value_item_detail($case,$group_id);
						$this->data['id'] = $group_id;
						$this->parse('group_log_detail.tpl', 'grouplog/group_log_detail');
						return;
					}
				}
				// セッションに検索条件を保存するを実行
				$this->session->set_userdata('group_detail', $condition);
			} elseif($case === 'paginate') {
				$conditionInfo = $this->session->userdata('group_detail');
				foreach ($targets as $target) {
					$condition[$target] = $conditionInfo[$target];
				}
			}
			
			$url = $this->data[''] . "admin_tools/grouplog/".$condition['group_id']."/detail/paginate";
			$uri_segment = 6;
			$total_records = $postDao->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $postDao->search($condition,$limit);
			// 項目値設定
			$this->set_value_item_detail($case,$group_id);
			if ($result->result_count() > 0) {
				$this->data['list_detail'] = $this->get_list_group_log($result);
			}
			$this->data['id'] = $condition['group_id'];
			
			header_remove("Cache-Control");
			$this->parse('group_log_detail.tpl', 'grouplog/group_log_detail');
	
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 検索結果をダウンロードの表示処理
	 */
	public function export_search_result() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if(FALSE === $this->session->userdata('group_detail')) {
				return;
			}
			$list_columns_table_user = $this->columns_table;
			$output_file_name =$this->lang->line('label_grouplog_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			$conditionSearch = $this->session->userdata('group_detail');
			
			$postDao = new PostDao();
			$result_search = $postDao->search($conditionSearch);
			
			$result_search= $this->get_list_group_log($result_search);
			
			$result_search = $this->display_detail_result($result_search);
			$encoding = $this->input->get('encoding');
			$this->process_export($result_search, $output_file_name, $list_columns_table_user,'label_grouplog_', $encoding);
	
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * DBから統計情報データを作成する処理
	 * @param $posts 投稿一覧
	 * @return $group_logs グループ別ログ
	 */
	private function get_list_group_log($posts) {
		$list_post = $this->parse_data_from_query($posts);
		$post_ids = $this->get_post_ids($posts);
		$thread_view = $this->get_thread_view_matrix($post_ids);
		$thread_download_view = $this->get_thread_download_matrix($post_ids);
	
		$arr = array();
		$group_logs = array();
		foreach ($list_post as $post) {
			if (array_key_exists($post['id'],$thread_view)) {	
				$arr['thread_view'] = $thread_view[$post['id']];
			} else {
				$arr['thread_view'] = '';
			}
	
			if (array_key_exists($post['id'],$thread_download_view)) {
				$arr['thread_download_view'] = $thread_download_view[$post['id']];
			} else {
				$arr['thread_download_view'] = '';
			}
				
			$arr['created_at'] = $post['created_at'];
			$arr['id'] = $post['id'];
			$arr['user_id'] = $post['user_id'];
			$arr['type'] = $post['type'];
			$arr['parent_id'] = $post['parent_id'];
			$arr['body'] = $post['body'];
			$arr['deleted_at'] = $post['deleted_at'];
			array_push($group_logs, $arr);
		}
		return $group_logs;
	}
	
	/**
	 * queryからarrayにデータをパースする。
	 * @param $result_query
	 * @return array
	 */
	private function parse_data_from_query($result_query) {
		$array = array();
		foreach ($result_query as $row) {
			$data['created_at'] = $row->created_at;
			$data['id'] = $row->id;
			$data['user_id'] = $row->user_id;
			$data['type'] = $row->type;
			$data['parent_id'] = $row->parent_id;
			$data['body'] = $row->body;
			$data['deleted_at'] = $row->deleted_at;
			array_push($array,$data);
		}
		return $array;
	}
	
	/**
	 * 投稿ID一覧を作成する処理
	 * @param $list_post 投稿一覧
	 * @return $post_ids  投稿ID一覧
	 */
	private function get_post_ids($list_post) {
		$post_ids = array();
		foreach ($list_post as $post) {
			array_push($post_ids,$post->id);
		}
		return $post_ids;
	}
	
	/**
	 * DBから統計情報データを取得する処理
	 * @param $list_post_id 投稿ID一覧
	 */
	private function get_thread_view_matrix($list_post_id) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_thread_view_matrix($list_post_id);
		return $result;
	}
	
	/**
	 * DBから統計情報データを取得する処理
	 * @param $list_post_id 投稿ID一覧
	 */
	private function get_thread_download_matrix($list_post_id) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_thread_download_matrix($list_post_id);
		return $result;
	}
		
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item_group($case) {
		$targets = array('id','name','last_name_ja','first_name_ja','public_status','date_from','date_to','per_page');
		if ($case === 'search') {
			// 検索の場合
			foreach ($targets as $target) {
				$this->data[$target] = $this->input->post($target);
			}
		} else  {
			// セッションの値を項目に設定
			$condition = $this->session->userdata('condition');
			foreach ($targets as $target) {
				$this->data[$target] = $condition[$target];
			}
		} 
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item_detail($case,$group_id) {
		$targets = array('from_date','to_date','group_id','per_page');
		if ($case === 'search') {
			foreach ($targets as $target) {
				$this->data[$target] = $this->input->post($target);
			}
			$this->data['id'] = $group_id;
		} else if ($case === 'paginate') {
			$condition = $this->session->userdata('group_detail');
			foreach ($targets as $target) {
				$this->data[$target] = $condition[$target];
			}
		}
	}
	
	private function display_detail_result($result_query){
		for ($i = 0 ; $i < count($result_query); $i++) {
			$result_query[$i]['type'] = $this->lang->line($this->list_post_types[$result_query[$i]['type']]['label']);
			if (isset($result_query[$i]['deleted_at'])) {
				$result_query[$i]['deleted_at'] = $this->lang->line($this->list_post_status['2']['label']);
			} else {
				$result_query[$i]['deleted_at'] = $this->lang->line($this->list_post_status['1']['label']);
			}
		}
		return $result_query;
	}

}