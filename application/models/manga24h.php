<?php
include_once(APPPATH.'/libraries/simple_html_dom.php');
/**
 * Created by PhpStorm.
 * User: hautruong
 * Date: 2/10/15
 * Time: 9:39 AM
 */
class Manga24h
{
    protected $baselink = 'http://manga24h.com/danhsach';
    protected $arrInfo;
    protected $intro;
    protected $title;
    protected $avatar;
    protected $arrChapter;
    private $totalManga;
    protected $arrManga = array();

    /**
     * @return array
     */
    public function getArrManga()
    {
        return $this->arrManga;
    }

    /**
     * @return string
     */
    public function getBaselink()
    {
        return $this->baselink;
    }

    /**
     * @return mixed
     */
    public function getArrInfo()
    {
        return $this->arrInfo;
    }

    /**
     * @return mixed
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return mixed
     */
    public function getArrChapter()
    {
        return $this->arrChapter;
    }

    public function __construct()
    {
        $this->getTotalManga();
    }
    public function getTotalManga()
    {
        $html = file_get_html($this->baselink);

        $total_number = $html->getElementById('ul.pagination > li')->childNodes(0)->outertext;
        $total_number = preg_replace('!\D+!','',$total_number);
        $this->totalManga =  ($total_number);
    }
    public function getListManga()
    {
        $arrManga = array();

        for($i = 1; $i < $this->totalManga; $i++) {
            $linkManga = $this->baselink . '/'.$i;
            $html = file_get_html($linkManga);

            foreach($html->find('div.col-md-8 > table > tr') as $element) {
                $manga = array();
                $manga['link'] = $element->first_child()->first_child()->first_child()->href;
                $arr = explode("/", $manga['link']);
                $manga['manga24h_id'] = $arr[3];
                array_push($arrManga, $manga);
            }
        }
        return $this->arrManga = $arrManga;
    }

    public function getMangaDetail($linkManga)
    {
        //try {
            $html = file_get_html($linkManga);
            if ($html == FALSE) {
                return FALSE;
            }
            //get info
            $arr_info = array();
            $targets = array(
                            'the-loai'=>'type'
                            ,'tac-gia-' => 'author'
                            ,'tinh-trang' => 'state'
                            ,'luot-xem' => 'view'
                            ,'nguon' => 'source'
                            ,'ngay-dang' => 'created_at'
                        );
            foreach ($html->find('div#truyen_thongtin > table > tr') as $element) {
                $key =  url_friendly(str_replace(':','',$element->childNodes(0)->plaintext));
                if (array_key_exists($key, $targets)) {
                    $key = $targets[$key];
                    $arr_info[$key] = $element->childNodes(1)->plaintext;
                } else {
                    $arr_info[$key] = $element->childNodes(1)->plaintext;
                }
                //echo $element->eq();
            }

            $this->arrInfo = $arr_info;
            //get introduce
            $arr_intro = '';
            foreach ($html->find('div#.noidung') as $element) {
                $arr_intro = trim($element->innertext);
            }
            $this->intro = $arr_intro;

            //get avatar
            foreach ($html->find('div.item_truyen_box > div.img > a > img') as $element) {
                $this->avatar = $element->src;
                //echo $element->eq();
            }
            //get title
            foreach ($html->find('div#wrap-rating > h3') as $element) {
                $this->title = $element->plaintext;
                //echo $element->eq();
            }

            //get list chapter
            //$this->arrChapter = $this->getListChapter($linkManga,$html);

            $arrData = array();
            $this->arrInfo['intro'] = $this->intro;
            $this->arrInfo['title'] = $this->title;
            $this->arrInfo['avatar'] = $this->avatar;

            $arrData['arrInfo'] = $this->arrInfo;

            //$arrData['arrChapter'] = $this->arrChapter;
            return $arrData;

        //} catch (Exception $e) {
        //    return null;
        //}

    }

    public function getListChapter($linkManga, $html = null)
    {
        if ($html == null) {
            $html = file_get_html($linkManga);
        }
        if(false == $html) {
            return FALSE;
        }
        $arrChapter = array();
		$i = 0 ;
        foreach($html->find('table.table_manga > tr') as $element) {
			if($i == 0) {
				$i++;
				continue;
			}
            $chapter = array();
            $chapter['name'] = trim($element->first_child()->first_child()->plaintext);
            $chapter['link'] = $element->first_child()->first_child()->href;
            $arr = explode("/", $chapter['link']);
            $chapter['chapter24h_id'] = $arr[3];
            array_push($arrChapter, $chapter);
        }
        return $arrChapter;
    }

    public function getChapterImage($linkChapter)
    {
       $arrChapterImage = array();
        if (! is_null($linkChapter)) {//.'<br />';
          $html = file_get_html($linkChapter);
            if($html == false) {
                return FALSE;
            }
          foreach ($html->find('img.img-responsive') as $element)
          {
              $a = array();
              $a['link'] = $element->src;
              array_push($arrChapterImage, $a);
          }
        }
        return $arrChapterImage;
}
}
