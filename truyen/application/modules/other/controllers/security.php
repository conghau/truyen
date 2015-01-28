<?php
/**
 * @name セキュリティのコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Security extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 機能とセキュリティ
	 */
	public function index() {
		try {
			$this->data["no_had_left"] = true;
			$this->data["no_had_top_right"] = true;
			$this->parse('security.tpl','security/index');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}

}
