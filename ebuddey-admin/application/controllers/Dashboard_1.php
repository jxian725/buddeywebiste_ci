<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->helper('timezone');
        sessionset();
    }
	public function index()
	{
	    $script          = '';
        $date            = '';
        $startDate       = '';
        $endDate         = '';
        //If Condition for Date Range
        if( $this->input->post( 'date' ) ) {
            $date = $this->input->post( 'date' );
            $dates          = explode('-', $date);
            if( $date ) {
                $startDate  = date( 'Y-m-d', strtotime( $dates[0] ) );
                $endDate    = date( 'Y-m-d', strtotime( $dates[1] ) );
            }
        }
        $host_total      = $this->Commonmodel->host_total_count( $startDate, $endDate );
        $guest_total     = $this->Commonmodel->guest_total_count( $startDate, $endDate );
        $revenue_total   = $this->Commonmodel->revenue_total_count( $startDate, $endDate );
        $service_total   = $this->Commonmodel->service_total_count( $startDate, $endDate );
        $host_pending    = $this->Commonmodel->host_total_pending( 0, $startDate, $endDate );
        $data1[ 'allPayout' ]                   = $this->Commonmodel->payment_count($startDate, $endDate);
        $data1[ 'activebooking' ]               = $this->Commonmodel->ActiveBookingTotal_count($startDate, $endDate);
        $data1[ 'host_total' ]                  = $host_total;
        $data1[ 'guest_total' ]                 = $guest_total;
        $data1[ 'revenue_total' ]               = $revenue_total;
        $data1[ 'service_total' ]               = $service_total;
        $data1[ 'host_pending' ]                = $host_pending;
		$content      = $this->load->view( 'common/dashboard', $data1, true );
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
        $this->load->view( 'common/templatecontent', $data );
    }
}
