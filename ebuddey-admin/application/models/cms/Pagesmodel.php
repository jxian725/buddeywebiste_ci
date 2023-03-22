<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Pagesmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//Page lists
	public function page_lists(){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->order_by("page_id", "desc");
	    $query = $this->db->get( 'cms_pages' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//Page Info
	function pageInfo( $pageID ) {
		$this->db->where( 'page_id', $pageID );
		$query 		= $this->db->get( 'cms_pages' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addPage($data){
		
    	$table  = $this->db->dbprefix('cms_pages');
		$this->db->insert( $table, $data );
		return true; 
	}
	public function updatePage( $pageID, $data ){
        $table2 = $this->db->dbprefix('cms_pages');
        $this->db->where( 'page_id', $pageID );
        $this->db->update( $table2, $data );
	}
	//Venue Partner Images List
	public function venuePartnerImgLists(){
		$this->db->select('*');
	    $this->db->where( 'status', 1 );
	    $this->db->order_by("cvp_id", "desc");
	    $query = $this->db->get( 'cms_venue_partner_images' );
	    if( $query->num_rows() > 0 ) {
	    	return $query->result();
	    } else {
	    	return false;
	    }
	}
	//Venue Partner Images Info
	function venuePartnerImgInfo( $cvp_id ) {
		$this->db->where( 'cvp_id', $cvp_id );
		$query 		= $this->db->get( 'cms_venue_partner_images' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	//Add Venue Partner Images
	public function addVenuePartnerImg($data){
		$this->db->insert( 'cms_venue_partner_images', $data );
		return true; 
	}
	//Update Venue Partner Images
	public function updateVenuePartnerImg($data, $cvp_id){
        $this->db->where( 'cvp_id', $cvp_id );
        $this->db->update( 'cms_venue_partner_images', $data );
	}
}