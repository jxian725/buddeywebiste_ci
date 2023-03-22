<?php
	class Logout extends CI_Controller {  
		function __construct() {
	        parent::__construct();
	        $this->load->model('talent/Talentmodel');
	        $this->load->helper('timezone');
	    }
		function index() {
			$data = array('web_active' => 0);
			$this->Talentmodel->updateTalent( $this->session->userdata('TALENT_ID'), $data );
			$this->session->unset_userdata('TALENT_ID');
			$this->session->unset_userdata('TALENT_NAME');
			$this->session->unset_userdata('TALENT_EMAILID');
			$this->session->unset_userdata('TALENT_MOBILE');
			$this->session->unset_userdata('TOKEN');
			$this->session->unset_userdata('booking_packages');
			$this->session->unset_userdata('booking_qty');
			$this->session->unset_userdata('booking_price');
			redirect( base_url() . 'talent/login' );
		}
	}
?>