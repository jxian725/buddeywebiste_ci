<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RequerySpaceBookingPayment extends CI_Controller{

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
                $guiderID   = trim( $user_input['guider_id'] );
                $order_id   = $user_input['order_id'];
                $createdon  = date("Y-m-d H:i:s");
                $orderInfo  = $this->Commonapimodel->senangpayOrderInfo( $order_id );
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

                                if($status == 'paid'){
                                    
                                    $data22       = array(
                                                        "transaction_id" => $transaction_id,
                                                        "pay_updated"    => $pay_updated,
                                                        "pay_status"     => 1
                                                        );
                                    /******UPDATE SENANGPAY SUCCESS********/
                                    $update     = $this->Commonapimodel->updateSenangpayPayment($data22, $order_id);
                                    /******UPDATE SENANGPAY FORCED CANCELLED********/
                                    $this->Commonapimodel->updateSenangpaySpacePaymentCancel();
                                    
                                    /******UPDATE SPACE BOOKING SUCCESS********/
                                    $data888  = array(
                                                    'transactionID' => $transaction_id,
                                                    'paidStatus'    => 1,
                                                    'status'        => 3,
                                                    'color'         => '#9c27b0',
                                                    'bookedType'    => 'APP',
                                                    'paidDatetime'  => date("Y-m-d H:i:s")
                                                );
                                    $this->Commonapimodel->updateSpaceBookingStatus($data888, $order_id);
                                    
                                    $res_data   = array(
                                                        "desc"          => 'paid',
                                                        "order_id"      => $order_id,
                                                        "status"        => intval(1)
                                                    );
                                    $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been completed Successfully.', 'result' => 'success', 'data' => $res_data);

                                    //PUSH NOTIFICATION GUIDER
                                    $deviceTokenList  = $this->Guiderapimodel->guiderDeviceTokenList( $guiderID );
                                    if($deviceTokenList){
                                        $push_data  = array(
                                                            'title'          => 'Host',
                                                            'body'           => 'Congrats on the new confirmed booking!',
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
                                    /******UPDATE SPACE BOOKING STATUS********/
                                    $data888  = array('paidStatus' => 0, 'host_id' => 0, 'status' => 1);
                                    $this->Commonapimodel->updateSpaceBookingStatus($data888, $order_id);
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
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Guider ID Cannot be empty';
        } else if ( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ){
            $this->error['warning']    = 'Invalid Guider Id.';
        } else if ( trim($user_input['order_id']) == '' ){
            $this->error['warning']    = 'Order ID Cannot be empty.';
        } else if ( !$orderInfo ){
            $this->error['warning']    = 'Invalid Order ID.';
        } else if ( $orderInfo->guiderID != $user_input['guider_id'] ){
            $this->error['warning']    = 'Mismatched Guider Id.';
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