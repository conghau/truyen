<?php
require_once APPPATH.'models/MY_DataMapper.php';
class Comic_ChapterDao extends My_DataMapper {
	var $table = 'manga24h_chapter';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	function getByStoryId($story_id) {
		$this->select("id, name");
	    $this->where('story_id = ', $story_id);
	    $result = $this->get()->all;
		return $result;
	}
}