<?php
/**
 * @name スレッド別ログ管理のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class ThreadLog extends MY_Controller {
	
	private $status_types;
	private $post_types;
	private $flag_deletes;
	
	private $columns_table_activity_thread_search = array('created_at','id','user_id',
							'type','parent_id','body','deleted_at','thread_view');
	
	public function __construct() {
		parent::__construct();
	
		if(!isset($_SESSION)){
			session_start();
		}
		$this->load->config('forminfo');
		$this->load->helper('form','url');
		$this->load->library('excel');
		
		$this->post_types = config_item('forminfo')['common']['activity']['post_types'];
		$this->data['post_types'] = $this->post_types;
		
		$this->flag_deletes = config_item('forminfo')['common']['activity']['flag_delete'];
		$this->data['flag_deletes'] = $this->flag_deletes;
		
		$this->status_types = config_item('forminfo')['common']['status_types'];
		$this->data['status_types'] = $this->status_types;
		$this->data['controller'] = 'threadlog';
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
		
			// 機能種別を指定する。
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			}
			$targets = array('id','user_id','last_name_ja','first_name_ja','from_date','to_date','status','per_page');
			if ($case === 'search') {
				foreach ($targets as $target) {
					$condition[$target] = $this->input->post($target);
				}
				if (trim($condition['to_date']) !== '' 
					|| trim($condition['from_date']) !== ''
					|| trim($condition['id']) !== ''
					|| trim($condition['user_id']) !== ''){
					$this->setup_validation_rules('threadlog/search');
					$validation = $this->form_validation->run($this);
					if(!$validation){
						$this->set_value_item($case);
						$this->parse('thread_log.tpl', 'threadlog/index');
						return;
					}
				}
				$this->session->set_userdata('condition', $condition);
			} else if ($case === 'paginate') {
					$conditionInfo = $this->session->userdata('condition');
					foreach ($targets as $target) {
						$condition[$target] = isset($conditionInfo[$target]) ? $conditionInfo[$target] : '';
					}
			}
			$post = new PostDao();
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/threadlog/paginate";
			$uri_segment = 4;
			$total_records = $post->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $post->search($condition,$limit);
			// 項目値設定
			$this->set_value_item($case);
			$this->display_list_thread($result);
		
			$this->data['posts'] = $result;
			$this->data['total_pages'] = $result->result_count();
			header_remove("Cache-Control");
			$this->parse('thread_log.tpl', 'threadlog/index');	
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}		
	}
	
	/**
	 * スレッド別ログ／集計画面の表示処理
	 * @param $user_id ユーサーＩＤ
	 * @param $case ケース
	 */
	public function thread_log($parent_id,$case="display") {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			// 機能種別を指定する。
			if ($this->uri->segment(5) === 'paginate') {
				$case = 'paginate';
			}
			$condition = array();
			if($case === 'display') {
				$condition['parent_id']	= $parent_id;
				$condition['per_page']	= $this->input->post('per_page');
			} elseif ($case ==='paginate') {
				$conditionInfo = $this->session->userdata['thread_detail'];
				$condition['parent_id']	= $conditionInfo['parent_id'];
				$condition['per_page']	= $conditionInfo['per_page'];
			}
			$post = new PostDao();
			$condition['post_deleted_at'] = 'all';
			$condition['post_type'] = TYPE_COMMENT;
			
			$url = $this->data[''] . "admin_tools/threadlog/{$condition['parent_id']}/detail/paginate";
			$uri_segment = 6;
			$total_records = $post->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $post->search($condition,$limit);
			$this->store_in_session_thread_log($case,$parent_id);
			$this->set_value_thread_log($case,$parent_id);
				
			if ( $result->result_count() > 0) {
				$this->data['posts'] = $this->get_thread_logs($result);
			}
			$this->data['parent_id'] = $condition['parent_id'];
			$this->parse('thread_log_detail.tpl','threadlog/thread_log');
			header_remove("Cache-Control");
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item($case) {
		$targets = array('id','user_id','last_name_ja','first_name_ja','from_date','to_date','status');
		switch ($case) {
			case 'search':
				foreach ($targets as $target) {
					$this->data[$target] = $this->input->post($target);
				}
				break;
			case 'paginate':
				$condition = $this->session->userdata('condition');
				foreach ($targets as $target) {
					$this->data[$target] = isset($condition[$target]) ? $condition[$target]: '';
				}
				break;
		}
	}
	
	/**
	 * 一覧画面の表現
	 * @param object $list_post
	 */
	private function display_list_thread($list_post) {
		foreach ($list_post as $post) {
			$post->status = $this->get_status_thread($post->status);
		}
	}
	
	/**
	 * ステータスの取得
	 * @param id $status_id
	 * @return string
	 */
	private function get_status_thread($status_id) {
		foreach ($this->status_types as $status) {
			if ($status_id == $status['id']) {
				return $status['label'];
			}
		}
	}
	
	/**
	 * セッションにデータのセット処理
	 * @param $case ケース
	 * @param $parent_id 親投稿ID
	 */
	private function store_in_session_thread_log($case, $parent_id) {
		if($case === 'display') {
			$sessionData = array(
					'parent_id'	=> $parent_id,
					'per_page'	=> $this->input->post('per_page')
			);
			$this->session->set_userdata('thread_detail',$sessionData);
		}
	}
	
	/**
	 * 項目値設定メソッド。
	 * @param $case ケース
	 * @param $parent_id 親投稿ID
	 */
	private function set_value_thread_log($case,$parent_id) {
		if($case==='display') {
			$this->data['parent_id']	=	$parent_id;
			$this->data['per_page']		=	$this->input->post('per_page');
		}elseif( $case ==='paginate') {
			$condition					=	$this->session->userdata('thread_detail');
			$this->data['parent_id']	=	$condition['parent_id'];
			$this->data['per_page']		=	$condition['per_page'];
		}
	}

	/**
	 * 集計データの作成処理
	 * @param  $posts
	 * @return $user_logs
	 */
	private function get_thread_logs($posts) {
		$list_post = $this->parse_data_from_query($posts);
		$thread_view = $this->get_thread_view_matrix($posts->parent_id);
		$data = array();
		$thread_logs = array();
		$view_total ='';
		if (array_key_exists($posts->parent_id, $thread_view)) {
			$view_total = $thread_view[$posts->parent_id];
		} 
		foreach ($list_post as $post) {
			$data['thread_view'] = $view_total;
			$data['created_at'] = $post['created_at'];
			$data['id'] = $post['id'];
			$data['user_id'] = $post['user_id'];
			$data['type'] = $post['type'];
			$data['parent_id'] = $post['parent_id'];
			$data['body'] = $post['body'];
			$data['deleted_at'] = $post['deleted_at'];
			
			array_push($thread_logs,$data);
		}
		return $thread_logs;
	}
	
	/**
	 * queryからarrayにデータをパースする。
	 * @param $result_query
	 * @return $posts 投稿一覧
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
	
	/**
	 * 投稿ID一覧を作成する処理
	 * @param $list_post 投稿一覧
	 * @return $post_ids  投稿ID一覧
	 */
	private function get_post_ids($posts) {
		$post_ids = array();
		foreach ($posts as $post) {
			array_push($post_ids,$post->id);
		}
		return $post_ids;
	}
	
	/**
	 * DBから統計情報データを取得する処理
	 * @param $list_post_id 投稿ID一覧
	 */
	private function get_thread_view_matrix($post_ids) {
		$asdao = new ActivityStatDao();
		$result = $asdao->get_thread_view_matrix($post_ids);
		return $result;
	}
	
	/**
	 * 検索結果のエクスポート処理
	 */
	public function export_search_thread_log() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if(FALSE === $this->session->userdata('thread_detail')) {
				return;
			}
			$conditionSearch = $this->session->userdata('thread_detail');
			$output_file_name = $this->lang->line('label_thread_log_export_file_name').'.'.OUTPUT_FILE_TYPE_TSV;
			$list_column_table = $this->columns_table_activity_thread_search;
			
			$postdao = new PostDao();
			
			$conditionSearch['post_deleted_at'] = 'all';
			$conditionSearch['post_type'] = TYPE_COMMENT;
			$encoding = $this->input->get('encoding');
			$result_search = $postdao->search($conditionSearch);
			$result = $this->get_thread_logs($result_search);
			$result = $this->display_detail_result($result);
			$this->process_export($result,$output_file_name,$list_column_table,'label_activity_thread_',$encoding);
			
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
