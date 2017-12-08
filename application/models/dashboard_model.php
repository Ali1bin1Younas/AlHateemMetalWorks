<?php 
class Dashboard_model extends CI_Model
{

	 public function total_cust(){ 
		$query = $this->db->query("SELECT COUNT(*) AS total_cust FROM `persons` WHERE delete_bit=0  ");
		 return $query->row();
	}
	 
	
	
}

?>