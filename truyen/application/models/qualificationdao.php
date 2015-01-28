<?php
/**
 * 職種情報アクセスオブジェクト
 * @name QualificationDao
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class QualificationDao extends MY_DataMapper {
	var $table = 'qualifications';
	var $db_params = SLAVE;
	
	/**
	 * ファイルの初期化メソッド。
	 */
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * データベースからデータを全て取得する。
	 * @return $result データベースからデータを全
	 */
	public function get_list() {
		$result = $this->where('deleted_at',null)->where('status',1)->order_by('position','ASC')->get();
		return $result;
	}
	
	public function count_list() {
		$sqlWhere['deleted_at'] = NULL;
		$result = $this->select("*")->where($sqlWhere)->get();
		return $result;
	}
	
	public function count_user(){
		return $this->get()->result_count();
	}
	
	public function insert_batch($recordset = array()) {
			$current_date = date("Y-m-d H:i:s");
			$target = array('id', 'name', 'category_id', 'position','status');
			
			$this->trans_begin();
			$row = count($recordset);
			for ($i = 1; $i <= $row; $i++ ) {
				$target_recordset = array();
				$this->created_at = $current_date;
				$this->updated_at = $current_date;

				foreach ($target as $key) {
					if (array_key_exists($key, $recordset[$i])) {
						if ($recordset[$i][$key] !== "") {
							$this->{$key} = $recordset[$i][$key];
								
							if($recordset[$i]['status'] == -1 && $key == "status") {
								continue;
							}
							$target_recordset[$key] = $recordset[$i][$key];
						} else {
							if($key == "position") {
								$this->{$key} = $recordset[$i]['id'] *10;
								$target_recordset[$key] = $recordset[$i]['id'] *10;	
							} else {							
							$this->{$key} = NULL;
							$target_recordset[$key] = NULL;
							}
						}
					}
				}
				
				if ($recordset[$i]['type'] == 1) {
					if ($this->status == -1) {
						$target_recordset['deleted_at'] = $current_date;
					}
						$target_recordset['updated_at'] = $current_date;
					$result = $this->where('id', $recordset[$i]['id'])->update($target_recordset);
				} else {
					$result = $this->save_as_new();
				}
				if (!$result){
					$this->trans_rollback();
					return FALSE;
				}
			}
			$this->trans_commit();
			return TRUE;
		}
	
	public function update_data ($data){
			$this->trans_begin();
			for ($i = 0; $i < count($data['id']); $i++){
				$item = array();
				$item['name'] = $data['name'][$i];
				$item['category_id'] = $data['category_id'][$i];
				$item['position'] = $data['position'][$i];
				$item['status'] = $data['status'][$i];
			
				$update_date = date("Y-m-d H:i:s");
				$item['updated_at'] = $update_date;
				
				$result =  $this->where('id', $data['id'][$i])->update($item);
				if (!$result){
					$this->trans_rollback();
					return FALSE;
				}
			}
			$this->trans_commit();
			return TRUE;
		}
			
	public function find_by_id($id){
		$result = $this->select("id")->where('id',$id)->get();
		return $result->result_count();
	}
	
	/**
	 * データベースからデータを全て取得する。
	 * @return $result データベースからデータを全
	 */
	public function get_all() {
		$result = $this->where('deleted_at',NULL)->get();
		return $result;
	}

	/**
	 * 指定したIDからデータを取得する。
	 * @param string $id
	 * @param $result 指定したIDからデータ
	 */
	public function get_by_id($id) {
		$result = $this->where('id',$id)->where('deleted_at',NULL)->get();
		return $result;
	}

}