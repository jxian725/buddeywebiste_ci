<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Commonmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    function InitiateSenangpay( $data ){
        $table = $this->db->dbprefix( 'senangpay_transaction' );
        $this->db->insert( $table, $data );
        $payment_id = $this->db->insert_id();
        return $payment_id;
    }
    function insertDonorInfo( $data ){
        $table = $this->db->dbprefix( 'qrscan_donate_users' );
        $this->db->insert( $table, $data );
    }
    public function requestInfo($request_uuid){
        
        $this->db->select('*');
        $this->db->where('request_uuid', "$request_uuid" );
        $this->db->where( 'request_uuid !=', '' );
        $this->db->where( 'payment_type', 2 );
        $query = $this->db->get('activity_request');
        $row = $query->row();
        return $row;
    }
    public function updateRequest($request_id, $data){
        
        $table  = $this->db->dbprefix('activity_request');
        $this->db->where( 'activity_request_id', $request_id );
        $this->db->update( $table, $data );
        return true;
    }
    //GET SPACE INFO BY UUID
    public function spaceInfo($space_uuid){
        
        $this->db->select('*');
        $this->db->where('space_uuid', "$space_uuid" );
        $this->db->where( 'space_uuid !=', '' );
        $this->db->where( 'status', 1 );
        $query = $this->db->get('events');
        $row = $query->row();
        return $row;
    }
    public function updateSpaceBookingStatus($space_uuid, $data){
        
        $table  = $this->db->dbprefix('events');
        $this->db->where('space_uuid', "$space_uuid" );
        $this->db->where( 'space_uuid !=', '' );
        $this->db->update( $table, $data );
        return true;
    }
}