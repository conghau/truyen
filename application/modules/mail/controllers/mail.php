<?php
/**
 * @name 設定のコントローラ
 * @copyright (C)201４ Sevenmedia Inc.
 * @author FJN
 *　@version 1.0
 */
class Mail extends MY_Controller{
	
	public function __construct() {
		parent::__construct();
		$this->data['controller'] = 'mail';
	}
	
	/**
	 * Process send mail
	 */
	public function index() {
		try {
			if(!$this->input->is_cli_request())
			{
				echo "This script can only be accessed via the command line" . PHP_EOL;
				return;
			}
			$maildao = new MailDao(MASTER);
			$results = $maildao->get_list();
			list($arr_mail, $arr_id) = $this->parse_from_query($results);
			
			if(count($arr_id) > 0) {
				// send mail
				foreach ($arr_mail as $mail){
					$to = array($mail['mail_to']);
					$from = $mail['mail_from'];
					$lang = isset($mail['language']) ? $mail['language'] : 'japanese';
					$this->data['subject'] = $mail['subject'];
					$this->data['content'] = $mail['content'];
					$this->data['link'] = $mail['link'];
					
					list($subject, $message) = $this->get_mail($lang.'/email_notice.tpl');
					$this->send_mail($to, $subject, $message ,$from);
				}
				//delete 
				$maildao->delete_mail($arr_id);
			}
		} catch (Exception $e) {
			log_message('error',$e->getMessage());
			show_error($e->getMessage());
		}
	}
	
	/**
	 * parse from query to array
	 */
	private function parse_from_query($results) {
		$arr_mail = array();
		$arr_id = array();
		foreach($results as $result){
			
			$mail['content'] 	= $result->content;
			$mail['subject'] 	= $result->subject;
			$mail['link'] 		= $result->link;
			$mail['mail_from']	= $result->mail_from;
			$mail['mail_to']	= $result->mail_to;
			$mail['language'] 	= $result->language;
			
			array_push($arr_mail, $mail);
			array_push($arr_id,$result->id);
		}
		return array($arr_mail, $arr_id);
	}
}
