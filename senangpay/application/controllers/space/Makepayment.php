<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Makepayment extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Commonmodel');
        error_reporting(E_ALL);
    }
    public function index() {
    }
    //REQUEST PAYMENT
    public function form() {
        $script    = '';
        $isRequest = 1;
        $uuID      = '';
        $spaceInfo = array();
        $spaceurl  = $this->uri->segment(4);
        if($spaceurl){
            $spaceurlsplit = explode("_", $spaceurl);
            if(count($spaceurlsplit) < 2){
                $isRequest  = 0;
            }else{
                $uuID      = $spaceurlsplit[1];
                $spaceInfo = $this->Commonmodel->spaceInfo( $uuID );
                if(!$spaceInfo){
                    $isRequest = 0; 
                }
            }
        }else{
            $isRequest  = 0;
        }
        $data[ 'uuID' ] = $uuID;
        $data[ 'header' ][ 'title' ] = 'Space Booking Payment Form';
        if($isRequest == 1){
            if($spaceInfo->paidStatus==1){
                $data['msg'] = 'Space was already booked';
                $this->load->view( 'webview/info', $data );
            }else{
                $data[ 'spaceInfo' ] = $spaceInfo;
                if($spaceInfo->partnerFees){
                    $this->load->view( 'webview/space_booking_payment_form', $data );
                }else{
                    $this->load->view( 'webview/space_booking_free_form', $data );
                }
            }
        }else{
            $this->load->view( 'webview/emptydata', $data );
        }
    }
    public function submit() {
        $submit     = $this->input->post('submit');
        $uuID       = $this->input->post('uuID');
        $amount     = $this->input->post('amount');
        $anonymous  = $this->input->post('anonymous');
        $createdon  = date("Y-m-d H:i:s");
        $request    = $this->input->post('form_request');
        if($request == 'space'){
            $spaceInfo= $this->Commonmodel->spaceInfo( $uuID );
            if(!$spaceInfo){
                $data[ 'header' ][ 'title' ] = 'Payment Form';
                $this->load->view( 'webview/emptydata', $data );
            }else{
                if($anonymous == 1){
                    $name       = 'Vinoth';
                    $email      = 'jvvinoth2@gmail.com';
                    $phone      = '147120490';
                }else{
                    $name       = $this->input->post('fullname');
                    $email      = $this->input->post('email');
                    $phone      = $this->input->post('phone');
                    $anonymous  = 0;
                }
                $spaceID      = $spaceInfo->id;
                $requestID    = $spaceInfo->host_id;
                if($submit == 'submit' && $spaceID && $amount){
                    $payinfo['spaceID']        = $spaceID;
                    $payinfo['hostID']         = $requestID;
                    $payinfo['amount']         = $amount;
                    $payinfo['name']           = $name;
                    $payinfo['email']          = $email;
                    $payinfo['phone']          = $phone;
                    $payinfo['order_id']       = sprintf("TTTXN%s%03d", $requestID.date("YmdHis"), $requestID);
                    $payinfo['detail']         = 'BuddeySenangpay_Request_'.$spaceInfo->city_id;
                    $payinfo['secretkey']      = SENANGPAY_SECRETKEY;
                    $payinfo['merchant_id']    = SENANGPAY_MERCHANTID;
                    $data3          = array(
                                        "order_id"          => $payinfo['order_id'],
                                        "order_detail"      => $payinfo['detail'],
                                        "requestID"         => $spaceID,
                                        "transaction_amount"=> $payinfo['amount'],
                                        "sub_total"         => $payinfo['amount'],
                                        "paymentAppType"    => 'space_booking',
                                        "pay_createdon"     => $createdon
                                    );
                    $payment_id = $this->Commonmodel->InitiateSenangpay($data3);
                    
                    $this->load->view( 'webview/submit_paymentform', $payinfo );
                }
            }
        }
    }
    public function freeBooking() {
        $data[ 'header' ][ 'title' ] = '';
        $uuID       = $this->input->post('uuID');
        $amount     = $this->input->post('amount');
        $createdon  = date("Y-m-d H:i:s");
        $request    = $this->input->post('form_request');
        if($request == 'space'){
            $spaceInfo= $this->Commonmodel->spaceInfo( $uuID );
            if(!$spaceInfo){
                $data[ 'header' ][ 'title' ] = 'Payment Form';
                $this->load->view( 'webview/emptydata', $data );
            }else{
                if($spaceInfo->paidStatus != 1){
                    $transactionID = 'FB'.str_replace(".","",microtime(true)).rand(000,999);
                    $data4    = array(
                                    "paidStatus" => 1,
                                    'transactionID' => $transactionID,
                                    "paidDatetime"  => $createdon
                                    );
                    $this->Commonmodel->updateSpaceBookingStatus($uuID, $data4);
                }else{ 
                    $transactionID = $spaceInfo->transactionID;
                }
                $this->session->set_flashdata('paySuccess', $transactionID);
                $this->load->view( 'webview/paymentsuccess', $data );
            }
        }
    }
    function success(){
        $data[ 'header' ][ 'title' ] = '';
        $this->load->view( 'webview/paymentsuccess', $data );
    }
    function failed(){
        $data[ 'header' ][ 'title' ]    = '';
        $this->load->view( 'webview/paymentfailed', $data );
    }
}
