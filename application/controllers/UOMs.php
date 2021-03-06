<?php

class UOMs extends CI_Controller{

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
	
	public function add_record(){
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = $val;
		}
		$rowData["dateTimeCreated"] = date('Y-m-d H:i:s');

		$this->load->model("uoms_model");
		$result = $this->UOMs_model->add_record("tbl_".$this->router->fetch_class(), $rowData);
		if($result){
			echo json_encode(array('status' => '200', 'msg' => 'UOM detail added successfully.', 'result' => $result));
		}else{
			echo json_encode(array('status' => '201', 'msg' => 'Unexpexted error, please try again..'));
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
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = (int)$val;
		}	
		$res = $this->Commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $rowData);
		
		//$res = $this->users_model->delete_user($this->input->get('usrDeleted'), ID);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $rowData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update Product detail!.', 'result' => $res));
	}
	//////////////////////////////////////////////
	public function get_view_main(){
			$data['row_data'] = $this->UOMs_model->get_records();
			echo $this->load->view("Products/".$this->router->fetch_class()."_view", $data, true);
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
}
?>