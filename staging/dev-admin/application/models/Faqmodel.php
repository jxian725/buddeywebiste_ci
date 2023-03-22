<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Faqmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//FAQ lists
	public function faq_lists(){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->order_by("faq_id", "desc");
	    $query = $this->db->get( 'faq_list' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//FAQ Info
	function faqInfo( $faq_id ) {
		$this->db->where( 'faq_id', $faq_id );
		$query 		= $this->db->get( 'faq_list' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addFaq($data){
		
    	$table  = $this->db->dbprefix('faq_list');
		$this->db->insert( $table, $data );
		return true; 
	}
	public function updateFaq( $faq_id, $data ){
        $table2 = $this->db->dbprefix('faq_list');
        $this->db->where( 'faq_id', $faq_id );
        $this->db->update( $table2, $data );
        return true;
	}
	//DELETE FAQ
	function deleteFaq( $faq_id ) {
		$this->db->where( 'faq_id', $faq_id );
        $this->db->delete( 'faq_list' );
        return true;
	}
	
}