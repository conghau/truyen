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
		    $objStoryDao = new StoryDao();
		    //var_dump($objStoryDao->get());die;
		    $this->data['list_story'] = $objStoryDao->get()->all;
		    // 			foreach ($recordset as $record) {
		    // 			    $arr = array();
		    // 			    $arr['name'] = $record->tacgia;
		    // 			    array_push($arrData, $arr);
		     
		    // 			}
		    // 			$objAuthorDao->insert_bulk($arrData);
		    //echo 'xong';
		    //$this->data['list_author'] = $recordset;
		    //var_dump($result);
		    //die;
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
		try {$this->output->enable_profiler(TRUE);
			//$objStoryDao = new StoryDao();
			$objStoryDetailDao = new Story_DetailDao();
            var_dump($story_id);
			//$this->data['story'] = $objStoryDao->get_by_id($story_id);
			var_dump($objStoryDetailDao->getByStoryId($story_id));
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
