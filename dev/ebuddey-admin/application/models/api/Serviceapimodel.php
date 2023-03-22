<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Serviceapimodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    public function serviceInfo( $service_id ){
        $this->db->select( '*' );
        $this->db->where( 'service_id', $service_id );
        $query  = $this->db->get( 'service_list' );
        $row    = $query->row();
        return $row;
    }
    public function guiderServiceInfo( $service_id, $guider_id ){
        $this->db->reset_query();
        $this->db->select( '*' );
        $this->db->where( 'service_id', $service_id );
        $this->db->where( 'service_guider_id', $guider_id );
        $this->db->where( 'service_list.status !=', 3 );
        $query  = $this->db->get( 'service_list' );
        $row    = $query->row();
        return $row;
    }
    public function travellerServiceInfo( $service_id, $traveller_id ){
        $this->db->reset_query();
        $this->db->select( '*' );
        $this->db->where( 'service_id', $service_id );
        $this->db->where( 'service_traveller_id', $traveller_id );
        $this->db->where( 'service_list.status !=', 3 );
        $query  = $this->db->get( 'service_list' );
        $row    = $query->row();
        return $row;
    }
    //ACCEPT/CANCEL request and add payment
    function updateServiceRequest( $data, $service_id ) {
        $table  = $this->db->dbprefix( 'service_list' );
        $this->db->where( 'service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function updateJourneyByServiceid( $data, $service_id ){
        $table  = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->update( $table, $data );
        return true; 
    }
    public function journeyInfo( $service_id ){
        $this->db->select( '*' );
        $this->db->where( 'jny_service_id', "$service_id" );
        $query  = $this->db->get( 'journey_list' );
        $row    = $query->row();
        return $row;
    }
    function updateJourney( $data, $service_id ) {
        $table  = $this->db->dbprefix( 'journey_list' );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->update( $table, $data );
        return true;
    }
    public function insertJourney( $data ){
        $table = $this->db->dbprefix( 'journey_list' );
        $this->db->insert( $table, $data );
    }
    function travellerJourneyInfo( $service_id, $traveller_id ) {
        $this->db->select( '*' );
        $this->db->where( 'jny_service_id', $service_id );
        $this->db->where( 'jny_traveller_id', $traveller_id );
        $query  = $this->db->get( 'journey_list' );
        $row    = $query->row();
        return $row;
    }
    //GET GUIDER FEEDBACK FROM TRAVELLER
    function getTravellerFeedback( $guider_id ) {
        $data = array();
        $this->db->select('jl.*,t.traveller_id,t.first_name,t.profile_image');
        $this->db->from( 'journey_list jl' );
        $this->db->join( 'traveller t', 't.traveller_id = jl.jny_traveller_id', 'left' );
        $this->db->where( 'jl.jny_guider_id', $guider_id );
        $this->db->where( 'traveller_feedback !=', '' );
        $this->db->order_by( "jl.journey_id", "desc" );
        //if($limit || $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $feedbackLists   = $query->result();
            foreach ( $feedbackLists as $feedback ) {
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'t_profile/';
                $profile_image      = ($feedback->profile_image) ? $profileImgPath.$feedback->profile_image : '';
                $data[] = array(
                        "ratings_count"     => "". $feedback->rating ."",
                        "rated_user_name"   => "". $feedback->first_name ."",
                        "rated_user_image"  => "". $profile_image ."",
                        "service_id"        => "". $feedback->jny_service_id ."",
                        "rated_user_ID"     => "". $feedback->jny_traveller_id ."",
                        "rating_message"    => "". $feedback->traveller_feedback ."",
                        "rated_date"        => "". $feedback->traveller_feedbackon .""
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get '.HOST_NAME.' feedback list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        } else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No '.HOST_NAME.' feedback found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    //GET TRAVELLER FEEDBACK FROM GUIDER
    function getGuiderAllFeedback( $traveller_id ) {
        $data = array();
        $this->db->select('jl.*,g.guider_id,g.first_name,g.profile_image');
        $this->db->from( 'journey_list jl' );
        $this->db->join( 'guider g', 'g.guider_id = jl.jny_guider_id', 'left' );
        $this->db->where( 'jl.jny_traveller_id', $traveller_id );
        $this->db->where( 'guider_feedback !=', '' );
        $this->db->order_by( "jl.journey_id", "desc" );
        //if($limit || $start){ $this->db->limit($limit, $start); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $feedbackLists   = $query->result();
            foreach ( $feedbackLists as $feedback ) {
                $upload_path_url    = $this->config->item( 'upload_path_url' );
                $profileImgPath     = $upload_path_url.'g_profile/';
                $profile_image      = ($feedback->profile_image) ? $profileImgPath.$feedback->profile_image : '';
                $data[] = array(
                        "ratings_count"     => "",
                        "rated_user_name"   => "". $feedback->first_name ."",
                        "rated_user_image"  => "". $profile_image ."",
                        "service_id"        => "". $feedback->jny_service_id ."",
                        "rated_user_ID"     => "". $feedback->jny_guider_id ."",
                        "rating_message"    => "". $feedback->guider_feedback ."",
                        "rated_date"        => "". $feedback->guider_feedbackon .""
                        );        
            }
            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Get '.GUEST_NAME.' feedback list Successfully.', 'result' => 'success', 'data' => $data);
            return $result;
        } else {
            $data[] = array('error' => 1);
            $result = array('response_code' => ERROR_CODE, 'response_description' => 'No '.GUEST_NAME.' feedback found.', 'result' => 'error', 'data' => $data);
            return $result; 
        }
    }
    public function siteInfo( $key ){
        $this->db->select( '*' );
        $this->db->where( 's_key', "$key" );
        $query  = $this->db->get( 'site_setting' );
        $row    = $query->row();
        return $row;
    }
}