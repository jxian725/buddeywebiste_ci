<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senangpay_transaction extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Senangpay_transactionmodel');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]        = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ]     = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'senangpay/senangpay_transaction_history', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Senangpay Transaction history';
        $data[ 'header' ][ 'metakeyword' ]      = 'Senangpay Transaction history';
        $data[ 'header' ][ 'metadescription' ]  = 'Senangpay Transaction history';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Senangpay Transaction history</li>';
        $this->template( $data );
	}
    public function senangpay_transTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Senangpay_transactionmodel->get_datatables($where);
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
                $viewbtn        = '<a href="'.base_url().'senangpay_transaction/view/'.$ipay88->payment_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                if ($ipay88->pay_status == 0) {
                    $status     = 'Failure';
                }elseif($ipay88->pay_status == 1){
                    $status     = 'Success';
                }elseif($ipay88->pay_status == 2){
                    $status     = 'Initiated';
                }elseif($ipay88->pay_status == 3){
                    $status     = 'Cancelled';
                }elseif($ipay88->pay_status == 4){
                    $status     = 'Pending';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $tripID;
                $row[]  = $ipay88->order_id;
                $row[]  = '<a href="'.base_url().'guider/view/'.$ipay88->guiderID.'" target="_blank">'.$ipay88->guiderName.'</a>';
                $row[]  = $ipay88->transaction_id;
                $row[]  = $ipay88->transaction_amount;
                $row[]  = $status;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Senangpay_transactionmodel->count_all($where),
                        "recordsFiltered"   => $this->Senangpay_transactionmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function view()
    {
        $payment_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Senangpay_transactionmodel->senangpayPaymentInfo($payment_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'senangpay_transaction' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'payment_id' ]                  = $payment_id;
        $script     = '';
        $content    = $this->load->view( 'senangpay/senangpay_transaction_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Senangpay Transaction history Information';
        $data[ 'header' ][ 'metakeyword' ]      = 'Senangpay Transaction history Information';
        $data[ 'header' ][ 'metadescription' ]  = 'Senangpay Transaction history Information';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'completedtrip">Trip List</a></li>
                                              <li class="active">Senangpay Transaction history Information</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
