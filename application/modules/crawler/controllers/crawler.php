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


	public function UpdateDetailManga24h($linkChapter = ''){
		try {

			$objManga24h = new Manga24h();
			$objDao = new Manga24h_StoryDao(MASTER);
			$lst_manga = $objDao->get_list(0);
			foreach($lst_manga as $manga) {
				//var_dump($manga->link);;
				$r = $objManga24h->getMangaDetail($manga->link);
				echo 'l ';
				$recordset = array();
				if($r !== FALSE) {
					$recordset = $r['arrInfo'];
				}
				$recordset['status'] = STATUS_DONE;
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

	public function GetChapterManga24h($linkStory = ''){
		try {
			$objManga24h = new Manga24h();
			$objSDao = new Manga24h_StoryDao();
			$objDao = new Manga24h_ChapterDao(MASTER);
			$lst_manga = $objSDao->get_list_2(500);
			foreach($lst_manga as $manga) {
				var_dump($manga->link);
				$arrChapter = $objManga24h->getListChapter($manga->link);
				if($arrChapter !== FALSE) {
					$total = count($arrChapter);
					for($i =0; $i <$total; $i++) {
						$arrChapter[$i]['story_id'] = $manga->id;
						$arrChapter[$i]['manga24h_story_id'] = $manga->manga24h_id;
					}
					$r = $objDao->insert_bulk($arrChapter);
					if($r) {
						$objSDao->update_record($manga->id, array('status_2' => STATUS_DONE));
					}
				} else {
					$objSDao->update_record($manga->id, array('status_2' => STATUS_DONE));
				}
				echo 'done';
			}



			//$objManga24h = new Manga24h();
			//$b = $objManga24h->getMangaDetail('http://manga24h.com/4182/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke.html');

		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}

	public function GetImageManga24h($linkChapter = ''){
		try {
			$objManga24h = new Manga24h();
			$objCDao = new Manga24h_ChapterDao(MASTER);
			$objDao = new Manga24h_ImageDao(MASTER);

			//$arrImage = $objManga24h->getChapterImage('http://manga24h.com/93114/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke-chap-8/');
			//var_dump($arrImage);

			$lst_chapter = $objCDao->get_list(500);
			foreach($lst_chapter as $chapter) {
				var_dump($chapter->link);
				$arrImage = $objManga24h->getChapterImage($chapter->link);
				if($arrImage !== FALSE) {
					$total = count($arrImage);
					for($i =0; $i <$total; $i++) {
						$arrImage[$i]['chapter_id'] = $chapter->id;
					}
					$r = $objDao->insert_bulk($arrImage);
					if($r) {
						$objCDao->update_record($chapter->id, array('status' => STATUS_DONE));
					}
				} else {
					$objCDao->update_record($chapter->id, array('status' => STATUS_DONE));
				}
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
			$b = $objManga24h->getMangaDetail('http://manga24h.com/3931/Abnormal-kei-Joshi-Nhung-co-nang-ki-quai.html');
			//$c = $objManga24h->getChapterImage('http://manga24h.com/93114/Bungaku-Shoujo-to-Shi-ni-Tagari-no-Douke-chap-8/');
			var_dump($b);
			
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	
}
