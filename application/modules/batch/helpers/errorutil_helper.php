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
if (!function_exists('mail_error')) {	
	function mail_error($error) {
		
		$config = load_config_yaml("forminfo.yaml");
		if (array_key_exists('error_mailto', $config)
				&& array_key_exists('error_subject', $config)
				&& array_key_exists('error_from', $config)) {
			$target = $config['error_mailto'];
			$subject = $config['error_subject'];
			$from = $config['error_from'];
			$message = "システムエラーが発生しました。システム管理者へお知らせください。\n\n".print_r($error, true);
//			print $message;
			mb_language("ja");
			mb_internal_encoding("UTF-8");
	
			if (is_array($from)) {
				$header = "From: ".implode(",", $from)."\n";
			} else {
				$header = "From: ".$from."\n";
			}
			// 送信対象にメール
			foreach ($target as $mail_address) {
				mb_send_mail($mail_address, $subject, $message, $header);
				log_message('mail', sprintf("%s\t%s\t%s", $mail_address, $subject, $message));
			}
			print $message;
		} else {
			print "something wrong.";
		}
		exit;
    }
}

/**
 * 
 */
set_error_handler( function ($number, $message, $file, $line, $errcontext) {
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
        mail_error($error);
    });
/**
 * 
 */
set_exception_handler( function ($exception) {
		// Smarty のワーニングは無視
		// http://k-holy.hatenablog.com/entry/2012/05/10/212139
		if (error_reporting() == E_WARNING | E_NOTICE 
				&& is_array($exception)
				&& array_key_exists('file', $exception)
				&& preg_match('/(\/Smarty\/|\/datamapper|\/MY_Log)/', $error['file'])) {
			return;
		}
        mail_error($exception);
    });
/**
 * 
 */
register_shutdown_function( function() {
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
	        mail_error($error);
        } else {
        	return true; 
		}
    });
