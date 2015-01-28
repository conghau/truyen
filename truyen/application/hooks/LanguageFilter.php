<?php
if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
class LanguageFilter {
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	function process() {
		if ($this->CI->uri->segment(1) == 'admin_tools') {
			$this->CI->config->set_item('language',	'japanese');
			$this->CI->data['language'] = 'japanese';
		} else {
			$lang_target = array('english', 'japanese');
			$req_lang = $this->CI->input->get_post('lang');
			$cookie_lang = $this->CI->input->cookie('lang');
			$user_lang = isset($this->CI->data['user']) ? array_selector('language', $this->CI->data['user'], '') : '';
			log_message('debug', sprintf("Language: [user]%s; [cookie]%s; [request]%s", $user_lang, $cookie_lang, $req_lang));

			// step 1. リクエストをチェック
			if (in_array($req_lang, $lang_target)) {
				$this->CI->input->set_cookie(array(
					'name'   => 'lang',
					'value'  => substr($req_lang, 0, 26), // 2６文字以内
					'expire' => '86500', // １日
					'domain' => $_SERVER['SERVER_NAME'],
					'path'   => '/',
			//		'secure' => TRUE
				));
				$this->CI->config->set_item('language',	$req_lang);
				$this->CI->data['language'] = $req_lang;
			// step 2. ログイン情報をチェック
			} else if (in_array($user_lang, $lang_target)) {
				$this->CI->config->set_item('language', $user_lang);
				$this->CI->data['language'] = $user_lang;
			// step 3. クッキーをチェック
			} else if (in_array($cookie_lang, $lang_target)) {
				$this->CI->config->set_item('language',	$cookie_lang);
				$this->CI->data['language'] = $cookie_lang;
			}
		}
	}
}
