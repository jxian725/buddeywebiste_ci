<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Specializationmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//specialization lists
	public function specialization_lists(){
		$this->db->select('*');
	    $this->db->where( 'status', 1 );
	    $this->db->order_by("specialization_id", "desc");
	    $query = $this->db->get( 'specialization' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//specialization Info
	function specializationInfo( $specializationID ) {
		$this->db->where( 'specialization_id', $specializationID );
		$query 		= $this->db->get( 'specialization' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addspecialization($data){
		
    	$table  = $this->db->dbprefix('specialization');
		$this->db->insert( $table, $data );
		return true; 
	}
}