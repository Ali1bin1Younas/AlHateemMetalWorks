<?php 
class login_lib {

	public function validate_login($user,$password){	
		$this->ci =& get_instance();
		$this->ci->load->model('login_model');
		$result=$this->ci->login_model->get_login($user,$password);

		if(count($result)>0){	
			$array=array(
			'ID'=>$result->ID,
			'usrName'=>$result->fullName,
			'typID'=>$result->typID,
			'logged_in'=>true
			);
			$this->ci->session->set_userdata($array);
			return true;
		}
		else{return false;}
	}
}

