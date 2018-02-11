<?Php
class Catagories_model extends CI_Model{

    public function get_records($tbl){
        $record=$this->db->query("SELECT * FROM  ".$tbl." where deleted = 0");
        return $record->result_array();
    }
    public function get_record($tbl, $id){   
        $query = $this->db->query("SELECT * FROM  ".$tbl." WHERE ID ='".$id."'");
        return $query->row();		
	}
    /////////////////////////////////////////////////
    ////////////////     Add Methods     ///////////
    ///////////////////////////////////////////////
    public function add_record($tbl, $data){
		$this->db->insert($tbl,$data);
		$this->db->insert_id();
		$id = $this->db->insert_id();
		if($this->db->affected_rows() > 0)
			return true;
        else
            return false;
	}
    public function add_record_with_data($tbl, $data){
		$this->db->insert($tbl,$data);
		$this->db->insert_id();
		$id = $this->db->insert_id();
		if($this->db->affected_rows() > 0){
			$this->db->select('*');
			$this->db->where('ID', $id);
			$query = $this->db->get($tbl);
			return $query->result_array();
		}
	}
    /////////////////////////////////////////////////
    ////////////////     Update Methods     ////////
    ///////////////////////////////////////////////
    public function update_record($tbl,$colum,$id,$data){
		$this->db->where($colum, $id);
		$this->db->update($tbl, $data); 
		if($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}
    /////////////////////////////////////////////////
    ////////////////     D & D Methods     /////////
    ///////////////////////////////////////////////
    public function delete_record($tbl, $deleted, $id){
        $qry = "UPDATE ".$tbl." SET `deleted` = ".$deleted.", `enable` = ".($deleted = 1 ? 0 : 1)." WHERE ID='". $id ."'";
        $query = $this->db->query($qry);
        return $this->db->affected_rows();
    }
    public function disable_record($tbl, $enable, $id){
        $query = $this->db->query("UPDATE ".$tbl." SET enable = ". $enable ." WHERE ID='".$id."'");
        return $query;
    }
}
?>