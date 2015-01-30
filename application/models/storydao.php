<?php
require_once APPPATH.'models/MY_DataMapper.php';
class StoryDao extends My_DataMapper {
	var $table = 'storys';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
}