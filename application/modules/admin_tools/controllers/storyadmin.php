<?php
class StoryAdmin extends MY_Controller
{
	public function __construct() {
		parent::__construct();
		if(!isset($_SESSION)) {
			session_start();
		}
		$this->data['controller'] = "storyadmin";
		$this->setup_form_info($this->data['controller'].'/'.$this->data['language']);
	}
}