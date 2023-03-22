<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Servicemodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	//Service lists
	public function serviceListData( $limit=false,$start=false,$search=false,$traveller_id=false, $service_search = '', $order_by = '', $startDate = '', $endDate = '' ){
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$this->db->select('service_list.*,traveller.first_name as requestorName, guider.first_name as guiderName');
        $this->db->from('service_list');
        $this->db->join('traveller', 'traveller.traveller_id = service_list.service_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = service_list.service_guider_id','left');
        if($traveller_id){ $this->db->where( 'service_traveller_id', $traveller_id ); }
        if($search){
            $this->db->where("(pickup_location LIKE '%".$search."%')");
        }
        //If Condition traveller search
	    if( $service_search ) {
	    	$this->db->where( "( traveller.first_name LIKE '%". $service_search ."%' OR guider.first_name LIKE '%". $service_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'traveller.first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'guider.first_name', 'desc' );
	    } 
	    //Date Range
	    if( ( $startDate ) && ( $endDate ) ) {
    		$this->db->where( 'service_list.service_date >=', $startDate );
    		$this->db->where( 'service_list.service_date <=', $endDate );
    	}
        $this->db->where( 'service_list.status !=', 4 );
        if($limit && $start){ $this->db->limit($limit, $start); }
        $this->db->group_by("service_list.service_id");
	    $this->db->order_by("service_id", "desc");
	    $query = $this->db->get();
	    //echo $this->db->last_query();
	    if ( $query->num_rows() > 0 ){
	        $result = $query->result();
	        
	        return $result;
	    }
	}
	public function serviceInfo($service_id){
		
		$this->db->select('service_list.*,traveller.first_name as requestorName, guider.first_name as guiderName');
        $this->db->from('service_list');
        $this->db->join('traveller', 'traveller.traveller_id = service_list.service_traveller_id','left');
        $this->db->join('guider', 'guider.guider_id = service_list.service_guider_id','left');
        $this->db->where('service_id', $service_id );
	    $query = $this->db->get();
		$row = $query->row();
	    return $row;
	}
	function cancelPendingRequests( $activity_id, $data ) {
    	$table  = $this->db->dbprefix( 'service_list' );
    	$this->db->where( 'activity_id', $activity_id );
    	$this->db->where('status', 1 );
		$this->db->update( $table, $data );
    	return true;
    }
}