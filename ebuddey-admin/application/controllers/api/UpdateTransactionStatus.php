<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateTransactionStatus extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->payment_validate( $user_input ) ) {
                $createdon          = date("Y-m-d H:i:s");
                $today              = date("Y-m-d");
                $serviceID          = trim( $user_input['request_primary_id'] );
                $serviceInfo        = $this->Serviceapimodel->serviceInfo( $serviceID );
                $guiderID           = $serviceInfo->service_guider_id;
                $travellerID        = $serviceInfo->service_traveller_id;
                $service_date       = $serviceInfo->service_date;
                $Status             = trim( $user_input['status'] ); //0-Initiated, 1 - Success, 2 - Cancelled, 3 - Failure, 4- pending
                if($paymentId){
                    $bankInfo       = $this->Commonapimodel->bankInfo($paymentId);
                    $bankName       = $bankInfo->BankName;
                }
                $guiderInfo     = $this->Travellerapimodel->guiderInfoById( $guiderID );
                $travellerinfo  = $this->Travellerapimodel->travellerInfoByUuid( $travellerID );
                if($Status == 1){
                    //PUSH NOTIFICATION TRAVELLER
                    $deviceTokenList1  = $this->Travellerapimodel->travellerDeviceTokenList( $travellerID );
                    if($deviceTokenList1){
                        $push_data1 = array(
                                        'title'             => 'Guest',
                                        'body'              => 'Congrats your booking confirmed',
                                        'action'            => 'complete_payment',
                                        'notificationId'    => 4,
                                        'sound'             => 'notification',
                                        'icon'              => 'icon'
                                      );
                        $device_tokenA1 = array();
                        $device_tokenI1 = array();
                        foreach ($deviceTokenList1 as $tokenList1) {
                            $device_token1   = trim($tokenList1->device_token);
                            $device_type1    = trim($tokenList1->device_type);
                            if ($device_type1 == 3) {
                                if (strlen($device_token1) > 10){
                                    $device_tokenA1[] = $device_token1;
                                }
                            }else if ($device_type1 == 2) {
                                if (strlen($device_token1) > 10){
                                    $device_tokenI1[] = $device_token1;
                                }
                            }
                        }
                        if (!empty($device_tokenA1)) {
                          $this->pushNotificationmodel->android_push_notification($device_tokenA1, $push_data1, 'T');
                        }
                        if (!empty($device_tokenI1)) {
                            $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI1, $push_data1, 'T');
                        }
                    }
                }
                //END TRAVELLER PUSH NOTIFICATION
                //PUSH NOTIFICATION GUIDER
                $deviceTokenList  = $this->Guiderapimodel->guiderDeviceTokenList( $guiderID );
                if($deviceTokenList){
                    $push_data  = array(
                                        'title'          => 'Host',
                                        'body'           => 'Congrats on the new confirmed booking! '.$travellerinfo->first_name.','.$travellerinfo->country_name.','.$serviceInfo->service_date.','.$serviceInfo->pickup_time,
                                        'action'         => 'complete_payment',
                                        'notificationId' => 4,
                                        'sound'          => 'notification',
                                        'icon'           => 'icon'
                                        );
                    $device_tokenA = array();
                    $device_tokenI = array();
                    foreach ($deviceTokenList as $tokenList) {
                        $device_token   = trim($tokenList->device_token);
                        $device_type    = trim($tokenList->device_type);
                        if ($device_type == 3) {
                            if (strlen($device_token) > 10){
                                $device_tokenA[] = $device_token;
                            }
                        }else if ($device_type == 2) {
                            if (strlen($device_token) > 10){
                                $device_tokenI[] = $device_token;
                            }
                        }
                    }
                    if (!empty($device_tokenA)) {
                        $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'G');
                    }
                    if (!empty($device_tokenI)) {
                        $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'G');
                    }
                }
                //END GUIDER PUSH NOTIFICATION
                $paymentInfo    = $this->Commonapimodel->getPaymentInfoByTRI( $user_input['transaction_ref_id'] );
                $payStatus      = $paymentInfo->Status;
                $res_data   = array( "transaction_ref_id"  => $TransactionRefId, 'status' => intval($payStatus) );
                $result     = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction status has been updated Successfully.', 'result' => 'success', 'data' => $res_data);
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
    function payment_validate( $user_input ) {
        $bankInfo   = $this->Commonapimodel->bankInfo($user_input['payment_id']);
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Talent ID Cannot be empty';
        } else if ( trim($user_input['traveller_id']) == '' ){
            $this->error['warning']    = 'Traveller ID Cannot be empty';
        } else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Service ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Service ID.';
        } else if ( trim($user_input['transaction_ref_id']) == '' ){
            $this->error['warning']    = 'Transaction Reference ID Cannot be empty';
        } else if ( trim($user_input['transaction_amount']) == '' ){
            $this->error['warning']    = 'Transaction Amount Cannot be empty';
        } else if ( trim($user_input['sub_total']) == '' ){
            $this->error['warning']    = 'Subtotal Cannot be empty';
        } else if ( trim($user_input['percentage_amount']) == '' ){
            $this->error['warning']    = 'Percentage amount Cannot be empty';
        } else if ( !$this->Commonapimodel->getPaymentInfoByTRI( $user_input['transaction_ref_id'] ) ){
            $this->error['warning']    = 'No Payment Initiated against this TransactionRefId.';
        } else if ( trim($user_input['status']) == '' ){
            $this->error['warning']    = 'Status Cannot be empty';
        }
        return !$this->error;
    }
}
?>