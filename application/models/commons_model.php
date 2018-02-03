<?php 
class Commons_model extends CI_Model
{
	/////////////////////////////////////////////////////
	//////////////     update methods     //////////////
	///////////////////////////////////////////////////
	public function update_record($table, $colum, $id, $data){
		$this->db->where($colum, $id);
		$this->db->update($table, $data); 
		//$this->db->update($table, array('usrEnable' => 0));
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}
	public function update_record_with_data($table,$colum,$id,$data){
		$this->db->where($colum, $id);
		$this->db->update($table, $data); 
		if($this->db->affected_rows() > 0){
			$this->db->select('*');
			$this->db->where('ID', $id);
			$query = $this->db->get($table);	
			return array('status' => '200', 'res' => $query->result_array());
		}else{
			return false;
		}
	}
	/////////////////////////////////////////////////////
	//////////////     Insert Methods     //////////////
	///////////////////////////////////////////////////
	public function insert_record($table, $data){
		$this->db->insert($table,$data);
		return $this->db->insert_id();		
	}
	/////////////////////////////////////////////////////
	//////////////     get single Record     ///////////
	///////////////////////////////////////////////////
	public function single_record($table,$colum,$id){
		$this->db->where($colum, $id);
		$query = $this->db->get($table);	
	 	return $query->row();		
	}
	/////////////////////////////////////////////////////
	//////////////     Check old Password     //////////
	///////////////////////////////////////////////////
	public function check_old_password($old_password, $id){
		//$this->db->where('usrPass',md5($old_password));
		$this->db->where('pass', $old_password);
		$this->db->where('ID', $id);
		$this->db->from('tbl_users');
		if($this->db->count_all_results() > 0)
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////
	//////////////                           ///////////
	///////////////////////////////////////////////////
	public function check_email_exist($email){
		$this->db->where('email',$email);
		$this->db->where('delete', 0);
		$this->db->from('tbl_users');	
		return $this->db->count_all_results();
	}
	
	
	public function check_cnic_exist($cnic){
		$this->db->where('cnic',$cnic);
		$this->db->where('delete_bit', 0);
		$this->db->from('persons');	
		
		return $this->db->count_all_results();
	}
	
	public function check_username_exist($user_name){
		$this->db->where('username',$user_name);
		$this->db->where('delete_bit', 0);
		$this->db->from('tbl_users');

		return $this->db->count_all_results();
	}
	
	public function check_update_email_exist($email,$user_id){
		$this->db->where('email',$email);
		$this->db->where('delete_bit', 0);
		$this->db->where('userid !=',$user_id);
		$this->db->from('users');	
		
		return $this->db->count_all_results();	
	}
	
	public function check_update_cnic_exist($cnic,$person_id){
		
		$this->db->where('cnic',$cnic);
		$this->db->where('delete_bit', 0);
		$this->db->where('person_id !=',$person_id);
		$this->db->from('persons');	
		
		return $this->db->count_all_results();
	}
	
	public function all_records($table)
	{
		$this->db->select('*');
		$this->db->where('delete_bit', 0);
		$this->db->where('active_bit', 1);
		
		$this->db->order_by("certificate_id", "desc");
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
	
	public function all_record($table)
	{
		$this->db->select('*');
		$this->db->where('delete_bit', 0);
		
		 $query = $this->db->get($table);	
		return $query->result_array();		
	}
	
	public function get_all_record($table)
	{
		$this->db->select('*');
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
	
	
	public function all_record_delete_bit($table)
	{
		$this->db->select('*');
		$this->db->where('delete_bit', 0);
	
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
	public function delete_record($table,$data,$colum,$id)
	{
		$this->db->where($colum, $id);
		$this->db->update($table,$data); 
		return $this->db->affected_rows();		
	}
	
	public function all_record_with_id($table,$colum,$id)
	{
		$this->db->where($colum, $id);
		$this->db->where('delete_bit', 0);
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
	
	public function all_install_with_id($table,$colum,$id)
	{
		$this->db->where($colum, $id);
		$this->db->where('delete_bit', 0);
		$this->db->where('received_bit', 0);
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
	
	public function delete_record_from_db($table,$colum,$id)
	{
		$this->db->where($colum, $id);
		$this->db->delete($table); 
		return $this->db->affected_rows();		
	}
	public function all_record_with_id_without_del($table,$colum,$id)
	{
		$this->db->where($colum, $id);
	
		$query = $this->db->get($table);	
		return $query->result_array();		
	}
		public function check_identification_exist($identification)
	{
		
		$this->db->where('identification',$identification);
		
		$this->db->where('delete_bit', 0);
		$this->db->from('products');	
		return $this->db->count_all_results();
	}
		public function check_customer_account($account)
	{
		
		$this->db->where('account',$account);
		$this->db->where('delete_bit', 0);
		$this->db->from('cust');	
		return $this->db->count_all_results();
	}
	public function checkk_old_password($old_password)
	{
		$this->db->where('user_pass',md5($old_password));
		$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	
}

?>