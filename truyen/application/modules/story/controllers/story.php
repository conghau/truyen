<?php
/**
 * @name 利用者のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Story extends MY_Controller {
	public function __construct() {
		try {
			parent::__construct();
			if (!isset($_SESSION)) {
				session_start();
			}
			$this->load->config('forminfo');
			$this->load->helper('form', 'url');
			$this->lang->load('application');
			$this->load->library('upload');
			
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 個人 ダッシュボード画面の表示処理
	 * @param $user_id 利用者ＩＤ
	 */
	public function index($user_id = null) {
		try {
// 			echo 'davao';die;
// 			// 検索による絞り込みに対応
// 			$this->data['keyword'] = substr($this->input->post('keyword'), 0, MAX_SEARCH_WORD_LENGTH);
// 			if (!empty($this->data['keyword'])) {
// 				$posts_tmp = $postdao->search_post_detail_by_user_and_keyword($user->id, $this->input->post('keyword'), $offset, $limit);
// 			} else {
// 				$posts_tmp = $postdao->get_post_detail_by_user($user->id, $offset, $limit);
// 			}
// 			$posts = $postdao->parse_post_detail($user, $posts_tmp);

// 			$this->data['posts'] = $posts;
// 			$offset += count($posts);
// 			$this->data['post_offset'] = $offset;
// 			$this->data['post_query_url'] = $this->data[''] . 'user/get_post';
// 			$this->data['send_to_id'] = $user_id; 
			$this->parse('index.tpl', 'user/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	public function view_story($story_id = null) {
		try {
			$this->parse('story_introduce.tpl', 'user/story');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	public function view_file($post_id = null) {
		try {
			$this->setup_view_post($post_id);
			$this->data['post'] = array_shift($this->data['posts']);
			$this->parse('file.tpl', 'user/file');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
}
