<?php 
class Login_lib {

	public function validate_login($user,$password){	
		$this->ci =& get_instance();
		$this->ci->load->model('Login_model');
		$result = $this->ci->Login_model->get_login($user,$password);

		if(count($result)>0){	
			$array=array(
			'usrID'=>$result->ID,
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

