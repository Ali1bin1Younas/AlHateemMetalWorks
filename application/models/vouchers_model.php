<?Php
class vouchers_model extends CI_Model{

    function get_records(){
        $qry = "SELECT tbl_voucher.*,ifNull(tbl_voucher.saleNo,tbl_voucher.purchaseNo) as voucherNo , tbl_users.name as usrName, tbl_voucherType.name as typName FROM  `tbl_voucher` ".
            " inner join tbl_users on tbl_users.ID = tbl_voucher.usrID ".
            " inner join tbl_voucherType on tbl_voucherType.ID = tbl_voucher.typID ".
            " where tbl_voucher.deleted = 0";
        $record=$this->db->query($qry);
        return $record->result_array();
    }

    function get_record($id){
        $qry = "SELECT tbl_voucher.*,ifNull(tbl_voucher.saleNo,tbl_voucher.purchaseNo) as voucherNo , tbl_users.name as usrName, tbl_voucherType.name as typName FROM  `tbl_voucher` ".
            " inner join tbl_users on tbl_users.ID = tbl_voucher.usrID ".
            " inner join tbl_voucherType on tbl_voucherType.ID = tbl_voucher.typID ".
            " where tbl_voucher.deleted = 0 and tbl_voucher.ID = '".$id."'";
        $record=$this->db->query($qry);
        return $record->result_array();
    }

    public function get_attributes(){
        try{
            $qry = " SELECT id,name from tbl_users where deleted = 0 and enable = 1";
            return array('usersTypes' => $this->db->query($qry)->result_array());
        }catch(Exception $e){
            return false;
        }
    }

    public function getProducts($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id,name as text,descrip as description,costPrice as unitPrice, id as trackingCode from tbl_products where deleted = 0 and enable = 1 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }

    public function getSuppliers($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id,name as text from tbl_users where deleted = 0 and enable = 1 and typID = 4 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }

    public function add_record_with_data($tbl, $data){
        $this->db->trans_start();
        $this->db->insert($tbl,$data);
        $usrID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts (code,tblID) values('1','1')");
        $accID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts_users (accID,usrID) values('".$accID."','".$usrID."')");
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $this->get_record($usrID);
        else
            return false;
	}

    public function update_record_with_data($table,$colum,$id,$data){
		$this->db->where($colum, $id);
		$this->db->update($table, $data); 
		if($this->db->affected_rows() > 0){	
			return array('status' => '200', 'res' => $this->get_record($id));
		}else{
			return false;
		}
	}

    public function delete_record($deleted, $id){
        $qry = "UPDATE tbl_users SET `deleted` = ".$usrDeleted.", `enable` = ".($usrDeleted = 1 ? 0 : 1)." WHERE ID='". $id ."'";
        $query = $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function disable_record($enable, $id){
        $query = $this->db->query("UPDATE tbl_users SET enable = ". $usrEnable ." WHERE ID='".$id."'");
        return $query;
    }
}
?>