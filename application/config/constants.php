<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('HTML_ENCODING', 'UTF-8');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0777);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('LIST_COUNT',		6);
define('ADMIN_LIST_COUNT',		20);
define('UPPATH', APPPATH.'tmp/uploads/');

define('MOBILE', 		1);
define('SMARTPHONE', 	2);

// for database.php
define('SLAVE', 	'slave');
define('MASTER', 	'default');
define('SURPRISE', 	'surprise');
define('ONEID_SLAVE', 	'oneid_slave');

define('DEFAULT_DB', 	MASTER);
define('CACHE_DB_TIME', 60);

//
define('COOKIE_SESSION' , 'sess');// フロントログイン用
define('COOKIE_ADMIN_SESSION' , 'ad_sess');// 管理ログイン用

// ページ配信種別
define('MAIL_OF_SECRETARIAT',serialize(array('hau-tc@fujinet.net','dat-tt@fujinet.net', 'nodat@sevenmedia.jp')));
define('MAIL_FROM', 'no-reply@qlife.jp');

define('NON_USER_PAGE'		,0);
define('USER_PAGE' 			,1);
define('ADMIN_USER_PAGE'	,2);

define('MAX_SEARCH_WORD_LENGTH', 64);
define('MAX_DAY_EXPIRED_ACTIVE_','+7 day');
define('DEFAULT_YEAR_CBX',1980);
define('MIN_YEAR_CBX',1900);
define('MIN_DATE','1900/01/01');
define('STATUS_ENABLE',1);
define('STATUS_DISABLE',2);
define('DEFAULT_PASSWORD','Abc12345');
define('UPLOAD_PATH_TSV', APPPATH.'uploads/');
define('UPLOAD_PATH', 'uploads/');
define('TMP_UPLOAD_PATH', 'tmp_uploads/');

// DICOM解析パーサーのパスを指定 @deprecated
//define('DICOM_PARSER', '/usr/local/qlifebox/bin/dicom_parser.pl');
// サーバー内のファイルをエンコードするコマンド
define('FILE_ENCODER', '/usr/bin/openssl enc -e -aes-128-cbc -pass pass:%s -in %s -out %s');
// サーバー内のファイルをデコードするコマンド
define('FILE_DECODER', '/usr/bin/openssl enc -d -aes-128-cbc -pass pass:%s -in %s -out %s');
// サーバー内のファイルをデコードするコマンド
define('FILE_DECODER_DIRECT', '/usr/bin/openssl enc -d -aes-128-cbc -pass pass:%s -in %s ');

if (ENVIRONMENT === 'production') {
	// アップロードファイルを公開するコマンド
	define('POST_UPLOAD_FILE_TRANSTER', 'nohup '.APPPATH.'../batch/post_upload_transfer.sh %s %s >/dev/null & '); // 出力があるとバックグラウンド処理にならないため、/dev/null
	// アップロードファイルを解凍し、サムネイルを作成するコマンド
	define('POST_FILE_PROCESSOR', 'nohup '.APPPATH.'../batch/post_process.sh %s >/dev/null & '); // 出力があるとバックグラウンド処理にならないため、/dev/null
} else {
	// アップロードファイルを公開するコマンド
	define('POST_UPLOAD_FILE_TRANSTER', 'nohup '.APPPATH.'../batch/post_upload_transfer_test.sh %s %s >/dev/null & '); // 出力があるとバックグラウンド処理にならないため、/dev/null
	// アップロードファイルを解凍し、サムネイルを作成するコマンド
	define('POST_FILE_PROCESSOR', 'nohup '.APPPATH.'../batch/post_process_test.sh %s >/dev/null & '); // 出力があるとバックグラウンド処理にならないため、/dev/null
}
//zip command line
define('CMD_ZIP_CODE', 'cd %s && zip -r %s ./%s');
// ダウンロードURL
define('DOWNLOAD_URL', '/file/download/');
define('PATH_TMP_FILE_ZIP',APPPATH.'tmp_zip/');

define('OUTPUT_FILE_TYPE_ZIP', '.zip');
define('OUTPUT_FILE_TYPE_CSV', 'CSV');
define('OUTPUT_FILE_TYPE_TSV', 'tsv');

//post
define('TYPE_THREAD', 1);			// スレッド
define('TYPE_COMMENT', 2);			// コメント

define('TYPE_AJAX','ajax');

define('TYPE_USER', 1);				// ユーザー
define('TYPE_GROUP', 2);			// グループ

define('TYPE_POST_USER', 1);		// 利用者
define('TYPE_POST_ANONYM', 0);		// 匿名

define('ROLE_SUPER_ADMIN', 1);		//管理者追加権限あり
define('ROLE_ADMIN', 2);			//管理者追加権限なし

define('TYPE_REGIST_USER_ADMIN', 1);	//管理登録
define('TYPE_REGIST_USER_RECOM', 2);	//推薦登録
define('TYPE_REGIST_USER_GENERAL', 3);	//一般登録

define('STATUS_REGIST_USER_PEND', 0);	//承認待ち
define('STATUS_REGIST_USER_ACTIVE', 1);	//承認・有効会員
define('STATUS_REGIST_USER_DENIAL', 2);	//否認・無効会員
define('STATUS_REGIST_USER_HOLD', 3);	//保留・無効会員

define('STATUS_NO_UPLOAD', 0);			//アップロードなし
define('STATUS_UPLOAD_PROCESSING', 1);	//アップロード処理中
define('STATUS_UPLOAD_PREPROCESS', 2);	//アップロード処理待ち
define('STATUS_UPLOAD_READY', 5);		//アップロード配信中

define('TYPE_UPLOAD_TASK', 1);			// アップロードタスク
define('TYPE_BACKGROUND_UPLOAD_TASK', 2); // バックグラウンドアプロードタスク
define('TASK_STANBY', 1);				// 処理待ち
define('TASK_PROCESSING', 2);			// 処理中
define('TASK_DONE', 3);					// 実行済み
define('TASK_ERROR', 9);				// エラー

define('TYPE_AUTH_METHOD_IMAGE', 1);	//画像
define('TYPE_AUTH_METHOD_PHONE', 2);	//電話確認

define('TYPE_BASE_FILE', 1);		//表に表示するファイル
define('TYPE_ARCHIVED_FILE', 2);	//アーカイブされていた解凍ファイル

define('EXPIRED_TYPE_INDEFINED', -1);
define('EXPIRED_TYPE_SPECIFY', 0);
define('EXPIRED_TYPE_1_YEAR', 365);
define('EXPIRED_TYPE_72_HOURS', 3);

define('STATUS_NOTICE_HIDDEN', 0);
define('STATUS_NOTICE_UNREAD', 1);
define('STATUS_NOTICE_READ', 2);

define('STATUS_GROUP_USER_ENABLE', 1);					//有効
define('STATUS_GROUP_USER_DISABLE', 2);					//無効
define('STATUS_GROUP_USER_PENDING_INVITATION', 3);		//被招待者受諾待ち
define('STATUS_GROUP_USER_OWNER_APPROVE', 4);			//オーナー承認待ち
define('STATUS_GROUP_PUBLIC', 1);					//公募
define('STATUS_GROUP_CLOSE', 2);					//クローズド
define('STATUS_GROUP_ENABLE', 1);					//有効


define('GROUP_USER_LEAVED', 3);

define('DOC', 'DOC');
define('DOCX', 'DOCX');
define('XLS', 'XLS');
define('XLSX', 'XLSX');
define('PPT', 'PPT');
define('PPTX', 'PPTX');
define('ZIP', 'ZIP');
define('JPG', 'JPG');
define('PNG', 'PNG');
define('BMP', 'BMP');
define('DICOM', 'DICOM');
define('MOV', 'MOV');
define('TXT', 'TXT');
define('RTF', 'RTF');

define('ADD_A_DAY','+1 day');
define('ADD_THREE_DAYS','+3 day');
define('ADD_A_YEAR','+1 year');

define('USER_SPACE_SIZE', 2147483648); //2GB
define('USER_SPACE_SIZE_UNIT', 1073741824); //1GB

define('MAX_USER_SEND', 50);
define('MAX_GROUP_SEND', 1);
define('MAX_USER_IN_GROUP', 100);
define('MAX_ERROR_SHOW', 100);

define('STATS_CATEGORY_THREAD', 3);
define('STATS_CATEGORY_FILE', 4);
define('STATS_SUMMARY_THREAD_VIEW_ALL', 1);
define('STATS_SUMMARY_THREAD_VIEW_DAILY', 2);
define('STATS_SUMMARY_THREAD_DL_ALL', 3);
define('STATS_SUMMARY_THREAD_DL_DAILY', 4);
define('STATS_SUMMARY_FILE_DL_ALL', 1);
define('STATS_SUMMARY_FILE_DL_DAILY', 2);

define('MODAL_HIDE_BUTTON_SEND', 1);
define('MODAL_HIDE_BUTTON_INVITE', 2);
define('MODAL_HIDE_SETTING', 3);

define('CONFIG_CATEGORY_NOTICE', 1);
define('CONFIG_CATEGORY_LANGUAGE', 2);

define('CONFIG_TYPE_RECEIVE_INVITE', 1);				//グループへの招待を受けた時
define('CONFIG_TYPE_USER_ACCEPT', 2);					//自分がグループオーナーで）メンバーがグループへの参加承認が認めた時
define('CONFIG_TYPE_GROUP_APPROVE', 3);					//自分がグループへの参加申請をして、承認された時
define('CONFIG_TYPE_THREAD_USER', 4);					//（グループでない自分あての）投稿があった場合
define('CONFIG_TYPE_COMMENT_USER', 5);					//（グループでない個人宛の）投稿にコメントがあった場合
define('CONFIG_TYPE_THREAD_GROUP', 6);					//参加しているグループの投稿があった場合
define('CONFIG_TYPE_COMMENT_GROUP_NOT_GROUP_OWNER', 7);	//（グループオーナーでない）参加しているグループの投稿にコメントがあった場合
define('CONFIG_TYPE_THREAD_GROUP_GROUP_OWNER', 8);		//「（自分がオーナーとなっている）グループ宛にコンサルト／カンファ」が発生した時
define('CONFIG_TYPE_COMMENT_GROUP_GROUP_OWNER', 9);		//（自分がグループオーナーで）グループの投稿にコメントがあった場合
define('CONFIG_TYPE_THREAD_NOT_READ_24', 10);			//２４時間以内に既読状態にならなかったコンサルト／カンファ、コメントがあった場合

define('ENCODE_SHIFT_JIS', 1);
define('ENCODE_UTF_8', 2);
define('DISPLAY_FILE_UPLOAD',5);

//crawler
define('STATUS_WAIT', 0);
define('STATUS_DONE', 1);



//* End of file constants.php */
/* Location: ./application/config/constants.php */