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
    public function form() {
        $script     = '';
        $isRequest  = 1;
        $uuID       = '';
        $requestInfo= array();
        $hosturl    = $this->uri->segment(4);
        if($hosturl){
            $hosturlsplit = explode("_", $hosturl);
            if(count($hosturlsplit) < 2){
                $isRequest     = 0;
            }else{
                $uuID       = $hosturlsplit[1];
                $requestInfo= $this->Commonmodel->requestInfo( $uuID );
                if(!$requestInfo){
                    $isRequest = 0; 
                }
            }
        }else{
            $isRequest  = 0;
        }
        $data[ 'uuID' ]                = $uuID;
        $data[ 'header' ][ 'title' ]   = 'Request Payment Form';
        if($isRequest == 1){
            $data[ 'requestInfo' ]     = $requestInfo;
            $this->load->view( 'webview/request_paymentform', $data );
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
        if($request == 'qrpay'){
            $guiderInfo = $this->Guidermodel->guiderInfoByUID($uuID);
            if(!$guiderInfo){
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
                $hostID     = $guiderInfo->guider_id;
                if($submit == 'submit' && $hostID && $amount){
                    $payinfo['hostID']         = $hostID;
                    $payinfo['amount']         = $amount;
                    $payinfo['name']           = $name;
                    $payinfo['email']          = $email;
                    $payinfo['phone']          = $phone;
                    $payinfo['order_id']       = sprintf("TTTXN%s%03d", $hostID.date("YmdHis"), $hostID);
                    $payinfo['detail']         = 'BuddeySenangpay_QRScan_'.$guiderInfo->first_name;
                    $payinfo['secretkey']      = SENANGPAY_SECRETKEY;
                    $payinfo['merchant_id']    = SENANGPAY_MERCHANTID;
                    $data           = array(
                                        "order_id"          => $payinfo['order_id'],
                                        "order_detail"      => $payinfo['detail'],
                                        "guiderID"          => $hostID,
                                        "transaction_amount"=> $payinfo['amount'],
                                        "sub_total"         => $payinfo['amount'],
                                        "paymentAppType"    => 'scan_payment',
                                        "pay_createdon"     => $createdon
                                    );
                    $payment_id = $this->Commonmodel->InitiateSenangpay($data);
                    $data2      = array(
                                        "paymentID"         => $payment_id,
                                        "fullName"          => $name,
                                        "email"             => $email,
                                        "phoneNumber"       => $phone,
                                        "anonymous"         => $anonymous,
                                        "createdon"         => $createdon
                                    );
                    $this->Commonmodel->insertDonorInfo($data2);
                    $this->load->view( 'webview/submit_paymentform', $payinfo );
                }
            }
        }
        if($request == 'request'){
            $requestInfo= $this->Commonmodel->requestInfo( $uuID );
            if(!$requestInfo){
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
                $requestID      = $requestInfo->activity_request_id;
                if($submit == 'submit' && $requestID && $amount){
                    $payinfo['hostID']         = $requestID;
                    $payinfo['amount']         = $amount;
                    $payinfo['name']           = $name;
                    $payinfo['email']          = $email;
                    $payinfo['phone']          = $phone;
                    $payinfo['order_id']       = sprintf("TTTXN%s%03d", $requestID.date("YmdHis"), $requestID);
                    $payinfo['detail']         = 'BuddeySenangpay_Request_'.$requestInfo->full_name;
                    $payinfo['secretkey']      = SENANGPAY_SECRETKEY;
                    $payinfo['merchant_id']    = SENANGPAY_MERCHANTID;
                    $data3          = array(
                                        "order_id"          => $payinfo['order_id'],
                                        "order_detail"      => $payinfo['detail'],
                                        "requestID"         => $requestID,
                                        "transaction_amount"=> $payinfo['amount'],
                                        "sub_total"         => $payinfo['amount'],
                                        "paymentAppType"    => 'request_payment',
                                        "pay_createdon"     => $createdon
                                    );
                    $payment_id = $this->Commonmodel->InitiateSenangpay($data3);
                    $data4      = array(
                                        "senangpay_orderID" => $payinfo['order_id'],
                                        "updatedon"         => $createdon
                                    );
                    $this->Commonmodel->updateRequest($requestID, $data4);
                    $this->load->view( 'webview/submit_paymentform', $payinfo );
                }
            }
        }
    }
    function success(){
        $data[ 'header' ][ 'title' ]    = '';
        $this->load->view( 'webview/paymentsuccess', $data );
    }
    function failed(){
        $data[ 'header' ][ 'title' ]    = '';
        $this->load->view( 'webview/paymentfailed', $data );
    }
}
