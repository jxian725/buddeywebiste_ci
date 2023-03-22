<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendingtrip extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Pendingbookingsmodel');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]        = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ]     = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'trip/pendingtrip', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Bookings';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Bookings';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Bookings';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Pending Bookings</li>';
        $this->template( $data );
	}
    public function pendingtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Pendingbookingsmodel->get_datatables($where);
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
                $tripID         = 'BT'.str_pad($service->service_id, 5, '0', STR_PAD_LEFT);
                $guider_charged = $service->guider_charged;
                $totalPassenger = $service->number_of_person;
                $processing_fee = $service->current_processing_fee;
                if($service->service_price_type_id == 1){
                    $subTotal   = $totalPassenger * $guider_charged;
                }elseif ($service->service_price_type_id == 2) {
                    $subTotal   = $guider_charged;
                }else{
                    $subTotal   = 0;
                }
                //CALCULATE SERVICE FEE
                $ServiceFees    = (PROCESSING_FEE / 100) * $subTotal;
                if($ServiceFees < 2){ $ServiceFees = 02.00; }
                //$ProcessingFees = ($processing_fee / 100) * $subTotal;
                $totalAmount    = $subTotal;
                $viewbtn    = '<a href="'.base_url().'pendingtrip/view/'.$service->service_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                $createdon  = date(getDateFormat(), strtotime($service->createdon)) .' '.date(getTimeFormat(), strtotime($service->createdon));
                if ($service->status == 1) {
                    $status         = 'Request Sent by '.GUEST_NAME.'';
                }elseif($service->status == 2){
                    $status         = 'Request Accept(Pending Payment)';
                }elseif($service->status == 3){ //CANCELLED
                    $deletebtn      = '';
                    $confirmbtn     = '';
                    if($service->cancelled_type == 1){
                        $status     = 'cancelled by '.GUEST_NAME.'';
                    }elseif($service->cancelled_type == 2){
                        $status     = 'cancelled by '.HOST_NAME.'';
                    }elseif($service->cancelled_type == 3){
                        $status     = 'cancelled by '.GUEST_NAME.' before payment';
                    }
                }
                $regionInfo     = $this->Guidermodel->stateInfoByid($service->service_region_id);
                if($regionInfo){
                    $regionName = $regionInfo->name;
                }else{
                    $regionName = '';
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $tripID;
                $row[]  = $createdon;
                $row[]  = '<a href="'.base_url().'traveller/view/'.$service->service_traveller_id.'" target="_blank">'.$service->travellerName.'</a>';
                $row[]  = date(getDateFormat(), strtotime($service->service_date));
                $row[]  = date(getTimeFormat(), strtotime($service->service_date));
                $row[]  = $totalPassenger;
                $row[]  = '<a href="'.base_url().'guider/view/'.$service->service_guider_id.'" target="_blank">'.$service->guiderName.'</a>';
                $row[]  = $regionName;
                $row[]  = number_format((float)$guider_charged, 2, '.', '');
                $row[]  = number_format((float)$subTotal, 2, '.', '');
                $row[]  = number_format((float)$ServiceFees, 2, '.', '');
                $row[]  = number_format((float)$totalAmount, 2, '.', '');
                $row[]  = $status;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Pendingbookingsmodel->count_all($where),
                        "recordsFiltered"   => $this->Pendingbookingsmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }

    public function view()
    {
        $service_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Servicemodel->serviceInfo($service_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'pendingtrip' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'service_id' ]                  = $service_id;
        $script     = '';
        $content    = $this->load->view( 'trip/pendingtrip_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Pending Bookings Information';
        $data[ 'header' ][ 'metakeyword' ]      = 'Pending Bookings Information';
        $data[ 'header' ][ 'metadescription' ]  = 'Pending Bookings Information';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'pendingtrip">Trip List</a></li>
                                              <li class="active">Pending Bookings Information</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
