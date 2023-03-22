<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Get_category_list extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        header("content-type:application/json");
    }
    public function index() {
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $result         = $this->Commonapimodel->get_specialization_list();
            if( $_SERVER['REQUEST_METHOD'] != 'GET' )  {
                $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
            } else if ( isset( $this->error['warning'] ) ) {
                $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
}
?>