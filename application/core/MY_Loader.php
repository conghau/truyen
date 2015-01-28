<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	
	
	/** Load a module model **/
	public function modules_model($model, $object_name = NULL, $connect = FALSE) {
			/* check module */
		list($path, $_model) = Modules::find(strtolower($model), $this->_module, 'models/');
		
		if ($path == FALSE) {
			
			/* check application & packages */
			parent::model($model, $object_name);
			
		} else {
			
			class_exists('CI_Model', FALSE) OR load_class('Model', 'core');
			
			if ($connect !== FALSE AND ! class_exists('CI_DB', FALSE)) {
				if ($connect === TRUE) $connect = '';
				$this->database($connect, FALSE, TRUE);
			}
			
			Modules::load_file($_model, $path);
		}
	}
	
}