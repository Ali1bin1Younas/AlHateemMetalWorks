<?Php
class Purchase_model extends CI_Model{

    function get_records(){
        $qry = "SELECT tbl_vouchers.*,ifNull(tbl_vouchers.saleNo,tbl_vouchers.purchaseNo) as voucherNo , tbl_users.name as usrName, tbl_voucherstype.name as typName FROM  `tbl_vouchers` ".
            " inner join tbl_users on tbl_users.ID = tbl_vouchers.usrID ".
            " inner join tbl_voucherstype on tbl_voucherstype.ID = tbl_vouchers.typID ".
            " where tbl_vouchers.deleted = 0 and tbl_vouchers.typID = 2";
        $record=$this->db->query($qry);
        return $record->result_array();
    }
    function get_record($id){
        $qry = "SELECT tbl_vouchers.*,ifNull(tbl_vouchers.saleNo,tbl_vouchers.purchaseNo) as voucherNo , tbl_users.name as usrName, tbl_voucherstype.name as typName FROM  `tbl_vouchers` ".
            " inner join tbl_users on tbl_users.ID = tbl_vouchers.usrID ".
            " inner join tbl_voucherstype on tbl_voucherstype.ID = tbl_vouchers.typID ".
            " where tbl_vouchers.deleted = 0 and tbl_vouchers.ID = '".$id."'";
        $record=$this->db->query($qry);
        return $record->result_array();
    }
    function get_purchaseNo(){
        $qry = "SELECT ((ifNull(Max(purchaseNo),1))+1) as purchaseNo from tbl_vouchers";
        return $this->db->query($qry)->row()->purchaseNo;
    }

    public function get_invoice_detail($id){
        $qry = "SELECT ifNull(tv.dateDelivery, '') as dateDelivery,tv.dateIssue,tv.discount,tv.discountType, ".
            " ifNull(tv.purchaseNo,1) as voucherNo, ifNull(tv.referenceNo,1) as referenceNo, tv.descrip, tu.id as usrID, ".
            " tu.name as usrName".
            " FROM  `tbl_vouchers` tv".
            " inner join tbl_users tu on tu.ID = tv.usrID ".
            " where tv.typID = 2 and tv.ID = '".$id."'";
        $rec = $this->db->query($qry)->result_array();
        $qry = "SELECT tvd.id,tp.ID as prdID,tp.Name as prdName,tp.descrip as prdDescrip,tvd.qty,tvd.price as amount, ".
            " tvd.builtyNo,tvd.cargoName,tvd.discount as discountAmount ".
            " FROM  `tbl_vouchersdetail` tvd ".
            " inner join tbl_products tp on tp.ID = tvd.prdID ".
            " where tvd.VID = '".$id."'";
        $rec_detail = $this->db->query($qry)->result_array();
        return json_encode(array('res' => $rec, 'res_detail' => $rec_detail));
    }
    ///////////////////////////////////////////
    ///////////     CRUB Operations     //////
    /////////////////////////////////////////
    public function add_record_with_data($tbl, $data, $LinesArr, $idEdit, $ID){
        $this->db->trans_start();
        $VID = $ID;

        if($idEdit == "1"){
            $this->db->where('id', $VID);
            $this->db->update($tbl, $data);
        }else{
            $this->db->insert($tbl,$data);
            $VID = $this->db->insert_id();
        }

        $this->db->where('VID', $VID);
        $this->db->delete('tbl_vouchersdetail');

        $LinesStr = '';
		foreach($LinesArr as $Line){
            $discountAmount = 0;
            $builtyNo = "";
            $cargoName = "";

            if(isset($Line->discountAmount) && $Line->discountAmount != '' && $Line->discountAmount != null)
                $discountAmount = $Line->discountAmount;
            if(isset($Line->builtyNo))
                $builtyNo = $Line->builtyNo;
            if(isset($Line->cargoName))
                $cargoName = $Line->cargoName;

			$LinesStr = " Insert Into tbl_vouchersdetail (vID,prdID,qty,price,builtyNo,cargoName,discount) ".
					   " Values(".$VID.",".$Line->Item->id.",".$Line->qty.",".$Line->AmountAsNumber.",'".$builtyNo."','".$cargoName."',".$discountAmount.");";	
                       $this->db->query($LinesStr);
                    }

        $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
         " values('0',".$data['gradeTotal'].",'".$this->get_account_ID($data['usrID'])."','2')");

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $VID;
        else
            return false;
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
    //////////////////////////////////////////////
    /////////     get Attributes     ////////////
    ////////////////////////////////////////////
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

    ////////////////////////////////////////////
    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_users where usrID = '".$id."'");
        return $query->row('accID');
    }
}
?>