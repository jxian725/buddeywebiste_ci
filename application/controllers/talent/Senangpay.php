<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senangpay extends CI_Controller {
    function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
    }
    function success(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'talent/senangpay/paymentsuccess', $data );
    }
    function failed(){
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = '';
        $data[ 'header' ][ 'metakeyword' ]      = '';
        $data[ 'header' ][ 'metadescription' ]  = '';
        $data[ 'footer' ][ 'script' ]           = '';
        $this->load->view( 'talent/senangpay/paymentfailed', $data );
    }
	function template( $data ){
        $this->load->view( 'talent/senangpay/paymentform', $data );
    }
}
