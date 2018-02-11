<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commons extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('Common_lib');			
	}
	public function general_email($name,$send_eamil,$traing_name,$instituate){
			$message ="";
			$message .= "<b>Expiry Date Notification <b>".'<br><br>';
			$message .= "Dear ".$name.' Your Training certificate will be expire today.<br>';
			$message .= "Please login here :<a href='".base_url()."'>'".base_url()."'</a>".'<br>';
			$this->sendmail($send_eamil,'Expiry Date Notification', $message);
	}
	public function sendmail($to, $subject, $message){
		$this->email->set_mailtype('html');
		$this->email->from('Sender', 'iasimriaz@gmail.com');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);
		$status = $this->email->send();
		return $status;
	}
	
}
