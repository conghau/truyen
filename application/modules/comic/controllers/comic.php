<?php
/**
 * @name 利用者のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Comic extends MY_Controller {
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
 		    $objStoryDao = new ComicDao();
 		    //pagination
 		    $url = $this->data['base_url']."story/paginate";
 		    $uri_segment = 3;
 		    $total_records = $objStoryDao->count_by_condition();
 		   	$limit = $this->create_pagination($total_records, '', $url, $uri_segment);
 		   	$this->data['list_story'] = $objStoryDao->search('',$limit);
		   	echo $total_records;
 		    $this->parse('index.tpl', 'user/index');
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
			$objStoryDao = new ComicDao();
			$this->data['story_detail'] =$objStoryDao->get_by_id($story_id);

			$objStoryDetailDao = new Comic_ChapterDao();
			$this->data['lst_chapter'] = $objStoryDetailDao->getByStoryId($story_id);

			$this->parse('story_introduce.tpl', 'user/story');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	public function view_chapter($chapter_id, $story_id) {
		try {
			$objComicChapterDao = new Comic_ChapterDao();
			$objComicImageDao = new Comic_ImageDao();
			$objComicDao = new ComicDao();
			$this->data['comic_title'] = $objComicDao->getFIELD_byId('title', $story_id)->title;
			$lst_chapter = $objComicChapterDao->getByStoryId($story_id);
			$this->data['lst_chapter'] = $lst_chapter;
			$lst_image = $objComicImageDao->getByChapterId($chapter_id);
			$this->data['lst_image'] = $lst_image;

			$this->data['chapter_id'] = $chapter_id;
			$this->data['story_id'] = $story_id;
			$this->parse('view_chapter.tpl', 'user/story');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
}
