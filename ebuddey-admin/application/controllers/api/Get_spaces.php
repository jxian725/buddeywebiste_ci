<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_spaces extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Commonapimodel');
        header("content-type:application/json");
    }
    
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( ($_SERVER['REQUEST_METHOD'] == 'GET') ) {
            $city_id = $_GET['city_id'];
            $result  = $this->Commonapimodel->get_city_space_list($city_id);
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
}
?>