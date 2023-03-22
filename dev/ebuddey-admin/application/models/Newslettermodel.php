<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Newslettermodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//Newsletter lists
	public function newsletter_lists(){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->order_by("newsletter_id", "desc");
	    $query = $this->db->get( 'newsletter' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//Newsletter Info
	function newsletterInfo( $newsletterID ) {
		$this->db->where( 'newsletter_id', $newsletterID );
		$query 		= $this->db->get( 'newsletter' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addNewsletter($data){
		
    	$table  = $this->db->dbprefix('newsletter');
		$this->db->insert( $table, $data );
		return true; 
	}
}