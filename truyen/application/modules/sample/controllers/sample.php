<?php

class Sample extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->lang->load('application');
    }

	public function index() {
		$this->setup_form_info('sample/index');
		// ビューで表示する値の設定
		$this->data['category'] = $this->device->get_category();
		$this->data['code'] = $this->device->get_code();
		$this->data['is_mobile'] = $this->device->is_mobile();
		$this->data['is_smartphone'] = $this->device->is_smartphone();
		$this->data['is_tablet'] = $this->device->is_tablet();
		$this->parse('index.tpl', 'sample/index');
	}

	public function tab() {
		$this->parse('tab.tpl', 'sample/upload'); 	
	}
	
	public function upload() {
		$this->parse('upload.tpl', 'sample/upload'); 	
	}


	public function upload_file() {
		$this->load->library('UploadHandler');
	}


	public function log() {
		if (!empty($this->data['user'])) {
			$aldao = new ActivityLogDao(MASTER);
			$aldao->on_user_join($this->data['user']);
			$aldao = new ActivityLogDao(MASTER);
			$aldao->on_user_leave($this->data['user']);
		}
		if (0) {
			$asdao = new ActivityStatDao(MASTER);
			$asdao->increment_thread_view(12345);
			$asdao->increment_file_download(5432, 123);

			
			$aldao = new ActivityLogDao();
			$conditions = array(
				'in' => array(
					'summary_id' => array(ActivityLogDao::USER_JOIN, ActivityLogDao::USER_LEAVE)
				),
				'like' => array(
					'content_data' => '"company_code":"00001"' // %は不要。
				)
			);
			$result = $aldao->find_list_by_key(ActivityLogDao::CATEGORY_USER, $conditions, 0);
//			$aldao->check_last_query();
		}
		$this->parse('log.tpl', 'sample/log'); 	
	}


}
