<?php
/**
 * お問い合わせ
 * @name Inquiry
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Inquiry extends MY_Controller {
	
	const SUBJECT = '【QLifeBOX 】 お問い合わせ受付';

	public function __construct() {
		parent::__construct();
		if (!isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');
		$this->lang->load('application');

		$lstTypes= config_item('forminfo')['common']['inquiry_types'];
		$this->data['inquiry_types'] = $lstTypes;
		$this->data['no_had_left'] = true;	
		$this->data['no_had_top_right'] = true;
		$this->data['controller'] = 'inquiry';
	}
	
	/**
	 * お問い合わせ画面の表示
	 */	
	public function index() {
		try {
			$user = $this->userauth->getUser();
			if (!isset($user->id)) {
				$this->data['is_logged_in'] = FALSE;
			} else {
				$this->data['is_logged_in'] = TRUE;
			}
			if ($this->input->post('user_name') !== '') {
				$this->data['user_name'] = $this->input->post('user_name');
			}
			if ($this->input->post('email') !== '') {
				$this->data['email'] = $this->input->post('email');
			}
			$this->data['category'] = $this->input->post('category');
			$this->data['content'] = $this->input->post('content');			
			$this->parse('index.tpl', 'inquiry/index');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * お問い合わせ確認画面の表示
	 */
	public  function confirm() {
		try {
			$inquiry_info = array();
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['inq_info'])) {
					unset($_SESSION['inq_info']);
				}
				redirect($this->data[''].'inquiry');
			}
			if (!isset($this->data['user'])) {
				$this->data['is_logged_in'] = FALSE;
				$this->setup_validation_rules('inquiry/no_login_index');
			}
			else {
				$this->data['is_logged_in'] = TRUE;
				$this->setup_validation_rules('inquiry/index');
			}
			$validate = $this->form_validation->run($this);
			// エラーメッセージが出する場合、前のページに行く
			if (FALSE === $validate) {
				if ($this->input->post('user_name') !== '') {
					$this->data['user_name'] = $this->input->post('user_name');
				}
				if ($this->input->post('mail_address') !== '') {
					$this->data['mail_address'] = $this->input->post('mail_address');
				}
				$this->data['category'] = $this->input->post('category');
				$this->data['content'] = $this->input->post('content');

				$this->parse('index.tpl', 'inquiry/index');
			}
			else {
				$inq_info = array();
				if (isset($this->data['user'])) {
					$inq_info['user_id'] = $this->data['user']->id;
					$inq_info['email'] = $this->data['user']->email;
					$inq_info['user_name'] = user_name($this->data['user'],$this->data['language']);
				}
				else {
					$inq_info['user_id'] = null;
					if ($this->input->post('user_name') !== '') {
						$inq_info['user_name'] = $this->input->post('user_name');
					}
					if ($this->input->post('mail_address') !== '') {
						$inq_info['email'] = $this->input->post('mail_address');
					}
				}
				$lst_inquiry_types = $this->data['inquiry_types'];
				$category = $this->input->post('category');
				foreach ($lst_inquiry_types as $type ) {
					if ($type['id'] == $category) {
						$inq_info['category'] = $type['label'];
						$inq_info['category_id'] = $type['id'];
					}
				}
				$inq_info['subject'] = $this::SUBJECT;
				$inq_info['body'] = $this->input->post('content');
				
				$_SESSION['inq_info'] = $inq_info;
				$this->data['inquiry'] = $inq_info;
				$this->parse('confirm.tpl', 'inquiry/confirm');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * データベースにデータを挿入
	 * 告知ダイアログを表示
	 */
	public function store() {
		try {
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['inq_info'])) {
					unset($_SESSION['inq_info']);
				}
				redirect($this->data[''].'inquiry');
			}
			if (!isset($_SESSION['inq_info'])) {
				set_status_header(417);
				exit;
			}
			$inquiry_info = array();
			$inquiry_info = $_SESSION['inq_info'];
			$inquiry = new InquiryDao(MASTER);
			$result = $inquiry->insert($inquiry_info);
			if (!$result) {
				$msgID = 'L-F-0004-E';
			} else {
				$msgID = 'L-F-0036-I';
						
				$lang = $this->data['language'] ;
				
				$from = MAIL_FROM;
				//sent to secretatiat
				$to = unserialize(MAIL_OF_SECRETARIAT);
				$this->data['user_agent'] = $this->device->get_user_agent();
				$this->data['user_id']	= isset($inquiry_info['user_id']) ? $inquiry_info['user_id'] : '';
				$inquiry_info['category'] = $this->lang->line($inquiry_info['category']);
				$inquiry_info['id'] = $inquiry->id;
				$this->data['inq_info'] = $inquiry_info;
				list($subject, $message) 	= $this->get_mail($lang.'/mail_to_secretariat.tpl');
				$this->send_mail($to, $subject, $message ,$from);
				
				//send email to user
				$to = array($inquiry_info['email']);
				$this->data['user_name'] = $inquiry_info['user_name'];
				list($subject, $message) = $this->get_mail($lang.'/mail_to_user.tpl');
				$this->send_mail($to, $subject, $message ,$from);
			}
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			$msg = $this->lang->line($msgID);
			echo json_encode($msg);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
}
