<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class autologin_bak extends CI_Controller { 
    public function __construct()
    {
       parent::__construct();
       $this->load->model( 'talent/Talentmodel' );
       $this->load->helper('talent_helper.php');
       $this->load->library('encryption');
    }
    function index(){
        $data = array();

        $sessionVal = array(
                      'TALENT_ID'           => 248,//321 //138 //354
                      'TALENT_NAME'         => 'BUDDEY ADMIN',
                      'TALENT_EMAILID'      => '',
                      'TALENT_MOBILE'       => 182036533
                    );

        $this->session->set_userdata( $sessionVal );

        if( $this->session->userdata( 'TALENT_ID' ) ) {
            redirect( base_url() . 'talent/forums' );
        }
        $this->load->view('talent/common/login', $data );
    }
}
