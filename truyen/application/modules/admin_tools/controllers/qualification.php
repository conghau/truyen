<?php
/**
 * @name 職種管理のコントローラ
 * @copyright (C)2014 Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Qualification extends MY_Controller {

	private $columns_table_qualification = array('id','name','category_id','position','status');
	private $title_admin = array('id', 'name', 'category_id', 'position','status');
	private $status_delete = "-1";
	private $first_row = 2;

	public function __construct() {
		parent::__construct();

		if (!isset($_SESSION)) {
			session_start();
		}
		$this->load->config('forminfo');

		$lst_status_types = config_item('forminfo')['common']['status_types'];
		$this->data['format_tsv'] = config_item('forminfo')['common']['format_export_tsv'];
		$this->data['status_types'] = $lst_status_types;
		$this->data['controller']	= 'qualification';
		$this->setup_form_info($this->data['controller'].'/'.$this->data['language']);
		$this->data['msg_confirm_logout'] = $this->lang->line('L-A-0058-Q');
	}

	/**
	 * 職種編集画面の表示処理
	 */
	
	public function edit() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();

			$qualificationdao = new QualificationDao();
			
			if($_SERVER['REQUEST_METHOD'] ==='GET') {
				$results = $qualificationdao->get_all();
				$qualifications = $this->parse_data_from_query($results);

				$_SESSION['qualifications'] = $qualifications;
				$this->data['qualifications']	=  $qualifications;

				$this->parse('qualification_update.tpl','qualification/update');
			} else {
				$qualification_info_edit = $_SESSION['qualifications'];
				$this->data['qualifications'] = $qualification_info_edit;

				$this->parse('qualification_update.tpl', 'qualification/update');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 職種編集確認画面の表示処理
	 */
	public function confirm_edit() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (TRUE === $_SESSION['qualifications']) {
					unset($_SESSION['qualifications']);
				}
				redirect($this->data[''].'admin_tools/qualification');
				return;
			}

			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->setup_validation_rules('qualification/confirm_edit');
				$validation =$this->form_validation->run($this);

				$qualifications = array();
				$qualifications['id']						= $_SESSION['qualifications']['id'];
				$qualifications['name'] 					= $this->input->post("name");
				$qualifications['category_id'] 				= $this->input->post("category_id");
				$qualifications['position']					= $this->input->post("position");
				$qualifications['status']					= $this->input->post("status");
				
				$this->data['qualifications']	= $qualifications;
				
				if($validation===TRUE) {
					$_SESSION['qualifications'] = $qualifications;
					$this->parse('qualification_update_confirm.tpl', 'qualification/update');
				} else {
					$this->parse('qualification_update.tpl', 'qualification/update');
				} 	
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
		
	/**
	 * 職種編集完了画面の表示処理
	 */
	public function update() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin(TYPE_AJAX);

			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (TRUE === $_SESSION['qualifications']) {
					unset($_SESSION['qualifications']);
				}
				redirect($this->data[''].'admin_tools/qualification');
				return;
			}

			if (FALSE === $_SESSION['qualifications']) {
				redirect($this->data[''].'admin_tools/qualification');
				return;
			}

			$qualifications= $_SESSION['qualifications'];

			$qualificationdao = new QualificationDao(MASTER);
			$result = $qualificationdao->update_data($qualifications);

			unset($_SESSION['qualifications']);

			$msgID = "";
			if (!$result) {
				$msgID = 'L-A-0006-E';
			} else {
				$msgID = 'L-A-0005-I';
			}
			$message = $this->lang->line($msgID);
			$this->clear_csrf(); // 使った CSRFトークン をクリア
			echo json_encode($message);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * 全て職種ののエクスポート処理管
	 */
	public function export_all() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$encoding = $this->input->get('encoding');
			$list_columns_table_qualification =  $this->columns_table_qualification;
			$output_file_name = $this->lang->line('label_qualification_export_all_file_name').'.'.OUTPUT_FILE_TYPE_TSV;	
			$qualificationdao = new QualificationDao();
			$result = $qualificationdao->get_all();

			$this->process_export($result,$output_file_name, $list_columns_table_qualification,'label_qualification_', $encoding);

		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	
	/**
	 * queryからarrayにデータをパースする。
	 * @param $result_query データベースからデータを取得した。
	 */
	private function parse_data_from_query($results) {
		$qualifications = array();
		$i=0;
		foreach($results as $result) {
			$qualifications['id'][$i] = $result->id;
			$qualifications['name'][$i] = $result->name;
			$qualifications['category_id'][$i] = $result->category_id;
			$qualifications['position'][$i] = $result->position;
			$qualifications['status'][$i] = $result->status;
			$i += 1;
		}

		return $qualifications;
	}

	/**
	 * 一括登録画面の表示処理。
	 */
	public function import_confirm() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$this->load->library('excel');
			$this->load->library('upload');
			
			if($_SERVER['REQUEST_METHOD'] !== 'POST'){
				redirect($this->data[''].'admin_tools/qualification');
			}
			$this->data['case'] = "upload_confirm";

			if (!is_dir(UPLOAD_PATH_TSV)) {
				mkdir(UPLOAD_PATH_TSV);
			}

			$qualificationdao = new QualificationDao();
			$results = $qualificationdao->get_all();
			$this->data['qualifications']	=  $this->parse_data_from_query($results);
			
			$config['upload_path'] = UPLOAD_PATH_TSV;
			$config['allowed_types'] = OUTPUT_FILE_TYPE_TSV;
			$config['max_size']	= '0';

			$ext = end(explode('.', $_FILES['upload']['name']));
			$this->data['file_name_uniqid'] = md5(uniqid()).'.'.$ext;
			$config['file_name'] = $this->data['file_name_uniqid'];

			$this->data['row'] = 0;
			$this->data['flag_error'] = TRUE;
			$this->upload->initialize($config);
			if ( ! $this->upload->do_upload('upload')) {
				$this->data['msg_error'] = $this->upload->display_errors();
				$this->parse("qualification_update.tpl","qualification/update");
				return;
			}
			$file = UPLOAD_PATH_TSV.$this->data['file_name_uniqid'];
			$reader = new PHPExcel_Reader_CSV();
			$reader->setDelimiter("\t");
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_MyValueBinder());
			$objXLS = $reader->load($file);

			//check data row title
			$row_title = $objXLS->getSheet(0)->getRowIterator(1)->current();
			$cellIterator = $row_title->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(FALSE);
			if ($this->check_title($cellIterator) === FALSE) {
				$this->data['msg_error'] = $this->lang->line('L-A-0012-E');
				if(file_exists($file)) {
					@unlink($file);
				}
				$this->parse("qualification_update.tpl","qualification/update");
				return;
			} else {
				$data = $this->check_validation($objXLS);
			}

			$objXLS->disconnectWorksheets();
			unset($objXLS);

			$admin_info['file_name_uniqid'] = $this->data['file_name_uniqid'];
			$admin_info['row'] = $data['row'];
			$this->session->set_userdata('upload_admin_info',$admin_info);

			$this->data['row'] = $data['row'];
			$this->data['type_row'] = $data['type_row'];
			if (isset($data['error_details'])) {
				$this->data['error_details'] = $data['error_details'];
			}
			if (isset($data['msg_error'])) {
				$this->data['msg_result'] = $data['msg_error'];
				if(file_exists($file)) {
					@unlink($file);
				}
			} else {
				$this->data['flag_error'] = FALSE;
				$this->data['msg_result'] = $this->lang->line('L-A-0024-I');
			}

			$this->parse("qualification_update.tpl","qualification/update");
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	/**
	 * 一括登録完了画面の表示処理。
	 */
	public function import_done() {
		try {
			// ログインチェック処理
			$this->form_validation->check_login_admin();
			$this->load->library('excel');
			if($_SERVER['REQUEST_METHOD'] != 'POST'){
				if ($this->session->userdata('upload_admin_info') != FALSE) {
					$this->session->unset_userdata('upload_admin_info');
				}
				redirect($this->data[''].'admin_tools/qualification');
			} else {
				if ($this->session->userdata('upload_admin_info') == FALSE) {
					redirect($this->data[''].'admin_tools/qualification');
				}

				$this->data['case'] = "upload_done";

				$admin_info = $this->session->userdata('upload_admin_info');
				$file_name = $admin_info['file_name_uniqid'];
				$row = $admin_info['row'];
				$file = UPLOAD_PATH_TSV.$file_name;

				$reader = new PHPExcel_Reader_CSV();
				$reader->setDelimiter("\t");
				PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_MyValueBinder());
				$objXLS = $reader->load($file);
				$data = $this->get_data_insert($objXLS, $row);

				$objXLS->disconnectWorksheets();
				unset($objXLS);

				//delete file tsv
				if(file_exists($file)) {
					@unlink($file);
				}

				$qualification = new QualificationDao(MASTER);
				$result = $qualification->insert_batch($data);
				if (!$result) {
					$this->data['message'] = $this->lang->line('L-A-0004-E');
				} else {
					$this->data['message'] = $this->lang->line('L-A-0026-I');
				}

				$this->session->unset_userdata('upload_admin_info');
				$this->parse("qualification_update.tpl","qualification/update");
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

	/**
	 * アップロードファイルのデータが妥当かチェックする。
	 * @param object $objXLS
	 * @return array
	 */
	public function check_validation($objXLS) {
		$data_file['row'] = 0;
		$msg_error = array();
		$data_file['type_row'] = array('new' => 0, 'update' => 0, 'delete' => 0);
		$tmp_objXLS = $objXLS->getSheet(0);
		$this->lang->load('form_validation');

		//check first row
		$data_first_row = $tmp_objXLS->getRowIterator($this->first_row)->current();
		if ($this->check_empty_row($data_first_row) === FALSE) {
			$data_file['msg_error'] = $this->lang->line('L-A-0013-E');
			return $data_file;
		}

		//get row of file import
		$row=$tmp_objXLS->getHighestRow();
		$arr_id = array();
		$count_error = 0;
		for ($i = $this->first_row; $i <= $row; $i++ ) {
			$row_data = $tmp_objXLS->getRowIterator($i)->current();
			if ($this->check_empty_row($row_data) === FALSE) {
				break;
			}
			$data_file['row'] += 1;
			$error = '';
			$id = trim($tmp_objXLS->getCell('A' . $i)->getValue());
			//check unique id in file
			$check_id = in_array($id, $arr_id);
			if ($check_id === TRUE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_unique_in_file'), $this->lang->line('label_qualification_upload_'.'id'))."\r\n";
			} elseif (is_numeric($id) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_qualification_upload_'.'id'))."\r\n";
			} else {
				$arr_id[$i] = $id;
			}

			$name = trim($tmp_objXLS->getCell('B' . $i)->getValue());
			if ($name == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_qualification_upload_'.'name'))."\r\n";
			} elseif ($this->form_validation->max_length($name, 255) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_qualification_upload_'.'name'), 255)."\r\n";
			}

			$category_id = trim($tmp_objXLS->getCell('C' . $i)->getValue());
			if (is_numeric($category_id) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_qualification_upload_'.'category_id'))."\r\n";
			} elseif ($this->form_validation->max_length($category_id, 2) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_qualification_upload_'.'category_id'), 2)."\r\n";
			}

			$position = trim($tmp_objXLS->getCell('D' . $i)->getValue());
			if ($position == "") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('isset'), $this->lang->line('label_qualification_upload_'.'position'))."\r\n";
			} elseif (is_numeric($position) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_numeric'), $this->lang->line('label_qualification_upload_'.'position'))."\r\n";
			} elseif ($this->form_validation->max_length($position, 8) === FALSE) {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('max_length'), $this->lang->line('label_qualification_upload_'.'position'), 8)."\r\n";
			}

			$status = $tmp_objXLS->getCell('E' . $i)->getValue();
			if ($status != strval(STATUS_DISABLE) && $status != strval(STATUS_ENABLE) && $status != $this->status_delete
					or $status =="") {
				$count_error++;
				$error = $error."\t".sprintf($this->lang->line('is_invalid'), $this->lang->line('label_qualification_upload_'.'status'))."\r\n";
			} else {
				//check unique id in database
				$check_unique_id = $this->form_validation->is_unique($id,'qualifications.id');
				if ($check_unique_id === FALSE ) {
					if ($status != $this->status_delete) {
						$data_file['type_row']['update'] += 1;
					} else {
						$data_file['type_row']['delete'] += 1;
					}
				} else {
					if ($status == $this->status_delete) {
						$count_error++;
						$error = $error."\t".$this->lang->line('delete_id_not_in_database')."\r\n";
					} else {
						$data_file['type_row']['new'] += 1;
					}
				}
			}
			if ($error != '') {
				$error = $i.$this->lang->line("label_line")." :\r\n".$error;
				array_push($msg_error, $error);
			}
		}
		if ($count_error > 0) {
			$data_file['msg_error'] = $this->lang->line('L-A-0025-E');
		
			if ($count_error <= MAX_ERROR_SHOW) {
				$data_file['error_details'] = implode('', $msg_error);
			} else {
				$this->load->helper(array('fileutil'));
				save_file(APPPATH.'tmp_files/error_upload_qualification_'.session_id().'.txt', $msg_error);
			}
		}
		return $data_file;
	}
	/**
	 * 取込ファイルからデータを取得してデータベースに挿入する。
	 * @param object $objXLS
	 * @param int $row
	 * @return array
	 */
	public function get_data_insert($objXLS, $row) {
		$tmp_objXLS = $objXLS->getSheet(0);
		$qualification = new QualificationDao();
		for ($i = 1, $j = $this->first_row; $j < $row + $this->first_row; $i++, $j++) {
			$data[$i]['id']				=	trim($tmp_objXLS->getCell('A' . $j)->getValue());
			$count = $qualification->find_by_id($data[$i]['id']);
			if ($count == "0") {
				$data[$i]['type'] = 0;
			} else {
				$data[$i]['type'] = 1;
			}

			$data[$i]['name']			=	trim($tmp_objXLS->getCell('B' . $j)->getValue());
			$data[$i]['category_id']	=	trim($tmp_objXLS->getCell('C' . $j)->getValue());
			$data[$i]['position']		=	trim($tmp_objXLS->getCell('D' . $j)->getValue());
			$data[$i]['status']			=	trim($tmp_objXLS->getCell('E' . $j)->getValue());

		}
		return $data;
	}
	
	/**
	 * アップロードファイルにおけるいずれの行が空白であるかをチェックする。　
	 * @param object $row
	 * @return boolean
	 */
	public function check_empty_row($row) {
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(FALSE);
		foreach ($cellIterator as $cell) {
			if (trim($cell->getValue()) != "") {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * アップロードのテンプレートが正しいかをチェックする。
	 * @param object $cellIterator
	 * @return boolean
	 */
	public function check_title($cellIterator) {
		$title_template = $this->title_admin;

		foreach ($cellIterator as $cell) {
			$title[] = $cell->getValue();
		}
		if (count($title) === count($title_template)) {
			for($i = 0; $i < count($title_template); $i++) {
				if (trim($title[$i]) != $this->lang->line('label_qualification_upload_'.$title_template[$i])) {
					return FALSE;
				}
			}
		}
		else {
			return FALSE;
		}
		return TRUE;
	}
	
	public function download_file_error() {
		$file_path = APPPATH.'tmp_files/error_upload_qualification_'.session_id().'.txt';
		if (!file_exists($file_path)) {
			redirect($this->data[''].'admin_tools/qualification');
		}
		$this->load->helper('download');
		$data = file_get_contents($file_path); // Read the file's contents
		$file_name = 'error_upload_qualification-'.date('Y-m-d').'.txt';
		$output_file_name = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0) ? $file_name : urlencode($file_name);
		force_download($output_file_name, $data);
	}
}

