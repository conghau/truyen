<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class MY_Form_validation extends CI_Form_validation {
	public function __construct() {
		parent::__construct();
		$this->CI->load->helper('dateutil','language', 'cookie');
	}

	function run($module = '', $group = '') {
		(is_object($module)) AND $this->CI =& $module;
		return parent::run($group);
	}
		
	/**
	 * Match one field to another
	 *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
	 */
	public function password_matches($id, $password)
	{
		if ( ! isset($_POST[$password]))
		{
			return FALSE;
		}
		$this->CI->load->library('userauth');
		$password = $_POST[$password];

		return $this->CI->userauth->login($id, $password);
	}
	
	public function admin_password_matches($id, $password)
	{
		if ( ! isset($_POST[$password]))
		{
			return FALSE;
		}
		$this->CI->load->library('adminauth');
		$password = $_POST[$password];
	
		return $this->CI->adminauth->login($id, $password);
	}
	
	/**
	 * エラー一覧取得
	 * @return boolean|multitype:
	 */
	function error_array()
	{
		if (count($this->_error_array) === 0)
		{
			return FALSE;
		}
		else
			return $this->_error_array;
	
	}
	
	public function confirm_pasword_matches($re_password) {
		$password = $_POST['password'];
		if($re_password === $password) {
			return TRUE;
		}
		return FALSE;
	}

	public function check_date($date) {
		$result = parse_date($date);
		if (!$result || count($result) != 3) {
			return FALSE;
		}
		if(!is_numeric($result[1]) || !is_numeric($result[2]) || !is_numeric($result[0])) {
			return FALSE;
		}
		return checkdate(intval($result[1]), intval($result[2]),intval($result[0]));
	}
	
	/**
	 * かな文字かどうかチェック
	 * @param string $data 文字列
	 * @return integer true(かな) or false
	 */
	function is_kana($text) {
		return preg_match("/^[ーぁ-ん]+$/u", $text) ? TRUE : FALSE;
	}
	
	/**
	 * カタカナかどうかチェック
	 * @param string $data 文字列
	 * @return integer true(かな) or false
	 */
	public function is_katakana($text) {
		return preg_match("/^\p{Katakana}|[ー]+$/u", $text) ? TRUE : FALSE;
	}
	
	/**
	 * 電話番号チェック
	 * @param string $tel
	 * @return boolean
	 */
	public function is_valid_phone_number($phone="") {
		return preg_match("/^0\d{9,10}$/", str_replace("-", "", $phone)) ? TRUE : FALSE;
	}
	
	public function check_expired_date($date){
		$cur_date = date('Y-m-d h:i');
		$expired_date = date('Y-m-d h:i',strtotime($date));
		if ($expired_date <= $cur_date) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function check_birthday($birthday) {
		$date = date_parse($birthday);
		
		if ($date['year'] != FALSE && $date['month'] != FALSE && $date['day'] != FALSE) {
			$birthday = date_create($birthday);
			$current_date = date_create(date("Y-m-d"));
			$date_from = date_create(MIN_DATE);

			if ($birthday <= $current_date && $birthday >= $date_from) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * 管理ツールのログインチェック
	 * @param $type
	 */
	public function check_login_admin($type = '') {
		$base_ssl = str_replace("http:", "https:", base_url());
		$this->data['admin'] = $this->CI->adminauth->getAdmin();
		if (!isset($this->data['admin'])) {
			if ($type == TYPE_AJAX) {
				set_status_header(401);
				exit;
			}
			redirect($base_ssl.'admin_tools/login');
		}
	}
	
	/**
	 * フロントのログインチェック
	 * @param $type
	 */
	public function check_login_user($type = '') {
		$base_ssl = str_replace("http:", "https:", base_url());
		$user = $this->CI->userauth->getUser();
		if (!isset($user)) {
			if ($type == TYPE_AJAX || isAjax()) {
				set_status_header(401);
				exit;
			}
			$exclude_uri = array('/error/', '/file/', '/post/store', '/login');
			// ajax 用フォルダか判定
			if (empty(array_filter($exclude_uri, function($path) {return strpos($_SERVER['REQUEST_URI'], $path) !== false;}))) {
				$cookie = array(
					'name' => 'ru', 
					'value' => substr(preg_replace('/(:\/\/|\.\.+)/', "", $_SERVER['REQUEST_URI']), 0, 32),  // sanitize url
					'path' => '/',
					'expire' => '3600',
					'secure' => true
				);
				set_cookie($cookie);
				log_message('debug', 'ru => '.$cookie['value']);
			}
			redirect($base_ssl.'login');
		}
		
	}
	
	public function check_role($role = ROLE_SUPER_ADMIN) {
		$this->data['role_admin'] = $this->CI->adminauth->getAdmin()->role;
		if ($this->data['role_admin'] == $role) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function check_end_date($end_date ='', $field_start_date='start_date') {
		if ('' == $end_date) {
			return TRUE;
		}
		if (!isset($_POST[$field_start_date]) && trim($_POST[$field_start_date]) == '') {
			return TRUE;
		}
		if(strtotime($_POST[$field_start_date]) <= strtotime($end_date)) {
			return TRUE;
		}
		return FALSE;
	}
	
	public function is_exist($str, $field) {
		return !$this->is_unique($str, $field);
	}
	
	public function email_is_exist($str, $field) {
		return !$this->is_unique($str, $field);
	}
	
	public function is_selected($str) {
		if(!empty($str) && $str != 0 && $str != '') {
			return TRUE;
		}
		return FALSE;
	}
	
	public function check_select_dest($user_id, $group_id) {
		if (empty($user_id) && empty($group_id)) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function check_password($password) {
		if ($this->min_length($password, 7) === FALSE) {
			return sprintf($this->CI->lang->line('min_length'), $this->CI->lang->line('label_admin_password'), 7);
		} elseif ($this->max_length($password, 255) === FALSE) {
			return sprintf($this->CI->lang->line('max_length'), $this->CI->lang->line('label_admin_password'), 255);
		} elseif($this->alpha_numeric($password) === FALSE) {
			return sprintf($this->CI->lang->line('alpha_numeric'), $this->CI->lang->line('label_admin_password'));
		}
		return TRUE;
	}
	
	public function is_unique_email_registered($email, $field) {
		if ($this->is_unique($email, 'users.email') == FALSE) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function is_unique_email_invite($email, $field) {
		$tmp_userdao = new Tmp_UserDao();
		$result = $tmp_userdao->get_by_email($email);
		if ($result->result_count() > 0 && $result->recommend_user_id !== NULL) {
			return FALSE;
		}
		return TRUE;
	}
	
	public function is_unique_email_request($email, $field) {
		$tmp_userdao = new Tmp_UserDao();
		$result = $tmp_userdao->get_by_email($email);
		if ($result->result_count() > 0 && $result->recommend_user_id == NULL) {
			return FALSE;
		}
		return TRUE;
	}
	
	
}
