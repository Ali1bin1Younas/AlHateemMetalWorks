<?php

class Products extends CI_Controller{

	function __construct(){
		parent::__construct();	
	    $this->load->model($this->router->fetch_class()."_model");
     	$this->load->model('Commons_model');	
 		$this->load->library('Commons_lib');
	
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'login');
		} 
	}
     
	function index(){
		$data['pageHeading'] = $this->router->fetch_class();
		$data['row_data'] = $this->Products_model->get_records();
		$data["name"] = $this->session->userdata('name');
		$this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_view",$data);
	}        
	
	public function add_record(){
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = $val;
		}
		$rowData["dateTimeCreated"] = date('Y-m-d H:i:s');

		$result = $this->Products_model->add_record_with_data('tbl_'.$this->router->fetch_class(), $rowData);
		if($result){
			echo json_encode(array('status' => '200', 'msg' => 'User detail added successfully.', 'result' => $result));
		}else{
			echo json_encode(array('status' => '201', 'msg' => 'Unexpexted error, please try again..'));
		}
	 }

	public function update_detail(){
		$ID = $this->input->get('ID');
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = $val;
		}
		if($ID != "" && $ID != "0"){
			$res = $this->Products_model->update_record_with_data('tbl_'.$this->router->fetch_class(),'ID',$ID, $rowData);
			if($res['status'] == 200){
				echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $res['res']));
			}else{
				echo json_encode(array('status' => '201', 'msg' => 'Product update failed!', 'result' => $res['res']));
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
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = (int)$val;
		}	
		$res = $this->commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $rowData);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $rowData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update Product detail!.', 'result' => $res));
	}

	/////////////////////////////////////////////
	//////////////     get view     ////////////
	///////////////////////////////////////////
	public function get_view_uom(){
		$data['row_data'] = $this->Products_model->get_records();
		echo $this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_view_uom", $data, true);
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
	public function get_attributes(){
		$res = $this->Products_model->get_attributes();
		if($res)
			echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $res));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update Product detail!.', 'result' => $res));
	}
}
?>