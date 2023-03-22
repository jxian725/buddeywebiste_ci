<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LockPackage extends CI_Controller{

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
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->lock_validate( $user_input ) ) {
                $user_id    = trim( $user_input['user_id'] );
                $guiderInfo = $this->Guiderapimodel->guiderInfoByUuid( $user_id );
                if($guiderInfo && $guiderInfo->status != 1){
                    $result = array('response_code' => ERROR_CODE, 'response_description' => 'Your account is inactive. Contact your administrator.', 'result' => 'error', 'data'=>array('error' => 1));
                    echo json_encode( $result );
                    exit;
                }
                $packageIDs = $user_input['package_id'];
                $sResMsg    = '';
                $eResMsg    = '';
                $bookedTimeLists = '';
                $data       = array();
                foreach ($packageIDs as $key => $package_id) {
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

                        $start_time = date('H:i', strtotime($packageInfo->start));
                        $end_time   = date('H:i', strtotime($packageInfo->end));

                        if($packageInfo->status == 1){ //lock
                        }else if($packageInfo->status == 4 && $packageInfo->lockedBy == $user_id){ //unlock
                        }else if($packageInfo->status == 4 && $packageInfo->lockedBy != $user_id){ //check 5min
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
                if($eResMsg == ''){
                    foreach ($packageIDs as $key => $package_id) {
                        $packageInfo = $this->Commonapimodel->packageInfo( $package_id );
                        if($packageInfo){

                            if($packageInfo->status == 1){ //lock

                                $data    = array("lockedBy" => $user_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                                $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                                $sResMsg = 1;
                            }else if($packageInfo->status == 4 && $packageInfo->lockedBy == $user_id){ //unlock

                                $data    = array("lockedBy" => 0, "lockedDateTime" => NULL, 'status' => 1 );
                                $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                                $sResMsg = 2;
                            }else if($packageInfo->status == 4 && $packageInfo->lockedBy != $user_id){ //check 5min
                                $convertedTime = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($packageInfo->lockedDateTime)));
                                $currentTime   = date('Y-m-d H:i:s');
                                if($currentTime > $convertedTime){ //allow
                                    $data    = array("lockedBy" => $user_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                                    $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                                    $sResMsg = 3;
                                }
                            }
                        }
                    }
                    //START SUCCESS RESPONSE
                    if($sResMsg == 1){
                        $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been locked Successfully.', 'result' => 'success', 'data' => array());
                    }else if($sResMsg == 2){
                        $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been unlocked Successfully.', 'result' => 'success', 'data' => array());
                    }else if($sResMsg == 3){
                        $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been locked Successfully.', 'result' => 'success', 'data' => array());
                    }
                    //END SUCCESS RESPONSE
                }else{
                    //START FAILURE RESPONSE
                    if($eResMsg == 1){
                        $result = array('response_code' => ERROR_CODE, 'response_description' => 'Selected ('.rtrim($bookedTimeLists, ', ').') slot locked', 'result' => 'error', 'data' => array('error' => 1));
                    }else if($eResMsg == 2){
                        $result = array('response_code' => ERROR_CODE, 'response_description' => 'Selected ('.rtrim($bookedTimeLists, ', ').') slot already booked.', 'result' => 'error', 'data' => array('error' => 1));
                    }else if($eResMsg == 3){
                        $result = array('response_code' => ERROR_CODE, 'response_description' => 'Invalid Package ID.', 'result' => 'error', 'data' => array('error' => 1));
                    }
                    //END FAILURE RESPONSE
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
    function lock_validate( $post_input ) {
        
        if( trim($post_input['user_id']) == '' ){
            $this->error['warning']    = HOST_NAME.' ID Cannot be empty';
        } else if( !$this->Guiderapimodel->guiderInfoByUuid( $post_input['user_id'] ) ) {
            $this->error['warning']    = 'Invalid '.HOST_NAME.' ID.';
        } else if( count($post_input['package_id']) <= 0 ) {
            $this->error['warning']    = 'Package ID Cannot be empty';
        }
        return !$this->error;
    }
}
?>