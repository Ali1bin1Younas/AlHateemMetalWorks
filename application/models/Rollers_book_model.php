<?Php
class Rollers_book_model extends CI_Model{

    function get_records(){
        $qry = "SELECT tbl_rollers_book.*, ".
            " tbl_rollers.name as rolrName, tbl_rollers_booktype.name as typName ".
            " FROM  `tbl_rollers_book` ".
            " inner join tbl_rollers on tbl_rollers.ID = tbl_rollers_book.rolrID ".
            " inner join tbl_rollers_booktype on tbl_rollers_booktype.ID = tbl_rollers_book.typID ".
            " where tbl_rollers_book.deleted = 0 ".
            " order by tbl_rollers_book.id desc ";
        return $this->db->query($qry)->result_array();
    }
    function get_record($id){
        $qry = "SELECT tbl_rollers_book.*, ".
            " tbl_rollers.name as rolrName, tbl_rollers_booktype.name as typName ".
            " FROM  `tbl_rollers_book` ".
            " inner join tbl_rollers on tbl_rollers.ID = tbl_rollers_book.rolrID ".
            " inner join tbl_rollers_booktype on tbl_rollers_booktype.ID = tbl_rollers_book.typID ".
            " where tbl_rollers_book.deleted = 0 and tbl_rollers_book.ID = '".$id."'";
        return $this->db->query($qry)->result_array();
    }
    public function get_invoice_detail($id){
        $qry = "SELECT tv.dateIssue,tv.ID, tv.descrip, ".
            " tu.id as rolrID, tu.name as rolrName".
            " FROM  `tbl_rollers_book` tv".
            " inner join tbl_rollers tu on tu.ID = tv.rolrID ".
            " where tv.ID = '".$id."'";
        $rec = $this->db->query($qry)->result_array();
        $qry = "SELECT tvd.id,tvd.weight,tvd.price as amount ".
            " FROM  `tbl_rollers_book_detail` tvd ".
            " inner join tbl_rollers_book tp on tp.ID = tvd.rolrID ".
            " where tvd.rolrID = '".$id."'";
        return json_encode(array('res' => $rec, 'res_detail' => $this->db->query($qry)->result_array()));
    }
    ////////////////////////////////////////////
    //////////////     CRUD Methods     ///////
    //////////////////////////////////////////
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
        
        $this->db->where('ID', $VID);
        $this->db->delete('tbl_rollers_book_detail');

        $LinesStr = '';
		foreach($LinesArr as $Line){
			$LinesStr = " Insert Into tbl_rollers_book_detail (rolrID,weight,price) ".
					   " Values(".$VID.",".$Line->weight.",".$Line->AmountAsNumber.");";	
                       $this->db->query($LinesStr);
        }
        $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
         " values('1','".$data['grandTotal']."','".$this->get_account_ID($data['rolrID'])."','2')");

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

    ///////////////////////////////////////////
    /////////////     Attributes     /////////
    /////////////////////////////////////////
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

    public function getRollers($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id,name as text from tbl_rollers where deleted = 0 and enable = 1 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }
    /////////////////////////////////////////
    /////////////     Helping Methods     //
    ///////////////////////////////////////
    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_rollers where rolrID = '".$id."'");
        return $query->row('accID');
    }
    function get_No(){
        $qry = "SELECT ((ifNull(Max(ID),0))+1) as ID from tbl_rollers_book";
        return $this->db->query($qry)->row()->ID;
    }

}
?>