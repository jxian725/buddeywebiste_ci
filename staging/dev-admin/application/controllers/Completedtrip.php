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
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                $viewbtn        = '<a href="'.base_url().'completedtrip/view/'.$service->service_id.'" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-search"></i></a>';
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
                $hostServicesFees   = hostServicesFees($service->service_price_type_id, $service->guider_charged, $service->number_of_person, $service->processing_FeesType, $service->processing_FeesValue);
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
    public function completedWebRequestTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Completedbookingsmodel->get_datatables3($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $totalAmount    = 0;
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $request) {
                $requestID  = 'RQT'.str_pad($request->activity_request_id, 5, '0', STR_PAD_LEFT);
                $createdon  = date(getDateFormat(), strtotime($request->createdon)) .' '.date(getTimeFormat(), strtotime($request->createdon));
                $status     = '<span class="label label-success">Approved</span>';
                if($request->payment_type == 1){
                    $paymentType= 'Cash';
                }else if($request->payment_type == 2){
                    $paymentType= 'SenangPay';
                }else{
                    $paymentType = 'n/a';
                }
                $row    = array();
                $row[]  = $requestID;
                $row[]  = $request->full_name;
                $row[]  = $request->countryCode.' '.$request->mobile_no;
                $row[]  = $request->email;
                $row[]  = rawurldecode($request->skillName);
                $row[]  = rawurldecode($request->cityName);
                $row[]  = $request->budget;
                $row[]  = $request->confirm_budget;
                $row[]  = $request->occasion;
                $row[]  = $request->venue;
                $row[]  = $request->time_hour;
                $row[]  = $createdon;
                $row[]  = $request->other_info;
                $row[]  = $paymentType;
                $row[]  = $status;

                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Completedbookingsmodel->count_all3($where),
                        "recordsFiltered"   => $this->Completedbookingsmodel->count_filtered3($where),
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
        $data1['booking_lists'] = $this->Completedbookingsmodel->completedBookings_export($data1);
        if($data1['booking_lists']){
            completed_booking_export($data1);
        }else{
            $this->session->set_flashdata('successMSG', 'No data found');
            redirect( $this->config->item( 'admin_url' ) . 'completedtrip' );
        }
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
