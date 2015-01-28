<?php
/**
 * @name 利用規約のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Terms extends MY_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * 利用規約
	 */
	public function index() {
		try {
			$this->data["no_had_left"] = true;
			$this->data["no_had_top_right"] = true;
			$this->parse('terms.tpl','terms/index');
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
}
