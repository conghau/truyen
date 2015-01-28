<?php

/**
 * @name 承認管理のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */

define('UNTREATED_MEMBER', 0);
define('ENABLE_MEMBER', 1);
define('DENIAL_MEMBER', 2);
define('HOLD_MEMBER', 3);
define('REGISTERED_TYPE', 3);
define('PER_PAGE', 20);

class Approve extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->config('forminfo');

		$this->data['auth_method'] = config_item('forminfo')['common']['auth_method'];
		$this->data['status_types'] = config_item('forminfo')['common']['status_types'];
		$this->data['gender_types'] = config_item('forminfo')['common']['profile']['gender_types'];
		if(!isset($_SESSION)) {
			session_start();
		}
		$this->data['controller'] = "approve";
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * 一覧画面を表示する。
	 */
	public function index() {
		try {
			$this->form_validation->check_login_admin();
			if ($this->input->post('flag') === FALSE) {
				$registered_status = UNTREATED_MEMBER;
			} else {
				$registered_status = $this->input->post('flag');
			}
			$this->get_data($registered_status);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 一覧画面を表示する。
	 */
	public function load_index() {
		try {
			$this->form_validation->check_login_admin();
			if (!isset($_SESSION['flag'])) {
				$registered_status = UNTREATED_MEMBER;
			} else {
				$registered_status = $_SESSION['flag'];
			}
			$this->get_data($registered_status);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 画面に表示するデータを作成する。
	 *  @param integer $registered_status
	 */
	public function get_data($registered_status) {
		$_SESSION['flag'] = $registered_status;

		$userdao = new UserDao();
		$condition['case']				=	"approve";
		$condition['registered_type']	=	REGISTERED_TYPE;
		$condition['registered_status'] = UNTREATED_MEMBER;
		$total_records[UNTREATED_MEMBER] = $userdao->count_by_condition($condition);
		$condition['registered_status'] = DENIAL_MEMBER;
		$total_records[DENIAL_MEMBER] = $userdao->count_by_condition($condition);
		$condition['registered_status'] = HOLD_MEMBER;
		$total_records[HOLD_MEMBER] = $userdao->count_by_condition($condition);

		$url = $this->data[''] . "admin_tools/approve/paginate";
		$uri_segment					=	4;
		$condition['per_page']			=	PER_PAGE;
		$condition['registered_status'] = $registered_status;
		$limit = $this->create_pagination_admin( $total_records[$registered_status], $condition, $url, $uri_segment);
		$result = $userdao->search($condition,$limit);

		$this->data['total_records'] = $total_records;
		$this->data['list_users'] = $result;
		$this->data['total_pages'] = $result->result_count();
		$this->data['flag'] = $registered_status;

		header_remove("Cache-Control");
		$this->parse('approve.tpl', 'approve/index');
	}
	
	/**
	 * ユーザ編集画面の表示処理
	 *  @param integer $id
	 */
	public function edit($id = null) {
		try {
			$this->form_validation->check_login_admin();
			$userdao = new UserDao();
			$result = $userdao->get_user_approve($id);
			if ($result->result_count() == 0) {
				if (isset($_SESSION['flag'])){
					unset($_SESSION['flag']);
				}
				redirect($this->data[''].'admin_tools/approve');
			}

			$qualification = new QualificationDao();
			$qualification_name = $qualification->get_by_id($result->qualification_id)->name;

			$approve_info['id'] = $id;
			$approve_info['admin_id'] = $this->adminauth->getAdmin()->id;
			$approve_info['email'] = $result->email;
			$_SESSION['approve_info'] = $approve_info;
			
			$this->data['user_info'] = $result;
			$this->data['qualification_name'] = $qualification_name;
			$this->parse('approve_edit.tpl', 'approve/edit');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ編集完了画面の表示処理。
	 */
	public function update() {
		try {
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if($_SERVER['REQUEST_METHOD'] !== 'POST'){
				if (isset($_SESSION['approve_info'])){
					unset($_SESSION['approve_info']);
				}
				redirect($this->data[''].'admin_tools/approve');
			} 

			$flag = $this->input->post('flag');
			$approve_info = $_SESSION['approve_info'];

			$userdao = new UserDao(MASTER);
			$result = $userdao->update_approve($approve_info, $flag);
			
			if (!$result) {
				$message = $this->lang->line('L-A-0006-E');
			} else {
				$message = $this->lang->line('L-A-0005-I');
				if ($flag == 1) {
					$user = new UserDao();
					$user_info = $user->get_by_id($approve_info['id']);
					$aldao = new ActivityLogDao(MASTER);
					$aldao->on_user_join($user_info);
					
					$lang = $user->language;
					
					$this->data['name'] = user_name($user_info, $lang);
					$from = MAIL_FROM;
					$email = $approve_info['email'];
					$to = array($email);
					list($subject, $message_mail) = $this->get_mail($lang.'/email_approve_to_user.tpl');
					$this->send_mail($to, $subject, $message_mail ,$from);
				}
 			}
 			unset($_SESSION['approve_info']);
 			$this->clear_csrf(); // 使った CSRFトークン をクリア
 			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザ削除完了画面の表示処理
	 * @param integer $id
	 */
	public function delete($id){
		try {
			$this->form_validation->check_login_admin(TYPE_AJAX);
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['approve_info'])){
					unset($_SESSION['approve_info']);
				}
				redirect($this->data[''].'admin_tools/approve');
			}

			$id = $_SESSION['approve_info']['id'];

			$userdao = new UserDao(MASTER);
			$userdao->id = $id;
			$result = $userdao->delete();

			if (!$result) {
				$message = $this->lang->line('L-A-0002-E');
			} else {
				$message = $this->lang->line('L-A-0001-I');
			}
			unset($_SESSION['approve_info']);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
}
