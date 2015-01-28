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

class UserAuth {

	/**
	 * @var ユーザーオブジェクト
	 **/
    protected $user;

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
		log_message('debug', 'User Auth Libraries');
	}

	/**
	 * ユーザー情報を返す。
	 *
	 * @return $user ユーザー情報
	 **/
	public function getUser() {
		return $this->user;
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

		$ud = new UserDao();
		$user = $ud->login($login_id, hash('sha256',$pass));
		$status = !empty($user);
		if ($status) {
			log_message('debug', sprintf("Logged in: (user_id) %s", $user->id));
			$this->user = $user;
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
		$user_type = 1;
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
		$ud = new UserDao();
		$user = $ud->verify_session($token);
		//$status = !empty($user);
		$status = (isset($user->id))? true : false;
		if ($status) {
			$this->user = $user;
			// 有効性をチェック。
			$this->validate_user($user);
			// セッション日時を更新
			$user_type = 1;
			$expired_at = date( "Y-m-d H:i:s" , time() + 86400 * 7);
			$md = new SessionDao();
			$md->update_session($this->user->user_id, $token, $user_type, $expired_at);
			log_message('debug', "Verify session token => user id: ".$this->user->user_id);
		}
 
		return $status;
	}


	/**
	 * 会員の有効性についてチェック
	 */
	private function validate_user($user) {
		$result = true;
		// チェックする内容があれば、チェック
		return $result;
	}

	/**
	 * ページ配信に対する権限チェック
	 */
	public function check_delivery_type($type = USER_PAGE) {
		$result = true;

		// ページの種別により、会員の状態をチェックする。
		if ($type === USER_PAGE) {
			$result = $this->validate_user($this->user);
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
		log_message('debug', "Update login check status (Logout) => user id: ".$this->user->id);
		$ud = new SessionDao();
		$ud->logout($this->user->id, $token);
		$this->user = null;
	}

	/**
	 * reminder
	 * @param $condition:Array
	 * @return $user
	 */
	public function reminder($condition) {
		$ud = new UserDao();
		$user = $ud->reminder($condition);
		return $user;
	}
	
}