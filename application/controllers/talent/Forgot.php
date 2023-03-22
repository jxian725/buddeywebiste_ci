<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Forgot extends CI_Controller {
    public function __construct()
    {
       parent::__construct();
       $this->load->helper('partner_helper.php');
       $this->load->library('encryption');
    }
    function index(){
        $data = array();
        if( $this->session->userdata( 'PARTNER_ID' ) ) {
            redirect( base_url() . 'partner/venue' );
        }
        $this->load->view('partner/common/forgot', $data );
    }
    //validate username and password
    function validate() {
        $email      = $this->input->post( 'email' );
        $this->form_validation->set_rules( 'email', 'Email', 'trim|required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            $this->session->set_flashdata('err_msg', validation_errors());
        } else {
            echo forgotpassword($email);
        }
    }
}