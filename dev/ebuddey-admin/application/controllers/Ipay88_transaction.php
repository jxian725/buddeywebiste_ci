<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipay88_transaction extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Ipay88_transactionmodel');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]        = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ]     = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'ipay88/ipay88_transaction_history', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'iPay88 Transaction history';
        $data[ 'header' ][ 'metakeyword' ]      = 'iPay88 Transaction history';
        $data[ 'header' ][ 'metadescription' ]  = 'iPay88 Transaction history';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">iPay88 Transaction history</li>';
        $this->template( $data );
	}
    public function ipay88_transTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Ipay88_transactionmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $ipay88) {
                $tripID         = 'BT'.str_pad($ipay88->serviceID, 5, '0', STR_PAD_LEFT);
                $paymentType    = $ipay88->paymentModeType;
                if($paymentType == 1){ $paymentType = 'Online Banking'; }else{ $paymentType = 'Credit Card'; }
                $viewbtn        = '<a href="'.base_url().'ipay88_transaction/view/'.$ipay88->payment_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                if ($ipay88->Status == 0) {
                    $status     = 'Initiated';
                }elseif($ipay88->Status == 1){
                    $status     = 'Success';
                }elseif($ipay88->Status == 2){
                    $status     = 'Cancelled';
                }elseif($ipay88->Status == 3){
                    $status     = 'Failure';
                }elseif($ipay88->Status == 4){
                    $status     = 'Pending';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $tripID;
                $row[]  = $ipay88->TransactionRefId;
                $row[]  = '<a href="'.base_url().'guider/view/'.$ipay88->guiderID.'" target="_blank">'.$ipay88->guiderName.'</a>';
                $row[]  = $paymentType;
                $row[]  = $ipay88->bankName;
                $row[]  = $ipay88->transactionBankName;
                $row[]  = $ipay88->iPay88TaransactionID;
                $row[]  = $ipay88->iPay88Remarks;
                $row[]  = $ipay88->iPay88ErrDescription;
                $row[]  = $ipay88->iPay88PaidAmount;
                $row[]  = $status;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Ipay88_transactionmodel->count_all($where),
                        "recordsFiltered"   => $this->Ipay88_transactionmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function view()
    {
        $payment_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Servicemodel->ipay88PaymentInfo($payment_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'ipay88_transaction' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'payment_id' ]                  = $payment_id;
        $script     = '';
        $content    = $this->load->view( 'ipay88/ipay88_transaction_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'iPay88 Transaction history Information';
        $data[ 'header' ][ 'metakeyword' ]      = 'iPay88 Transaction history Information';
        $data[ 'header' ][ 'metadescription' ]  = 'iPay88 Transaction history Information';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'completedtrip">trip List</a></li>
                                              <li class="active">iPay88 Transaction history Information</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
