<?php
/**
 * @name 設定のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Crawler extends MY_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->data['controller'] = 'crawler';
	}

	public function GetStoryManga24h(){
		try {
			$objManga24h = new Manga24h();
			$a =  $objManga24h->getListManga();
			$objDao = new Manga24h_StoryDao(MASTER);
			$r = $objDao->insert_bulk($a);

		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}


	public function GetChapterManga24h($linkChapter = ''){
		try {

			$objManga24h = new Manga24h();
			$objDao = new Manga24h_StoryDao(MASTER);
			$lst_manga = $objDao->get_list(100);
			foreach($lst_manga as $manga) {
				//var_dump($manga->link);;
				$r = $objManga24h->getMangaDetail($manga->link);
				echo 'l ';
				//var_dump($r);
				$recordset = array();
				$recordset = $r['arrInfo'];
				$recordset['status'] = STATUS_DONE;
				var_dump($recordset);
				$objDao->update_record($manga->id, $recordset);
				echo 'done';
			}



			//$objManga24h = new Manga24h();
			//$b = $objManga24h->getMangaDetail('http://manga24h.com/4182/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke.html');

		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}



	public function index() {
		try {
// 			if(!$this->input->is_cli_request())
// 			{
// 				echo "This script can only be accessed via the command line" . PHP_EOL;
// 				return;
// 			}
			$objManga24h = new Manga24h();
			//$a =  $objManga24h->getListManga();
			//var_dump($a);
			//$objDao = new Manga24h_StoryDao(MASTER);
			//$r = $objDao->insert_bulk($a);
			$b = $objManga24h->getMangaDetail('http://manga24h.com/4182/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke.html');
			//$c = $objManga24h->getChapterImage('http://manga24h.com/93114/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke-chap-8/');
			var_dump($b);
			
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	
}
