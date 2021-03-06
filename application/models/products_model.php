<?Php
class Products_model extends CI_Model{
    /////////////////////////////////////////////////
    ////////////////     Get Methods     ///////////
    ///////////////////////////////////////////////
    function get_records(){
        $qry = "SELECT tbl_products.*,tbl_uoms.name as UOMName,tbl_catagories.name as catName, ".
        " tbl_types.name as typName,tbl_accounts_products.accID ".
        " FROM `tbl_products` ".
        " inner join tbl_uoms on tbl_products.UOMID = tbl_uoms.ID and tbl_uoms.enable = 1 and tbl_uoms.deleted = 0 ".
        " inner join tbl_catagories on tbl_products.catID = tbl_catagories.ID and tbl_catagories.enable = 1 and tbl_catagories.deleted = 0 ".
        " inner join tbl_types on tbl_products.typID = tbl_types.ID and tbl_types.enable = 1 and tbl_types.deleted = 0 ".
        " inner join tbl_accounts_products on tbl_accounts_products.prdID = tbl_products.ID ".
        " where tbl_products.Deleted = 0";
        $record = $this->db->query($qry);
        return $record->result_array();
    }

    public function get_record($id){   
        $qry = "SELECT tbl_products.*,tbl_uoms.name as UOMName,tbl_catagories.name as catName, ".
        " tbl_types.name as typName,tbl_accounts_products.accID ".
        " FROM `tbl_products` ".
        " inner join tbl_uoms on tbl_products.UOMID = tbl_uoms.ID and tbl_uoms.enable = 1 and tbl_uoms.deleted = 0 ".
        " inner join tbl_catagories on tbl_products.catID = tbl_catagories.ID and tbl_catagories.enable = 1 and tbl_catagories.deleted = 0 ".
        " inner join tbl_types on tbl_products.typID = tbl_types.ID and tbl_types.enable = 1 and tbl_types.deleted = 0 ".
        " inner join tbl_accounts_products on tbl_accounts_products.prdID = tbl_products.ID ".
        "where tbl_products.Deleted = 0 and tbl_Products.ID ='".$id."'";
        $record = $this->db->query($qry);
        return $record->result_array();		
	}

    public function get_attributes(){
        try{
            $qryUOM = " SELECT id,name from tbl_uoms where deleted = 0 and enable = 1;";
            $qryCat = " SELECT id,name from tbl_catagories where deleted = 0 and enable = 1;";
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

        $this->db->query("Insert Into tbl_accounts (code,name,headID,tblID) values('10010".$prdID."','".$data['name']."','15','2')");
        $accID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_accounts_Products (accID,prdID) values('".$accID."','".$prdID."')");
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $this->get_record($prdID);
        else
            return false;
	}
    /////////////////////////////////////////////////
    ////////////////     Update Methods     ////////
    ///////////////////////////////////////////////
    public function update_record_with_data($table,$colum,$id,$data){
        $this->db->trans_start();
		$this->db->where($colum, $id);
        $this->db->update($table, $data);

        $this->db->where($colum, $this->get_account_ID($id));
        $this->db->update("tbl_accounts", array('name' => $data['name']));
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
            return array('status' => '200', 'res' => $this->get_record($id));
        else
            return false;
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

    /////////////////////////////////////////////////
    ////////////////     Helping Methods     ///////
    ///////////////////////////////////////////////
    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_products where prdID = '".$id."'");
        return $query->row('accID');
    }
}
?>