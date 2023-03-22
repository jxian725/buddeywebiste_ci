<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buskerspod_report extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model( 'BuskerspodReportmodel' );
        $this->load->model( 'Partnermodel' );
        $this->load->model( 'Guidermodel' );
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $data1      = array();
        $content    = $this->load->view( 'buskerspod/pod_report', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Buskers Pod Report';
        $data[ 'header' ][ 'metakeyword' ]      = 'Buskers Pod Report';
        $data[ 'header' ][ 'metadescription' ]  = 'Buskers Pod Report';
        $data[ 'footer' ][ 'script' ]           = '';
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Buskers Pod Report</li>';
        $this->template( $data );
	}
    public function podReportTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $podLists   = $this->BuskerspodReportmodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($podLists){
            foreach ($podLists as $podInfo) {
                
                if($podInfo->host_id){
                    $guiderInfo = $this->Guidermodel->guiderInfo($podInfo->host_id);
                    if($guiderInfo){
                        $name  = $guiderInfo->first_name;
                        $email = $guiderInfo->email;
                    }else{
                        $name  = '';
                        $email = '';
                    }
                }
                
                $row    = array();
                $row[]  = $si_no;
                $row[]  = rawurldecode($podInfo->partner_name);
                $row[]  = $podInfo->address;
                $row[]  = $podInfo->cityName;
                $row[]  = date('d M Y', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->start));
                $row[]  = date('H:i', strtotime($podInfo->end));
                $row[]  = (($podInfo->partnerFees)? number_format($podInfo->partnerFees, 2) : '');
                $row[]  = $podInfo->transactionID;
                $row[]  = $name;
                $row[]  = $email;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->BuskerspodReportmodel->count_all($where),
                        "recordsFiltered"   => $this->BuskerspodReportmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
