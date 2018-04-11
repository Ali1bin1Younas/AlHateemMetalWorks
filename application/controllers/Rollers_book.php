<?php

class Rollers_book extends CI_Controller{

	function __construct(){
		parent::__construct();	
	    $this->load->model("Rollers_book_model");
     	$this->load->model('Commons_model');	
		$this->load->library('Commons_lib');
		$this->load->helper('url');
	
		if(!$this->session->userdata('logged_in'))
		{
			redirect(base_url().'Login');
		} 
	}

	function index(){
		$data['pageHeading'] = "Rooler Book";
		$data['row_data'] = $this->Rollers_book_model->get_records();
		$data["name"] = $this->session->userdata('name');
		$this->load->view("Rollers/".$this->router->fetch_class()."_view",$data);
	}
	function rollers_book_create_send(){
		$data['pageHeading'] = "Create Rooler Invoice";
		$data["name"] = $this->session->userdata('name');
		$data['isEdit'] = 0;
		$data['typID'] = 1;
		$data['IDNo'] = $this->Rollers_book_model->get_No();
		$this->session->unset_userdata('ID');
		$this->load->view("Rollers/".$this->router->fetch_class(),$data);
	}
	function rollers_book_create_receive(){
		$data['pageHeading'] = "Create Rooler Invoice";
		$data["name"] = $this->session->userdata('name');
		$data['isEdit'] = 0;
		$data['typID'] = 2;
		$data['IDNo'] = $this->Rollers_book_model->get_No();
		$this->session->unset_userdata('ID');
		$this->load->view("Rollers/".$this->router->fetch_class(),$data);
	}

	function sale_edit(){
		$data['pageHeading'] = "Edit Rooler Word";
		$data["name"] = $this->session->userdata('name');

		if(null != $this->session->userdata('ID')){
			$data['IDNo'] = $this->session->userdata('ID');
			$data['isEdit'] = 1;
		}else{
			$data['IDNo'] = $this->Rollers_book_model->get_No();
		}
		$this->load->view("Rollers/".$this->router->fetch_class(),$data);
	}

	function get_invoice_detail(){
		echo $this->Rollers_book_model->get_invoice_detail($this->input->get('ID'));
	}
	/////////////////////////////////////////////
	//////////////////////////////////////////// 
	public function add_record(){
		$Vdata = json_decode($this->input->get("model"));
		$issueDateStr = null;
		$VoucherDescriptionStr = "";
		if(isset($Vdata->issueDate)){
			$issueDateStr = explode("/", (string)$Vdata->issueDate);
			$issueDateStr = $issueDateStr[2] . "/" . $issueDateStr[1] ."/" . $issueDateStr[0];
		}
		if(isset($Vdata->VoucherDescription)){
			$VoucherDescriptionStr = $Vdata->VoucherDescription;
		}

		$data['descrip'] = $VoucherDescriptionStr;
		$data['dateIssue'] = $issueDateStr;
		$data["dateTimeCreated"] = date('Y-m-d H:i:s');
		$data['rolrID'] = $Vdata->roller->id;
		$data['usrID_usr'] = $this->session->userdata('usrID');
		$data['grandTotal'] = $Vdata->GrandTotal;

		$this->load->model($this->router->fetch_class()."_model");
		$result = $this->Rollers_book_model->add_record_with_data('tbl_'.$this->router->fetch_class(), $data, $Vdata->Lines, $Vdata->isEdit, $Vdata->ID);
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
		$res = $this->Rollers_book_model->getProducts($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}

	public function getRollers(){
		$res = $this->Rollers_book_model->getRollers($this->input->get("Term"));
		if($res)
			echo json_encode(array('results' => $res));
		else
			echo json_encode(array('results' => $res));
	}

	public function set_edit_session(){
		$this->ci =& get_instance();
		$array=array(
			'ID'=>$this->input->get("id")
		);
		$this->ci->session->set_userdata($array);
		echo json_encode(array('id' => $this->session->userdata('ID')));
	}
}
