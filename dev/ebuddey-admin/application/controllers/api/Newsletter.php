<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter extends CI_Controller{

    private $error = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('api/Newsletterapimodel');
        header("content-type:application/json");
    }
    
    public function index() {
        $input  = json_decode(file_get_contents("php://input"));
        if( $_SERVER['HTTP_ACCESS_TOKEN'] != API_ACCESS_TOKEN ) {
            $result = array( 'message' => 'Authorization error', 'result' => 'error' );
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $page_no    = trim( $user_input['page_no'] );
                $page_total = trim( $user_input['page_total'] );
                if(!$page_no){
                    $page_no = 1;
                }
                $page_number     = ($page_no) ? $page_no : 1;
                $offset          = ($page_number  == 1) ? 0 : ($page_number * $page_total) - $page_total;

                $result     = $this->Newsletterapimodel->get_newsletter_list($page_total, $offset);
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
}
?>