<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Completedtrip extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('host_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Travellermodel');
        $this->load->model('hostPortal/Completedrequestmodel');
        $this->load->model('Servicemodel');
        host_sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'host_id' ]         = $this->session->userdata['HOST_ID'];
        $data1[ 'guider_lists' ]    = $this->Guidermodel->guider_lists();
        $data1[ 'traveller_lists' ] = $this->Travellermodel->traveller_lists();
        $content    = $this->load->view( 'hostPortal/completedtrip', $data1, true );
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
        $host_id        = $this->session->userdata['HOST_ID'];
        $serviceLists   = $this->Completedrequestmodel->get_datatables($where, $host_id);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                $viewbtn        = '';
                $createdon      = date(getDateFormat(), strtotime($service->pay_createdon)) .' '.date(getTimeFormat(), strtotime($service->pay_createdon));
                if($service->tgender == 1){
                    $tgender = 'Male';
                }elseif ($service->tgender == 2) {
                    $tgender = 'Female';
                }else{
                    $tgender = '';
                }
                $spec = [];
                if($service->guiding_speciality){
                    $array  = explode(',', $service->guiding_speciality);
                    foreach ($array as $item) {
                        $specInfo = $this->Guidermodel->guiderSpecialityInfo($item);
                        if($specInfo){ $spec[] = rawurldecode($specInfo->specialization); }
                    }
                }
                $hostServicesFees   = hostServicesFee($service->service_price_type_id, $service->guider_charged, $service->number_of_person, $service->processing_FeesType, $service->processing_FeesValue);
                $paid_to_guider = $service->sub_total - $hostServicesFees;
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $createdon;
                $row[]  = implode(',', $spec);
                $row[]  = $service->guiderName;
                $row[]  = $service->order_id;
                $row[]  = rawurldecode($service->travellerName);
                $row[]  = $tgender;
                $row[]  = $service->temail;
                $row[]  = $service->additional_information;
                $row[]  = number_format((float)$service->sub_total, 2, '.', '');
                $row[]  = $hostServicesFees;
                $row[]  = number_format((float)$paid_to_guider, 2, '.', '');
                $row[]  = $viewbtn;
                
                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Completedrequestmodel->count_all($where, $host_id),
                        "recordsFiltered"   => $this->Completedrequestmodel->count_filtered($where, $host_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    public function completedFreetripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $host_id        = $this->session->userdata['HOST_ID'];
        $serviceLists   = $this->Completedrequestmodel->get_datatables2($where, $host_id);
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
                $viewbtn        = '';
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
                $row[]  = $service->travellerName;
                $row[]  = (($service->service_date != '0000-00-00')? date(getDateFormat(), strtotime($service->service_date)):'n/a');
                $row[]  = (($service->service_date != '0000-00-00')? date(getTimeFormat(), strtotime($service->service_date)):'n/a');
                $row[]  = $totalPassenger;
                $row[]  = $service->guiderName;
                $row[]  = $regionName;
                $row[]  = $status;
                $row[]  = $viewbtn;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Completedrequestmodel->count_all2($where, $host_id),
                        "recordsFiltered"   => $this->Completedrequestmodel->count_filtered2($where, $host_id),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function exportExcelForm() {
        $data1[ 'trip_id' ]       = '';
        $data1[ 'traveller_id' ]  = $this->input->post( 'traveller_id' );
        $data1[ 'guider_id' ]     = $this->input->post( 'guider_id' );
        $data1[ 'getAllActivityLists'] = $this->Commonmodel->getAllActivityLists();
        $data1[ 'guider_lists'] = $guider_lists = $this->Guidermodel->guider_lists( false, 1 );
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
        $data1['guider_id']     = $this->input->get('guider_id');
        $data1['start_date']    = $this->input->get('start_date');
        $data1['end_date']      = $this->input->get('end_date');
        $data1['booking_lists'] = $this->Completedrequestmodel->completedBookings_export($data1);
        if($data1['booking_lists']){
            completed_booking_export($data1);
        }else{
            $this->session->set_flashdata('successMSG', 'No data found');
            redirect( $this->config->item( 'admin_url' ) . 'completedtrip' );
        }
    }
	function template( $data ){
        $this->load->view( 'hostPortal/templatecontent', $data );
    }
}
