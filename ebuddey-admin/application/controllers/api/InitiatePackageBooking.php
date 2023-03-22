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

                $user_id        = trim( $user_input['guider_id'] );
                $guiderInfo     = $this->Guiderapimodel->guiderInfoByUuid( $user_id );
                if($guiderInfo && $guiderInfo->status != 1){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Your account is inactive. Contact your administrator.', 'result' => 'error', 'data'=>array('error' => 1));
                    echo json_encode( $result );
                    exit;
                }
                $package_ids    = $user_input['package_id'];
                $amount         = 0;
                $eResMsg        = '';
                $bookedTimeLists= '';

                foreach ($package_ids as $key => $package_id) {
                    $packageInfo = $this->Commonapimodel->packageInfo( $package_id );
                    if($packageInfo){
                        //CHECK SPACE BLACKLIST
                        $this->db->select('talent_id,space_id');
                        $this->db->where('talent_id', $user_id );
                        $this->db->where('space_id', $packageInfo->partner_id );
                        $query = $this->db->get('space_blacklist');
                        if($query->row()){
                            echo json_encode(array('status' => 'error','msg' => 'The partner space not available.'));
                            exit;
                        }
                        //END CHECK SPACE BLACKLIST
                        $amount  += $packageInfo->partnerFees;
                        $start_time = date('H:i', strtotime($packageInfo->start));
                        $end_time   = date('H:i', strtotime($packageInfo->end));

                        if($packageInfo->status == 1){ //lock
                        }else if($packageInfo->status == 4 && $packageInfo->lockedBy == $user_id){ //unlock
                        }else if($packageInfo->status == 4 && $packageInfo->lockedBy != $user_id && $packageInfo->orderID == ''){ //check 5min
                            $convertedTime = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($packageInfo->lockedDateTime)));
                            $currentTime   = date('Y-m-d H:i:s');
                            if($currentTime > $convertedTime){ //allow

                            }else{
                                $eResMsg    = 1;
                                $bookedTimeLists .= $start_time.' - '.$end_time.', ';
                            }
                        }else{
                            $eResMsg    = 2;
                            $bookedTimeLists .= $start_time.' - '.$end_time.', ';
                        }
                    }else{
                        $eResMsg    = 3;
                        $bookedTimeLists .= $start_time.' - '.$end_time.', ';
                    }
                }

                if($eResMsg == 1){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Selected ('.rtrim($bookedTimeLists, ', ').') slot locked.', 'result' => 'error', 'data' => array('error' => 1));
                }else if($eResMsg == 2){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Selected ('.rtrim($bookedTimeLists, ', ').') slot locked..', 'result' => 'error', 'data' => array('error' => 1));
                }else if($eResMsg == 3){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Selected ('.rtrim($bookedTimeLists, ', ').') slot already booked.', 'result' => 'error', 'data' => array('error' => 1));
                }else{
                    $totalAmount    = $amount;
                    $detail         = 'BuddeySenangpay_Space_'.$guiderInfo->first_name;
                    $packageID2     = implode("",$package_ids);
                    $order_id       = sprintf("TXN%s%05d", $user_id.date("YmdHis"), $user_id);
                    $createdon      = date("Y-m-d H:i:s");
                    $packageIDs     = implode(",",$package_ids);
                    if($totalAmount > 0){
                        $data       = array(
                                            "order_id"          => $order_id,
                                            "order_detail"      => $detail,
                                            "guiderID"          => $user_id,
                                            "transaction_amount"=> $totalAmount,
                                            "sub_total"         => $totalAmount,
                                            "paymentAppType"    => 'space_booking',
                                            "packageID"         => $packageIDs,
                                            "pay_createdon"     => $createdon
                                        );
                        $insert   = $this->Commonapimodel->InitiateSenangpay($data);
                        $data2    = array("orderID" => $order_id, "host_id" => $user_id, 'paidStatus' => 2, 'status' => 2 );
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
                                    "host_id"       => $user_id,
                                    "transactionID" => $transactionID,
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
            $this->error['warning']    = 'Talent ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $user_input['guider_id'] ) ) {
            $this->error['warning']    = 'Invalid Talent ID.';
        } else if( count($user_input['package_id']) == 0 ){
            $this->error['warning']    = 'Package ID Cannot be empty';
        }
        return !$this->error;
    }
}
?>