<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequeryPayment extends CI_Controller{

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
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->requery_validate( $user_input ) ) {
                $serviceID      = trim( $user_input['request_primary_id'] );
                $order_id       = $user_input['order_id'];
                $createdon      = date("Y-m-d H:i:s");
                $orderInfo      = $this->Commonapimodel->senangpayOrderInfo( $order_id );
                if($orderInfo->pay_status == 0){
                    $res_data   = array(
                                "desc"          => 'failed',
                                "order_id"      => $order_id,
                                "status"        => intval(0)
                            );
                    $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction has been failed.', 'result' => 'success', 'data' => $res_data);
                }elseif ($orderInfo->pay_status == 1) {
                    $res_data   = array(
                                        "desc"          => 'paid',
                                        "order_id"      => $order_id,
                                        "status"        => intval(1)
                                    );
                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been completed Successfully.', 'result' => 'success', 'data' => $res_data);
                }elseif ($orderInfo->pay_status == 3) {
                    $res_data   = array(
                                "desc"          => 'cancelled',
                                "order_id"      => $order_id,
                                "status"        => intval(3)
                            );
                    $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction has been cancelled.', 'result' => 'success', 'data' => $res_data);
                }else{
                    $serviceInfo    = $this->Serviceapimodel->serviceInfo( $serviceID );
                    $secretkey      = SENANGPAY_SECRETKEY;
                    $merchant_id    = SENANGPAY_MERCHANTID;
                    $hashed         = md5($merchant_id.$secretkey.$order_id);
                    $getURL         = 'https://app.senangpay.my/apiv1/query_order_status?hash='.$hashed.'&merchant_id='.$merchant_id.'&order_id='.$order_id;
                    $getResponse    = $this->httpGet($getURL);
                    $response       = json_decode($getResponse);
                    if($response->status == 1){
                        if(!$response->data){
                            $desc   = 'Transaction not started for this Order Id.';
                            $res_data   = array(
                                        "desc"          => $desc,
                                        "order_id"      => $order_id,
                                        "status"        => intval(2)
                                    );
                            $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction not started for this Order Id.', 'result' => 'success', 'data' => $res_data);
                        }else{
                            $payment_info = $response->data[0]->payment_info;
                            if($payment_info){
                                $transaction_id     = $payment_info->transaction_reference;
                                $transaction_date   = $payment_info->transaction_date;
                                $pay_updated        = date('Y-m-d',$transaction_date);
                                $payment_mode       = $payment_info->payment_mode;
                                $status             = $payment_info->status;

                                $guiderID     = $serviceInfo->service_guider_id;
                                $travellerID  = $serviceInfo->service_traveller_id;
                                $travellerID  = $serviceInfo->service_traveller_id;
                                $activity_id  = $serviceInfo->activity_id;
                                if($status == 'paid'){
                                    
                                    $data22       = array(
                                                        "transaction_id"        => $transaction_id,
                                                        "pay_updated"           => $pay_updated,
                                                        "pay_status"            => 1
                                                        );
                                    /******UPDATE SENANGPAY SUCCESS********/
                                    $update     = $this->Commonapimodel->updateSenangpayPayment($data22, $order_id);
                                    /******UPDATE SENANGPAY FORCED CANCELLED********/
                                    $this->Commonapimodel->updateSenangpayPaymentCancel($serviceID);
                                    /******UPDATE SERVICE PAYMENT COMPLETED********/
                                    $data3      = array('status' => 4,'transactionID' => $transaction_id);
                                    $this->Serviceapimodel->updateServiceRequest($data3, $serviceID);
                                    //INSERT/UPDATE JOURNEY
                                    if($today == $service_date){
                                        $jny_status = 2; //ONGOING
                                    }else{
                                        $jny_status = 1; //UPCOMING
                                    }
                                    /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                                    if ( $this->Serviceapimodel->journeyInfo( $serviceID ) ){
                                        $data11   = array('jny_status' => $jny_status);
                                        $this->Serviceapimodel->updateJourney($data11, $serviceID);
                                    }else{
                                        $data2  = array(
                                                    'jny_traveller_id'  => $travellerID,
                                                    'jny_guider_id'     => $guiderID,
                                                    'jny_service_id'    => $serviceID,
                                                    'jny_activity_id'   => $activity_id,
                                                    'createdon'         => $createdon,
                                                    'payment_status'    => 'paid',
                                                    'jny_transactionID' => $transaction_id,
                                                    'jny_status'        => $jny_status
                                                    );
                                        $this->Serviceapimodel->insertJourney($data2);
                                    }
                                    $res_data   = array(
                                                        "desc"          => 'paid',
                                                        "order_id"      => $order_id,
                                                        "status"        => intval(1)
                                                    );
                                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been completed Successfully.', 'result' => 'success', 'data' => $res_data);

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
                                    $travellerinfo  = $this->Travellerapimodel->travellerInfoByUuid( $travellerID );
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
                        
                                }elseif($status == 'failed'){
                                    $data2 = array(
                                                    "transaction_id"   => $transaction_id,
                                                    "pay_updated"      => $createdon,
                                                    "pay_status"       => 0
                                                );
                                    $update2     = $this->Commonapimodel->updateSenangpayPayment($data2, $order_id);
                                    $desc = $response->msg;
                                    $res_data   = array(
                                                "desc"          => 'failed',
                                                "order_id"      => $order_id,
                                                "status"        => intval(0)
                                            );
                                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction has been failed.', 'result' => 'success', 'data' => $res_data);
                                }else{
                                    $res_data   = array(
                                                            "desc"          => 'proccessing',
                                                            "order_id"      => $order_id,
                                                            "status"        => intval(4)
                                                        );
                                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been processing.', 'result' => 'success', 'data' => $res_data);
                                }
                            }else{
                                $desc       = 'Transaction not started for this Order Id.';
                                $res_data   = array(
                                                "desc"       => $desc,
                                                "order_id"   => $order_id,
                                                "status"     => intval(2)
                                               );
                                $result     = array('response_code' => SUCCESS_CODE, 'response_description' => 'Transaction not started for this Order Id.', 'result' => 'success', 'data' => $res_data);
                            }
                        }
                    }else{
                        //HASH ERROR
                        $desc = $response->msg;
                        $res_data   = array(
                                    "desc"          => $desc,
                                    "order_id"      => $order_id,
                                    "status"        => intval(5)
                                );
                        $result = array('response_code' => SUCCESS_CODE, 'response_description' => $desc, 'result' => 'success', 'data' => $res_data);
                    }
                }
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
    function requery_validate( $user_input ) {
        $orderInfo  = $this->Commonapimodel->senangpayOrderInfo( $user_input['order_id'] );
        if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Request Primary ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Request Primary Id.';
        } else if ( trim($user_input['order_id']) == '' ){
            $this->error['warning']    = 'Order ID Cannot be empty.';
        } else if ( !$orderInfo ){
            $this->error['warning']    = 'Invalid Order ID.';
        } else if ( $orderInfo->serviceID != $user_input['request_primary_id'] ){
            $this->error['warning']    = 'Mismatched Request Primary Id.';
        }
        return !$this->error;
    }
    function httpGet($url)
    {
        $ch = curl_init();  
     
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //curl_setopt($ch,CURLOPT_HEADER, false); 
        $output = curl_exec($ch);
        //$result = json_decode($response);
        curl_close($ch);
        return $output;
    }
}
?>