<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Guidermodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    //INSERT NEW HOST
    public function insertHost( $data ){
        $table  = $this->db->dbprefix( 'guider' );
        $this->db->insert( $table, $data );
        return $this->db->insert_id();
    }
	//Guider lists
	public function guider_lists( $guider_search = '', $order_by = '' ){
		$this->db->select('*');
	    $this->db->where( 'status !=', 4 );
	    $this->db->where( 'status !=', 0 );
	    //If Condition guider search
	    if( $guider_search ) {
	    	$this->db->where( "( first_name LIKE '%". $guider_search ."%' OR phone_number LIKE '%". $guider_search ."%' OR email LIKE '%". $guider_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'first_name', 'desc' );
	    } else if( $order_by == 3 ) {
	    	$this->db->order_by( 'email', 'asc' );
	    } else if( $order_by == 4 ) {
	    	$this->db->order_by( 'email', 'desc' );
	    } else {
	    	$this->db->order_by( 'guider_id', 'desc' );
	    }
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	public function pendingGuiderLists( $guider_search = '', $order_by = '' ){
		$this->db->select('*');
	    $this->db->where( 'status', 0 );
	    //If Condition guider search
	    if( $guider_search ) {
	    	$this->db->where( "( first_name LIKE '%". $guider_search ."%' OR phone_number LIKE '%". $guider_search ."%' OR email LIKE '%". $guider_search ."%' )" );
	    }
	    //If Condition for order by
	    if( $order_by == 1 ) {
	    	$this->db->order_by( 'first_name', 'asc' );
	    } else if( $order_by == 2 ) {
	    	$this->db->order_by( 'first_name', 'desc' );
	    } else if( $order_by == 3 ) {
	    	$this->db->order_by( 'email', 'asc' );
	    } else if( $order_by == 4 ) {
	    	$this->db->order_by( 'email', 'desc' );
	    } else {
	    	$this->db->order_by( 'guider_id', 'desc' );
	    }
	    $query = $this->db->get( 'guider' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$result = $query->result();
	    	return $result;
	    } else {
	    	return false;
	    }
	}
	function guiderActivityLists( $guider_id ) {
        $this->db->select('*');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function activityInfo($activity_id){
		
		$this->db->select('*');
	    $this->db->where('activity_id', $activity_id );
        $this->db->where( 'activity_status !=', 4 );
	    $query = $this->db->get('guider_activity_list');
		$row = $query->row();
	    return $row;
	}
	function updateActivityInfo( $activity_id, $data ) {
    	$table  = $this->db->dbprefix( 'guider_activity_list' );
    	$this->db->where( 'activity_id', $activity_id );
		$this->db->update( $table, $data );
    	return true;
    }
    public function insertGuiderActivity( $data ){
        $table  = $this->db->dbprefix( 'guider_activity_list' );
        $this->db->insert( $table, $data );
        $id     = $this->db->insert_id();
        return $id;
    }
    function getGuiderActiveServiceRegionLists($guider_id) {
    	$serviceLists = array();
        $this->db->select('activity_id,service_providing_region');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
        $this->db->where( 'activity_status !=', 4 );
        $this->db->order_by("activity_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $results = $query->result();
            if($results){
            	foreach ($results as $key => $value) {
            		$serviceLists[] = $value->service_providing_region;
            	}
            }
        }
        return $serviceLists;
    }
	public function guiderInfo($guider_id){
		
		$this->db->select('*');
	    $this->db->where('guider_id', $guider_id );
	    $query = $this->db->get('guider');
		$row = $query->row();
	    return $row;
	}
    public function guiderInfoByUID($host_uuid){
        
        $this->db->select('guider_id,first_name');
        $this->db->where('host_uuid', "$host_uuid" );
        $this->db->where( 'host_uuid !=', '' );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
    public function guiderInfoByPhone($phone_number){
        
        $this->db->select('guider_id');
        $this->db->where('phone_number', $phone_number );
        $this->db->where( 'status !=', 4 );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
	//UPDATE GUIDER STATUS
    function guiderStatus( $guider_id, $status ) {
    	$data 	= array( 'status' => $status );
    	$table  = $this->db->dbprefix( 'guider' );
    	$this->db->where( 'guider_id', $guider_id );
		$this->db->update( $table, $data );
    	return true;
    }
    function deleteGuider( $guider_id, $data ) {
        $table  = $this->db->dbprefix( 'guider' );
        $this->db->where( 'guider_id', $guider_id );
        $this->db->update( $table, $data );
        return true;
    }
    //Check password
    function check_password( $user_id, $password ) {
    	$this->db->select('*');
	    $this->db->where('user_id', $user_id );
	    $query = $this->db->get('user');
		$adminInfo   = $query->row();
        if($adminInfo){
            $oldPass     = $adminInfo->password;
            $currentPass = $this->encryption->decrypt($oldPass);
            if ($currentPass == trim($password)) {
                return true;
            }else{
                return false;
            }
        }else{
           return false;
        }
		
		
	    
    }
    public function stateInfoByid( $id ){
        $this->db->select( '*' );
        $this->db->where('id', $id);
        $query = $this->db->get('states');
        if( $query->num_rows() > 0 ){
            $row = $query->row();
            return $row; 
        }else{
            return false; 
        }
    }
    public function guiderLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'guider_language' );
        $row    = $query->row();
        return $row;
    }
    function getHostLangLists() {
        $this->db->select('*');
        $this->db->from('guider_language');
        $this->db->where('status', 1 );
        $this->db->order_by("language", "ASC");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function guiderSpecialityInfo( $specialization_id ) {
        $this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
    }
    function updateGuiderInfo( $guider_id, $data ) {

    	$table  = $this->db->dbprefix( 'guider' );
    	$this->db->where( 'guider_id', $guider_id );
		$this->db->update( $table, $data );
    	return true;
    }
    function serviceRegionLists($country_id=132) {

        $this->db->select('*');
        $this->db->from('states');
        $this->db->where('status', 1 );
        $this->db->where( 'country_id', $country_id );
        $this->db->order_by("name", "asc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    function getSpecializationLists() {
        $this->db->select('*');
        $this->db->from('specialization');
        $this->db->where('status', 1 );
        $this->db->order_by("specialization_id", "desc");
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }
    }
    public function guiderDeviceTokenList($guider_id){
        
        $this->db->select('*');
        $this->db->from('guider_device_info');
        $this->db->where( 'guider_id', $guider_id );
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ){
            $result = $query->result();
            return $result;
        }
    }
}