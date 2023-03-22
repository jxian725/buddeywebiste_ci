<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Guidermodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
    public function guiderInfoByUID($host_uuid){
        
        $this->db->select('guider_id, first_name, last_name, email, phone_number, profile_image, about_me');
        $this->db->where('host_uuid', "$host_uuid" );
        $this->db->where( 'host_uuid !=', '' );
        $query = $this->db->get('guider');
        $row = $query->row();
        return $row;
    }
    public function donation_lists($guider_id, $limit=false, $order=false){

        $this->db->select('senangpay_transaction.*, fullName, email, phoneNumber, message');
        $this->db->from('senangpay_transaction');
        $this->db->join('qrscan_donate_users', 'qrscan_donate_users.paymentID = senangpay_transaction.payment_id');
        $this->db->where( 'pay_status', 1 );
        $this->db->where('paymentAppType', 'scan_payment' );
        $this->db->where('guiderID', $guider_id );
        
        if($order){ $this->db->order_by("sub_total", "desc"); }else{ $this->db->order_by("payment_id", "desc"); }
        if($limit){ $this->db->limit($limit); }else{ $this->db->limit(10); }
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            return $query->result();
        }else {
            return false; 
        }
    }
}