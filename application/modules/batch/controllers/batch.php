<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @name バッチ用コントローラ
 * @copyright (C)2013 Sevenmedia Inc.
 * @author nodat
 *　@version 1.0
 */
class Batch extends MY_Controller {

	/**
	 * 初期化メソッド
	 */
	public function __construct() {
		parent:: __construct();
		if (ENVIRONMENT === 'production') {
			$this->load->helper(array('errorutil', 'fileutil', 'dicomutil', 'movutil'));
		// 本番以外の環境では、エラーメールしない。
		} else {
			$this->load->helper(array('fileutil', 'dicomutil', 'movutil'));
		}
		
		set_time_limit(0);
	}

	/**
	 * ユーザーのファイルサイズを最新のものに更新する。
	 */
	public function setup_user_file_size() {
		$this->benchmark->mark('update_user_file_size_start');
		$userdao = new UserDao();
		$uploaddao = new UploadDao();
		$updateuserdao = new UserDao(MASTER);
		$user_count = $userdao->count_by_condition(array());
		$total_count = 0;
		$total_file_size = 0;
		for ($xi = 0; $xi < $user_count; $xi += 100) {
			$list = $userdao->search(array(), array(100, $xi));
			foreach ($list as $user) {
				$file_size = $this->update_user_file_size($uploaddao, $updateuserdao, $user->id);
				$total_count++;
				$total_file_size += $file_size;
			}
		}
		$this->benchmark->mark('update_user_file_size_end');
		echo "total:".$total_count."; total file size:".$total_file_size."\n";
		echo "Done.";
	}

	/**
	 * 有効期限が切れたファイルを削除する。
	 * １時間に１度実行する。
	 */
	public function file_expiration() {
		$this->benchmark->mark('file_expiration_start');
		$forminfo = $this->load->config('forminfo');
		$result = "";
		$command = array_selector('command', $forminfo, '', 'disk_storage');
		$prev_df = sprintf("Prev:\n%s\n", implode('', execute_command($command)));
		

		$tudao = new TmpUploadDao();
		$udao = new UploadDao();
		$utudao = new TmpUploadDao(MASTER);
		$uudao = new UploadDao(MASTER);

		$target_date = date('Y-m-d H:i:s');
		
		// 一時アップロード先のファイル有効期限をチェック
		// 開始
		$tmp_upload_count = $tudao->count_file_expiration($target_date);
		$tmp_count = 0;
		$tmp_filesize = 0;
		for ($xi = 0; $xi < $tmp_upload_count; $xi += 100) {
			$list = $tudao->find_expired_list($target_date, $xi, 100);
			foreach ($list as $recordset) {
				foreach (array($recordset->file_path, $recordset->small_thumbnail_path, $recordset->large_thumbnail_path) as $file_path) {
					if (file_exists($file_path)) {
						log_message('batch', sprintf('tmp_uploads.id: %s; file unlink: %s;', $recordset->id, $file_path));
						$tmp_count++;
						$tmp_filesize += filesize($file_path);
					}
				}
				$utudao->remove_recordset($recordset);
			}
		}
		$result .= sprintf("Temporary File upload expiration: %s (deleted file: %s | size: %s)\n", $tmp_upload_count, $tmp_count, $tmp_filesize);

		// アップロード先のファイル有効期限をチェック
		$upload_count = $udao->count_file_expiration($target_date);
		log_message('debug', $udao->check_last_query(false, true));
		$count = 0;
		$filesize = 0;
		$user_checker = array();
		for ($xi = 0; $xi < $upload_count; $xi += 100) {
			$list = $udao->find_expired_list($target_date, $xi, 100);
			foreach ($list as $recordset) {
				foreach (array($recordset->file_path, $recordset->small_thumbnail_path, $recordset->large_thumbnail_path) as $file_path) {
					if (file_exists($file_path)) {
						log_message('batch', sprintf('uploads.id: %s; file unlink: %s;', $recordset->id, $file_path));
						$count++;
						$filesize += filesize($file_path);
					}
				}
				if (isset($user_checker[$recordset->user_id])) {
					$user_checker[$recordset->user_id]++;
				} else {
					$user_checker[$recordset->user_id] = 1;
				}
				$uudao->remove_recordset($recordset);
			}
		}
		$result .= sprintf("File upload expiration: %s (deleted file: %s | size: %s)\r\n", $upload_count, $count, $filesize);
		
		// 削除ファイルユーザーのサイズを更新
		$userdao  = new UserDao(MASTER);
		foreach (array_keys($user_checker) as $user_id) {
			$this->update_user_file_size($udao, $userdao, $user_id);
		}
		
		$curr_df = sprintf("Current:\n%s\n", implode("", execute_command($command)));
		$result .= "\n\n".$prev_df.$curr_df;
		
		if ($count === 0 && $tmp_count === 0) {
			return;
		}
		$this->benchmark->mark('file_expiration_end');
		
		
		
		
		$elapsed_time = $this->benchmark->elapsed_time('file_expiration_start', 'file_expiration_end');
		$title = sprintf('File Expiration: %s(%.5f)', $_SERVER['SERVER_NAME'], $elapsed_time);
		log_message('batch', $title."\n".$result);

		// メールを送信する
		$this->data['title'] = $title;
		$this->data['result'] = $result;
		$this->data['message'] = 'Result: ';
		$this->data['date'] = date("Y-m-d H:i:s");
		$this->data['end_message'] = 'END';
		
		$this->data['mailto'] = array_selector('mailto', $forminfo);
		$this->data['from'] = array_selector('from', $forminfo);
		list($subject, $message) = $this->get_mail('result.tpl');
		$this->send_mail($this->data['mailto'], $subject, $message, $this->data['from'], false);
	}

	/**
	 * アップロードに必要な処理を実行する。
	 * 毎分実行する。
	 */
	public function post_upload_transfer($user_id, $post_id) {
		$this->benchmark->mark('post_upload_transfer_start');
		$forminfo = $this->load->config('forminfo');
		$CI =& get_instance();
		$CI->load->library('UploadHandler', array('initialize' => false));
		
		$target_date = date('Y-m-d H:i:s');
		$uploaddao = new UploadDao(MASTER);
		$taskdao = new TaskDao(MASTER);
		$postdao = new PostDao(MASTER);
		$userdao  = new UserDao(MASTER);
		
		$task_count = $taskdao->count_list();
		$result = "";
		for ($xi = 0; $xi < $task_count; $xi += 100) {
			$list = $taskdao->get_list($xi);
			foreach ($list as $task) {
				if ($task->type != TYPE_BACKGROUND_UPLOAD_TASK) {
					continue;
				}
				// type=2 (TYPE_BACKGROUND_UPLOAD_TASK): アップロードに関する処理を行う ※分岐させる場合、別途調整
				$task_record = explode(',', $task->data);
				if (count($task_record) !== 4) {
					continue;
				}
				list($upload_dir, $post_id, $expire_type, $encryption_key) = $task_record; 
				$ret = $uploaddao->transfer_upload_file($upload_dir, $post_id, $expire_type);
				$report_line = "[done] post_id:".$post_id;
				$result .= $report_line."\n";
				log_message('batch', $report_line);
					
				// 転送失敗した場合、次へ
				if (!$ret) {
					continue;
				}
					
				//　転送成功した場合は後処理
				$checker[$post_id] = isset($checker[$post_id]) ? $checker[$post_id] + $uploaddao->transfer_count : 1;
				$taskdao->update_status($task->id, TASK_DONE);
				$postdao->update_upload_status($post_id, STATUS_UPLOAD_PREPROCESS);
				log_message('debug', $taskdao->check_last_query(false, true));					
				log_message('debug', $postdao->check_last_query(false, true));					
				
				// ユーザーのサイズを調整
				$this->update_user_file_size($uploaddao, $userdao, $user_id);
				
				$uploaddao->clear();
				$taskdao->clear();
				$postdao->clear();
			}
		}
		$this->benchmark->mark('post_upload_transfer_end');
		$elapsed_time = $this->benchmark->elapsed_time('post_upload_transfer_start', 'post_upload_transfer_end');
		$title = sprintf('Post Upload Transfer: %s(%.5f)', $_SERVER['SERVER_NAME'], $elapsed_time);
		log_message('batch', $title."\n".$result);

		// メールを送信する
		$this->data['title'] = $title;
		$this->data['result'] = $result;
		$this->data['message'] = 'Result: ';
		$this->data['date'] = date("Y-m-d H:i:s");
		$this->data['end_message'] = 'END';
		
		$this->data['mailto'] = array_selector('mailto', $forminfo);
		$this->data['from'] = array_selector('from', $forminfo);
		list($subject, $message) = $this->get_mail('result.tpl');
		$this->send_mail($this->data['mailto'], $subject, $message, $this->data['from'], false);
		
	}
	
	/**
	 * アップロードに必要な処理を実行する。
	 * 毎分実行する。
	 */
	public function upload_transfer($post_id = null) {
		$this->benchmark->mark('upload_transfer_start');
		$forminfo = $this->load->config('forminfo');
		$result = "";

		$uploaddao = new UploadDao(MASTER);
		$taskdao = new TaskDao(MASTER);
		$postdao = new PostDao(MASTER);
		$CI =& get_instance();
		$CI->load->library('UploadHandler', array('initialize' => false));
		
		$target_date = date('Y-m-d H:i:s');

		// 開始
		$checker = array();
		/////////////////////////////////////////////////////		
		//　転送前のファイルがあった場合は、公開処理を以下で行う
		/////////////////////////////////////////////////////		
		$task_count = $taskdao->count_list();
		for ($xi = 0; $xi < $task_count; $xi += 100) {
			$list = $taskdao->get_list($xi);
			foreach ($list as $task) {
				if ($task->type != TYPE_UPLOAD_TASK) {
					continue;
				}
				// type=1 (TYPE_UPLOAD_TASK): アップロードに関する処理を行う ※分岐させる場合、別途調整
				list($upload_dir, $post_id, $expire_type) = explode(',', $task->data);
				$uploaddao->transfer_upload_file($upload_dir, $post_id, $expire_type);
				log_message('batch', $upload_dir."(".$post_id."): transfer done.");
					
				// 転送失敗した場合、次へ
				if (!$result) {
					continue;
				}
					
				//　転送成功した場合は後処理
				$checker[$post_id] = isset($checker[$post_id]) ? $checker[$post_id] + $uploaddao->transfer_count : 1;
				$taskdao->update_status($task->id, TASK_DONE);
				$postdao->update_upload_status($post_id, STATUS_UPLOAD_READY);
				log_message('debug', $taskdao->check_last_query(false, true));					
				log_message('debug', $postdao->check_last_query(false, true));					

				$uploaddao->clear();
				$taskdao->clear();
				$postdao->clear();
			}
		}

		/////////////////////////////////////////////////////		
		//　未処理の投稿についてバックグラウンドで決められた件数分、実行（＝スレッド化）
		/////////////////////////////////////////////////////
		$list_count = BATCH_THREAD_COUNT;
		$condition = array('upload_status' => STATUS_UPLOAD_PREPROCESS);
		$sort = array(
				'posts.created_at' => 'asc', 
				'posts.id' => 'asc'
			);		
		$post_list = $postdao->search($condition, array($list_count, 0), $sort);
		foreach ($post_list as $post) {
			$result .= sprintf("post_id: %s; post_upload_count: %s; post_status: %s;\n", $post->id, $post->upload_count, $post->status);
			// バッチをバックグラウンドで実行
			execute_command(sprintf(POST_FILE_PROCESSOR, $post->id));
			
		}
		
		$this->benchmark->mark('upload_transfer_end');
		$elapsed_time = $this->benchmark->elapsed_time('upload_transfer_start', 'upload_transfer_end');
		$title = sprintf('Upload Transfer: %s(%.5f)', $_SERVER['SERVER_NAME'], $elapsed_time);
		log_message('batch', $title."\n".$result);
	}

	public function post_process($post_id) {
		$this->benchmark->mark('post_process_start');
		$forminfo = $this->load->config('forminfo');
		$result = "";

		$uploaddao = new UploadDao(MASTER);
		$postdao = new PostDao(MASTER);
		
		// 下記必須。
		$CI =& get_instance();
		$CI->load->library('UploadHandler', array('initialize' => false));
		
		$target_date = date('Y-m-d H:i:s');
		
		// 該当IDが不正な場合はエラー
		if (empty($post_id) && !is_numeric($post_id)) {
			log_message('error', 'No post id :'.$post_id);
			return;
		}

		/////////////////////////////////////////////////////		
		//　公開したアップロードファイルの後処理を実施する
		/////////////////////////////////////////////////////		
		$list_count = 100;
		$condition = array('upload_status' => STATUS_UPLOAD_PREPROCESS);
		$condition['id'] = $post_id;	
		$post_count = $postdao->count_by_condition($condition); 
		
		// 対象レコードがない場合も抜ける
		if ($post_count == 0) {
			log_message('debug', 'NO POST PROCESS');
			return;	
		}
				
		$sort = array(
				'posts.created_at' => 'asc', 
				'posts.id' => 'asc'
			);
		$message = array();
		// 先にファイル数を調整
		for ($xi = 0; $xi < $post_count; $xi += $list_count) {
			$post_list = $postdao->search($condition, array($list_count, $xi), $sort);
			foreach ($post_list as $post) {
				echo sprintf("%s:%s:%s:%s\n", $post->id, $post->upload_count, $post->status, $post->deleted_at);
				// step 1. 進捗表示するためのファイル数について精査し、必要あれば更新
				$this->update_file_count($post, $postdao, $uploaddao, $message);
			}
		}
		// その後サムネイル作成
		for ($xi = 0; $xi < $post_count; $xi += $list_count) {
			$post_list = $postdao->search($condition, array($list_count, $xi), $sort);
			foreach ($post_list as $post) {
				// step 2. ZIP解凍＋サムネイルの作成（後処理の実施）
				$this->post_process_files($post, $postdao, $uploaddao, $message);
				// step 3. 公開済みに調整
				$postdao->update_upload_status($post->id, STATUS_UPLOAD_READY);
			}
		}
		$result .= implode("\n", $message);

		if (empty($result)) {
			return;	
		}
		
		$this->benchmark->mark('post_process_end');
		$elapsed_time = $this->benchmark->elapsed_time('post_process_start', 'post_process_end');
		$title = sprintf('Post process: %s(%.5f) [%s]', $_SERVER['SERVER_NAME'], $elapsed_time, $post_id);
		log_message('batch', $title."\n".$result);
		
		// メールを送信する
		$this->data['title'] = $title;
		$this->data['result'] = $result;
		$this->data['message'] = 'Result: ';
		$this->data['date'] = date("Y-m-d H:i:s");
		$this->data['end_message'] = 'END';
		
		$this->data['mailto'] = array_selector('mailto', $forminfo);
		$this->data['from'] = array_selector('from', $forminfo);
		list($subject, $message) = $this->get_mail('result.tpl');
		$this->send_mail($this->data['mailto'], $subject, $message, $this->data['from'], false);
	}

	// step 1. 進捗表示するためのファイル数について精査
	private function update_file_count($post, $postdao, $uploaddao, &$message) {
		$upload_count = $post->upload_count;
		$process_count = $post->process_count;
		$upload_list = $uploaddao->get_by_post_id($post->id);
		$count = 0;
		$zipped_count = 0;

		$work_dir = APPPATH.'tmp_files/batch/';
		make_dir($work_dir);
	
		foreach ($upload_list as $upload) {
			$file_path = array_selector('file_path', $upload);
			$pass = array_selector('encryption_key', $upload);
			$ext = array_selector('file_extension', $upload);
	
			// ファイルがなければ抜ける
			if (!file_exists($file_path)) {
				continue;
			}
	
			// 暗号化パスワードの指定があるときはデコードする
			$file_path = $this->move_file_to_workspace($file_path, $work_dir, $pass);
			log_message('debug', 'DECODED: '.$file_path);
			
			// dicom のうちPixelSequence の数をカウントして加算。
			if (is_dicom($file_path)) {
				$dicom_count = count_dicom_pixel_sequence($file_path);
				log_message('debug', 'DICOM FILE: '.$file_path);
				if ($dicom_count > 0) {
					$count += $dicom_count;
					$zipped_count += $dicom_count;
				} else {
					$count++;
				}
			} else if (is_video($file_path, $ext)) {
				$count++;

			} else if (is_image($file_path, $ext)) {
				$count++;
				
			// ZIP の場合、ファイル情報を確認して数を加算。
			} else if ($ext === 'zip') {
				log_message('debug', 'ZIP FILE: '.$file_path);
				$zip = zip_open($file_path);
				if (!is_resource($zip)) {
					continue;
				}
				while( $entry = zip_read($zip) ) {
					//オープンしたファイルを読み込む
					$zip_file_name = zip_entry_name($entry);
					$zip_file_size = zip_entry_filesize($entry);
					if ($zip_file_size == 0) {
						continue;
					}
					if (preg_match('/__MACOSX/', $zip_file_name)) {
						continue;
					}
					// 関連ファイルのみカウント
					$count++;
					$zipped_count++;
				}
			// 処理が不要であれば削除
			} else {
				unlink($file_path);
				log_message('debug', 'REMOVE: '.$file_path);
			}
//			echo sprintf("upload_path:%s to %s\n", $upload->file_path, $file_path);
		}
		// プロセス対象の数で調整し直し
		if ($count == 0) {
			$postdao->update_process_status($post->id, STATUS_UPLOAD_READY, $count);
		} else if ($process_count != $count) {
			$postdao->update_process_status($post->id, $post->upload_status, $count);
			echo sprintf("update process status %s to %s\n", $process_count, $count);
		}
		$message[] = sprintf("[POST:%s] has %s (%s) files.", $post->id, $count, $zipped_count);
	}

	// step 2. ZIP解凍＋サムネイルの作成（後処理の実施）
	public function post_process_files($post, $postdao, $uploaddao, &$message) {
		// 既存の投稿IDに対するTYPE_ARCHIVED_FILEファイルがある場合は、検索して削除する
		$upload_list = $uploaddao->get_by_post_id($post->id);
		foreach ($upload_list as $upload) {
			$archive_upload_list = $uploaddao->get_by_parent_id($upload->id);
			echo "delete target (uploads.id:".$upload->id."): ".count($archive_upload_list)."\n";
			foreach ($archive_upload_list as $archive_upload) {
				$uploaddao->remove_recordset($archive_upload, true);
				$uploaddao->clear();
			}
		}

		$count = 0;
		$zipped_count = 0;
	
		$work_dir = APPPATH.'tmp_files/batch/';
		make_dir($work_dir);
				
		// 
		$upload_list = $uploaddao->get_by_post_id($post->id);
		$uploaddao->id = null;
		foreach ($upload_list as $upload) {
			$pass = array_selector('encryption_key', $upload);
			$original_file_path = array_selector('file_path', $upload);
			$file_path = $this->workspace_file_name($original_file_path, $work_dir, $pass);

			$original_file_name = array_selector('original_file_name', $upload);
			$user_id = array_selector('user_id', $upload);
			$parent_id = array_selector('id', $upload);
			$ext = array_selector('file_extension', $upload);
			$file_size = array_selector('file_size', $upload);

			$encryption_key = array_selector('encryption_key', $upload);
			$expired_type = array_selector('expired_type', $upload);
			$expired_at = array_selector('expired_at', $upload);

			// ファイルがなければ抜ける
			if (!file_exists($file_path)) {
				echo "NOT FOUND\n";
				continue;
			}

		    $file = new \stdClass();
			$file->file_path = $original_file_path;
			$file->name = $original_file_name;
			$file->size = $file_size;
			$file->ext = $ext;
			$file->expired_type = $expired_type;
			$file->expired_at = $expired_at;

			// dicom のうちPixelSequence の数をカウントして加算。
			if (is_dicom($file_path)) {
				$dicom_count = count_dicom_pixel_sequence($file_path);
				log_message('debug', 'DICOM FILE: '.$file_path);
				if ($dicom_count !== false && $dicom_count > 0) {
					$count += $dicom_count;
					
					// dicom のアーカイブを解凍
					$dcm_work_dir = dirname($file_path).'/dcm_archive_'.$upload->id;
					make_dir($dcm_work_dir);
					unzip_dicom_pixel_sequence($file_path, $dcm_work_dir);
					$basename = basename($file_path);
					$dcm_dir_list = scandir($dcm_work_dir);
					$dicom_sequence_list = array_diff($dcm_dir_list, array('.', '..'));

					// dicom ファイルについて zip ファイルと同様に個別のファイルを生成する。
					$zip_path = dirname($upload->file_path).'/zipped/';
					make_dir($zip_path);
					$dcm_index = 0;
					foreach ($dicom_sequence_list as $key => $dicom_simple_file_path) {
						$file_id = $this->uploadhandler->make_random_word();
						$dicom_file_path = $dcm_work_dir.'/'.$dicom_simple_file_path;
						$dicom_ext = get_dicom_file_extension($dicom_file_path, '');
						$published_dicom_file_path = $zip_path.$file_id.'.'.$dicom_ext;
						rename($dicom_file_path, $published_dicom_file_path);
						log_message('debug', 'published dicom from: '.$dicom_file_path.'; to: '.$published_dicom_file_path);

						$dicom_file = new \stdClass();
						$dicom_file->file_path = $published_dicom_file_path;
						$dicom_file->name = 'index_'.$dcm_index.'.'.$dicom_ext;
						$dicom_file->size = filesize($published_dicom_file_path);
						$dicom_file->ext = 'dcm'; // 元フォーマットを保持
						$dicom_file->expired_type = $expired_type;
						$dicom_file->expired_at = $expired_at;
						log_message('debug', sprintf("%s,%s,%s,%s,%s",$user_id, $published_dicom_file_path, $parent_id, TYPE_ARCHIVED_FILE, true));
						$this->uploadhandler->post_process_data($user_id, $uploaddao, $published_dicom_file_path, $dicom_file, $parent_id, TYPE_ARCHIVED_FILE, true);
						log_message('debug', 'dicom archive pos: '.$dcm_index);
						$zipped_count++;
						$dcm_index++;
					}
//					$uploaddao->update_recordset($upload->id, array('file_extension' => 'zip', 'file_info' => json_encode($file->file_info)));
				} else {
					$this->uploadhandler->make_thumbnail($file_path, $file, $encryption_key);
					log_message('debug', var_export($file, true));
					$this->uploadhandler->publish_thumbnail($file);
					$uploaddao->update_file_info($upload->id, $file);
					$count++;
				}
			} else if (is_video($file_path, $ext)) {
				$this->uploadhandler->make_thumbnail($file_path, $file, $encryption_key);
				log_message('debug', var_export($file, true));
				$this->uploadhandler->publish_thumbnail($file);
				$uploaddao->update_file_info($upload->id, $file);
				$count++;

			} else if (is_image($file_path, $ext)) {
				$this->uploadhandler->make_thumbnail($file_path, $file, $encryption_key);
				log_message('debug', var_export($file, true));
				$this->uploadhandler->publish_thumbnail($file);
				$uploaddao->update_file_info($upload->id, $file);
				$count++;
				
			// ZIPを解凍
			} else if ($ext === 'zip') {
				log_message('debug', 'ZIP FILE: '.$file_path);
				$this->uploadhandler->unzip_file($upload, $upload->id, true);
			}
			// 作業ファイルを削除
			if (file_exists($file_path)) {
				log_message('debug', 'delete file: '.$file_path);
				unlink($file_path);
			}
			$message[] = sprintf("[done] upload_id:%s", $upload->id);
		}
	}

	private function move_file_to_workspace($file_path, $work_dir, $pass) {
		if (!empty($pass)) {
		    $work_file_path = $this->workspace_file_name($file_path, $work_dir, $pass);
			$decode_cmd = sprintf(FILE_DECODER, $pass, $file_path, $work_file_path);
		    system($decode_cmd);
			log_message('debug', 'decode cmd: '.$decode_cmd);
		} else {
		    $work_file_path = $this->workspace_file_name($file_path, $work_dir, $pass);
			system(sprintf("cp -f %s %s", $file_path, $work_file_path));	
		}
		return $work_file_path;
	}
	
	private function workspace_file_name($file_path, $work_dir, $pass) {
		$file_path = $work_dir.basename($file_path);
		return $file_path;
	}

	private function post_process_single_file($file_path, $options) {
		$file_info_list = array();
		if (is_dicom($file_path)) {
			$dicom_count = count_dicom_pixel_sequence($file_path);
			// 複数の画像コンテナの場合
			if ($dicom_count > 0) {
				$dcm_work_dir = dirname($file_path).'/dcm_archive_'.$upload->id;
				make_dir($dcm_work_dir);
				unzip_dicom_pixel_sequence($file_path, $dcm_work_dir);
				$basename = basename($file_path);
				$dcm_dir_list = scandir($dcm_work_dir);
				$dicom_sequence_list = array_diff(array_filter($dcm_dir_list, 
					function ($path) {
						return preg_match('/^'.$basename.'/', $path);
					}
				), array('.', '..'));
				echo basename($file_path)."\n";
			
			// 一枚ごとのファイルの場合
			} else {
				$file_info_list[] = $this->make_resize_image($file_path, $options);
			}
		// video の場合
		} else if (is_video($file_path, $ext)) {
			$file_info_list[] = $this->make_resize_image($file_path, $options);
		// 画像の場合
		} else if (is_image($file_path, $ext)) {
			$file_info_list[] = $this->make_resize_image($file_path, $options);
		}
		return $file_info_list;
	}

	private function make_resize_image($file_path, $options) {
		return array();
//		return $this->uploadhandler->handle_image_file($file_path, $options);
	}	

	/**
	 * お知らせ通知
	 * １時間に１度実行する。
	 */
	public function post_notification() {
		$this->benchmark->mark('notification_mail_start');
		$forminfo = $this->load->config('forminfo');
		
		$filename = APPPATH.'cache/post_notification.ini';
		if (file_exists($filename)) {
			$prev_date = array_selector('prev_date', parse_ini_file($filename), null);
		} else {
			$prev_date = null;
		}
		// １日まえまでの投稿を対象
		$target_date = date('Y-m-d H:i:s', time() - 86400); 
		
		// 親スレッドに対する通知を行う
		$postdao = new PostDao();
		$openlogdao = new OpenLogDao();
		$userdao = new UserDao();
		$configdao = new ConfigDao();
		$maildao = new MailDao(MASTER);
		$noticedao = new NoticeDao(MASTER);

		$parent_user_table = array();
		$user_config_table = array();
		// 投稿種別
		$condition = array('created_datetime_start' => $prev_date, 'created_datetime_end' => $target_date, 'post_type' => 'all');
		$post_count = $postdao->count_by_condition($condition);
//		log_message('debug', $postdao->check_last_query(false, true));
//		echo $postdao->check_last_query(false, true)."\n";
			
		echo "post_count: ".$post_count."\n";
		$count = 0;
		$result = "";
			
		// 投稿数により１００件ずつ処理
		for ($xi = 0; $xi < $post_count; $xi += 100) {
			$list = $postdao->search($condition, array(100, $xi), array('posts.created_at' => 'asc', 'posts.id' => 'asc'));
//			log_message('debug', $postdao->check_last_query(false, true));
			// 投稿一覧
			foreach ($list as $recordset) {
				$parent_user = null;
				$parent_user_id = $recordset->parent_user_id;
				$type = $recordset->type;
				$unread_post_id = $recordset->id;
				if (!empty($parent_user_id)) {
					if (isset($parent_user_table[$parent_user_id])) {
//						print "load parent user: ". $parent_user_id."\n";
						$parent_user = $parent_user_table[$parent_user_id];
					} else {
						$parent_user = $userdao->get_by_id($parent_user_id);
						$parent_user_table[$parent_user_id] = $parent_user;
					}
					$unread_post_id = $recordset->parent_id;
				}
					
				log_message('debug', 'Processing: '.$recordset->id);
				echo 'Processing: '.$recordset->id."\n";
				$unread_user_list = $openlogdao->get_unread_by_post_id($unread_post_id, 1, $recordset->user_id);
				$unread_group_list = $openlogdao->get_unread_by_post_id($unread_post_id, 2, $recordset->user_id);

				$unread_list = array();
				$unread_list = $unread_user_list + $unread_group_list;
				if ($type == TYPE_COMMENT && $parent_user_id != $recordset->user_id) {
					$unread_list[] = $parent_user;
//					echo "Target Post Id: ".$recordset->id." => added user: ".$parent_user->id."\n";
				}
//				echo $openlogdao->check_last_query(false, true)."\n";
//				log_message('debug', $openlogdao->check_last_query(false, true));
				// ユーザー一覧
				foreach ($unread_list as $user) {
					if (isset($user_config_table[$user->id])) {
						$send_mail_flag = $user_config_table[$user->id];
					} else {
						$list_config = $configdao->get_by_user_id($user->id, CONFIG_CATEGORY_NOTICE)->all;
						$send_mail_flag = false;
						foreach ($list_config as $config) {
							if (array_selector('target_id', $config) == CONFIG_TYPE_THREAD_NOT_READ_24) {
								$send_mail_flag = array_selector('status', $config) == 1;
								break;
							}
						}
						echo "send_mail_flag:".$send_mail_flag."\n";
						$user_config_table[$user->id] = $send_mail_flag;
					}
					log_message('notice_mail', sprintf("Post id: %s; Unread user id: %s(%.5f) > Thread Type: %s | Posted: %s | Thread Posted: %s | Send Mail Flag: %s", $recordset->id, $user->id, $user->language, $type, $recordset->user_id, $parent_user_id, $send_mail_flag));
//					echo sprintf("Post id: %s; Unread user id: %s(%.5f) > Thread Type: %s | Posted: %s | Thread Posted: %s\n", $recordset->id, $user->id, $user->language, $type, $recordset->user_id, $parent_user_id);
					$result .= $this->notification_mail($noticedao, $maildao, $recordset, $user, $parent_user, $type, $send_mail_flag);
					$result .= "\n";
					$count++;
				}
				$openlogdao->clear();
			}
			$postdao->clear();
		}
		$postdao->clear();
				
		echo $prev_date.":".$target_date."\n";
		save_file($filename, sprintf("prev_date=%s", $target_date));
		$this->benchmark->mark('notification_mail_end');

		if ($count == 0) {
			return;
		}
		$elapsed_time = $this->benchmark->elapsed_time('notification_mail_start', 'notification_mail_end');
		$title = sprintf('Notification Mail: %s(%.5f)', $_SERVER['SERVER_NAME'], $elapsed_time);
		log_message('batch', $title."\n".$result);
		
		// メールを送信する
		$this->data['title'] = $title;
		$this->data['result'] = $result;
		$this->data['message'] = 'Result: ';
		$this->data['date'] = date("Y-m-d H:i:s");
		$this->data['end_message'] = 'END';
		
		$this->data['mailto'] = array_selector('mailto', $forminfo);
		$this->data['from'] = array_selector('from', $forminfo);
		list($subject, $message) = $this->get_mail('result.tpl');
		$this->send_mail($this->data['mailto'], $subject, $message, $this->data['from'], false);
	}

	//・２４時間以内に既読状態にならなかったコンサルト／カンファ、コメントがあった場合。　※バッチで登録
	//　L-F-0066-I 文言：「（グループオーナー）さんからコンサルト／カンファが届きましたが、未読です。」
	//　L-F-0067-I 文言：「（グループオーナー）さんからのコンサルト／カンファに（コメント者）さんからコメントが届きましたが、未読です。」
	//　L-F-0068-I 文言：「コンサルト／カンファに（コメント者）さんからコメントが届きましたが、未読です。」
	private function notification_mail($noticedao, $maildao, $post, $user, $parent_user, $type, $send_mail_flag = false) {
		$notice = array();
		// forward 種別が特定できない場合、個人宛であると考える
		if (isset($user->forward_type) && $user->forward_type == 2) {
			$notice['link'] = "group/" . $user->forward_id . "/post/".(empty($post->parent_id) ? $post->id : $post->parent_id);
		} else {
			$notice['link'] = "user/post/".(empty($post->parent_id) ? $post->id : $post->parent_id);
		}
		$this->lang->load('application', $user->language);
		$this->lang->load('msg_error', $user->language);

		$notice['language'] = $user->language;
		$post_user = array();
		$post_user['ja'] = user_name($post, 'japanese');
		$post_user['en'] = user_name($post, 'english');
		if ($type == TYPE_THREAD) {
			$notice['message'] = json_encode(array('L-F-0066-I', array($post_user)));
		} else if ($type == TYPE_COMMENT && $parent_user->id != $user->id) {
			$parent_post_user = array();
			$parent_post_user['ja'] = user_name($parent_user, 'japanese');
			$parent_post_user['en'] = user_name($parent_user, 'english');
			$notice['message'] = json_encode(array('L-F-0067-I', array($parent_post_user, $post_user)));
//			echo $type.":".$parent_user->id.":".$user->id.":".$notice['message']."\n";
		} else if ($type == TYPE_COMMENT && $parent_user->id == $user->id) {
			$notice['message'] = json_encode(array('L-F-0068-I', array($post_user)));
//			echo $type.":".$parent_user->id.":".$user->id.":".$notice['message']."\n";
		} else {
			log_message('notice_mail', 'Something wrong at notification mail.');
			return;
		}
		$notice['user_id'] = $user->id;
		$noticedao->insert_notice($notice['user_id'], $notice['link'], $notice['message']);
		$noticedao->id = null;
		$noticedao->clear();
		$notice['subject_mail'] = $this->lang->line('label_notice_subject_email');
		log_message('notice_mail', sprintf("noticed: %s/%s/%s", $notice['user_id'], $notice['link'], $notice['message']));

		if (!$send_mail_flag) {
			log_message('notice_mail', "not mailed.");
			return;
		}
		$mail = array();
		$mail['mail_from'] = MAIL_FROM;
		$mail['subject'] = $this->lang->line('label_notice_subject_email');
		$mail['content'] = $noticedao->parse_message($notice['message']);
		$mail['language'] = $notice['language'];
		$mail['mail_to'] = $user->email;
		$mail['link'] = $notice['link'];
		$maildao->insert($mail);
		$maildao->id = null;
		$maildao->clear();
		log_message('notice_mail', "mail to: ". $user->email);
		return "mail to: ". $user->email;
	}

	/**
	 * 集計処理
	 */
	public function analyze_statistics() {
		print "analyze statistics done.";
	}

}