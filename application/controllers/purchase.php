<?php

class purchase extends CI_Controller{

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
		$data['row_data'] = $this->purchase_model->get_records();
		$data["name"] = $this->session->userdata('name');
		$this->load->view("vouchers/".$this->router->fetch_class()."_view",$data);
	}
	function purchaseCreate(){
		$data['pageHeading'] = "Create Voucher";
		$data['purchaseNo'] = $this->purchase_model->get_purchaseNo();
		$data["name"] = $this->session->userdata('name');
		$this->load->view("vouchers/purchase_create",$data);
	}
	/////////////////////////////////////////////    
	public function add_record(){

		$Vdata = json_decode($this->input->get("model"));
		$issueDateStr = null;
		$deliveryDateStr = null;
		$VoucherDescriptionStr = "";
		if(isset($Vdata->issueDate)){
			$issueDateStr = explode("/", (string)$Vdata->issueDate);
			$issueDateStr = $issueDateStr[2] . "/" . $issueDateStr[1] ."/" . $issueDateStr[0];
		}
		if(isset($Vdata->deliveryDate)){
			$deliveryDateStr = explode("/", (string)$Vdata->deliveryDate);
			$deliveryDateStr = $deliveryDateStr[2] . "/" . $deliveryDateStr[1] ."/" . $deliveryDateStr[0];
		}
		if(isset($Vdata->VoucherDescription)){
			$VoucherDescriptionStr = $Vdata->VoucherDescription;
		}

		$data['dateIssue'] = $issueDateStr;
		$data['dateDelivery'] = $deliveryDateStr;
		$data["dateTimeCreated"] = date('Y-m-d H:i:s');
		$data['referenceNo'] = $Vdata->referenceNo;
		$data['usrID'] = $Vdata->supplier->id;
		$data['typID'] = 2;
		$data['descrip'] = $VoucherDescriptionStr;
		$data['discount'] = $Vdata->discount;
		$data['discountType'] = $Vdata->discountType;
		$data['usrID_usr'] = $this->session->userdata('usrID');
		$data['purchaseNo'] = $Vdata->purchaseNo;

		
		// echo json_encode(array('status' => '200', 'msg' => 'User detail added successfully.', 'result' => $lineStr));
		// die();
		
		$this->load->model($this->router->fetch_class()."_model");
		$result = $this->purchase_model->add_record_with_data('tbl_vouchers', $data, $Vdata->Lines);
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
			$res = $this->Vouchers_model->update_record_with_data('tbl_'.$this->router->fetch_class(),'ID',$ID, $usrData);
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
			//$res = $this->Vouchers_model->disable_user($this->input->get('usrEnable'), $ID);
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
		
		//$res = $this->Vouchers_model->delete_user($this->input->get('usrDeleted'), ID);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'User detail updated successfully.', 'result' => $usrData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update user detail!.', 'result' => $res));
	}
	/////////////////////////////////////////////
	//////////////     Get Views Methods    ////
	///////////////////////////////////////////
	public function get_view_create(){
		$data['pageHeading'] = "Create ". $this->router->fetch_class();
		$data['row_data'] = $this->vouchers_model->get_records("tbl_".$this->router->fetch_class());
		echo $this->load->view("vouchers/".$this->router->fetch_class()."_create", $data, true);
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
	public function getProducts(){
		$res = $this->purchase_model->getProducts($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}

	public function getSuppliers(){
		$res = $this->purchase_model->getSuppliers($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}
	///////////////
	public function ko(){
		$data['row_data'] = $this->purchase_model->get_records();
		$this->load->view("vouchers/koFunctions",$data);
	}
}
?>