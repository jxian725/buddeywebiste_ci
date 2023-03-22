<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
	   parent::__construct();
       $this->load->helper('captcha');
       $this->load->helper('host_helper.php');
       $this->load->library('encryption');
       //host_sessionset();
	}
	function index(){
        //error_reporting(E_ALL);
        $data = array();
        $this->session->userdata( 'HOST_ID' );
	 	if( $this->session->userdata( 'HOST_ID' ) ) {
            redirect( $this->config->item('hostportal_url') . 'dashboard' );
        }
        $this->load->view('hostPortal/login', $data );
	}
	//validate username and password
    function validate() {
        $phone_number = $this->input->post( 'phone_number' );
        $pass         = $this->input->post( 'password' );
        $this->form_validation->set_rules( 'phone_number', 'User Name', 'required' );
        $this->form_validation->set_rules( 'password', 'Password', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            $this->session->set_flashdata('err_msg', validation_errors());
        } else {
            echo hostLoginNow( $phone_number, $pass );
        }
    }
}
