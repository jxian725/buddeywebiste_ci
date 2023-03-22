<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qrscandonate extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('admin_helper.php');
        $this->load->model('Guidermodel');
        $this->load->model('Qrscandonatemodel');
        $this->load->model('Servicemodel');
        sessionset();
        error_reporting(E_ALL);
    }
	public function index() {
        $script     = '';
        $data1[ 'guider_lists' ]        = $this->Guidermodel->guider_lists();
        $content    = $this->load->view( 'qrscan/qrscandonate', $data1, true );
        $data[ 'navigation' ]                   = '';
        $data[ 'Emessage' ]                     = '';
        $data[ 'Smessage' ]                     = '';
        $data[ 'header' ][ 'title' ]            = 'QR Scan Donation';
        $data[ 'header' ][ 'metakeyword' ]      = 'QR Scan Donation';
        $data[ 'header' ][ 'metadescription' ]  = 'QR Scan Donation';
        $data[ 'footer' ][ 'script' ]           = $script;
        $data[ 'content' ]                      = $content;
        $data[ 'breadcrumb' ]                   = '<li class="active">QR Scan Donation</li>';
        $this->template( $data );
	}
    public function completedtripTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $serviceLists   = $this->Qrscandonatemodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $status         = '';
        $data           = array();
        if($serviceLists){
            foreach ($serviceLists as $service) {
                $createdon      = date(getDateFormat(), strtotime($service->pay_createdon)) .' '.date(getTimeFormat(), strtotime($service->pay_createdon));
                if($service->anonymous == 1){
                    $fullName    = '';
                    $email       = '';
                    $phoneNumber = '';
                }else{
                    $fullName    = $service->fullName;
                    $email       = $service->email;
                    $phoneNumber = $service->phoneNumber;
                }
                $row    = array();
                $row[]  = $si_no;
                $row[]  = $createdon;
                $row[]  = $service->guiderName;
                $row[]  = $service->order_id;
                $row[]  = rawurldecode($service->guiderName);
                $row[]  = $fullName;
                $row[]  = $phoneNumber;
                $row[]  = $email;
                $row[]  = number_format((float)$service->sub_total, 2, '.', '');
                $row[]  = number_format((float)$service->service_fees, 2, '.', '');
                $row[]  = number_format((float)$service->paid_to_guider, 2, '.', '');
                
                $data[] = $row;
                $si_no++;
            }
        }
        $results = array(
                        "draw"              => $_POST['draw'],
                        "recordsTotal"      => $this->Qrscandonatemodel->count_all($where),
                        "recordsFiltered"   => $this->Qrscandonatemodel->count_filtered($where),
                        "data"              => $data
                );
        echo json_encode($results);
    }
    function exportExcelForm() {
        $data1[ 'guider_lists'] = $guider_lists = $this->Guidermodel->guider_lists( false, 1 );
        echo $this->load->view( 'qrscan/exportexcelform', $data1, true );
    }
    public function bookings_export()
    {
        error_reporting(E_ALL);
        $this->load->library('excel');
        $data1['guider_id']     = $this->input->get('guider_id');
        $data1['user_random_id']= $this->input->get('user_random_id');
        $data1['start_date']    = $this->input->get('start_date');
        $data1['end_date']      = $this->input->get('end_date');
        $data1['booking_lists'] = $this->Qrscandonatemodel->completedBookings_export($data1);
        if($data1['booking_lists']){
            qrscan_donate_export($data1);
        }else{
            $this->session->set_flashdata('successMSG', 'No data found');
            redirect( $this->config->item( 'admin_url' ) . 'qrscandonate' );
        }
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
