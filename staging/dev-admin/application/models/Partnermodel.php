<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Partnermodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//PARTNER LISTS
	public function partnerList(){
		$this->db->select('partner_list.*,states.name as cityName');
        $this->db->from('partner_list');
		$this->db->join('states', 'states.id = partner_list.city_id','left');
	    $this->db->where( 'partner_list.status', 1 );
	    $this->db->order_by("partner_id", "desc");
	    $query = $this->db->get();
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	//PARTNER INFO
	function partnerInfo( $partnerID ) {
		$this->db->where( 'partner_id', $partnerID );
		$query 		= $this->db->get( 'partner_list' );
		$numRows 	= $query->num_rows();
		if( $numRows > 0 ) {
			return $query->row();
		} else { return false; }
	}
	public function addPartner($data){
    	$table  = $this->db->dbprefix('partner_list');
		$this->db->insert( $table, $data );
		return true; 
	}
	public function updatePartner( $partner_id, $data ){
        $table2 = $this->db->dbprefix('partner_list');
        $this->db->where( 'partner_id', $partner_id );
        $this->db->update( $table2, $data );
	}
	function deletePartner( $partner_id ) {
        $this->db->where( 'partner_id', $partner_id );
        $this->db->delete( 'partner_list' );

        return true;
    }
}