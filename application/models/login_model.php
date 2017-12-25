<?php 
class Login_model extends CI_Model
{
	
	public function get_login($username,$password){
		$this->db->select('*');
		$this->db->where('name',$username);		
		//$this->db->where('usrPass',md5($password));
		$this->db->where('pass',$password);
		$this->db->where('enable',1);		
		$query=$this->db->get('tbl_users');	
		return $query->row();	
	}	
	
	public function check_pass($user_id,$password){ 
		$this->db->select('*');
		$this->db->where('usrID',$user_id);		
		$this->db->where('pass',md5($password));
		$this->db->where('typID',0);			
		$query=$this->db->get('tbl_users');
			
		return $query->row();	
	}
	
	public function user_edit($user_id){	
		echo $user_id;
		$data['original_form_values']=1;
		$data['ID']=$user_id;
		$data['user_data']=$this->commons_model->single_record('tbl_users','ID',$user_id);
		$this->load->view('user_edit',$data);
	}
	public function update_pass($user_id,$password){
		$this->db->set('pass',md5($password));
		$this->db->where('ID',$user_id);		
		$this->db->update('tbl_users'); 
		return $this->db->affected_rows();		
	}
}
?>