<?php
require_once APPPATH.'models/MY_DataMapper.php';
class Comic_ImageDao extends My_DataMapper {
	var $table = 'manga24h_image';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	function getByChapterId($chapter_id) {
		$this->select("*");
	    $this->where('chapter_id = ', $chapter_id);
	    $result = $this->get()->all;
		return $result;
	}
}