<?Php
class Products_model extends CI_Model{
    /////////////////////////////////////////////////
    ////////////////     Get Methods     ///////////
    ///////////////////////////////////////////////
    function get_records(){
        $qry = "SELECT tbl_products.*,tbl_UOMs.name as UOMName,tbl_catagories.name as catName, ".
        " tbl_types.name as typName ".
        " FROM `tbl_products` ".
        " inner join tbl_UOMs on tbl_products.UOMID = tbl_UOMs.ID and tbl_UOMs.enable = 1 and tbl_UOMs.deleted = 0 ".
        " inner join tbl_catagories on tbl_products.catID = tbl_catagories.ID and tbl_catagories.enable = 1 and tbl_catagories.deleted = 0 ".
        " inner join tbl_types on tbl_products.typID = tbl_types.ID and tbl_types.enable = 1 and tbl_types.deleted = 0 ".
        " where tbl_products.Deleted = 0";
        $record = $this->db->query($qry);
        return $record->result_array();
    }

    public function get_record($id){   
        $qry = "SELECT tbl_products.*,tbl_UOMs.name as UOMName,tbl_catagories.name as catName, ".
        " tbl_types.name as typName ".
        " FROM `tbl_products` ".
        " inner join tbl_UOMs on tbl_products.UOMID = tbl_UOMs.ID and tbl_UOMs.enable = 1 and tbl_UOMs.deleted = 0 ".
        " inner join tbl_catagories on tbl_products.catID = tbl_catagories.ID and tbl_catagories.enable = 1 and tbl_catagories.deleted = 0 ".
        " inner join tbl_types on tbl_products.typID = tbl_types.ID and tbl_types.enable = 1 and tbl_types.deleted = 0 ".
        "where tbl_products.Deleted = 0 and tbl_Products.ID ='".$id."'";
        $record = $this->db->query($qry);
        return $record->result_array();		
	}

    public function get_attributes(){
        try{
            $qryUOM = " SELECT id,name from tbl_UOMs where deleted = 0 and enable = 1;";
            $qryCat = " SELECT id,name from tbl_Catagories where deleted = 0 and enable = 1;";
            $qryTyp = " SELECT id,name from tbl_types where deleted = 0 and enable = 1;";
            return array('UOM' => $this->db->query($qryUOM)->result_array(), 
                'cat' => $this->db->query($qryCat)->result_array(),
                'typ' => $this->db->query($qryTyp)->result_array()
                );
        }catch(Exception $e){
            return false;
        }
    }
    /////////////////////////////////////////////////
    ////////////////     Add Methods     ///////////
    ///////////////////////////////////////////////
    public function add_record_with_data($table, $data){
        $this->db->trans_start();
        $this->db->insert($table,$data);
        $prdID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts (code,tblID) values('1','2')");
        $accID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts_Products (accID,prdID) values('".$accID."','".$prdID."')");
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $this->get_record($prdID);
        else
            return false;

		// if($this->db->affected_rows() > 0){
		// 	$this->db->select('*');
		// 	$this->db->where('ID', $id);
		// 	$query = $this->db->get($table);
		// 	return $query->result_array();
		// }
	}
    /////////////////////////////////////////////////
    ////////////////     Update Methods     ////////
    ///////////////////////////////////////////////
    public function update_record_with_data($table,$colum,$id,$data){
		$this->db->where($colum, $id);
		$this->db->update($table, $data); 
		if($this->db->affected_rows() > 0){
			return array('status' => '200', 'res' => $this->get_records());
		}else{
			return false;
		}
	}
    public function delete($deleted, $id){
        $qry = "UPDATE tbl_products SET `deleted` = ".$Deleted.", `enable` = ".($Deleted = 1 ? 0 : 1)." WHERE ID='". $id ."'";
        $query = $this->db->query($qry);
        return $this->db->affected_rows();
    }
    public function disable($enable, $id){
        $query = $this->db->query("UPDATE tbl_products SET enable = ". $Enable ." WHERE ID='".$id."'");
        return $query;
    }
}
?>