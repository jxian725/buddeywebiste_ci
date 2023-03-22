<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senangpay extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        error_reporting(E_ALL);
    }
	public function index() {
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        echo $this->template( $data );
	}
    function success(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'senangpay/paymentsuccess', $data );
    }
    function failed(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'senangpay/paymentfailed', $data );
    }
	function template( $data ){
        $this->load->view( 'senangpay/paymentform', $data );
    }
}
