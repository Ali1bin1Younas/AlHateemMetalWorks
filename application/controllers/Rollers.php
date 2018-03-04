<?php

class Rollers extends CI_Controller{

	function __construct(){
		parent::__construct();	
	    $this->load->model($this->router->fetch_class()."_model");
     	$this->load->model('Commons_model');	
 		$this->load->library('Commons_lib');
	
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'Login');
		} 
	}
	function index(){
		$data['pageHeading'] = $this->router->fetch_class();
		$data['row_data'] = $this->Rollers_model->get_records('tbl_'.$this->router->fetch_class());
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
		$result = $this->Rollers_model->add_record_with_data('tbl_'.$this->router->fetch_class(), $usrData);
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
			$res = $this->Rollers_model->update_record_with_data('tbl_'.$this->router->fetch_class(),'ID',$ID, $usrData);
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
			$res = $this->Commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $usrData);
			if($res == true)
				echo json_encode(array('status' => '200', 'msg' => 'Roller detail updated successfully.', 'res' => array('usrEnable' => 0),'res2' => $usrData));
			else
				echo json_encode(array('status' => $res, 'msg' => 'Unable to update roller detail!.', 'res' => $usrData));
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
		$res = $this->Commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $usrData);
		
		//$res = $this->users_model->delete_user($this->input->get('usrDeleted'), ID);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'Roller detail updated successfully.', 'result' => $usrData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update roller detail!.', 'result' => $res));
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
}
?>