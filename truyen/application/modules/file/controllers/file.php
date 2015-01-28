<?php
class File extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->lang->load('application');
		if (!isset($_SESSION)) {
			session_start();
		}
	}

	/**
	 * アップロードを行う。
	 * @param $gid アップロード先（個人ダッシュボードの場合0、グループの場合1～）
	 */
	public function upload($gid = 0) {
		// ログインのチェック
		$this->form_validation->check_login_user();

		$options = array();
		$options['group_id'] = $gid;
		$options['user_dirs'] = $this->data['user']->user_id . '/' . $gid . '/';
		$options['user'] = $this->data['user'];
		// $options['dicom_parser'] = file_exists(DICOM_PARSER) ? DICOM_PARSER : false;
		$this->load->library('UploadHandler', $options);
	}

	/**
	 * ダウンロードを行う。
	 */
	public function download($file_id, $user_mode = '') {
		// ログインのチェック
		if ($user_mode === 'admin') {
			$this->form_validation->check_login_admin();
		} else {
			$this->form_validation->check_login_user();
		}
		$version = 'download';
		$this->process_download($file_id, $version, $user_mode);
	}
	
	/**
	 * ダウンロードを行う。
	 */
	public function thumbnail($file_id, $user_mode = '') {
		// ログインのチェック
		if ($user_mode === 'admin') {
			$this->form_validation->check_login_admin();
		} else {
			$this->form_validation->check_login_user();
		}
		$version = 'download_thumbnail';
		$this->process_download($file_id, $version, $user_mode);
	}
	
	/**
	 * ダウンロードを行う。
	 */
	public function image($file_id, $version = '', $user_mode = '') {
		// ログインのチェック
		if ($user_mode === 'admin') {
			$this->form_validation->check_login_admin();
		} else {
			$this->form_validation->check_login_user();
		}
		$this->process_download($file_id, $version, $user_mode);
	}

	/**
	 * ダウンロードを行う。
	 */
	public function movie($file_id, $user_mode = '') {
		try {
			log_message('debug', 'movie processing.');
			// ログインのチェック
			if ($user_mode === 'admin') {
				$this->form_validation->check_login_admin();
			} else {
				$this->form_validation->check_login_user();
			}
			$dao = new UploadDao();
			if ($user_mode == 'admin') {
				if (isset($this->data['admin'])) {
					$can_download = true;
				}
			} else {
				$user_mode = '';
				$user = $this->userauth->getUser();
				$can_download = $dao->can_download($user->id, NULL, $file_id);
			}
	
			if (!$can_download){
				log_message('debug', 'Can not download: '.$file_id);
				set_status_header(401);
				return;
			}
			$dao->clear();
			$dto = $dao->find_by_file_id($file_id);
			// log_message('debug', $dao->check_last_query(false, true));
			if ($dto === NULL) {
				set_status_header(404);
				return;
			}
			$file_extension = $dto->file_extension;
			$file_name = $dto->original_file_name;
			if (empty($file_name)) {
				$file_name = $file_id . '.' . $file_extension;
			}
		
//			header("Cache-Control: no-transform,private,max-age=3600,s-maxage=3600");
//			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
			$this->data['file_type'] = $this->get_file_type($dto->original_file_name);
			list($header, $meta_extension) = $this->get_meta_file_type($file_extension);
//			$this->output->set_header('Content-Type: '.$header);
//			$file_name = preg_replace('/\.'.$file_extension.'$/', '.'.$meta_extension, $file_name);
//			header('Content-Disposition: attachment; filename="' . $file_name . '"');
			log_message('debug', $header);
//			header('Content-Disposition: inline; filename="' . $file_name . '"');
			$this->data['file_id'] = $file_id;
			// テンプレート情報の取得
//			$template_name = $this->device->get_template_name('movie.tpl');
//			$this->parser->html_parse($template_name, $this->data, FALSE, TRUE, HTML_ENCODING);
			$this->parse('movie.tpl', 'file/movie');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}


	/**
	 * ファイル個別 ダウンロード
	 * @param $file_id ファイルID
	 */
	public function download_file_list($file_id = null) {
		try {
			// ログインチェック処理
			if (isset($this->data['user'])){
				$this->process_download($file_id);
			} else {
				$this->data['auth'] = false;
				$this->parse('filemodal.tpl','file/filelist');
			}
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	private function process_download($file_id, $version = '', $user_mode = '') {
		// ダウンロード時は制限つけない	
		set_time_limit(600);
		$dao = new UploadDao();
		$can_download = false;
		if ($user_mode == 'admin') {
			if (isset($this->data['admin'])) {
				$can_download = true;
			}
		} else {
			$user_mode = '';
			$user = $this->userauth->getUser();
			$can_download = $dao->can_download($user->id, NULL, $file_id);
		}

		if (!$can_download){
			log_message('debug', 'Can not download: '.$file_id);
			set_status_header(401);
			return;
		}
		$dao->clear();
		$dto = $dao->find_by_file_id($file_id);
		// log_message('debug', $dao->check_last_query(false, true));
		if ($dto === NULL) {
			set_status_header(404);
			return;
		}
		
		// ファイル情報を調整
		$file_extension = $dto->file_extension;
		$pass = $dto->encryption_key;
		$file_name = $dto->original_file_name;
		if ($version === 'large' || $version === 'thumbnail_l') {
			$file_path = $dto->large_thumbnail_path;
			$file_size = $this->get_file_size('thumbnail_l_size', $dto->file_info);
			// 拡張子がなかったり、画像以外のときは強制画像化
			if (empty($file_extension) || !preg_match('/\.(gif|jpe?g|png)$/i', $file_name)) {
				$file_name = $file_name.".jpg";
				$file_extention = "jpg";
			}
			$file_type = $this->get_file_type($file_name);
			log_message('debug', sprintf("[large thumbnail] %s; %s; %s; %s;", $file_name, $file_size, $file_type, $file_path));
		} else if ($version === 'download_thumbnail') {
			$version = '';
			$file_path = $dto->large_thumbnail_path;
			$file_size = $this->get_file_size('thumbnail_l_size', $dto->file_info);
			// 拡張子がなかったり、画像以外のときは強制画像化
			if (empty($file_extension) || !preg_match('/\.(gif|jpe?g|png)$/i', $file_name)) {
				$file_name = $file_name.".jpg";
				$file_extention = "jpg";
			}
			$file_type = $this->get_file_type($file_name);
			log_message('debug', sprintf("[large thumbnail] %s; %s; %s; %s;", $file_name, $file_size, $file_type, $file_path));
		} else if ($version === 'small' || $version === 'thumbnail_s') {
			$file_path = $dto->small_thumbnail_path;
			$file_size = $this->get_file_size('thumbnail_s_size', $dto->file_info);
			// 拡張子がなかったり、画像以外のときは強制画像化
			if (empty($file_extension) || !preg_match('/\.(gif|jpe?g|png)$/i', $file_name)) {
				$file_name = $file_name.".jpg";
				$file_extention = "jpg";
			}
			$file_type = $this->get_file_type($file_name);
			log_message('debug', sprintf("[small thumbnail] %s; %s; %s; %s;", $file_name, $file_size, $file_type, $file_path));
		}else if ($version === 'movie') {
			$file_path = $dto->file_path;
			$file_size = $dto->file_size;
			$file_type = $this->get_file_type($file_name);
			log_message('debug', sprintf("[movie file] %s; %s; %s; %s;", $file_name, $file_size, $file_type, $file_path));
		}else {
			$version = '';
			$file_path = $dto->file_path;
			$file_size = $dto->file_size;
			$file_type = $this->get_file_type($file_name);
			log_message('debug', sprintf("[original file] %s; %s; %s; %s;", $file_name, $file_size, $file_type, $file_path));
		}

		if (empty($file_name)) {
			$file_name = $file_id . '.' . $file_extension;
		}
		
		if (!file_exists($file_path)) {
			log_message('debug', 'File not found: ' . $file_path);
			set_status_header(404);
			return;
		}
		
		// Prevent browsers from MIME-sniffing the content-type:
		header('X-Content-Type-Options: nosniff');
		if (empty($version) && !$this->device->is_smartphone()){ //Download
			$isIE = (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") > 0);
			if ($isIE){
				$file_name = urlencode($file_name);
			}
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $file_name . '"');
		} else { //Thumbnail
			header("Cache-Control: no-transform,private,max-age=3600,s-maxage=3600");
			header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
			header('Content-Type: ' . $this->get_file_type($file_name));
			header('Content-Disposition: inline; filename="' . $file_name . '"');
		}

		if ($file_size > 0) {
			$length = $file_size;           // Content length
			$start  = 0;               // Start byte
			$end    = $file_size - 1;       // End byte
		} else {
			$length = 0;
			$start = 0;
			$end = 0;
		}

		log_message('debug', 'Download header out: ' . $file_size . 'byte');
		header("Accept-Ranges: 0-$length");
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', filemtime($file_path)));

		if (isset($_SERVER['HTTP_RANGE'])) {
		    $c_start = $start;
		    $c_end   = $end;
		    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
		    if (strpos($range, ',') !== false) {
		        header('HTTP/1.1 416 Requested Range Not Satisfiable');
		        header("Content-Range: bytes $start-$end/$file_size");
				log_message('debug', 'Not Partial Content: '.$range.'('.$start.'-'.$end.'|'.$file_size.')');
		        exit;
		    }
		    if ($range == '-') {
		        $c_start = $file_size - substr($range, 1);
		    }else{
		        $range_array  = explode('-', $range);
		        $c_start = $range_array[0];
		        $c_end   = (isset($range_array[1]) && is_numeric($range_array[1])) ? $range_array[1] : $file_size;
		    }
		    $c_end = ($c_end > $end) ? $end : $c_end;
		    if ($c_start > $c_end || $c_start > $file_size - 1 || $c_end >= $file_size) {
		        header('HTTP/1.1 416 Requested Range Not Satisfiable');
		        header("Content-Range: bytes $start-$end/$file_size");
				log_message('debug', 'Not Partial Content: '.$range.'('.$start.'-'.$end.'|'.$file_size.')');
		        exit;
		    }
			log_message('debug', 'Partial Content: '.$range.'('.$c_start.'-'.$c_end.'|'.$length.')');
		    $start  = $c_start;
		    $end    = $c_end;
		    $length = $end - $start + 1;
		    header('HTTP/1.1 206 Partial Content');
			header("Content-Range: bytes $start-$end/$file_size");
			header("Content-Length: ".$length);

			$cmd = sprintf(FILE_DECODER_DIRECT_WITH_RANGE, $pass, $file_path, min($start, $end) , $length);
			log_message('debug', 'FILE DECODER: ' . $cmd);
			passthru($cmd);
		} else {
			header('Content-Length: ' . $file_size);
			$cmd = sprintf(FILE_DECODER_DIRECT, $pass, $file_path);
			log_message('debug', 'FILE DECODER: ' . $cmd);
			passthru($cmd);
		}

		// ファイルの終端までたどり着いてダウンロードの場合
		if ($file_size > 0 && $end == $file_size - 1 && empty($version) && !$this->device->is_smartphone()){ //Download
			$postuploaddao = new PostUploadDao();
			if ($dto->file_type == 2) {
				$upload_info = $postuploaddao->get_by_upload_id($dto->parent_id);
			} else {
				$upload_info = $postuploaddao->get_by_upload_id($dto->id);
			}
			log_message('debug', $postuploaddao->check_last_query(false, true)."\n".$upload_info->post_id.":".$upload_info->upload_id);
			//Insert stat and activity log
			if ($user_mode === '' && !empty($upload_info)) { // Do not write log for admin
				log_message('debug', 'export upload_info:'.var_export($upload_info, true));
				$asdao = new ActivityStatDao(MASTER);
				$asdao->increment_file_download($upload_info->upload_id, $upload_info->post_id);
				$aldao = new ActivityLogDao(MASTER);
				$aldao->on_file_download($user->id, $upload_info->post_id, $upload_info->upload_id);
			}
		}
	}
	
	protected function get_file_size($key, $file_info) {
		if (!preg_match('/"' . $key . '":"?(\-|\d+|null)"?/', $file_info, $matches)) {
			return 0;
		}
		return $matches[1] == 'null' ? 0 : $matches[1]; // マッチしたものが NULL のときは 0 に変換
	}

	protected function get_file_type($file_path) {
		switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
		case 'jpeg':
		case 'jpg':
			return 'image/jpeg';
		case 'png':
			return 'image/png';
		case 'gif':
			return 'image/gif';
		case 'dcm':
		case 'dic':
			return 'application/dicom';
		case 'pdf':
			return 'application/pdf';
		case 'wmv':
			return 'video/x-ms-wmv';
		case 'mp4':
		case 'm4v':
		case 'm4a':
			return 'video/mp4';
		case 'qt':
		case 'mov':
			return 'video/quicktime';
		case 'ogv':
			return 'video/ogg';
		case 'webm':
			return 'video/webm';
		case 'mpg':
		case 'm4v':
		case 'm4a':
			return 'video/mpeg';
		case 'xls':
			return 'application/vnd.ms-excel';
		case 'xlsx':
			return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
		case 'ppt':
			return 'application/vnd.ms-powerpoint';
		case 'pptx':
			return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
		case 'doc':
			return 'application/msword';
		case 'docx':
			return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
		default:
			return 'application/octet-stream';
		}
	}

	protected function get_meta_file_type($ext) {
		switch ($ext) {
		case 'wmv':
			return array('video/x-ms-asf', 'asf');
		default:
			return array('', '');
		}
	}


	public function preview($post_id = '', $user_mode = '') {
		try {
			$this->data['auth'] = false;
			$this->data['post_id'] = $post_id;
			$this->setup_auth($post_id, $user_mode);
				
			if ($this->data['auth']) {
				$this->data['post'] = $this->get_post($post_id);		
				$this->load->config('forminfo');
				$pagingNum = array_selector('preview', config_item('forminfo'), 0, 'pagingNum');
				$cacheNum = array_selector('preview', config_item('forminfo'), 0, 'cacheNum');
						
				$this->data['moveNum'] = array_selector('preview', config_item('forminfo'), 0, 'moveNum');
				$this->data['pagingNum'] = $pagingNum;
				$this->data['cacheNum'] = $cacheNum;
				$this->data['file_list'] = $this->get_preview_file_list($post_id, $user_mode);
				$this->data['file_count'] = count($this->data['file_list']);
			}
			$this->parse('preview.tpl', 'file/preview');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	public function preview_processing($post_id = '', $user_mode = '') {
		try {
			$this->data['auth'] = false;
			$this->setup_auth($post_id, $user_mode);
			
			$result = array('progress_count' => 0, 'upload_status' => 0);
			if ($this->data['auth']) {
				$this->data['post'] = $this->get_post($post_id);
				if (isset($this->data['post']->upload_status)) {
					$result['upload_status'] = intval($this->data['post']->upload_status);				
				}
				$uploaddao = new UploadDao();
				$total = $uploaddao->count_preview_list($post_id);
				$result['progress_count'] = intval($total);				
			}
			echo json_encode($result);
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}	
	
	private function setup_auth($post_id, $user_mode) {
		if ($user_mode == 'admin') {
			if (isset($this->data['admin'])) {
				$this->data['auth'] = true;
			}
		} else {
			$user = $this->data['user'];
			if (isset($user)) {
				$postdao = new PostDao();
				if ($postdao->can_see_post($user->id, $post_id)) {
					$this->data['auth'] = true;
					$user_mode = '';
				}
			}
		}		
	}

	private function get_post($post_id) {
		$postdao = new PostDao();
//		log_message('debug', var_export($this->data['post'], true));
		return $postdao->find_by_id($post_id);
	}
	
	private function get_preview_file_list($post_id, $user_mode) {
		$user = $this->data['user'];
		$pagingNum = $this->data['pagingNum'];
		$cacheNum = $this->data['cacheNum'];

		// キャッシュがある場合、そこから取得
		if ($user_mode !== 'admin') {
			$cache_path = sprintf("user/%s/preview/%s", $user->id, $post_id);
			$results = $this->load_cache($cache_path);
			if (!empty($results)) {
				return $results;
			}
		}
		$thumb_large_dir = 'assets/img/thumb_icon/large/';
		$thumb_small_dir = 'assets/img/thumb_icon/small/';
		$thumb_ext = '.png';
				
		$thumb_exceptions = array('pdf');
		$video_extensions = array('mp4', 'm4a', 'm4v', 'wmv', '3pg', 'flv', 'mov', 'ogg');
				
		$file_list = array();
		$now = new DateTime();
	
		$uploaddao = new UploadDao();
		$total = $uploaddao->count_preview_list($post_id);
		$this->data['progress_count'] = !empty($total) ? $total : 0;
		if ($total == 0) {
			return array();	
		}
		$uploads_tmp = $uploaddao->get_preview_list($post_id);
		$file_number = 0;
		foreach ($uploads_tmp as $upload_tmp) {
			// Calculate expired time and chose the smallest one
			if ($upload_tmp->expired_type != EXPIRED_TYPE_INDEFINED && $upload_tmp->expired_at) {
				$expired_at = new DateTime($upload_tmp->expired_at);
			} else { // Unlimited time
				$expired_at = null;
			}
	
			// Escape expired file
			if ($expired_at != null && $expired_at <= $now) {
				continue;
			}
	
			// Make Upload Data
			$file = array();
			$file['index'] = ++$file_number;
					
			$file['file_id'] = $upload_tmp->file_id;
			if ( !in_array($upload_tmp->file_extension, $thumb_exceptions)
					&& $upload_tmp->small_thumbnail_path && $upload_tmp->large_thumbnail_path) {
						$file['thumb'] = $this->data[''] . 'file/image/' . $upload_tmp->file_id . '/thumbnail_s/' . $user_mode;
				$file['main'] = $this->data[''] . 'file/image/' . $upload_tmp->file_id . '/thumbnail_l/' . $user_mode;
			} else {
				$icon_large = $thumb_large_dir . $upload_tmp->file_extension . $thumb_ext;
				$icon_small = $thumb_small_dir . $upload_tmp->file_extension . $thumb_ext;
				if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $icon_large)){
					$icon_large = $thumb_large_dir . 'other' . $thumb_ext;
				}
				if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $icon_small)){
					$icon_small = $thumb_small_dir . 'other' . $thumb_ext;
				}
				$file['thumb'] = $this->data[''] . $icon_small;
				$file['main'] = $this->data[''] . $icon_large;
			}

					
					
			if (in_array($upload_tmp->file_extension, $video_extensions)){
				$video_url = $this->data[''] . 'file/movie/' . $upload_tmp->file_id . '/' . $user_mode;
				$file['movie'] = $video_url;
				// 動画の際は元ファイルをダウンロード
				$file['down'] = $this->data[''] . 'file/download/' . $upload_tmp->file_id . '/' . $user_mode;
			} else {
				$file['movie'] = '';
				// 動画の際は元ファイルをダウンロード
				$file['down'] = $this->data[''] . 'file/thumbnail/' . $upload_tmp->file_id . '/' . $user_mode;
			}
			
			// 
			$index = $file_number;
			$file['number'] = ($index % $pagingNum == 1 ) ? $index."~".( $index+($pagingNum-1)>=$total?$total:$index+($pagingNum-1) ) : $index;
			array_push($file_list, $file);
		}

		// 更新完了している状態のときキャッシュ化
		if ($user_mode !== 'admin' && isset($this->data['post']) && $this->data['post']->upload_status == 5) {
			$this->save_cache($cache_path, $file_list);
		}
		return $file_list;
	}
	
	public function index() {
		redirect($this->data['']);
	}
	
	public function get_and_zip_file($post_id) {
		try {
			//ログインチェック処理
			$this->form_validation->check_login_user(TYPE_AJAX);
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				set_status_header(401);
			}
			
			$uploaddao = new UploadDao();
			if (!($uploaddao->can_download($this->data['user']->id,$post_id))) {
				set_status_header(401);
				exit;
			}
			list($files, $uploads_id) = $this->get_file_by_post_id($uploaddao,$post_id);
			if(empty($files)){
				set_status_header(500);
				exit;
			}
			$_SESSION['dl_post_id'] = $post_id;
			$_SESSION['dl_uploads_id'] = $uploads_id;
			$tmp_folder_name = session_id().'/';
			$result = $this->process_zip($files, $tmp_folder_name);
			if($result) {
				set_status_header(200);
				exit;
			} else {
				unset($_SESSION['dl_post_id']);
				unset($_SESSION['dl_uploads_id']);
				set_status_header(500);
				exit;
			} 
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * zip圧縮とファイルダウンロー
	 * @param $files ファイル一覧
	 * @param $file_download_name ファイルアップロード名
	 */
	private function process_zip($files, $tmp_folder_name) {
		$result = FALSE;
		ignore_user_abort(TRUE);
		set_time_limit(600);
		$this->delete_file_in_folder(PATH_TMP_FILE_ZIP.$tmp_folder_name,FALSE);
		
		log_message('debug', 'Process ZIP: '.PATH_TMP_FILE_ZIP.$tmp_folder_name);
		if (!is_dir(PATH_TMP_FILE_ZIP.$tmp_folder_name)) {
			mkdir(PATH_TMP_FILE_ZIP.$tmp_folder_name,0777,TRUE);
			chmod(PATH_TMP_FILE_ZIP.$tmp_folder_name, 0777);
		}
		$arr_name = array();
		foreach($files as $file) {
			$file_path =$file['file_path'];
			$pass = $file['pass'];
			$temp_decrypt_file = PATH_TMP_FILE_ZIP.$tmp_folder_name.'tmp_file'.$file['file_extension'];
			$output_file = PATH_TMP_FILE_ZIP.$tmp_folder_name.$file['file_name'];
			
			if (in_array($output_file, $arr_name)) {
				$file_extension = '.'.$file['file_extension'];
				$index = strrpos($output_file, $file_extension);
				$i = 1;
				$name = substr($output_file, 0, $index);
				while(true) {
					$output_file = $name.'('.$i.')'.$file_extension;
					if (in_array($output_file, $arr_name)){
						$i++;
					} else {
						break;
					}
				}
			}
			array_push($arr_name, $output_file);
			
			$cmd = sprintf(FILE_DECODER, $pass, $file_path, $temp_decrypt_file);
			system($cmd);
			rename($temp_decrypt_file,$output_file);
		}
		$path_folder = PATH_TMP_FILE_ZIP.session_id();
		$zip_name = session_id().OUTPUT_FILE_TYPE_ZIP;
		$cmd = sprintf(CMD_ZIP_CODE,$path_folder,$zip_name,'*');
		log_message('debug', 'ZIP CMD:'.$cmd);
		system($cmd);
		$result = TRUE;
		return $result;
	}
	
	public function batch_download($post_id){
		try {
			$this->form_validation->check_login_user();
			$tmp_folder_name = session_id().'/';
			ignore_user_abort(TRUE);
			set_time_limit(600);
			if(!isset($_SESSION['dl_post_id'])){
				redirect($this->data[''].'user');
				exit;
			}
			if($_SESSION['dl_post_id'] != $post_id) {
				redirect($this->data[''].'user');
				exit;
			}
			$file_path = PATH_TMP_FILE_ZIP.$tmp_folder_name.session_id().OUTPUT_FILE_TYPE_ZIP;
			$file_size = filesize($file_path);

			$uploads_id = $_SESSION['dl_uploads_id'] ;
			$postdao = new PostDao();
			$user = $postdao->get_user_by_post_id($post_id);
			$date_create = date("YmdHis", strtotime($user->created_at));
			$file_download_name = $user->last_name.'_'.$user->first_name.'_('. $post_id . ')'.$date_create;
			
			header('X-Content-Type-Options: nosniff');
			header('Content-type: application/zip');
			header('Content-disposition: attachment; filename='.$file_download_name.OUTPUT_FILE_TYPE_ZIP);
			header("Pragma: no-cache");
			header("Expires: 0");

			if ($file_size > 0) {
				$length = $file_size;           // Content length
				$start  = 0;               // Start byte
				$end    = $file_size - 1;       // End byte
			} else {
				$length = 0;
				$start = 0;
				$end = 0;
			}

			if (isset($_SERVER['HTTP_RANGE'])) {
			    $c_start = $start;
			    $c_end   = $end;
			    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
			    if (strpos($range, ',') !== false) {
			        header('HTTP/1.1 416 Requested Range Not Satisfiable');
			        header("Content-Range: bytes $start-$end/$file_size");
					log_message('debug', 'Not Partial Content: '.$range.'('.$start.'-'.$end.'|'.$file_size.')');
			        exit;
			    }
			    if ($range == '-') {
			        $c_start = $file_size - substr($range, 1);
			    }else{
			        $range_array  = explode('-', $range);
			        $c_start = $range_array[0];
			        $c_end   = (isset($range_array[1]) && is_numeric($range_array[1])) ? $range_array[1] : $file_size;
			    }
			    $c_end = ($c_end > $end) ? $end : $c_end;
			    if ($c_start > $c_end || $c_start > $file_size - 1 || $c_end >= $file_size) {
			        header('HTTP/1.1 416 Requested Range Not Satisfiable');
			        header("Content-Range: bytes $start-$end/$file_size");
					log_message('debug', 'Not Partial Content: '.$range.'('.$start.'-'.$end.'|'.$file_size.')');
			        exit;
			    }
				log_message('debug', 'Partial Content: '.$range.'('.$c_start.'-'.$c_end.'|'.$length.')');
			    $start  = $c_start;
			    $end    = $c_end;
			    $length = $end - $start + 1;
			    header('HTTP/1.1 206 Partial Content');
				header("Content-Range: bytes $start-$end/$file_size");
				header("Content-Length: ".$length);
				$fp = @fopen($file_path, 'rb');
				fseek($fp, $start);
				$buffer = 1024 * 8;
				while(!feof($fp) && ($p = ftell($fp)) <= $end) {
				    if ($p + $buffer > $end) {
				        $buffer = $end - $p + 1;
				    }
				    echo fread($fp, $buffer);
				    flush();
				}
				 
				fclose($fp);
				log_message('debug', 'ZIP FILE OUT(RANGE): ' . $post_id);
			} else {
				header('Content-Length: ' . $file_size);
				ob_clean();
				flush();
				readfile($file_path);
				log_message('debug', 'ZIP FILE OUT: ' . $post_id);
			}
			
			// ファイルが終端まで行った場合
			if ($file_size > 0 && $end == $file_size - 1) {
				//write log ActivityStart
				$asdao = new ActivityStatDao(MASTER);
				$asdao->increment_file_bulkdownload($uploads_id, $post_id);
					
				//write log ActivityLog
				$aldao = new ActivityLogDao(MASTER);
				foreach ($uploads_id as $upload_id) {
					$aldao->on_file_download($this->data['user']->id, $post_id, $upload_id);
				}
	
				// remove session
				unset($_SESSION['dl_uploads_id']);
				unset($_SESSION['dl_post_id']);
				
				// 
				unlink($file_path);
				$this->delete_file_in_folder(PATH_TMP_FILE_ZIP.$tmp_folder_name,TRUE);
			}
			exit;
		} catch (Exception $e) {
			log_message('error_download',$e->getMessage());
			show_message($e->getMessage());
		}
	}
	
	/**
	 * ファイル一覧を投稿IDによって取得する。
	 * @param $id 投稿ID
	 */
	private function get_file_by_post_id($uploaddao,$post_id = null) {
		$uploads = $uploaddao->get_by_post_id($post_id);
		$files = array();
		$uploads_id = array();
		$now = new DateTime();
		foreach ($uploads as $upload) {
			$expired_at = $this->get_expired_at($upload);
			if ($expired_at == null || $expired_at > $now) {
				$file['file_path'] = $upload->file_path;
				$file['file_name'] = $upload->original_file_name;
				$file['pass'] = $upload->encryption_key;
				$file['file_extension'] = $upload->file_extension;
				if(file_exists($upload->file_path)) {
					array_push($files, $file);
					array_push($uploads_id, $upload->id);
				}
			}
		}
		return array($files, $uploads_id);
	}
	/**
	 * アップロード情報IDで有効期限を取得する。
	 * @param $upload アップロード情報ID
	 */
	private function get_expired_at($upload) {
		// Calculate expired time and chose the smallest one
		if ($upload->expired_type != EXPIRED_TYPE_INDEFINED && $upload->expired_at) {
			$expired_at = new DateTime($upload->expired_at);
		} else { // Unlimited time
			$expired_at = null;
		}
		return $expired_at;
	}
	
	/**
	 * Process delete file in folder
	 * @param string $path_folder
	 * @param boolean $is_delete_folder
	 */
	private function delete_file_in_folder($path_folder, $is_delete_folder = FALSE){
		if (is_dir($path_folder)) {
			array_map('unlink', glob($path_folder.'*'));
		}
		if($is_delete_folder) {
			rmdir($path_folder);
		}
	}
	
	/**
	 * ファイル一覧を表示する。
	 */
	public function file_list($id ='') {
		try {
			// ログインチェック処理
			if (!isset($this->data['user'])){
				$this->data['auth'] = false;
			} else {
				$this->data['auth'] = true;
				$user_id = $this->data['user']->id;
				$upload = new UploadDao();
				$can_download = $upload->can_download($user_id, $id, NULL);
				if ($can_download == TRUE) {
					$uploaddao = new UploadDao();
					$files_tmp = $uploaddao->get_by_post_id($id);
	
					$files = array();
					$now = new DateTime();
					foreach ($files_tmp as $file_tmp) {
						$expired_at = $this->get_expired_at($file_tmp);
						if ($expired_at == null || $expired_at > $now) {
							$file = array();
							$file['file_size'] = $file_tmp->file_size;
							$file_path = $file_tmp->file_path;
							$file['file_name'] = $file_tmp->original_file_name;
							if(file_exists($file_path)) {
								$file['file_id'] = $file_tmp->file_id;
							} else {
								$file['file_id'] = 0;
							}
							array_push($files, $file);
						}
					}
					$this->data['files'] = $files;
				}
			}
			$this->parse('filemodal.tpl','file/filelist');
		} catch (Exception $e) {
			log_message('error', $e->getMessage());
			show_error($e->getMessage());
		}
	}

}