<?Php
class Rollers_model extends CI_Model{

    function get_records($tbl){
        $qry = "SELECT * FROM  ".$tbl." where deleted = 0";
        return $this->db->query($qry)->result_array();
    }

    function get_record($tbl, $id){
        $qry = "SELECT * FROM  ".$tbl." where deleted = 0 and ID = '".$id."'";
        return $this->db->query($qry)->result_array();
    }

    public function get_last_user($id){   
        $query = $this->db->query("SELECT * FROM  `tbl_users` WHERE ID ='".$id."'");
        return $query->row();		
	}

    public function add_record_with_data($tbl, $data){
        $this->db->trans_start();
        $this->db->insert($tbl,$data);
        $ID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts (code,name,headID,tblID) values('20040".$ID."','".$data['name']."','29','5')");
        $accID = $this->db->insert_id();

        $this->db->query("Insert Into tbl_Accounts_rollers (accID,rolrID) values('".$accID."','".$ID."')");
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
			return $this->get_record($tbl, $ID);
        else
            return false;
	}

    public function update_record_with_data($tbl,$colum,$id,$data){
        $this->db->trans_start();
        $this->db->where($colum, $id);
		$this->db->update($tbl, $data); 
        
        $this->db->where($colum, $this->get_account_ID($id));
        $this->db->update("tbl_accounts", array('name' => $data['name']));
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE)
            return array('status' => '200', 'res' => $this->get_record($tbl, $id));
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

    /////////////////////////////////////////////////
    ////////////////     Helping Methods     ///////
    ///////////////////////////////////////////////
    public function get_account_ID($id){
        $query = $this->db->query("select accID from tbl_accounts_rollers where rolrID = '".$id."'");
        return $query->row('accID');
    }
}
?>