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
}
?>