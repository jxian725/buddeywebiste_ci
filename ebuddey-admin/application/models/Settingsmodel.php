<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Settingsmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//role lists
	public function role_lists(){
		$this->db->select('*');
	    $this->db->where( 'status', 1 );
	    $this->db->order_by("role_id", "desc");
	    $query = $this->db->get( 'role' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//role Info
	function roleInfo( $role_id ) {
		$this->db->where( 'role_id', $role_id );
		$query 		= $this->db->get( 'role' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addrole($data){
    	$table  = $this->db->dbprefix('role');
		$this->db->insert( $table, $data );
		return true; 
	}
	public function updaterole($data, $role_id){
    	$table  = $this->db->dbprefix('role');
    	$this->db->where( 'role_id', $role_id );
		$this->db->update( $table, $data );
		return true; 
	}
	public function permission_list( $role_id ) {
        $this->db->where( 'role_id', $role_id );
        $this->db->where('status', 1 );
        $this->db->select('module');
        $list = $this->db->get( 'permission' );
        $val = $list->result();
        return $val;
    }
    public function addPermission( $permission_data ) {
        $this->db->insert( 'permission', $permission_data );
        return $this->db->insert_id();
    }
    public function updatePermission( $role_id, $module, $permission_data ) {
        $table  = $this->db->dbprefix('permission');
    	$this->db->where( 'role_id', $role_id );
        $this->db->where( 'module', "$module" );
		$this->db->update( $table, $permission_data );
    }
    public function updateAllPermission( $role_id, $permission_data ) {
        $table  = $this->db->dbprefix('permission');
    	$this->db->where( 'role_id', $role_id );
		$this->db->update( $table, $permission_data );
    }
    public function delete_permission_role( $role_id ) {
        $this->db->where( 'role_id', $role_id );
        $this->db->delete('permission');
    }
    public function rolePermissionInfo( $role_id, $module ) {
        $this->db->select( '*' );
        $this->db->where( 'role_id', $role_id );
        $this->db->where( 'module', "$module" );
        $query  = $this->db->get( 'permission' );
        $row    = $query->row();
        return $row;
    }
}