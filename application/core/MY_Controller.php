<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


/* load the MX_Loader class */
require APPPATH."third_party/MX/Controller.php";

/**
 * @name 基底コントローラー
 * @copyright (C)2012 Sevenmedia Inc.
 * @author Takeo Noda
 * @version 1.0
 * @link http://www.sevenmedia.jp/
 */
class MY_Controller extends MX_Controller {

	var $data = array();

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		parent::__construct();
		$this->output->enable_profiler(TRUE);
		$config['queries']         = TRUE;
		// 言語
		$this->data['language'] = ($this->config->item('language') == '') ? 'vietnamese' : $this->config->item('language') ;

		// ===== ライブラリ群のロード =====
		$libraries = array('parser', 'device');
		
		$this->load->library($libraries);
		$helpers = array('cookie', 'language', 'sqlutil', 'htmlutil','arrayutil');
		$this->load->helper($helpers);
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));

		// ===== 共通データ =====
		// アプリケーションURL
		$this->data['apppath'] = APPPATH;
		// ベースURL
		$this->data['base_url'] = base_url();
		// ベースURL
		$this->data['ssl_base_url'] = str_replace("http:", "https:", base_url());
		// ROOT URL
		$this->data['root_url'] = config_item('root_url');
		// ROOT URL
		$this->data['ssl_root_url'] = config_item('ssl_root_url');
		// 現在の接続状態に合わせたベースURL
		$this->data['fixed_base_url'] =  (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS']) ? $this->data['ssl_base_url'] : $this->data['base_url'];
		$this->data['fixed_base_url'] .= '/wwwroot/';
		// デバイス情報をセット
		$this->data['device_info'] = $this->device;
		// 検索条件のデフォルトをセット（デザイン的に全般になるためダミーを設定）
		$this->data['keyword'] = "";
		// CSRF ハッシュをセット
		$csrf_hash = $this->security->get_csrf_hash();
        $this->data['csrf_hidden'] = $csrf_hash;
        $lang = $this->data['language'];
		// ユーザー情報
// 		try {
// 			$this->data['user'] = $this->userauth->getUser();
// 			$this->data['admin'] = $this->adminauth->getAdmin();
// 			$this->data['role_super_admin'] = ROLE_SUPER_ADMIN;

// 			$config =& get_config();
// 			if ($this->uri->segment(1) == 'admin_tools') {
// 				$lang = $config['language'];
// 			} else {
// 				$lang = array_selector('language', $this->data['user'], $config['language']);
// 			}
// 		} catch (Exception $e) {
// 			$config =& get_config();
// 			$lang = $config['language'];
// 		}

		$this->lang->load('error', $lang);
		$this->lang->load('msg_error', $lang);
		$this->lang->load('application', $lang);
		//===================================
// 		$this->setup_request_params(); // 互換を保つための対応
// 		if ($this->uri->segment(1) === 'admin_tools' || $this->uri->segment(1) === 'error') {
// 			return;
// 		}
// 		$this->parse_data_master();
// 		if ($this->uri->segment(1) == 'group' && ctype_digit($this->uri->segment(2)) 
// 				&& ($this->uri->segment(3) == '' || $this->uri->segment(3) == 'post' 
// 					|| $this->uri->segment(3) == 'edit' || $this->uri->segment(3) == 'confirm_edit')){
// 			$this->parse_data_master_group();
// 		} else {
// 			$this->parse_data_master_user();
// 		}
	}

	/**
     * フォーム情報のフォームデータ($this->data)への設定を行う。
     * フォームのselect/radio/checkboxで表示する項目や性別、血液型といった
     * システム共通で使う値を指定する。
	 *
	 * 【注意】
	 * 設定ファイルで指定されたキー名で $this->data に設定されます。
	 * 予約されたキーになりますので、それ以前の $this->data への設定でこれらの
	 * キー名を使わないようお願いします。
	 *
	 * (モジュール別設定)
     *   APPPATH/modules/(モジュール名)/config/forminfo.php (*)をコピーして配置
     *   APPPATH/modules/(モジュール名)/config/forminfo.yaml
     *
     * (共通設定)
	 *   APPPATH/config/forminfo.php (*) yamlと対となるファイル。yamlをこの中で読み込み。
     *   APPPATH/config/forminfo.yaml
     *
     * ■フォーム情報設定内容 yaml 書式
     * ----------------------------------------------------------
	 * (アクション名):
     *  (メソッド名):
     *   (設定キー名):
     *    (キー名): (値)
     *     :
     * ----------------------------------------------------------
     * ※パスの値が存在しない場合、アクション名、メソッド名で default で指定された
     *   値を階層的に検索して適用する。
     *
     * 検索順序:
     *  1. (対象アクション名)/(対象メソッド名)
     *  2. (対象アクション名)/default
     *  3. default/(対象メソッド名)
     *  4. default/default
     *
	 * @param $path 設定パス(アクション名/メソッド名で記述)。
     *
     *   例： contact/form
     *        diary/writer
     */
	public function setup_form_info($path) {
		$cache_path = get_class($this)."/".$path;
		$target_data = $this->cache->get($cache_path);
		if (!empty($target_data)) {
			$this->data = array_merge($this->data, $target_data);
			return;
		}
		
		// 設定値のロード
		$forminfo = $this->load->config('forminfo');

		// 検索対象パスのリストアップ
		list($target_action, $target_method) = explode('/', $path);
		$list_action = array($target_action, 'default');
		$list_method = array($target_method, 'default');

		// 設定値の検索
		$target_data = array();
		foreach ($list_action as $action) {
			foreach ($list_method as $method) {
				// 対象アクションのキーがなければ次へ
				if (!array_key_exists($action, $forminfo)) {
					continue;
				}
				// 対象メソッドのキーがなければ次へ
				$actioninfo = $forminfo[$action];
				if (!array_key_exists($method, $actioninfo)) {
					continue;
				}

				// 見つかった場合は、再帰的に配列の値を変換し、指定されているキーをフォームデータに値を設定。
				$info = $actioninfo[$method];
				foreach ($info as $key => $value) {
//var_dump($key ."=>".$value);
					$target_data[$key] = $value; // $this->array_parse($value);
				}
			}
		}
		$this->cache->save($cache_path, $target_data);
		$this->data = array_merge($this->data, $target_data);
	}


	/**
     * ページ情報のフォームデータ($this->data)への設定を行う。
     * ページ情報とは、主にHTMLのヘッダーで指定する title, keywords, description を
     * 想定しているが、それ以外のキーについてもpageinfo_additional_keys に指定を
     * 追加することで保持をすることが可能。
	 *
	 * 【注意】
	 * title, keywords, description, pageinfo_additional_keys で指定されたキー名が
	 * $this->data に設定されます。予約されたキーになりますので、それ以前の$this->data
	 * への設定でこれらのキー名を使わないようお願いします。
	 *
     * また、この関数は、$this->parser() に包含されており、$this->parser() の第２引数
	 * でパスを指定した場合は、重複して呼び出す必要はありません。
     *
	 * (モジュール別設定)
     *   APPPATH/modules/(モジュール名)/config/pageinfo.php (*)をコピーして配置
     *   APPPATH/modules/(モジュール名)/config/pageinfo.yaml
     *
     * (共通設定)
	 *   APPPATH/config/pageinfo.php (*) yamlと対となるファイル。yamlをこの中で読み込み。
     *   APPPATH/config/pageinfo.yaml
     *
     * ■ページ情報設定内容 yaml 書式
     * ----------------------------------------------------------
	 * (アクション名):
     *  (メソッド名):
     *   title: (HTMLタイトル)
     *   keywords: (HTMLキーワード)
     *   description: (HTML概要)
     *   (pageinfo_additional_keysで指定されたキー名:n): (キーの値)
     *
     * pageinfo_additional_keys:
     *  - (追加検索キー名1)
     *  - (追加検索キー名2)
     *  - :
     * ----------------------------------------------------------
     * ※パスの値が存在しない場合、アクション名、メソッド名で default で指定された
     *   値を階層的に検索して適用する。
     *
     * 検索順序:
     *  1. (対象アクション名)/(対象メソッド名)
     *  2. (対象アクション名)/default
     *  3. default/(対象メソッド名)
     *  4. default/default
     *
	 * @param $path 設定パス(アクション名/メソッド名で記述)。
     *
     *   例： contact/form
     *        diary/writer
     */
	public function setup_page_info($path) {
		// 設定値のロード
		$pageinfo = $this->load->config('pageinfo');

		// 検索対象パスのリストアップ
		list($target_action, $target_method) = explode('/', $path);
		$list_action = array($target_action, 'default');
		$list_method = array($target_method, 'default');

		// ページ情報基本キーの調整
		$target = array('title', 'keywords', 'description');

		// ページ情報キーの追加
		if (isset($pageinfo['pageinfo_additional_keys']) && is_array($pageinfo['pageinfo_additional_keys'])) {
			$target = array_merge($target, $pageinfo['pageinfo_additional_keys']);
		}

		// 設定値の検索
		foreach ($list_action as $action) {
			foreach ($list_method as $method) {
				// 対象アクションのキーがなければ次へ
				if (!is_array($pageinfo) || !array_key_exists($action, $pageinfo)) {
					continue;
				}
				// 対象メソッドのキーがなければ次へ
				$actioninfo = $pageinfo[$action];
				if (!is_array($actioninfo) || !array_key_exists($method, $actioninfo)) {
					continue;
				}

				// 見つかった場合は、ページ情報のキーをもとに設定内容をフォームデータに組み込み
				$info = $actioninfo[$method];
				// 次回検索候補のページ情報キー配列
				$tmp_target = array();
				foreach ($target as $key) {
					// 設定情報がなければ、次回検索を行うようリストに追加。
					if (!array_key_exists($key, $info)) {
						array_push($tmp_target, $key);
						continue;
					}
					// 設定情報があれば、ページ情報をフォームデータに組み込み（その際、Smartyテンプレートも適用し、動的に値を組み込めるようにする）
					$this->data[$key] = $this->parser->string_parse($info[$key], $this->data);
				}
				// すべてのページ情報キーの設定が終わったら、終了
				if (count($tmp_target) == 0) {
					return;
				}
				// ページ情報の対象が残っていれば、引き続き検索
				$target = $tmp_target;
			}
		}
		// 対象がなくなれば終わり
	}

	/**
     * 配列を再帰的に検査し、フォームデータと組み合わせた値で配列を組み直す
	 *
	 * @param $value 対象値
	 * @param $result 変換した配列
	 * @return フォームデータと組み合わせを行った配列
	 */
	protected function array_parse($value) {
		$new_result = array();
		// 対象値が連想配列の場合
		if (is_array($value) && is_hash($value)) {
			foreach ($value as $k => $v) {
				$new_result[$k] = $this->array_parse($v);
			}
		// 対象値が配列の場合
		} else if (is_array($value)) {
			foreach ($value as $v) {
				array_push($new_result, $this->array_parse($v));
			}
		// 対象値が値の場合
		} else {
			$new_result = $this->parser->string_parse($value, $this->data);
		}

		// 変換データ
		return $new_result;
	}

	/**
     * バリデーション情報のフレームワークへの設定を行う。
	 *
	 * (モジュール別設定)
     *   APPPATH/modules/(モジュール名)/config/formvalidations.php (*)をコピーして配置
     *   APPPATH/modules/(モジュール名)/config/formvalidations.yaml
     *
     * (共通設定)
	 *   APPPATH/config/formvalidations.php (*) yamlと対となるファイル。yamlをこの中で読み込み。
     *   APPPATH/config/formvalidations.yaml
     *
     * ■バリデーション情報設定内容 yaml 書式
     * ----------------------------------------------------------
	 * (アクション名):
     *  (メソッド名):
     *   - field: (検索キー名)
     *     label: (ラベル名)
     *     rules: (検査ルール)
     *   - field: (検索キー名)
     *     label: (ラベル名)
     *     rules: (検査ルール)
     *   :
     *
     * ----------------------------------------------------------
     * ※パスの値が存在しない場合は、検査しない。対応するルールはページごとに必ず記述。
     *
	 * @param $path 設定パス(アクション名/メソッド名で記述)。
     *
     *   例： contact/form
     *        diary/writer
     */
	public function setup_validation_rules($path) {
		// 設定値のロード
		$validations = $this->load->config('formvalidations');

		// 対象パスのルールがあれば、フレームワークの検査ルールに追加。
		if (is_array($validations) && array_key_exists($path, $validations)) {
			$list = $validations[$path];
			foreach ($list as $condition) {
				$this->form_validation->set_rules($condition['field'], lang($condition['label']), $condition['rules']);
			}
		}
	}

	/**
     * ページ情報の組み込みおよびテンプレートの表示を行う。
	 *
	 * (モジュール別テンプレート配置先)
     *   APPPATH/modules/(モジュール名)/view/(テンプレート名)
     *
     * (共通テンプレート配置先)
	 *   APPPATH/view/(テンプレート名)
	 *
	 * @param $target 設定パス(アクション名/メソッド名で記述)。
     */
	public function parse($target, $pageinfo = "", $return_string = FALSE) {
        // ページ情報を設定する
		if ($pageinfo !== "") {
			$this->setup_page_info($pageinfo);
		}
		// テンプレート情報の取得
		$template_name = $this->device->get_template_name($target);
		//　テンプレート表示
		$CI =& get_instance();
		$CI->output->set_header('Content-Type: text/html; charset='.HTML_ENCODING);
		$this->parser->html_parse($template_name, $this->data, $return_string, TRUE, HTML_ENCODING);
	}

    /**
     * フォームボタンがクリックされたかチェック
     * @param $name ボタンフォーム名
     * @return クリックされた場合 true、そうでない場合 false。
     */
    public function is_clicked($name, $array = array()) {
		$array = $_REQUEST;
		return array_key_exists($name, $array) && !empty($array[$name]);
    }
	
	public function is_logged_in() {
		return !empty($this->data['member']['member_uk']);
	}

	protected function setup_form_parameter($button_name, $keys = array()) {
		if (count($keys) === 0 && array_key_exists("request_keys", $this->data)) {
			$keys = $this->data["request_keys"];
		}
		foreach ($keys as $target => $value) {
			if ($this->input->post($target)) {
				$this->data[$target] = $this->input->post($target); // ボタン押下時（値あり）
			} else if (isset($button_name) && $this->input->post($button_name)) {
				$this->data[$target] = ""; // ボタン押下時（値なし）
			} else {
				$this->data[$target] = $value; // 初回時
			}
		}
	}

	protected function setup_hidden_parameter($button_name, $hidden_keys = array()) {
		if (isset($button_name) && count($hidden_keys) === 0 && array_key_exists($button_name."_hidden_keys", $this->data)) {
			$hidden_keys = $this->data[$button_name."_hidden_keys"];
		}
		foreach ($hidden_keys as $target) {
			if (array_key_exists($target, $this->data)) {
				$this->data['hidden_keys'][$target] = $this->data[$target];
			}
		}
	}

	protected function get_device_type() {
		$device_type = MOBILE;
		if($this->device->is_smartphone()){	
			$device_type = SMARTPHONE;
		}
		return $device_type;
	}
	
	protected function setup_request_params() {
		if (!$this->input->get()) {
			return;
		}
		foreach ($this->input->get() as $key => $value) {
			$_REQUEST[$key] = $value;
		}
	}
	
	/***
	 * メールタイトルを取得する便利メソッド
	* $message １行目にタイトル、２行目以下が本文のメールテンプレート
	*/
	public function get_mail_subject($message) {
		$list = preg_split("/(\r\n|\r|\n)/", $message);
		return array_shift($list);
	}
	
	/***
	 * メール本文を取得する便利メソッド
	* $message １行目にタイトル、２行目以下が本文のメールテンプレート
	*/
	public function get_mail_body($message) {
		$list = preg_split("/(\r\n|\r|\n)/", $message);
		array_shift($list);
		return implode("\n", $list);
	}
	
	/***
	 * まとめてメール関連の情報を整理する便利メソッド
	*/
	public function get_mail($template_name) {
		$message = $this->parser->mail_parse($template_name, $this->data);
		$subject = $this->get_mail_subject($message);
		$message = $this->get_mail_body($message);
		return array($subject, $message);
	}
	
	protected function send_mail($target = array(), $subject, $message, $from, $html = true) {
		mb_language("ja");
		mb_internal_encoding("utf8");
	
		if (is_array($from)) {
			$header = "From: ".implode(",", $from)."\n";
		} else {
			$header = "From: ".$from."\n";
		}
		// 文字コードはJISに変換
		if ($html) {
			$header .= 'Content-type: text/html; charset=ISO-2022-JP';
		} else {
			$header .= 'Content-type: text/plain; charset=ISO-2022-JP';
		}
//		$subject = mb_convert_encoding($subject, "iso-2022-jp", "utf-8");
		$subject = mb_encode_mimeheader($subject, 'ISO-2022-JP-MS');
		$message = mb_convert_encoding($message, "iso-2022-jp", "utf-8");
		
		// 送信対象にメール
		$debug_backtrace = debug_backtrace();
		$debug = array_shift($debug_backtrace);
		$debuginfo = $debug['file'].":".$debug['line'];
		foreach ($target as $mail_address) {
			mail($mail_address, $subject, $message, $header);
			log_message('mail', sprintf("%s\t%s\t%s", $mail_address, $subject, $debuginfo));
		}
	}
	
		// Cookie と POST 値の検証で CSRF 対策を行う。	
	public function setup_csrf() {
		$csrf_token_name = $this->security->get_csrf_token_name();
		$csrf_hash = $this->security->get_csrf_hash();
        $this->data['csrf_hidden'] = $csrf_hash;
//		$this->security->csrf_set_cookie();
		
		log_message('debug', sprintf("CSRF token %s => %s", $csrf_token_name, $csrf_hash));
	}
	
	public function clear_csrf() {
		$csrf_token_name = $this->security->get_csrf_token_name();
		$cookie = array(
			'name' => $csrf_token_name,
			'value' => '',
			'path' => '/',
			'expire' => -1
			);
		set_cookie($cookie);		
		log_message('debug', 'CSRF clear');
	}
	
	public function verify_csrf() {
		$this->security->csrf_verify();
		log_message('debug', 'CSRF valid');
	}

	/**
	 * 管理ツール向け改ページメソッド
	 * @param object $model
	 * @param array $condition
	 * @param string $url
	 * @return array
	 */
	public function create_pagination_admin($total_records, $condition, $url, $uri_segment, $conf_key = 'pagination_admin') {
		return $this->create_pagination($total_records, $condition, $url, $uri_segment, $conf_key);
	}
	
	/**
	 * 改ページメソッド
	 * @param object $model
	 * @param array $condition
	 * @param string $url
	 * @return array
	 */
	public function create_pagination($total_records, $condition, $url, $uri_segment, $conf_key = 'pagination') {
		$this->data['total_records'] = $total_records;
		// pagination.phpファイルからページング情報を取得する。
		$this->load->config('pagination');
		
		$config['base_url'] = $url;
		$config['total_rows'] = $total_records;
		$config["uri_segment"] = $uri_segment;

		$paginateConf = $this->config->item($conf_key);
	
		foreach ($paginateConf as $key => $value) {
			$config[$key] = $value;
		}
		
		$this->data['pagination_per_page'] = $config['per_page'];
		$forminfo = config_item('forminfo');
		$this->data['pagination_total_option'] = $forminfo['common']['pagination']['total_item'];
		$this->data['pagination_per_page_option'] = $forminfo['common']['pagination']['per_page_list'];
		$this->data['format_tsv'] = $forminfo['common']['format_export_tsv'];
		
		if (TRUE == $condition['per_page']) {
			$config['per_page'] = $condition['per_page'];
		}
		$this->data['per_page'] = $config['per_page'];
	
		$this->pagination->initialize($config);
	
		// ページングリンク作成
		$this->data['links'] = $this->customize_links($this->pagination->create_links(), $config, $total_records);
	
		// 現在ページに表示開始するレコード位置を指定する。
		$start = $this->pagination->cur_page <= 1 ? 0 : ($this->pagination->cur_page -1 ) * $config['per_page'];
	
		$limit = array($config['per_page'], $start);
		
		return $limit;
	}
	
	/**
	 * 改ページリンクカスタムメソッド
	 * @param string $links
	 * @param array $config
	 * @param long $sum
	 * @return string
	 */
	public function customize_links($links, $config, $sum) {
		$total_pages = ceil($sum/$config['per_page']);
		// リンク一覧を配列に切り替える。
		$linkTemp = str_replace($config['num_tag_open'], ",", $links);
		$linkTemp = str_replace($config['num_tag_close'], ",", $linkTemp);
		$linkTemp = str_replace(",,", ",", $linkTemp);
		$linkTemp = str_replace($config['full_tag_open'], ",", $linkTemp);
		$linkTemp = str_replace($config['full_tag_close'], ",", $linkTemp);
		$arrlinks = explode(",", $linkTemp);
		$arrlinksTemp = array();
		$curentPage = 0;
	
		// first_page、last_page、next_page、previous_pageの各リンクの削除
		$lenArrlinks = count($arrlinks);
		for ($i = 1; $i < $lenArrlinks - 1; $i++) {
			if ($arrlinks[$i] === "" ||
					(strpos($arrlinks[$i], $config['next_link']) !== false
							|| strpos($arrlinks[$i], $config['prev_link']) !== false)) {
				continue;
			}
			array_push($arrlinksTemp, $arrlinks[$i]);
			if ( strpos($arrlinksTemp[count($arrlinksTemp)-1], $config['cur_tag_open']) === 0) {
				$curentPage = count($arrlinksTemp)-1;
			}
		}
	
		// 各表示リンクの開始位置・終了位置の選択
		$num_page = ceil($config['num_links'] / 2);
		$startPage = 0;
		$totalArrlinksTemp = count($arrlinksTemp);
		$endPage = $totalArrlinksTemp;
		if ($endPage > $config['num_links']) {
			if ($endPage - $curentPage > $curentPage) {
				if ($curentPage + 1 > $num_page) {
					$startPage = $curentPage - $num_page + 1;
					$endPage = $startPage  + $config['num_links'];
				} else {
					$endPage = $config['num_links'];
				}
			} else {
				if ($endPage - $curentPage > $num_page) {
					$endPage = $curentPage + $num_page;
					$startPage = $endPage - $config['num_links'];
				} else {
					$startPage = $endPage - $config['num_links'];
				}
			}
		}
			
		$arrlinksPage = array();
		// first_pageeの各リンクの作成
		if ($startPage > 0) {
			$firstLink = "<a href='" . $config['base_url'] . "'>" . $config['first_link'] . "</a>";
			array_push($arrlinksPage, $config['num_tag_open'] . $firstLink);
		}
	
		// 選択されたリンクを配列から文字列に変換する
		for ($i=$startPage; $i<$endPage; $i++) {
			$numTagOpen = $i!=$curentPage ? $config['num_tag_open']:"";
			array_push($arrlinksPage, $numTagOpen.$arrlinksTemp[$i]);
		}
	
		// last_pageの各リンクの作成
		if ($endPage < $totalArrlinksTemp) {
			$lastLink = "<a href='" . $config['base_url'] . "/" . (($total_pages - 1) * $config['per_page']) . "'>" . $config['last_link'] . "</a>";
			array_push($arrlinksPage, $config['num_tag_open'] . $lastLink);
		}
		return $config['full_tag_open'] . implode($config['num_tag_close'], $arrlinksPage) . $config['full_tag_close'];
	}
	
	/**
	 * データのエクスポート処理
	 * @param object $result_query
	 * @param string $output_file_name
	 */
	public function process_export($datas, $output_file_name, $list_column_table, $label, $encoding = ENCODE_SHIFT_JIS) {
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$tmp_objPHPExcel = $objPHPExcel->getActiveSheet();
	
		$row = 1;
	
		for ($column = 0 ; $column < count($list_column_table) ; $column ++) {
			$tmp_objPHPExcel->setCellValueExplicitByColumnAndRow($column , $row
					,$this->lang->line($label.$list_column_table[$column]));
		}
	
		$row = 2;
		foreach ($datas as $key => $item) {
			$objPHPExcel->getActiveSheet();
			for ($column = 0 ; $column < count($list_column_table) ; $column++) {
				if (is_array($datas)) {
					$tmp_objPHPExcel->setCellValueExplicitByColumnAndRow($column,$row
							, $item[$list_column_table[$column]]);
				} else {
					if ($list_column_table[$column] == 'password') {
						$tmp_objPHPExcel->setCellValueExplicitByColumnAndRow($column, $row, '');
					} else {
						$tmp_objPHPExcel->setCellValueExplicitByColumnAndRow($column, $row
							, $item->$list_column_table[$column]);
					}
				}
			}
			$row++;
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,OUTPUT_FILE_TYPE_CSV);
		$objWriter->setDelimiter("\t");
		$objWriter->setSheetIndex(0);
	
		$output_file_name = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0) ? $output_file_name : urlencode($output_file_name);
		
		header('Content-Encoding: UTF-8');
		header('Content-Type: text/plain; charset=UTF-8');
		header('Content-Disposition: attachment;filename="'.$output_file_name);
		header('Cache-Control: max-age=0');
		
		if ($encoding == ENCODE_UTF_8) {
			echo "\xEF\xBB\xBF";
		} else {
			mb_http_output('SJIS');
			ob_start('mb_output_handler');
		}
		
		$objWriter->save('php://output');
	}
	
	/**
	 * Parse data for general master layout
	 */
	public function parse_data_master(){
		$user = $this->userauth->getUser();
		if (isset($user)) {
			$noticedao = new NoticeDao();
			$notice_num = $noticedao->count_unread_notice_by_user($user->id);
			$this->data['master_notice_num'] = $notice_num;
			
			// ユーザーごとのファイルサイズ上限は指定できないか？
			$uploaddao = new UploadDao();
			$this->data['user_size'] = $user->max_file_size / USER_SPACE_SIZE_UNIT;
			$this->data['user_used'] = $user->file_size / USER_SPACE_SIZE_UNIT;
//			$this->data['user_size'] = USER_SPACE_SIZE / USER_SPACE_SIZE_UNIT;
//			$this->data['user_used'] = $uploaddao->sum_size_by_user($user->id) / USER_SPACE_SIZE_UNIT;
		}
	}
	
	/**
	 * Parse data for user's master layout
	 */
	public function parse_data_master_user(){
		$user = $this->userauth->getUser();
		if (isset($user)) {
			$this->data['master_user_name'] = user_name($user, $user->language);
			$this->data['master_user_department'] = $user->organization.'　'.$user->position;
			$this->data['master_user_id'] = $user->id;

			$qualification = new QualificationDao();
			$category_id = $qualification->get_by_id($user->qualification_id)->category_id;
			$this->data['master_category_id'] = $category_id;
			$groupdao = new GroupDao();
			$group_joined = $groupdao->get_group_by_user_id($user->id)->all;
			$groupdao2 = new GroupDao();
			$group_can_join = $groupdao2->get_group_can_join($user->id)->all;
			$this->data['master_group_joined']	= $group_joined;
			$this->data['master_group_can_join'] = $group_can_join;
			
			$this->data['msg_confirm_logout'] = $this->lang->line('L-F-0058-Q');
		}
	}
	
	/**
	 * Parse data for group's master layout
	 */
	public function parse_data_master_group(){
		$user = $this->userauth->getUser();
		if (isset($user)) {
			$group_id = $this->uri->segment(2);
			
			$groupdao = new GroupDao();
			$group_obj = $groupdao->get_group_by_id($group_id);
			
			$blacklist_user = new Blacklist_UserDao();
			$array_blacklist_user = $blacklist_user->get_user_id_by_target($user->id);
			$array_blacklist_user = $this->parse_arr_blacklist_user($array_blacklist_user);
			
			if ($group_obj->result_count() > 0 && $group_obj->status == STATUS_GROUP_ENABLE) {
				$this->data['msg_confirm_unsubscribe'] = $this->lang->line('L-F-0056-Q');
				$this->data['master_group_name'] = $group_obj->name;
				$this->data['master_group_summary'] = $group_obj->summary;
				$this->data['master_group_id'] = $group_id;
				$this->data['master_group_owned'] = ($group_obj->user_id == $user->id);

				$userdao = new UserDao();
				$group_owner_obj = $userdao->get_user($group_obj->user_id);
				$in_array = in_array($group_owner_obj->id, $array_blacklist_user);
				if ($in_array == FALSE || $this->data['master_group_owned'] == TRUE) {
					$group_owner = array();
					$group_owner['id'] = $group_owner_obj->id;
					$group_owner['name'] = user_name($group_owner_obj, $user->language);
					$group_owner['organization'] = $group_owner_obj->organization;
					$group_owner['position'] = $group_owner_obj->position;
					$group_owner['category_id'] = $group_owner_obj->category_id;
					$group_owner['status'] = STATUS_GROUP_USER_ENABLE;
					$userdao->clear();
					
					$this->data['master_group_owner'] = $group_owner;
				}
				$group_members = array();
				$group_members_invite = array();
				$groupuserdao = new GroupUserDao();
				$group_users = $groupuserdao->get_member_in_group($group_id, 3);
				
				foreach($group_users as $group_user){
					$in_array = in_array($group_user->user_id, $array_blacklist_user);
					if ($in_array == FALSE || $this->data['master_group_owned'] == TRUE) {
						$member = array();
						$member['id'] = $group_user->user_id;
						$member['name'] = user_name($group_user, $user->language);
						$member['organization'] = $group_user->organization;
						$member['position'] = $group_user->position;
						$member['category_id'] = $group_user->category_id;
						$member['status'] = $group_user->status ;
						if ($group_user->status == STATUS_GROUP_USER_ENABLE ||
								$group_user->status == STATUS_GROUP_USER_OWNER_APPROVE) {
							$group_members[] = $member;
						} else if($group_user->status == STATUS_GROUP_USER_PENDING_INVITATION) {
							$group_members_invite[] = $member;
						}
						if (($group_user->user_id == $user->id) &&
								($group_user->status == STATUS_GROUP_USER_ENABLE)
								){
							$this->data['master_group_joined'] = true;
						}
					}
				}
				if(!isset($this->data['master_group_joined'])){
					$this->data['master_group_joined'] = false;
				}
				$this->data['master_group_member'] = $group_members;
				$this->data['master_group_member_invite'] = $group_members_invite;
			}
		}
	}

	public function parse_arr_blacklist_user($object) {
		$black_user_id = array();
		foreach ($object as $obj) {
			array_push($black_user_id,$obj->user_id);
		}
		return $black_user_id;
	}
	
	/**
	 * ユーザーのファイル情報を更新
	 */
	protected function update_user_file_size($uploaddao = null, $userdao = null, $user_id = null) {
		if ($uploaddao === null) {
			$uploaddao = new UploadDao();
		}
		if ($userdao === null) {
			$userdao = new UserDao(MASTER);
		}
		if ($user_id === null && $this->data['user'] !== null) {
			$user_id = array_selector('user', $this->data, null, 'id');	
		}
		if ($user_id === null) {
			log_message('error', 'Wrong state to update user file size.');
			return;	
		}
		$file_size = $uploaddao->sum_size_by_user($user_id);
		$file_size = ($file_size > 0) ? $file_size : 0;
		$userdao->update_file_size($user_id, $file_size);
		log_message('debug', '[UPDATE USER FILE SIZE] User ID: '.$user_id.'; File Size: '.$file_size);
		$userdao->id = null;
		$userdao->clear();
		return $file_size;
	}

	// 保持しているキャッシュ一覧
	// プレビュー一覧: 	sprintf("user/%s/preview/%s", $user->id, $post_id);
	// お知らせ一覧:	sprintf("user/%s/notice/%s/%s", $user->id, $offset, $limit);
	/**
	 * キャッシュをロードする
	 */
	protected function load_cache($cache_path) {
		// 対象パスのディレクトリがなければ作成
		$cache_dir = APPPATH.'cache/'.dirname($cache_path);
		log_message('debug', $cache_dir);
		if (!file_exists($cache_dir)) {
			log_message('debug', 'make cache dir: '.mkdir($cache_dir, 0777, true));
		}
		$results = $this->cache->get($cache_path);
		return $results;
	}
	
	/**
	 * キャッシュを保存する
	 */
	protected function save_cache($cache_path, $data, $ttl = 60) {
		return $this->cache->save($cache_path, $data, $ttl);
	}

	/**
	 * キャッシュを削除する
	 */
	protected function delete_cache($cache_path) {
		return $this->cache->delete($cache_path);
	}

}