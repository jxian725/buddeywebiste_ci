<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completedpayout extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Guiderpayoutmodel');
        $this->load->model('Completedpayoutmodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script         = '';
        $guider_id      = $this->input->get('guider_id');
        $data1[ 'guider_lists' ] = $this->Guidermodel->guider_lists( false,false );
        $content    = $this->load->view( 'payout/completedpayout', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Completed Payout lists';
        $data[ 'header' ][ 'metakeyword' ]      = 'Completed Payout lists';
        $data[ 'header' ][ 'metadescription' ]  = 'Completed Payout lists';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Completed Payout lists</li>';
        $this->template( $data );
	}
    public function completedPayoutTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Completedpayoutmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                
                $viewbtn        = '<a href="'.base_url().'completedpayout/view/'.$service->pt_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                $lastPayoutInfo = $this->Guiderpayoutmodel->lastPayoutInfo($service->guiderID,$service->createdon);
                if($lastPayoutInfo){
                    $payoutDate     = date(getDateFormat(), strtotime($lastPayoutInfo->createdon));
                    $payoutAmount   = number_format((float)$lastPayoutInfo->payoutAmount, 2, '.', '');
                }else{
                    $payoutDate     = '-';
                    $payoutAmount   = '-';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $service->guiderName;
                $row[]  = $service->totalTrip;
                $row[]  = number_format((float)$service->totalAmount, 2, '.', '');
                $row[]  = number_format((float)$service->percentageAmount, 2, '.', '');
                $row[]  = number_format((float)$service->payoutAmount, 2, '.', '');
                $row[]  = $payoutDate;
                $row[]  = $payoutAmount;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Completedpayoutmodel->count_all($where),
                        "recordsFiltered"   => $this->Completedpayoutmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function view()
    {
        $script         = '';
        $payment_id     = $this->uri->segment(3);
        $data1[ 'payouttransInfo' ] = $this->Completedpayoutmodel->payouttransInfo( $payment_id );
        //$data1[ 'guider_info' ]     = $this->Guidermodel->guiderInfo( $guider_id );
        $content    = $this->load->view( 'payout/completedpayout_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Completed Payout View';
        $data[ 'header' ][ 'metakeyword' ]      = 'Completed Payout View';
        $data[ 'header' ][ 'metadescription' ]  = 'Completed Payout View';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Completed Payout View</li>';
        $this->template( $data );
    }
    function excutePayout(){
        $guider_id  = $this->input->post( 'guider_id' );
        $this->Guiderpayoutmodel->updateExcutePayout( $guider_id );
    }
    public function test(){

        $test = $this->Completedpayoutmodel->testquery();
        print_r($test);
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
