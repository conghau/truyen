<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty date2time modifier plugin
 * 
 * Type:     modifier<br>
 * Name:     spacify<br>
 * Purpose:  convert date text to timestamp
 * 
 * @author Takeo Noda
 * @param string $filename
 * @return string
 */
function smarty_modifier_file_exists($filename)
{
	return file_exists($filename);
} 

?>