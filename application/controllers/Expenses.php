<?php

class Expenses extends CI_Controller{

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
		$data['row_data'] = $this->Expenses_model->get_records('tbl_'.strtolower($this->router->fetch_class()));
		$data["name"] = $this->session->userdata('name');
		$this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_view",$data);
	}   
	/////////////////////////////////////////////
	////////     create & Edit Methods     /////
	///////////////////////////////////////////
	function expense_create(){
		$data['pageHeading'] = "New Expenses";
		$data['No'] = $this->Expenses_model->get_No();
		$data["name"] = $this->session->userdata('name');
		$data['isEdit'] = 0;
		$this->session->unset_userdata('expID');
		$this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_create",$data);
	}
	function expenses_edit(){
		$data['pageHeading'] = "Edit Expense";
		$data["name"] = $this->session->userdata('name');

		if(null != $this->session->userdata('expID')){
			$data['No'] = $this->session->userdata('expID');
			$data['isEdit'] = 1;
		}else{
			$data['No'] = $this->Expenses_model->get_purchaseNo();
		}
		$this->load->view($this->router->fetch_class()."/".$this->router->fetch_class()."_create",$data);
	}
	function get_invoice_detail(){
		echo $this->Expenses_model->get_invoice_detail($this->input->get('ID'));
	}
	public function getPersons(){
		$res = $this->Expenses_model->getPersons($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}
	public function getBankAccounts(){
		$res = $this->Expenses_model->getBankAccounts($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}
	/////////////////////////////////////////////
	//////////////     CRUD Method     /////////
	///////////////////////////////////////////
	public function add_record(){
		$Vdata = json_decode($this->input->get("model"));
		$issueDateStr = null;
		$deliveryDateStr = null;
		$VoucherDescriptionStr = "";
		$referenceNo = '';
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
		if(isset($Vdata->referenceNo))
			$referenceNo = $Vdata->referenceNo;
		$data['referenceNo'] = $referenceNo;
		$data['usrID'] = $Vdata->supplier->id;
		$data['typID'] = 2;
		if($Vdata->discount){
			$data['discount'] = $Vdata->discount;
			$data['discountType'] = $Vdata->discountType;
		}
		$data['descrip'] = $VoucherDescriptionStr;
		$data['usrID_usr'] = $this->session->userdata('usrID');
		$data['purchaseNo'] = $Vdata->purchaseNo;
		$data['gradeTotal'] = $Vdata->GradeTotal;

		$this->load->model($this->router->fetch_class()."_model");
		$result = $this->Purchase_model->add_record_with_data('tbl_vouchers', $data, $Vdata->Lines, $Vdata->isEdit, $Vdata->VID);
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
		$rowData = array();
		foreach($this->input->get() as $key => $val){
			if($key != 'ID')
				$rowData[$key] = (int)$val;
		}	
		$res = $this->Commons_model->update_record('tbl_'.$this->router->fetch_class(), 'ID', $ID, $rowData);
		if($res > 0)
			echo json_encode(array('status' => '200', 'msg' => 'Product detail updated successfully.', 'result' => $rowData));
		else
			echo json_encode(array('status' => '201', 'msg' => 'Unable to update Product detail!.', 'result' => $res));
	}
	/////////////////////////////////////////////
	//////////////     Helping functions     ///
	///////////////////////////////////////////
	public function set_edit_session(){
		$this->ci =& get_instance();
			$array=array(
			'expID'=>$this->input->get("id")
			);
		$this->ci->session->set_userdata($array);

		echo json_encode(array('id' => $this->session->userdata('expID')));
	}
}
?>