<?php
/**
 * @name 投稿のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class PostAdmin extends MY_Controller {

	private $status_types;
	private $columns_table_post = array('id', 'type', 'parent_id', 'root_id'
			, 'user_type', 'user_id', 'category_id', 'title', 'body', 'open_at'
			, 'expired_at', 'created_at', 'updated_at','deleted_at', 'status'
	);
	
	public function __construct() {
		parent::__construct();

		$this->lang->load('application');
		$this->load->config('forminfo');

		$this->status_types = config_item('forminfo')['common']['status_types'];
		
		$this->data['controller']	= 'post';
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 *一覧画面の表示処理
	 * @param $case 機能種別
	 */
	public function index($case = "search") {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if (isset($_SESSION['post_info'])) {
				unset($_SESSION['post_info']);
			}
			
			if (isset($_SESSION['comment_info'])) {
				unset($_SESSION['comment_info']);
			}
			
			if ($this->session->userdata('post_id') != FALSE) {
				$this->session->unset_userdata('post_id');
			}
			
			if ($this->session->userdata('comment_id') != FALSE) {
				$this->session->unset_userdata('comment_id');
			}

			// 機能種別を指定する。
			if ($this->uri->segment(3) === 'paginate') {
				$case = 'paginate';
			} else if ($this->uri->segment(4) === 'user') {
				$case = "user_id";
			} else if ($this->uri->segment(4) === 'group') {
				$case = "group_post_id";
			}
			
			switch ($case) {
				case "search":
					$condition['id'] = $this->input->post('post_id');
					$condition['group_id'] = $this->input->post('group_id');
					$condition['user_id'] = $this->input->post('user_id');
					$condition['last_name_ja'] = $this->input->post('last_name');
					$condition['first_name_ja'] = $this->input->post('first_name');
					$condition['from_date'] = $this->input->post('from_date');
					$condition['to_date'] = $this->input->post('to_date');
					$condition['status'] = $this->input->post('status');
					$condition['per_page'] = $this->input->post('per_page');
					
					if (trim($condition['from_date']) !== '' || trim($condition['to_date']) !== ''
						|| trim($condition['id']) !== '' || trim($condition['group_id']) !== '' 
						|| trim($condition['user_id']) !== '') {
						$this->setup_validation_rules('post/search');
						$validation = $this->form_validation->run($this);
						if(!$validation){
							$this->data['is_has_data'] = FALSE;
							$this->set_value_item($case);
							$this->data['status_types'] = $this->status_types;
							$this->parse('post_list.tpl', 'postadmin/index');
							return;
						}
					}
					
					break;
				case "paginate":
					// 機能がソート、ページング、削除、再表示の場合、セッションから情報を取得する。
					$conditionInfo = $this->session->userdata('condition');
					$condition['id'] = isset($conditionInfo['id']) ? $conditionInfo['id']: '';
					$condition['group_id'] = isset($conditionInfo['group_id']) ? $conditionInfo['group_id']: '';
					$condition['user_id'] = isset($conditionInfo['user_id']) ? $conditionInfo['user_id']: '';
					$condition['last_name_ja'] = isset($conditionInfo['last_name_ja']) ? $conditionInfo['last_name_ja']: '';
					$condition['first_name_ja'] = isset($conditionInfo['first_name_ja']) ? $conditionInfo['first_name_ja']: '';
					$condition['from_date'] = isset($conditionInfo['from_date']) ? $conditionInfo['from_date']: '';
					$condition['to_date'] = isset($conditionInfo['to_date']) ? $conditionInfo['to_date']: '';
					$condition['status'] = isset($conditionInfo['status']) ? $conditionInfo['status']: '';
					$condition['per_page'] = isset($conditionInfo['per_page']) ? $conditionInfo['per_page']: '';
					break;
				case "user_id":
					$condition['user_id'] = $this->uri->segment(3);
					$this->data['user_id'] = $this->uri->segment(3);
					$condition['per_page'] = '';
					break;
				case "group_post_id":
					$group_id = $this->uri->segment(3);
					$groupdao = new GroupDao();
					$group_owner = $groupdao->get_group_owner_by_group_id($group_id);
					$condition['user_id'] = $group_owner->id;
					$condition['group_id'] = $group_id;
					$condition['per_page'] = '';
					break;
				}
				
			$post = new PostDao();
			
			// 改ページ作成を実行
			$url = $this->data[''] . "admin_tools/post/paginate";
			$uri_segment = 4;
			$total_records = $post->count_by_condition($condition);
			$limit = $this->create_pagination_admin($total_records, $condition, $url, $uri_segment);
			$result = $post->search($condition,$limit);
			
			// セッションに検索条件を保存するを実行
			$this->store_in_session($case);

			// 項目値設定
			$this->set_value_item($case);

			$this->display_list($result);

			$postdao = new PostDao();
			$count_all_record = $postdao->count_by_condition(array());
			$this->data['is_has_data'] = ($count_all_record > 0)? TRUE: FALSE; 
			$this->data['posts'] = $result;
			$this->data['status_types'] = $this->status_types;
			$this->data['total_pages'] = $result->result_count();
			$this->data['total_records'] = $total_records;
			header_remove("Cache-Control");
			$this->parse('post_list.tpl', 'postadmin/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 編集画面の表示処理
	 * @param $id 投稿ID
	 */
	public function edit($id = null) {
		try {
			if (!isset($_SESSION)) {
				session_start();
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0029-Q');
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$post = new PostDao();
				$post_info = $post->get_post($id);

				if (!$post_info->id) {
					redirect($this->data[''].'admin_tools/post');
				}
				$post_arr = $this->get_post_array($post_info);

				$comment = new PostDao();
				$comments_info = $comment->get_comment_detail($id);
				$post_arr['comments'] = $this
						->get_comment_array($comments_info);

				$this->data['post'] = $post_arr;
				$this->session->set_userdata('post_id', $id);
			} else {
				$post_info = $_SESSION["post_info"];
				$post_info['body'] = htmlspecialchars_decode($post_info['body']);
				$comment = new PostDao();
				$comments_info = $comment->get_comment_detail($post_info['id']);
				$comments = $this->get_comment_array($comments_info);
				$post_info['comments'] = $comments;
				$this->data['post'] = $post_info;
			}
			$this->data['status_types'] = $this->status_types;
			$this->parse('post_edit.tpl', 'postadmin/edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 編集確認画面の表示処理
	 */
	public function confirm_edit() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['post_info'])) {
					unset($_SESSION['post_info']);
				}
				redirect($this->data[''].'admin_tools/post');
			}

			$post['id'] = $this->session->userdata('post_id');
			$post['body'] = htmlspecialchars($this->input->post('body'));
			$post['status'] = $this->input->post('status');
			$post['created_at'] = $this->input->post('created_at');
			$post['user_id'] = $this->input->post('user_id');
			$post['user'] = $this->input->post('user_dest');
			$post['group'] = $this->input->post('group_dest');
			$post['attach_file'] = $this->input->post('attach_file');

			$comment = new PostDao();
			$comments_info = $comment->get_comment_detail($post['id']);
			$comments = $this->get_comment_array($comments_info);
			
			if (!isset($_SESSION)) {
				session_start();
			}
			$_SESSION["post_info"] = $post;
			log_message("daoerr", "session" . $_SESSION["post_info"]["body"]);
			$post['comments'] = $comments;

			$this->data['post'] = $this->display_detail($post);
			$this->parse('post_confirm_edit.tpl', 'postadmin/confirm_edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 編集完了画面の表示処理
	 */
	public function update() {
		try {
			if (!isset($_SESSION)) {
				session_start();
			}
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION["post_info"])) {
					unset($_SESSION["post_info"]);
				}
				redirect($this->data[''].'admin_tools/post');
			}

			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);
			
			if (!isset($_SESSION["post_info"])) {
				redirect($this->data[''].'admin_tools/post');
			}

			$storeError = false;
			$msgID = "";

			$post_info = $_SESSION["post_info"];
			log_message("daoerr","test:" + $post_info["body"]);
			$post = new PostDao(MASTER);
			$result = $post->update_post($post_info);

			if (!$result) {
				$msgID = 'L-A-0006-E';
			} else {
				$msgID = 'L-A-0005-I';
			}

			unset($_SESSION["post_info"]);
			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 削除完了画面の表示処理
	 */
	public function delete() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['post_info'])) {
					unset($_SESSION['post_info']);
				}
				redirect($this->data[''].'admin_tools/post');
			}

			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);

			if (!$this->session->userdata('post_id')) {
				redirect($this->data[''].'admin_tools/post');
			}

			$storeError = FALSE;
			$msgID = "";

			$post = new PostDao(MASTER);
			$id = $this->session->userdata('post_id');
			$result = $post->delete_post($id);

			if (!$result) {
				$msgID = 'L-A-0002-E';
			} else {
				$msgID = 'L-A-0001-I';
			}

			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 編集画面の表示処理
	 * @param $id 投稿ID
	 */
	public function file($id = null) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$postdao = new PostDao();
			$post_info = $postdao->get_post($id);
			if (!$post_info->id) {
				redirect($this->data[''].'admin_tools/post');
			}
			$posts_tmp = $postdao->get_post_detail_by_user(NULL, 0, 0, $id);
			$posts = $postdao->parse_post_detail(NULL, $posts_tmp);
			$this->data['post'] = array_shift($posts);
			$this->session->set_userdata('post_id', $id);
			$this->data['status_types'] = $this->status_types;
			$this->parse('post_file.tpl', 'postadmin/file');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * コメント編集画面の表示処理
	 * @param $id コメントID
	 */
	public function comment_edit($id) {
		try {
			if (!isset($_SESSION)) {
				session_start();
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$this->data['msg_delete_confirm'] = $this->lang->line('L-A-0030-Q');
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				$post = new PostDao();
				$comment = $post->get_comment_by_id($id);
				
				if (!$comment->id) {
					redirect($this->data[''].'admin_tools/post');
				}
				
				$comment_info['id'] = $id;
				$comment_info['parent_id'] = $comment->parent_id;
				$comment_info['user_id'] = $comment->user_id;
				$destination = $this->get_destination($comment->parent_id);
				$comment_info['user'] = $destination['user'];
				$comment_info['group'] = $destination['group'];
				$comment_info['created_at'] = $comment->created_at;
				$comment_info['status'] = $comment->status;
				$comment_info['body'] = htmlspecialchars_decode($comment->body);
				
				$this->data['comment'] = $comment_info;
				$this->session->set_userdata('comment_id', $id);
			}  else {
				$comment = $_SESSION["comment_info"];
				$comment['body'] = htmlspecialchars_decode($comment['body']);
				$this->data['comment'] = $comment;
			}
			
			$this->data['status_types'] = $this->status_types;
			$this->parse('comment_edit.tpl', 'postadmin/comment_edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * コメント編集確認画面の表示処理
	 */
	public function comment_confirm_edit() {
		try {
			if (!isset($_SESSION)) {
				session_start();
			}
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION["comment_info"])) {
					unset($_SESSION["comment_info"]);
				}
				redirect($this->data[''].'admin_tools/post');
			}
			$comment['id'] = $this->session->userdata('comment_id');
			$comment['body'] = htmlspecialchars($this->input->post('body'));
			$comment['status'] = $this->input->post('status');
			$comment['created_at'] = $this->input->post('created_at');
			$comment['user_id'] = $this->input->post('user_id');
			$comment['user'] = $this->input->post('user_dest');
			$comment['group'] = $this->input->post('group_dest');
			$comment['parent_id'] = $this->input->post('parent_id');
			$_SESSION["comment_info"] = $comment;
			$comment['status'] = $this->get_status($comment['status']);
			$this->data['comment'] = $comment;
			$this->parse('comment_confirm_edit.tpl', 'postadmin/comment_confirm_edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * コメント編集完了画面の表示処理
	 */
	public function comment_update() {
		try {
			if (!isset($_SESSION)) {
				session_start();
			}
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION["comment_info"])) {
					unset($_SESSION["comment_info"]);
				}
				redirect($this->data[''].'admin_tools/post');
			}

			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);

			if (!isset($_SESSION["comment_info"])) {
				redirect($this->data[''].'admin_tools/post');
			}

			$storeError = false;
			$msgID = "";

			$post_info = $_SESSION["comment_info"];
			$post = new PostDao(MASTER);
			$result = $post->update_post($post_info);

			if (!$result) {
				$msgID = 'L-A-0006-E';
			} else {
				$msgID = 'L-A-0005-I';
			}

			unset($_SESSION["comment_info"]);
			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * コメント削除完了画面の表示処理
	 */
	public function comment_delete() {
		try {
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['comment_info'])) {
					unset($_SESSION['comment_info']);
				}
				redirect($this->data[''].'admin_tools/post');
			}

			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);

			if (!$this->session->userdata('comment_id')) {
				redirect($this->data[''].'admin_tools/post');
			}

			$storeError = FALSE;
			$msgID = "";
			
			$post = new PostDao(MASTER);
			$id = $this->session->userdata('comment_id');
			$result = $post->delete_post($id);

			if (!$result) {
				$msgID = 'L-A-0002-E';
			} else {
				$msgID = 'L-A-0001-I';
			}

			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * スレッド全体のエクスポート処理
	 */
	public function export_all() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			$encoding = $this->input->get('encoding');
			$list_column_table = $this->columns_table_post;
			$file_name = $this->lang->line('label_post_export_all_file_name');
			$output_file_name = $file_name . '.' . OUTPUT_FILE_TYPE_TSV;

			$post = new PostDao();
			$result = $post->get_all();

			$this->process_export($result,$output_file_name, $list_column_table, 'label_post_', $encoding);
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
			
			if (FALSE === $this->session->userdata('condition')) {
				return;
			}
			$encoding = $this->input->get('encoding');
			$list_column_table = $this->columns_table_post;
			$file_name = $this->lang->line('label_post_export_search_file_name');
			$output_file_name = $file_name . '.'. OUTPUT_FILE_TYPE_TSV;
					
			$conditionSearch = $this->session->userdata('condition');

			$post = new PostDao();
			$result = $post->search($conditionSearch);

			$this->process_export($result,$output_file_name, $list_column_table, 'label_post_', $encoding);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 編集確認画面の表現処理
	 * @param array $post
	 * @return array
	 */
	private function display_detail($post) {
		$post['status'] = $this->get_status($post['status']);
		return $post;
	}

	/**
	 * スレッドの取得
	 * @param $result_query データベースからデータを取得した。
	 */
	private function get_post_array($result_query) {
		$post['id'] = $result_query->id;
		$post['body'] = htmlspecialchars_decode($result_query->body);
		$post['user_id'] = $result_query->user_id;
		$post['status'] = $result_query->status;
		$post['attach_file'] = $this->get_number_attach_file($result_query->id);

		$destination = $this->get_destination($result_query->id);
		$post['user'] = $destination['user'];
		$post['group'] = $destination['group'];

		$post['created_at'] = $result_query->created_at;
		return $post;
	}

	/**
	 * 添付ファイル数の取得
	 * @param $post_id 投稿ID
	 */
	private function get_number_attach_file($post_id) {
		$post_upload = new PostUploadDao();
		$attach_file = $post_upload->count_by_post_id($post_id);
		return $attach_file;
	}

	/**
	 * 宛先の取得
	 * @param $post_id 投稿ID
	 * @return string
	 */
	private function get_destination($post_id) {
		$forward = new ForwardDao();
		$destinations = $forward->get_by_post_id($post_id);
		$user = array();
		$group = array();
		foreach ($destinations as $destination) {
			if ($destination->user_type === '1') {
				$user[] = $destination->send_id;
			} else {
				$group[] = $destination->send_id;
			}
		}
		$comma = $this->lang->line('label_comma') . ' ';
		$dest['user'] = implode($comma, $user);
		$dest['group'] = implode($comma, $group);
		return $dest;
	}

	/**
	 * コメントの取得
	 * @param $result_query データベースからデータを取得した。
	 * @return array
	 */
	private function get_comment_array($result_query) {
		$comments = array();
		$comment_id = array();
		foreach ($result_query as $result) {
			$comment['id'] = $result->id;
			$comment['body'] = $result->body;
			$comment['created_at'] = $result->created_at;
			$comment['status'] = $this->get_status($result->status);
			$comment['user_id'] = $result->user_id;
			$comment_id[] = $result->id;
			$comments[] = $comment;
		}
		$this->session->set_userdata("comment_id", $comment_id);
		return $comments;
	}

	/**
	 * 一覧画面の表現
	 * @param $list_post 投稿一覧
	 */
	private function display_list($list_post) {
		foreach ($list_post as $post) {
			$post->status = $this->get_status($post->status);
		}
	}	

	/**
	 * 項目値設定メソッド。
	 * @param $case 機能種別
	 */
	private function set_value_item($case) {
		switch ($case) {
			case 'search':
				// 検索の場合
				$this->data['post_id'] = $this->input->post('post_id');
				$this->data['group_id'] = $this->input->post('group_id');
				$this->data['user_id'] = $this->input->post('user_id');
				$this->data['last_name'] = $this->input->post('last_name');
				$this->data['first_name'] = $this->input->post('first_name');
				$this->data['from_date'] = $this->input->post('from_date');
				$this->data['to_date'] = $this->input->post('to_date');
				$this->data['status'] = $this->input->post('status');
				break;
			case 'paginate':
				//セッションの値を項目に設定
				$condition = $this->session->userdata('condition');
				$this->data['post_id'] = isset($condition['id']) ? $condition['id']: '';
				$this->data['group_id'] = isset($condition['group_id']) ? $condition['group_id']: '';
				$this->data['user_id'] = isset($condition['user_id']) ? $condition['user_id']: '';
				$this->data['last_name'] = isset($condition['last_name_ja']) ? $condition['last_name_ja']: '';
				$this->data['first_name'] = isset($condition['first_name_ja']) ? $condition['first_name_ja']: '';
				$this->data['from_date'] = isset($condition['from_date']) ? $condition['from_date']: '';
				$this->data['to_date'] = isset($condition['to_date']) ? $condition['to_date']: '';
				$this->data['status'] = isset($condition['status']) ? $condition['status']: '';
				break;
			case 'group_post_id':
				$condition = $this->session->userdata('condition');
				$this->data['group_id'] = isset($condition['group_id']) ? $condition['group_id']: '';
				$this->data['user_id'] = isset($condition['user_id']) ? $condition['user_id']: '';
		}
	}

	/**
	 * ステータスの取得
	 * @param $status_id ステータスID
	 */
	private function get_status($status_id) {
		foreach ($this->status_types as $status_type) {
			if ($status_id == $status_type['id']) {
				return $status_type['label'];
			}
		}
	}

	/**
	 *　セッションに検索条件を保存するメソッド
	 * @param $case 機能種別
	 */
	private function store_in_session($case) {
		switch ($case) {
			case 'search':
				// セッションに検索条件を保存する
				$dataSession = array(
					'id' => $this->input->post('post_id'),
					'group_id' => $this->input->post('group_id'),
					'user_id' => $this->input->post('user_id'),
					'last_name_ja' => $this->input->post('last_name'),
					'first_name_ja' => $this->input->post('first_name'),
					'from_date' => $this->input->post('from_date'),
					'to_date' => $this->input->post('to_date'),
					'status' => $this->input->post('status'),
					'per_page' => $this->input->post('per_page'),
				);
				$this->session->set_userdata('condition', $dataSession);
				break;
			case 'user_id':
				$dataSession = array('user_id' => $this->uri->segment(3));
				$this->session->set_userdata('condition', $dataSession);
				break;
			case 'group_post_id':
				$group_id = $this->uri->segment(3);
				$groupdao = new GroupDao();
				$group_owner = $groupdao->get_group_owner_by_group_id($group_id);
				
				$dataSession = array('group_id' => $group_id, 'user_id' => $group_owner->id);
				$this->session->set_userdata('condition', $dataSession);
				break;
		}
	}
}
