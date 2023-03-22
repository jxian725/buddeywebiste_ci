<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	public function __construct()
	{
	   parent::__construct();
       $this->load->helper('captcha');
       $this->load->helper('admin_helper.php');
       $this->load->library('encryption');
	}
	function index(){
        //error_reporting(E_ALL);
        $data = array();
        $this->session->userdata( 'USER_ID' );
	 	if( $this->session->userdata( 'USER_ID' ) ) {
            redirect( $this->config->item('admin_url') . 'index' );
        }
        $this->load->view('common/login', $data );
        //$this->load->view('mail/reg_traveller', $data );
	}
	//validate username and password
    function validate() {
        $username   = $this->input->post( 'username' );
        $pass       = $this->input->post( 'password' );
        $this->form_validation->set_rules( 'username', 'User Name', 'required' );
        $this->form_validation->set_rules( 'password', 'Password', 'required' );
        if( $this->form_validation->run() == FALSE ) {
            echo validation_errors();
            $this->session->set_flashdata('err_msg', validation_errors());
        } else {
            echo loginNow( $username, $pass );
        }
    }
}
