<?php
/**
 * @name タイムラインプレビューのコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Index extends MY_Controller {

	const LIMIT = 10;
	const OFFSET = 0;
	
	public function __construct() {
		parent::__construct();
		$this->lang->load('application');
		$this->data['controller'] = 'index';
		if(!isset($_SESSION)) {
			session_start();
		}
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * タイムラインプレビュー画面の表示処理
	 */
	public function index() {
		try {
			//ログインチェック処理
			$this->form_validation->check_login_admin();

			$postdao = new PostDao();
			
			$this->load->config('pagination');
			$limit = $this->config->config['pagination']['index_page'];
			
			$offset = 0;
			$posts_tmp = $postdao->get_post_detail_by_user(null, $offset, $limit);
			$posts = $postdao->parse_post_detail(null, $posts_tmp);

			$this->data['posts'] = $posts;

			$offset += count($posts);
			$this->data['post_offset'] = $offset;
			$this->data['post_query_url'] = $this->data[''] . 'admin_tools/get_post';
			$this->parse('index.tpl', 'admin_tools/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		} 	
	}
	private function validate_post_ids($post_ids){
		if(!isset($post_ids) || !is_array($post_ids)) {
			return false;
		}
		$this->load->config('pagination');
		$per_page_ajax = $this->config->config['pagination']['per_page'];
		if (count($post_ids) > $per_page_ajax){
			return false;
		}
		$post_ids = array_filter($post_ids, 'ctype_digit');
		if (count($post_ids) == 0)
			return false;
		return true;
	}

	public function get_post($offset = 0) {
		try {
			$this->form_validation->check_login_admin(TYPE_AJAX);
			$offset = intval($offset);
				
			$postdao = new PostDao();

			$this->load->config('pagination');
			$limit = $this->config->config['pagination']['per_page'];
			
			$posts_tmp = $postdao->get_post_detail_by_user(NULL, $offset, $limit);
			$posts = $postdao->parse_post_detail(NULL, $posts_tmp);
				
			$this->data['posts'] = $posts;
			$this->parse('post_layout_admin.tpl', 'admin_tools/get_post_detail');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * スレッドのコメント一覧を取得する。
	 * @param $id スレッドＩＤ
	 */
	public function get_comment_list($id) {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);

			$postdao = new PostDao();
			$comments = $postdao->get_comment_detail($id);
	
			$this->data['comments'] = $this->get_array_comment($comments);
			$this->data['post_id'] = $id;
			$this->data['number_comment_show'] = sprintf($this->lang->line('label_hide_comments'), count($comments->all));
			$this->data['number_comment_hide'] = sprintf($this->lang->line('label_show_comments'), count($comments->all));
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			$this->parse('comment_list.tpl');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function get_array_comment($comments) {
		$arr_comments = array();
		foreach ($comments as $comment) {
			$arr_comment = array();
			$arr_comment['id'] = $comment->id;
			$arr_comment['last_name_ja'] = $comment->last_name_ja;
			$arr_comment['first_name_ja'] = $comment->first_name_ja;
//			$arr_comment['body'] = addTagA($comment->body); オリジナルデータを変えない。　テンプレート側調整すること
			$arr_comment['body'] = $comment->body;
			$arr_comment['updated_at'] = $comment->updated_at;
			$arr_comments[] = $arr_comment;
		}
		return $arr_comments;
	}

}
