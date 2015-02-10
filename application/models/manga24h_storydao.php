<?php
require_once APPPATH.'models/MY_DataMapper.php';
class Manga24h_StoryDao extends My_DataMapper {
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
	
	public function insert_bulk($recordset = array()) {
		$target = array(
			'manga24h_id'
			//,'title'
			//,'avatar'
			//,'intro'
			,'link'
			//,'type'
			//,'state'
			//,'source'
			//,'view'
			//,'author'
			,'status'
		);
		
		$this->trans_begin();
		$row = count($recordset);
		for ($i = 0; $i < $row; $i++ ){
			foreach ($target as $key) {
				if (array_key_exists($key, $recordset[$i])) {
					if ($recordset[$i][$key] !== "") {
						if($key == 'manga24h_id') {
							//echo $recordset[$i][$key];
							//echo 'davao';die;
							$r = $this->form_validation->is_unique($recordset[$i][$key], $this->table.'.'.$key);
							if (!$r) {
								echo 'trung'.$i;
								continue;
							}
						}
						
						$this->{$key} = $recordset[$i][$key];
 					}
 					//die;
//					else {
// 						if ($key == 'password') {
// 							continue;
// 						}
// 						$this->{$key} = NULL;
// 						$recordset[$i][$key] = NULL;
// 					}
				}
				
			}
			echo $i.'<br/>';
			$result = $this->save();
			$this->clear();
			if (!$result){
				$this->trans_rollback();
				return FALSE;
			}
		}
		$this->trans_commit();
		return TRUE;
	}
}