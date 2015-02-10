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
        try {
            $html = file_get_html($linkManga);
            //get info
            $arr_info = array();
            foreach ($html->find('div#truyen_thongtin > table > tr') as $element) {
                $arr_info[$element->childNodes(0)->plaintext] = $element->childNodes(1)->plaintext;
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
            $this->arrChapter = $this->getListChapter($linkManga,$html);

            $arrData = array();
            $arrData['arrInfo'] = $this->arrInfo;
            $arrData['arrChapter'] = $this->arrChapter;
            $arrData['intro'] = $this->intro;
            $arrData['title'] = $this->title;
            $arrData['avatar'] = $this->avatar;
            return $arrData;

        } catch (Exception $e) {
            var_dump($e);
        }

    }

    public function getListChapter($linkManga, $html = null)
    {
        if ($html == null) {
            $html = file_get_html($linkManga);
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
            array_push($arrChapter, $chapter);
        }
        return $arrChapter;
    }

    public function getChapterImage($linkChapter)
    {
       $arrChapterImage = array();
        if (! is_null($linkChapter)) {//.'<br />';
          $html = file_get_html($linkChapter);
          foreach ($html->find('img.img-responsive') as $element)
          {
              array_push($arrChapterImage, $element->src);
          }
        }
        return $arrChapterImage;
}
}
