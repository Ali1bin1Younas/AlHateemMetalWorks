<?Php
class Expenses_model extends CI_Model{
    
    public function get_records($tbl){
        $record=$this->db->query("SELECT * FROM  ".$tbl." where deleted = 0");
        return $record->result_array();
    }
    /////////////////////////////////////////////////
    ////////////////     Add Methods     ///////////
    ///////////////////////////////////////////////
    public function add_record($tbl, $data, $LinesArr, $idEdit, $ID){
        $this->db->trans_start();
        $expID = $ID;

        if($idEdit == "1"){
            $this->db->where('id', $expID);
            $this->db->update($tbl, $data);
        }else{
            $this->db->insert($tbl,$data);
            $expD = $this->db->insert_id();
        }
        
        $this->db->where('expID', $expID);
        $this->db->delete('tbl_expensesdetail');

        $LinesStr = '';
		foreach($LinesArr as $Line){
            $descrip = '';
            $amountAsNumber = 0;
            $usrID = 0;
            $bankAccountID = 0;
            if(isset($Line->AmountAsNumber))
                $amountAsNumber = $Line->AmountAsNumber;
            if(isset($Line->AmountAsNumber))
                $amountAsNumber = $Line->AmountAsNumber;
            if(isset($Line->persons->id))
                $usrID = $Line->persons->id;
            if(isset($Line->bankAccounts->id))
                $bankAccountID = $Line->bankAccounts->id;

			$LinesStr = " Insert Into tbl_expensesdetail (expID,usrID,bnkAccID,amount,descrip) ".
					   " Values(".$expID.",".$usrID.",".$bankAccountID.",".$amountAsNumber.",'".$Line->description."');";	
            $this->db->query($LinesStr);
            
            if($usrID != 0 && $usrID != "" && $usrID != null){
                $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
                " values('1',".$amountAsNumber.",'".$this->get_usr_account_ID($usrID)."','4')");
            }else{
                $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
                " values('1',".$amountAsNumber.",'19','3')");
            }

            if($bankAccountID != 0 && $bankAccountID != "" && $bankAccountID != null && $Line->isBank){
                $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
                " values('0',".$amountAsNumber.",'".$this->get_bnkAcc_account_ID($bankAccountID)."','5')");
            }else if(!($Line->isBank) && $Line->CB == 26){
                $this->db->query("Insert Into tbl_transactions (typID,amount,accID,traLID) ".
                " values('0',".$amountAsNumber.",'26','6')");
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $expID;
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
    //////////////////////////////////////////////
    /////////////     Helping Methods     ///////
    ////////////////////////////////////////////
    public function getPersons($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and name LIKE '%".$term."%'";}
            $qry = " SELECT id, name as text ".
            " from tbl_users where deleted = 0 and enable = 1 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }
    public function getBankAccounts($term){
        try{
            $nameClause = "";
            if($term != ""){$nameClause = "and accountNo LIKE '%".$term."%'";}
            $qry = " SELECT id, accountNo as text ".
            " from tbl_bankaccounts where deleted = 0 and enable = 1 " . $nameClause;
            return $this->db->query($qry)->result_array();
        }catch(Exception $e){
            return false;
        }
    }
    public function get_usr_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_users where usrID = '".$id."'");
        return $query->row('accID');
    }
    public function get_bnkAcc_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_bankaccounts where bnkaccID = '".$id."'");
        return $query->row('accID');
    }
    function get_No(){
        $qry = "SELECT ((ifNull(Max(ID),0))+1) as ID from tbl_expenses";
        return $this->db->query($qry)->row()->ID;
    }
}
?>