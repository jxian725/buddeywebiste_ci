<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InitiatePackageBooking extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Guiderapimodel');
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
                $isSpaceBooked  = 0;
                $isValidSpaceid = 0;
                $guider_id      = trim( $user_input['guider_id'] );
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $guider_id );
                $package_ids    = $user_input['package_id'];
                $amount         = 0;
                foreach ($package_ids as $key => $id) {
                    $packageInfo = $this->Commonapimodel->packageInfo( $id );
                    if($packageInfo){
                        $amount  += $packageInfo->partnerFees;
                        if($packageInfo->paidStatus != 0){
                            $isSpaceBooked = 1;
                        }
                    }else{
                        $isValidSpaceid = 1;
                    }
                }
                if($isSpaceBooked == 1){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Package was already booking by other host.', 'result' => 'error', 'data'=>array('error' => 1));
                }else if($isValidSpaceid == 1){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Invalid Package id', 'result' => 'error', 'data'=>array('error' => 1));
                }else{
                    $totalAmount    = $amount;
                    $detail         = 'BuddeySenangpay_Space_'.$guiderInfo->first_name;
                    $packageID2     = implode("",$package_ids);
                    $order_id       = sprintf("TXN%s%05d", $guider_id.date("YmdHis"), $packageID2);
                    $createdon      = date("Y-m-d H:i:s");
                    $packageIDs     = implode(",",$package_ids);
                    if($totalAmount > 0){
                        $data       = array(
                                            "order_id"          => $order_id,
                                            "order_detail"      => $detail,
                                            "guiderID"          => $guider_id,
                                            "transaction_amount"=> $totalAmount,
                                            "sub_total"         => $totalAmount,
                                            "paymentAppType"    => 'space_booking',
                                            "packageID"         => $packageIDs,
                                            "pay_createdon"     => $createdon
                                        );
                        $insert   = $this->Commonapimodel->InitiateSenangpay($data);
                        $data2    = array("orderID" => $order_id, "host_id" => $guider_id, 'paidStatus' => 2 );
                        $update   = $this->Commonapimodel->updateSpaceBookingOrder($data2, $package_ids);
                        
                        $res_data   = array(
                                        "detail"        => $detail,
                                        "order_id"      => $order_id,
                                        "sub_total"     => floatval($totalAmount)
                                    );
                        $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Payment has been Initiated Successfully.', 'result' => 'success', 'is_senangpay' => 1, 'data' => $res_data);
                    }else{
                        $transactionID = 'FB'.str_replace(".","",microtime(true)).rand(000,999);
                        $orderID = 'FB'.$order_id;
                        $data2  = array(
                                    "orderID"       => $orderID,
                                    "host_id"       => $guider_id,
                                    "transactionID" => $transactionID,
                                    "color"         => '#9c27b0',
                                    "bookedType"    => 'APP',
                                    "paidDatetime"  => $createdon,
                                    "paidStatus"    => 1,
                                    "status"        => 3
                                );
                        $update   = $this->Commonapimodel->updateSpaceBookingOrder($data2, $package_ids);
                        $res_data   = array(
                                        "order_id"  => $orderID,
                                        "booking_id"=> $transactionID,
                                        "sub_total" => floatval($totalAmount)
                                    );
                        $result = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been booked Successfully.', 'result' => 'success', 'is_senangpay' => 0, 'data' => $res_data);
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
    function initiated_validate( $user_input ) {
        
        if( trim($user_input['guider_id']) == '' ){
            $this->error['warning']    = 'Guider ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Invalid Guider ID.';
        } else if( count($user_input['package_id']) == 0 ){
            $this->error['warning']    = 'Package ID Cannot be empty';
        }
        return !$this->error;
    }
}
?>