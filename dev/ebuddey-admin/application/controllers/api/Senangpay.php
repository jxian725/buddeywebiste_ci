<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senangpay extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('api/Guiderapimodel');
        $this->load->model('api/Travellerapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/pushNotificationmodel');
        $this->load->model('api/MailNotificationmodel');
        $this->load->helper('timezone');
        //error_reporting(E_ALL);
    }
	public function index() {
        
        $input  = json_decode(file_get_contents("php://input"));
        if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->verify_validate( $user_input ) ) {
                $email          = trim( $user_input['email'] );
                $phone_number   = trim( $user_input['phone'] );
                $amount         = trim( $user_input['amount'] );
                $user_input['secretkey']    = SENANGPAY_SECRETKEY;
                $user_input['merchant_id']  = SENANGPAY_MERCHANTID;
                $this->load->view( 'senangpay/paymentform', $user_input );
                $dataString  = date("Y-m-d H:i:s").'-'.'|transaction_id:|order_id :test_3|status:0';
                $dataString .= "\n";
                $fWrite      = fopen(FCPATH . '/uploads/senang.txt','a');
                $wrote       = fwrite($fWrite, $dataString);
                fclose($fWrite);
            } else {
                if( $_SERVER['REQUEST_METHOD'] != 'POST' )  {
                    $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
                } else if ( isset( $this->error['warning'] ) ) {
                    $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
                }
                echo json_encode($result);
            }
        } else {
            $result = array('message' => 'No Input Received', 'result' => 'error');
            echo json_encode($result);
        }
	}
    private function verify_validate( $user_input ) {
        if ( $user_input['email'] == '' ){
            $this->error['warning']    = 'Email Cannot be empty';
        } /*else if( trim($user_input['request_primary_id']) == '' ){
            $this->error['warning']    = 'Request Primary ID Cannot be empty';
        } else if ( !$this->Serviceapimodel->serviceInfo( $user_input['request_primary_id'] ) ){
            $this->error['warning']    = 'Invalid Request Primary Id.';
        }*/ else if ( strlen( $user_input['phone'] ) == '' ) {
            $this->error['warning']    = 'Phone Number Cannot be empty';
        } else if ( trim($user_input['amount']) == '' ){
            $this->error['warning']    = 'Amount Cannot be empty';
        } else if ( trim($user_input['order_id']) == '' ){
            $this->error['warning']    = 'Order Id Cannot be empty';
        } else if ( !$this->Commonapimodel->senangpayOrderInfo( $user_input['order_id'] ) ){
            $this->error['warning']    = 'Order ID not valid.';
        }
        return !$this->error;
    }
    function success(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'senangpay/paymentsuccess', $data );
    }
    function failed(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'senangpay/paymentfailed', $data );
    }
	function template( $data ){
        $this->load->view( 'senangpay/paymentform', $data );
    }
}
