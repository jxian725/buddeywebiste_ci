<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Get_list_of_places extends CI_Controller{

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
        } else if ( $input != '' ) {
            $user_input = get_object_vars( $input );
            if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && $this->validate( $user_input ) ) {
                $country    = trim( $user_input['country'] );
                $states     = trim( $user_input['states'] );
                $countryInfo = $this->Commonapimodel->countryInfoByShortcode($country);
                $country_id = $countryInfo->id;
                if($country && !$states){
                    $result     = $this->Commonapimodel->get_place_state_list($country_id, $states);
                }else{
                    $result     = $this->Commonapimodel->get_place_state_list($country_id, $states);
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
    private function validate( $user_input ) {
        
        if ( trim( $user_input['country'] ) == '' ) {
            $this->error['warning']    = 'Country Cannot be empty';
        } 
        return !$this->error;
    }
}
?>