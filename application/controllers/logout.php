<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class logout extends CI_Controller {

	function __construct()
	{
		parent::__construct();			
	}
	public function index()
	{
		//echo base_url().'login';exit;	
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('usrID');
		redirect(base_url().'login');
	}
}
