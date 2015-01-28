<?php
/**
 * @name 設定のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Setting extends MY_Controller{
	
	private $mail_default_check = array(CONFIG_TYPE_RECEIVE_INVITE, CONFIG_TYPE_GROUP_APPROVE, 
									CONFIG_TYPE_THREAD_USER, CONFIG_TYPE_THREAD_GROUP_GROUP_OWNER);
	public function __construct() {
		parent::__construct();
		if(!isset($_SESSION)){
			session_start();
		}
		$this->load->config('forminfo');
		$this->lang->load('application');
		$this->data['controller'] = 'setting';
	}
	
	/**
	 * 設定編集
	 */
	public function index() {
		try {
			
			$configdao = new ConfigDao();
			$blacklist_userdao = new Blacklist_UserDao();
			$groupuserdao = new GroupUserDao();
			$user_id = $this->userauth->getUser()->id;
			$list_config_id = $configdao->get_by_user_id($user_id,CONFIG_CATEGORY_NOTICE);
			$list_config = $this->parse_query($list_config_id);
			list($back_list_user_id,$blacklist_users) = $this->parse_from_query($blacklist_userdao->get_black_list_by_user_id($user_id),'user_all');
			$groups = $groupuserdao->search_list_group($user_id);
			$groups_id = $this->parse_from_query($groups);
			
			$data = array();
			$data['list_config'] = $list_config;
			$data['black_list_user'] = $blacklist_users;
			$data['black_list_user_id'] = json_encode($back_list_user_id);
			$data['list_group'] = $groups;
			$data['list_group_id'] = json_encode($groups_id);
			$data['check'] = STATUS_ENABLE;
			$data['user_id'] = $user_id;
			$data['check_enable_mail'] = json_encode($this->mail_default_check);
			$_SESSION['groups_id'] = serialize($groups_id);
			$_SESSION['black_list_user_id'] = serialize($back_list_user_id);
			$data['msg_save_confirm'] = $this->lang->line('L-F-0038-Q');
			return $data;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * parse from query to array config data
	 */
	private function parse_query($data_query) {
		try {
			$data = array();
			$result = array();
			$list_config  = config_item('forminfo')['common']['config_setting'];
			foreach ($list_config as $config) {
				$data[$config['id']] = STATUS_ENABLE;
			}
			foreach ($data_query as $qr) {
				$data[$qr->target_id] = $qr->status;
			}	
			return $data;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * list user_idをblack_list_userに追加する。
	 */
	private function get_users_add() {
		try {
			$back_list_user_id = unserialize($_SESSION['black_list_user_id']);
			$users_list = $this->input->post('user_list');
			$users_add = array();
			if ($users_list) {
				$users = preg_split('/[,]/',$users_list);
				$data = array();
				foreach ($users as $user) {
					$data['id'] = $user;
					if (count($back_list_user_id) == 0 || in_array($data,$back_list_user_id) == FALSE) {
						array_push($users_add, $data);
					}
				}
			}
			return $users_add;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 削除されたユーザ一覧を取得する。
	 */
	private function get_users_delete() {
		try {
			$back_list_user_id =  unserialize($_SESSION['black_list_user_id']);
			$users_list = $this->input->post('user_list');
			$users_delete = array();
			if ($users_list) {
				$users = preg_split('/[,]/',$users_list);
				$users = $this->parse_array($users);
				$data = array();
				foreach ($back_list_user_id as $user) {
					$data['id'] = intval($user['id']); 
					if (in_array($data,$users) == FALSE) {
						array_push($users_delete, $user);
					}
				}
				return $users_delete;
			}
			return $back_list_user_id;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 削除されたグループ一覧を取得する。
	 */
	private function get_groups_delete() {
		try {
			$groups_id = unserialize($_SESSION['groups_id']);
			$groups_list = $this->input->post('group_list');
			$groups_delete = array();
			if ($groups_list) {
				$groups = preg_split('/[,]/',$groups_list);
				$groups = $this->parse_array($groups);
				$data = array();
				foreach ($groups_id as $group) {
					$data['id'] = intval($group['id']);
					if (in_array($data,$groups) == FALSE) {
						array_push($groups_delete, $data);
					}
				}
				return $groups_delete;
			}
			return $groups_id;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * parse list id to array data 
	 */
	private function parse_array($ids) {
		try {
			$data = array();
			$list_id = array();
			foreach ($ids as $id) {
				$data['id'] = $id;
				array_push($list_id,$data);
			}
			return $list_id;
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * parse from query to array
	 */
	private function parse_from_query($data_query, $mode='id') {
		$list_id = array();
		$list_users = array();
		$data = array();
		$language = $this->data['language'];
		foreach ($data_query as $qr) {
			$data['id'] = $qr->id;
			array_push($list_id,$data);
			if($mode == 'user_all') {
				$data['name'] = user_name($qr,$language);
				$data['organization'] = $qr->organization;
				$data['position'] = $qr->position;
				array_push($list_users,$data);
			}
		}
		if ($mode == 'user_all') {
			return array($list_id,$list_users);
		} else {
			return $list_id; 
		}
	}
	
	/**
	 * 設定を更新する。
	 */
	public function save() {
		try {
			// Check login
			$this->form_validation->check_login_user(TYPE_AJAX);
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_SESSION['black_list_user_id'])) {
					unset($_SESSION['black_list_user_id']);
				}
				if (isset($_SESSION['groups_id'])) {
					unset($_SESSION['groups_id']);
				}
				set_status_header(417);
			}
			$user_id = $this->data['user']->id;
			$this->update_setting_language($user_id);
			$this->update_blacklist_user($user_id);
			$this->update_groups_user($user_id);
			$this->update_setting_mails();
			$info = array();
			$info['message'] = $this->lang->line('L-F-0005-I');
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($info);
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 宛先の名前を取得する
	 */
	public function get_user_by_id() {
		try {
			// Check login
			$this->form_validation->check_login_user(TYPE_AJAX);
			$users = $_REQUEST['user_add'];
			$arr_user_id = explode(",", $users);
			$list = array();
			$language = $this->data['language'];
			if ($arr_user_id) {
				$userdao = new UserDao();
				$users = $userdao->get_user_by_id_in($arr_user_id);
				$index = 0;
				foreach($users as $user) {
					$list[$index]['id'] = $user->id ;
					$list[$index]['name'] = user_name($user,$language);
					$list[$index]['organization'] = isset($user->organization)?$user->organization:'';
					$list[$index]['position'] = isset($user->position)?$user->position:'';
					$index ++;
				}
			}
			echo json_encode($list);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	public function delete_user() {
		try {
				// Check login
				$this->form_validation->check_login_user(TYPE_AJAX);
				$user_id = $_REQUEST['userId'];
				$userdao = new UserDao(MASTER);
				$userdao->delete_user($user_id);
				return ;
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 退会完了
	 */
	public function withdraw_finish() {
		$this->data['no_had_left'] = true;
		$this->parse('withdraw.tpl', 'setting/withdraw');
	}
	
	/**
	 * blacklist_usersを更新する。
	 */
	private function update_blacklist_user($user_id) {
		try {
			$blacklist_userdao = new Blacklist_UserDao(MASTER);
			$users_add = $this->get_users_add();		
			$users_delete = $this->get_users_delete();
			$blacklist_userdao->update_blacklist_user($user_id,$users_add,$users_delete);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * ユーザーグループ一覧を更新する。
	 */
	private function update_groups_user($user_id) {
		try {
			$result = FALSE;
			$groupuserdao = new GroupUserDao(MASTER);
			$groups_delete = $this->get_groups_delete();
			$result = $groupuserdao->remove_group_user($user_id,$groups_delete);
			if ($result) {
				$this->on_group_leave($user_id, $groups_delete);
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * グループ退会時にアクティビティログを出力する
	 */
	private function on_group_leave($user_id,$groups_id) {
		try {
			foreach ($groups_id as $group) {
				$activitylogdao = new ActivityLogDao(MASTER);
				$activitylogdao->on_group_leave($user_id,$group['id']);
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 設定言語を更新する。
	 */
	private function update_setting_language($user_id) {
		try {
			$language = $this->input->post('language');
			if ($language && !is_null($language)) {
				$record_data['language'] = $language;
				$userdao = new UserDao(MASTER);
				$result = $userdao->update_user($user_id, $record_data);
				
				if ($result) {
					$this->input->set_cookie(array(
							'name'   => 'lang',
							'value'  => substr($language, 0, 26), // 2６文字以内
							'expire' => '86500', // １日
							'domain' => $_SERVER['SERVER_NAME'],
							'path'   => '/',
							//		'secure' => TRUE
					));
				}
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * 言語設定を取得する。
	 */
	private function get_language($user_id) {
		try {
			$userdao = new UserDao();
			$result = $userdao->get_by_id($user_id);
			return $result->language;
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * configs設定を更新する。
	 */
	private function update_setting_mails() {
		try {
			$configdao = new ConfigDao(MASTER);
			$list_config  = config_item('forminfo')['common']['config_setting'];
			$setting_checks = $this->input->post('setting_mail');
			$user_id = $this->userauth->getUser()->id;
			$list_mail_setting = array();
			$data = array();
			
			foreach ($list_config as $config) {
				$data['id'] = $config['id'];
				$data['user_id'] = $user_id;
				$data['category_id'] = CONFIG_CATEGORY_NOTICE;
				if (($setting_checks == FALSE || in_array($config['id'],$setting_checks) == FALSE) 
						&& in_array($config['id'],$this->mail_default_check)==FALSE) {
					$data['check'] = STATUS_DISABLE;
				} else {
					$data['check'] = STATUS_ENABLE;
				}
				array_push($list_mail_setting,$data);
			}
			$configdao->update_config($list_mail_setting);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
}
