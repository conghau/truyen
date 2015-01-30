<?php
require_once APPPATH.'models/MY_DataMapper.php';
class Story_DetailDao extends My_DataMapper {
	var $table = 'story_details';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	function getByStoryId($story_id) {
	    $sqlWhere['id'] = 2;
	    //$this->where($sqlWhere);
	    $result = $this->get();
		return $result;

	}

}