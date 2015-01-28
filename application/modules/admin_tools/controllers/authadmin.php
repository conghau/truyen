<?php

class AuthAdmin extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->lang->load('application');
    }
	
	public function login() { 
		// リクエストを処理
		$this->setup_login_parameter();
		// auth/login をキーとしたバリデーションの設定を formvalidations.yamlから取得
		$this->setup_validation_rules("authadmin/login"); 
		log_message('debug', 'setup login controller');
		// ログイン済みの場合、トップページへ飛ばす
		if ($this->adminauth->getAdmin()) {
			redirect($this->data[''] . "admin_tools");
		}
		$validate = $this->input->post('authadmin') !== false && $this->form_validation->run(); // ここでログイン認証を実施(password_matches)
		if ($validate) {
			//ログイン状態に更新
			$this->setup_cookie();
			// ログイン成功したらトップページへ飛ばす。
			redirect($this->data[''] . "admin_tools");
		} else {
			// ログイン失敗したら、ログイン画面を表示
			$this->parse('login.tpl','authadmin/login');
		}
	}
	
	public function logout() {
		$admin = $this->adminauth->getAdmin(); // データ取得済みかチェック
		$session_id = $this->input->cookie(COOKIE_ADMIN_SESSION);
		$this->adminauth->logout($session_id);
		log_message('authadmin', sprintf("%s\t%s\t%s", 'logout', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $session_id, 'admin_id' => array_selector('id', $admin)))));
		$cookie = array(
			'name' => COOKIE_ADMIN_SESSION,
			'value' => '',
			'path' => '/',
			'expire' => -1
			);
		set_cookie($cookie);
		redirect($this->data['ssl_base_url'].'admin_tools/login');
	}
	
	private function setup_login_parameter() {
		// リクエストキー名と初期値を設定する。
		$target_list = array('admin_id' => '', 'password' => '', 'login_flag' => '1');
		foreach ($target_list as $target => $value) {
			if ($this->input->post($target)) {
				$this->data[$target] = $this->input->post($target); // ボタン押下時（値あり）
			} else if ($this->input->post('authadmin')) {
				$this->data[$target] = ""; // ボタン押下時（値なし）
			} else {
				$this->data[$target] = $value; // 初回時
			}
		}
	}

	private function setup_cookie() {
		$admin = $this->adminauth->getAdmin(); // データ取得済みかチェック
		if (!$admin) {
			redirect($this->data[''] . "admin_tools"); // 会員情報がない場合、トップへ飛ばす
		}
		$login_flag = $this->input->post('login_flag') ? 1 : 0;
		$uniq = $this->adminauth->publish_session($admin->id, $login_flag);
		$cookie = array(
				'name' => COOKIE_ADMIN_SESSION,
				'value' => $uniq,
				'expire' => 86400 * 7 * $login_flag, // login_flag = 0 のときは expire も 0 / login_flag = 1 のときは、そのまま７日先の日付を指定
				'path' => '/'
			);
		set_cookie($cookie);
		log_message('authadmin', sprintf("%s\t%s\t%s", 'login', $_SERVER['HTTP_USER_AGENT'], json_encode(array('session_id' => $uniq, 'admin_id' => $admin->id))));
	}		
}
