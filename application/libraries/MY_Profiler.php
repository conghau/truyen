<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Profiler Class
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/profiling.html
 */
class MY_Profiler extends CI_Profiler {

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @return	array
	 */
	protected function _compile_benchmarks_log()
	{
		$profile = array();
		foreach ($this->CI->benchmark->marker as $key => $val)
		{
			// We match the "end" marker so that the list ends
			// up in the order that it was defined
			if (preg_match("/(.+?)_end/i", $key, $match))
			{
				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
				{
					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
				}
			}
		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged

		$output  = "\n";
		$output .= $this->CI->lang->line('profiler_benchmarks');

		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			$output .= "\n".$key."\t".$val;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_queries_log()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}

		if (count($dbs) == 0)
		{
			$output  = "\n";
			$output .= $this->CI->lang->line('profiler_queries');
			$output .= "\n";
			$output .= $this->CI->lang->line('profiler_no_db');
			return $output;
		}

		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');


		$count = 0;
		$total_time = 0;

		foreach ($dbs as $db)
		{
			$count++;

			$output  = "\n";
			$output .= $this->CI->lang->line('profiler_database').':'.$db->database;
			$output .= "\n";
			$output .= $this->CI->lang->line('profiler_queries').':'.count($db->queries);

			if (count($db->queries) == 0)
			{
				$output .= "\n";
				$output .= $this->CI->lang->line('profiler_no_queries');
			}
			else
			{
				foreach ($db->queries as $key => $val)
				{
					$time = number_format($db->query_times[$key], 4);
					$output .= "\n".$time."\t\"".preg_replace('/(\r\n|\r|\n)/', ' ', $val)."\"";
					$total_time += $time;
				}
			}
		}

		return "\nTotal queries time\t$total_time\n".$output;
	}


	/**
	 * Show query string
	 *
	 * @return	string
	 */
	protected function _compile_uri_string_log()
	{
		$output = "\n";
		$output .= $this->CI->lang->line('profiler_uri_string');
		$output .= "\t";

		if ($this->CI->uri->uri_string == '')
		{
			$output .= $this->CI->lang->line('profiler_no_uri');
		}
		else
		{
			$output .= $this->CI->uri->uri_string;
		}

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Show the controller and function that were called
	 *
	 * @return	string
	 */
	protected function _compile_controller_info_log()
	{
		$output  = "\n";
		$output .= $this->CI->lang->line('profiler_controller_info');
		$output .= "\t";

		$output .= $this->CI->router->fetch_class()."/".$this->CI->router->fetch_method();

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @return	string
	 */
	protected function _compile_memory_usage_log()
	{
		$output  = "\n";
		$output .= $this->CI->lang->line('profiler_memory_usage');
		$output .= "\t";

		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output .= $usage;
		}
		else
		{
			$output .= $this->CI->lang->line('profiler_no_memory');
		}
		return $output;
	}
	
	/**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	public function run()
	{
		$output = "";
		$output .= $this->_compile_uri_string_log();
		$output .= $this->_compile_controller_info_log();
		$output .= $this->_compile_memory_usage_log();
		$output .= $this->_compile_benchmarks_log();
		$output .= $this->_compile_queries_log();
		$output .= "\n--------------------------------------------------------";
		log_message('profile', $output);
	}
}

// END CI_Profiler class

/* End of file Profiler.php */
/* Location: ./system/libraries/Profiler.php */