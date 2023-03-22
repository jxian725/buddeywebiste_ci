<?php
	class Logout extends CI_Controller {
		function __construct() {
	        parent::__construct();
	        $this->load->model('hostPortal/Hostmodel');
	    }
		function index() {
			//$this->session->sess_destroy();
			//$array_items = array('HOST_ID' => '','HOST_NAME' => '','HOST_EMAIL' => '','SHOW_NOTIFICATIONS' => '');
			//$this->session->unset_userdata($array_items);
			$data2 	= array('web_active' => 0);
			$this->Hostmodel->updateGuiderInfo( $this->session->userdata['HOST_ID'], $data2 );
			$this->session->unset_userdata('HOST_ID');
			$this->session->unset_userdata('HOST_NAME');
			$this->session->unset_userdata('HOST_EMAIL');
			$this->session->unset_userdata('SHOW_NOTIFICATIONS');
			redirect( $this->config->item('hostportal_url') . 'login' );
		}
	}
?>