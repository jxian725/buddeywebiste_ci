<?php
	class Logout extends CI_Controller {  
		function __construct() {
	        parent::__construct();
	        $this->load->model('partner/Partnermodel');
	        $this->load->helper('timezone');
	    }
		function index() {
			$data = array('web_active' => 0);
			$this->Partnermodel->updatePartner( $this->session->userdata('PARTNER_ID'), $data );
			$this->session->unset_userdata('PARTNER_ID');
			$this->session->unset_userdata('PARTNER_NAME');
			$this->session->unset_userdata('USER_EMAILID');
			$this->session->unset_userdata('SHOW_NOTIFICATIONS');
			redirect( base_url() . 'partner/login' );
		}
	}
?>