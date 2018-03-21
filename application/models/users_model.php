<?Php
class Users_model extends CI_Model{

    function get_records(){
        $qry = "SELECT tbl_users.*, tbl_userstype.name as typName FROM  `tbl_users` ".
            " inner join tbl_userstype on tbl_userstype.ID = tbl_users.typID ".
            " where deleted = 0";
        $record=$this->db->query($qry);
        return $record->result_array();
    }

    function get_record($id){
        $qry = "SELECT tbl_users.*, tbl_userstype.name as typName FROM  `tbl_users` ".
            " inner join tbl_userstype on tbl_userstype.ID = tbl_users.typID ".
            " where deleted = 0 and tbl_users.ID = '".$id."'";
        $record=$this->db->query($qry);
        return $record->result_array();
    }

    public function get_attributes(){
        try{
            $qry = " SELECT id,name from tbl_userstype";
            return array('usersTypes' => $this->db->query($qry)->result_array());
        }catch(Exception $e){
            return false;
        }
    }

    public function get_last_user($id){   
        $query = $this->db->query("SELECT * FROM  `tbl_users` WHERE ID ='".$id."'");
        return $query->row();		
	}

    function get_user_records_by_its_type($id){
        $qry = "SELECT tbl_users.*, tbl_userstype.name as typName FROM  `tbl_users` ".
            " inner join tbl_userstype on tbl_userstype.ID = tbl_users.typID ".
            " where deleted = 0 and tbl_userstype.ID = $id";
        $record=$this->db->query($qry);
        return $record->result_array();
    }
    
    public function add_record_with_data($tbl, $data){
        $this->db->trans_start();
        $this->db->insert($tbl,$data);
        $ID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts (code,name,headID,tblID) values('".$this->getUserCode($data['typID'], $ID)."','".$data['fullName']."','".$this->getHeadID($data['typID'])."','1')");
        $accID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts_users (accID,usrID) values('".$accID."','".$ID."')");
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $this->get_record($ID);
        else
            return false;
	}

    public function update_record_with_data($table,$colum,$id,$data){
        $this->db->trans_start();
        $this->db->where($colum, $id);
        $this->db->update($table, $data); 

        $this->db->where($colum, $this->get_account_ID($id));
        $this->db->update("tbl_accounts", array('name' => $data['fullName']));
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
            return array('status' => '200', 'res' => $this->get_record($id));
        else
            return false;
	}

    public function delete_user($usrDeleted, $id){
        $qry = "UPDATE tbl_users SET `deleted` = ".$usrDeleted.", `enable` = ".($usrDeleted = 1 ? 0 : 1)." WHERE ID='". $id ."'";
        $query = $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function disable_user($usrEnable, $id){
        $query = $this->db->query("UPDATE tbl_users SET enable = ". $usrEnable ." WHERE ID='".$id."'");
        return $query;
    }

    public function change_pass($pass, $id){
        $this->db->query("UPDATE tbl_users SET pass = '".$pass."' WHERE ID='".$id."'");
        return $this->db->affected_rows();
    }

    ////////////////////////////////////////////////////
    //////////////////// Helping functions     ////////
    //////////////////////////////////////////////////
    public function getUserCode($typID, $ID){
        if($typID == "2" || $typID == "1"){return "20020".$ID;}else if($typID == "3"){return "10040".$ID;}else if($typID == "4"){return "20010".$ID;}else{return "0";}
    }
    public function getHeadID($typID){
        if($typID == "2" || $typID == "1"){return "17";}else if($typID == "3"){return "24";}else if($typID == "4"){return "23";}else{return "0";}
    }

    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_users where usrID = '".$id."'");
        return $query->row('accID');
    }
}
?>