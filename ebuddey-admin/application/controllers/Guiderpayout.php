<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guiderpayout extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script         = '';
        $guider_id      = $this->input->get('guider_id');
        $data1[ 'guider_lists' ]    = $this->Guidermodel->guider_lists( false,false );
        $data1[ 'guider_info' ]     = $this->Guidermodel->guiderInfo( $guider_id );
        $data1[ 'settledPayment' ]  = $this->Guiderpayoutmodel->guiderSettledPayment( $guider_id );
        $data1[ 'payoutAmt' ]       = $this->Guiderpayoutmodel->guiderPendingPayoutAmt( $guider_id );
        $data1[ 'percentageAmt' ]   = $this->Guiderpayoutmodel->guiderPendingPercentageAmt( $guider_id );
        $data1[ 'transactionAmt' ]  = $this->Guiderpayoutmodel->guiderPendingTransactionAmt( $guider_id );
        $data1[ 'pendingPaymentLists' ]  = $this->Guiderpayoutmodel->guiderPendingPaymentLists( $guider_id );
        $content    = $this->load->view( 'payout/payout', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = HOST_NAME.' Payout lists';
        $data[ 'header' ][ 'metakeyword' ]      = HOST_NAME.' Payout lists';
        $data[ 'header' ][ 'metadescription' ]  = HOST_NAME.' Payout lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">'.HOST_NAME.' Payout lists</li>';
        $this->template( $data );
	}
    function excutePayout(){
        $guider_id      = $this->input->post( 'guider_id' );
        $payoutAmt      = $this->input->post( 'payoutAmt' );
        $transactionAmt = $this->input->post( 'transactionAmt' );
        $percentageAmt  = $this->input->post( 'percentageAmt' );
        $totalTrip      = $this->input->post( 'totalTrip' );
        $this->Guiderpayoutmodel->updateExcutePayout( $guider_id, $payoutAmt, $transactionAmt, $percentageAmt, $totalTrip );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
