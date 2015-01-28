<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty file_size_format modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     file_size_format<br>
 * Purpose:  format file size to human style (KB/MB/GB)<br>
 * Input:    size: size in byte
 */
function smarty_modifier_file_size_format($size, $precision = 2, $postfix = "B")
{
	$unit = "";
	
    if ($size > 1073741824) { //GB
		$size /= 1073741824;
		$unit = "G";
	} elseif ($size > 1048576) { //MB
		$size /= 1048576;
		$unit = "M";
	} elseif ($size > 1024) { //KB
		$size /= 1024;
		$unit = "K";
	} else {
		$precision = 0;
	}
	
	$size = round($size, $precision);
	$format = "%." . $precision . "f" . $unit . $postfix;
	return  sprintf($format, $size);
} 

?>