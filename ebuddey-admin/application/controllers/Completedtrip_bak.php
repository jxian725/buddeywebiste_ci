<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completedtrip extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('Completedbookingsmodel');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]        = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ]     = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'trip/completedtrip', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Completed Bookings';
        $data[ 'header' ][ 'metakeyword' ]      = 'Completed Bookings';
        $data[ 'header' ][ 'metadescription' ]  = 'Completed Bookings';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">Completed Bookings</li>';
        $this->template( $data );
	}
    public function completedtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Completedbookingsmodel->get_datatables($where);
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
                if($service->service_price_type_id == 1){
                    $subTotal   = $totalPassenger * $guider_charged;
                }elseif ($service->service_price_type_id == 2) {
                    $subTotal   = $guider_charged;
                }else{
                    $subTotal   = 0;
                }
                /*$processing_fee = $service->current_processing_fee;
                $subTotal       = $totalPassenger * $guider_charged;
                $ProcessingFees = ($processing_fee / 100) * $subTotal;*/
                $totalAmount    = $subTotal;
                //CALCULATE SERVICE FEE
                $ServiceFees    = (PROCESSING_FEE / 100) * $subTotal;
                if($ServiceFees < 2){ $ServiceFees = 02.00; }
                $viewbtn        = '<a href="'.base_url().'completedtrip/view/'.$service->service_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                $createdon      = date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon));
                if ($service->jny_status == 1) {
                    $status     = 'Upcoming';
                }elseif($service->jny_status == 2){
                    $status     = 'Ongoing';
                }elseif($service->jny_status == 3){ //COMPLETED
                    $status     = 'Completed';
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
                        "recordsTotal"      => $this->Completedbookingsmodel->count_all($where),
                        "recordsFiltered"   => $this->Completedbookingsmodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function completedFreetripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Completedbookingsmodel->get_datatables2($where);
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
                $subTotal       = intval($totalPassenger) * floatval($guider_charged);
                $ProcessingFees = ($processing_fee / 100) * $subTotal;
                $totalAmount    = $ProcessingFees + $subTotal;
                $viewbtn        = '<a href="'.base_url().'completedtrip/view/'.$service->service_id.'" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-search"></i></a>';
                $createdon      = date(getDateFormat(), strtotime($service->jny_createdon)) .' '.date(getTimeFormat(), strtotime($service->jny_createdon));
                if ($service->jny_status == 1) {
                    $status     = 'Upcoming';
                }elseif($service->jny_status == 2){
                    $status     = 'Ongoing';
                }elseif($service->jny_status == 3){ //COMPLETED
                    $status     = 'Completed';
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
                $row[]  = $status;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Completedbookingsmodel->count_all2($where),
                        "recordsFiltered"   => $this->Completedbookingsmodel->count_filtered2($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function exportExcelForm() {
        $data1[ 'trip_id' ]       = $this->input->post( 'trip_id' );
        $data1[ 'traveller_id' ]  = $this->input->post( 'traveller_id' );
        $data1[ 'guider_id' ]     = $this->input->post( 'guider_id' );
        echo $this->load->view( 'trip/exportexcelform', $data1, true );
    }
    public function bookings_export()
    {
        error_reporting(E_ALL);
        $this->load->library('excel');
        $data1['trip_id']       = $this->input->get('trip_id');
        $data1['traveller_id']  = $this->input->get('traveller_id');
        $data1['guider_id']     = $this->input->get('guider_id');
        $data1['user_random_id']= $this->input->get('user_random_id');
        $data1['booking_lists'] = $this->Completedbookingsmodel->completedBookings_export($data1);
        completed_booking_export($data1);
    }

    public function view()
    {
        $service_id    = $this->uri->segment(3);
        $serviceInfo   = $this->Servicemodel->serviceInfo($service_id);
        if(!$serviceInfo){ redirect( $this->config->item( 'admin_url' ) . 'completedtrip' ); }
        $data1[ 'serviceInfo' ]                 = $serviceInfo;
        $data1[ 'service_id' ]                  = $service_id;
        $script     = '';
        $content    = $this->load->view( 'trip/completedtrip_view', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'Completed Bookings Information';
        $data[ 'header' ][ 'metakeyword' ]      = 'Completed Bookings Information';
        $data[ 'header' ][ 'metadescription' ]  = 'Completed Bookings Information';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '
                                              <li><a href="'.$this->config->item( 'admin_url' ).'completedtrip">trip List</a></li>
                                              <li class="active">Completed Bookings Information</li>';
        $this->template( $data );
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
