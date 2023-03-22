<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('host_helper.php');
        $this->load->model('hostPortal/Hostmodel');
        $this->load->library('encryption');
        $this->load->helper('timezone');
        host_sessionset();
    }
	public function index()
	{
	    $script         = '';
        $date           = '';
        $startDate      = '';
        $endDate        = '';
        $host_id        = $this->session->userdata['HOST_ID'];
        //If Condition for Date Range
        if( $this->input->post( 'date' ) ) {
            $date = $this->input->post( 'date' );
            $dates          = explode('-', $date);
            if( $date ) {
                $startDate  = date( 'Y-m-d', strtotime( $dates[0] ) );
                $endDate    = date( 'Y-m-d', strtotime( $dates[1] ) );
            }
        }
        $data1[ 'activebooking' ]       = $this->Hostmodel->activeBookingTotal_count($startDate, $endDate, $host_id);
        $data1[ 'completedbooking' ]    = $this->Hostmodel->completedBookingTotal_count($startDate, $endDate, $host_id);
        $data1[ 'upcomingbooking' ]     = $this->Hostmodel->upcomingBookingTotal_count($startDate, $endDate, $host_id);
        $data1[ 'pending_request' ]     = $this->Hostmodel->pendingRequestTotal_count( $startDate, $endDate, $host_id );
		$content      = $this->load->view( 'hostPortal/dashboard', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Dashboard';
        $data[ 'header' ][ 'metakeyword' ]      = 'Dashboard';
        $data[ 'header' ][ 'metadescription' ]  = 'Dashboard';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ] = '<li class="active">Dashboard</li>';
        $this->template( $data );
	}

	function template( $data ){
        $this->load->view( 'hostPortal/templatecontent', $data );
    }
}
