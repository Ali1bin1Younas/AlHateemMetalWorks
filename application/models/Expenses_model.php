<?Php
class Expenses_model extends CI_Model{
    
    public function get_records($tbl){
        $record=$this->db->query("SELECT * FROM  ".$tbl." where deleted = 0");
        return $record->result_array();
    }
    /////////////////////////////////////////////////
    ////////////////     Add Methods     ///////////
    ///////////////////////////////////////////////
    public function add_record($tbl, $data){
		$this->db->insert($tbl,$data);
		$id = $this->db->insert_id();
		if($this->db->affected_rows() > 0)
			return true;
        else
            return false;
	}
    public function add_record_with_data($tbl, $data){
		$this->db->insert($tbl,$data);
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
    /////////////////////////
    public function getProducts($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id,name as text,descrip as description,costPrice as unitPrice, id as trackingCode ".
            " from tbl_products where typID = 3 and deleted = 0 and enable = 1 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }

    function get_No(){
        $qry = "SELECT ((ifNull(Max(ID),0))+1) as ID from tbl_expenses";
        return $this->db->query($qry)->row()->ID;
    }
}
?>