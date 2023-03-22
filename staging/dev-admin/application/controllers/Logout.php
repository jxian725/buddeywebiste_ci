<?php
	class Logout extends CI_Controller {
		function index() {
			//$this->session->sess_destroy();
			$this->session->unset_userdata('USER_ID');
			$this->session->unset_userdata('USER_NAME');
			$this->session->unset_userdata('USER_EMAIL');
			$this->session->unset_userdata('SHOW_NOTIFICATIONS');
			redirect( $this->config->item('admin_url') . 'login' );
		}
	}
?>