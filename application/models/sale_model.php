<?Php
class sale_model extends CI_Model{

    function get_records(){
        $qry = "SELECT tbl_vouchers.*, ifNull(tbl_vouchers.saleNo,1) as voucherNo,".
            " tbl_users.name as usrName, tbl_voucherstype.name as typName ".
            " FROM  `tbl_vouchers` ".
            " inner join tbl_users on tbl_users.ID = tbl_vouchers.usrID ".
            " inner join tbl_voucherstype on tbl_voucherstype.ID = tbl_vouchers.typID ".
            " where tbl_vouchers.deleted = 0 and tbl_voucherstype.ID = 1";
        $record=$this->db->query($qry);
        return $record->result_array();
    }
    function get_record($id){
        $qry = "SELECT tbl_vouchers.*,ifNull(tbl_vouchers.saleNo,1) as voucherNo,".
            " tbl_users.name as usrName, tbl_voucherstype.name as typName ".
            " FROM  `tbl_vouchers` ".
            " inner join tbl_users on tbl_users.ID = tbl_vouchers.usrID ".
            " inner join tbl_voucherstype on tbl_voucherstype.ID = tbl_vouchers.typID ".
            " where tbl_vouchers.deleted = 0 and tbl_voucherstype.ID = 1 and tbl_vouchers.ID = '".$id."'";
        $record=$this->db->query($qry);
        return $record->result_array();
    }

    function get_saleNo(){
        $qry = "SELECT ((ifNull(Max(saleNo),0))+1) as saleNo from tbl_vouchers";
        return $this->db->query($qry)->row()->saleNo;
    }
    
    ///////////////////////////////////////////
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

    public function getCustomers($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id,name as text from tbl_users where deleted = 0 and enable = 1 and typID = 3 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }

    public function add_record_with_data($tbl, $data, $LinesArr){
        $this->db->trans_start();
        $this->db->insert($tbl,$data);
        $VID = $this->db->insert_id();
        $LinesStr = '';
		foreach($LinesArr as $Line){
            $discountAmount = 0;
            $builtyNo = "";
            $cargoName = "";
            if(isset($Line->discountAmount))
                $discountAmount = $Line->discountAmount;
            if(isset($Line->builtyNo))
                $builtyNo = $Line->builtyNo;
            if(isset($Line->cargoName))
                $cargoName = $Line->cargoName;

			$LinesStr = " Insert Into tbl_vouchersDetail (vID,prdID,qty,price,builtyNo,cargoName,discount) ".
					   " Values(".$VID.",".$Line->Item->id.",".$Line->qty.",".$Line->AmountAsNumber.",'".$builtyNo."','".$cargoName."',".$discountAmount.");";	
                       $this->db->query($LinesStr);
        }
        $this->db->query("Insert Into tbl_Transactions (typID,amount,accID,traLID) ".
         " values('1',".$data['gradeTotal'].",'".$this->get_account_ID($data['usrID'])."','2')");

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $VID;
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

    //////////////////////////
    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_users where usrID = '".$id."'");
        return $query->row('accID');
    }
}
?>