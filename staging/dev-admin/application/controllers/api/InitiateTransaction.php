<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InitiateTransaction extends CI_Controller{

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
                $paymentId      = trim( $user_input['payment_id'] );
                $service_charge_percentage = trim( $user_input['service_charge_percentage'] );
                $serviceInfo    = $this->Serviceapimodel->serviceInfo( $serviceID );
                $bankInfo       = $this->Commonapimodel->bankInfo($paymentId);
                $guiderID       = $serviceInfo->service_guider_id;
                $travellerID    = $serviceInfo->service_traveller_id;
                $currency       = trim( $user_input['currency'] );
                $bankName       = trim( $user_input['bank_name'] );
                $transactionAmount = trim( $user_input['total_transaction_amount'] );
                $TransactDescription = trim( $user_input['transaction_description'] );
                $subTotal       = trim( $user_input['sub_total'] );
                $percentageAmount  = trim( $user_input['percentage_amount'] );
                $UserEmail      = trim( $user_input['user_email'] );
                $UserPhoneNo    = trim( $user_input['user_phone_no'] );
                $TransactionRefId = sprintf("TXN%s%03d", $travellerID.date("YmdHis"), $serviceID);
                $createdon        = date("Y-m-d H:i:s");
                $data       = array(
                                "TransactionRefId"  => $TransactionRefId,
                                "guiderID"          => $guiderID,
                                "serviceID"         => $serviceID,
                                "travellerID"       => $travellerID,
                                "transactionAmount" => $transactionAmount,
                                "subTotal"          => $subTotal,
                                "percentageAmount"  => $percentageAmount,
                                "service_charge_percentage" => $service_charge_percentage,
                                "TransactDescription" => $TransactDescription,
                                "currency"          => $currency,
                                "paymentId"         => $paymentId,
                                "bankName"          => $bankName,
                                "UserEmail"         => $UserEmail,
                                "Country"           => COUNTRY,
                                "UserPhoneNo"       => $UserPhoneNo,
                                "ipay_createdon"    => $createdon
                            );
                $insert   = $this->Commonapimodel->InitiatedPayment($data);

                $Status     = 'Initiated';
                $res_data   = array(
                                "transaction_ref_id"=> "". $TransactionRefId ."",
                                "merchant_key"      => "". MERCHANTKEY ."",
                                "merchant_code"     => "". MERCHANTCODE ."",
                                "back_end_post_URL" => "". BACKENDPOSTURL ."",
                                "status"            => "". $Status ."",
                                "status_code"       => 0,
                                "guider_id"         => intval($guiderID),
                                "request_primary_id"=> intval($serviceID),
                                "traveller_id"       => intval($travellerID),
                                "transaction_amount" => floatval($transactionAmount),
                                "sub_total"          => floatval($subTotal),
                                "percentage_amount"  => floatval($percentageAmount),
                                "service_charge_percentage"=> intval($percentageAmount),
                                "transaction_description"  => "". $TransactDescription ."",
                                "currency"          => "". $currency ."",
                                "payment_id"        => intval($paymentId),
                                "bank_name"         => "". $bankName ."",
                                "user_email"        => "". $UserEmail ."",
                                "country"           => "". COUNTRY ."",
                                "user_phone_no"     => "". $UserPhoneNo ."",
                                "location"          => "". LOCATION ."",
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
        $bankInfo   = $this->Commonapimodel->bankInfo($user_input['payment_id']);
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Guider ID Cannot be empty';
        } else if ( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Request Primary ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Request Primary Id.';
        } else if ( trim($user_input['payment_id']) == '' ){
            $this->error['warning']    = 'Payment ID Cannot be empty';
        } else if ( !$this->Commonapimodel->bankInfo( $user_input['payment_id'] ) ){
            $this->error['warning']    = 'Invalid Payment ID.';
        } else if ( trim($user_input['currency']) == '' ){
            $this->error['warning']    = 'Currency Cannot be empty';
        } else if ( trim($user_input['service_charge_percentage']) == '' ){
            $this->error['warning']    = 'Service Charge Percentage Cannot be empty';
        } else if ( trim($user_input['total_transaction_amount']) == '' ){
            $this->error['warning']    = 'Total Transaction Amount Cannot be empty';
        } else if ( trim($user_input['sub_total']) == '' ){
            $this->error['warning']    = 'Subtotal Cannot be empty';
        } else if ( trim($user_input['percentage_amount']) == '' ){
            $this->error['warning']    = 'Percentage amount Cannot be empty';
        } else if ( $this->Commonapimodel->processingPaymentInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Transaction is being processed, Please wait.';
        }
        return !$this->error;
    }
}
?>