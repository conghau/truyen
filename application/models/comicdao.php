<?php
require_once APPPATH.'models/MY_DataMapper.php';
class ComicDao extends My_DataMapper {
	var $table = 'manga24h_story';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	function getFIELD_byId($field_name = 'id', $id) {
		$this->select($field_name);
		$this->where('id = ', $id);
		$result = $this->get();
		return $result;
	}
	
	function count_by_condition($condition = '') {
		$this->select(" count(id) As totalRecords ");
// 		$sqlWhere = $this->createWhere($condition);
// 		$this->like($sqlWhere[0]);
// 		$this->where($sqlWhere[1]);
		return $this->get()->totalRecords;
	}
	
public function search($condition = '',$limit = NULL) {
		//$sqlWhere = $this->createWhere($condition);
		if (NULL === $limit) {
			$result = $this->get();
		} else {
			$result = $this
			->limit($limit[0], $limit[1])->get();
		}
		return $result;
	}
}