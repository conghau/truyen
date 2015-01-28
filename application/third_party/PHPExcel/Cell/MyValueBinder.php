<?php

class PHPExcel_Cell_MyValueBinder extends PHPExcel_Cell_DefaultValueBinder
	implements PHPExcel_Cell_IValueBinder 
{ 
	public function bindValue(PHPExcel_Cell $cell, $value = null) 
	{
		$type_encode =  mb_detect_encoding($value,"UTF-8, SJIS, sjis-win, eucjp-win, JIS");
		if (is_string($value)) {
			$value = PHPExcel_Shared_String::ConvertEncoding($value,"UTF-8", $type_encode);
		} 
		
		// Implement your own override logic 
		if (is_string($value) && $value[0] == '0') { 
			$cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING); 
			return true; 
		} 

		// Not bound yet? Use default value parent... 
		return parent::bindValue($cell, $value); 
	} 
} 