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
}