<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InitiateSenangpay extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->initiated_validate( $user_input ) ) {
                $serviceID      = trim( $user_input['request_primary_id'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $serviceID );
                $guider_id      = $serviceInfo->service_guider_id;
                $traveller_id   = $serviceInfo->service_traveller_id;
                $booking_id     = $serviceInfo->booking_request_id;
                $detail         = 'BuddeySenangpay'.$booking_id;
                if($serviceInfo->service_price_type_id == 1){
                    $guider_charged   = $serviceInfo->guider_charged * $serviceInfo->number_of_person;
                }else{
                    $guider_charged   = $serviceInfo->guider_charged;
                }
                //SERVICE AMOUT CALCULATION
                $ServiceFees    = (PROCESSING_FEE / 100) * $guider_charged;
                if($ServiceFees < 2){ $ServiceFees = 02.00; }
                $paidToGuider   = $guider_charged - $ServiceFees;

                $processing_fee = $serviceInfo->current_processing_fee;
                $ProcessingFees = ($processing_fee / 100) * $guider_charged;
                if(PROCESSING_FEE_ENABLED == 'NO'){ $ProcessingFees = 0; }
                $totalAmount    = $guider_charged;

                $order_id       = sprintf("TXN%s%03d", $traveller_id.date("YmdHis"), $serviceID);
                $createdon      = date("Y-m-d H:i:s");
                $data           = array(
                                    "order_id"          => $order_id,
                                    "order_detail"      => $detail,
                                    "guiderID"          => $guider_id,
                                    "serviceID"         => $serviceID,
                                    "travellerID"       => $traveller_id,
                                    "transaction_amount"=> $guider_charged,
                                    "sub_total"         => $totalAmount,
                                    "percentage_amount" => $ProcessingFees,
                                    "service_fees"      => $ServiceFees,
                                    "paid_to_guider"    => $paidToGuider,
                                    "pay_createdon"     => $createdon
                                );
                $insert   = $this->Commonapimodel->InitiateSenangpay($data);
                
                $res_data   = array(
                                "detail"        => $detail,
                                "order_id"      => $order_id,
                                "sub_total"     => floatval($totalAmount)
                            );
                $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been Initiated Successfully.', 'result' => 'success', 'data' => $res_data);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
    function initiated_validate( $user_input ) {
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Request Primary ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Request Primary Id.';
        }
        return !$this->error;
    }
}
?>