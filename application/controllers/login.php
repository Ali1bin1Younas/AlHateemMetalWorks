<?php
defined('BASEPATH') OR exit('No direct script access allowed');

 class Login extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->library('Login_lib');
		$this->load->model('Login_model');
		
		if($this->session->userdata('logged_in') && $this->session->userdata('usrTypID')==0){	
			redirect(base_url().'Dashboard/');
		}
	}
	public function index(){
		$this->load->view('Login');
	}
	
	public function change_pass(){
		$this->load->view('Change_pass');
	}
	public function check_username($user_name){
		//echo $user_name."<br><br><br>";
		$validate = preg_match('/[A-Z]/', $user_name);//exit;
		
		if($validate){
			 $this->form_validation->set_message('check_username', 'User Name must have Lowercase.');
			return FALSE;
		}else
		{
			return TRUE;
		}	
	}

	public function login_verify(){
		$username=$this->input->post('usrName');
		$password=$this->input->post('usrPass');
		$this->form_validation->set_rules('usrName', 'Username', 'trim|required|min_length[3]|max_length[50]');
		$this->form_validation->set_rules('usrPass', 'Password', 'trim|required|min_length[3]|max_length[50]');
		if ($this->form_validation->run() == FALSE)
		{	//echo "asdsad";exit;
			$this->session->set_flashdata('login_error','Minimum 3 Character Required');
			$this->load->view('login');
		}else{
			if($this->Login_lib->validate_login($username, $password))
			{ 
				if( $this->session->userdata('usrTypID')==0) {
						redirect(base_url().'Dashboard/');
				}
			}
			else
			{
				$this->session->set_flashdata('usrName',$username);
				$this->session->set_flashdata('login_error','Incorrect Username or Password');
				redirect(base_url().'Login');
			}
		}
	}
	public function update_pass(){
		$user_id = $this->session->userdata('user_id');
		$password = $this->input->post('password');
		$update_pass = $this->Login_model->update_pass($user_id,$password );
		if($update_pass){
			redirect(base_url().$this->config->item('certificate_path').'manage_certificate');
		}else{
			$this->session->set_flashdata('login_error','password not set');
			redirect(base_url(). $this->config->item('login_path').'change_pass');		
			}
	}
}
