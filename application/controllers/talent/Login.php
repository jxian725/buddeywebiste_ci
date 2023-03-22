<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Login extends CI_Controller { 
    public function __construct()
    {
       parent::__construct();
       $this->load->model( 'talent/Talentmodel' );
       $this->load->helper('talent_helper.php');
       $this->load->library('encryption');
    }
    function index(){
        $data = array();

        if( $this->session->userdata( 'TALENT_ID' ) ) {
            redirect( base_url() . 'talent/forums' );
        }
        $this->load->view('talent/common/login', $data );
    }
    //validate username and password
    function login_now() {
        $mobile_number = $this->input->post( 'mobile_number' );
        $this->form_validation->set_rules( 'mobile_number', 'Mobile Number', 'required|min_length[6]' );
        if( $this->form_validation->run() == FALSE ) {
            echo json_encode(array('status' => 'failed', 'msg' => validation_errors('<div class="error">', '</div>')));
            //$this->session->set_flashdata('err_msg', validation_errors());
        } else {
            $mobile_number = ltrim($mobile_number, '0');
            echo loginNow( $mobile_number);
        }
    }
    public function validate() {
        $mobile_number = $this->input->post( 'mobile_number' );
        $mobile_number = ltrim($mobile_number, '0');
        $checkMobile   = $this->Talentmodel->talentMobileExists($mobile_number);
        if($checkMobile){
            echo json_encode(['status' => 1, 'message' => 'Mobile number verified successfully.','url' => '']);
        }else{
            echo json_encode(['status' => 2, 'message' => 'Please enter the registered mobile number','url' => '']);
        }
    }
    
}
