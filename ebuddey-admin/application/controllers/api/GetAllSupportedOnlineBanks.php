<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GetAllSupportedOnlineBanks extends CI_Controller{

    private $error = array();
    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        $this->load->helper('timezone');
        header("content-type:application/json");
    }
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else{
            if( ( $_SERVER['REQUEST_METHOD'] == 'GET' )) {
                $result   = $this->Commonapimodel->bankList();
            } else if( $_SERVER['REQUEST_METHOD'] != 'GET' )  {
                $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
            } else if ( isset( $this->error['warning'] ) ) {
                $result = array('response_code' => ERROR_CODE, 'response_description' => $this->error['warning'], 'result' => 'error', 'data'=>array('error' => 1));
            }
        }
        echo json_encode($result);
    }
}
?>