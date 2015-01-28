<?php

class Pager {
	var $_total;
	var $_current_page;
	var $_list_count;
	var $_index_count;

	function Pager( $total = 0, $current_page = 1, $list_count = 10, $index_count = 10) {
		$this->config($total, $current_page, $list_count, $index_count);
	}

	function config($total = 0, $current_page = 1, $list_count = 10, $index_count = 10) {
		$this->_total = $total;
		$this->_current_page = $current_page;
		$this->_list_count = $list_count;
		$this->_index_count = $index_count;
	}

	function get_count() {
		return $this->_total;
	}

	function get_list_count() {
		return $this->_list_count;
	}

	function get_page_count() {
		return $this->_list_count > 0 ? max(1, ceil( $this->_total / $this->_list_count )) : 0;
	}

	function get_page_index_count() {
		return $this->_index_count;
	}
	
	function has_next_page() {
		return $this->get_current_page() < $this->get_end_page() ? 1 : 0;
	}

	function has_prev_page() {
		return $this->get_current_page() > 1 ? 1 : 0;
	}

	function get_current_page() {
		return max(1, min($this->_current_page, $this->get_end_page()));
	}

	function get_start_page() {
		$pgcnt = $this->get_page_count();
		$start_page = round( $this->_current_page -  $this->_index_count / 2 );
		$start_page = max( 1,  min($start_page, $pgcnt - $this->_index_count + 1));
		return $start_page;
	}

	function get_end_page() {
		$pgcnt = $this->get_page_count();
		$end_page   = min( $pgcnt,  $this->get_start_page() + $this->_index_count - 1);
		return $end_page;
	}

	function get_start_offset() {
		return $this->get_count() ? (min($this->_current_page, $this->get_page_count()) -1 ) * $this->_list_count + 1 : 0;
	}

	function get_end_offset() {
		return min($this->_current_page * $this->_list_count -1, $this->get_count() - 1 ) + 1;
	}
}
?>
