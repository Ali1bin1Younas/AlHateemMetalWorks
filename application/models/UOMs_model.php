<?Php
class UOMs_model extends CI_Model{

    public function get_records(){
        $record=$this->db->query("SELECT * FROM  `tbl_uoms` where deleted = 0");
        return $record->result_array();
    }
    public function get_record($id){   
        $query = $this->db->query("SELECT * FROM  `tbl_uoms` WHERE ID ='".$id."'");
        return $query->row();		
	}
    /////////////////////////////////////////////////
    ////////////////     Add Methods     ///////////
    ///////////////////////////////////////////////
    public function add_record($table, $data){
		$this->db->insert($table,$data);
		$this->db->insert_id();
		$id = $this->db->insert_id();
		if($this->db->affected_rows() > 0)
			return true;
        else
            return false;
	}
    public function add_record_with_data($table, $data){
		$this->db->insert($table,$data);
		$this->db->insert_id();
		$id = $this->db->insert_id();
		if($this->db->affected_rows() > 0){
			$this->db->select('*');
			$this->db->where('ID', $id);
			$query = $this->db->get($table);
			return $query->result_array();
		}
	}
    /////////////////////////////////////////////////
    ////////////////     Update Methods     ////////
    ///////////////////////////////////////////////
    public function update_record($table,$colum,$id,$data){
		$this->db->where($colum, $id);
		$this->db->update($table, $data); 
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}
    /////////////////////////////////////////////////
    ////////////////     D & D Methods     /////////
    ///////////////////////////////////////////////
    public function delete_record($deleted, $id){
        $qry = "UPDATE tbl_uoms SET `deleted` = ".$deleted.", `enable` = ".($deleted = 1 ? 0 : 1)." WHERE ID='". $id ."'";
        $query = $this->db->query($qry);
        return $this->db->affected_rows();
    }
    public function disable_record($enable, $id){
        $query = $this->db->query("UPDATE tbl_uoms SET enable = ". $enable ." WHERE ID='".$id."'");
        return $query;
    }
}
?>