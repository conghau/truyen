<?php
/*
 * jQuery File Upload Plugin PHP Class 8.0.4
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

class UploadHandler
{

    protected $options;

    // PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $error_messages = array();

    protected $image_objects = array();
	
	protected $session_id;

    public function __construct($options = null) {
    	$fixed_url = (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? str_replace("http:", "https:", base_url()) : base_url();
		
		// Code Igniter 関連
		$CI =& get_instance();
		$CI->lang->load('application');
		$CI->load->helper('dicomutil');
		$CI->load->helper('movutil');
		
		// 基本エラーメッセージ
		$this->error_messages = array(
	        1 => lang('label_upload_error_1'),
	        2 => lang('label_upload_error_2'),
	        3 => lang('label_upload_error_3'),
	        4 => lang('label_upload_error_4'),
	        6 => lang('label_upload_error_6'),
	        7 => lang('label_upload_error_7'),
	        8 => lang('label_upload_error_8'),
	        'post_max_size' => lang('label_upload_error_post_max_size'),
	        'max_file_size' => lang('label_upload_error_max_file_size'),
	        'min_file_size' => lang('label_upload_error_min_file_size'),
	        'accept_file_types' => lang('label_upload_error_accept_file_types'),
	        'max_number_of_files' => lang('label_upload_error_max_number_of_files'),
	        'max_width' => lang('label_upload_error_max_width'),
	        'min_width' => lang('label_upload_error_min_width'),
	        'max_height' => lang('label_upload_error_max_height'),
	        'min_height' => lang('label_upload_error_min_height'),
	        'abort' => lang('label_upload_error_abort'),
	        'image_resize' => lang('label_upload_error_image_resize'),
	        'max_upload_size' => lang('label_upload_error_max_upload_size')
		);
		
		if ($options && isset($options['group_id']) && $options['group_id'] > 0) {
			$group_path = "/".$options['group_id'];
		} else {
			$group_path = "";
		}
		
		// オプション設定
		$this->options = array(
			'initialize' => true,
			'dicom_parser' => false, 
            'script_url' => $fixed_url.'file/upload'.$group_path,
            'upload_dir' => APPPATH.'tmp_files/',
            'upload_url' => $fixed_url.'file/upload'.$group_path,
            'download_url' => $fixed_url.'file/download'.$group_path,
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'param_name' => 'files',
            // Set the following option to 'POST', if your server does not support
            // DELETE requests. This is a parameter sent to the client:
            'delete_type' => 'DELETE',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => array(
                'OPTIONS',
                'HEAD',
                'GET',
                'POST',
                'PUT',
                'PATCH',
                'DELETE'
            ),
            'access_control_allow_headers' => array(
                'Content-Type',
                'Content-Range',
                'Content-Disposition'
            ),
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => true,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024, // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Defines which files (based on their names) are accepted for upload:
            'accept_file_types' => '/.+$/i',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => 1073741824, //1024 * 1024 * 1024,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => 20,
            // Defines which files are handled as image files:
            'image_file_types' => '/\.(gif|jpe?g|png)$/i',
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 2,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => '/usr/local/bin/convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            'convert_params' => '-trim -background black -define dcm:display-range=reset -auto-level -normalize',
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => '/usr/local/bin/identify',
            'image_versions' => array(
                // The empty image version key defines options for the original image:
//                '' => array(
//                    // Automatically rotate images based on EXIF meta data:
//                    'auto_orient' => true
//                ),
                // Uncomment the following to create medium sized images:
                'thumbnail_l' => array(
                    'max_width' => 512,
                    'max_height' => 512
                ),
                'thumbnail_s' => array(
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
                    //'upload_url' => $this->get_full_url().'/thumb/',
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    //'crop' => true,
                    'max_width' => 85,
                    'max_height' => 85
                )
            )
        );
        if ($options) {
            $this->options = $options + $this->options;
        }
        if (array_key_exists('error_messages', $options) && is_array($options['error_messages'])) {
            $this->error_messages = $options['error_messages'] + $this->error_messages;
        }
        if ($this->options['initialize']) {
            $this->initialize();
			log_message('debug', 'FileHandler initialized');
        } else {
        	log_message('debug', 'FileHandler not initialized');
		}
    }

    protected function initialize() {
        switch ($this->get_server_var('REQUEST_METHOD')) {
            case 'OPTIONS':
            case 'HEAD':
                $this->head();
                break;
            case 'GET':
                $this->get();
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                $this->post();
                break;
            case 'DELETE':
                $this->delete();
                break;
            default:
                $this->header('HTTP/1.1 405 Method Not Allowed');
        }
    }
	
	/**
	 * 指定されたUploadパスに対して登録処理を行います。
	 * ※使うときは、$options['initialize'] = false を指定してライブラリをロードしてください。
	 */
	public function regist_upload_file($post_id, $expire_type) {
		$upload_dir = $this->get_upload_path();
		log_message('debug', 'Start making relation with posts data: '.$upload_dir.'/'.$post_id.'/'.$expire_type);
		$dao = new UploadDao();
		$dao->transfer_upload_file($upload_dir, $post_id, $expire_type);
		log_message('debug', 'Start making relation with posts data');
	}

	/**
	 * セッションIDを取得する。
	 */
    protected function get_session_id() {
    	if (empty($this->session_id)) {
			@session_start();
	        $this->session_id = session_id();
			log_message('debug', 'Createded session:'.session_id());
		}
		return $this->session_id;
    }

	/**
	 * ユーザー向けパスを取得する
	 * APPPATH/files/(user_id)/(session_id)
	 */
    protected function get_user_path() {
        if ($this->options['user_dirs']) {
            return $this->options['user_dirs'].$this->get_session_id().'/';
        }
        return '';
    }

	/**
	 * アップロードされたファイルパスを取得する
	 */
    public function get_upload_path($file_name = null, $version = null) {
        log_message('debug', sprintf("file_name = %s, version = %s", $file_name, $version));
        $file_name = $file_name ? $file_name : '';
        if (empty($version)) {
            $version_path = '';
        } else if (isset($this->options['image_versions'][$version]['upload_dir'])) {
            $version_dir = @$this->options['image_versions'][$version]['upload_dir'];
            if ($version_dir) {
                return $this->get_user_path().$version_dir.$file_name;
            }
            $version_path = $version.'/';
        } else {
            $version_path = $version.'/';
		}
	 	// APPPATH/files/(user_id)/(session_id)
        return $this->options['upload_dir'].$this->get_user_path().$version_path.$file_name;
    }

    protected function get_query_separator($url) {
        return strpos($url, '?') === false ? '?' : '&';
    }

	/**
	 * ダウンロードURLを取得する
	 */
    protected function get_download_url($file_name, $version = null, $direct = false) {
        if (!$direct && $this->options['download_via_php']) {
            $url = $this->options['download_url'].'/'.rawurlencode($file_name);
            if ($version) {
                $url .= '/'.rawurlencode($version);
            }
            return $url;
        }
        if (empty($version)) {
            $version_path = '';
        } else {
            $version_url = @$this->options['image_versions'][$version]['upload_url'];
            if ($version_url) {
                return $version_url.$this->get_user_path().rawurlencode($file_name);
            }
            $version_path = '/'.rawurlencode($version);
        }
        return $this->options['download_url'].$this->get_user_path().rawurlencode($file_name).$version_path;
    }


	/**
	 * ファイルの追加プロパティを設定する
	 */
    protected function set_additional_file_properties($file) {
		$ext = strtolower(pathinfo($file->upload_name, PATHINFO_EXTENSION));
		$file_id = basename($file->upload_name, '.'.$ext);
		// ダウンロードURLを設定
        $file->url = $this->get_download_url($file->upload_name);
		// 削除URLを設定
	    $file->deleteUrl = $this->options['script_url']
            .$this->get_query_separator($this->options['script_url'])
            .$this->get_singular_param_name()
            .'='.rawurlencode($file_id);
        $file->deleteType = $this->options['delete_type'];
        if ($file->deleteType !== 'DELETE') {
            $file->deleteUrl .= '&_method=DELETE';
        }
		// credentials のアクセスコントロールの設定があるかチェック
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
		// サムネイルURLの作成
		foreach($this->options['image_versions'] as $version => $options) {
        	if (!empty($version)) {
            	if (is_file($this->get_upload_path($file->name, $version))) {
                	$file->{$version.'Url'} = $this->get_download_url(
                    	$file->name,
                        $version
                    );
                }
            }
        }


    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size) {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

	/**
	 * ファイルサイズを取得する
	 */
    protected function get_file_size($file_path, $clear_stat_cache = false) {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
		$file_size = $this->fix_integer_overflow(filesize($file_path));
		log_message('debug', 'FILE SIZE: '.$file_size.' | '.filesize($file_path).' | '.$file_path);
        return $file_size;
    }

    protected function is_valid_file_object($file_name) {
        $file_path = $this->get_upload_path($file_name);
        if (is_file($file_path) && $file_name[0] !== '.') {
            return true;
        }
        return false;
    }

	/**
	 * ファイル情報を取得する
	 * @param $file_name 対象ファイル名
	 * @return ファイル情報
	 */
	// GETメソッドで取得する際のファイル名
    protected function get_file_object($file_name) {
        if ($this->is_valid_file_object($file_name)) {
            $file = new \stdClass();
			$dao = new TmpUploadDao();
			$target = $dao->find_by_file_id($file_name);
			if ($target === NULL) {
				log_message('debug', $file_name.' is not found.');
				return null;
			}
			log_message('debug', 'FOUND: '.$file_name.'!');
			$file_info = json_decode($target->file_info);
			$file->name = $target->original_file_name;
			$file->ext = $target->file_extension;
			$file->ext_type = get_file_ext_type($file->ext);
            $file->upload_name = $file_name;
            $file->size = $target->file_size; // get_file_size($this->get_upload_path($file_name));

            $this->set_additional_file_properties($file);
			log_message('debug', var_export($file, true));
            return $file;
        }
        return null;
    }

    protected function get_file_objects($iteration_method = 'get_file_object') {
        $upload_dir = $this->get_upload_path();
        if (!is_dir($upload_dir)) {
            return array();
        }
        return array_values(array_filter(array_map(
            array($this, $iteration_method),
            scandir($upload_dir)
        )));
    }

    protected function count_file_objects() {
        return count($this->get_file_objects('is_valid_file_object'));
    }

    public function get_file_objects_file_size() {
    	$size = 0;
        foreach ($this->get_file_objects() as $file) {
        	if (empty($file)) {
        		continue;
			}
			$size += $file->size;
		}
		return $size;
    }


    protected function get_error_message($error) {
        return array_key_exists($error, $this->error_messages) ?
            $this->error_messages[$error] : $error;
    }

    function get_config_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $this->fix_integer_overflow($val);
    }

    protected function validate($uploaded_file, $file, $error, $index) {
    	if ($error) {
    		$file->error = $this->get_error_message($error);
    		return false;
    	}
		
		// アップロードされたファイルサイズを算出
		$uploaded_file_size = $this->get_file_objects_file_size();
		$free_size = $this->options['user']->max_file_size - $this->options['user']->file_size - $uploaded_file_size;
		log_message('debug', sprintf('UPLOAD FREE SIZE: %s = %s - %s - %s', $free_size, $this->options['user']->max_file_size, $this->options['user']->file_size, $uploaded_file_size));		
		if ($file->size > $free_size) {
			$file->error = $this->get_error_message('max_upload_size');
			return false;
		}

        $content_length = $this->fix_integer_overflow(intval(
            $this->get_server_var('CONTENT_LENGTH')
        ));
        $post_max_size = $this->get_config_bytes(ini_get('post_max_size'));
        if ($post_max_size && ($content_length > $post_max_size)) {
            $file->error = $this->get_error_message('post_max_size');
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], $file->name)) {
            $file->error = $this->get_error_message('accept_file_types');
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = $this->get_file_size($uploaded_file);
        } else {
            $file_size = $content_length;
        }
        if ($this->options['max_file_size'] && (
                $file_size > $this->options['max_file_size'] ||
                $file->size > $this->options['max_file_size'])
            ) {
            $file->error = $this->get_error_message('max_file_size');
            return false;
        }
        if ($this->options['min_file_size'] &&
            $file_size < $this->options['min_file_size']) {
            $file->error = $this->get_error_message('min_file_size');
            return false;
        }
        if (is_int($this->options['max_number_of_files']) &&
                ($this->count_file_objects() >= $this->options['max_number_of_files']) &&
                // Ignore additional chunks of existing files:
                !is_file($this->get_upload_path($file->name))) {
            $file->error = $this->get_error_message('max_number_of_files');
            return false;
        }
        $max_width = @$this->options['max_width'];
        $max_height = @$this->options['max_height'];
        $min_width = @$this->options['min_width'];
        $min_height = @$this->options['min_height'];
        if (($max_width || $max_height || $min_width || $min_height)
           && preg_match($this->options['image_file_types'], $file->name)) {
            list($img_width, $img_height) = $this->get_image_size($uploaded_file);
        }
        if (!empty($img_width)) {
            if ($max_width && $img_width > $max_width) {
                $file->error = $this->get_error_message('max_width');
                return false;
            }
            if ($max_height && $img_height > $max_height) {
                $file->error = $this->get_error_message('max_height');
                return false;
            }
            if ($min_width && $img_width < $min_width) {
                $file->error = $this->get_error_message('min_width');
                return false;
            }
            if ($min_height && $img_height < $min_height) {
                $file->error = $this->get_error_message('min_height');
                return false;
            }
        }
        return true;
    }

	/**
	 * 末尾に _(数値)を付ける
	 */
    protected function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return '_'.$index.''.$ext;
    }

	/**
	 * ファイル名重複の場合は、末尾に _(数値)を付ける
	 */
    protected function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }

    protected function get_unique_filename($file_path, $name, $size, $type, $error,
            $index, $content_range) {
        while(is_dir($this->get_upload_path($name))) {
            $name = $this->upcount_name($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fix_integer_overflow(intval($content_range[1]));
        while(is_file($this->get_upload_path($name))) {
            if ($uploaded_bytes === $this->get_file_size(
                    $this->get_upload_path($name))) {
                break;
            }
            $name = $this->upcount_name($name);
        }
        return $name;
    }

    protected function trim_file_name($file_path, $name, $size, $type, $error,
            $index, $content_range) {
        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = trim(basename(stripslashes($name)), ".\x00..\x20");
        // Use a timestamp for empty filenames:
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        // Add missing file extension for known image types:
        if (strpos($name, '.') === false &&
                preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
            $name .= '.'.$matches[1];
        }
        if (function_exists('exif_imagetype')) {
            switch(@exif_imagetype($file_path)){
                case IMAGETYPE_JPEG:
                    $extensions = array('jpg', 'jpeg');
                    break;
                case IMAGETYPE_PNG:
                    $extensions = array('png');
                    break;
                case IMAGETYPE_GIF:
                    $extensions = array('gif');
                    break;
            }
            // Adjust incorrect image file extensions:
            if (!empty($extensions)) {
                $parts = explode('.', $name);
                $extIndex = count($parts) - 1;
                $ext = strtolower(@$parts[$extIndex]);
                if (!in_array($ext, $extensions)) {
                    $parts[$extIndex] = $extensions[0];
                    $name = implode('.', $parts);
                }
            }
        }
        return $name;
    }

	/**
	 * ファイル名を取得する
	 */
    protected function get_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
        return $this->get_unique_filename(
            $file_path,
            $this->trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range),
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
    }

	/**
	 * ファイル名を取得する
	 */
    protected function get_unique_random_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
    	$name = $this->make_random_word();
        while(is_dir($this->get_upload_path($name))) {
            $name = $this->make_random_word();
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fix_integer_overflow(intval($content_range[1]));
        while(is_file($this->get_upload_path($name))) {
            if ($uploaded_bytes === $this->get_file_size($this->get_upload_path($name))) {
                break;
            }
            $name = $this->make_random_word();
        }
		log_message('debug', 'unique_file_name: '.$name);
        return $name;
	}

	public function make_random_word() {
		return md5(uniqid(mt_rand(), true)).md5(uniqid(rand(), true));	
	}

    public function post_process_data($user_id, $dao, $file_path, $file, $parent_id = NULL, 
    		$file_type = TYPE_BASE_FILE, $make_thumbnail = false, $encryption = true) {
		$recordset = array();
    	// ディレクトリ構成
    	// APPPATH.'tmp_files/($user_id)/(target_board)/($session)/対象ファイル名'
    	// target_board => 個人の場合、0。グループの場合、グループID
    	// APPPATH.'tmp_files/($user_id)/(target_board)/($session)/thumbnail/対象ファイル名'
    	//  => サムネイル保存先
    	// APPPATH.'tmp_files/($user_id)/(target_board)/($session)/unzipped/対象ファイル名/解凍ファイル'
    	//  => 解凍ファイル保存先
    	// APPPATH.'tmp_files/($user_id)/(target_board)/($session)/unzipped/対象ファイル名/thumbnail/解凍ファイル名'
    	//  => 解凍/サムネイルファイル保存先
    	    	    	    	
        // Handle form data, e.g. $_REQUEST['description'][$index]
        if (empty($file->encryption_key)){ 
			$encryption_key = md5(uniqid(mt_rand(), true));
		} else {
			$encryption_key = $file->encryption_key;
		}
        // サムネイルを作る場合は、暗号化前に作成
        $file->file_info = array();
		if ($make_thumbnail) {
			try {
				log_message('debug', 'Making thumbnail: '.$file_path);
				$this->make_thumbnail($file_path, $file, $encryption_key);
			} catch (Exception $e) {
                error_log($e->getMessage());
				log_message('errur', var_export($e->getMessage(), true));
			}
		}
		// 元ファイルの暗号化
		if ($encryption) {
			$this->encrypt_file($file_path, $encryption_key);
		}

		// 一時アップロード情報へデータを登録
		$recordset['file_size'] = $file->size;
		$recordset['original_file_name'] = $file->name;
		$recordset['file_extension'] = $file->ext;
		$recordset['file_id'] = basename($file_path, '.'.$recordset['file_extension']);
		if (!empty($parent_id)) {
			$recordset['parent_id'] =  $parent_id;
		}
		$recordset['file_info'] = json_encode($file->file_info);
		$recordset['encryption_key'] = $encryption_key;
//		$recordset['file_data'] = '';
		$recordset['file_type'] = $file_type; // 1: アップロードファイルの実体。 2: 解凍ファイル
		$recordset['hash_value'] = md5_file($file_path);
		$recordset['file_path'] = $file_path;
		if (isset($file->thumbnail_s_path)) {
			$recordset['small_thumbnail_path'] = $file->thumbnail_s_path;
			unset($file->thumbnail_s_path);
		} else {
			$recordset['small_thumbnail_path'] = '';
		}
		if (isset($file->thumbnail_l_path)) {
			$recordset['large_thumbnail_path'] = $file->thumbnail_l_path;
			unset($file->thumbnail_l_path);
		} else {
			$recordset['large_thumbnail_path'] = '';
		}
		$recordset['user_id'] = $user_id;
		$recordset['expired_type'] = $file->expired_type;
		$recordset['expired_at'] = $file->expired_at;
		$recordset['status'] = 1;

		// openssl enc -d -aes-128-cbc -in encoded.file -pass pass:test > original.file
		$recordset['id'] = $dao->insert_recordset($recordset);
		$dao->id = null;
		$dao->clear();
		log_message('debug', $dao->check_last_query(false, true));
		return $recordset;
    }

	/**
	 * サムネイルを作成
	 * @param $file_path 対象ファイル
	 * @param $file データ保持オブジェクト（※ここにサムネイル情報を追加）
	 * @param $encryption_key 暗号化文字列
	 */
	public function make_thumbnail($file_path, $file, $encryption_key = null) {
		// DICOM 変換
		if (is_dicom($file_path)) {
			log_message('debug', '[DICOM Processing]');
// セキュリティリスクが高いため、DBに残さないよう調整
//			$file->file_info['dicom'] = load_dicom_tags($file_path);
	    	$this->handle_image_file($file_path, $file, true);
		// ビデオ変換
		} else if (is_video($file_path)) {
			log_message('debug', '[VIDEO Processing]');	
	        $this->handle_image_file($file_path, $file, false);
		// 画像変換
		} else if ($this->is_valid_image_file($file_path)) {
	        log_message('debug', '[IMAGE Processing]');
	    	$this->handle_image_file($file_path, $file, false);
	    } else {
	    	log_message('debug', '[Other] Not making thumbnail...');	
		}
		// サムネイルファイルの暗号化
		if (!empty($encryption_key)) {
			foreach($this->options['image_versions'] as $version => $options) {
				if (!empty($version) && isset($file->{$version.'_path'})) {
			    	$this->encrypt_file($file->{$version.'_path'}, $encryption_key);
				}
			}
		}
	}
	
	/**
	 * 本番環境への公開
	 */
	public function publish_thumbnail($file) {
		foreach($this->options['image_versions'] as $version => $options) {
			if (!empty($version) && isset($file->{$version.'_path'})) {
				$new_dir = dirname($file->file_path).'/'.$version;
				make_dir($new_dir);
				$new_file_name = $new_dir.'/'.basename($file->{$version.'_path'});
				rename($file->{$version.'_path'}, $new_file_name);
				log_message('debug', 'Publish Thumbnail: '.$version.'|'.$file->{$version.'_path'}.'=>'.$new_file_name);
				$file->{$version.'_path'} = $new_file_name;
			}
		}
	}

	protected function parse_dicom_result($info) {
		return is_array($info) ? join("", $info) : $info;	
	}

	public function encrypt_file($file_path, $pass) {
    	$encrypt_file_path = $file_path."_enc";
		$encode_cmd = sprintf(FILE_ENCODER, $pass, $file_path, $encrypt_file_path);
    	system($encode_cmd);
		log_message('debug', 'encode cmd: '.$encode_cmd);
		if (file_exists($encrypt_file_path)) {
			unlink($file_path);
			rename($encrypt_file_path, $file_path);
			log_message('debug', 'encrypted file: '.$file_path);
		}
	}		

	public function decrypt_file($file_path, $pass) {
    	$decrypt_file_path = $file_path."_dec";
		$decode_cmd = sprintf(FILE_DECODER, $pass, $file_path, $decrypt_file_path);
    	system($decode_cmd);
		log_message('debug', 'decode cmd: '.$decode_cmd);
		if (file_exists($decrypt_file_path)) {
			unlink($file_path);
			rename($decrypt_file_path, $file_path);
			log_message('debug', 'decrypted file: '.$file_path);
		}
	}		

    protected function get_scaled_image_file_paths($file_path, $version) {
        $file_dir = dirname($file_path);
        if (!empty($version)) {
            $version_dir = $file_dir.'/'.$version;
            if (!is_dir($version_dir)) {
                mkdir($version_dir, $this->options['mkdir_mode'], true);
            }
            $new_file_path = $version_dir.'/'.basename($file_path);
        } else {
            $new_file_path = $file_path;
        }
		log_message('debug', $file_path."->".$new_file_path);
        return array($file_path, $new_file_path);
    }

    protected function gd_get_image_object($file_path, $func, $no_cache = false) {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->gd_destroy_image_object($file_path);
            $this->image_objects[$file_path] = $func($file_path);
        }
        return $this->image_objects[$file_path];
    }

    protected function gd_set_image_object($file_path, $image) {
        $this->gd_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function gd_destroy_image_object($file_path) {
        $image = (isset($this->image_objects[$file_path])) ? $this->image_objects[$file_path] : null ;
        return $image && imagedestroy($image);
    }

    protected function gd_imageflip($image, $mode) {
        if (function_exists('imageflip')) {
            return imageflip($image, $mode);
        }
        $new_width = $src_width = imagesx($image);
        $new_height = $src_height = imagesy($image);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $src_x = 0;
        $src_y = 0;
        switch ($mode) {
            case '1': // flip on the horizontal axis
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                break;
            case '2': // flip on the vertical axis
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            case '3': // flip on both axes
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            default:
                return $image;
        }
        imagecopyresampled(
            $new_img,
            $image,
            0,
            0,
            $src_x,
            $src_y,
            $new_width,
            $new_height,
            $src_width,
            $src_height
        );
        return $new_img;
    }

    protected function gd_orient_image($file_path, $src_img) {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
            case 2:
                $new_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                break;
            case 3:
                $new_img = imagerotate($src_img, 180, 0);
                break;
            case 4:
                $new_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                break;
            case 5:
                $tmp_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 6:
                $new_img = imagerotate($src_img, 270, 0);
                break;
            case 7:
                $tmp_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 8:
                $new_img = imagerotate($src_img, 90, 0);
                break;
            default:
                return false;
        }
        $this->gd_set_image_object($file_path, $new_img);
        return true;
    }

    protected function gd_create_scaled_image($file_name, $version, $options, $type) {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
//      $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $src_img = $this->gd_get_image_object(
            $file_path,
            $src_func,
            !empty($options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gd_orient_image(
                $file_path,
                $src_img
            )) {
            $image_oriented = true;
            $src_img = $this->gd_get_image_object(
                $file_path,
                $src_func
            );
        }
        // 最大値をチェック
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($options['max_width'])) {
            $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $max_height = $options['max_height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }
        $success = imagecopyresampled(
            $new_img,
            $src_img,
            $dst_x,
            $dst_y,
            0,
            0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        ) && $write_func($new_img, $new_file_path, $image_quality);
        $this->gd_set_image_object($file_path, $new_img);
        return $success;
    }

    protected function imagick_get_image_object($file_path, $no_cache = false) {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->imagick_destroy_image_object($file_path);
            $image = new \Imagick();
            if (!empty($this->options['imagick_resource_limits'])) {
                foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                    $image->setResourceLimit($type, $limit);
                }
            }
            $image->readImage($file_path);
            $this->image_objects[$file_path] = $image;
        }
        return $this->image_objects[$file_path];
    }

    protected function imagick_set_image_object($file_path, $image) {
        $this->imagick_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function imagick_destroy_image_object($file_path) {
        $image = (isset($this->image_objects[$file_path])) ? $this->image_objects[$file_path] : null ;
        return $image && $image->destroy();
    }

    protected function imagick_orient_image($image) {
        $orientation = $image->getImageOrientation();
        $background = new \ImagickPixel('none');
        switch ($orientation) {
            case \imagick::ORIENTATION_TOPRIGHT: // 2
                $image->flopImage(); // horizontal flop around y-axis
                break;
            case \imagick::ORIENTATION_BOTTOMRIGHT: // 3
                $image->rotateImage($background, 180);
                break;
            case \imagick::ORIENTATION_BOTTOMLEFT: // 4
                $image->flipImage(); // vertical flip around x-axis
                break;
            case \imagick::ORIENTATION_LEFTTOP: // 5
                $image->flopImage(); // horizontal flop around y-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_RIGHTTOP: // 6
                $image->rotateImage($background, 90);
                break;
            case \imagick::ORIENTATION_RIGHTBOTTOM: // 7
                $image->flipImage(); // vertical flip around x-axis
                $image->rotateImage($background, 270);
                break;
            case \imagick::ORIENTATION_LEFTBOTTOM: // 8
                $image->rotateImage($background, 270);
                break;
            default:
                return false;
        }
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); // 1
        return true;
    }

    protected function imagick_create_scaled_image($file_name, $version, $options, $type) {
        list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
        $image = $this->imagick_get_image_object(
            $file_path,
            !empty($options['no_cache'])
        );
        if ($image->getImageFormat() === 'GIF') {
            // Handle animated GIFs:
            $images = $image->coalesceImages();
            foreach ($images as $frame) {
                $image = $frame;
                $this->imagick_set_image_object($file_name, $image);
                break;
            }
        }
        $image_oriented = false;
        if (!empty($options['auto_orient'])) {
            $image_oriented = $this->imagick_orient_image($image);
        }
        $new_width = $max_width = $img_width = $image->getImageWidth();
        $new_height = $max_height = $img_height = $image->getImageHeight();
        if (!empty($options['max_width'])) {
            $new_width = $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $new_height = $max_height = $options['max_height'];
        }
//        if (!($image_oriented || $max_width < $img_width || $max_height < $img_height)) {
//            if ($file_path !== $new_file_path) {
//                return copy($file_path, $new_file_path);
//            }
//            return true;
//        }
        $crop = !empty($options['crop']);
        if ($crop) {
            $x = 0;
            $y = 0;
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = 0; // Enables proportional scaling based on max_height
                $x = ($img_width / ($img_height / $max_height) - $max_width) / 2;
            } else {
                $new_height = 0; // Enables proportional scaling based on max_width
                $y = ($img_height / ($img_width / $max_width) - $max_height) / 2;
            }
        }
        $success = $image->resizeImage(
            $new_width,
            $new_height,
            isset($options['filter']) ? $options['filter'] : \imagick::FILTER_LANCZOS,
            isset($options['blur']) ? $options['blur'] : 1,
            $new_width && $new_height // fit image into constraints if not to be cropped
        );
        if ($success && $crop) {
            $success = $image->cropImage(
                $max_width,
                $max_height,
                $x,
                $y
            );
            if ($success) {
                $success = $image->setImagePage($max_width, $max_height, 0, 0);
            }
        }
//        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                if (!empty($options['jpeg_quality'])) {
                    $image->setImageCompression(\imagick::COMPRESSION_JPEG);
                    $image->setImageCompressionQuality($options['jpeg_quality']);
                }
                break;
        }
        if (!empty($options['strip'])) {
            $image->stripImage();
        }
        return $success && $image->writeImage($new_file_path);
    }

    protected function imagemagick_create_scaled_image($file_name, $version, $options, $type, $with_convert_param = false) {
        list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
        $resize = @$options['max_width'].(empty($options['max_height']) ? '' : 'X'.$options['max_height']);
        if (!$resize && empty($options['auto_orient'])) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $cmd = $this->options['convert_bin'];
        if ($with_convert_param && !empty($this->options['convert_params'])) {
            $cmd .= ' '.$this->options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg($file_path);
        if (!empty($options['auto_orient'])) {
            $cmd .= ' -auto-orient';
        }
        if ($resize) {
            // Handle animated GIFs:
            $cmd .= ' -coalesce';
            if (empty($options['crop'])) {
                $cmd .= ' -resize '.escapeshellarg($resize.'>');
            } else {
                $cmd .= ' -resize '.escapeshellarg($resize.'^');
                $cmd .= ' -gravity center';
                $cmd .= ' -crop '.escapeshellarg($resize.'+0+0');
            }
            // Make sure the page dimensions are correct (fixes offsets of animated GIFs):
            $cmd .= ' +repage';
        }
        if (!empty($options['convert_params'])) {
            $cmd .= ' '.$options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg($new_file_path.'.'.$type);
		log_message('debug', 'resize cmd:'.$cmd);
        exec($cmd, $output, $error);
        if ($error) {
            error_log(implode('\n', $output));
            return false;
        }
		if (file_exists($new_file_path.'.'.$type)) {
			rename($new_file_path.'.'.$type, $new_file_path);
		}
        return true;
    }
    
    protected function ffmpeg_create_scaled_image($file_name, $version, $options, $type) {
    	list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_name, $version);
    
    	// 最大値をチェック
    	list($img_width, $img_height, $file_type) = video_imagexy($file_path);
    	$max_width = $img_width;
    	$max_height = $img_height;
    	if (!empty($options['max_width'])) {
    		$max_width = $options['max_width'];
    	}
    	if (!empty($options['max_height'])) {
    		$max_height = $options['max_height'];
    	}
    	$scale = min(
    			$max_width / $img_width,
    			$max_height / $img_height
    	);
    	log_message('debug', sprintf("size: (max) x|%s, y|%s; (img) x|%s, y|%s; scale %s", $max_width, $max_height, $img_width, $img_height, $scale));
    	if (empty($options['crop'])) {
    		$new_width = $img_width * $scale;
    		$new_height = $img_height * $scale;
    	} else {
    		if (($img_width / $img_height) >= ($max_width / $max_height)) {
    			$new_width = $img_width / ($img_height / $max_height);
    			$new_height = $max_height;
    		} else {
    			$new_width = $max_width;
    			$new_height = $img_height / ($img_width / $max_width);
    		}
    	}
    	$size = intval($new_width).'x'.intval($new_height);
		
		$duration = video_duration($file_path);
		if ($duration <= '00:00:05') {
			$start = 0;
		} else {
			$start = 5;	
		}
    
    	// コマンドを実行
    	if ($file_type == 'mjpeg') {
	    	$cmd = make_video_thumbnail_cmd($file_name, escapeshellarg($new_file_path.'.jpg'), null, null, $size);
		} else {
	    	$cmd = make_video_thumbnail_cmd($file_name, escapeshellarg($new_file_path.'.jpg'), $start, 1, $size);
		}
    	log_message('debug', 'resize cmd:'.$cmd);
//    	exec($cmd, $output, $error);
//    	if ($error) {
//    		error_log(implode('\n', $output));
//   		return false;
//    	}
		$result = execute_command($cmd);
    	if (file_exists($new_file_path.'.jpg')) {
    		rename($new_file_path.'.jpg', $new_file_path);
			log_message('debug', 'thumbnail done:'.$new_file_path);
    	}
    	return true;
    }

    protected function get_image_size($file_path) {
        if ($this->options['image_library']) {
            if ($this->options['image_library'] === 2) {
                $cmd = $this->options['identify_bin'];
                $cmd .= ' -ping '.escapeshellarg($file_path);
                $output = execute_command($cmd);
				log_message('debug', var_export($output, true));
                if (!empty($output) && preg_match('/\s(\d+\s*x\s*\d+)\s/', $output[0], $matches)) {
                	//  PNG image data, 640 x 960, 8-bit/color RGBA, non-interlaced
                    // image.jpg JPEG 1920x1080 1920x1080+0+0 8-bit sRGB 465KB 0.000u 0:00.000
                    $dimensions = preg_split('/\s*x\s*/', $matches[1]);
                    return $dimensions;
                }
                return false;
            }
            if (extension_loaded('imagick')) {
                $image = new \Imagick();
                try {
                    if (@$image->pingImage($file_path)) {
                        $dimensions = array($image->getImageWidth(), $image->getImageHeight());
                        $image->destroy();
                        return $dimensions;
                    }
                    return false;
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
        if (!function_exists('getimagesize')) {
            error_log('Function not found: getimagesize');
            return false;
        }
        return @getimagesize($file_path);
    }
    
    /**
     * サムネイル作成ロジック分岐
     */

    protected function create_scaled_image($file_name, $version, $options, $type, $with_convert_param = false) {
    	if (is_video($file_name, $type)) {
    		return $this->ffmpeg_create_scaled_image($file_name, $version, $options, $type);
    	}
    	if (empty($type)) {
    		$type = "jpg";
		}
        if ($this->options['image_library'] === 2) {
            return $this->imagemagick_create_scaled_image($file_name, $version, $options, $type, $with_convert_param);
        }
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagick_create_scaled_image($file_name, $version, $options, $type);
        }
        return $this->gd_create_scaled_image($file_name, $version, $options, $type);
    }

    protected function destroy_image_object($file_path) {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagick_destroy_image_object($file_path);
        }
    }

    protected function is_valid_image_file($file_path) {
// ファイル名を見てのチェックはしない。実体でチェック
//        if (!preg_match($this->options['image_file_types'], $file_path)) {
//            return false;
//        }
        if (function_exists('exif_imagetype')) {
        	$result = @exif_imagetype($file_path);
        	log_message('debug', 'exif_imagetype:'.var_export($result, true));
            return $result;
        }
        $image_info = $this->get_image_size($file_path);
        log_message('debug', 'image_info:'.var_export($image_info, true));
        return $image_info && $image_info[0] && $image_info[1];
    }

    protected function handle_image_file($file_path, $file, $with_convert_param = false) {
        $failed_versions = array();
        foreach($this->options['image_versions'] as $version => $options) {
        	log_message('debug', sprintf("HANDLE IMAGE: %s | %s", $version, $file_path));
        	// リサイズ処理
            if ($this->create_scaled_image($file_path, $version, $options, $file->ext, $with_convert_param)) {
			    list($file_path, $new_file_path) = $this->get_scaled_image_file_paths($file_path, $version);
	        	log_message('debug', 'Resize image: '.$new_file_path);
				if (!empty($version) && file_exists($new_file_path)) {
					$file->{$version} = $this->get_download_url(basename($file_path), $version);
					$file->{$version.'_path'} = $new_file_path;
					$file->{$version.'_size'} = $this->get_file_size($new_file_path); // レスポンス用レコード
					$file->file_info[$version.'_size'] = $file->{$version.'_size'}; // DB用レコード
					log_message('debug', $version.' making done.');
				}
            } else {
	        	log_message('debug', 'Original image');
                $failed_versions[] = $version ? $version : 'original';
            }
        }
        if (count($failed_versions)) {
            $file->error = $this->get_error_message('image_resize').' ('.implode($failed_versions,', ').')';
        }
        // Free memory:
        $this->destroy_image_object($file_path);
    }

	/////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * ファイルアップロード処理
	 * @param $uploaded_file アップロードファイル
	 * @param $name 
	 * @param $size
	 * @param $type 
	 * @param $error 
	 * @param $index
	 * @param $content_range
	 */
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
    	// 標準クラス
        $file = new \stdClass();
		// アップロードファイル名
		log_message("debug", sprintf("[UPLOAD FILE INFO]%s, %s, %s, %s, %s, %s, %s;", $uploaded_file, $name, $size, $type, $error, $index, $content_range));
		// オリジナルのファイル名
        $file->name = $name;
		$file->ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		$file->ext_type = get_file_ext_type($file->ext);
		
        $file->upload_name = $this->get_unique_random_file_name($uploaded_file, $name, $size, $type, $error, $index, $content_range);
		// ファイルサイズ
        $file->size = $this->fix_integer_overflow(intval($size));
		// MIME-TYPE
        $file->type = $type;
		$file->expired_type = 3;
		$file->expired_at = date("Y-m-d H:i:s", time() + 86400 * 3);
		$file->encryption_key = "";

		log_message("debug", sprintf("[FILE INFO]%s, %s, %s;", $file->name, $file->size, $file->type));

		// アップロードの内容を検査
        if ($this->validate($uploaded_file, $file, $error, $index)) {

			// アップロードパスを取得
            $upload_dir = $this->get_upload_path();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
			// 保存先ファイル名を取得
//			$file_path = $this->get_upload_path($file->name);
			$file_path = $this->get_upload_path($file->upload_name);
			
			// 追加アペンドか状態取得
			$append_file = $content_range && is_file($file_path) && $file->size > $this->get_file_size($file_path);
			log_message("debug", sprintf("get append status: (appned)%s, (content_range)%s", $append_file, $content_range));
				
			// アップロードファイルが上がった場合、内容を更新
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                	log_message("debug", "append uploaded file: ".$file_path);
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
		            $file_size = $this->get_file_size($file_path, $append_file);
                } else {
	                log_message("debug", "move uploaded file: ".$file_path);
                  	move_uploaded_file($uploaded_file, $file_path);
		            $file_size = $this->get_file_size($file_path, $append_file); // not encrypted
//  暗号化はしない
//					$file->encryption_key = md5(uniqid(mt_rand(), true));
//					$encode_cmd = sprintf(FILE_ENCODER, $file->encryption_key, $uploaded_file, $file_path);
//	    			system($encode_cmd);
//    	            $file_size = $this->get_file_size($uploaded_file, $append_file);
//					unlink($uploaded_file);
	            }
            } else {
	            log_message("debug", "put uploaded file: ".$file_path);
                // Non-multipart uploads (PUT method support)
                file_put_contents(
                    $file_path,
                    fopen('php://input', 'r'),
                    $append_file ? FILE_APPEND : 0
                );
	            $file_size = $this->get_file_size($file_path, $append_file);
            }
            
			$dao = new TmpUploadDao(MASTER);
            // ファイルサイズが適正ならアップロード成功
            if ($file_size === $file->size) {
				// アップロードファイルについて処理
				$user_id = $this->options['user']->user_id;	
				$file_recordset = $this->post_process_data($user_id, $dao, $file_path, $file, NULL, TYPE_BASE_FILE, false, false);
				// 削除用URLを設定
        		$this->set_additional_file_properties($file);
			// ファイルサイズが不正なら、アップロード失敗
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    unlink($file_path);
                    $file->error = $this->get_error_message('abort');
                }
	            $this->set_additional_file_properties($file);
            }
        }
		log_message('debug', "[FILE INFO END]".var_export($file, true));
		unset($file->file_info);
        return $file;
    }

	/**
	 * ファイルの読み込み
	 */
    protected function readfile($file_path) {
        $file_size = $this->get_file_size($file_path);
        $chunk_size = $this->options['readfile_chunk_size'];
        if ($chunk_size && $file_size > $chunk_size) {
            $handle = fopen($file_path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, $chunk_size);
                @ob_flush();
                @flush();
            }
            fclose($handle);
            return $file_size;
        }
        return readfile($file_path);
    }

    protected function body($str) {
        echo $str;
    }
    
    protected function header($str) {
        header($str);
    }

    protected function get_server_var($id) {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }

	/**
	 * コンテンツをレスポンスする
	 * @param $content JSON化する内容
	 * @param $print_response デフォルトでは、レスポンスを出力する
	 * @return $content そのもの
	 */
    protected function generate_response($content, $print_response = true) {
        if ($print_response) {
            $json = json_encode($content);
            $redirect = isset($_REQUEST['redirect']) ?
                stripslashes($_REQUEST['redirect']) : null;
            if ($redirect) {
                $this->header('Location: '.sprintf($redirect, rawurlencode($json)));
                return;
            }
            $this->head();
            if ($this->get_server_var('HTTP_CONTENT_RANGE')) {
                $files = isset($content[$this->options['param_name']]) ?
                    $content[$this->options['param_name']] : null;
                if ($files && is_array($files) && is_object($files[0]) && $files[0]->size) {
                    $this->header('Range: 0-'.(
                        $this->fix_integer_overflow(intval($files[0]->size)) - 1
                    ));
                }
            }
            $this->body($json);
        }
        return $content;
    }

    protected function get_version_param() {
        return isset($_GET['version']) ? basename(stripslashes($_GET['version'])) : null;
    }

    protected function get_singular_param_name() {
        return substr($this->options['param_name'], 0, -1);
    }

    protected function get_file_name_param() {
        $name = $this->get_singular_param_name();
        return isset($_REQUEST[$name]) ? basename(stripslashes($_REQUEST[$name])) : null;
    }

    protected function get_file_names_params() {
        $params = isset($_REQUEST[$this->options['param_name']]) ?
            $_REQUEST[$this->options['param_name']] : array();
        foreach ($params as $key => $value) {
            $params[$key] = basename(stripslashes($value));
        }
        return $params;
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
            case 'mp4':
            case 'm4v':
            case 'm4a':
                return 'application/mp4';
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
                return '';
        }
    }

	/**
	 * ダウンロード処理
	 */
    protected function download() {
        switch ($this->options['download_via_php']) {
            case 1:
                $redirect_header = null;
                break;
            case 2:
                $redirect_header = 'X-Sendfile';
                break;
            case 3:
                $redirect_header = 'X-Accel-Redirect';
                break;
            default:
                return $this->header('HTTP/1.1 403 Forbidden');
        }
        $file_name = $this->get_file_name_param();
        if (!$this->is_valid_file_object($file_name)) {
            return $this->header('HTTP/1.1 404 Not Found');
        }
        if ($redirect_header) {
            return $this->header(
                $redirect_header.': '.$this->get_download_url(
                    $file_name,
                    $this->get_version_param(),
                    true
                )
            );
        }
        $file_path = $this->get_upload_path($file_name, $this->get_version_param());
        // Prevent browsers from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if (!preg_match($this->options['inline_file_types'], $file_name)) {
            $this->header('Content-Type: application/octet-stream');
            $this->header('Content-Disposition: attachment; filename="'.$file_name.'"');
        } else {
            $this->header('Content-Type: '.$this->get_file_type($file_path));
            $this->header('Content-Disposition: inline; filename="'.$file_name.'"');
        }
        $this->header('Content-Length: '.$this->get_file_size($file_path));
        $this->header('Last-Modified: '.gmdate('D, d M Y H:i:s T', filemtime($file_path)));
        $this->readfile($file_path);
    }

    protected function send_content_type_header() {
        $this->header('Vary: Accept');
        if (strpos($this->get_server_var('HTTP_ACCEPT'), 'application/json') !== false) {
            $this->header('Content-type: application/json');
        } else {
            $this->header('Content-type: text/plain');
        }
    }

    protected function send_access_control_headers() {
        $this->header('Access-Control-Allow-Origin: '.$this->options['access_control_allow_origin']);
        $this->header('Access-Control-Allow-Credentials: '
            .($this->options['access_control_allow_credentials'] ? 'true' : 'false'));
        $this->header('Access-Control-Allow-Methods: '
            .implode(', ', $this->options['access_control_allow_methods']));
        $this->header('Access-Control-Allow-Headers: '
            .implode(', ', $this->options['access_control_allow_headers']));
    }

    public function head() {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
        $this->send_content_type_header();
    }

    public function get($print_response = true) {
        if ($print_response && isset($_GET['download'])) {
            return $this->download();
        }
        $file_name = $this->get_file_name_param();
        if ($file_name) {
            $response = array(
                $this->get_singular_param_name() => $this->get_file_object($file_name)
            );
        } else {
            $response = array(
                $this->options['param_name'] => $this->get_file_objects()
            );
        }
        return $this->generate_response($response, $print_response);
    }

	/**
	 * ポスト処理
	 */
    public function post($print_response = true) {
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            return $this->delete($print_response);
        }
        $upload = isset($_FILES[$this->options['param_name']]) ? $_FILES[$this->options['param_name']] : null;
			
        // Parse the Content-Disposition header, if available:
        $file_name = $this->get_server_var('HTTP_CONTENT_DISPOSITION') ?
            rawurldecode(preg_replace(
                '/(^[^"]+")|("$)/',
                '',
                $this->get_server_var('HTTP_CONTENT_DISPOSITION')
            )) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range = $this->get_server_var('HTTP_CONTENT_RANGE') ? preg_split('/[^0-9]+/', $this->get_server_var('HTTP_CONTENT_RANGE')) : null;
        $size =  $content_range ? $content_range[3] : null;
        $files = array();
		
		// 複数ファイルのアップロード
        if ($upload && is_array($upload['tmp_name'])) {
            // param_name is an array identifier like "files[]",
            // $_FILES is a multi-dimensional array:
            foreach ($upload['tmp_name'] as $index => $value) {
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'][$index],
                    $file_name ? $file_name : $upload['name'][$index],
                    $size ? $size : $upload['size'][$index],
                    $upload['type'][$index],
                    $upload['error'][$index],
                    $index,
                    $content_range
                );
            }
		// 単一ファイルのアップロード
        } else {
            // param_name is a single object identifier like "file",
            // $_FILES is a one-dimensional array:
            $files[] = $this->handle_file_upload(
                isset($upload['tmp_name']) ? $upload['tmp_name'] : null,
                $file_name ? $file_name : (isset($upload['name']) ? $upload['name'] : null),
                $size ? $size : (isset($upload['size']) ? $upload['size'] : $this->get_server_var('CONTENT_LENGTH')),
                isset($upload['type']) ? $upload['type'] : $this->get_server_var('CONTENT_TYPE'),
                isset($upload['error']) ? $upload['error'] : null,
                null,
                $content_range
            );
        }
        return $this->generate_response(
            array($this->options['param_name'] => $files),
            $print_response
        );
    }

	/**
	 * 削除処理
	 */
    public function delete($print_response = true) {
        $file_names = $this->get_file_names_params();
        if (empty($file_names)) {
            $file_names = array($this->get_file_name_param());
        }
        $response = array();
        foreach($file_names as $file_name) {
            $file_path = $this->get_upload_path($file_name);
            $success = is_file($file_path) && $file_name[0] !== '.' && unlink($file_path);
            if ($success) {
				$dao = new TmpUploadDao(MASTER);
				log_message('debug', sprintf("[DELETE FILE] %s; %s", $file_path, $file_name));
				$dao->delete_by_file_id($file_name);
				log_message('debug', $dao->check_last_query(false, true));
                foreach($this->options['image_versions'] as $version => $options) {
                    if (!empty($version)) {
                        $file = $this->get_upload_path(basename($file_name), $version);
                        if (is_file($file)) {
	                    	log_message('debug', '[DELETE THUMBNAIL FILE]'.$version.' file: '.$file);
                            unlink($file);
						}
                    }
                }
            }
            $response[$file_name] = $success;
        }
        return $this->generate_response($response, $print_response);
    }

	/**
	 * ファイルの解凍処理を行う
	 */
	public function unzip_file($destDto, $parent_id, $make_thumbnail = false, $mkdir_mode = 0777) {
		$file_path = array_selector('file_path', $destDto);
		$ext = array_selector('file_extension', $destDto);
		$pass = array_selector('encryption_key', $destDto);
		$user_id = array_selector('user_id', $destDto);
		$expired_type = array_selector('expired_type', $destDto);
		$expired_at = array_selector('expired_at', $destDto);
		$unzip_dir = dirname($file_path).'/unzipped/';
		
		// パスワードの指定があるときはデコードする
		if (!empty($pass)) {
			$work_dir = APPPATH.'tmp_files/batch/';
	    	$decode_file_path = $work_dir.basename($file_path)."_dec";
			// 事前処理でファイルがすでにあるときはそれを使う（バッチ処理限定）
			if (!file_exists($decode_file_path)) {
				make_dir($work_dir);
				$decode_cmd = sprintf(FILE_DECODER, $pass, $file_path, $decode_file_path);
	    		system($decode_cmd);
				log_message('debug', 'decode cmd: '.$decode_cmd);
			}
			$file_path = $decode_file_path;
		}
		
		// zip されていれば zip を解凍
		$zip = zip_open($file_path);
		if (is_resource($zip) && $ext === 'zip') {
			$dao = new UploadDao(MASTER);
			// アップロードパスを取得
			if (!is_dir($unzip_dir)) {
	        	mkdir($unzip_dir, $mkdir_mode, true);
			}
			// 保存先ファイル名を取得
			while( $entry = zip_read($zip) ) {
				//zip内のファイルをオープン
				zip_entry_open($zip, $entry, "r");
				//オープンしたファイルを読み込む
				$zip_file_name = zip_entry_name($entry);
				$zip_file_size = zip_entry_filesize($entry);
				if ($zip_file_size == 0) {
					continue;
				}
				if (preg_match('/__MACOSX/', $zip_file_name)) {
					continue;
				}
				// zip ファイルを保存
				$zip_file_id = $this->make_random_word();
				$zip_file_path = $unzip_dir.$zip_file_id;
				$entry_content = zip_entry_read($entry, $zip_file_size);
				$fp = fopen($zip_file_path,'w');
				fputs($fp, $entry_content);
				fclose($fp);
				log_message('debug', sprintf('zip entry:%s(%s) => %s', $zip_file_name, $zip_file_size, $zip_file_path));

				// DBに登録
		        $zipfile = new \stdClass();
				$zipfile->name = $zip_file_name;
				$zipfile->size = $zip_file_size;
				$zipfile->ext =  strtolower(pathinfo($zip_file_name, PATHINFO_EXTENSION));
				$zipfile->expired_type = $expired_type;
				$zipfile->expired_at = $expired_at;
				$zip_recordset = $this->post_process_data($user_id, $dao, $zip_file_path, $zipfile, $parent_id, TYPE_ARCHIVED_FILE, $make_thumbnail);				
			}
		}
		// パスワードの指定があるときはデコードファイルを削除
		if (!empty($pass)) {
			unlink($file_path);
			log_message('debug', 'unlink decoded file: '.$file_path);
		}
	}


	public function getOptions($key) {
		return array_key_exists($key, $this->options) ? $this->options[$key] : NULL;
	}
}
