<?php

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->lang->load('application');
		$this->data['no_had_left']= TRUE;
		$this->data['controller']= 'login';
    }
	
	public function login() {
		$redirect_url = $this->input->cookie('ru');
		// リクエストを処理
		$this->setup_login_parameter(); 
		// auth/login をキーとしたバリデーションの設定を formvalidations.yamlから取得
		$this->setup_validation_rules("auth/login"); 
		log_message('debug', 'setup login controller');
		// ログイン済みの場合、トップページへ飛ばす
		if ($this->userauth->getUser()) {
			redirect(""); 
		}
		$validate = $this->input->post('auth') !== false && $this->form_validation->run(); // ここでログイン認証を実施(password_matches)
		$this->data['validate'] = $validate;
		if ($validate) {
			//ログイン状態に更新
			$this->setup_cookie();
			// ログイン成功したらトップページへ飛ばす。
			log_message('debug', '[AUTH REDIRECT TARGET]'.$redirect_url);
			redirect($redirect_url); 
		} else {
			// ログイン失敗したら、ログイン画面を表示
			$this->parse('login.tpl','auth/login');
		}
	}
	
	public function logout() {
		$user = $this->userauth->getUser(); // データ取得済みかチェック
		$session_id = $this->input->cookie(COOKIE_SESSION);
		$this->userauth->logout($session_id);
		log_message('auth', sprintf("%s\t%s\t%s", 'logout', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $session_id, 'user_id' => array_selector('id', $user)))));
		$cookie = array(
			'name' => COOKIE_SESSION,
			'value' => '',
			'path' => '/',
			'expire' => -1
			);
		set_cookie($cookie);
		redirect(""); // トップへ飛ばす
	}
	
	public function pass_reissue() {
		if ($this->userauth->getUser()) {
			redirect("");
		}
		$this->data['controller']= 'pass_reissue';
		$this->data['txt_email']	= '';
		$this->data['txt_year']		= '';
		$this->data['txt_month']	= '';
		$this->data['txt_day']		= '';
		$this->parse('pass_reissue.tpl','auth/pass_reissue');
	}
	
	public function pass_reissue_finish() {
		try {
			if ($this->userauth->getUser()) {
				redirect("");
			}
			$this->data['controller']= 'pass_reissue';
			$this->setup_validation_rules('auth/pass_reissue_finish');
			$validation = $this->form_validation->run($this);
			if ($validation === FALSE) {
				$this->data['txt_email']	= $this->input->post('txt_email');
				$this->data['txt_year']		= $this->input->post('txt_year');
				$this->data['txt_month']	= $this->input->post('txt_month');
				$this->data['txt_day']		= $this->input->post('txt_day');
				$this->parse('pass_reissue.tpl','auth/pass_reissue');
				return;
			}
			
			$email	= $this->input->post('txt_email');
			$year	= $this->input->post('txt_year');
			$month	= $this->input->post('txt_month');
			$day	= $this->input->post('txt_day');
			$userdao = new UserDao(MASTER);
			$user_info = $userdao->get_by_email($email);
			$birthday = date_parse($user_info->birthday);
			if ($birthday['year'] !== intval($year) || $birthday['month'] !== intval($month)
					|| $birthday['day'] !== intval($day)) {
				$this->data['error'] = $this->lang->line('email_birthday_not_match');
				$this->data['txt_email']	= $this->input->post('txt_email');
				$this->data['txt_year']		= $this->input->post('txt_year');
				$this->data['txt_month']	= $this->input->post('txt_month');
				$this->data['txt_day']		= $this->input->post('txt_day');
				$this->parse('pass_reissue.tpl','auth/pass_reissue');
				return;
			}
			$password_new = random_string('alnum', 16);
			$user['password'] = hash('sha256',$password_new);
			$result = $userdao->update_user($user_info->id, $user);
			if ($result === TRUE) {
				$from = MAIL_FROM;
				$to =array($email);
				$lang = $user_info->language;
				$this->data['name'] = user_name($user_info, $lang);
				$this->data['password'] = $password_new;
				list($subject, $message) 	= $this->get_mail($lang.'/email_password_reissue.tpl');
				$this->send_mail($to, $subject, $message ,$from);
				
				$this->parse('pass_reissue_finish.tpl','auth/pass_reissue_finish');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		} 	
	}
	
	private function setup_login_parameter() {
		// リクエストキー名と初期値を設定する。
		$target_list = array('user_id' => '', 'password' => '', 'login_flag' => '1');
		foreach ($target_list as $target => $value) {
			if ($this->input->post($target)) {
				$this->data[$target] = $this->input->post($target); // ボタン押下時（値あり）
			} else if ($this->input->post('auth')) {
				$this->data[$target] = ""; // ボタン押下時（値なし）
			} else {
				$this->data[$target] = $value; // 初回時
			}
		}
	}

	private function setup_cookie() {
		$user = $this->userauth->getUser(); // データ取得済みかチェック
		if (!$user) {
			redirect(""); // 会員情報がない場合、トップへ飛ばす
		}
		$login_flag = $this->input->post('login_flag') ? 1 : 0;
		$uniq = $this->userauth->publish_session($user->id, $login_flag);
		$cookie = array(
				'name' => COOKIE_SESSION,
				'value' => $uniq,
				'expire' => 86400 * 7 * $login_flag, // login_flag = 0 のときは expire も 0 / login_flag = 1 のときは、そのまま７日先の日付を指定
				'path' => '/'
			);
		set_cookie($cookie);
		log_message('auth', sprintf("%s\t%s\t%s", 'login', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $uniq, 'user_id' => $user->id))));
		
		$this->input->set_cookie(array(
				'name'   => 'lang',
				'value'  => substr($user->language, 0, 26), // 2６文字以内
				'expire' => '86500', // １日
				'domain' => $_SERVER['SERVER_NAME'],
				'path'   => '/',
		//		'secure' => TRUE
		));
	}		
}
