<?php
require_once APPPATH.'models/MY_DataMapper.php';
class AuthorDao extends My_DataMapper {
	var $table = 'authors';
	var $db_params = SLAVE;
	
	function __construct($db_params = SLAVE) {
		$this->db_params = $db_params;
		parent::__construct($db_params);
	}
	
	public function getAuthor() {
	    $sql = <<<SQL
select tacgia from contents group by tacgia
SQL;
	    return $this->query($sql)->all;
	}

	public function insert_bulk($recordset = '')
	{
		
		//$recordset = $this->getAuthor();echo '1';
		if ( is_array($recordset) ) {
		    $this->trans_begin();
			foreach ($recordset as $record) {
			    $a = $this->hasExist($record['name']);
				if (!$a) {
				    $this->name = $record['name'];
				    $this->save();
				    $this->clear();
				}
				//echo '1';
			}
			$this->trans_commit();
		}
	}

	public function hasExist($value)
	{
	    $sql = '';
	    if(trim($value) != '') {
		    $sql = "select * from authors where name = '%s' ";
		    $sql = sprintf($sql,$value);
		    echo $sql.'<br />';
		    $result =  $this->db->query($sql)->num_rows;
		    return !($result === 0);
		} 
		return TRUE;

	}
    
	public function getIdByName($name) {
	    $sql = '';
	    if(trim($value) != '') {
	        $sql = "select * from authors where name = '%s' ";
	        $sql = sprintf($sql,$value);
	        echo $sql.'<br />';
	        $result =  $this->get_by_name($name)->get();
	        return $result;
	    }
	    return 0;
	}
}
