<?php

class Sale extends CI_Controller{

	function __construct(){
		parent::__construct();	
	    $this->load->model("Sale_model");
     	$this->load->model('Commons_model');	
		$this->load->library('Commons_lib');
		$this->load->helper('url');
	
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'Login');
		} 
	}

	function index(){
		$data['pageHeading'] = "Sales Invoice";
		$data['row_data'] = $this->Sale_model->get_records();
		$data["name"] = $this->session->userdata('name');
		$this->load->view("Vouchers/".$this->router->fetch_class()."_view",$data);
	}
	function sale_create(){
		$data['pageHeading'] = "Create Voucher";
		$data["name"] = $this->session->userdata('name');
		$data['isEdit'] = 0;
		$data['saleNo'] = $this->Sale_model->get_saleNo();
		$this->session->unset_userdata('VID');
		$this->load->view("Vouchers/sale_create",$data);
	}

	function sale_edit(){
		$data['pageHeading'] = "Create Voucher";
		$data["name"] = $this->session->userdata('name');

		if(null != $this->session->userdata('VID')){
			$data['saleNo'] = $this->session->userdata('VID');
			$data['isEdit'] = 1;
		}else{
			$data['saleNo'] = $this->Sale_model->get_saleNo();
		}
		$this->load->view("Vouchers/".$this->router->fetch_class()."_create",$data);
	}

	function get_invoice_detail(){
		echo $this->Sale_model->get_invoice_detail($this->input->get('ID'));
	}
	/////////////////////////////////////////////
	//////////////////////////////////////////// 
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
		$data['usrID'] = $Vdata->customer->id;
		$data['typID'] = 1;
		if($Vdata->discount){
			$data['discount'] = $Vdata->discount;
			$data['discountType'] = $Vdata->discountType;
		}
		$data['descrip'] = $VoucherDescriptionStr;
		$data['usrID_usr'] = $this->session->userdata('usrID');
		$data['saleNo'] = $Vdata->saleNo;
		$data['gradeTotal'] = $Vdata->GradeTotal;

		$this->load->model($this->router->fetch_class()."_model");
		$result = $this->Sale_model->add_record_with_data('tbl_vouchers', $data, $Vdata->Lines, $Vdata->isEdit, $Vdata->VID);
		if($result){
			echo json_encode(array('status' => '200', 'msg' => 'User detail added successfully.', 'result' => $result));
		}else{
			echo json_encode(array('status' => '201', 'msg' => 'Unexpexted error, please try again..'));
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
		
		//$res = $this->Vouchers_model->delete_user($this->input->get('usrDeleted'), ID);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'User detail updated successfully.', 'result' => $usrData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update user detail!.', 'result' => $res));
	}
	/////////////////////////////////////////////
	//////////////     Get Views Methods(not bieng used)    ////
	///////////////////////////////////////////
	public function get_view_create(){
		$data['pageHeading'] = "Create ". $this->router->fetch_class();
		$data['row_data'] = $this->Purchase_model->get_records("tbl_".$this->router->fetch_class());
		echo $this->load->view("vouchers/".$this->router->fetch_class()."_create", $data, true);
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
	public function getProducts(){
		$res = $this->Sale_model->getProducts($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}

	public function getCustomers(){
		$res = $this->Sale_model->getCustomers($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}

	public function set_edit_session(){
		$this->ci =& get_instance();
		$array=array(
			'VID'=>$this->input->get("id")
		);
		$this->ci->session->set_userdata($array);
		echo json_encode(array('id' => $this->session->userdata('VID')));
	}
}
?>