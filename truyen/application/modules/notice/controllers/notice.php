<?php

class Notice extends MY_Controller {
	const PRELOAD_NUMBER = 50;
	const LOAD_NUMBER = 10;
	
	public function __construct() {
		parent::__construct();
		if (!isset($_SESSION)) {
			session_start();
		}
		$this->lang->load('application');
	}

	public function index() {
		try {
			$user = $this->userauth->getUser();
			if (!isset($user)){
				$this->data['auth'] = false;
			} else {
				$this->data['auth'] = true;
				$notices = $this->get_notice_list($user, 0, $this::PRELOAD_NUMBER);								
				$this->data['notices'] = $notices;
			}
			$this->parse('index.tpl', 'notice/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	public function get() {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
			$user = $this->userauth->getUser();

			$offset = ($this->input->get('offset')) ? $this->input->get('offset') : 0;
			$limit = ($this->input->get('limit')) ? $this->input->get('limit') : $this::LOAD_NUMBER;
			$results = $this->get_notice_list($user, $offset, $limit);
			header('Content-Type: application/json');
			echo json_encode($results);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function get_notice_list($user, $offset, $limit) {
		$cache_path = sprintf("user/%s/notice/%s/%s", $user->id, $offset, $limit);
		$results = $this->load_cache($cache_path);
		if (!empty($results)) {
			return $results;
		}
		$noticedao = new NoticeDao(MASTER);
		$notices = $noticedao->get_notice_by_user($user->id, $offset, $limit);
		$results = array();
		foreach ($notices as $notice) {
			$result = array();
			$result['id'] = $notice->id;
			$result['message'] = $noticedao->parse_message($notice->message);
			$result['link'] = $this->data[''] . $notice->link;
			$result['status'] = ($notice->status == STATUS_NOTICE_UNREAD) ? 'unread' : 'read';
			array_push($results, $result);
		}
//		$this->save_cache($cache_path, $results);
		return $results;		
	}
	
	public function set_read($notice_id = '') {
		try {
			$this->form_validation->check_login_user(TYPE_AJAX);
	
			$user = $this->userauth->getUser();
			
			if ($notice_id != '' && ctype_digit($notice_id)){
				$noticedao = new NoticeDao(MASTER);
				$results = $noticedao->set_notice_read($user->id, $notice_id);
				echo $results;
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

}
