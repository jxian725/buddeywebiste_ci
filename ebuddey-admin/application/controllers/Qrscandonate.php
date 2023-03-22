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
    public function donationTableResponse(){

        $data           = array();
        $results        = array();
        $where          = array();
        $donationLists   = $this->Qrscandonatemodel->get_datatables($where);
        if($_POST['start']){
            $si_no  = $_POST['start']+1;
        }else{
            $si_no  = 1;
        }
        $data = array();
        if($donationLists){
            foreach ($donationLists as $donation) {
                if ($donation->is_paid == 0) {
                    $status    = 'Pending';
                    $statusbtn = '<a href="javascript:;" onClick="return updateDonationStatus('.$donation->payment_id.', 1);" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="Click to Paid"><i class="fa fa-toggle-off" aria-hidden="true"></i></a>';
                }elseif($donation->is_paid == 1){
                    $status    = 'Paid';
                    $statusbtn = '<a href="javascript:;" onClick="return updateDonationStatus('.$donation->payment_id.', 0);" class="btn btn-success btn-xs" data-toggle="tooltip" data-original-title="Click to Pending"><i class="fa fa-toggle-on" aria-hidden="true"></i></a>';
                }else{
                    $status     = '';
                    $statusbtn  = '';
                }

                $row    = array();
                $row[]  = $si_no;
                $row[]  = $donation->talentDisplayName;
                $row[]  = $donation->talentEmail;
                $row[]  = date('d M Y', strtotime($donation->pay_updated));
                $row[]  = $donation->fullName;
                $row[]  = $donation->transaction_id;
                $row[]  = number_format((float)$donation->transaction_amount, 2, '.', '');
                $row[]  = '0.00';
                $row[]  = number_format((float)$donation->sub_total, 2, '.', '');
                $row[]  = $status;
                $row[]  = $statusbtn;
                $row[]  = (($donation->is_paid_updated_date)? date('d M Y', strtotime($donation->is_paid_updated_date)) : date('d M Y', strtotime($donation->pay_updated)));

                
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

    function updateDonationStatus(){
        $payment_id  = $this->input->post( 'payment_id' );
        $status      = $this->input->post( 'status' );
        $updateData  = array('is_paid' => $status, 'is_paid_updated_date' => date("Y-m-d H:i:s"));
        $this->Qrscandonatemodel->updateDonation( $payment_id, $updateData );
        echo json_encode( array('status' => 1) );
    }

    function exportExcelForm() {
        $data1[ 'guider_lists'] = $guider_lists = $this->Guidermodel->guider_lists( false, 1 );
        echo $this->load->view( 'qrscan/exportexcelform', $data1, true );
    }
    public function bookings_export()
    {
        error_reporting(E_ALL);
        $this->load->library('excel');
        $excelData['guider_id']     = $this->input->get('guider_id');
        $excelData['user_random_id']= $this->input->get('user_random_id');
        $excelData['start_date']    = $this->input->get('start_date');
        $excelData['end_date']      = $this->input->get('end_date');
        $excelData['booking_lists'] = $this->Qrscandonatemodel->completedBookings_export($excelData);
        //print_r($excelData['booking_lists']);
        //exit;
        if($excelData['booking_lists']){
            qrscan_donate_export($excelData);
        }else{
            $this->session->set_flashdata('successMSG', 'No data found');
            redirect( $this->config->item( 'admin_url' ) . 'qrscandonate' );
        }
    }
	function template( $data ){
        $this->load->view( 'common/templatecontent', $data );
    }
}
