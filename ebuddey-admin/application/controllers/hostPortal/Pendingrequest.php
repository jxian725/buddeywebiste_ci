<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendingrequest extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('host_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Servicemodel');
        $this->load->model('hostPortal/Servicemodel');
        $this->load->model('hostPortal/Pendingrequestmodel');
        $this->load->model('api/pushNotificationmodel');
        host_sessionset();
        error_reporting(E_ALL);
    }
    public function index() {
        $script     = '';
        $data1[ 'host_id' ]         = $this->session->userdata['HOST_ID'];
        $data1[ 'guider_lists' ]    = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ] = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'hostPortal/pendingrequet', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Bookings';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Bookings';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Bookings';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending Bookings</li>';
        $this->template( $data );
    }
    public function pendingtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $host_id        = $this->session->userdata['HOST_ID'];
        $serviceLists   = $this->Pendingrequestmodel->get_datatables($where, $host_id);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                $tripID         = 'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT);
                $guider_charged = $service->guider_charged;
                $totalPassenger = $service->number_of_person;
                $processing_fee = $service->current_processing_fee;
                if($service->service_price_type_id == 1){
                    $subTotal   = $totalPassenger * $guider_charged;
                    $ServiceFees    = (PROCESSING_FEE / 100) * $subTotal;
                    if($ServiceFees < 5){ $ServiceFees = PROCESSING_FEE; }
                }elseif ($service->service_price_type_id == 2) {
                    $subTotal   = $guider_charged;
                    $ServiceFees    = (PROCESSING_FEE / 100) * $subTotal;
                    if($ServiceFees < 5){ $ServiceFees = PROCESSING_FEE; }
                }else{
                    $subTotal    = 0;
                    $ServiceFees = '';
                }
                //$ProcessingFees = ($processing_fee / 100) * $subTotal;
                $totalAmount    = $subTotal;
                $reqacceptbtn   = '';
                $reqcancelbtn   = '';
                $createdon  = date(getDateFormat(), strtotime($service->createdon)) .' '.date(getTimeFormat(), strtotime($service->createdon));
                if ($service->status == 1) {
                    $status         = 'Request Sent by Traveller';
                    $reqacceptbtn   = '<a href="javascript:;" onclick="return requestAction('.$service->service_id.',2);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Accept"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                    $reqcancelbtn   = '<a href="javascript:;" onclick="return requestAction('.$service->service_id.', 3);" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Click to Cancel"><i class="fa fa-remove" aria-hidden="true"></i></a>';
                }elseif($service->status == 2){
                    $status         = 'Request Accept(Pending Payment)';
                }elseif($service->status == 3){ //CANCELLED
                    $deletebtn      = '';
                    $confirmbtn     = '';
                    if($service->cancelled_type == 1){
                        $status     = 'cancelled by traveller';
                    }elseif($service->cancelled_type == 2){
                        $status     = 'cancelled by guider';
                    }elseif($service->cancelled_type == 3){
                        $status     = 'cancelled by traveller before payment';
                    }
                }elseif($service->status == 5){
                    $status         = 'Processing';
                }
                $regionInfo     = $this->Guidermodel->stateInfoByid($service->service_region_id);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $tripID;
                $row[]  = $createdon;
                $row[]  = '<a href="javascript:;">'.$service->travellerName.'</a>';
                $row[]  = date(getDateFormat(), strtotime($service->service_date));
                $row[]  = date(getTimeFormat(), strtotime($service->service_date));
                $row[]  = $totalPassenger;
                $row[]  = $regionName;
                $row[]  = number_format((float)$guider_charged, 2, '.', '');
                $row[]  = number_format((float)$subTotal, 2, '.', '');
                $row[]  = number_format((float)$ServiceFees, 2, '.', '');
                $row[]  = number_format((float)$totalAmount, 2, '.', '');
                $row[]  = $status;
                $row[]  = $reqacceptbtn.$reqcancelbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Pendingrequestmodel->count_all($where, $host_id),
                        "recordsFiltered"   => $this->Pendingrequestmodel->count_filtered($where, $host_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function requestAction(){
        $service_id     = $this->input->post( 'service_id' );
        $status         = $this->input->post( 'status' );
        $serviceInfo    = $this->Pendingrequestmodel->serviceInfo( $service_id );
        $traveller_id   = $serviceInfo->service_traveller_id;
        $service_date   = $serviceInfo->service_date;
        $guiderID       = $serviceInfo->service_guider_id;
        $activity_id    = $serviceInfo->activity_id;
        $today          = date("Y-m-d");
        $createdon      = date("Y-m-d H:i:s");
        if($status == 2){
            if($serviceInfo->service_price_type_id == 3){
                //CREATE NEW JOURNEY
                $data       =   array(
                                'status'         => 4,
                                'cancelled_by'   => '',
                                'cancelled_type' => 0,
                                'view_by_guider' => 'N',
                                'view_by_traveller' => 'N'
                            );
                $result1    = $this->Pendingrequestmodel->updateService( $service_id, $data );
                if($today == $service_date){
                    $jny_status = 2; //ONGOING
                }else{
                    $jny_status = 1; //UPCOMING
                }
                /******GET SERVICE DATE,GUIDER ID TRAVELLER ID FOR NEW JOURNEY********/
                if ( $this->Pendingrequestmodel->journeyInfo( $service_id ) ){
                    $data11   = array('jny_status' => $jny_status);
                    $this->Pendingrequestmodel->updateJourney($data11, $service_id);
                }else{
                    $data2  = array(
                                'jny_traveller_id'  => $traveller_id,
                                'jny_guider_id'     => $guiderID,
                                'jny_service_id'    => $service_id,
                                'jny_activity_id'   => $activity_id,
                                'createdon'         => $createdon,
                                'payment_status'    => 'paid',
                                'jny_transactionID' => 'FREE_BOOKING_'.$service_id,
                                'jny_status'        => $jny_status
                                );
                    $this->Pendingrequestmodel->insertJourney($data2);
                }
                $push_data  = array(
                                    'title'         => 'Guest',
                                    'body'          => 'Congrats your booking confirmed',
                                    'action'        => 'complete_payment',
                                    'notificationId'=> 4,
                                    'sound'         => 'notification',
                                    'icon'          => 'icon'
                                );
                $res_msg = 'Congrats your booking confirmed.';
            }else{
                $data       =   array(
                                'status'         => 2,
                                'cancelled_by'   => '',
                                'cancelled_type' => 0,
                                'view_by_guider' => 'N',
                                'view_by_traveller' => 'N'
                            );
                $result1    = $this->Pendingrequestmodel->updateService( $service_id, $data );
                $push_data  = array(
                                'title'         => 'Guest',
                                'body'          => 'Your request has been accepted. Please proceed to payment to complete booking.',
                                'action'        => 'accept_request',
                                'notificationId'=> 2,
                                'sound'         => 'notification',
                                'icon'          => 'icon'
                              );
                $res_msg = 'Service request accepted successfully.';
            }
            //PUSH NOTIFICATION
            $deviceTokenList  = $this->Pendingrequestmodel->travellerDeviceTokenList( $traveller_id );
            //PUSH NOTIFICATION GUIDER
            if($deviceTokenList){
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
                  $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'T');
                }
                if (!empty($device_tokenI)) {
                    $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'T');
                }
            }
            //END PUSH NOTIFICATION
        }else{
            $guiderInfo     = $this->Guidermodel->guiderInfo( $guider_id );
            //PUSH NOTIFICATION GUIDER
            $regionInfo     = $this->Guidermodel->stateInfoByid($serviceInfo->service_region_id);
            if($regionInfo){
                $regionName = $regionInfo->name;
            }else{
                $regionName = '';
            }
            //PUSH NOTIFICATION
            $deviceTokenList  = $this->Pendingrequestmodel->travellerDeviceTokenList( $traveller_id );
            if($deviceTokenList){
                $push_data      = array(
                                'title'             => 'Guest',
                                'body'              => 'Your request has been cancelled. '.$guiderInfo->first_name.','.$regionName.','.$serviceInfo->service_date.','.$serviceInfo->pickup_time,
                                'action'            => 'cancel_request',
                                'notificationId'    => 3,
                                'sound'             => 'notification',
                                'icon'              => 'icon'
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
                  $this->pushNotificationmodel->android_push_notification($device_tokenA, $push_data, 'T');
                }
                if (!empty($device_tokenI)) {
                    $this->pushNotificationmodel->sendPushNotification_ios($device_tokenI, $push_data, 'T');
                }
            }
            //END PUSH NOTIFICATION

            $data       = array(
                                'status'            => 3,
                                'cancelled_by'      => 'G',
                                'cancelled_type'    => 2,
                                'view_by_guider'    => 'N',
                                'view_by_traveller' => 'N'
                                );
            $result1    = $this->Pendingrequestmodel->updateService( $service_id, $data );
        }
    }
    function template( $data ){
        $this->load->view( 'hostPortal/templatecontent', $data );
    }
}
