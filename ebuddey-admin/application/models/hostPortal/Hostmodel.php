<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Hostmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    public function guiderInfo($guider_id){
        
        $this->db->select('*');
        $this->db->where('guider_id', $guider_id );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
    public function activityInfo($activity_id){
        
        $this->db->select('*');
        $this->db->where('activity_id', $activity_id );
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
    function updateGuiderInfo( $guider_id, $data ) {

        $table  = $this->db->dbprefix( 'guider' );
        $this->db->where( 'guider_id', $guider_id );
        $this->db->update( $table, $data );
        return true;
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
    public function guiderLangInfo( $lang_id ) {
        $this->db->select( '*' );
        $this->db->where( 'lang_id', $lang_id );
        $query  = $this->db->get( 'guider_language' );
        $row    = $query->row();
        return $row;
    }
    public function guiderSpecialityInfo( $specialization_id ) {
        $this->db->select( '*' );
        $this->db->where( 'specialization_id', $specialization_id );
        $query  = $this->db->get( 'specialization' );
        $row    = $query->row();
        return $row;
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
    function getGuiderActiveServiceRegionLists($guider_id) {
        $serviceLists = array();
        $this->db->select('activity_id,service_providing_region');
        $this->db->from('guider_activity_list');
        $this->db->where( 'activity_guider_id', $guider_id );
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
    function activeBookingTotal_count( $startDate = '', $endDate = '', $host_id ) {
        $this->db->select('journey_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where( 'jny_status', 2 );
        //Date Range
        if( ( $startDate ) && ( $endDate ) ) {
            $this->db->where( 'service_date >=', $startDate );
            $this->db->where( 'service_date <=', $endDate );
        }
        $this->db->where( 'jny_guider_id', $host_id );
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            return $rowcount;
        } else {
            return 0;
        }
    }
    function completedBookingTotal_count( $startDate = '', $endDate = '', $host_id ) {
        $this->db->select('journey_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where( 'jny_status', 3 );
        //Date Range
        if( ( $startDate ) && ( $endDate ) ) {
            $this->db->where( 'service_date >=', $startDate );
            $this->db->where( 'service_date <=', $endDate );
        }
        $this->db->where( 'jny_guider_id', $host_id );
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            return $rowcount;
        } else {
            return 0;
        }
    }
    function upcomingBookingTotal_count( $startDate = '', $endDate = '', $host_id ) {
        $this->db->select('journey_id');
        $this->db->from('journey_list');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->where( 'jny_status', 3 );
        //Date Range
        if( ( $startDate ) && ( $endDate ) ) {
            $this->db->where( 'service_date >=', $startDate );
            $this->db->where( 'service_date <=', $endDate );
        }
        $this->db->where( 'jny_guider_id', $host_id );
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            return $rowcount;
        } else {
            return 0;
        }
    }
    function pendingRequestTotal_count( $startDate = '', $endDate = '', $host_id ) {
        $this->db->select('service_id');
        $this->db->from('service_list');
        $this->db->where( 'status', 1 );
        //Date Range
        if( ( $startDate ) && ( $endDate ) ) {
            $this->db->where( 'service_date >=', $startDate );
            $this->db->where( 'service_date <=', $endDate );
        }
        $this->db->where( 'service_guider_id', $host_id );
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if( $rowcount > 0 ) {
            return $rowcount;
        } else {
            return 0;
        }
    }
    function cancelPendingRequests( $activity_id, $data ) {
        $table  = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'activity_id', $activity_id );
        $this->db->where('status', 1 );
        $this->db->update( $table, $data );
        return true;
    }
    function insertServiceLog( $data ){
        $table = $this->db->dbprefix( 'service_log' );
        $this->db->insert( $table, $data );
    }
}