<?php

class Users extends CI_Controller{

	function __construct(){
		parent::__construct();	
	    $this->load->model($this->router->fetch_class()."_model");
     	$this->load->model('commons_model');	
 		$this->load->library('commons_lib');
	
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'login');
		} 
	}
	function index(){
		$data['pageHeading'] = $this->router->fetch_class();
		$data['row_data'] = $this->users_model->get_records();
		$data["name"] = $this->session->userdata('name');
		$this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_view",$data);
	}
	        
	public function add_record(){
		$usrData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$usrData[$key] = $val;
		}
		$usrData["dateTimeCreated"] = date('Y-m-d H:i:s');

		$this->load->model($this->router->fetch_class()."_model");
		$result = $this->Users_model->add_record_with_data('tbl_'.$this->router->fetch_class(), $usrData);
		if($result){
			echo json_encode(array('status' => '200', 'msg' => 'User detail added successfully.', 'result' => $result));
		}else{
			echo json_encode(array('status' => '201', 'msg' => 'Unexpexted error, please try again..'));
		}
	 }

	public function update_detail(){
		$ID = $this->input->get('ID');
		$usrData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$usrData[$key] = $val;
		}
		if($ID != "" && $ID != "0"){
			$res = $this->users_model->update_record_with_data('tbl_'.$this->router->fetch_class(),'ID',$ID, $usrData);
			if($res['status'] == 200){
				echo json_encode(array('status' => '200', 'msg' => 'User detail updated successfully.', 'result' => $res['res']));
			}else{
				echo json_encode(array('status' => '201', 'msg' => 'User update failed!', 'result' => $res['res']));
			}
		}else{
			echo json_encode(array('status' => '204', 'msg' => 'Unexpected error!, please contact system administrator.'));
		}
	 }
	
	public function disable_record(){
		$ID = $this->input->get('ID');
		$usrData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$usrData[$key] = (int)$val;
		}
		if($ID != "" && $ID != "0"){
			//$res = $this->users_model->disable_user($this->input->get('usrEnable'), $ID);
			$res = $this->commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $usrData);
			if($res == true)
				echo json_encode(array('status' => '200', 'msg' => 'User detail updated successfully.', 'res' => array('usrEnable' => 0),'res2' => $usrData));
			else
				echo json_encode(array('status' => $res, 'msg' => 'Unable to update user detail!.', 'res' => $usrData));
		}else{
			echo json_encode(array('status' => '204', 'msg' => 'Unexpected error!, please contact system administrator.'));
		}
	}
	public function delete_record(){
		$ID = $this->input->get('ID');
		if($ID == "" && $ID == 0){echo json_encode(array('status' => '204', 'msg' => 'Unexpected error, please contact system administrator!'));}
		
		$res = 0;
		$usrData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$usrData[$key] = (int)$val;
		}	
		$res = $this->commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $usrData);
		
		//$res = $this->users_model->delete_user($this->input->get('usrDeleted'), ID);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'User detail updated successfully.', 'result' => $usrData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update user detail!.', 'result' => $res));
	}

	public function change_pass(){
		$ID = $this->input->get('ID');
		$usrData = array(
			"pass" => $this->input->get('pass')
		);
		$res = "";
		if($ID != "" && $ID != "0"){
			if($this->commons_model->check_old_password($this->input->get('passOld'), $ID)){
				$res = $this->users_model->change_pass($this->input->get('pass'), $ID);
				if($res > 0){
					echo json_encode(array('status' => '200', 'msg' => 'User password updated successfully.'));
					}else{
						echo json_encode(array('status' => '201', 'msg' => 'Unexpected error!, please try again later!'));
					}
			}else{
				echo json_encode(array('status' => '202', 'msg' => 'Wrong old password entered!', 'result' => $res));
			}
		}else{
			echo json_encode(array('status' => '204', 'msg' => 'Unexpected error!, please contact system administrator!'));
		}	
	 }





	
	
	
	
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
	public function get_attributes(){
		$res = $this->Users_model->get_attributes();
		if($res)
			echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $res));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update Product detail!.', 'result' => $res));
	}
}
?>