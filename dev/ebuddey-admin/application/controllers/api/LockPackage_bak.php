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
                $user_id     = trim( $user_input['user_id'] );
                $package_id  = trim( $user_input['package_id'] );
                $packageInfo = $this->Commonapimodel->packageInfo( $package_id );
                if($packageInfo->status == 1){ //lock

                    $data    = array("lockedBy" => $user_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                    $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                    
                    $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been locked Successfully.', 'result' => 'success', 'data' => array());
                }else if($packageInfo->status == 4 && $packageInfo->lockedBy == $user_id){ //unlock

                    $data    = array("lockedBy" => 0, "lockedDateTime" => '0000-00-00 00:00:00', 'status' => 1 );
                    $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                    
                    $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been unlocked Successfully.', 'result' => 'success', 'data' => array());
                }else if($packageInfo->status == 4 && $packageInfo->lockedBy != $user_id){ //check 5min

                    $cenvertedTime = date('Y-m-d H:i:s',strtotime('+5 minutes',strtotime($packageInfo->lockedDateTime)));
                    $currentTime   = date('Y-m-d H:i:s');
                    if($currentTime > $cenvertedTime){ //allow
                        $data    = array("lockedBy" => $user_id, "lockedDateTime" => date("Y-m-d H:i:s"), 'status' => 4 );
                        $update  = $this->Commonapimodel->updatePackageInfo($data, $package_id);
                        $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package has been locked Successfully.', 'result' => 'success', 'data' => array());
                    }else{
                        $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package already locked.', 'result' => 'success', 'data' => array());
                    }
                }else{
                    $result  = array('response_code' => SUCCESS_CODE, 'response_description' => 'Package already booked.', 'result' => 'success', 'data' => array());
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
        } else if( $post_input['package_id'] == 0 ){
            $this->error['warning']    = 'Package ID Cannot be empty';
        } else if( !$this->Commonapimodel->packageInfo( $post_input['package_id'] ) ) {
            $this->error['warning']    = 'Invalid '.HOST_NAME.' ID.';
        }
        return !$this->error;
    }
}
?>