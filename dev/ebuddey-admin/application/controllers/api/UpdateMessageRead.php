<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UpdateMessageRead extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->model('api/Serviceapimodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if( ( $_SERVER['REQUEST_METHOD'] == 'POST' ) && $this->delete_validate( $user_input ) ) {
                $cuser_type = trim($user_input['current_user_type']);
                $ruser_type = trim($user_input['retrieve_user_type']);
                $ruser_id   = trim($user_input['retrieve_user_id']);
                $puser_type = trim($user_input['post_user_type']);
                $puser_id   = trim($user_input['post_user_id']);
            
                $result     = $this->Commonapimodel->updateMessageList( $ruser_type, $ruser_id, $puser_type, $puser_id, $cuser_type );
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
    function delete_validate( $user_input ) {
        if( trim($user_input['current_user_type']) == '' ){
            $this->error['warning']    = 'Current User Type Cannot be empty';
        } else if( trim($user_input['retrieve_user_type']) == '' ){
            $this->error['warning']    = 'Retrive User Type Cannot be empty.';
        } else if( trim($user_input['retrieve_user_id']) == '' ){
            $this->error['warning']    = 'Retrive User ID Cannot be empty.';
        } else if( trim($user_input['post_user_type']) == '' ){
            $this->error['warning']    = 'Post User Type Cannot be empty.';
        } else if( trim($user_input['post_user_id']) == '' ){
            $this->error['warning']    = 'Post User ID Cannot be empty.';
        }
        return !$this->error;
    }
}
?>