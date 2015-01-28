<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ユーザー認証ライブラリ
　* Name:  User Auth
　* Author: Takeo Noda
　* Created:  2012/05/15
　*
　* Requirements: PHP5 or above
　*
　*/

class AdminAuth {

	/**
	 * @var ユーザーオブジェクト
	 **/
    protected $admin;

	/**
	 * @var 会員取得状態 (true あり / false なし)
	 **/
	protected $status;

	/**
	 * コンストラクタ
	 *
	 * @return void
	 * @author Ben
	 **/
	public function __construct() {
		log_message('debug', 'Admin Auth Libraries');
	}

	/**
	 * ユーザー情報を返す。
	 *
	 * @return $user ユーザー情報
	 **/
	public function getAdmin() {
		return $this->admin;
	}
	
	/**
	 * ログイン処理を行う
	 *
	 * @param $login_id ログインID
	 * @param $pass パスワード
	 * @return $user ユーザー情報
	 **/
	public function login($login_id, $pass) {
		log_message('debug', sprintf("Check id / pass: %s / %s", $login_id, $pass));

		$ad = new AdminDao();
		$admin = $ad->login($login_id, hash('sha256',$pass));
		$status = !empty($admin);
		if ($status) {
			log_message('debug', sprintf("Logged in: (admin_id) %s", $admin->id));
			$this->admin = $admin;
		}
		return $status;
	}

	/**
	 * セッション発行処理を行う
	 *
	 * @param $id ユーザーID
	 * @return $token セッショントークン
	 **/
	public function publish_session($id, $login_flag = 0) {
		$user_type = 2;
		$expired_at = time2datetime(time() + 86400 * 7);
		$token = md5(uniqid(mt_rand(), true));

		log_message('debug', 'make SESSION token');
		$retry = 5;
		for ($xi = 0; $xi < $retry; $token = md5(uniqid(mt_rand(), true))) {
			$vmd = new SessionDao();
			$sess = $vmd->find_by_token($token);
			if (empty($sess)) {
				$xi += $retry; // 終わり
			}
			$xi++;
			log_message('debug', 'retry making session ... '.$xi);
		}
		log_message('debug', 'UNIQ SESSION:'.$sess);
		$md = new SessionDao();
		$md->update_session($id, $token, $user_type, $expired_at);
		return $token;
	}

	/**
	 * トークンの有効性をチェックしてユーザー情報をセットする
	 * 
	 * @param $token セッションID
	 * @return $status 有効状態
	 */
	public function verify_session($token) {
		$CI =& get_instance();
		$CI->load->helper('dateutil');
		$ad = new AdminDao();
		$admin = $ad->verify_session($token);
		//$status = !empty($admin);
		$status = (isset($admin->id))? true : false;
		if ($status) {
			$this->admin = $admin;
			// 有効性をチェック。
			$this->validate_admin($admin);
			// セッション日時を更新
			$admin_type = 2;
			$expired_at = time2datetime(time() + 86400 * 7);
			$session = new SessionDao();
			$session->update_session($this->admin->user_id, $token, $admin_type, $expired_at);
			log_message('debug', "Verify session token => admin id: ".$this->admin->user_id);
		}
 
		return $status;
	}


	/**
	 * 会員の有効性についてチェック
	 */
	private function validate_admin($user) {
		$result = true;
		// チェックする内容があれば、チェック
		return $result;
	}

	/**
	 * ページ配信に対する権限チェック
	 */
	public function check_delivery_type($type = ADMIN_USER_PAGE) {
		$result = true;

		// ページの種別により、会員の状態をチェックする。
		if ($type === ADMIN_USER_PAGE) {
			$result = $this->validate_admin($this->admin);
		}
		return $result;
	}

	/**
	 * ログアウト処理を行う。
	 *
	 * @param $token セッションID
	 * @return void
	 **/
	public function logout($token) {
		log_message('debug', "Update login check status (Logout) => admin id: ".$this->admin->id);
		$ud = new SessionDao();
		$ud->logout($this->admin->id, $token);
		$this->admin = null;
	}

	/**
	 * reminder
	 * @param $condition:Array
	 * @return $user
	 */
	public function reminder($condition) {
		$ad = new AdminDao();
		$admin = $ad->reminder($condition);
		return $admin;
	}
	
}