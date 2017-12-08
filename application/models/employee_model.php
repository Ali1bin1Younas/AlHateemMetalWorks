<?php 
class Employee_model extends CI_Model
{
	
	
	
	
	public function insert_employee($user_data)
	{
			$query = $this->db->insert('employee',$user_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	
	public function update_employee($emp_data,$id)
	{
			$this->db->where("employee_id",$id);
			$query = $this->db->update('employee',$emp_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	
	
	
/*******************************************************************************************************************/	
	public function all_employee()
	{
		$this->db->where('delete_bit',1);
		$query=$this->db->get('employee');
		return $query->result_array();
	}
	
	
	public function get_employee($id)
	{
		$this->db->where("employee_id",$id);
		$query=$this->db->get('employee');
		return $query->row();
	}
	public function delete_employee($id)
	{
		$emp_data=array('delete_bit'=>0);
			$this->db->where("employee_id",$id);
			$query = $this->db->update('employee',$emp_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	
	public function all_task()
	{
		$this->db->where('delete_bit',1);
		$query=$this->db->get('task');
		return $query->result_array();
	}
	public function insert_task($task_data)
	{
			$query = $this->db->insert('task',$task_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	public function get_task($id)
	{
		$this->db->where("task_id",$id);
		$query=$this->db->get('task');
		return $query->row();
	}
	public function update_task($task_data,$id)
	{
			$this->db->where("task_id",$id);
			$query = $this->db->update('task',$task_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	public function delete_task($id)
	{
		$emp_data=array('delete_bit'=>0);
			$this->db->where("task_id",$id);
			$query = $this->db->update('task',$emp_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	
	public function all_project()
	{
		$this->db->where('delete_bit',1);
		$query=$this->db->get('project');
		return $query->result_array();
	}
		public function insert_project($project_data)
	{
			$query = $this->db->insert('project',$project_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	public function get_project($id)
	{
		$this->db->where("project_id",$id);
		$query=$this->db->get('project');
		return $query->row();
	}
	public function update_project($pro_data,$id)
	{
			$this->db->where("project_id",$id);
			$query = $this->db->update('project',$pro_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	public function delete_project($id)
	{
		$emp_data=array('delete_bit'=>0);
			$this->db->where("project_id",$id);
			$query = $this->db->update('project',$emp_data);
			if($query){
				return true;	
			}
			else{
				return false;	
			}
	}
	public function all_task_result($data)
	{
		
			$query =$this->db->query("SELECT * FROM task ".$data);
			return $query->result();
	}
}

?>