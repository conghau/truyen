<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CodeIgniter Sort Util Helpers
 *
 * sort util helpers.
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		
 * @link		
 */
// --------------------------------------------------------------------

/**
*/
if (!function_exists('error_log_handler')) {	
	function error_log_handler ($number, $message, $file, $line ) {
        // Insert all in one table
        $error = array( 'type' => $number, 'message' => $message, 'file' => $file, 'line' => $line );
		// Smarty のワーニングは無視
		// http://k-holy.hatenablog.com/entry/2012/05/10/212139
		if (error_reporting() == E_WARNING | E_NOTICE 
				&& is_array($error)
				&& array_key_exists('file', $error)
				&& preg_match('/(\/Smarty\/|\/datamapper|\/MY_Log)/', $error['file'])) {
			return;
		}
        // Display content $error variable
        log_error($error);
    }
}
/**
*/
if (!function_exists('exception_log_handler')) {	
	function exception_log_handler ($exception) {
		// Smarty のワーニングは無視
		// http://k-holy.hatenablog.com/entry/2012/05/10/212139
		if (error_reporting() == E_WARNING | E_NOTICE 
				&& is_array($exception)
				&& array_key_exists('file', $exception)
				&& preg_match('/(\/Smarty\/|\/datamapper|\/MY_Log)/', $error['file'])) {
			return;
		}
        log_error($exception);
    }
}
/**
*/
if (!function_exists('runtime_log_handler')) {	
	function runtime_log_handler () {
        $error = error_get_last();
		// Smarty のワーニングは無視
		// http://k-holy.hatenablog.com/entry/2012/05/10/212139
		if (error_reporting() == E_WARNING | E_NOTICE 
				&& is_array($error)
				&& array_key_exists('file', $error)
				&& preg_match('/(\/Smarty\/|\/datamapper|\/MY_Log)/', $error['file'])) {
			return;
		}
        if( $error ) {
	        log_error($error);
        } else {
        	return true; 
		}
    }
}	
/**
*/
if (!function_exists('log_error')) {	
	function log_error($error) {
		log_message('debug', json_encode($error));
    }
}

set_error_handler( 'error_log_handler' );
set_exception_handler( 'exception_log_handler' );
register_shutdown_function( 'runtime_log_handler' );
