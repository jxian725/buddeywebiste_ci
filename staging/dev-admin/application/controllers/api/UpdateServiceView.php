<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateServiceView extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->update_validate( $user_input ) ) {
                $user_type      = trim($user_input['user_type']);
                $user_id        = trim($user_input['user_id']);
                $service_ids    = $user_input['service_ids'];
                $result     = $this->Commonapimodel->updateServiceViews( $user_type, $user_id, $service_ids );
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
    function update_validate( $user_input ) {
        if( trim($user_input['user_type']) == '' ){
            $this->error['warning']    = 'User Type Cannot be empty';
        } else if( trim($user_input['user_id']) == '' ){
            $this->error['warning']    = 'User Id Cannot be empty.';
        } else if( count($user_input['service_ids']) == 0 ){
            $this->error['warning']    = 'Service Id Cannot be empty.';
        }
        return !$this->error;
    }
}
?>