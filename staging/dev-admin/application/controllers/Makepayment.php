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
        $ishost     = 1;
        $uuID     = '';
        $hosturl    = $this->uri->segment(3);
        if($hosturl){
            $hosturlsplit = explode("_", $hosturl);
            if(count($hosturlsplit) < 2){
                $ishost     = 0;
            }else{
                $uuID       = $hosturlsplit[1];
                $guiderInfo = $this->Guidermodel->guiderInfoByUID($uuID);
                if(!$guiderInfo){
                    $ishost = 0; 
                }
            }
        }else{
            $ishost     = 0;
        }
        $data[ 'uuID' ]                = $uuID;
        $data[ 'header' ][ 'title' ]   = 'Payment Form';
        if($ishost == 1){
            $this->load->view( 'webview/paymentform', $data );
        }else{
            $this->load->view( 'webview/emptydata', $data );
        }
	}
    public function submit() {
        $submit     = $this->input->post('submit');
        $uuID     = $this->input->post('uuID');
        $guiderInfo = $this->Guidermodel->guiderInfoByUID($uuID);
        if(!$guiderInfo){
            $data[ 'header' ][ 'title' ] = 'Payment Form';
            $this->load->view( 'webview/emptydata', $data );
        }else{
            $hostID     = $guiderInfo->guider_id;
            $amount     = $this->input->post('amount');
            $anonymous  = $this->input->post('anonymous');
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
            if($submit == 'submit' && $hostID && $amount){
                $payinfo['hostID']         = $hostID;
                $payinfo['amount']         = $amount;
                $payinfo['name']           = $name;
                $payinfo['email']          = $email;
                $payinfo['phone']          = $phone;
                $payinfo['order_id']       = sprintf("TTTXN%s%03d", $hostID.date("YmdHis"), $hostID);
                $payinfo['detail']         = 'BuddeySenangpay_QRScan_'.$guiderInfo->first_name;
                $payinfo['secretkey']    = SENANGPAY_SECRETKEY;
                $payinfo['merchant_id']  = SENANGPAY_MERCHANTID;
                $createdon      = date("Y-m-d H:i:s");
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
                $this->load->view( 'senangpay/paymentform', $payinfo );
            }
        }
    }
}
