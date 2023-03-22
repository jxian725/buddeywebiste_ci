<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Requestormodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//Requestor lists
	public function requestor_lists(){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $query = $this->db->get( 'requestor' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	public function requestorInfo($requestor_id){
		
		$this->db->select('*');
	    $this->db->where('requestor_id', $requestor_id );
	    $query = $this->db->get('requestor');
		$row = $query->row();
	    return $row;
	}
	//UPDATE REQUESTOR STATUS
    function requestorStatus( $requestor_id, $status ) {
    	$data 	= array( 'status' => $status );
    	$table  = $this->db->dbprefix( 'requestor' );
    	$this->db->where( 'requestor_id', $requestor_id );
		$this->db->update( $table, $data );
    	return true;
    }
}