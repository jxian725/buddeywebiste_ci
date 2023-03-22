<?php  
if ( ! defined( 'BASEPATH' )) exit( 'No direct script access allowed' );
class Guiderpayoutmodel extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function guiderSettledPayment($guider_id){
		$this->db->select('SUM(senangpay_transaction.sub_total) as guiderPayment');
		$this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
	    $this->db->where('jny_guider_id', $guider_id );
	    $this->db->where('guider_payout', 'Y' );
	    $this->db->where('jny_status',3,FALSE);
	    $query 		= $this->db->get( 'journey_list' );
	    $rowcount 	= $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row->guiderPayment;
	    } else {
	    	return 0;
	    }
	}
	public function guiderPendingPayoutAmt($guider_id){
		$this->db->select('SUM(senangpay_transaction.sub_total) as payoutAmt');
		$this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
	    $this->db->where('jny_guider_id', $guider_id );
	    $this->db->where('guider_payout', 'N' );
	    $this->db->where('jny_status',3,FALSE);
	    $query 		= $this->db->get( 'journey_list' );
	    $rowcount 	= $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row->payoutAmt;
	    } else {
	    	return 0;
	    }
	}
	public function guiderPendingPercentageAmt($guider_id){
		$this->db->select('SUM(senangpay_transaction.percentage_amount) as percentageAmt');
		$this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
	    $this->db->where('jny_guider_id', $guider_id );
	    $this->db->where('guider_payout', 'N' );
	    $this->db->where('jny_status',3,FALSE);
	    $query 		= $this->db->get( 'journey_list' );
	    $rowcount 	= $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row->percentageAmt;
	    } else {
	    	return 0;
	    }
	}
	public function guiderPendingTransactionAmt($guider_id){
		$this->db->select('SUM(senangpay_transaction.percentage_amount) as transactionAmt');
		$this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
	    $this->db->where('jny_guider_id', $guider_id );
	    $this->db->where('guider_payout', 'N' );
	    $this->db->where('jny_status',3,FALSE);
	    $query 		= $this->db->get( 'journey_list' );
	    $rowcount 	= $query->num_rows();
	    if( $rowcount > 0 ) {
	    	$row = $query->row();
	    	return $row->transactionAmt;
	    } else {
	    	return 0;
	    }
	}
	public function guiderPendingPaymentLists($guider_id){

		$this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,traveller.first_name AS travellerName,
                    senangpay_transaction.sub_total as guiderPayment,percentage_amount, transaction_amount
                    ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id AND (service_price_type_id != 3)');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('jny_guider_id', $guider_id );
        $this->db->where('guider_payout', 'N' );
        $this->db->where('jny_status',3,FALSE);
        $query = $this->db->get();
        return $query->result();
	}
	public function updateExcutePayout($guider_id, $payoutAmt, $transactionAmt, $percentageAmt, $totalTrip){
		$BTdata 	= array();
		$createdon  = date("Y-m-d H:i:s");

		$this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status');
        $this->db->from('journey_list');
        $this->db->where('jny_guider_id', $guider_id );
        $this->db->where('guider_payout', 'N' );
        $this->db->where('jny_status',3,FALSE);
        $query = $this->db->get();
        if( $query->num_rows() > 0 ) {
            $serviceLists   = $query->result();
            foreach ( $serviceLists as $service ){
		        $BTdata[] = $service->journey_id;
            }
            //UPDATE
            $data2  = array( 'guider_payout' => 'Y' );
	        $table2 = $this->db->dbprefix('journey_list');
	        $this->db->where( 'jny_guider_id', $guider_id );
	        $this->db->where_in( 'journey_id', $BTdata );
	        $this->db->update( $table2, $data2 );
            //ADD
            $BTdataS    = serialize($BTdata);
            $payoutBy 	= $this->session->userdata( 'USER_ID' );
            $count_payout = $this->payout_total_count();
            $pay_id  	= 'PAY-'.str_pad($count_payout+1, 5, '0', STR_PAD_LEFT);
            $BTdata3    = array(
                                'pay_id'        => $pay_id, 'guiderID' => $guider_id,
                                'journeyList' 	=> $BTdataS, 'payoutBy' => $payoutBy,
                                'payoutAmount' 	=> $payoutAmt, 'totalTrip' => $totalTrip,
                                'totalAmount' 	=> $transactionAmt, 'percentageAmount' => $percentageAmt,
                                'createdon' 	=> $createdon
                                );
            $table3 = $this->db->dbprefix( 'payout_transaction' );
        	$this->db->insert( $table3, $BTdata3 );
        }
	}
	function payout_total_count() {
		$this->db->select('*');
	    $query = $this->db->get( 'payout_transaction' );
	    $rowcount = $query->num_rows();
	    if( $rowcount > 0 ) {
	    	return $rowcount;
	    } else {
	    	return 0;
	    }
	}
	public function guiderCompletedPaymentLists($guider_id){

		$this->db->select('journey_id,jny_service_id,jny_traveller_id,jny_guider_id,jny_status,journey_list.createdon AS jny_createdon,
                    service_list.*,
                    guider.first_name AS guiderName,traveller.first_name AS travellerName,
                    senangpay_transaction.sub_total as guiderPayment,percentage_amount
                    ');
        $this->db->from('journey_list');
        $this->db->join('senangpay_transaction', 'senangpay_transaction.transaction_id = journey_list.jny_transactionID','left');
        $this->db->join('service_list', 'service_list.service_id = journey_list.jny_service_id');
        $this->db->join('guider', 'guider.guider_id = journey_list.jny_guider_id', 'left');
        $this->db->join('traveller', 'traveller.traveller_id = journey_list.jny_traveller_id', 'left');
        $this->db->where('jny_guider_id', $guider_id );
        $this->db->where('guider_payout', 'Y' );
        $this->db->where('jny_status',3,FALSE);
        $query = $this->db->get();
        return $query->result();
	}
	function lastPayoutInfo11($guiderID){
        $userdetails = $this->db->query("SELECT * 
        								FROM payout_transaction 
        								WHERE guiderID = '$guiderID'
        								ORDER BY pt_id DESC");
    	return $userdetails->row();
    }
    function lastPayoutInfo($guiderID,$createdon=false){
    	$this->db->select('*');
	    $this->db->where('guiderID', $guiderID );
	    if($createdon){ $this->db->where('createdon <', $createdon );}
	    $query = $this->db->get('payout_transaction');
		$row = $query->row();
	    return $row;
    }
	public function guiderInfo($guider_id){
		
		$this->db->select('*');
	    $this->db->where('guider_id', $guider_id );
	    $query = $this->db->get('journey_list');
		$row = $query->row();
	    return $row;
	}
}