<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('commons_model');		
		$this->load->model('dashboard_model');

		$this->load->library('commons_lib');
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'login');
			//$this->load->view('dashboard');
		}	
	}
	public function index()
	{	
		//	echo "asd";exit;
		//$data["total_cust"]=$this->dashboard_model->total_cust();
		//$data["total_file"]=$this->dashboard_model->total_file();
		//$data["sold_file"]=$this->dashboard_model->sold_file();
		//$data["pending_file"]=$this->dashboard_model->pending_file();
		$data["user_name"]=$this->session->userdata('user_name');
		
		$this->load->view('dashboard',$data);
	}
	public function change_password()
	{	
		 $data["user_name"]=$this->session->userdata('user_name');
		$this->load->view('change_password',$data);
	}
	
	public function check_old_password_varification(){	
		$old_password=$this->input->post('old_password');
		$result_old_password = $this->commons_lib->check_old_password($old_password);
		if ($result_old_password == 0)
		{
			$this->form_validation->set_message('check_old_password_varification', 'your old password does not recognized.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}		
	}

	public function submit_change_password(){
	
		$old_password=$this->input->post('old_password');	
		$new_password=$this->input->post('new_password');
		$c_password=$this->input->post('c_password');
		$this->form_validation->set_rules('old_password', 'Old Password', 'required|xss_clean|callback_check_old_password_varification');
		$this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[3]|max_length[12]|xss_clean');
		$this->form_validation->set_rules('c_password', 'Confirm Passoword', 'required|matches[new_password]|min_length[3]|max_length[12]|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$data["user_name"]=$this->session->userdata('user_name');
			$this->load->view('change_password',$data);
		}else
		{ 
			$data=array("user_pass"=>md5($c_password));
			$updated_status = $this->commons_model->update_record('users','user_id',$this->session->userdata("user_id"), $data);
	
			if($updated_status > 0)
			{
				$this->session->set_flashdata('message',"Your Password has been updated successfully.");
				redirect(base_url().$this->config->item('dashboard_path').'change_password');
			}
			else
			{
				$this->session->set_flashdata('message',"some problem.");
				redirect(base_url().$this->config->item('dashboard_path').'change_password');
			}
		}
	}
 
}
