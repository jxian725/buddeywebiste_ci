<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Senangpayreturnurl extends CI_Controller{

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
        //header("content-type:application/json");
    }
    public function index() {
        $createdon      = date("Y-m-d H:i:s");
        $today          = date("Y-m-d");
        $secretkey      = SENANGPAY_SECRETKEY;
        if(isset($_GET['status_id']) && isset($_GET['order_id']) && isset($_GET['msg']) && isset($_GET['transaction_id']) && isset($_GET['hash']))
        {
            $hashed_string = md5($secretkey.urldecode($_GET['status_id']).urldecode($_GET['order_id']).urldecode($_GET['transaction_id']).urldecode($_GET['msg']));
            
            if($hashed_string == urldecode($_GET['hash']))
            {
                if(urldecode($_GET['status_id']) == '1'){
                    $order_id        = urldecode($_GET['order_id']);
                    $getISpayInfo    = $this->Commonapimodel->getNotCompletedSenangpayInfoByORD($order_id);
                    if($getISpayInfo){
                        if($getISpayInfo->paymentAppType == 'scan_payment'){
                            $transaction_id = $_GET['transaction_id'];
                            $data66     = array(
                                                "transaction_id"        => $transaction_id,
                                                "senangpay_msg"         => urldecode($_GET['msg']),
                                                "pay_updated"           => $createdon,
                                                "pay_status"            => 1
                                                );
                            /******UPDATE SENANGPAY SUCCESS********/
                            $update     = $this->Commonapimodel->updateSenangpayPayment($data66, $order_id);
                            echo 'OK';
                            $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:1';
                            $dataString .= "\n";
                            $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                            $wrote       = fwrite($fWrite, $dataString);
                            fclose($fWrite);
                            $this->session->set_flashdata('paySuccess', $transaction_id);
                            redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/success' );
                        }else if($getISpayInfo->paymentAppType == 'request_payment'){
                            $transaction_id = $_GET['transaction_id'];
                            $data77     = array(
                                                "transaction_id"        => $transaction_id,
                                                "senangpay_msg"         => urldecode($_GET['msg']),
                                                "pay_updated"           => $createdon,
                                                "pay_status"            => 1
                                                );
                            /******UPDATE SENANGPAY SUCCESS********/
                            $update     = $this->Commonapimodel->updateSenangpayPayment($data77, $order_id);
                            $data88     = array('status' => 1, 'updatedon' => date("Y-m-d H:i:s"));
                            $this->Commonapimodel->updateRequest($data88, $order_id);
                            echo 'OK';
                            $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:1';
                            $dataString .= "\n";
                            $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                            $wrote       = fwrite($fWrite, $dataString);
                            fclose($fWrite);
                            $this->session->set_flashdata('paySuccess', $transaction_id);
                            redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/success' );
                        }else if($getISpayInfo->paymentAppType == 'space_booking'){
                            $transaction_id = $_GET['transaction_id'];
                            $data777    = array(
                                                "transaction_id"        => $transaction_id,
                                                "senangpay_msg"         => urldecode($_GET['msg']),
                                                "pay_updated"           => $createdon,
                                                "pay_status"            => 1
                                                );
                            /******UPDATE SENANGPAY SUCCESS********/
                            $update   = $this->Commonapimodel->updateSenangpayPayment($data777, $order_id);
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
                            echo 'OK';
                            $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:1';
                            $dataString .= "\n";
                            $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                            $wrote       = fwrite($fWrite, $dataString);
                            fclose($fWrite);
                            $this->session->set_flashdata('paySuccess', $transaction_id);
                            redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/success' );
                        }else{
                            $transaction_id = $_GET['transaction_id'];
                            $serviceID    = $getISpayInfo->serviceID;
                            $serviceInfo  = $this->Serviceapimodel->serviceInfo( $serviceID );
                            $guiderID     = $serviceInfo->service_guider_id;
                            $travellerID  = $serviceInfo->service_traveller_id;
                            $activity_id  = $serviceInfo->activity_id;
                            $service_date = $serviceInfo->service_date;
                            $data22       = array(
                                                "transaction_id"        => $transaction_id,
                                                "senangpay_msg"         => urldecode($_GET['msg']),
                                                "pay_updated"           => $createdon,
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
                            echo 'OK';
                            $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:1';
                            $dataString .= "\n";
                            $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                            $wrote       = fwrite($fWrite, $dataString);
                            fclose($fWrite);
                            $this->session->set_flashdata('paySuccess', $transaction_id);
                            redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/success' );
                        }
                    }
                }else{
                    $order_id        = urldecode($_GET['order_id']);
                    $getISpayInfo    = $this->Commonapimodel->getNotCompletedSenangpayInfoByORD($order_id);
                    if($getISpayInfo){
                        $transaction_id = $_GET['transaction_id'];
                        $data2 = array(
                                        "transaction_id"   => $transaction_id,
                                        "senangpay_msg"    => urldecode($_GET['msg']),
                                        "pay_updated"      => $createdon,
                                        "pay_status"       => 0
                                    );
                        $update2     = $this->Commonapimodel->updateSenangpayPayment($data2, $order_id);
                        if($getISpayInfo->paymentAppType == 'space_booking'){
                            /******UPDATE SPACE BOOKING STATUS********/
                            $data888  = array('host_id' => 0, 'paidStatus' => 0, 'status' => 1);
                            $this->Commonapimodel->updateSpaceBookingStatus($data888, $order_id);
                        }

                        echo 'ERROR';
                        $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:0';
                        $dataString .= "\n";
                        $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                        $wrote       = fwrite($fWrite, $dataString);
                        fclose($fWrite);
                        $this->session->set_flashdata('payError', $transaction_id);
                        redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/failed' );
                    }
                }
            }else{
                $order_id        = urldecode($_GET['order_id']);
                $getISpayInfo    = $this->Commonapimodel->getNotCompletedSenangpayInfoByORD($order_id);
                if($getISpayInfo){
                    $transaction_id = $_GET['transaction_id'];
                    $data2 = array(
                                    "transaction_id"   => $transaction_id,
                                    "senangpay_msg"    => urldecode($_GET['msg']),
                                    "pay_updated"      => $createdon,
                                    "pay_status"       => 0
                                );
                    $update2     = $this->Commonapimodel->updateSenangpayPayment($data2, $order_id);
                    if($getISpayInfo->paymentAppType == 'space_booking'){
                        /******UPDATE SPACE BOOKING STATUS********/
                        $data888  = array('host_id' => 0, 'paidStatus' => 0, 'status' => 1);
                        $this->Commonapimodel->updateSpaceBookingStatus($data888, $order_id);
                    }

                    echo 'ERROR';
                    $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:'.$transaction_id.'|order_id : '.urldecode($_GET['order_id']).'|status:2';
                    $dataString .= "\n";
                    $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                    $wrote       = fwrite($fWrite, $dataString);
                    fclose($fWrite);
                    $this->session->set_flashdata('payError', $transaction_id);
                    redirect( $this->config->item( 'admin_url' ) . 'api/senangpay/failed' );
                }
            }
        }
    }
}
?>