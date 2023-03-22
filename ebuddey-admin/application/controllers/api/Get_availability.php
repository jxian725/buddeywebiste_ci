<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_availability extends CI_Controller{

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
            $partner_id = $_GET['space_id'];
            $date       = $_GET['date'];
            if($partner_id && !$date){
                $result  = $this->Commonapimodel->get_availability_date_list($partner_id);
            }else{
                $result  = $this->Commonapimodel->get_availability_list($partner_id, $date);
            }
        } else {
            $result = array( 'message' => 'Undefined Request Method', 'result' => 'error' );
        }
        echo json_encode( $result );
    }
}
?>