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
			echo '1';
// 		    $objStoryDao = new StoryDao();
// 		    //pagination
// 		    $url = $this->data['base_url']."story/paginate";
// 		    $uri_segment = 3;
// 		    $total_records = $objStoryDao->count_by_condition();
// 		   	$limit = $this->create_pagination($total_records, '', $url, $uri_segment);
// 		   	$this->data['list_story'] = $objStoryDao->search('',$limit);
		   	
// 		    $this->parse('index.tpl', 'user/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	public function category($category_id = 0)
	{

	}
	
	public function view_story($story_id = null) {
		try {
			$objStoryDao = new StoryDao();
			$this->data['story_title'] =url_friendly($objStoryDao->getFIELD_byId('title',$story_id)->title);
			echo $this->data['story_title'];
			$objStoryDetailDao = new Story_DetailDao();
			$this->data['lstChapter'] = $objStoryDetailDao->getByStoryId($story_id);
			$this->parse('story_introduce.tpl', 'user/story');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	public function view_chapter($chapter_id) {
		try {
			$objStoryDetailDao = new Story_DetailDao();
			
			$chapter = $objStoryDetailDao->get_by_id($chapter_id);
			$lst_chapter = $objStoryDetailDao->getByStoryId($chapter->story_id);
			$this->data['lstChapter'] = $lst_chapter;
			$this->data['chapter_content'] = $chapter;
			
			$this->parse('view_chapter.tpl', 'user/story');
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
