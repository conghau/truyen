<?php
/**
 * 
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
require_once APPPATH.'models/MY_DataMapper.php';
class MailDao extends MY_DataMapper {
	var $table = 'mails';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}

	/**
	 * Get List 
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	function get_list($limit = 100 ,$offset = 0 ) {
		$results = $this->order_by("created_at","ASC")->get($limit,$offset);
		return $results;
	}
	
	function delete_mail($arr_id) {
		$binds = implode(',', $arr_id);
		$this->db->query("DELETE FROM mails WHERE mails.id IN (".$binds.")");
	}

	public function insert($recordset = array()) {
		$this->clear();
		$current_date = date("Y-m-d H:i:s");
		$this->created_at = $current_date;

		$target = array("mail_from", "mail_to", "subject", "content", "language", "link");
		foreach ($target as $key) {
			if (array_key_exists($key, $recordset) && trim($recordset[$key]) != '') {
				$this->{$key} = trim($recordset[$key]);
			}
		}

		$this->trans_begin();
		$result = $this->save();
		if(!$result){
			$this->trans_rollback();
			return FALSE;
		}
		$this->trans_commit();
		return TRUE;
	}
}